<?php
/**
 * @author Clark Tomlinson  <clark@owncloud.com>
 * @since 2/19/15, 11:22 AM
 * @copyright 2015 ownCloud, Inc.
 *
 * This file is licensed under the Affero General Public License version 3 or later.
 * See the COPYING-README file.
 */


use OCP\AppFramework\App;

(new App('encryption'))->registerRoutes($this, array('routes' => array(

	[
		'name' => 'recovery#adminRecovery',
		'url' => '/ajax/adminRecovery',
		'verb' => 'POST'
	],
	[
		'name' => 'recovery#userRecovery',
		'url' => '/ajax/userRecovery',
		'verb' => 'POST'
	]


)));
