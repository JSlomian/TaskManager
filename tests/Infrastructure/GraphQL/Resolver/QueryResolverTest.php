<?php

declare(strict_types=1);

namespace App\Tests\Infrastructure\GraphQL\Resolver;

use App\Infrastructure\Doctrine\Repository\EventEntityRepository;
use App\Infrastructure\Doctrine\Repository\TaskEntityRepository;
use App\Infrastructure\Doctrine\Repository\UserEntityRepository;
use App\Infrastructure\GraphQL\Resolver\QueryResolver;
use Overblog\GraphQLBundle\Definition\Argument;
use PHPUnit\Framework\Attributes\AllowMockObjectsWithoutExpectations;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

#[AllowMockObjectsWithoutExpectations]
final class QueryResolverTest extends TestCase
{
    private UserEntityRepository $userEntityRepository;
    private TaskEntityRepository $taskEntityRepository;
    private EventEntityRepository $eventEntityRepository;
    private RequestStack $requestStack;
    private SessionInterface $session;
    private QueryResolver $resolver;

    protected function setUp(): void
    {
        $this->userEntityRepository = $this->createMock(UserEntityRepository::class);
        $this->taskEntityRepository = $this->createMock(TaskEntityRepository::class);
        $this->eventEntityRepository = $this->createMock(EventEntityRepository::class);
        $this->requestStack = $this->createMock(RequestStack::class);
        $this->session = $this->createMock(SessionInterface::class);

        $this->resolver = new QueryResolver(
            $this->userEntityRepository,
            $this->taskEntityRepository,
            $this->eventEntityRepository,
            $this->requestStack
        );
    }

    public function testUsers(): void
    {
        $expectedUsers = [['id' => 1, 'name' => 'John']];
        $this->userEntityRepository->expects($this->once())
            ->method('findAll')
            ->willReturn($expectedUsers);

        $this->assertSame($expectedUsers, $this->resolver->users());
    }

    public function testMeSuccess(): void
    {
        $userId = 123;
        $expectedUser = ['id' => 123, 'username' => 'testuser'];

        $this->requestStack->expects($this->once())
            ->method('getSession')
            ->willReturn($this->session);

        $this->session->expects($this->once())
            ->method('get')
            ->with('user_id')
            ->willReturn($userId);

        $this->userEntityRepository->expects($this->once())
            ->method('findOneAsArray')
            ->with($userId)
            ->willReturn($expectedUser);

        $this->assertSame($expectedUser, $this->resolver->me());
    }

    public function testMeNotLoggedIn(): void
    {
        $this->requestStack->expects($this->once())
            ->method('getSession')
            ->willReturn($this->session);

        $this->session->expects($this->once())
            ->method('get')
            ->with('user_id')
            ->willReturn(null);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('User not logged it');

        $this->resolver->me();
    }

    public function testMeUserNotFound(): void
    {
        $userId = 123;

        $this->requestStack->expects($this->once())
            ->method('getSession')
            ->willReturn($this->session);

        $this->session->expects($this->once())
            ->method('get')
            ->with('user_id')
            ->willReturn($userId);

        $this->userEntityRepository->expects($this->once())
            ->method('findOneAsArray')
            ->with($userId)
            ->willReturn(null);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('User not found');

        $this->resolver->me();
    }

    public function testTasksAdminSuccess(): void
    {
        $userId = 1; // Admin
        $expectedTasks = [['id' => 1, 'title' => 'Task 1']];

        $this->requestStack->expects($this->once())
            ->method('getSession')
            ->willReturn($this->session);

        $this->session->expects($this->once())
            ->method('get')
            ->with('user_id')
            ->willReturn($userId);

        $this->taskEntityRepository->expects($this->once())
            ->method('findAll')
            ->willReturn($expectedTasks);

        $this->assertSame($expectedTasks, $this->resolver->tasks());
    }

    public function testTasksNotAdmin(): void
    {
        $userId = 2; // Not admin

        $this->requestStack->expects($this->once())
            ->method('getSession')
            ->willReturn($this->session);

        $this->session->expects($this->once())
            ->method('get')
            ->with('user_id')
            ->willReturn($userId);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('User not an admin');

        $this->resolver->tasks();
    }

    public function testTasksByUser(): void
    {
        $userId = 123;
        $expectedTasks = [['id' => 1, 'assignedUserId' => 123]];

        $this->taskEntityRepository->expects($this->once())
            ->method('findByAssignedUser')
            ->with($userId)
            ->willReturn($expectedTasks);

        $this->assertSame($expectedTasks, $this->resolver->tasksByUser($userId));
    }

    public function testEvents(): void
    {
        $expectedEvents = [['id' => 1, 'type' => 'created']];
        $this->eventEntityRepository->expects($this->once())
            ->method('findAll')
            ->willReturn($expectedEvents);

        $this->assertSame($expectedEvents, $this->resolver->events());
    }

    public function testEventsForTask(): void
    {
        $argsArray = ['eventType' => 'Task', 'aggregateId' => 10];
        $args = new Argument($argsArray);

        $expectedEvents = [['id' => 1, 'aggregateType' => 'Task', 'aggregateId' => 10]];

        $this->eventEntityRepository->expects($this->once())
            ->method('findByAggregate')
            ->with('Task', 10)
            ->willReturn($expectedEvents);

        $this->assertSame($expectedEvents, $this->resolver->eventsForTask($args));
    }
}
