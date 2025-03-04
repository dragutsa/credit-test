<?php

namespace Credit\Domain\Entities;

use Credit\Domain\ValueObjects\CreditId;
use Credit\Domain\ValueObjects\ProductId;
use Credit\Domain\ValueObjects\Rate;
use Doctrine\ORM\Mapping as ORM;

class Credit
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid')]
    private CreditId $creditId;

    #[ORM\Column]
    /** @phpstan-ignore-next-line */
    private Rate $rate;

    #[ORM\Column(type: 'uuid')]
    /** @phpstan-ignore-next-line */
    private ProductId $productId;

    #[ORM\Column(type: 'date_immutable')]
    /** @phpstan-ignore-next-line */
    private \DateTimeImmutable $dateOfIssue;

    #[ORM\ManyToOne(targetEntity: CreditUser::class, inversedBy: 'credits')]
    /** @phpstan-ignore-next-line */
    private CreditUser $user;

    public function __construct(
        CreditUser $user,
        Product $product,
        Rate $rate,
        \DateTimeImmutable $dateOfIssue,
    ) {
        $this->creditId = CreditId::generate($user->getId(), $product->getId());
        $this->user = $user;
        $this->productId = $product->getId();
        $this->rate = $rate;
        $this->dateOfIssue = $dateOfIssue;
    }

    public function getId(): CreditId
    {
        return $this->creditId;
    }
}
