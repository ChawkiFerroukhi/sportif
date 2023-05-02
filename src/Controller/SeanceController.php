<?php

namespace App\Controller;

use App\Entity\Seance;
use App\Entity\Equipe;
use App\Entity\Cycle;
use App\Entity\Section;
use App\Form\SeanceType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/seance')]
class SeanceController extends AbstractController
{
    #[Route('/equipe/{id}', name: 'app_seance_index', methods: ['GET'])]
    public function index(Equipe $equipe,EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();
        $seances = $entityManager
            ->getRepository(Seance::class)
            ->findBy(['equipeid' => $equipe->getId()]);
        $sections = $entityManager
            ->getRepository(Section::class)
            ->findBy(['clubid' => $this->getUser()->getClubid()]);
        $this->user = $usr;
        return $this->render('seance/index.html.twig', [
            'seances' => $seances,
            'sections' => $sections,
            'section' => $equipe->getNiveauid()->getSectionid()
        ]);
    }

    #[Route('/{id}/new', name: 'app_seance_new', methods: ['GET', 'POST'])]
    public function new(Cycle $cycle,Request $request, EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();
        $sections = $entityManager
            ->getRepository(Section::class)
            ->findBy(['clubid' => $this->getUser()->getClubid()]);
        $seance = new Seance();
        $form = $this->createForm(SeanceType::class, $seance,[
            'choices' => $cycle->getCoursid()->getNiveauid()->getEquipes()
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $seance->setCreatedAt(new \DateTime());
            $seance->setUpdatedAt(new \DateTime());
            $seance->setCycleid($cycle);
            $seance->setClubid($cycle->getClubid());
            $entityManager->persist($seance);
            $entityManager->flush();

            $this->user = $usr;
        return $this->redirectToRoute('app_cycle_show', ['id' => $cycle->getId()], Response::HTTP_SEE_OTHER);
        }

        $this->user = $usr;
        return $this->renderForm('seance/new.html.twig', [
            'seance' => $seance,
            'form' => $form,
            'cycle' => $cycle,
            'sections' => $sections,
            'section' => $cycle->getCoursid()->getNiveauid()->getSectionid()
        ]);
    }

    #[Route('/{id}', name: 'app_seance_show', methods: ['GET'])]
    public function show(Seance $seance, EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();
        $sections = $entityManager
            ->getRepository(Section::class)
            ->findBy(['clubid' => $this->getUser()->getClubid()]);
        $this->user = $usr;
        return $this->render('seance/show.html.twig', [
            'seance' => $seance,
            'sections' => $sections,
            'section' => $seance->getEquipeid()->getNiveauid()->getSectionid()
        ]);
    }

    #[Route('/{id}/edit', name: 'app_seance_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Seance $seance, EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();
        $sections = $entityManager
            ->getRepository(Section::class)
            ->findBy(['clubid' => $this->getUser()->getClubid()]);
        $form = $this->createForm(SeanceType::class, $seance,[
            'choices' => $seance->getCycleid()->getCoursid()->getNiveauid()->getEquipes()
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->user = $usr;
        return $this->redirectToRoute('app_seance_show', ['id' => $seance->getId()], Response::HTTP_SEE_OTHER);
        }

        $this->user = $usr;
        return $this->renderForm('seance/edit.html.twig', [
            'seance' => $seance,
            'form' => $form,
            'sections' => $sections,
            'section' => $seance->getCycleid()->getCoursid()->getNiveauid()->getSectionid()

        ]);
    }

    #[Route('/{id}', name: 'app_seance_delete', methods: ['POST'])]
    public function delete(Request $request, Seance $seance, EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();
        if ($this->isCsrfTokenValid('delete'.$seance->getId(), $request->request->get('_token'))) {
            $entityManager->remove($seance);
            $entityManager->flush();
        }

        $this->user = $usr;
        return $this->redirectToRoute('app_seance_index', [], Response::HTTP_SEE_OTHER);
    }
}
