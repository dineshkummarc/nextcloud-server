<?php

/**
 * SPDX-FileCopyrightText: 2025 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

declare(strict_types=1);


namespace Core\Sharing;

use OC\Core\Sharing\TokenShareRecipientType;
use Test\TestCase;

class TokenShareRecipientTypeTest extends TestCase {
	private TokenShareRecipientType $recipientType;

	public function setUp(): void {
		parent::setUp();

		$this->recipientType = new TokenShareRecipientType();
	}

	public function testValidateRecipient(): void {
		/** @psalm-suppress InvalidArgument */
		$this->assertFalse($this->recipientType->validateRecipient(''));
		$this->assertFalse($this->recipientType->validateRecipient('#'));
		$this->assertTrue($this->recipientType->validateRecipient('a-1'));
	}

	public function testGetRecipientValues(): void {
		$this->assertEquals([], $this->recipientType->getRecipientValues(null, null));
		$this->assertEquals([], $this->recipientType->getRecipientValues(null, 1));
		$this->assertEquals([''], $this->recipientType->getRecipientValues(null, ''));
		$this->assertEquals(['abc'], $this->recipientType->getRecipientValues(null, 'abc'));
	}
}
