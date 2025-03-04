<?php

declare(strict_types=1);

namespace User\Application\Services;

use Common\Outbox\Services\OutboxStub;
use Common\ValueObjects\UserId;
use Psr\Log\LoggerInterface;
use User\Application\Dto\AppUserChangeEmail;
use User\Application\Dto\AppUserCreate;
use User\Application\Exceptions\UserException;
use User\Domain\Entities\User;
use User\Domain\Repositories\AddressesRepository;
use User\Domain\Repositories\UsersRepository;

final readonly class UsersService
{
    public function __construct(
        private UsersRepository $usersRepository,
        private AddressesRepository $addressesRepository,
        private OutboxStub $outbox,
        private LoggerInterface $logger,
    ) {
    }

    /** @return User */
    public function create(AppUserCreate $dto): object
    {
        return $this->outbox->transactional(function () use ($dto) {
            if ($this->usersRepository->exists(UserId::generate($dto->ssn))) {
                $this->error('User already exists');
            }
            return $this->usersRepository->save(
                new User(
                    $dto->ssn,
                    $dto->email,
                    $dto->fullName,
                    $this->addressesRepository->find($dto->addressId) ?? $this->error('Address not found'),
                    $dto->birthDate,
                    $dto->ficoScore,
                    $dto->monthlyIncome,
                    $dto->phone,
                ),
            );
        });
    }

    public function changeEmail(AppUserChangeEmail $dto): void
    {
        $this->outbox->transactional(function () use ($dto) {
            $user = $this->usersRepository->find($dto->userId) ?? $this->error('User not found');
            $user->changeEmail($dto->email);

            return $this->usersRepository->save($user);
        });
    }

    private function error(string $msg): never
    {
        $this->logger->error($msg);
        throw new UserException($msg);
    }
}