<?php
/**
 * @author Clark Tomlinson  <clark@owncloud.com>
 * @since 2/19/15, 10:13 AM
 * @copyright 2015 ownCloud, Inc.
 *
 * This file is licensed under the Affero General Public License version 3 or later.
 * See the COPYING-README file.
 */

namespace OCA\Encryption;


use OCA\Encryption\Hooks\Contracts\IHook;

class HookManager {

	private $hookInstances = [];

	/**
	 * @param array|IHook $instances
	 *        - This accepts either a single instance of IHook or an array of instances of IHook
	 * @return bool
	 */
	public function registerHook($instances) {
		if (is_array($instances)) {
			foreach ($instances as $instance) {
				if (!$instance instanceof IHook) {
					return false;
				}
				$this->hookInstances[] = $instance;
				return true;
			}

		}
		$this->hookInstances[] = $instances;
		return true;
	}

	/**
	 *
	 */
	public function fireHooks() {
		foreach ($this->hookInstances as $instance) {
			/**
			 * @var $instance IHook
			 */
			$instance->addHooks();
		}

	}

}
