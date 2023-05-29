<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/')]
class HomeController extends AbstractController
{    
    #[Route('/', name: 'app_index', methods: ['GET'])]
    public function index(): Response
    {
        
        return $this->redirectToRoute('app_club_index', [], Response::HTTP_SEE_OTHER);
    }
}
