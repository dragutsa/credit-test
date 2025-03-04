<?php

declare(strict_types=1);

namespace Common\ValueObjects;

use Webmozart\Assert\Assert;

final readonly class Phone
{
    const string PATTERN = '/^\(?\d{3}\)?-? *\d{3}-? *-?\d{4}$/';

    /** @param non-empty-string $number */
    public static function fromString(string $number): self
    {
        Assert::regex($number, self::PATTERN);

        return new self($number);
    }

    private function __construct(private string $number)
    {
    }

    public function toString(): string
    {
        return $this->number;
    }
}