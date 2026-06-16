<?php

namespace App\Controller;

use App\Entity\Module;

use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('api/module', name: 'app_module')]
final class ModuleController extends AbstractController
{
    #[Route('', name: 'all_modules', methods: ["GET"])]
    function getModules(EntityManagerInterface $em): JsonResponse {
        try {
            $modules = $em->getRepository(Module::class)->findAll();
            if(empty($modules)){
                return $this->json(["message" => "Modules not found"], Response::HTTP_BAD_REQUEST);
            }

            return $this->json($modules, Response::HTTP_OK, [], ['groups' => 'module:read']);
        } catch (Exception $e) {
            return $this->json($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }
    
}
