<?php

namespace App\Controller;

use App\Entity\Certification as Certification;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('api/certification', name: 'app_certification')]
final class CertificationController extends AbstractController
{

    #[Route('', name: "get_certifications", methods: ["GET"])]
    function getCertifications(EntityManagerInterface $em): JsonResponse
    {
        try {
            $certifications = $em->getRepository(Certification::class)->findAll();
            if (empty($certifications)) {
                return $this->json(["message" => "Certifications not found"], Response::HTTP_BAD_REQUEST);
            }
            return $this->json($certifications);
        } catch (Exception $e) {
            return $this->json($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/{id}', name: "certification_id", methods: ["GET"], requirements: ['id' => '\d+'])]
    function getCertificationById(int $id, EntityManagerInterface $em): JsonResponse {
        try {
            $certification = $em->getRepository(Certification::class)->find($id);
            if(!$certification){
                return $this->json(["message" => "Oups, Bad Request"], Response::HTTP_BAD_REQUEST);
            }
            return $this->json($certification);
        } catch (Exception $e) {
            return $this->json($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('', name: "create_certification", methods: ["POST"])]
    function createCertification(EntityManagerInterface $em, Request $req): JsonResponse {
        try {
            $data = json_decode($req->getContent(), true);
            if(!$data){
                return $this->json(["message" => "Cannot access Data"], Response::HTTP_BAD_REQUEST);
            }

            $certification = new Certification();
            $certification->setName($data["name"]);
            $certification->setImage($data["image"]);

            $em->persist($certification);
            $em->flush();

            return $this->json(["message" => "Certification created with success."]);
        } catch (Exception $e) {
            return $this->json($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/{id}', name: "remove_certification", methods: ["DELETE"], requirements: ['id' => '\d+'])]
    function removeCertification(EntityManagerInterface $em, int $id): JsonResponse {
        try {
            $certification = $em->getRepository(Certification::class)->find($id);
            if(!$certification){
                return $this->json(["message" => "Oups, Bad Request"], Response::HTTP_BAD_REQUEST);
            }

            $em->remove($certification);
            $em->flush();
            return $this->json(["message" => "Certification " . $id . " removed with success."]);
        } catch (Exception $e) {
            return $this->json($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }
}
