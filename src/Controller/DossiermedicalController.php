<?php

namespace App\Controller;

use App\Entity\Dossiermedical;
use App\Form\DossiermedicalType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/dossiermedical')]
class DossiermedicalController extends AbstractController
{
    #[Route('/', name: 'app_dossiermedical_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $dossiermedicals = $entityManager
            ->getRepository(Dossiermedical::class)
            ->findAll();

        return $this->render('dossiermedical/index.html.twig', [
            'dossiermedicals' => $dossiermedicals,
        ]);
    }

    #[Route('/new', name: 'app_dossiermedical_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $dossiermedical = new Dossiermedical();
        $form = $this->createForm(DossiermedicalType::class, $dossiermedical);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($dossiermedical);
            $entityManager->flush();

            return $this->redirectToRoute('app_dossiermedical_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('dossiermedical/new.html.twig', [
            'dossiermedical' => $dossiermedical,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_dossiermedical_show', methods: ['GET'])]
    public function show(Dossiermedical $dossiermedical): Response
    {
        return $this->render('dossiermedical/show.html.twig', [
            'dossiermedical' => $dossiermedical,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_dossiermedical_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Dossiermedical $dossiermedical, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DossiermedicalType::class, $dossiermedical);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_dossiermedical_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('dossiermedical/edit.html.twig', [
            'dossiermedical' => $dossiermedical,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_dossiermedical_delete', methods: ['POST'])]
    public function delete(Request $request, Dossiermedical $dossiermedical, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$dossiermedical->getId(), $request->request->get('_token'))) {
            $entityManager->remove($dossiermedical);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_dossiermedical_index', [], Response::HTTP_SEE_OTHER);
    }
}
