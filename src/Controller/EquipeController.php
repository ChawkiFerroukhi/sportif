<?php

namespace App\Controller;

use App\Entity\Equipe;
use App\Entity\Section;
use App\Form\EquipeType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/equipe')]
class EquipeController extends AbstractController
{
    #[Route('/', name: 'app_equipe_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $equipes = $entityManager
            ->getRepository(Equipe::class)
            ->findAll();
        $sections = $entityManager
            ->getRepository(Section::class)
            ->findAll();

        return $this->render('equipe/index.html.twig', [
            'equipes' => $equipes,
            'sections' => $sections,
        ]);
    }

    #[Route('/new', name: 'app_equipe_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $equipe = new Equipe();
        $form = $this->createForm(EquipeType::class, $equipe);
        $form->handleRequest($request);
        $sections = $entityManager
            ->getRepository(Section::class)
            ->findAll();

        if ($form->isSubmitted() && $form->isValid()) {
            $equipe->setCreatedAt(new \DateTime());
            $equipe->setUpdatedAt(new \DateTime());
            $entityManager->persist($equipe);
            $entityManager->flush();

            return $this->redirectToRoute('app_equipe_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('equipe/new.html.twig', [
            'equipe' => $equipe,
            'form' => $form,
            'sections' => $sections,
        ]);
    }

    #[Route('/{id}', name: 'app_equipe_show', methods: ['GET'])]
    public function show(Equipe $equipe,EntityManagerInterface $entityManager): Response
    {

        $sections = $entityManager
            ->getRepository(Section::class)
            ->findAll();
        return $this->render('equipe/show.html.twig', [
            'equipe' => $equipe,
            'sections' => $sections,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_equipe_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Equipe $equipe, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EquipeType::class, $equipe);
        $form->handleRequest($request);
        $sections = $entityManager
            ->getRepository(Section::class)
            ->findAll();

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_equipe_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('equipe/edit.html.twig', [
            'equipe' => $equipe,
            'form' => $form,
            'sections' => $sections,
        ]);
    }

    #[Route('/{id}', name: 'app_equipe_delete', methods: ['POST','GET'])]
    public function delete(Request $request, Equipe $equipe, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$equipe->getId(), $request->request->get('_token'))) {
            $entityManager->remove($equipe);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_equipe_index', [], Response::HTTP_SEE_OTHER);
    }
}
