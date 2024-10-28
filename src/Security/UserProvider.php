<?php

// src/Security/UserProvider.php

namespace App\Security;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class UserProvider implements UserProviderInterface
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        return $this->entityManager->getRepository(User::class)->findOneBy(['email' => $identifier]);
    }

    /**
     * @throws \Exception
     */
    public function refreshUser(UserInterface $user): UserInterface
    {
        // Re-fetch the user from the database
        $userClass = get_class($user);
        $freshUser = $this->entityManager->getRepository($userClass)->find($user->getId());

        if (!$freshUser) {
            throw new \Exception("User not found.");
        }

        return $freshUser; // Return the fresh user entity
    }

    public function supportsClass(string $class): bool
    {
        return User::class === $class;
    }
}
