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
        $usr = $this->getUser();
        if(!isset($usr->getRoles()['ROLE_MASTER']) && !isset($usr->getRoles()['app_section_index'])) {
            $this->user = $usr;
            return $this->redirectToRoute('app_home_access_denied', [], Response::HTTP_SEE_OTHER);
        }
        $sections = $entityManager
            ->getRepository(Section::class)
            ->findAll();
        $section = new Section();
        $this->user = $usr;
        return $this->render('section/index.html.twig', [
            'sections' => $sections,
        ]);
    }

    #[Route('/{id}/home', name: 'app_section_home', methods: ['GET'])]
    public function home(Section $section, EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();
        if(!isset($usr->getRoles()['ROLE_MASTER']) && !isset($usr->getRoles()['app_section_home'])) {
            $this->user = $usr;
            return $this->redirectToRoute('app_home_access_denied', [], Response::HTTP_SEE_OTHER);
        }
        $sections = $entityManager
            ->getRepository(Section::class)
            ->findBy(['clubid' => $this->getUser()->getClubid()]);
        $this->user = $usr;
        return $this->render('section/home.html.twig', [
            'sections' => $sections,
            'section' => $section
        ]);
    }

    #[Route('/{id}/new', name: 'app_section_new', methods: ['GET', 'POST'])]
    public function new(Club $club,Request $request, EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();
        if(!isset($usr->getRoles()['ROLE_MASTER']) && !isset($usr->getRoles()['app_section_new'])) {
            $this->user = $usr;
            return $this->redirectToRoute('app_home_access_denied', [], Response::HTTP_SEE_OTHER);
        }
        // if($club->getNbSections() >= count($club->getSections())) {
            // $this->user = $usr;
            // return $this->redirectToRoute('app_home_limit_reached', [], Response::HTTP_SEE_OTHER);
        // }
        $section = new Section();
        $form = $this->createForm(SectionType::class, $section);
        $form->handleRequest($request);
        $sections = $entityManager
            ->getRepository(Section::class)
            ->findBy(['clubid' => $this->getUser()->getClubid()]);

        if ($form->isSubmitted() && $form->isValid()) {
            $section->setCreatedAt(new \DateTime());
            $section->setUpdatedAt(new \DateTime());
            $section->setClubid($club);
            $entityManager->persist($section);
            $entityManager->flush();

            $this->user = $usr;
        return $this->redirectToRoute('app_section_show', ['id' => $section->getId()], Response::HTTP_SEE_OTHER);
        }

        $this->user = $usr;
        return $this->renderForm('section/new.html.twig', [
            'section' => $section,
            'form' => $form,
            'sections' => $sections,
        ]);
    }

    #[Route('/{id}', name: 'app_section_show', methods: ['GET'])]
    public function show(Section $section, EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();
        $sections = $entityManager
            ->getRepository(Section::class)
            ->findBy(['clubid' => $this->getUser()->getClubid()]);
        $M=0;
        $F=0;
        $niveaux = $section->getNiveaux();
        foreach($niveaux as $niveau) {
            $equipes = $niveau->getEquipes();
            foreach($equipes as $equipe) {
                $adherants = $equipe->getAdherants();
                foreach($adherants as $adherant) {
                    if($adherant->getSexe() == "H") {
                        $M++;
                    } else {
                        $F++;
                    }
                }
            }
        }
        $this->user = $usr;
        return $this->render('section/show.html.twig', [
            'section' => $section,
            'M' => $M,
            'F' => $F,
            'sections' => $sections,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_section_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Section $section, EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();
        if(!isset($usr->getRoles()['ROLE_MASTER']) && !isset($usr->getRoles()['app_section_edit'])) {
            $this->user = $usr;
            return $this->redirectToRoute('app_home_access_denied', [], Response::HTTP_SEE_OTHER);
        }
        $form = $this->createForm(SectionType::class, $section);
        $form->handleRequest($request);
        $sections = $entityManager
            ->getRepository(Section::class)
            ->findBy(['clubid' => $this->getUser()->getClubid()]);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->user = $usr;
            return $this->redirectToRoute('app_section_show', ['id' => $section->getId()], Response::HTTP_SEE_OTHER);
        }

        $this->user = $usr;
        return $this->renderForm('section/edit.html.twig', [
            'section' => $section,
            'form' => $form,
            'sections' => $sections,
        ]);
    }

    #[Route('/{id}', name: 'app_section_delete', methods: ['POST'])]
    public function delete(Request $request, Section $section, EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();
        if(!isset($usr->getRoles()['ROLE_MASTER']) && !isset($usr->getRoles()['app_section_delete'])) {
            $this->user = $usr;
            return $this->redirectToRoute('app_home_access_denied', [], Response::HTTP_SEE_OTHER);
        }
        if ($this->isCsrfTokenValid('delete'.$section->getId(), $request->request->get('_token'))) {
            $entityManager->remove($section);
            $entityManager->flush();
        }

        $this->user = $usr;
        return $this->redirectToRoute('app_section_index', [], Response::HTTP_SEE_OTHER);
    }
}
