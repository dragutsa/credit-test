<?php

namespace Credit\Infrastructure\Registry;

use Common\ValueObjects\Money;
use Common\ValueObjects\Name;
use Credit\Domain\Entities\Product;
use Credit\Domain\Repositories\ProductsRepository;
use Credit\Domain\ValueObjects\ProductId;
use Credit\Domain\ValueObjects\Rate;

class RegistryProductsRepository implements ProductsRepository
{
    /** @var Product[] */
    private array $items = [];

    public function __construct()
    {
        for ($i = 0; $i < 5; $i++) {
            $product = new Product(
                Name::fromString('Product ' . $i),
                100 + $i,
                Rate::fromFloat(10.0 + $i),
                Money::fromIntDollars(1000 + $i),
            );
            $this->items[$product->getId()->toString()] = $product;
        }
    }

    public function save(Product $product): Product
    {
        $this->items[$product->getId()->toString()] = $product;

        return $product;
    }

    public function find(ProductId $productId): ?Product
    {
        return $this->items[$productId->toString()] ?? null;
    }
}
