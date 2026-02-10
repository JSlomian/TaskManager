<?php

declare(strict_types=1);

namespace App\Tests\Domain\Strategy;

use App\Domain\Model\ValueObject\TaskStatus;
use App\Domain\Strategy\DefaultTaskWorkflowStrategy;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

final class DefaultTaskWorkflowStrategyTest extends TestCase
{
    private DefaultTaskWorkflowStrategy $strategy;

    protected function setUp(): void
    {
        $this->strategy = new DefaultTaskWorkflowStrategy();
    }

    #[DataProvider('provideValidTransitions')]
    public function testValidTransitions(TaskStatus $from, TaskStatus $to): void
    {
        $this->strategy->assertCanTransition($from, $to);
        $this->assertTrue(true);
    }

    public static function provideValidTransitions(): array
    {
        return [
            'ToDo to InProgress' => [TaskStatus::ToDo, TaskStatus::InProgress],
            'InProgress to Done' => [TaskStatus::InProgress, TaskStatus::Done],
        ];
    }

    #[DataProvider('provideInvalidTransitions')]
    public function testInvalidTransitions(TaskStatus $from, TaskStatus $to): void
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage(sprintf('Cannot transition task status from %s to %s', $from->value, $to->value));

        $this->strategy->assertCanTransition($from, $to);
    }

    public static function provideInvalidTransitions(): array
    {
        return [
            'ToDo to Done' => [TaskStatus::ToDo, TaskStatus::Done],
            'InProgress to ToDo' => [TaskStatus::InProgress, TaskStatus::ToDo],
            'Done to ToDo' => [TaskStatus::Done, TaskStatus::ToDo],
            'Done to InProgress' => [TaskStatus::Done, TaskStatus::InProgress],
            'ToDo to ToDo' => [TaskStatus::ToDo, TaskStatus::ToDo],
            'InProgress to InProgress' => [TaskStatus::InProgress, TaskStatus::InProgress],
            'Done to Done' => [TaskStatus::Done, TaskStatus::Done],
        ];
    }
}
