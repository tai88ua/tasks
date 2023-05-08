<?php

namespace App\Service;

use App\Dto\TaskDto;

class TaskService
{

    public function getTask(int $id) : ?TaskDto
    {
        $dto = new TaskDto();
        $dto->setTitle('Test lolo');
        $dto->setDescription('Test descr');
        $dto->setDate('09-05-2023');

        return $dto;
    }
    public function getTasks($filterBy, $limit = 50) : array
    {
        return [];
    }

    public function deleteTask($id)
    {

    }

    public function createTask(TaskDto $dto)
    {

    }

    public function updateTask(TaskDto $dto)
    {

    }
}
