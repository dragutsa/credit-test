<?php

declare(strict_types=1);

namespace Credit\Application\Services;

use Common\Outbox\Services\OutboxStub;
use Credit\Application\Dto\AppCreditUserChange;
use Credit\Application\Exceptions\AppException;
use Credit\Domain\Entities\CreditUser;
use Credit\Domain\Repositories\CreditUsersRepository;
use Psr\Log\LoggerInterface;

final readonly class CreditUsersService
{
    public function __construct(
        private CreditUsersRepository $usersRepository,
        private OutboxStub $outbox,
        private LoggerInterface $logger,
    ) {
    }

    /** @return CreditUser */
    public function change(AppCreditUserChange $dto): object
    {
        return $this->outbox->transactional(function () use ($dto) {
            $user = $this->usersRepository->find($dto->userId) ?? $this->error('User not found');
            $user->changeFico($dto->ficoScore);
            $user->changeBirthDate($dto->birthDate);
            $user->changeMonthlyIncome($dto->monthlyIncome);

            return $this->usersRepository->save($user);
        });
    }

    private function error(string $msg): never
    {
        $this->logger->error($msg);
        throw new AppException($msg);
    }
}