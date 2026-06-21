<?php

namespace App\Controller;

use App\Entity\Categorie as Categorie;
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

#[Route('/api/categorie', name: 'app_categorie')]
final class CategorieController extends AbstractController
{
    #[Route('', name: 'all_categories', methods: ["GET"])]
    function getCategories(EntityManagerInterface $em): JsonResponse
    {
        try {
            $categories = $em->getRepository(Categorie::class)->findAll();
            if (empty($categories)) {
                return $this->json(["message" => "Categorie Entity is empty"], Response::HTTP_BAD_REQUEST);
            }
            return $this->json($categories);
        } catch (DBALException) {
            return $this->json(["message" => "Database error"], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (Exception) {
            return $this->json(["message" => "An error occured"], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/{id}', name: 'categorie_id', methods: ["GET"], requirements: ['id' => '\d+'])]
    function getCategorieById(EntityManagerInterface $em,  int $id): JsonResponse
    {
        try {
            $categorie = $em->getRepository(Categorie::class)->find($id);
            if (!$categorie) {
                return $this->json(["message" => "Categorie not found in database"], Response::HTTP_NOT_FOUND);
            }
            return $this->json($categorie, Response::HTTP_OK);
        } catch (DBALException) {
            return $this->json(["message" => "Database error"], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (Exception) {
            return $this->json(["message" => "An error occured"], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    #[Route('', name: 'create_categorie', methods: ["POST"])]
    #[IsGranted('ROLE_ADMIN')]
    function CreateCategorie(Request $req, EntityManagerInterface $em, ValidatorInterface $validator): JsonResponse
    {
        try {
            $data = json_decode($req->getContent(), true);

            if (!$data) {
                return $this->json(["message" => "Cannot access Data"], Response::HTTP_BAD_REQUEST);
            }

            $constraints = new Assert\Collection([
                'name' => [new Assert\NotBlank(), new Assert\Type(type: 'string'), new Assert\Length(max: 20)]
            ]);

            $errors = $validator->validate($data, $constraints);
            if (count($errors) > 0) {
                return $this->json(["message" => (string) $errors], Response::HTTP_BAD_REQUEST);
            }

            $categorie = new Categorie();
            $categorie->setName($data["name"]);

            $em->persist($categorie);
            $em->flush();

            return $this->json(["message" => "Categorie created with success."], Response::HTTP_CREATED);
        } catch (DBALException) {
            return $this->json(["message" => "Database error"], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (Exception) {
            return $this->json(["message" => "An error occured"], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/{id}', name: 'remove_categorie', methods: ["DELETE"], requirements: ['id' => '\d+'])]
    #[IsGranted('ROLE_ADMIN')]
    function removeCategorie(EntityManagerInterface $em, int $id): JsonResponse
    {
        try {
            $categorie = $em->getRepository(Categorie::class)->find($id);
            if (!$categorie) {
                return $this->json(["message" => "Categorie not found in database"], Response::HTTP_NOT_FOUND);
            }

            $em->remove($categorie);
            $em->flush();

            return $this->json(["message" => "Categorie " . $id . " removed with success."]);
        } catch (DBALException) {
            return $this->json(["message" => "Database error"], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (Exception) {
            return $this->json(["message" => "An error occured"], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
