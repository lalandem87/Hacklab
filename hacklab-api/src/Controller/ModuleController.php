<?php

namespace App\Controller;

use App\Entity\Module;
use App\Entity\Course;
use App\Entity\Challenge;
use App\Entity\Categorie;
use App\Entity\Certification;
use App\Entity\UserCertification;
use App\Entity\UserModule;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('api/module', name: 'app_module')]
final class ModuleController extends AbstractController
{
    #[Route('', name: 'all_modules', methods: ["GET"])]
    function getModules(EntityManagerInterface $em): JsonResponse
    {
        try {
            $modules = $em->getRepository(Module::class)->findAll();
            if (empty($modules)) {
                return $this->json(["message" => "Modules not found"], Response::HTTP_NOT_FOUND);
            }

            return $this->json($modules, Response::HTTP_OK, [], ['groups' => 'module:read']);
        } catch (Exception $e) {
            return $this->json($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/{id}', name: 'get_module', methods: ["GET"], requirements: ['id' => '\d+'])]
    function getModuleById(EntityManagerInterface $em, int $id): JsonResponse
    {
        try {
            $module = $em->getRepository(Module::class)->find($id);
            if (!$module) {
                return $this->json(["message" => "Module not found in database"], Response::HTTP_NOT_FOUND);
            }
            return $this->json($module,  Response::HTTP_OK, [], ['groups' => 'module:read']);
        } catch (Exception $e) {
            return $this->json($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('', name: 'create_module', methods: ["POST"])]
    function createModule(EntityManagerInterface $em, Request $req, ValidatorInterface $validator): JsonResponse
    {
        try {
            $data = json_decode($req->getContent(), true);
            if (!$data) {
                return $this->json(["message" => "Cannot access Data"], Response::HTTP_BAD_REQUEST);
            }

            $constraints = new Assert\Collection([
                'name' => [new Assert\NotBlank(), new Assert\Length(max: 60), new Assert\Type(type: 'string')],
                'description' => [new Assert\NotBlank(), new Assert\Type(type: 'string')],
                'difficulty' => [new Assert\NotBlank(), new Assert\Type(type: 'string')],
                'course_id' => [new Assert\NotBlank(), new Assert\Type(type: 'integer')],
                'challenge_id' => [new Assert\NotBlank(), new Assert\Type(type: 'integer')],
                'categorie_id' => [new Assert\NotBlank(), new Assert\Type(type: 'integer')],
            ]);

            $errors = $validator->validate($data, $constraints);
            if (count($errors) > 0) {
                return $this->json(["message" => (string) $errors], Response::HTTP_BAD_REQUEST);
            }

            $course = $em->getRepository(Course::class)->find($data["course_id"]);
            if (!$course) {
                return $this->json(["message" => "Course not found in database"], Response::HTTP_NOT_FOUND);
            }

            $challenge = $em->getRepository(Challenge::class)->find($data["challenge_id"]);
            if (!$challenge) {
                return $this->json(["message" => "Challenge not found in database"], Response::HTTP_NOT_FOUND);
            }

            $categorie = $em->getRepository(Categorie::class)->find($data["categorie_id"]);
            if (!$categorie) {
                return $this->json(["message" => "Categorie not found in database"], Response::HTTP_NOT_FOUND);
            }

            $module = new Module();
            $module->setName($data["name"]);
            $module->setCourse($course);
            $module->setChallenge($challenge);
            $module->setCategorie($categorie);
            $module->setDescription($data["description"]);
            $module->setDifficulty($data["difficulty"]);

            $em->persist($module);
            $em->flush();
            return $this->json(["message" => "Module successfuly created."], Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->json($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/{id}/submit', name: 'submit_module', methods: ["POST"], requirements: ['id' => '\d+'])]
    function SubmitModule(EntityManagerInterface $em, int $id, Security $sec, Request $req, ValidatorInterface $validator): JsonResponse
    {
        try {

            $data = json_decode($req->getContent(), true);
            if (!$data) {
                return $this->json(["message" => "Cannot access Data."], Response::HTTP_BAD_REQUEST);
            }

            $constraints = new Assert\Collection([
                'submittedFlag' => [new Assert\NotBlank(), new Assert\Type(type: 'string')]
            ]);

            $errors = $validator->validate($data, $constraints);
            if( count($errors) > 0){
                return $this->json(["message" => (string) $errors], Response::HTTP_BAD_REQUEST);
            }

            $module = $em->getRepository(Module::class)->find($id);
            if (!$module) {
                return $this->json(["message" => "Module not found in database"], Response::HTTP_NOT_FOUND);
            }

            $user = $sec->getUser();
            /** @var \App\Entity\User $user */
            $submittedFlag = $data["submittedFlag"];
            if ($module->getChallenge()->getFlag() === $submittedFlag) {
                $user->setPointEarn($user->getPointEarn() + $module->getChallenge()->getPoint());

                $userModule = new UserModule();
                $userModule->setUsr($user);
                $userModule->setModule($module);
                $userModule->setSolved(true);
                $userModule->setSubmittedFlag($submittedFlag);
                $em->persist($userModule);
                $em->flush();

                $certification = $em->getRepository(Certification::class)->findOneBy(['module' => $module]);
                if ($certification) {
                    $userCertifications = new UserCertification();
                    $userCertifications->setUsr($user);
                    $userCertifications->setCertification($certification);
                    $em->persist($userCertifications);
                    $em->flush();
                }
                $em->persist($user);
                $em->flush();
                return $this->json(["message" => "Module " . $id . " successfuly done"]);
            }

            return $this->json(["message" => "Sorry, Wrong answer", "solved" => false], Response::HTTP_BAD_REQUEST);
        } catch (Exception $e) {
            return $this->json($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/{id}', name: 'remove_module', methods: ["DELETE"], requirements: ['id' => '\d+'])]
    function removeModule(EntityManagerInterface $em, int $id): JsonResponse
    {
        try {
            $module = $em->getRepository(Module::class)->find($id);
            if (!$module) {
                return $this->json(["message" => "Module not found in database"], Response::HTTP_NOT_FOUND);
            }

            $em->remove($module);
            $em->flush();
            return $this->json(["message" => "Module " . $id . " successfuly removed"], Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->json($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }
}
