<?php

namespace App\Controller;

use App\Entity\Mesure;
use App\Entity\Section;
use App\Form\MesureType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/mesure')]
class MesureController extends AbstractController
{
    #[Route('/', name: 'app_mesure_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();
        $mesures = $entityManager
            ->getRepository(Mesure::class)
            ->findAll();
        $sections = $entityManager
            ->getRepository(Section::class)
            ->findBy(['clubid' => $this->getUser()->getClubid()]);

        $this->user = $usr;
        return $this->render('mesure/index.html.twig', [
            'mesures' => $mesures,
            'sections' => $sections,
        ]);
    }

    #[Route('/new', name: 'app_mesure_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();
        $mesure = new Mesure();
        $form = $this->createForm(MesureType::class, $mesure);
        $form->handleRequest($request);
        $sections = $entityManager
            ->getRepository(Section::class)
            ->findBy(['clubid' => $this->getUser()->getClubid()]);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($mesure);
            $entityManager->flush();

            $this->user = $usr;
        return $this->redirectToRoute('app_mesure_index', [], Response::HTTP_SEE_OTHER);
        }

        $this->user = $usr;
        return $this->renderForm('mesure/new.html.twig', [
            'mesure' => $mesure,
            'form' => $form,
            'sections' => $sections,
        ]);
    }

    #[Route('/{id}', name: 'app_mesure_show', methods: ['GET'])]
    public function show(Mesure $mesure, EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();
        $sections = $entityManager
            ->getRepository(Section::class)
            ->findBy(['clubid' => $this->getUser()->getClubid()]);
        $this->user = $usr;
        return $this->render('mesure/show.html.twig', [
            'mesure' => $mesure,
            'sections' => $sections,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_mesure_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Mesure $mesure, EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();
        $form = $this->createForm(MesureType::class, $mesure);
        $form->handleRequest($request);
        $sections = $entityManager
            ->getRepository(Section::class)
            ->findBy(['clubid' => $this->getUser()->getClubid()]);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->user = $usr;
        return $this->redirectToRoute('app_mesure_index', [], Response::HTTP_SEE_OTHER);
        }

        $this->user = $usr;
        return $this->renderForm('mesure/edit.html.twig', [
            'mesure' => $mesure,
            'form' => $form,
            'sections' => $sections,
        ]);
    }

    #[Route('/{id}', name: 'app_mesure_delete', methods: ['POST'])]
    public function delete(Request $request, Mesure $mesure, EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();
        if ($this->isCsrfTokenValid('delete'.$mesure->getId(), $request->request->get('_token'))) {
            $entityManager->remove($mesure);
            $entityManager->flush();
        }

        $this->user = $usr;
        return $this->redirectToRoute('app_mesure_index', [], Response::HTTP_SEE_OTHER);
    }
}
