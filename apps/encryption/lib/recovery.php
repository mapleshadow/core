<?php
/**
 * @author Clark Tomlinson  <clark@owncloud.com>
 * @since 2/19/15, 11:45 AM
 * @copyright 2015 ownCloud, Inc.
 *
 * This file is licensed under the Affero General Public License version 3 or later.
 * See the COPYING-README file.
 */

namespace OCA\Encryption;


use OC\Files\View;

class Recovery {


	/**
	 * @var \OC\Server
	 */
	protected $di;
	/**
	 * @var null|\OCP\IUser
	 */
	protected $user;
	/**
	 * @var Crypt
	 */
	protected $crypt;

	public function __construct() {
		$this->di = \OC::$server;
		$this->user = $this->di->getUserSession()->getUser();
		$this->crypt = new Crypt();
	}

	public function enableAdminRecovery($recoveryKeyId, $password) {
		$view = new View('/');
		$appConfig = $this->di->getConfig();

		if ($recoveryKeyId === null) {
			$recoveryKeyId = $this->di->getSecureRandom()->getLowStrengthGenerator();
			$appConfig->setAppValue('encryption', 'recoveryKeyId', $recoveryKeyId);
		}

		$keyManager = new Keymanager($view);

		$keyStorage = $this->di->getEncryptionKeyStorage();

		if (!$keyManager->recoveryKeyExists($recoveryKeyId)) {
			$keyPair = $this->crypt->createKeyPair();

			// Save Public Key
			$keyStorage->setUserKey($this->user->getUID(), $recoveryKeyId, $keyPair['publicKey']);

			$encryptedKey = $this->crypt->symmetricEncryptFileContent($keyPair['privateKey'], $password);
			if ($encryptedKey) {
				$keyStorage->setUserKey($this->user->getUID(), $recoveryKeyId, $encryptedKey);
				$appConfig->setAppValue('encryption', 'recoveryAdminEnabled', 1);
				return true;
			}
		}

		if ($keyManager->checkRecoveryPassword($this->user->getUID(), $recoveryKeyId, $password)) {
			$appConfig->setAppValue('encryption', 'recoveryAdminEnabled', 1);
			return true;
		}

		return false;
	}

	public function disableAdminRecovery($recoveryKeyId, $recoveryPassword) {
		// Todo use DI
		$view = new View('/');

		// todo use DI
		$keyManager = new KeyManager($view);

		if ($keyManager->checkRecoveryPassword($this->user->getUID(), $recoveryKeyId, $recoveryPassword)) {
			// Set recoveryAdmin as disabled
			$this->di->getConfig()->setAppValue('encryption', 'recoveryAdminEnabled', 0);
			return true;
		}
		return false;
	}

}
