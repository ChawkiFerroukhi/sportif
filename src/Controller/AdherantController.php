<?php

namespace App\Controller;

use App\Entity\Adherant;
use App\Form\AdherantType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/adherant')]
class AdherantController extends AbstractController
{
    #[Route('/', name: 'app_adherant_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $adherants = $entityManager
            ->getRepository(Adherant::class)
            ->findAll();

        return $this->render('adherant/index.html.twig', [
            'adherants' => $adherants,
        ]);
    }

    #[Route('/new', name: 'app_adherant_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $adherant = new Adherant();
        $form = $this->createForm(AdherantType::class, $adherant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($adherant);
            $entityManager->flush();

            return $this->redirectToRoute('app_adherant_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('adherant/new.html.twig', [
            'adherant' => $adherant,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_adherant_show', methods: ['GET'])]
    public function show(Adherant $adherant): Response
    {
        return $this->render('adherant/show.html.twig', [
            'adherant' => $adherant,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_adherant_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Adherant $adherant, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AdherantType::class, $adherant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_adherant_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('adherant/edit.html.twig', [
            'adherant' => $adherant,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_adherant_delete', methods: ['POST'])]
    public function delete(Request $request, Adherant $adherant, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$adherant->getId(), $request->request->get('_token'))) {
            $entityManager->remove($adherant);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_adherant_index', [], Response::HTTP_SEE_OTHER);
    }
}
