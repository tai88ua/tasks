<?php

namespace App\Controller;

use App\Dto\CriteriaDto;
use App\Dto\TaskDto;
use App\Form\CriteriaType;
use App\Form\TaskType;
use App\Service\TaskService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class TaskController extends MainController
{
    /**
     * @throws \Exception
     */
    #[Route('/task/info/{id}', name: 'app_task_page', requirements: ['id' => '\d+'])]
    public function viewTask(int $id, TaskService $taskService, Request $request): Response
    {
        $parameters = [
            'title_page'    => 'Update task',
            'user'          => $this->user,
        ];

        $taskDto = $taskService->getTask($id, $this->user);
        $form = $this->createForm(TaskType::class, $taskDto, [
            'attr' => ['class' => 'col-lg-6 offset-lg-3'],
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $taskService->createOrUpdateTask($form->getData(), $this->user);
            $parameters['error_msg'] = null;
            $parameters['success_msg'] = 'Task is successfully updated!';
            return $this->render('task/msg.html.twig', $parameters);
        }

        $parameters['task'] = $taskDto;
        $parameters['form'] = $form;
        return $this->render('task/index.html.twig', $parameters);
    }

    #[Route('/task/list', name: 'app_task_list_page')]
    public function listTasks(TaskService $taskService, Request $request): Response
    {

        $criteria = new CriteriaDto();
        $form = $this->createForm(CriteriaType::class, $criteria);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tasks = $taskService->getTasks($this->user, $form->getData());
        } else {
            $tasks = $taskService->getTasks($this->user, $criteria);
        }

        return $this->render('task/list.html.twig', [
            'title_page'    => 'List Task',
            'user'          => $this->user,
            'tasks'         => $tasks,
            'searchForm'    => $form
        ]);
    }

    /**
     * @throws \Exception
     */
    #[Route('/task/add', name: 'app_task_add_page')]
    public function addTask(Request $request, TaskService $taskService): Response
    {
        $parameters = [
            'title_page'    => 'Add task',
            'user'          => $this->user,
        ];

        $task = new TaskDto();
        $task->setDate((new \DateTimeImmutable()));

        $form = $this->createForm(TaskType::class, $task, [
            'attr' => ['class' => 'col-lg-6 offset-lg-3'],
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $taskService->createOrUpdateTask($form->getData(), $this->user);
            $parameters['error_msg'] = null;
            $parameters['success_msg'] = 'Task is successfully added!';
            return $this->render('task/msg.html.twig', $parameters);
        }

        $parameters['form'] = $form;
        return $this->render('task/add.html.twig', $parameters);
    }


    /**
     * @throws \Exception
     */
    #[Route('/task/delete/{id}', name: 'app_task_delete_page', requirements: ['id' => '\d+'])]
    public function deleteTask(int $id, TaskService $taskService): Response
    {
        $errorMsg = null;
        $successMsg = null;

        try {
            $taskService->deleteTask($id, $this->user);
            $successMsg = 'Task is deleted!';
        } catch (\Exception $exception) {
            $errorMsg = $exception->getMessage();
        }

        return $this->render('task/msg.html.twig', [
            'title_page'    => 'Delete task',
            'error_msg'     => $errorMsg,
            'success_msg'   => $successMsg,
            'user'          => $this->user,
        ]);
    }
}
