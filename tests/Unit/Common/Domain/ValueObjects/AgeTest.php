<?php

declare(strict_types=1);

namespace Tests\Unit\Common\Domain\ValueObjects;

use Common\ValueObjects\Age;
use PHPUnit\Framework\TestCase;

final class AgeTest extends TestCase
{
    public function testAgeSuccess(): void
    {
        $age = new Age(new \DateTimeImmutable('2000-01-01'), new \DateTimeImmutable('2025-01-01'));
        $this->assertInstanceOf(Age::class, $age);
        $this->assertTrue($age->isSolvent());

        $age = new Age(new \DateTimeImmutable('2010-01-01'), new \DateTimeImmutable('2025-01-01'));
        $this->assertInstanceOf(Age::class, $age);
        $this->assertFalse($age->isSolvent());

        $age = new Age(new \DateTimeImmutable('1965-01-01'), new \DateTimeImmutable('2025-01-01'));
        $this->assertInstanceOf(Age::class, $age);
        $this->assertFalse($age->isSolvent());
    }

    public function testAgeError(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Expected a value less than DateTimeImmutable: "2025-01-01T00:00:00+00:00". Got: DateTimeImmutable: "2026-01-01T00:00:00+00:00"');
        new Age(new \DateTimeImmutable('2026-01-01'), new \DateTimeImmutable('2025-01-01'));
    }
}