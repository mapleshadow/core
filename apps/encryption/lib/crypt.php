<?php
/**
 * @author Clark Tomlinson  <clark@owncloud.com>
 * @since 2/19/15, 1:42 PM
 * @copyright 2015 ownCloud, Inc.
 *
 * This file is licensed under the Affero General Public License version 3 or later.
 * See the COPYING-README file.
 */

namespace OCA\Encryption;


use OCA\Encryption\Exception\EncryptionException;

class Crypt {

	const ENCRYPTION_UKNOWN_ERROR = -1;
	const ENCRYPTION_NOT_INIALIZED_ERROR = 1;
	const ENCRYPTIION_PRIVATE_KEY_NOT_VALID_ERROR = 2;
	const ENCRYPTION_NO_SHARE_KEY_FOUND = 3;

	const BLOCKSIZE = 8192;
	const DEFAULT_CIPHER = 'AES-256-CFB';

	const HEADERSTART = 'HBEGIN';
	const HEADEREND = 'HEND';

	public function __construct() {
		$this->di = \OC::$server;
	}

	public function mode($user = null) {
		return 'server';
	}

	/**
	 *
	 */
	public function createKeyPair() {

		$log = $this->di->getLogger();
		$res = $this->getOpenSSLPKey();

		if (!$res) {
			$user = $this->di->getUserSession()->getUser();
			$log->error("Encryption Library could'nt generate users key-pair for {$user->getUID()}", ['app' => 'encryption']);

			if (openssl_error_string()) {
				$log->error('Encryption library openssl_pkey_new() fails: ' . openssl_error_string(), ['app' => 'encryption']);
			}
		} elseif (openssl_pkey_export($res, $privateKey, null, $this->getOpenSSLConfig())) {
			$keyDetails = openssl_pkey_get_details($res);
			$publicKey = $keyDetails['key'];

			return [
				'publicKey' => $publicKey,
				'privateKey' => $privateKey
			];
		}
		$log->error('Encryption library couldn\'t export users private key, please check your servers openSSL configuration.' . $user->getUID(), ['app' => 'encryption']);
		if (openssl_error_string()) {
			$log->error('Encryption Library:' . openssl_error_string(), ['app' => 'encryption']);
		}

		return false;
	}

	/**
	 * @return resource
	 */
	private function getOpenSSLPKey() {
		$config = $this->getOpenSSLConfig();
		return openssl_pkey_new($config);
	}

	/**
	 * @return array
	 */
	private function getOpenSSLConfig() {
		$config = ['private_key_bits' => 4096];
		$config = array_merge(\OC::$server->getConfig()->getSystemValue('openssl', []), $config);
		return $config;
	}

	public function symmetricEncryptFileContent($plainContent, $passphrase) {

		if (!$plainContent) {
			$this->di->getLogger()->error('Encryption Library, symmetrical encryption failed no content given', ['app' => 'encryption']);
			return false;
		}

		$iv = $this->generateIv();

		try {
			$encryptedContent = $this->encrypt($plainContent, $iv, $passphrase, $this->getCipher());
			// combine content to encrypt the IV identifier and actual IV
			$catFile = $this->concatIV($encryptedContent, $iv);
			$padded = $this->addPadding($catFile);

			return $padded;
		} catch (EncryptionException $e) {
			$message = 'Could not encrypt file content (code: ' . $e->getCode() . '): ';
			$this->di->getLogger()->error('files_encryption' . $message . $e->getMessage(), ['app' => 'encryption']);
			return false;
		}

	}

	private function encrypt($plainContent, $iv, $passphrase = '', $cipher = self::DEFAULT_CIPHER) {
		$encryptedContent = openssl_encrypt($plainContent, $cipher, $passphrase, false, $iv);

		if (!$encryptedContent) {
			$error = 'Encryption (symmetric) of content failed';
			$this->di->getLogger()->error($error . openssl_error_string(), ['app' => 'encryption']);
			throw new EncryptionException($error, EncryptionException::ENCRYPTION_FAILED);
		}

		return $encryptedContent;
	}

	public function getCipher() {
		$cipher = $this->di->getConfig()->getSystemValue('cipher', self::DEFAULT_CIPHER);
		if ($cipher !== 'AES-256-CFB' || $cipher !== 'AES-128-CFB') {
			$this->di
				->getLogger()
				->warning('Wrong cipher defined in config.php only AES-128-CFB and AES-256-CFB are supported. Fall back' . self::DEFAULT_CIPHER, ['app' => 'encryption']);
			$cipher = self::DEFAULT_CIPHER;
		}

		return $cipher;
	}

	private function concatIV($encryptedContent, $iv) {
		return $encryptedContent . '00iv00' . $iv;
	}

	private function addPadding($data) {
		return $data . 'xx';
	}

	public function decryptPrivateKey($recoveryKey, $password) {

		$header = $this->parseHeader($recoveryKey);
		$cipher = $this->getCipher($header);

		// If we found a header we need to remove it from the key we want to decrypt
		if (!empty($header)) {
			$recoveryKey = substr($recoveryKey, strpos($recoveryKey, self::HEADEREND) + strlen(self::HEADERSTART));
		}

		$plainKey = $this->symmetricDecryptFileContent($recoveryKey, $password, $cipher);

		// Check if this is a valid private key
		$res = openssl_get_privatekey($plainKey);
		if (is_resource($res)) {
			$sslInfo = openssl_pkey_get_details($res);
			if (!isset($sslInfo['key'])) {
				return false;
			}
		} else {
			return false;
		}

		return $plainKey;
	}

	public function symmetricDecryptFileContent($keyFileContents, $passphrase = '', $cipher = self::DEFAULT_CIPHER) {
		// Remove Padding
		$noPadding = $this->removePadding($keyFileContents);

		$catFile = $this->splitIv($noPadding);

		$plainContent = $this->decrypt($catFile['encrypted'], $catFile['iv'], $passphrase, $cipher);

		if ($plainContent) {
			return $plainContent;
		}

		return false;
	}

	private function removePadding($padded) {
		if (substr($padded, -2) === 'xx') {
			return substr($padded, 0, -2);
		}
		return false;
	}

	private function splitIv($catFile) {
		// Fetch encryption metadata from end of file
		$meta = substr($catFile, -22);

		// Fetch IV from end of file
		$iv = substr($meta, -16);

		// Remove IV and IV Identifier text to expose encrypted content

		$encrypted = substr($catFile, 0, -22);

		return [
			'encrypted' => $encrypted,
			'iv' => $iv
		];
	}

	private function decrypt($encryptedContent, $iv, $passphrase = '', $cipher = self::DEFAULT_CIPHER) {
		$plainContent = openssl_decrypt($encryptedContent, $cipher, $passphrase, false, $iv);

		if ($plainContent) {
			return $plainContent;
		} else {
			throw new EncryptionException('Encryption library: Decryption (symmetric) of content failed');
		}
	}

	private function parseHeader($data) {
		$result = [];

		if (substr($data, 0, strlen(self::HEADERSTART)) === self::HEADERSTART) {
			$endAt = strpos($data, self::HEADEREND);
			$header = substr($data, 0, $endAt + strlen(self::HEADEREND));

			// +1 not to start with an ':' which would result in empty element at the beginning
			$exploded = explode(':', substr($header, strlen(self::HEADERSTART) + 1));

			$element = array_shift($exploded);

			while ($element != self::HEADEREND) {
				$result[$element] = array_shift($exploded);
				$element = array_shift($exploded);
			}
		}

		return $result;
	}
}

