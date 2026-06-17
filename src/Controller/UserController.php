<?php

namespace App\Controller;

use App\Entity\User as User;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('api/user', name: 'app_user')]
final class UserController extends AbstractController
{
    #[Route('', name: 'all_users', methods: ["GET"])]
    public function getUsers(EntityManagerInterface $em): JsonResponse
    {
        try {
            $users = $em->getRepository(User::class)->findAll();
            if (empty($users)) {
                return $this->json(['message' => "Oups, Bad Request"], Response::HTTP_BAD_REQUEST);
            }
            return $this->json($users);
        } catch (Exception $e) {
            return $this->json($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/me', name: 'get_me', methods: ["GET"])]
    function getUserByToken(Security $sec): JsonResponse {
        try {
            $user = $sec->getUser();
            if(!$user){
                return $this->json(["message" => "Not Authenticated"], Response::HTTP_UNAUTHORIZED);
            }
            return $this->json($user);
        } catch (Exception $e) {
            return $this->json($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/{id}', name: 'get_user', methods: ["GET"])]
    function getUserById(EntityManagerInterface $em, int $id): JsonResponse
    {
        try {
            $user = $em->getRepository(User::class)->find($id);
            if (!$user) {
                return $this->json(["message" => "Oups, Bad Request"], Response::HTTP_BAD_REQUEST);
            }

            return $this->json($user);
        } catch (Exception $e) {
            return $this->json($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }


    #[Route('/{id}', name: 'remove_user', methods: ["DELETE"])]
    function removeUser(EntityManagerInterface $em, int $id): JsonResponse {
        try {
            $user = $em->getRepository(User::class)->find($id);
            if(!$user){
                return $this->json(["message" => "Oups, Bad Request"], Response::HTTP_BAD_REQUEST);
            }

            $em->remove($user);
            $em->flush();
            return $this->json(["message" => "User " . $id . " successfuly removed."], Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->json($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }
}
