<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2026 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace OC\Core\Sharing;

use Exception;
use OCA\Files\Sharing\NodeShareSourceType;
use OCA\Sharing\Model\IShareFeature;
use OCA\Sharing\Model\IShareFeatureFilter;
use OCP\IUser;
use OCP\Security\IHasher;
use OCP\Server;

// TODO: Hash password automatically on share creation
class PasswordShareFeature implements IShareFeatureFilter, IShareFeature {
	public function getCompatibles(): array {
		$compatibles = [];
		foreach ([NodeShareSourceType::class] as $sourceType) {
			foreach ([TokenShareRecipientType::class] as $recipientType) {
				$compatibles[] = [
					'source_type' => $sourceType,
					'recipient_type' => $recipientType,
				];
			}
		}

		return $compatibles;
	}

	public function validateProperties(array $properties): bool {
		if (array_keys($properties) !== ['hash'] || count($properties['hash']) !== 1) {
			return false;
		}

		return Server::get(IHasher::class)->validate($properties['hash'][0]);
	}

	/**
	 * @throws Exception
	 */
	public function isFiltered(?IUser $currentUser, mixed $arguments, array $properties): bool {
		if (!is_string($arguments)) {
			return true;
		}

		// TODO: Check if the hash has to be updated and save it.
		return !Server::get(IHasher::class)->verify($arguments, $properties['hash'][0]);
	}
}
