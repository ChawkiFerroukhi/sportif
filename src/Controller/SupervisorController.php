<?php

namespace App\Controller;

use App\Entity\Supervisor;
use App\Form\SupervisorType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/supervisor')]
class SupervisorController extends AbstractController
{
    #[Route('/', name: 'app_supervisor_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $supervisors = $entityManager
            ->getRepository(Supervisor::class)
            ->findAll();

        return $this->render('supervisor/index.html.twig', [
            'supervisors' => $supervisors,
        ]);
    }

    #[Route('/new', name: 'app_supervisor_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $supervisor = new Supervisor();
        $form = $this->createForm(SupervisorType::class, $supervisor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($supervisor);
            $entityManager->flush();

            return $this->redirectToRoute('app_supervisor_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('supervisor/new.html.twig', [
            'supervisor' => $supervisor,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_supervisor_show', methods: ['GET'])]
    public function show(Supervisor $supervisor): Response
    {
        return $this->render('supervisor/show.html.twig', [
            'supervisor' => $supervisor,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_supervisor_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Supervisor $supervisor, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SupervisorType::class, $supervisor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_supervisor_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('supervisor/edit.html.twig', [
            'supervisor' => $supervisor,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_supervisor_delete', methods: ['POST'])]
    public function delete(Request $request, Supervisor $supervisor, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$supervisor->getId(), $request->request->get('_token'))) {
            $entityManager->remove($supervisor);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_supervisor_index', [], Response::HTTP_SEE_OTHER);
    }
}
