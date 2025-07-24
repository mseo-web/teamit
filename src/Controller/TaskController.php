<?php

namespace App\Controller;

use App\Entity\Task;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/tasks')]
class TaskController extends AbstractController
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    #[Route('', name: 'task_list', methods: ['GET'])]
    public function index(Request $request, TaskRepository $taskRepository): JsonResponse
    {
        $page = $request->query->getInt('page', 1);
        $limit = $request->query->getInt('limit', 10);
        $status = $request->query->get('status');

        $tasks = $taskRepository->findPaginated($page, $limit, $status);
        
        return $this->json($tasks);
    }

    #[Route('', name: 'task_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em, SerializerInterface $serializer, ValidatorInterface $validator): JsonResponse
    {
        try {
            $task = $serializer->deserialize($request->getContent(), Task::class, 'json');

            $errors = $validator->validate($task);
            if (count($errors) > 0) {
                return $this->json($errors, 400);
            }

            $em->persist($task);
            $em->flush();

            return $this->json($task, 201);
        } catch (\Exception $e) {
            $this->logger->error('Error creating task: ' . $e->getMessage());
            return $this->json(['error' => 'An error occurred while creating the task.'], 500);
        }
    }

    #[Route('/{id}', name: 'task_show', methods: ['GET'])]
    public function show(Task $task): JsonResponse
    {
        return $this->json($task);
    }

    #[Route('/{id}', name: 'task_update', methods: ['PUT'])]
    public function update(Request $request, Task $task, EntityManagerInterface $em, SerializerInterface $serializer, ValidatorInterface $validator): JsonResponse
    {
        try {
            $updatedTask = $serializer->deserialize($request->getContent(), Task::class, 'json', ['object_to_populate' => $task]);

            $errors = $validator->validate($updatedTask);
            if (count($errors) > 0) {
                return $this->json($errors, 400);
            }

            $em->flush();

            return $this->json($updatedTask);
        } catch (\Exception $e) {
            $this->logger->error('Error updating task: ' . $e->getMessage());
            return $this->json(['error' => 'An error occurred while updating the task.'], 500);
        }
    }

    #[Route('/{id}', name: 'task_delete', methods: ['DELETE'])]
    public function delete(Task $task, EntityManagerInterface $em): JsonResponse
    {
        try {
            $em->remove($task);
            $em->flush();

            return new JsonResponse(null, 204);
        } catch (\Exception $e) {
            $this->logger->error('Error deleting task: ' . $e->getMessage());
            return $this->json(['error' => 'An error occurred while deleting the task.'], 500);
        }
    }
}
