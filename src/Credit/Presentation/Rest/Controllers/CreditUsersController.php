<?php

declare(strict_types=1);

namespace Credit\Presentation\Rest\Controllers;

use Common\ValueObjects\Fico;
use Common\ValueObjects\Money;
use Common\ValueObjects\UserId;
use Credit\Application\Dto\AppCreditUserChange;
use Credit\Application\Exceptions\AppException;
use Credit\Application\Services\CreditUsersService;
use Credit\Presentation\Rest\Dto\CreditUserChange;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Webmozart\Assert\InvalidArgumentException;


#[AsController]
final readonly class CreditUsersController
{
    public function __construct(
        private CreditUsersService $usersService,
    ) {
    }

    #[Route('/credit_users/{id}', methods: ['PATCH'])]
    public function change(#[MapRequestPayload] CreditUserChange $dto, string $id): JsonResponse
    {
        try {
            $this->usersService->change(
                new AppCreditUserChange(
                    UserId::fromString($id),
                    $dto->birthDate ? new \DateTimeImmutable($dto->birthDate) : null,
                    $dto->ficoScore ? new Fico($dto->ficoScore) : null,
                    $dto->monthlyIncome ? Money::fromIntDollars($dto->monthlyIncome) : null,
                ),
            );
        } catch (AppException | InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage(), $e);
        }

        return new JsonResponse(status: Response::HTTP_NO_CONTENT);
    }
}