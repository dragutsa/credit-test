<?php

declare(strict_types=1);

namespace Tests\Unit\Credit\Domain\Entities\CreditUser;

use Common\ValueObjects\Fico;
use Common\ValueObjects\Money;
use Common\ValueObjects\Name;
use Common\ValueObjects\Ssn;
use Common\ValueObjects\State;
use Common\ValueObjects\UserId;
use Credit\Domain\Entities\CreditUser;
use Credit\Domain\Entities\Product;
use Credit\Domain\Events\CreditAdded;
use Credit\Domain\Deciders\Decider;
use Credit\Domain\ValueObjects\Rate;
use PHPUnit\Framework\TestCase;


final class CreditUserTest extends TestCase
{
    private const string NOW = '2025-01-01';
    private $addCredit;
    private $releaseEvents;

    /**
     * @testWith ["Nevada", 1000]
     *           ["California", 2149]
     */
    public function testCreditUserCheckCreditAvailabilitySuccess(
        string $state,
        int $rate,
    ): void {
        $ssn = Ssn::fromString('111-22-0000');
        $userId = UserId::generate($ssn);
        $user = new CreditUser(
            $userId,
            new \DateTimeImmutable('2000-01-01'),
            $ssn,
            State::from($state),
            new Fico(700),
            Money::fromIntDollars(1000),
        );
        $product = $this->createProduct();

        $this->addCredit = $user->addCredit(
            $product,
            $this->now(),
            $this->createDecider(),
        );
        $this->addCredit;

        $this->releaseEvents = $user->releaseEvents();
        $events = $this->releaseEvents;
        $this->assertCount(1, $events);
        $event = $events[0];
        $this->assertInstanceOf(CreditAdded::class, $event);
        $this->assertSame($userId->toString(), $event->userId->toString());
        $this->assertSame($product->getId()->toString(), $event->productId->toString());
        $this->assertSame($rate, $event->rate->toBaseUnits());
    }

    /**
     * @testWith ["2000-01-01", "Nevada", 300, 1000, "Fico score is not solvent"]
     *           ["2000-01-01", "Nevada", 700, 900, "Monthly income is not solvent"]
     *           ["2009-01-01", "Nevada", 700, 1000, "Age is not solvent"]
     *           ["2000-01-01", "Texas", 700, 1000, "State is not available for credit"]
     */
    public function testCreditUserCheckCreditAvailabilityErrors(
        string $birthDate,
        string $state,
        int $ficoScore,
        int $monthlyIncome,
        string $msg,
    ): void {
        $ssn = Ssn::fromString('111-22-0000');
        $userId = UserId::generate($ssn);
        $user = new CreditUser(
            $userId,
            new \DateTimeImmutable($birthDate),
            $ssn,
            State::from($state),
            new Fico($ficoScore),
            Money::fromIntDollars($monthlyIncome),
        );
        $product = $this->createProduct();

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage($msg);

        $user->addCredit(
            $product,
            $this->now(),
            $this->createDecider(),
        );
    }

    private function createDecider(): Decider
    {
        $decider = $this->createMock(Decider::class);
        $decider->method('decide')->willReturn(true);

        return $decider;
    }

    private function createProduct(): Product
    {
        return new Product(
            Name::fromString('Product'),
            100,
            Rate::fromFloat(10.0),
            Money::fromIntDollars(2000),
        );
    }

    private function now(): \DateTimeImmutable
    {
        return new \DateTimeImmutable(self::NOW);
    }
}