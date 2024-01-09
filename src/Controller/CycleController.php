<?php

namespace App\Controller;

use App\Entity\Cycle;
use App\Entity\Niveau;
use App\Entity\Section;
use App\Form\CycleType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/cycle')]
class CycleController extends AbstractController
{
    #[Route('/', name: 'app_cycle_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();
        $cycles = $entityManager
            ->getRepository(Cycle::class)
            ->findAll();

        $sections = $entityManager
            ->getRepository(Section::class)
            ->findBy(['clubid' => $this->getUser()->getClubid()]);
        $section = new Section();

        $this->user = $usr;
        return $this->render('cycle/index.html.twig', [
            'cycles' => $cycles,
            'sections' => $sections,
            'section' => $section,
        ]);
    }

    #[Route('/{id}/new', name: 'app_cycle_new', methods: ['GET', 'POST'])]
    public function new(Niveau $niveau,Request $request, EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();
        if(!isset($usr->getRoles()["app_cycle_new"]) && !isset($usr->getRoles()['ROLE_MASTER'])) {
            $this->user = $usr;
            return $this->redirectToRoute('app_home_access_denied', [], Response::HTTP_SEE_OTHER);
        }
        $sections = $entityManager
            ->getRepository(Section::class)
            ->findBy(['clubid' => $this->getUser()->getClubid()]);
        $cycle = new Cycle();
        $form = $this->createForm(CycleType::class, $cycle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $cycle->setCreatedAt(new \DateTime());
            $cycle->setUpdatedAt(new \DateTime());
            $cycle->setNiveauid($niveau);
            $cycle->setClubid($niveau->getClubid());            
            $entityManager->persist($cycle);
            $entityManager->flush();

            $this->user = $usr;
            return $this->redirectToRoute('app_cycle_show', ['id' => $cycle->getId()], Response::HTTP_SEE_OTHER);
        }

        $this->user = $usr;
        return $this->renderForm('cycle/new.html.twig', [
            'cycle' => $cycle,
            'form' => $form,
            'sections' => $sections,
            'section' => $niveau->getSectionid(),
        ]);
    }

    #[Route('/{id}', name: 'app_cycle_show', methods: ['GET'])]
    public function show(Cycle $cycle,EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();

        $sections = $entityManager
            ->getRepository(Section::class)
            ->findBy(['clubid' => $this->getUser()->getClubid()]);
        $this->user = $usr;
        return $this->render('cycle/show.html.twig', [
            'cycle' => $cycle,
            'sections' => $sections,
            'section' => $cycle->getNiveauid()->getSectionid(),
        ]);
    }

    #[Route('/{id}/edit', name: 'app_cycle_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Cycle $cycle, EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();
        if(!isset($usr->getRoles()["app_cycle_edit"]) && !isset($usr->getRoles()['ROLE_MASTER'])) {
            $this->user = $usr;
            return $this->redirectToRoute('app_home_access_denied', [], Response::HTTP_SEE_OTHER);
        }
        $sections = $entityManager
            ->getRepository(Section::class)
            ->findBy(['clubid' => $this->getUser()->getClubid()]);
        $form = $this->createForm(CycleType::class, $cycle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->user = $usr;
            return $this->redirectToRoute('app_cycle_show', ['id' => $cycle->getId()], Response::HTTP_SEE_OTHER);
        }

        $this->user = $usr;
        return $this->renderForm('cycle/edit.html.twig', [
            'cycle' => $cycle,
            'form' => $form,
            'sections' => $sections,
            'section' => $cycle->getNiveauid()->getSectionid(),
        ]);
    }

    #[Route('/{id}', name: 'app_cycle_delete', methods: ['POST'])]
    public function delete(Request $request, Cycle $cycle, EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();
        if(!isset($usr->getRoles()["app_cycle_delete"]) && !isset($usr->getRoles()['ROLE_MASTER'])) {
            $this->user = $usr;
            return $this->redirectToRoute('app_home_access_denied', [], Response::HTTP_SEE_OTHER);
        }
        if ($this->isCsrfTokenValid('delete'.$cycle->getId(), $request->request->get('_token'))) {
            $entityManager->remove($cycle);
            $entityManager->flush();
        }

        $this->user = $usr;
        return $this->redirectToRoute('app_niveau_show', ['id' => $cycle->getNiveauid()->getId()], Response::HTTP_SEE_OTHER);
    }
}
