<?php

declare(strict_types=1);

namespace Credit\Presentation\Rest\Controllers;

use Common\ValueObjects\UserId;
use Credit\Application\Dto\AppCreditAdd;
use Credit\Application\Dto\AppCreditCheck;
use Credit\Application\Exceptions\AppException;
use Credit\Application\Services\CreditsService;
use Credit\Domain\ValueObjects\ProductId;
use Credit\Presentation\Rest\Dto\CreditAdd;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Webmozart\Assert\InvalidArgumentException;


#[AsController]
final readonly class CreditsController
{
    public function __construct(
        private CreditsService $creditsService,
    ) {
    }

    #[Route('/credit_users/{id}/check_credit', methods: ['GET'])]
    public function checkCredit(string $id): JsonResponse
    {
        try {
            return new JsonResponse(
                $this->creditsService->checkCredit(
                    new AppCreditCheck(
                        UserId::fromString($id),
                        new \DateTimeImmutable,
                    ),
                ),
            );
        } catch (AppException | InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage(), $e);
        }
    }

    #[Route('/credit_users/{id}/add_credit', methods: ['PATCH'])]
    public function addCredit(#[MapRequestPayload] CreditAdd $dto, string $id): JsonResponse
    {
        try {
            $this->creditsService->addCredit(
                new AppCreditAdd(
                    UserId::fromString($id),
                    ProductId::fromString($dto->productId),
                    new \DateTimeImmutable,
                ),
            );
        } catch (AppException | InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage(), $e);
        }

        return new JsonResponse(status: Response::HTTP_NO_CONTENT);
    }
}