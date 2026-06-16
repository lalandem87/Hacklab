<?php

namespace App\Controller;

use App\Entity\Course as Course;

use Doctrine\ORM\EntityManagerInterface;
use Exception;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('api/course', name: 'app_course')]
final class CourseController extends AbstractController
{

    #[Route('', name: 'all_courses', methods: ["GET"])]
    function getCourses(EntityManagerInterface $em): JsonResponse
    {
        try {
            $course = $em->getRepository(Course::class)->findAll();
            if (empty($course)) {
                return $this->json(["message" => "Courses not found."], Response::HTTP_BAD_REQUEST);
            }
            return $this->json($course, Response::HTTP_OK, [], ['groups' => 'course:read']);
        } catch (Exception $e) {
            return $this->json($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/{id}', name: 'get_course', methods: ["GET"])]
    function getCourseById(EntityManagerInterface $em, int $id): JsonResponse
    {
        try {
            $course = $em->getRepository(Course::class)->find($id);
            if (!$course) {
                return $this->json(["message" => "Oups, Bad Request"], Response::HTTP_BAD_REQUEST);
            }
            return $this->json($course, Response::HTTP_OK, [], ['groups' => 'course:read']);
        } catch (Exception $e) {
            return $this->json($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('', name: 'create_course', methods: ["POST"])]
    function createCourse(EntityManagerInterface $em, Request $req): JsonResponse
    {
        try {
            $data = json_decode($req->getContent(), true);
            if (!$data) {
                return $this->json(["message" => "Cannot access Data."], Response::HTTP_BAD_REQUEST);
            }

            $course = new Course();
            $course->setName($data["name"]);
            $course->setContent($data["content"]);
            $course->setPoint($data["point"]);

            $em->persist($course);
            $em->flush();

            return $this->json(["message" => "Course successfuly created."], Response::HTTP_CREATED);
        } catch (Exception $e) {
            return $this->json($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/{id}', name: 'remove_course', methods: ["DELETE"])]
    function removeCourse(EntityManagerInterface $em, int $id): JsonResponse
    {
        try {
            $course = $em->getRepository(Course::class)->find($id);
            if (!$course) {
                return $this->json(["message" => "Oups, Bad Request"], Response::HTTP_BAD_REQUEST);
            }

            $em->remove($course);
            $em->flush();

            return $this->json(["message" => "Course " . $id . " successfuly removed"], Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->json($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }
}
