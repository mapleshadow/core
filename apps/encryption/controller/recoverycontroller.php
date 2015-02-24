<?php
/**
 * @author Clark Tomlinson  <clark@owncloud.com>
 * @since 2/19/15, 11:25 AM
 * @copyright 2015 ownCloud, Inc.
 *
 * This file is licensed under the Affero General Public License version 3 or later.
 * See the COPYING-README file.
 */

namespace OCA\Encryption\Controller;


use OCA\Encryption\Recovery;
use OCP\AppFramework\Controller;
use OCP\IRequest;
use OCP\JSON;
use Symfony\Component\HttpFoundation\JsonResponse;

class RecoveryController extends Controller {
	protected $di;

	/**
	 * @param string $AppName
	 * @param IRequest $request
	 */
	public function __construct($AppName, IRequest $request) {
		$this->di = \OC::$server;
		parent::__construct($AppName, $request);
		$this->l = $this->di->getL10N($AppName);
	}

	public function adminRecovery($recoveryPassword, $confirmPassword, $adminEnableRecovery) {
		$return = false;
		$errorMessage = $this->l->t('Unknown Error');

		// Check if both passwords are the same
		if (empty($recoveryPassword)) {
			$errorMessage = $this->l->t('Missing recovery key password');
			return new JsonResponse(['data' => ['message' => $errorMessage]], 500);
		}

		if (empty($confirmPassword)) {
			$errorMessage = $this->l->t('Please repeat the recovery key password');
			return new JsonResponse(['data' => ['message' => $errorMessage]], 500);
		}

		if ($recoveryPassword !== $confirmPassword) {
			$errorMessage = $this->l->t('Repeated recovery key password does not match the provided recovery key password');
			return new JsonResponse(['data' => ['message' => $errorMessage]], 500);
		}

		// Enable recoveryAdmin
		$recoveryKeyId = $this->di->getConfig()->getAppValue('encryption', 'recoveryKeyId');

		if (isset($adminEnableRecovery) && $adminEnableRecovery === '1') {
			if ((new Recovery())->enableAdminRecovery($recoveryKeyId, $recoveryPassword)) {
				return new JsonResponse(['data' => array('message' => $this->l->t('Recovery key successfully enabled'))]);
			}
			return new JsonResponse(['data' => array('message' => $this->l->t('Could not enable recovery key. Please check your recovery key password!'))]);
		} elseif (isset($adminEnableRecovery) && $adminEnableRecovery === '0') {
			if ((new Recovery())->disableAdminRecovery($recoveryKeyId, $recoveryPassword)) {
				return new JsonResponse(['data' => array('message' => $this->l->t('Recovery key successfully disabled'))]);
			}
			return new JsonResponse(['data' => array('message' => $this->l->t('Could not disable recovery key. Please check your recovery key password!'))]);
		}
	}

	public function userRecovery() {

	}

}
