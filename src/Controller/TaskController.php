<?php

namespace App\Controller;

use App\Service\TaskService;
use App\Service\UserService;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\SecurityBundle\Security;

class TaskController extends MainController
{
    #[Route('/task/info/{id}', name: 'app_task_page')]
    public function viewTask($id, TaskService $taskService) : Response
    {
        $taskDto = $taskService->getTask($id);
        return $this->render('task/index.html.twig', [
            'task' => $taskDto,
            'user' => $this->user,
        ]);
    }

    #[Route('/task/list', name: 'app_task_list_page')]
    public function listTasks(TaskService $taskService) : Response
    {
        return $this->render('task/list.html.twig', [
            'title_page' => 'List Task',
            'user'       => $this->user,
        ]);
    }
}