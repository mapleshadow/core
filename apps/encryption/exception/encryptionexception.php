<?php
/**
 * @author Clark Tomlinson  <clark@owncloud.com>
 * @since 2/20/15, 11:51 AM
 * @copyright 2015 ownCloud, Inc.
 *
 * This file is licensed under the Affero General Public License version 3 or later.
 * See the COPYING-README file.
 */

namespace OCA\Encryption\Exception;

/**
 * Base class for all encryption exception
 *
 * Possible Error Codes:
 * 10 - generic error
 * 20 - unexpected end of encryption header
 * 30 - unexpected blog size
 * 40 - encryption header to large
 * 50 - unknown cipher
 * 60 - encryption failed
 * 70 - decryption failed
 * 80 - empty data
 * 90 - private key missing
 */
class EncryptionException extends \Exception {

	const GENERIC = 10;
	const UNEXPECTED_END_OF_ENCRYPTION_HEADER = 20;
	const UNEXPECTED_BLOCK_SIZE = 30;
	const ENCRYPTION_HEADER_TO_LARGE = 40;
	const UNKNOWN_CIPHER = 50;
	const ENCRYPTION_FAILED = 60;
	const DECRYPTION_FAILED = 70;
	const EMPTY_DATA = 80;
	const PRIVATE_KEY_MISSING = 90;

}
