<?php

namespace App\Controller;

use App\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

#[Route('/api', name: 'api_')]
class AuthController extends AbstractController
{
    #[Route(path: '/api/login', name: 'api_login', methods: ['POST'])]
    public function getTokenUser(UserInterface $user, JWTTokenManagerInterface $JWTManager): JsonResponse
    {
        return new JsonResponse(['token' => $JWTManager->create($user)]);
    }

    #[Route('/users', name: 'get_users', methods: ['get'])]
    public function getUsers(EntityManagerInterface $em): Response
    {
        // make try and catch for this function and return the error message
        try {
            $users = $em->getRepository(Users::class)->findAll();
            $listUser = [];
            $this->data($users, $listUser);
            /** @var Users $listUser */
            return $this->json($listUser);
        } catch (\Exception $e) {
            return $this->json(['message' => $e->getMessage()]);
        }
    }

    public function data($users, &$listUser): void
    {
        foreach ($users as $user) {
            $listUser = [
                'id' => $user->getId(),
                'email' => $user->getEmail(),
                'roles' => $user->getRoles(),
                'userName' => $user->getUserName()
            ];
        }
    }

    #[Route('/register', name: 'register', methods: ['POST'])]
    public function register(EntityManagerInterface $em, Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {
        $decoded = json_decode($request->getContent());
        $email = $decoded->email;
        $roles = $decoded->roles ?? ['ROLE_USER'];
        $plaintextPassword = $decoded->password;
        $user = new Users();
        $hashedPassword = $passwordHasher->hashPassword(
            $user,
            $plaintextPassword
        );
        $user->setPassword($hashedPassword);
        $user->setEmail($email);
        $user->setRoles($roles);
        if (isset($decoded->userName)) {
            $userName = $decoded->userName;
            $user->setUserName($userName);
        }
        $em->persist($user);
        $em->flush();
        return $this->json(['message' => 'Registered Successfully']);
    }
}
