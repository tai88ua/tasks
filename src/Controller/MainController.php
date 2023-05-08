<?php

namespace App\Controller;

use App\Dto\UserDto;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\SecurityBundle\Security;

class MainController extends AbstractController
{
    protected ?UserDto $user = null;

    public function __construct(UserService $userService, Security $security)
    {
        $user = $security->getUser();
        if ($user) {
            $this->user = $userService->getUser($user->getUserIdentifier());
        }
    }

    #[Route('/', name: 'main_page')]
    public function test() : Response
    {
        //if login redirect to task list
        return $this->render('index.html.twig', [
            'title_page' => 'Main page',
            'user'       => $this->user,
        ]);
    }
}
