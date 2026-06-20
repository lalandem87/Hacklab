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

#[Route('api/challenge', name: 'app_challenge')]
final class ChallengeController extends AbstractController
{
    #[Route('', name: 'all_challenges', methods: ["GET"])]
    function getChallenge(EntityManagerInterface $em): JsonResponse {
        try {
            $challenges = $em->getRepository(Challenge::class)->findAll();
            if(empty($challenges)){
                return $this->json(["message" => "Challenge not found"], Response::HTTP_BAD_REQUEST);
            }
            return $this->json($challenges);
        } catch (Exception $e) {
            return $this->json($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }


    #[Route('/{id}', name: 'get_challenge', methods: ["GET"], requirements: ['id' => '\d+'])]
    function getChallengeById(int $id, EntityManagerInterface $em): JsonResponse {
        try {
            $challenge = $em->getRepository(Challenge::class)->find($id);
            if(!$challenge){
                return $this->json(["message" => "Oups, Bad Request"], Response::HTTP_BAD_REQUEST);
            }
            return $this->json($challenge);
        } catch (Exception $e) {
            return $this->json($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('', name: 'create_challenge', methods: ["POST"])]
    function createChallenge(EntityManagerInterface $em, Request $req): JsonResponse {
        try {
            $data = json_decode($req->getContent(), true);
            if(!$data){
                return $this->json(["message" => "Cannot access data"], Response::HTTP_BAD_REQUEST);
            }

            $challenge = new Challenge();
            $challenge->setName($data["name"]);
            $challenge->setContent($data["content"]);
            $challenge->setPoint($data["point"]);
            $challenge->setFlag($data["flag"]);

            $em->persist($challenge);
            $em->flush();

            return $this->json(["message" => "Challenge successfuly created."], Response::HTTP_CREATED);
        } catch (Exception $e) {
            return $this->json($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/{id}', name: 'remove_challenge', methods: ["DELETE"], requirements: ['id' => '\d+'])]
    function removeChallenge(EntityManagerInterface $em, int $id): JsonResponse {
        try {
            $challenge = $em->getRepository(Challenge::class)->find($id);
            if(!$challenge){
                return $this->json(["message" => "Oups, Bad Request"], Response::HTTP_BAD_REQUEST);
            }

            $em->remove($challenge);
            $em->flush();
            return $this->json(["message" => "Challenge " . $id . " successfuly removed." ], Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->json($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }
    
}
