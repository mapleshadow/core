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

class AppHooks implements IHook {
	/**
	 * Connects Hooks
	 *
	 * @return null
	 */
	public function addHooks() {
		Util::connectHook('OC_App', 'pre_disable', 'OCA\Encryption\Hooks', 'preDisable');
		Util::connectHook('OC_App', 'post_disable', 'OCA\Encryption\Hooks', 'postEnable');
	}
}
