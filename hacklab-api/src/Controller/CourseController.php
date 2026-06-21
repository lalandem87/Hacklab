<?php

namespace App\Controller;

use App\Entity\Course as Course;
use App\Entity\CourseImage as CourseImage;

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

#[Route('api/course', name: 'app_course')]
final class CourseController extends AbstractController
{

    #[Route('', name: 'all_courses', methods: ["GET"])]
    function getCourses(EntityManagerInterface $em): JsonResponse
    {
        try {
            $course = $em->getRepository(Course::class)->findAll();
            if (empty($course)) {
                return $this->json(["message" => "Courses not found."], Response::HTTP_NOT_FOUND);
            }
            return $this->json($course, Response::HTTP_OK, [], ['groups' => 'course:read']);
        } catch (DBALException) {
            return $this->json(["message" => "Database error"], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (Exception) {
            return $this->json(["message" => "An error occured"], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/{id}', name: 'get_course', methods: ["GET"], requirements: ['id' => '\d+'])]
    function getCourseById(EntityManagerInterface $em, int $id): JsonResponse
    {
        try {
            $course = $em->getRepository(Course::class)->find($id);
            if (!$course) {
                return $this->json(["message" => "Course not found in database"], Response::HTTP_NOT_FOUND);
            }
            return $this->json($course, Response::HTTP_OK, [], ['groups' => 'course:read']);
        } catch (DBALException) {
            return $this->json(["message" => "Database error"], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (Exception) {
            return $this->json(["message" => "An error occured"], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('', name: 'create_course', methods: ["POST"])]
    #[IsGranted('ROLE_ADMIN')]
    function createCourse(EntityManagerInterface $em, Request $req, ValidatorInterface $validator): JsonResponse
    {
        try {
            $data = json_decode($req->getContent(), true);
            if (!$data) {
                return $this->json(["message" => "Cannot access Data."], Response::HTTP_BAD_REQUEST);
            }

            $constraints = new Assert\Collection([
                'name' => [new Assert\NotBlank(), new Assert\Type(type: 'string'), new Assert\Length(max: 40)],
                'point' => [new Assert\NotBlank(), new Assert\Type(type: 'integer')]
            ]);

            $errors = $validator->validate($data, $constraints);
            if (count($errors) > 0) {
                return $this->json(["message" => (string) $errors], Response::HTTP_BAD_REQUEST);
            }

            $course = new Course();
            $course->setName($data["name"]);
            $course->setPoint($data["point"]);

            $em->persist($course);
            $em->flush();

            return $this->json(["message" => "Course successfuly created."], Response::HTTP_CREATED);
        } catch (DBALException) {
            return $this->json(["message" => "Database error"], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (Exception) {
            return $this->json(["message" => "An error occured"], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/{id}', name: 'remove_course', methods: ["DELETE"], requirements: ['id' => '\d+'])]
    #[IsGranted('ROLE_ADMIN')]
    function removeCourse(EntityManagerInterface $em, int $id): JsonResponse
    {
        try {
            $course = $em->getRepository(Course::class)->find($id);
            if (!$course) {
                return $this->json(["message" => "Course not found in database"], Response::HTTP_NOT_FOUND);
            }

            $em->remove($course);
            $em->flush();

            return $this->json(["message" => "Course " . $id . " successfuly removed"], Response::HTTP_OK);
        } catch (DBALException) {
            return $this->json(["message" => "Database error"], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (Exception) {
            return $this->json(["message" => "An error occured"], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/{id}/image', name: 'set_course_image', methods: ["POST"], requirements: ['id' => '\d+'])]
    #[IsGranted('ROLE_ADMIN')]
    function setCourseImage(EntityManagerInterface $em, int $id, Request $req, ValidatorInterface $validator): JsonResponse
    {
        try {
            $course = $em->getRepository(Course::class)->find($id);
            if (!$course) {
                return $this->json(["message" => "Course not found in database"], Response::HTTP_NOT_FOUND);
            }

            $data = json_decode($req->getContent(), true);
            if (!$data) {
                return $this->json(["message" => "Cannot access Data."], Response::HTTP_BAD_REQUEST);
            }

            $constraints = new Assert\Collection([
                'imageUrl' => [new Assert\NotBlank(), new Assert\Type(type: 'string')]
            ]);

            $errors = $validator->validate($data, $constraints);
            if (count($errors) > 0) {
                return $this->json(["message" => (string) $errors], Response::HTTP_BAD_REQUEST);
            }

            $courseImage = new CourseImage();
            $courseImage->setImageUrl($data["imageUrl"]);
            $courseImage->setCourse($course);

            $em->persist($courseImage);
            $em->flush();

            return $this->json(["message" => "CourseImage successfuly created."], Response::HTTP_CREATED);
        } catch (DBALException) {
            return $this->json(["message" => "Database error"], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (Exception) {
            return $this->json(["message" => "An error occured"], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/image/{id}', name: 'del_course_image', methods: ["DELETE"], requirements: ['id' => '\d+'])]
    #[IsGranted('ROLE_ADMIN')]
    function removeCourseImage(EntityManagerInterface $em, int $id): JsonResponse
    {
        try {
            $courseImage = $em->getRepository(CourseImage::class)->find($id);
            if (!$courseImage) {
                return $this->json(["message" => "Course image not found in database"], Response::HTTP_NOT_FOUND);
            }

            $em->remove($courseImage);
            $em->flush();
            return $this->json(["message" => "CourseImage " . $id . " successfuly removed."], Response::HTTP_OK);
        } catch (DBALException) {
            return $this->json(["message" => "Database error"], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (Exception) {
            return $this->json(["message" => "An error occured"], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
