<?php

namespace App\Controller;

use App\Entity\Task as Task;
use App\Entity\Course as Course;
use App\Entity\TaskImage as TaskImage;
use App\Entity\TaskQuestion;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Doctrine\DBAL\Exception as DBALException;

#[Route('api/task', name: 'app_task')]
final class TaskController extends AbstractController
{

    #[Route('', name: 'all_tasks', methods: ["GET"])]
    function getTasks(EntityManagerInterface $em): JsonResponse
    {
        try {
            $tasks = $em->getRepository(Task::class)->findAll();
            if (empty($tasks)) {
                return $this->json(["message" => "Tasks not found"], Response::HTTP_NOT_FOUND);
            }
            return $this->json($tasks, Response::HTTP_OK, [], ['groups' => 'course:read']);
        } catch (DBALException) {
            return $this->json(["message" => "Database error"], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (Exception) {
            return $this->json(["message" => "An error occured"], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/{id}', name: 'get_task', methods: ["GET"], requirements: ['id' => '\d+'])]
    function getTaskById(EntityManagerInterface $em, int $id): JsonResponse
    {
        try {
            $task = $em->getRepository(Task::class)->find($id);
            if (!$task) {
                return $this->json(["message" => "Task not found in database"], Response::HTTP_NOT_FOUND);
            }
            return $this->json($task, Response::HTTP_OK, [], ['groups' => 'course:read']);
        } catch (DBALException) {
            return $this->json(["message" => "Database error"], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (Exception) {
            return $this->json(["message" => "An error occured"], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('', name: 'create_task', methods: ["POST"])]
    #[IsGranted('ROLE_ADMIN')]
    function createTask(EntityManagerInterface $em, Request $req, ValidatorInterface $validator): JsonResponse
    {
        try {
            $data = json_decode($req->getContent(), true);
            if (!$data) {
                return $this->json(["message" => "Cannot access Data."], Response::HTTP_BAD_REQUEST);
            }

            $constraints = new Assert\Collection([
                'name' => [new Assert\NotBlank(), new Assert\Type(type: 'string'), new Assert\Length(max: 20)],
                'content' => [new Assert\NotBlank(), new Assert\Type(type: 'string')],
                'taskOrder' => [new Assert\NotBlank(), new Assert\Type(type: 'integer')],
                'course_id' => [new Assert\NotBlank(), new Assert\Type(type: 'integer')],
                'taskQuestion_id' => [new Assert\NotBlank(), new Assert\Type(type: 'integer')]
            ]);

            $errors = $validator->validate($data, $constraints);
            if (count($errors) > 0) {
                return $this->json(["message" => (string) $errors], Response::HTTP_BAD_REQUEST);
            }

            $task = new Task();
            $task->setName($data["name"]);
            $task->setContent($data["content"]);
            $task->setTaskOrder($data["taskOrder"]);
            $courseId = $data["course_id"];
            $taskQuestionId = $data["taskQuestion_id"];

            $taskQuestion = $em->getRepository(TaskQuestion::class)->find($taskQuestionId);
            if (!$taskQuestion) {
                return $this->json(["message" => "Task question not found in database"], Response::HTTP_NOT_FOUND);
            }
            $task->addTaskQuestion($taskQuestion);

            $course = $em->getRepository(Course::class)->find($courseId);
            $task->setCourse($course);

            $em->persist($task);
            $em->flush();

            return $this->json(["message" => "Task successfuly created."], Response::HTTP_CREATED);
        } catch (DBALException) {
            return $this->json(["message" => "Database error"], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (Exception) {
            return $this->json(["message" => "An error occured"], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/{id}', name: 'edit_task', methods: ["PUT"], requirements: ['id' => '\d+'])]
    #[IsGranted('ROLE_ADMIN')]
    function editTask(EntityManagerInterface $em, int $id, Request $req, ValidatorInterface $validator): JsonResponse
    {
        try {
            $data = json_decode($req->getContent(), true);
            if (!$data) {
                return $this->json(["message" => "Cannot access Data"], Response::HTTP_BAD_REQUEST);
            }

            $constraints = new Assert\Collection([
                'name' => [new Assert\NotBlank(), new Assert\Type(type: 'string'), new Assert\Length(max: 20)],
                'content' => [new Assert\NotBlank(), new Assert\Type(type: 'string')],
                'taskOrder' => [new Assert\NotBlank(), new Assert\Type(type: 'integer')],
                'course_id' => [new Assert\NotBlank(), new Assert\Type(type: 'integer')],
            ]);

            $errors = $validator->validate($data, $constraints);
            if (count($errors) > 0) {
                return $this->json(["message" => (string) $errors], Response::HTTP_BAD_REQUEST);
            }

            $task = $em->getRepository(Task::class)->find($id);
            if (!$task) {
                return $this->json(["message" => "Task not found in database"], Response::HTTP_NOT_FOUND);
            }

            $course = $em->getRepository(Course::class)->find($data["course_id"]);
            if (!$course) {
                return $this->json(["message" => "Course not found"], Response::HTTP_NOT_FOUND);
            }

            $task->setName($data["name"]);
            $task->setContent($data["content"]);
            $task->setTaskOrder($data["taskOrder"]);
            $task->setCourse($course);

            $em->persist($task);
            $em->flush();
            return $this->json(["message" => "Task " . $id . " successfuly updated."], Response::HTTP_OK);
        } catch (DBALException) {
            return $this->json(["message" => "Database error"], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (Exception) {
            return $this->json(["message" => "An error occured"], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    #[Route('/{id}', name: 'remove_task', methods: ["DELETE"], requirements: ['id' => '\d+'])]
    #[IsGranted('ROLE_ADMIN')]
    function removeTask(EntityManagerInterface $em, int $id): JsonResponse
    {
        try {
            $task = $em->getRepository(Task::class)->find($id);
            if (!$task) {
                return $this->json(["message" => "Task not found in database"], Response::HTTP_NOT_FOUND);
            }

            $em->remove($task);
            $em->flush();
            return $this->json(["message" => "Task " . $id . " successfuly removed"], Response::HTTP_OK);
        } catch (DBALException) {
            return $this->json(["message" => "Database error"], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (Exception) {
            return $this->json(["message" => "An error occured"], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/{id}/image', name: 'set_task_image', methods: ["POST"], requirements: ['id' => '\d+'])]
    #[IsGranted('ROLE_ADMIN')]
    function setTaskImage(EntityManagerInterface $em, int $id, Request $req, ValidatorInterface $validator): JsonResponse
    {
        try {
            $data = json_decode($req->getContent(), true);
            if (!$data) {
                return $this->json(["message" => "Cannot access Data"], Response::HTTP_BAD_REQUEST);
            }

            $constraints = new Assert\Collection([
                'imageUrl' => [new Assert\NotBlank(), new Assert\Type(type: 'string'), new Assert\Url()],
            ]);

            $errors = $validator->validate($data, $constraints);
            if (count($errors) > 0) {
                return $this->json(["message" => (string) $errors], Response::HTTP_BAD_REQUEST);
            }

            $task = $em->getRepository(Task::class)->find($id);
            if (!$task) {
                return $this->json(["message" => "Task not found in database"], Response::HTTP_NOT_FOUND);
            }

            $taskImage = new TaskImage();
            $taskImage->setImageUrl($data["imageUrl"]);
            $taskImage->setTask($task);

            $em->persist($taskImage);
            $em->flush();
            return $this->json(["message" => "TaskImage successfuly created."], Response::HTTP_CREATED);
        } catch (DBALException) {
            return $this->json(["message" => "Database error"], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (Exception) {
            return $this->json(["message" => "An error occured"], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    #[Route('/image/{id}', name: 'remove_task_image', methods: ["DELETE"], requirements: ['id' => '\d+'])]
    #[IsGranted('ROLE_ADMIN')]
    function removeCourseImage(EntityManagerInterface $em, int $id): JsonResponse
    {
        try {
            $taskImage = $em->getRepository(TaskImage::class)->find($id);
            if (!$taskImage) {
                return $this->json(["message" => "Task image not found in database"], Response::HTTP_NOT_FOUND);
            }

            $em->remove($taskImage);
            $em->flush();
            return $this->json(["message" => "TaskImage " . $id . " successfuly removed."], Response::HTTP_OK);
        } catch (DBALException) {
            return $this->json(["message" => "Database error"], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (Exception) {
            return $this->json(["message" => "An error occured"], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/questions', name: 'all_question', methods: ["GET"])]
    function getTaskQuestion(EntityManagerInterface $em): JsonResponse
    {
        try {
            $taskQuestion = $em->getRepository(TaskQuestion::class)->findAll();
            if (empty($taskQuestion)) {
                return $this->json(["message" => "Entity Task question is empty"], Response::HTTP_NOT_FOUND);
            }
            return $this->json($taskQuestion);
        } catch (DBALException) {
            return $this->json(["message" => "Database error"], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (Exception) {
            return $this->json(["message" => "An error occured"], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/question/{id}', name: 'get_task_question', methods: ["GET"], requirements: ['id' => '\d+'])]
    function getTaskQuestionById(EntityManagerInterface $em, int $id): JsonResponse
    {
        try {
            $taskQuestion = $em->getRepository(TaskQuestion::class)->find($id);
            if (!$taskQuestion) {
                return $this->json(["message" => "Task question not found"], Response::HTTP_NOT_FOUND);
            }
            return $this->json($taskQuestion);
        } catch (DBALException) {
            return $this->json(["message" => "Database error"], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (Exception) {
            return $this->json(["message" => "An error occured"], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/questions', name: 'create_task_question', methods: ["POST"])]
    #[IsGranted('ROLE_ADMIN')]
    function createTaskQuestion(EntityManagerInterface $em, Request $req, ValidatorInterface $validator): JsonResponse
    {
        try {
            $data = json_decode($req->getContent(), true);
            if (!$data) {
                return $this->json(["message" => "Cannot access Data"], Response::HTTP_NOT_FOUND);
            }

            $constraints = new Assert\Collection([
                'name' => [new Assert\NotBlank(), new Assert\Length(max: 80)],
                'answer' => [new Assert\NotBlank()],
                'questionOrder' => [new Assert\NotBlank(), new Assert\Type('integer')],
                'task_id' => [new Assert\NotBlank(), new Assert\Type('integer')],
            ]);

            $errors = $validator->validate($data, $constraints);
            if (count($errors) > 0) {
                return $this->json(["message" => (string) $errors], Response::HTTP_BAD_REQUEST);
            }

            $taskQuestion = new TaskQuestion();
            $taskQuestion->setName($data["name"]);
            $taskQuestion->setAnswer($data["answer"]);
            $taskQuestion->setQuestionOrder($data["questionOrder"]);

            $task = $em->getRepository(Task::class)->find($data["task_id"]);
            if (!$task) {
                return $this->json(["message" => "Cannot found task in database"], Response::HTTP_NOT_FOUND);
            }
            $taskQuestion->setTask($task);
            $em->persist($taskQuestion);
            $em->flush();
            return $this->json(["message" => "taskQuestion create successfuly"], Response::HTTP_CREATED);
        } catch (DBALException) {
            return $this->json(["message" => "Database error"], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (Exception) {
            return $this->json(["message" => "An error occured"], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/questions/{id}', name: 'edit_task_question', methods: ["PUT"], requirements: ['id' => '\d+'])]
    #[IsGranted('ROLE_ADMIN')]
    function editTaskQuestion(EntityManagerInterface $em, int $id, Request $req, ValidatorInterface $validator): JsonResponse
    {
        try {
            $taskQuestion = $em->getRepository(TaskQuestion::class)->find($id);
            if (!$taskQuestion) {
                return $this->json(["message" => "Task question not found in database"], Response::HTTP_NOT_FOUND);
            }

            $data = json_decode($req->getContent(), true);
            if (!$data) {
                return $this->json(["message" => "Cannot access Data"], Response::HTTP_BAD_REQUEST);
            }

            $constraints = new Assert\Collection([
                'name' => [new Assert\NotBlank(), new Assert\Length(max: 80)],
                'answer' => [new Assert\NotBlank()],
                'questionOrder' => [new Assert\NotBlank(), new Assert\Type('integer')],
                'task_id' => [new Assert\NotBlank(), new Assert\Type('integer')],
            ]);

            $errors = $validator->validate($data, $constraints);
            if (count($errors) > 0) {
                return $this->json(["message" => (string) $errors], Response::HTTP_BAD_REQUEST);
            }

            $task = $em->getRepository(Task::class)->find($data["task_id"]);
            if (!$task) {
                return $this->json(["message" => "Cannot found task in database"], Response::HTTP_NOT_FOUND);
            }

            $taskQuestion->setName($data["name"]);
            $taskQuestion->setAnswer($data["answer"]);
            $taskQuestion->setQuestionOrder($data["questionOrder"]);
            $taskQuestion->setTask($task);

            $em->persist($taskQuestion);
            $em->flush();
            return $this->json(["message" => "Task question modified successfuly"], Response::HTTP_OK);
        } catch (DBALException) {
            return $this->json(["message" => "Database error"], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (Exception) {
            return $this->json(["message" => "An error occured"], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/questions/{id}', name: 'remove_task_question', methods: ["DELETE"], requirements: ['id' => '\d+'])]
    #[IsGranted('ROLE_ADMIN')]
    function removeTaskQuestion(EntityManagerInterface $em, int $id): JsonResponse
    {
        try {
            $taskQuestion = $em->getRepository(TaskQuestion::class)->find($id);
            if (!$taskQuestion) {
                return $this->json(["message" => "Task question not found in database"], Response::HTTP_NOT_FOUND);
            }

            $em->remove($taskQuestion);
            $em->flush();
            return $this->json(["message" => "Task question removed successfuly"], Response::HTTP_OK);
        } catch (DBALException) {
            return $this->json(["message" => "Database error"], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (Exception) {
            return $this->json(["message" => "An error occured"], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
