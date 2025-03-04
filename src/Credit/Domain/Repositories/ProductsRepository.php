<?php

declare(strict_types=1);

namespace Credit\Domain\Repositories;

use Credit\Domain\Entities\Product;
use Credit\Domain\ValueObjects\ProductId;

interface ProductsRepository
{
    public function save(Product $product): Product;

    public function find(ProductId $productId): ?Product;
}