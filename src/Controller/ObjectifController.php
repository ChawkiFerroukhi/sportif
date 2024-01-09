<?php

namespace App\Controller;

use App\Entity\Objectif;
use App\Entity\Cycle;
use App\Entity\Section;
use App\Form\ObjectifType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/objectif')]
class ObjectifController extends AbstractController
{
    #[Route('/', name: 'app_objectif_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();
        if(!isset($usr->getRoles()['ROLE_MASTER']) && !isset($usr->getRoles()['app_objectif_index'])) {
            $this->user = $usr;
            return $this->redirectToRoute('app_home_access_denied', [], Response::HTTP_SEE_OTHER);
        }
        $objectifs = $entityManager
            ->getRepository(Objectif::class)
            ->findAll();
        $sections = $entityManager
            ->getRepository(Section::class)
            ->findBy(['clubid' => $this->getUser()->getClubid()]);
        $this->user = $usr;
        return $this->render('objectif/index.html.twig', [
            'objectifs' => $objectifs,
            'sections' => $sections,
            'section' => new Section()
        ]);
    }

    #[Route('/{id}/new', name: 'app_objectif_new', methods: ['GET', 'POST'])]
    public function new(Request $request, Cycle $cycle,EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();
        if(!isset($usr->getRoles()['ROLE_MASTER']) && !isset($usr->getRoles()['app_objectif_new'])) {
            $this->user = $usr;
            return $this->redirectToRoute('app_home_access_denied', [], Response::HTTP_SEE_OTHER);
        }
        $objectif = new Objectif();
        $form = $this->createForm(ObjectifType::class, $objectif);
        $form->handleRequest($request);
        $sections = $entityManager
            ->getRepository(Section::class)
            ->findBy(['clubid' => $this->getUser()->getClubid()]);

        if ($form->isSubmitted() && $form->isValid()) {
            $objectif->setCreatedAt(new \DateTime());
            $objectif->setUpdatedAt(new \DateTime());
            $objectif->setCycleid($cycle);
            $objectif->setClubid($cycle->getClubid());
            $entityManager->persist($objectif);
            $entityManager->flush();

            $this->user = $usr;
        return $this->redirectToRoute('app_objectif_show', ['id' => $objectif->getId()], Response::HTTP_SEE_OTHER);
        }

        $this->user = $usr;
        return $this->renderForm('objectif/new.html.twig', [
            'objectif' => $objectif,
            'form' => $form,
            'sections' => $sections,
            'section' => $cycle->getNiveauid()->getSectionid()
        ]);
    }

    #[Route('/{id}', name: 'app_objectif_show', methods: ['GET'])]
    public function show(Objectif $objectif,EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();
        $this->user = $usr;
        $sections = $entityManager
            ->getRepository(Section::class)
            ->findBy(['clubid' => $this->getUser()->getClubid()]);
        return $this->render('objectif/show.html.twig', [
            'objectif' => $objectif,
            'sections' => $sections,
            'section' => $objectif->getCycleid()->getNiveauid()->getSectionid()
        ]);
    }

    #[Route('/{id}/edit', name: 'app_objectif_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Objectif $objectif, EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();
        if(!isset($usr->getRoles()['ROLE_MASTER']) && !isset($usr->getRoles()['app_objectif_edit'])) {
            $this->user = $usr;
            return $this->redirectToRoute('app_home_access_denied', [], Response::HTTP_SEE_OTHER);
        }
        $form = $this->createForm(ObjectifType::class, $objectif);
        $form->handleRequest($request);
        $sections = $entityManager
            ->getRepository(Section::class)
            ->findBy(['clubid' => $this->getUser()->getClubid()]);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->user = $usr;
            return $this->redirectToRoute('app_objectif_show', ['id' => $objectif->getId()], Response::HTTP_SEE_OTHER);
        }

        $this->user = $usr;
        return $this->renderForm('objectif/edit.html.twig', [
            'objectif' => $objectif,
            'form' => $form,
            'sections' => $sections,
            'section' => $objectif->getCycleid()->getNiveauid()->getSectionid()
        ]);
    }

    #[Route('/{id}', name: 'app_objectif_delete', methods: ['POST'])]
    public function delete(Request $request, Objectif $objectif, EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();
        if(!isset($usr->getRoles()['ROLE_MASTER']) && !isset($usr->getRoles()['app_objectif_delete'])) {
            $this->user = $usr;
            return $this->redirectToRoute('app_home_access_denied', [], Response::HTTP_SEE_OTHER);
        }
        if ($this->isCsrfTokenValid('delete'.$objectif->getId(), $request->request->get('_token'))) {
            $entityManager->remove($objectif);
            $entityManager->flush();
        }

        $this->user = $usr;
        return $this->redirectToRoute('app_cycle_show', ['id' => $objectif->getCycleid()->getId()], Response::HTTP_SEE_OTHER);
    }
}
