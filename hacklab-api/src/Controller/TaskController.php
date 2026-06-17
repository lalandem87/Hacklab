<?php

namespace App\Controller;

use App\Entity\Task as Task;
use App\Entity\Course as Course;
use App\Entity\TaskImage as TaskImage;

use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('api/task', name: 'app_task')]
final class TaskController extends AbstractController
{

    #[Route('', name: 'all_tasks', methods: ["GET"])]
    function getTasks(EntityManagerInterface $em): JsonResponse
    {
        try {
            $tasks = $em->getRepository(Task::class)->findAll();
            if (empty($tasks)) {
                return $this->json(["message" => "Tasks not found"], Response::HTTP_BAD_REQUEST);
            }
            return $this->json($tasks, Response::HTTP_OK, [], ['groups' => 'course:read']);
        } catch (Exception $e) {
            return $this->json($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/{id}', name: 'get_task', methods: ["GET"])]
    function getTaskById(EntityManagerInterface $em, int $id): JsonResponse
    {
        try {
            $task = $em->getRepository(Task::class)->find($id);
            if (!$task) {
                return $this->json(["message" => "Oups, Bad Request"], Response::HTTP_BAD_REQUEST);
            }
            return $this->json($task, Response::HTTP_OK, [], ['groups' => 'course:read']);
        } catch (Exception $e) {
            return $this->json($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('', name: 'create_task', methods: ["POST"])]
    function createTask(EntityManagerInterface $em, Request $req): JsonResponse
    {
        try {
            $data = json_decode($req->getContent(), true);
            if (!$data) {
                return $this->json(["message" => "Cannot access Data."], Response::HTTP_BAD_REQUEST);
            }

            $task = new Task();
            $task->setName($data["name"]);
            $task->setContent($data["content"]);
            $task->setTaskOrder($data["taskOrder"]);
            $courseId = $data["course_id"];

            if (!$courseId) {
                return $this->json(["message" => "Oups, Bad Request"], Response::HTTP_BAD_REQUEST);
            }

            $course = $em->getRepository(Course::class)->find($courseId);
            $task->setCourse($course);

            $em->persist($task);
            $em->flush();

            return $this->json(["message" => "Task successfuly created."], Response::HTTP_CREATED);
        } catch (Exception $e) {
            return $this->json($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/{id}', name: 'edit_task', methods: ["PUT"])]
    function editTask(EntityManagerInterface $em, int $id, Request $req): JsonResponse {
        try {
            $data = json_decode($req->getContent(), true);
            if(!$data){
                return $this->json(["message" => "Cannot access Data"], Response::HTTP_BAD_REQUEST);
            }
            $task = $em->getRepository(Task::class)->find($id);
            if(!$task) {
                return $this->json(["message" => "Oups, Bad Request"], Response::HTTP_BAD_REQUEST);
            }

            $courseId = $data["course_id"];
            $course = $em->getRepository(Course::class)->find($courseId);
            if(!$course){
                return $this->json(["message" => "Oups, Bad Request"], Response::HTTP_BAD_REQUEST);
            }

            $task->setName($data["name"]);
            $task->setContent($data["content"]);
            $task->setTaskOrder($data["taskOrder"]);
            $task->setCourse($course);

            $em->persist($task);
            $em->flush();
            return $this->json(["message" => "Task " . $id . " successfuly updated."], Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->json($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/{id}', name: 'remove_task', methods: ["DELETE"])]
    function removeTask(EntityManagerInterface $em, int $id): JsonResponse
    {
        try {
            $task = $em->getRepository(Task::class)->find($id);
            if (!$task) {
                return $this->json(["message" => "Oups, Bad Request"], Response::HTTP_BAD_REQUEST);
            }

            $em->remove($task);
            $em->flush();
            return $this->json(["message" => "Task " . $id . " successfuly removed"], Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->json($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/{id}/image', name: 'set_task_image', methods: ["POST"])]
    function setTaskImage(EntityManagerInterface $em, int $id, Request $req): JsonResponse
    {
        try {
            $data = json_decode($req->getContent(), true);
            if (!$data) {
                return $this->json(["message" => "Cannot access Data"], Response::HTTP_BAD_REQUEST);
            }
            $task = $em->getRepository(Task::class)->find($id);
            if (!$task) {
                return $this->json(["message" => "Oups, Bad Request"], Response::HTTP_BAD_REQUEST);
            }

            $taskImage = new TaskImage();
            $taskImage->setImageUrl($data["imageUrl"]);
            $taskImage->setTask($task);

            $em->persist($taskImage);
            $em->flush();
            return $this->json(["message" => "TaskImage successfuly created."], Response::HTTP_CREATED);
        } catch (Exception $e) {
            return $this->json($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/image/{id}', name: 'remove_task_image', methods: ["DELETE"])]
    function removeCourseImage(EntityManagerInterface $em, int $id): JsonResponse
    {
        try {
            $taskImage = $em->getRepository(TaskImage::class)->find($id);
            if (!$taskImage) {
                return $this->json(["message" => "Oups, Bad Request"], Response::HTTP_BAD_REQUEST);
            }

            $em->remove($taskImage);
            $em->flush();
            return $this->json(["message" => "TaskImage " . $id . " successfuly removed."], Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->json($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }
}
