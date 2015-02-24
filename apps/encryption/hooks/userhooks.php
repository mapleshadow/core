<?php
/**
 * @author Clark Tomlinson  <clark@owncloud.com>
 * @since 2/19/15, 10:02 AM
 * @copyright 2015 ownCloud, Inc.
 *
 * This file is licensed under the Affero General Public License version 3 or later.
 * See the COPYING-README file.
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
