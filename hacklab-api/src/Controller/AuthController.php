<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\DBAL\Exception as DBALException;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints as Assert;


#[Route('api/auth', name: 'app_auth')]
final class AuthController extends AbstractController
{
    #[Route('/register', name: 'register_auth', methods: ["POST"])]
    public function register(UserPasswordHasherInterface $hash, Request $req, EntityManagerInterface $em, ValidatorInterface $validator): JsonResponse
    {
        try {
            $data = json_decode($req->getContent(), true);
            if (!$data) {
                return $this->json(["message" => "Cannot access Data."], Response::HTTP_BAD_REQUEST);
            }

            $constraints = new Assert\Collection([
                'email' => [new Assert\NotBlank(), new Assert\Email(), new Assert\Type(type: 'string')],
                'gamertag' => [new Assert\NotBlank(), new Assert\Type(type: 'string'), new Assert\Length(max: 20)],
                'password' => [new Assert\NotBlank(), new Assert\Type(type: 'string')]
            ]);


            $errors = $validator->validate($data, $constraints);
            if (count($errors) > 0) {
                return $this->json(["message" => (string) $errors], Response::HTTP_BAD_REQUEST);
            }

            $existingUser = $em->getRepository(User::class)->findOneBy(['email' => $data['email']]);
            if ($existingUser) {
                return $this->json(["message" => "An error occured"], Response::HTTP_BAD_REQUEST);
            }

            $user = new User();
            $user->setEmail($data["email"]);
            $user->setGamertag($data["gamertag"]);
            $user->setPointEarn(0);

            $plainTextPassword = $data["password"];
            $hashPassword = $hash->hashPassword($user, $plainTextPassword);
            $user->setPassword($hashPassword);

            $em->persist($user);
            $em->flush();
            return $this->json(["message" => "User successfuly created."], Response::HTTP_CREATED);
        } catch (DBALException) {
            return $this->json(["message" => "Database error"], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (Exception) {
            return $this->json(["message" => "An error occured"], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/login', name: 'login_auth', methods: ["POST"])]
    public function login(): JsonResponse
    {
        // Cette méthode ne sera jamais appelée
        // Le firewall json_login intercepte avant
        throw new \Exception('Should not reach here');
    }
}
