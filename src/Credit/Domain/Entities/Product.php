<?php

namespace Credit\Domain\Entities;

use Common\EventBus\Contracts\EventsHolder;
use Common\EventBus\Contracts\EventsHolderTrait;
use Common\ValueObjects\Money;
use Common\ValueObjects\Name;
use Credit\Domain\Repositories\ProductsRepository;
use Credit\Domain\ValueObjects\ProductId;
use Credit\Domain\ValueObjects\Rate;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductsRepository::class)]
class Product implements EventsHolder
{
    use EventsHolderTrait;

    #[ORM\Id]
    #[ORM\Column(type: 'uuid')]
    private ProductId $productId;

    #[ORM\Column]
    /** @phpstan-ignore-next-line */
    private Name $name;

    #[ORM\Column]
    /** @phpstan-ignore-next-line */
    private int $term;

    #[ORM\Column]
    private Rate $rate;

    #[ORM\Column]
    /** @phpstan-ignore-next-line */
    private Money $amount;

    /** @param positive-int $term */
    public function __construct(
        Name $name,
        int $term,
        Rate $rate,
        Money $amount,
    ) {
        $this->productId = ProductId::generate($name);
        $this->name = $name;
        $this->term = $term;
        $this->rate = $rate;
        $this->amount = $amount;
    }

    public function getId(): ProductId
    {
        return $this->productId;
    }

    public function getRate(): Rate
    {
        return $this->rate;
    }
}
