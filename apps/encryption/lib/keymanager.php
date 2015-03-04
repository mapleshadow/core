<?php
/**
 * @author Clark Tomlinson  <clark@owncloud.com>
 * @since 2/19/15, 1:20 PM
 * @copyright Copyright (c) 2015, ownCloud, Inc.
 * @license AGPL-3.0
 *
 * This code is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License, version 3,
 * as published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License, version 3,
 * along with this program.  If not, see <http://www.gnu.org/licenses/>
 *
 */

namespace OCA\Encryption;


use OC\Files\View;

class KeyManager {

	/**
	 * @var View
	 */
	private $view;

	/**
	 * @var string
	 */
	private $keysBaseDir = '/encryption/keys/';
	/**
	 * @var string
	 */
	private $encryptionBaseDir = '/encryption';
	/**
	 * @var string
	 */
	private $publicKeyDir = 'publicKeys/';
	/**
	 * @var string
	 */
	private $privateKeyDir = 'privateKeys/';
	/**
	 * @var Crypt
	 */
	private $crypt;

	public function __construct(View $view, Crypt $crypt) {

		$this->view = $view;
		$this->crypt = $crypt;
	}

	/**
	 * Check if a recovery key exists
	 *
	 * @param $recoveryKeyId
	 * @return bool
	 */
	public function recoveryKeyExists($recoveryKeyId) {
		if ($recoveryKeyId) {
			$result = ($this->view->file_exists($this->publicKeyDir . '/' . $recoveryKeyId . ".publicKey")
				&& $this->view->file_exists($this->encryptionBaseDir . '/' . $recoveryKeyId . ".privateKey"));
			return $result;
		}
		return false;
	}

	public function getPrivateKey($keyId) {
		$this->view->file_exists($this->keysBaseDir . $this->privateKeyDir . $keyId);
	}

	public function getPublicKey($keyId) {
		if ($this->view->file_exists($this->keysBaseDir . $this->publicKeyDir . $keyId)) {
			return \OC::$server->getEncryptionKeyStorage();
		}
	}

	public function checkRecoveryPassword($user, $keyID, $password) {
		$keyStore = \OC::$server->getEncryptionKeyStorage();

		$recoveryKey = $keyStore->getSystemUserKey($user, $keyID);
		$decryptedRecoveryKey = $this->crypt->decryptPrivateKey($recoveryKey, $password);

		if ($decryptedRecoveryKey) {
			return true;
		}
		return false;
	}

}
