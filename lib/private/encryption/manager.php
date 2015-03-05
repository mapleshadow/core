<?php

/**
 * ownCloud
 *
 * @copyright (C) 2015 ownCloud, Inc.
 *
 * @author Bjoern Schiessle <schiessle@owncloud.com>
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU AFFERO GENERAL PUBLIC LICENSE
 * License as published by the Free Software Foundation; either
 * version 3 of the License, or any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU AFFERO GENERAL PUBLIC LICENSE for more details.
 *
 * You should have received a copy of the GNU Affero General Public
 * License along with this library.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace OC\Encryption;

use OCP\Encryption\IEncryptionModule;

class Manager implements \OCP\Encryption\IManager {

	/** @var array */
	protected $encryptionModules;

	/** @var \OCP\IConfig */
	protected $config;

	/**
	 * @param \OCP\IConfig $config
	 */
	public function __construct(\OCP\IConfig $config) {
		$this->encryptionModules = array();
		$this->config = $config;
	}

	/**
	 * Check if encryption is available (at least one encryption module needs to be enabled)
	 *
	 * @return bool true if enabled, false if not
	 */
	public function isEnabled() {
		$enabled = $this->config->getSystemValue('encryption_enabled', false);
		$modules = $this->getEncryptionModules();
		if ($enabled && !empty($modules)) {
			return true;
		}
		return false;
	}

	/**
	 * Registers an encryption module
	 *
	 * @param IEncryptionModule $module
	 * @throws Exceptions\ModuleAlreadyExistsException
	 */
	public function registerEncryptionModule(IEncryptionModule $module) {

		if (!empty($this->encryptionModules)) {
			$message = 'At the moment it is not allowed to register more than one encryption module';
			throw new Exceptions\ModuleAlreadyExistsException($message);
		}

		$id = $module->getId();
		$name = $module->getDisplayName();
		$this->encryptionModules[$id] = $module;
	}

	/**
	 * Unregisters an encryption module
	 *
	 * @param IEncryptionModule $module
	 * @return void
	 */
	public function unregisterEncryptionModule(IEncryptionModule $module) {
		unset($this->encryptionModules[$module->getId()]);
	}

	/**
	 * get a list of all encryption modules
	 *
	 * @return IEncryptionModule[]
	 */
	public function getEncryptionModules() {
		return $this->encryptionModules;
	}

	/**
	 * get a specific encryption module
	 *
	 * @param string $moduleId
	 * @return IEncryptionModule
	 * @throws Exceptions\ModuleDoesNotExistsException
	 */
	public function getEncryptionModule($moduleId) {
		if (isset($this->encryptionModules[$moduleId])) {
			return $this->encryptionModules[$moduleId];
		} else {
			$message = "Module with id: $moduleId does not exists.";
			throw new Exceptions\ModuleDoesNotExistsException($message);
		}
	}

}
