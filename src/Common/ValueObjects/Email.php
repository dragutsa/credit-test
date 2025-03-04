<?php

declare(strict_types=1);

namespace Common\ValueObjects;

use Webmozart\Assert\Assert;

final readonly class Email
{
    const string PATTERN = '/^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$/';

    /** @param non-empty-string $value */
    public static function fromString(string $value): self
    {
        $value = strtolower($value);
        Assert::regex($value, self::PATTERN);

        return new self(...explode('@', $value));
    }

    private function __construct(
        private string $user,
        private string $domain,
    ) {
    }

    public function toString(): string
    {
        return $this->user . '@' . $this->domain;
    }

    public function equals(self $email): bool
    {
        return $this->domain === $email->domain && $this->user === $email->user;
    }
}