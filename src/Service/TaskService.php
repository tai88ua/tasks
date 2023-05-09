<?php

namespace App\Service;

use App\Dto\TaskDto;
use App\Dto\UserDto;
use App\Entity\Task;
use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;

class TaskService
{
    private ManagerRegistry $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }

    public function getTask(int $id, ?UserDto $userDto): ?TaskDto
    {
        $taskRegistry = $this->managerRegistry->getRepository(Task::class);

        $criteria = ['id' => $id];
        if (!in_array(UserDto::ROLE_ADMIN, $userDto->getRoles())) {
            $criteria['user'] = $userDto->getId();
        }

        $taskModel = $taskRegistry->findOneBy($criteria);

        if (!$taskModel) {
            return null;
        }

        return $this->makeTask($taskModel);
    }

    public function getTasks(?UserDto $userDto): array
    {
        if (!$userDto) {
            return [];
        }

        $taskRegistry = $this->managerRegistry->getRepository(Task::class);

        $criteria = [];
        if (!in_array(UserDto::ROLE_ADMIN, $userDto->getRoles())) {
            $criteria['user'] = $userDto->getId();
        }

        $taskModels = $taskRegistry->findBy($criteria);

        $items = [];
        foreach ($taskModels as $taskModel) {
            $items[] = $this->makeTask($taskModel);
        }

        return $items;
    }

    private function makeTask(Task $taskModel): TaskDto
    {
        $dto = new TaskDto();
        $dto->setId($taskModel->getId());
        $dto->setTitle($taskModel->getTitle());
        $dto->setDescription($taskModel->getDescription());
        $dto->setDate($taskModel->getDate());
        $dto->setStatus($taskModel->getStatus());

        $userDto = new UserDto();
        $userDto->setId($taskModel->getUser()->getId());
        $userDto->setName($taskModel->getUser()->getName());
        $userDto->setRoles($taskModel->getUser()->getRoles());
        $userDto->setEmail($taskModel->getUser()->getEmail());

        $dto->setUser($userDto);
        return $dto;
    }


    /**
     * @throws \Exception
     */
    public function deleteTask(int $id, ?UserDto $userDto): bool
    {
        if (!$userDto) {
            throw new \Exception('User not found!');
        }

        $criteria = ['id' => $id];
        if (!in_array(UserDto::ROLE_ADMIN, $userDto->getRoles())) {
            $criteria['user'] = $userDto->getId();
        }

        $taskModel = $this->managerRegistry->getRepository(Task::class)->findOneBy($criteria);

        if (!$taskModel) {
            throw new \Exception('Task not found!');
        }

        $this->managerRegistry->getManager()->remove($taskModel);
        $this->managerRegistry->getManager()->flush();

        return true;
    }

    /**
     * @throws \Exception
     */
    public function createOrUpdateTask(TaskDto $dto, UserDto $userDto): void
    {
        if ($dto->getId() !== null) {
            $criteria = ['id' => $dto->getId()];
            if (!in_array(UserDto::ROLE_ADMIN, $userDto->getRoles())) {
                $criteria['user'] = $userDto->getId();
            }
            $taskModel = $this->managerRegistry->getRepository(Task::class)->findOneBy($criteria);
        } else {
            $taskModel = new Task();

            $userRegistry = $this->managerRegistry->getRepository(User::class);
            $userModel = $userRegistry->find($userDto->getId());

            $taskModel->setUser($userModel);
        }

        $taskModel->setTitle($dto->getTitle());
        $taskModel->setDescription($dto->getDescription());
        $date = $dto->getDate();
        if ($date::class == \DateTime::class) {
            $date = \DateTimeImmutable::createFromMutable($date);
        }
        $taskModel->setDate($date);
        $taskModel->setStatus($dto->getStatus());

        $this->managerRegistry->getManager()->persist($taskModel);
        $this->managerRegistry->getManager()->flush();
    }
}
