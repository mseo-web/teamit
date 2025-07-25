<?php

namespace App\Tests\Controller;

use App\Controller\TaskController;
use App\Entity\Task;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class TaskControllerTest extends TestCase
{
    private function createContainerWithSerializer(array $responseData): ContainerInterface
    {
        $serializer = $this->createMock(SerializerInterface::class);
        $serializer->method('serialize')->willReturn(json_encode($responseData));

        $container = $this->createMock(ContainerInterface::class);
        $container->method('has')->with('serializer')->willReturn(true);
        $container->method('get')->with('serializer')->willReturn($serializer);

        return $container;
    }

    public function testCreateSuccess(): void
    {
        $request = new Request([], [], [], [], [], [], json_encode(['title' => 'Test Task']));
        $task = new Task();

        $serializer = $this->createMock(SerializerInterface::class);
        $serializer->method('deserialize')->willReturn($task);

        $validator = $this->createMock(ValidatorInterface::class);
        $validator->method('validate')->willReturn(new ConstraintViolationList());

        $em = $this->createMock(EntityManagerInterface::class);
        $em->expects($this->once())->method('persist')->with($task);
        $em->expects($this->once())->method('flush');

        $logger = $this->createMock(LoggerInterface::class);
        $controller = new TaskController($logger);
        $controller->setContainer($this->createContainerWithSerializer(['message' => 'Task created']));

        $response = $controller->create($request, $em, $serializer, $validator);

        $this->assertEquals(201, $response->getStatusCode());
    }

    public function testCreateValidationFailure(): void
    {
        $request = new Request([], [], [], [], [], [], json_encode(['title' => '']));
        $task = new Task();

        $violation = new ConstraintViolation(
            'Title cannot be empty',
            null,
            [],
            null,
            'title',
            '',
            null
        );

        $violations = new ConstraintViolationList([$violation]);

        $serializer = $this->createMock(SerializerInterface::class);
        $serializer->method('deserialize')->willReturn($task);

        $validator = $this->createMock(ValidatorInterface::class);
        $validator->method('validate')->willReturn($violations);

        $em = $this->createMock(EntityManagerInterface::class);
        $em->expects($this->never())->method('persist');
        $em->expects($this->never())->method('flush');

        $logger = $this->createMock(LoggerInterface::class);
        $controller = new TaskController($logger);
        $controller->setContainer($this->createContainerWithSerializer([
            'errors' => ['Title cannot be empty']
        ]));

        $response = $controller->create($request, $em, $serializer, $validator);

        $this->assertEquals(400, $response->getStatusCode());
    }

    public function testUpdateSuccess(): void
    {
        $request = new Request([], [], [], [], [], [], json_encode(['title' => 'Updated']));
        $task = new Task();

        $serializer = $this->createMock(SerializerInterface::class);
        $serializer->method('deserialize')->willReturn($task);

        $validator = $this->createMock(ValidatorInterface::class);
        $validator->method('validate')->willReturn(new ConstraintViolationList());

        $em = $this->createMock(EntityManagerInterface::class);
        $em->expects($this->once())->method('flush');

        $logger = $this->createMock(LoggerInterface::class);
        $controller = new TaskController($logger);
        $controller->setContainer($this->createContainerWithSerializer(['title' => 'Updated']));

        $response = $controller->update($request, $task, $em, $serializer, $validator);

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testDeleteSuccess(): void
    {
        $task = new Task();

        $em = $this->createMock(EntityManagerInterface::class);
        $em->expects($this->once())->method('remove')->with($task);
        $em->expects($this->once())->method('flush');

        $logger = $this->createMock(LoggerInterface::class);
        $controller = new TaskController($logger);

        $response = $controller->delete($task, $em);

        $this->assertEquals(204, $response->getStatusCode());
        $this->assertEquals('{}', $response->getContent()); // ← исправлено: ожидаем '{}'
    }

    public function testShowReturnsTask(): void
    {
        $task = new Task();

        $logger = $this->createMock(LoggerInterface::class);
        $controller = new TaskController($logger);
        $controller->setContainer($this->createContainerWithSerializer(['title' => 'Sample']));

        $response = $controller->show($task);

        $this->assertEquals(200, $response->getStatusCode());
        $data = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('title', $data);
    }

    public function testIndexReturnsPaginatedTasks(): void
    {
        $request = new Request([
            'page' => 1,
            'limit' => 2,
            'status' => 'new'
        ]);

        $repository = $this->createMock(TaskRepository::class);
        $repository->method('findPaginated')
                   ->with(1, 2, 'new')
                   ->willReturn([
                       ['title' => 'Task 1'],
                       ['title' => 'Task 2']
                   ]);

        $logger = $this->createMock(LoggerInterface::class);
        $controller = new TaskController($logger);
        $controller->setContainer($this->createContainerWithSerializer([
            ['title' => 'Task 1'],
            ['title' => 'Task 2']
        ]));

        $response = $controller->index($request, $repository);

        $this->assertEquals(200, $response->getStatusCode());
        $data = json_decode($response->getContent(), true);
        $this->assertCount(2, $data);
    }
}