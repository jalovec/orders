<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use App\Domain\User\Repository\UserRepository;
use App\Entity\User;
use Faker\Factory;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class OrdersControllerTest extends WebTestCase
{
    private $client;
    private EntityManagerInterface $entityManager;
    private UserPasswordHasherInterface $passwordHasher;
    private MockObject $userRepository;

    protected function setUp(): void
    {
        // Create a client for the test
        $this->client = static::createClient();

        // Get the entity manager from the service container
        $this->entityManager = self::getContainer()->get(EntityManagerInterface::class);

        // Get the password hasher from the service container
        $this->passwordHasher = self::getContainer()->get(UserPasswordHasherInterface::class);

        $this->userRepository = $this->createMock(UserRepository::class);
    }

    private function createAuthenticatedUser(): User
    {
        $faker = Factory::create();
        $user = new User();
        $user->setEmail($faker->email());

        // Hash the password properly using the password hasher
        $hashedPassword = $this->passwordHasher->hashPassword($user, 'password');
        $user->setPassword($hashedPassword);
        $user->setRoles(['ROLE_ADMIN']);

        // Persist the user to the database
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }

    public function testListOrders(): void
    {
        $user = $this->createAuthenticatedUser();
        $this->client->loginUser($user);

        // Make a request to the '/orders' endpoint
        $this->client->request('GET', '/orders');

        // Assert the response status code is 200 (OK)
        $this->assertResponseIsSuccessful();

        $this->userRepository->remove($user, true);
    }
}
