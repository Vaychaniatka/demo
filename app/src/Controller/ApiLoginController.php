<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class ApiLoginController extends AbstractController
{
    #[Route('/api/login', name: 'app_api_login', methods: ['POST'])]
    public function login(#[CurrentUser] ?User $user): Response
    {
        if (!$user) {
            return $this->json(
                [
                    'message' => 'missing credentials',
                ],
                Response::HTTP_UNAUTHORIZED
            );
        }

        $token = $user->getToken();

        return $this->json(['token' => $token]);
    }

    #[Route('/api/signin', name: 'app_api_signin', methods: ['POST'])]
    public function signin(UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): Response
    {
        $plaintextPassword = 'userpass';
        $user = (new User())
            ->setUsername('username')
            ->setRoles(['ROLE_ADMIN', 'ROLE_USER'])
            ->setToken('API_TOKEN')
        ;

        $hashedPassword = $passwordHasher->hashPassword(
            $user,
            $plaintextPassword
        );

        $user->setPassword($hashedPassword);

        $entityManager->persist($user);
        $entityManager->flush();

        return $this->json([
            'identifier' => $user->getUserIdentifier(),
        ]);
    }
}
