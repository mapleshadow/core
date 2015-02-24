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

class FileSystemHooks implements IHook {

	/**
	 * Connects Hooks
	 *
	 * @return null
	 */
	public function addHooks() {
		Util::connectHook('OC_Filesystem', 'rename', 'OCA\Encryption\Hooks', 'preRename');
		Util::connectHook('OC_Filesystem', 'post_rename', 'OCA\Encryption\Hooks', 'postRenameOrCopy');
		Util::connectHook('OC_Filesystem', 'copy', 'OCA\Encryption\Hooks', 'preCopy');
		Util::connectHook('OC_Filesystem', 'post_copy', 'OCA\Encryption\Hooks', 'postRenameOrCopy');
		Util::connectHook('OC_Filesystem', 'post_delete', 'OCA\Encryption\Hooks', 'postDelete');
		Util::connectHook('OC_Filesystem', 'delete', 'OCA\Encryption\Hooks', 'preDelete');
		Util::connectHook('\OC\Core\LostPassword\Controller\LostController', 'post_passwordReset', 'OCA\Encryption\Hooks', 'postPasswordReset');
		Util::connectHook('OC_Filesystem', 'post_umount', 'OCA\Encryption\Hooks', 'postUnmount');
		Util::connectHook('OC_Filesystem', 'umount', 'OCA\Encryption\Hooks', 'preUnmount');
	}
}
