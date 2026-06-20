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
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[Route('api/certification', name: 'app_certification')]
final class CertificationController extends AbstractController
{

    #[Route('', name: "get_certifications", methods: ["GET"])]
    function getCertifications(EntityManagerInterface $em): JsonResponse
    {
        try {
            $certifications = $em->getRepository(Certification::class)->findAll();
            if (empty($certifications)) {
                return $this->json(["message" => "Certifications not found"], Response::HTTP_NOT_FOUND);
            }
            return $this->json($certifications);
        } catch (Exception $e) {
            return $this->json($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/{id}', name: "certification_id", methods: ["GET"], requirements: ['id' => '\d+'])]
    function getCertificationById(int $id, EntityManagerInterface $em): JsonResponse
    {
        try {
            $certification = $em->getRepository(Certification::class)->find($id);
            if (!$certification) {
                return $this->json(["message" => "Certification not found in database"], Response::HTTP_NOT_FOUND);
            }
            return $this->json($certification);
        } catch (Exception $e) {
            return $this->json($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('', name: "create_certification", methods: ["POST"])]
    function createCertification(EntityManagerInterface $em, Request $req, ValidatorInterface $validator): JsonResponse
    {
        try {
            $data = json_decode($req->getContent(), true);
            if (!$data) {
                return $this->json(["message" => "Cannot access Data"], Response::HTTP_BAD_REQUEST);
            }

            $constraints = new Assert\Collection([
                'name' => [new Assert\NotBlank(), new Assert\Type(type: 'string'), new Assert\Length(max: 20)],
                'image' => [new Assert\NotBlank(), new Assert\Type(type: 'string')],
            ]);

            $errors = $validator->validate($data, $constraints);

            if (count($errors) > 0) {
                return $this->json(["message" => (string) $errors], Response::HTTP_BAD_REQUEST);
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
    function removeCertification(EntityManagerInterface $em, int $id): JsonResponse
    {
        try {
            $certification = $em->getRepository(Certification::class)->find($id);
            if (!$certification) {
                return $this->json(["message" => "Certification not found in database"], Response::HTTP_NOT_FOUND);
            }

            $em->remove($certification);
            $em->flush();
            return $this->json(["message" => "Certification " . $id . " removed with success."]);
        } catch (Exception $e) {
            return $this->json($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }
}
