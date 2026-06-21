<?php

namespace App\Controller;

use App\Entity\Challenge as Challenge;

use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Doctrine\DBAL\Exception as DBALException;

#[Route('api/challenge', name: 'app_challenge')]
final class ChallengeController extends AbstractController
{
    #[Route('', name: 'all_challenges', methods: ["GET"])]
    function getChallenge(EntityManagerInterface $em): JsonResponse
    {
        try {
            $challenges = $em->getRepository(Challenge::class)->findAll();
            if (empty($challenges)) {
                return $this->json(["message" => "Challenge not found"], Response::HTTP_NOT_FOUND);
            }
            return $this->json($challenges);
        } catch (DBALException) {
            return $this->json(["message" => "Database error"], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (Exception) {
            return $this->json(["message" => "An error occured"], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    #[Route('/{id}', name: 'get_challenge', methods: ["GET"], requirements: ['id' => '\d+'])]
    function getChallengeById(int $id, EntityManagerInterface $em): JsonResponse
    {
        try {
            $challenge = $em->getRepository(Challenge::class)->find($id);
            if (!$challenge) {
                return $this->json(["message" => "Challenge not found in database"], Response::HTTP_NOT_FOUND);
            }
            return $this->json($challenge);
        } catch (DBALException) {
            return $this->json(["message" => "Database error"], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (Exception) {
            return $this->json(["message" => "An error occured"], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('', name: 'create_challenge', methods: ["POST"])]
    #[IsGranted('ROLE_ADMIN')]
    function createChallenge(EntityManagerInterface $em, Request $req, ValidatorInterface $validator): JsonResponse
    {
        try {
            $data = json_decode($req->getContent(), true);
            if (!$data) {
                return $this->json(["message" => "Cannot access data"], Response::HTTP_BAD_REQUEST);
            }

            $constraints = new Assert\Collection([
                'name' => [new Assert\NotBlank(), new Assert\Type(type: 'string'), new Assert\Length(max: 40)],
                'content' => [new Assert\NotBlank(), new Assert\Type(type: 'string')],
                'point' => [new Assert\NotBlank(), new Assert\Type(type: 'integer')],
                'flag' => [new Assert\NotBlank(), new Assert\Type(type: 'string')],
            ]);

            $errors = $validator->validate($data, $constraints);

            if (count($errors) > 0) {
                return $this->json(["message" => (string) $errors], Response::HTTP_BAD_REQUEST);
            }

            $challenge = new Challenge();
            $challenge->setName($data["name"]);
            $challenge->setContent($data["content"]);
            $challenge->setPoint($data["point"]);
            $challenge->setFlag($data["flag"]);

            $em->persist($challenge);
            $em->flush();

            return $this->json(["message" => "Challenge successfuly created."], Response::HTTP_CREATED);
        } catch (DBALException) {
            return $this->json(["message" => "Database error"], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (Exception) {
            return $this->json(["message" => "An error occured"], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/{id}', name: 'remove_challenge', methods: ["DELETE"], requirements: ['id' => '\d+'])]
    #[IsGranted('ROLE_ADMIN')]
    function removeChallenge(EntityManagerInterface $em, int $id): JsonResponse
    {
        try {
            $challenge = $em->getRepository(Challenge::class)->find($id);
            if (!$challenge) {
                return $this->json(["message" => "Challenge not found in database"], Response::HTTP_NOT_FOUND);
            }

            $em->remove($challenge);
            $em->flush();
            return $this->json(["message" => "Challenge " . $id . " successfuly removed."], Response::HTTP_OK);
        } catch (DBALException) {
            return $this->json(["message" => "Database error"], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (Exception) {
            return $this->json(["message" => "An error occured"], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
