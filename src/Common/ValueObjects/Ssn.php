<?php

declare(strict_types=1);

namespace Common\ValueObjects;

use Webmozart\Assert\Assert;

final readonly class Ssn
{
    public const string PATTERN = '/^\d{3}-\d{2}-\d{4}$/';

    /** @param non-empty-string $value */
    public static function fromString(string $value): self
    {
        $value = trim($value);
        Assert::regex($value, self::PATTERN);

        return new self((int) preg_replace('/-/', '', $value));
    }

    /** @param positive-int $value */
    public function __construct(private int $value)
    {
    }

    public function toString(): string
    {
        $str = (string) $this->value;

        return substr($str, 0, 3) . '-' . substr($str, 3, 2) . '-' . substr($str, 5);
    }

    public function toInt(): int
    {
        return $this->value;
    }
}