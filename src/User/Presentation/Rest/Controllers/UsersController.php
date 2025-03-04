<?php

declare(strict_types=1);

namespace User\Presentation\Rest\Controllers;

use Common\ValueObjects\Email;
use Common\ValueObjects\Fico;
use Common\ValueObjects\FullName;
use Common\ValueObjects\Money;
use Common\ValueObjects\Name;
use Common\ValueObjects\Phone;
use Common\ValueObjects\Ssn;
use Common\ValueObjects\UserId;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Attribute\Route;
use User\Application\Dto\AppUserChangeEmail;
use User\Application\Dto\AppUserCreate;
use User\Application\Exceptions\UserException;
use User\Application\Services\UsersService;
use User\Domain\ValueObjects\AddressId;
use User\Presentation\Rest\Dto\UserChangeEmail;
use User\Presentation\Rest\Dto\UserCreate;
use Webmozart\Assert\InvalidArgumentException;


#[AsController]
final readonly class UsersController
{
    public function __construct(
        private UsersService $usersService,
    ) {
    }

    #[Route('/users', methods: ['POST'])]
    public function create(#[MapRequestPayload] UserCreate $dto): JsonResponse
    {
        try {
            $user = $this->usersService->create(
                new AppUserCreate(
                    Ssn::fromString($dto->ssn),
                    Email::fromString($dto->email),
                    new FullName(Name::fromString($dto->firstName), Name::fromString($dto->lastName)),
                    AddressId::fromString($dto->addressId),
                    new \DateTimeImmutable($dto->birthDate),
                    new Fico($dto->ficoScore),
                    Money::fromIntDollars($dto->monthlyIncome),
                    Phone::fromString($dto->phone),
                ),
            );
        } catch (UserException | InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage(), $e);
        }

        return new JsonResponse(['userId' => $user->getId()->toString()], Response::HTTP_CREATED);
    }

    #[Route('/users/{id}/change_email', methods: ['PATCH'])]
    public function changeEmail(#[MapRequestPayload] UserChangeEmail $dto): JsonResponse
    {
        try {
            $this->usersService->changeEmail(
                new AppUserChangeEmail(
                    UserId::fromString($dto->userId),
                    Email::fromString($dto->email),
                ),
            );
        } catch (UserException | InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage(), $e);
        }

        return new JsonResponse(status: Response::HTTP_NO_CONTENT);
    }
}