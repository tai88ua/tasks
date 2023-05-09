<?php

namespace App\Service;

use App\Dto\CriteriaDto;
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
        $criteria = $this->makeCriteria($id, $userDto);
        $taskModel = $this->managerRegistry->getRepository(Task::class)->findOneBy($criteria);

        if (!$taskModel) {
            return null;
        }

        return $this->makeTask($taskModel);
    }

    public function getTasks(?UserDto $userDto, ?CriteriaDto $criteriaDto = null): array
    {
        if (!$userDto) {
            return [];
        }

        $criteria = $this->makeCriteria(null, $userDto);

        $taskModels = $this->managerRegistry->getRepository(Task::class)
            ->findByCriteria($criteria['user'] ?? null, $criteriaDto);

        $items = [];
        foreach ($taskModels as $taskModel) {
            $items[] = $this->makeTask($taskModel);
        }

        return $items;
    }

    /**
     * @throws \Exception
     */
    public function deleteTask(int $id, ?UserDto $userDto): bool
    {
        if (!$userDto) {
            throw new \Exception('User not found!');
        }

        $criteria = $this->makeCriteria($id, $userDto);
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
            $criteria = $this->makeCriteria($dto->getId(), $userDto);
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

    private function makeCriteria(?int $taskId, ?UserDto $userDto): array
    {
        $criteria = [];

        if ($taskId) {
            $criteria['id'] = $taskId;
        }

        if (!in_array(UserDto::ROLE_ADMIN, $userDto->getRoles())) {
            $criteria['user'] = $userDto->getId();
        }

        return $criteria;
    }
}
