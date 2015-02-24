<?php
/**
 * @author Clark Tomlinson  <clark@owncloud.com>
 * @since 2/19/15, 9:52 AM
 * @copyright 2015 ownCloud, Inc.
 *
 * This file is licensed under the Affero General Public License version 3 or later.
 * See the COPYING-README file.
 */

use OCA\Encryption\AppInfo\Encryption;
use OCA\Encryption\HookManager;
use OCA\Encryption\Hooks\AppHooks;
use OCA\Encryption\Hooks\FileSystemHooks;
use OCA\Encryption\Hooks\ShareHooks;
use OCA\Encryption\Hooks\UserHooks;
use OCP\App;

script('encryption', 'encryption');
script('encryption', 'detect-migration');


$ioc = \OC::$server;
$config = $ioc->getConfig();
// Lets register this encryption module
$encryptionModule = $ioc->getEncryptionManager()->registerEncryptionModule(new Encryption());

if ($config->getSystemValue('maintenance', false)) {
	OC_FileProxy::register(new OCA\Encryption\Proxy());


	// Register our hooks and fire them.
	$hookManager = new HookManager();

	$hookManager->registerHook([
		new UserHooks(),
		new ShareHooks(),
		new FileSystemHooks(),
		new AppHooks()
	]);

	$hookManager->fireHooks();

	if (!in_array('crypt', stream_get_wrappers())) {
		stream_wrapper_register('crypt', 'OCA\Encryption\Stream');
	}
} else {
	// Logout user if we are in maintenance to force re-login
	$ioc->getUserSession()->logout();
}

// Register settings scripts
App::registerAdmin('encryption', 'settings/settings-admin');
App::registerPersonal('encryption', 'settings/settings-personal');
