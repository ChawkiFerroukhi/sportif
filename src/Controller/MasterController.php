<?php

namespace App\Controller;

use App\Entity\Master;
use App\Form\MasterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/master')]
class MasterController extends AbstractController
{
    #[Route('/', name: 'app_master_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $masters = $entityManager
            ->getRepository(Master::class)
            ->findAll();

        return $this->render('master/index.html.twig', [
            'masters' => $masters,
        ]);
    }

    #[Route('/new', name: 'app_master_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $master = new Master();
        $form = $this->createForm(MasterType::class, $master);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($master);
            $entityManager->flush();

            return $this->redirectToRoute('app_master_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('master/new.html.twig', [
            'master' => $master,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_master_show', methods: ['GET'])]
    public function show(Master $master): Response
    {
        return $this->render('master/show.html.twig', [
            'master' => $master,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_master_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Master $master, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MasterType::class, $master);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_master_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('master/edit.html.twig', [
            'master' => $master,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_master_delete', methods: ['POST'])]
    public function delete(Request $request, Master $master, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$master->getId(), $request->request->get('_token'))) {
            $entityManager->remove($master);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_master_index', [], Response::HTTP_SEE_OTHER);
    }
}
