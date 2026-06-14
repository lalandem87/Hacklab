<?php

namespace App\Controller;

use App\Entity\Categorie as Categorie;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use SebastianBergmann\Type\TrueType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/categorie', name: 'app_categorie')]
final class CategorieController extends AbstractController
{
    #[Route('', name: 'all_categories', methods: ["GET"])]
    function getCategories(EntityManagerInterface $em): JsonResponse {
        try {
            $categories = $em->getRepository(Categorie::class)->findAll();
            return $this->json($categories);
        } catch (Exception $e) {
            return $this->json($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/{id}', name: 'categorie_id', methods: ["GET"])]
    function getCategorieById(EntityManagerInterface $em,  int $id ): JsonResponse {
        try {
            $categorie = $em->getRepository(Categorie::class)->find($id);
            if(!$categorie){
              return $this->json(["message" => "Oups, Bad Request"], Response::HTTP_BAD_REQUEST); 
            }
            return $this->json($categorie, Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->json($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }


    #[Route('', name: 'create_categorie', methods: ["POST"])]
    function CreateCategorie(Request $req, EntityManagerInterface $em): JsonResponse {
        try {
            $data = json_decode($req->getContent(), true);
            
            if(!$data) {
                return $this->json(["message" => "Cannot access Data"], Response::HTTP_BAD_REQUEST);
            }

            $categorie = new Categorie(); 
            $categorie->setName($data["name"]);

            $em->persist($categorie);
            $em->flush();

            return $this->json(["message" => "Categorie created with success."], Response::HTTP_CREATED);

        } catch (Exception $e) {
            return $this->json($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/{id}', name: 'remove_categorie', methods: ["DELETE"])]
    function removeCategorie(EntityManagerInterface $em, int $id): JsonResponse {
        try {
            $categorie = $em->getRepository(Categorie::class)->find($id);
            if(!$categorie){
                return $this->json(["message" => "Oups, Bad Request"], Response::HTTP_BAD_REQUEST);
            }

            $em->remove($categorie);
            $em->flush();

            return $this->json(["message" => "Categorie " . $id . " removed with success."]);
        } catch (Exception $e) {
           return $this->json($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }
}
