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

class ShareHooks implements IHook {

	/**
	 * Connects Hooks
	 *
	 * @return null
	 */
	public function addHooks() {
		Util::connectHook('OCP\Share', 'pre_shared', 'OCA\Encryption\Hooks', 'preShared');
		Util::connectHook('OCP\Share', 'post_shared', 'OCA\Encryption\Hooks', 'postShared');
		Util::connectHook('OCP\Share', 'post_unshare', 'OCA\Encryption\Hooks', 'postUnshare');
	}
}
