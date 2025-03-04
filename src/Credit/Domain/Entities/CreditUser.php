<?php

namespace Credit\Domain\Entities;

use Common\EventBus\Contracts\EventsHolder;
use Common\EventBus\Contracts\EventsHolderTrait;
use Common\ValueObjects\Age;
use Common\ValueObjects\Fico;
use Common\ValueObjects\Money;
use Common\ValueObjects\Ssn;
use Common\ValueObjects\State;
use Common\ValueObjects\UserId;
use Credit\Domain\Events\CreditAdded;
use Credit\Domain\Exceptions\CreditException;
use Credit\Domain\Helpers\Randomizer;
use Credit\Domain\Repositories\CreditUsersRepository;
use Credit\Domain\ValueObjects\Rate;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CreditUsersRepository::class)]
class CreditUser implements EventsHolder
{
    use EventsHolderTrait;

    #[ORM\Id]
    #[ORM\Column(type: 'uuid')]
    private UserId $userId;

    #[ORM\Column(type: 'date_immutable')]
    private \DateTimeImmutable $birthDate;

    #[ORM\Column]
    /** @phpstan-ignore-next-line */
    private Ssn $ssn;

    #[ORM\Column]
    private State $state;

    #[ORM\Column]
    private Fico $ficoScore;

    #[ORM\Column]
    private Money $monthlyIncome;

    /** @var Collection<int, Credit> */
    #[ORM\OneToMany(targetEntity: Credit::class, mappedBy: 'user', cascade: ['persist'])]
    private Collection $credits;

    public function __construct(
        UserId $userId,
        \DateTimeImmutable $birthDate,
        Ssn $ssn,
        State $state,
        Fico $ficoScore,
        Money $monthlyIncome,
    ) {
        $this->userId = $userId;
        $this->birthDate = $birthDate;
        $this->ssn = $ssn;
        $this->state = $state;
        $this->ficoScore = $ficoScore;
        $this->monthlyIncome = $monthlyIncome;
        $this->credits = new ArrayCollection();
    }

    public function getId(): UserId
    {
        return $this->userId;
    }

    public function addCredit(Product $product, \DateTimeImmutable $dateOfIssue, Randomizer $randomizer): self
    {
        $this->checkAvailability($dateOfIssue, $randomizer);
        $rate = $this->recalculateRate($product->getRate());
        $credit = new Credit(
            $this,
            $product,
            $rate,
            $dateOfIssue,
        );
        $this->credits->add($credit);

        $this->events[] = new CreditAdded(
            $this->userId,
            $credit->getId(),
            $product->getId(),
            $rate,
        );

        return $this;
    }

    public function checkAvailability(\DateTimeImmutable $dateOfIssue, Randomizer $randomizer): void
    {
        if (!$this->ficoScore->isSolvent()) {
            throw new CreditException('Fico score is not solvent');
        }
        if (!$this->monthlyIncome->isSolvent()) {
            throw new CreditException('Monthly income is not solvent');
        }
        if (!(new Age($this->birthDate, $dateOfIssue))->isSolvent()) {
            throw new CreditException('Age is not solvent');
        }
        if (!$this->state->isCreditAvailable($randomizer)) {
            throw new CreditException('State is not available for credit');
        }
    }

    public function changeBirthDate(?\DateTimeImmutable $birthDate): self
    {
        if ($birthDate && $this->birthDate !== $birthDate) {
            $this->birthDate = $birthDate;
        }

        return $this;
    }

    public function changeMonthlyIncome(?Money $monthlyIncome): self
    {
        if ($monthlyIncome && !$this->monthlyIncome->equals($monthlyIncome)) {
            $this->monthlyIncome = $monthlyIncome;
        }

        return $this;
    }

    public function changeFico(?Fico $fico): self
    {
        if ($fico && !$this->ficoScore->equals($fico)) {
            $this->ficoScore = $fico;
        }

        return $this;
    }

    private function recalculateRate(Rate $rate): Rate
    {
        return $this->state->isExtraRate() ? $rate->addExtraRate() : $rate;
    }
}
