<?php
/**
 * @author Clark Tomlinson  <clark@owncloud.com>
 * @since 2/19/15, 10:03 AM
 * @copyright 2015 ownCloud, Inc.
 *
 * This file is licensed under the Affero General Public License version 3 or later.
 * See the COPYING-README file.
 */

namespace OCA\Encryption\Hooks\Contracts;


interface IHook {
	/**
	 * Connects Hooks
	 *
	 * @return null
	 */
	public function addHooks();
}
