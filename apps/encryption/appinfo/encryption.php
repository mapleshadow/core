<?php
/**
 * @author Clark Tomlinson  <clark@owncloud.com>
 * @since 2/19/15, 9:59 AM
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

namespace OCA\Encryption\AppInfo;


use OCP\Encryption\IEncryptionModule;

class Encryption implements IEncryptionModule {

	/**
	 * @return string defining the technical unique key
	 */
	public function getKey() {
		// TODO: Implement getKey() method.
	}

	/**
	 * In comparison to getKey() this function returns a human readable (maybe translated) name
	 *
	 * @return mixed
	 */
	public function getDisplayName() {
		// TODO: Implement getDisplayName() method.
	}

	/**
	 * start receiving chungs from a file. This is the place where you can
	 * perfom some initial step before starting encrypting/decrypting the
	 * chunks
	 *
	 * @param string $path to the file
	 * @param array $header contains the header data read from the file
	 *
	 * $return array $header optional in case of a write operation the array
	 *                       contain data which should be written to the header
	 */
	public function begin($path, $header) {
		// TODO: Implement begin() method.
	}

	/**
	 * last chunk received. This is the place where you can perform some final
	 * operation and return some remaining data if something is left in your
	 * buffer.
	 *
	 * @param string $path to the file
	 */
	public function end($path) {
		// TODO: Implement end() method.
	}

	/**
	 * encrypt data
	 *
	 * @param string $data you want to encrypt
	 * @param array $users list of users who should be able to access the file
	 * @param array $groups list of groups which should be able to access the file
	 * @return mixed encrypted data
	 */
	public function encrypt($data, $users, $groups) {
		// TODO: Implement encrypt() method.
	}

	/**
	 * decrypt data
	 *
	 * @param string $data you want to decrypt
	 * @param string $user decrypt as user
	 * @return mixed decrypted data
	 */
	public function decrypt($data, $user) {
		// TODO: Implement decrypt() method.
	}

	/**
	 * update encrypted file, e.g. give additional users access to the file
	 *
	 * @param string $path path to the file which should be updated
	 * @param string $users list of user who should have access to the file
	 * @return boolean
	 */
	public function update($path, $users, $groups) {
		// TODO: Implement update() method.
	}

	/**
	 * should the file be encrypted or not
	 *
	 * @param string $path
	 * @return boolean
	 */
	public function shouldEncrypt($path) {
		// TODO: Implement shouldEncrypt() method.
	}

	/**
	 * calculate unencrypted size
	 *
	 * @param string $path to file
	 * @return integer unencrypted size
	 */
	public function calculateUnencryptedSize($path) {
		// TODO: Implement calculateUnencryptedSize() method.
	}
}
