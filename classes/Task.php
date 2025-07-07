<?php

namespace local_todolist\classes;

class Task {
    public function __construct(
        private readonly int $id,
        private string $name,
        private bool $completed = false
    ) {
        if ($this->id <= 0) {
            throw new \InvalidArgumentException('Task ID must be a positive integer');
        }

        $trimmed = trim($this->name);
        if ($trimmed === '') {
            throw new \InvalidArgumentException('Task name cannot be empty');
        }

        $this->name = $trimmed;
    }

    public function getId(): int {
        return $this->id;
    }

    public function getName(): string {
        return $this->name;
    }

    public function isCompleted(): bool {
        return $this->completed;
    }

    public function rename(string $name): void {
        $name = trim($name);
        if ($name === '') {
            throw new \InvalidArgumentException('Task name cannot be empty');
        }
        $this->name = $name;
    }

    public function markPending(): void {
        $this->completed = false;
    }

    public function markCompleted(): void {
        $this->completed = true;
    }
}
