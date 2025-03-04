<?php

declare(strict_types=1);

namespace Credit\Application\Services;

use Common\Outbox\Services\OutboxStub;
use Credit\Application\Dto\AppCreditAdd;
use Credit\Application\Dto\AppCreditCheck;
use Credit\Application\Dto\CreditCheckResult;
use Credit\Application\Exceptions\AppException;
use Credit\Domain\Entities\CreditUser;
use Credit\Domain\Deciders\Decider;
use Credit\Domain\Repositories\CreditUsersRepository;
use Credit\Domain\Repositories\ProductsRepository;
use Psr\Log\LoggerInterface;

final readonly class CreditsService
{
    public function __construct(
        private CreditUsersRepository $usersRepository,
        private ProductsRepository $productsRepository,
        private OutboxStub $outbox,
        private LoggerInterface $logger,
        private Decider $decider,
    ) {
    }

    public function checkCredit(AppCreditCheck $dto): CreditCheckResult
    {
        $user = $this->usersRepository->find($dto->userId) ?? $this->error('User not found');
        try {
            $user->checkAvailability(
                $dto->dateOfIssue,
                $this->decider,
            );
        } catch (\DomainException $e) {
            return new CreditCheckResult(false, $e->getMessage());
        }

        return new CreditCheckResult(true);
    }

    public function addCredit(AppCreditAdd $dto): CreditUser
    {
        return $this->outbox->transactional(function () use ($dto) {
            $user = $this->usersRepository->find($dto->userId) ?? $this->error('User not found');

            return $this->usersRepository->save(
                $user->addCredit(
                    $this->productsRepository->find($dto->productId) ?? $this->error('Product not found'),
                    $dto->dateOfIssue,
                    $this->decider,
                ),
            );
        });
    }

    private function error(string $msg): never
    {
        $this->logger->error($msg);
        throw new AppException($msg);
    }
}