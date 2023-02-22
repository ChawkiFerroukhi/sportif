<?php

namespace App\Controller;

use App\Entity\Demecategorie;
use App\Form\DemecategorieType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/demecategorie')]
class DemecategorieController extends AbstractController
{
    #[Route('/', name: 'app_demecategorie_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $demecategories = $entityManager
            ->getRepository(Demecategorie::class)
            ->findAll();

        return $this->render('demecategorie/index.html.twig', [
            'demecategories' => $demecategories,
        ]);
    }

    #[Route('/new', name: 'app_demecategorie_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $demecategorie = new Demecategorie();
        $form = $this->createForm(DemecategorieType::class, $demecategorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($demecategorie);
            $entityManager->flush();

            return $this->redirectToRoute('app_demecategorie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('demecategorie/new.html.twig', [
            'demecategorie' => $demecategorie,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_demecategorie_show', methods: ['GET'])]
    public function show(Demecategorie $demecategorie): Response
    {
        return $this->render('demecategorie/show.html.twig', [
            'demecategorie' => $demecategorie,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_demecategorie_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Demecategorie $demecategorie, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DemecategorieType::class, $demecategorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_demecategorie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('demecategorie/edit.html.twig', [
            'demecategorie' => $demecategorie,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_demecategorie_delete', methods: ['POST'])]
    public function delete(Request $request, Demecategorie $demecategorie, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$demecategorie->getId(), $request->request->get('_token'))) {
            $entityManager->remove($demecategorie);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_demecategorie_index', [], Response::HTTP_SEE_OTHER);
    }
}
