<?php

namespace App\Controller;

use App\Entity\Niveau;
use App\Entity\Section;
use App\Form\NiveauType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/categorie')]
class NiveauController extends AbstractController
{
    #[Route('/', name: 'app_niveau_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();
        if(!isset($usr->getRoles()['ROLE_MASTER']) && !isset($usr->getRoles()['app_niveau_index'])) {
            $this->user = $usr;
            return $this->redirectToRoute('app_home_access_denied', [], Response::HTTP_SEE_OTHER);
        }
        $niveaux = $entityManager
            ->getRepository(Niveau::class)
            ->findAll();
        $sections = $entityManager
            ->getRepository(Section::class)
            ->findBy(['clubid' => $this->getUser()->getClubid()]);
        $section = new Section();
        $this->user = $usr;
        return $this->render('niveau/index.html.twig', [
            'niveaux' => $niveaux,
            'sections' => $sections,
            'section' => $section,
        ]);
    }

    #[Route('/{id}/new', name: 'app_niveau_new', methods: ['GET', 'POST'])]
    public function new(Section $section,Request $request, EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();
        if(!isset($usr->getRoles()['ROLE_MASTER']) && !isset($usr->getRoles()['app_niveau_new'])) {
            $this->user = $usr;
            return $this->redirectToRoute('app_home_access_denied', [], Response::HTTP_SEE_OTHER);
        }
        $niveau = new Niveau();
        $form = $this->createForm(NiveauType::class, $niveau);
        $form->handleRequest($request);
        $sections = $entityManager
            ->getRepository(Section::class)
            ->findBy(['clubid' => $this->getUser()->getClubid()]);

        if ($form->isSubmitted() && $form->isValid()) {
            $niveau->setCreatedAt(new \DateTime());
            $niveau->setUpdatedAt(new \DateTime());
            $niveau->setSectionid($section);
            $niveau->setClubid($section->getClubid());
            $entityManager->persist($niveau);
            $entityManager->flush();

            $this->user = $usr;
        return $this->redirectToRoute('app_niveau_show', ['id' => $niveau->getId()], Response::HTTP_SEE_OTHER);
        }

        $this->user = $usr;
        return $this->renderForm('niveau/new.html.twig', [
            'niveau' => $niveau,
            'form' => $form,
            'sections' => $sections,
            'section' => $section
        ]);
    }

    #[Route('/{id}', name: 'app_niveau_show', methods: ['GET'])]
    public function show(Niveau $niveau,EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();
        $sections = $entityManager
            ->getRepository(Section::class)
            ->findBy(['clubid' => $this->getUser()->getClubid()]);
        $this->user = $usr;
        return $this->render('niveau/show.html.twig', [
            'niveau' => $niveau,
            'sections' => $sections,
            'section' => $niveau->getSectionid(),
        ]);
    }

    #[Route('/{id}/edit', name: 'app_niveau_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Niveau $niveau, EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();
        if(!isset($usr->getRoles()['ROLE_MASTER']) && !isset($usr->getRoles()['app_niveau_edit'])) {
            $this->user = $usr;
            return $this->redirectToRoute('app_home_access_denied', [], Response::HTTP_SEE_OTHER);
        }
        $form = $this->createForm(NiveauType::class, $niveau);
        $form->handleRequest($request);
        $sections = $entityManager
            ->getRepository(Section::class)
            ->findBy(['clubid' => $this->getUser()->getClubid()]);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->user = $usr;
            return $this->redirectToRoute('app_niveau_show', ['id' => $niveau->getId()], Response::HTTP_SEE_OTHER);
        }

        $this->user = $usr;
        return $this->renderForm('niveau/edit.html.twig', [
            'niveau' => $niveau,
            'form' => $form,
            'sections' => $sections,
            'section' => $niveau->getSectionid(),
        ]);
    }

    #[Route('/{id}', name: 'app_niveau_delete', methods: ['POST'])]
    public function delete(Request $request, Niveau $niveau, EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();
        if(!isset($usr->getRoles()['ROLE_MASTER']) && !isset($usr->getRoles()['app_niveau_delete'])) {
            $this->user = $usr;
            return $this->redirectToRoute('app_home_access_denied', [], Response::HTTP_SEE_OTHER);
        }
        if ($this->isCsrfTokenValid('delete'.$niveau->getId(), $request->request->get('_token'))) {
            $entityManager->remove($niveau);
            $entityManager->flush();
        }

        $this->user = $usr;
        return $this->redirectToRoute('app_section_show', ['id' => $niveau->getSectionid()->getId()], Response::HTTP_SEE_OTHER);
    }
}
