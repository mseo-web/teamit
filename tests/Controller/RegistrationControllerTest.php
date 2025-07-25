<?php

namespace App\Tests\Controller;

use App\Controller\RegistrationController;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Psr\Container\ContainerInterface;

class RegistrationControllerTest extends TestCase
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

    public function testRegisterSuccess(): void
    {
        $request = new Request([], [], [], [], [], [], json_encode([
            'email' => 'user@example.com',
            'password' => 'securepass'
        ]));

        $passwordHasher = $this->createMock(UserPasswordHasherInterface::class);
        $passwordHasher->method('hashPassword')->willReturn('hashed_pass');

        $validator = $this->createMock(ValidatorInterface::class);
        $validator->method('validate')->willReturn(new ConstraintViolationList());

        $em = $this->createMock(EntityManagerInterface::class);
        $em->expects($this->once())->method('persist');
        $em->expects($this->once())->method('flush');

        $controller = new RegistrationController();
        $controller->setContainer($this->createContainerWithSerializer([
            'message' => 'User registered successfully'
        ]));

        $response = $controller->register($request, $passwordHasher, $em, $validator);

        $this->assertEquals(201, $response->getStatusCode());
        $data = json_decode($response->getContent(), true);
        $this->assertEquals(['message' => 'User registered successfully'], $data);
    }

    public function testRegisterValidationErrors(): void
    {
        $request = new Request([], [], [], [], [], [], json_encode([
            'email' => '', // Пустой email
            'password' => 'short'
        ]));

        $passwordHasher = $this->createMock(UserPasswordHasherInterface::class);
        $passwordHasher->method('hashPassword')->willReturn('hashed_short');

        $violation1 = new ConstraintViolation(
            'Email cannot be blank',
            null,
            [], // ← исправленный параметр
            null,
            'email',
            '',
            null
        );

        $violation2 = new ConstraintViolation(
            'Password must be at least 8 characters',
            null,
            [],
            null,
            'password',
            '',
            null
        );

        $violations = new ConstraintViolationList([$violation1, $violation2]);

        $validator = $this->createMock(ValidatorInterface::class);
        $validator->method('validate')->willReturn($violations);

        $em = $this->createMock(EntityManagerInterface::class);
        $em->expects($this->never())->method('persist');
        $em->expects($this->never())->method('flush');

        $controller = new RegistrationController();
        $controller->setContainer($this->createContainerWithSerializer([
            'errors' => ['Email cannot be blank', 'Password must be at least 8 characters']
        ]));

        $response = $controller->register($request, $passwordHasher, $em, $validator);

        $this->assertEquals(400, $response->getStatusCode());
        $data = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('errors', $data);
        $this->assertContains('Email cannot be blank', $data['errors']);
        $this->assertContains('Password must be at least 8 characters', $data['errors']);
    }
}