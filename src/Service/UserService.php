<?php

namespace App\Service;

use App\Dto\UserDto;
use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;

class UserService
{
    private ManagerRegistry $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }

    public function getUser(string $email) : UserDto
    {
        $userDto = new UserDto();
        $userModel =  $this->managerRegistry->getRepository(User::class)->findOneBy(['email' => $email]);

        if ($userModel) {
            $userDto->setEmail($email);
            $userDto->setId($userModel->getId());
            $userDto->setName($userModel->getName());
            $userDto->setRoles($userModel->getRoles());
        }

        return $userDto;
    }

}