<?php

namespace App\Controller;

use App\Entity\Cycle;
use App\Form\CycleType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/cycle')]
class CycleController extends AbstractController
{
    #[Route('/', name: 'app_cycle_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $cycles = $entityManager
            ->getRepository(Cycle::class)
            ->findAll();

        return $this->render('cycle/index.html.twig', [
            'cycles' => $cycles,
        ]);
    }

    #[Route('/new', name: 'app_cycle_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $cycle = new Cycle();
        $form = $this->createForm(CycleType::class, $cycle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($cycle);
            $entityManager->flush();

            return $this->redirectToRoute('app_cycle_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('cycle/new.html.twig', [
            'cycle' => $cycle,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_cycle_show', methods: ['GET'])]
    public function show(Cycle $cycle): Response
    {
        return $this->render('cycle/show.html.twig', [
            'cycle' => $cycle,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_cycle_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Cycle $cycle, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CycleType::class, $cycle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_cycle_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('cycle/edit.html.twig', [
            'cycle' => $cycle,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_cycle_delete', methods: ['POST'])]
    public function delete(Request $request, Cycle $cycle, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$cycle->getId(), $request->request->get('_token'))) {
            $entityManager->remove($cycle);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_cycle_index', [], Response::HTTP_SEE_OTHER);
    }
}
