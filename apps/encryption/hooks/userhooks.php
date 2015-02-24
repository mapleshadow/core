<?php
/**
 * @author Clark Tomlinson  <clark@owncloud.com>
 * @since 2/19/15, 10:02 AM
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

namespace OCA\Encryption\Hooks;


use OCA\Encryption\Hooks\Contracts\IHook;
use OCP\Util;

class UserHooks implements IHook {

	/**
	 * Connects Hooks
	 *
	 * @return null
	 */
	public function addHooks() {
		Util::connectHook('OC_User', 'post_login', 'OCA\Encryption\Hooks', 'login');
		Util::connectHook('OC_User', 'logout', 'OCA\Encryption\Hooks', 'logout');
		Util::connectHook('OC_User', 'post_setPassword', 'OCA\Encryption\Hooks', 'setPassphrase');
		Util::connectHook('OC_User', 'pre_setPassword', 'OCA\Encryption\Hooks', 'preSetPassphrase');
		Util::connectHook('OC_User', 'post_createUser', 'OCA\Encryption\Hooks', 'postCreateUser');
		Util::connectHook('OC_User', 'post_deleteUser', 'OCA\Encryption\Hooks', 'postDeleteUser');
	}
}
