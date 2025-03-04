<?php

declare(strict_types=1);

namespace Common\ValueObjects;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
final readonly class FullName
{
    public function __construct(
        #[ORM\Column] public Name $firstName,
        #[ORM\Column] public Name $lastName,
    ) {
    }
}