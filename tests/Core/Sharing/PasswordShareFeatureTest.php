<?php

/**
 * SPDX-FileCopyrightText: 2026 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

declare(strict_types=1);


namespace Tests\Core\Sharing;

use OC\Core\Sharing\PasswordShareFeature;
use OCP\Security\IHasher;
use OCP\Server;
use Test\TestCase;

class PasswordShareFeatureTest extends TestCase {
	private PasswordShareFeature $feature;

	public function setUp(): void {
		parent::setUp();

		$this->feature = new PasswordShareFeature();
	}

	public function testValidateProperties(): void {
		$hash = Server::get(IHasher::class)->hash('123');

		$this->assertTrue($this->feature->validateProperties(['hash' => [$hash]]));

		$this->assertFalse($this->feature->validateProperties([]));
		$this->assertFalse($this->feature->validateProperties(['a' => []]));
		$this->assertFalse($this->feature->validateProperties(['hash' => [$hash], 'a' => ['']]));
		$this->assertFalse($this->feature->validateProperties(['hash' => []]));
		$this->assertFalse($this->feature->validateProperties(['hash' => ['']]));
		$this->assertFalse($this->feature->validateProperties(['hash' => [$hash, $hash]]));
	}

	public function testIsFiltered(): void {
		$this->assertFalse($this->feature->isFiltered(null, '123', ['hash' => [Server::get(IHasher::class)->hash('123')]]));
		$this->assertTrue($this->feature->isFiltered(null, '456', ['hash' => [Server::get(IHasher::class)->hash('123')]]));
		$this->assertTrue($this->feature->isFiltered(null, null, ['hash' => [Server::get(IHasher::class)->hash('123')]]));
	}
}
