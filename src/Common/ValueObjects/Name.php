<?php

declare(strict_types=1);

namespace Common\ValueObjects;

use Webmozart\Assert\Assert;

final readonly class Name
{
    const string PATTERN = '/^[\w ]{2,255}$/';

    /** @param non-empty-string $value */
    public static function fromString(string $value): self
    {
        $value = preg_replace('/\s+/', ' ', trim($value));
        Assert::regex($value, self::PATTERN);

        return new self($value);
    }

    private function __construct(private string $value)
    {
    }

    public function toString(): string
    {
        return $this->value;
    }
}