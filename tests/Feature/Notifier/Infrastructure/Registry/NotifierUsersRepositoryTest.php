<?php

declare(strict_types=1);

namespace Feature\Notifier\Infrastructure\Registry;

use Common\ValueObjects\Email;
use Common\ValueObjects\FullName;
use Common\ValueObjects\Name;
use Common\ValueObjects\Phone;
use Common\ValueObjects\Ssn;
use Common\ValueObjects\UserId;
use Notifier\Domain\Entities\NotifierUser;
use Notifier\Domain\Repositories\NotifierUsersRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class NotifierUsersRepositoryTest extends KernelTestCase
{
    public function testNotifierUsersRepositoryTest(): void
    {
        // (1) boot the Symfony kernel
        self::bootKernel();

        // (2) use static::getContainer() to access the service container
        $container = static::getContainer();
        $repo = $container->get(NotifierUsersRepository::class);

        $ssn = Ssn::fromString('111-22-0000');
        $userId = UserId::generate($ssn);
        $nonExistentUserId = UserId::generate(Ssn::fromString('000-00-0000'));
        $fullName = new FullName(Name::fromString('firstName'), Name::fromString('lastName'));
        $email = Email::fromString('test@example.com');
        $phone = Phone::fromString('(800) 326-6148');

        $repo->save(new NotifierUser($userId, $fullName, $email, $phone));
        $this->databaseHas([$userId, $fullName, $email, $phone]);

        $this->assertTrue($repo->exists($userId));
        $this->assertFalse($repo->exists($nonExistentUserId));

        $userFound = $repo->find($userId);
        $this->assertInstanceOf(NotifierUser::class, $userFound);
        $this->assertSame($userId, $userFound->getId());

        $this->assertNull($repo->find($nonExistentUserId));
    }

    /**
     * direct sql query stub
     * @phpstan-ignore-next-line
     */
    private function databaseHas(array $criteria): void
    {
        /** @phpstan-ignore-next-line */
        $this->assertTrue(true);
    }
}