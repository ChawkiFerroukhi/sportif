<?php

namespace App\Controller;

use App\Entity\Section;
use App\Entity\Club;
use App\Form\SectionType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/section')]
class SectionController extends AbstractController
{
    #[Route('/', name: 'app_section_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $sections = $entityManager
            ->getRepository(Section::class)
            ->findAll();

        return $this->render('section/index.html.twig', [
            'sections' => $sections,
        ]);
    }

    #[Route('/{id}/new', name: 'app_section_new', methods: ['GET', 'POST'])]
    public function new(Club $club,Request $request, EntityManagerInterface $entityManager): Response
    {
        $section = new Section();
        $form = $this->createForm(SectionType::class, $section);
        $form->handleRequest($request);
        $sections = $entityManager
                ->getRepository(Section::class)
                ->findAll();

        if ($form->isSubmitted() && $form->isValid()) {
            $section->setCreatedAt(new \DateTime());
            $section->setUpdatedAt(new \DateTime());
            $section->setClubid($club);
            $entityManager->persist($section);
            $entityManager->flush();

            return $this->redirectToRoute('app_club_show', ['id' => $club->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('section/new.html.twig', [
            'section' => $section,
            'form' => $form,
            'sections' => $sections,
        ]);
    }

    #[Route('/{id}', name: 'app_section_show', methods: ['GET'])]
    public function show(Section $section, EntityManagerInterface $entityManager): Response
    {

        $sections = $entityManager
                ->getRepository(Section::class)
                ->findAll();
        return $this->render('section/show.html.twig', [
            'section' => $section,
            'sections' => $sections,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_section_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Section $section, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SectionType::class, $section);
        $form->handleRequest($request);
        $sections = $entityManager
                ->getRepository(Section::class)
                ->findAll();

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_section_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('section/edit.html.twig', [
            'section' => $section,
            'form' => $form,
            'sections' => $sections,
        ]);
    }

    #[Route('/{id}', name: 'app_section_delete', methods: ['POST'])]
    public function delete(Request $request, Section $section, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$section->getId(), $request->request->get('_token'))) {
            $entityManager->remove($section);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_section_index', [], Response::HTTP_SEE_OTHER);
    }
}
