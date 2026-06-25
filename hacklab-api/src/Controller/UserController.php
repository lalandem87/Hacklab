<?php

namespace App\Controller;

use App\Entity\User as User;
use App\Entity\UserCertification;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Doctrine\DBAL\Exception as DBALException;

#[Route('api/user', name: 'app_user')]
final class UserController extends AbstractController
{
    #[Route('', name: 'all_users', methods: ["GET"])]
    #[IsGranted('ROLE_ADMIN')]
    public function getUsers(EntityManagerInterface $em): JsonResponse
    {
        try {
            $users = $em->getRepository(User::class)->findAll();
            if (empty($users)) {
                return $this->json(['message' => "Oups, Bad Request"], Response::HTTP_BAD_REQUEST);
            }
            return $this->json($users, 200, [], ['groups' => 'user:read']);
        } catch (DBALException) {
            return $this->json(["message" => "Database error"], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (Exception) {
            return $this->json(["message" => "An error occured"], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/me', name: 'get_me', methods: ["GET"])]
    function getUserByToken(Security $sec): JsonResponse
    {
        try {
            $user = $sec->getUser();
            if (!$user) {
                return $this->json(["message" => "Not Authenticated"], Response::HTTP_UNAUTHORIZED);
            }
            return $this->json($user, 200, [], ['groups' => 'user:read']);
        } catch (DBALException) {
            return $this->json(["message" => "Database error"], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (Exception) {
            return $this->json(["message" => "An error occured"], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/{id}', name: 'get_user', methods: ["GET"], requirements: ['id' => '\d+'])]
    function getUserById(EntityManagerInterface $em, int $id, Security $sec): JsonResponse
    {
        try {
            $currentUser =  $sec->getUser();
            /** @var \App\Entity\User $currentUser */

            if ($currentUser->getId() !== $id && !in_array('ROLE_ADMIN', $currentUser->getRoles())) {
                return $this->json(["message" => "Access Denied"], Response::HTTP_FORBIDDEN);
            }

            $user = $em->getRepository(User::class)->find($id);
            if (!$user) {
                return $this->json(["message" => "Oups, Bad Request"], Response::HTTP_BAD_REQUEST);
            }

            return $this->json($user, 200, [], ['groups' => 'user:read']);
        } catch (DBALException) {
            return $this->json(["message" => "Database error"], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (Exception) {
            return $this->json(["message" => "An error occured"], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    #[Route('/{id}', name: 'remove_user', methods: ["DELETE"], requirements: ['id' => '\d+'])]
    #[IsGranted('ROLE_ADMIN')]
    function removeUser(EntityManagerInterface $em, int $id): JsonResponse
    {
        try {
            $user = $em->getRepository(User::class)->find($id);
            if (!$user) {
                return $this->json(["message" => "Oups, Bad Request"], Response::HTTP_BAD_REQUEST);
            }

            $em->remove($user);
            $em->flush();
            return $this->json(["message" => "User " . $id . " successfuly removed."], Response::HTTP_OK);
        } catch (DBALException) {
            return $this->json(["message" => "Database error"], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (Exception) {
            return $this->json(["message" => "An error occured"], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/leaderboard', name: 'leaderboard_users', methods: ["GET"])]
    function getLeaderboard(EntityManagerInterface $em): JsonResponse
    {
        try {
            $userSort = $em->getRepository(User::class)->findBy([], ['pointEarn' => 'DESC']);
            if (empty($userSort)) {
                return $this->json(["message" => "Leaderboard is empty cause no player yet"], Response::HTTP_BAD_REQUEST);
            }
            return $this->json($userSort, 200, [], ['groups' => 'user:read']);
        } catch (DBALException) {
            return $this->json(["message" => "Database error"], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (Exception) {
            return $this->json(["message" => "An error occured"], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    #[Route('/certification', name: 'certification_users', methods: ["GET"])]
    function usersCertifications(EntityManagerInterface $em): JsonResponse
    {
        try {
            $usersCertifs = $em->getRepository(UserCertification::class)->findAll();
            return $this->json($usersCertifs);
        } catch (DBALException) {
            return $this->json(["message" => "Database error"], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (Exception) {
            return $this->json(["message" => "An error occured"], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
