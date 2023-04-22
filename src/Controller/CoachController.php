<?php

namespace App\Controller;

use App\Entity\Coach;
use App\Entity\Section;
use App\Form\CoachType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/coach')]
class CoachController extends AbstractController
{
    #[Route('/', name: 'app_coach_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();
        $coaches = $entityManager
            ->getRepository(Coach::class)
            ->findAll();
        $sections = $entityManager
            ->getRepository(Section::class)
            ->findBy(['clubid' => $this->getUser()->getClubid()]);

        $this->user = $usr;
        return $this->render('coach/index.html.twig', [
            'coaches' => $coaches,
            'sections' => $sections,
        ]);
    }

    #[Route('/new', name: 'app_coach_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();
        $coach = new Coach();
        $form = $this->createForm(CoachType::class, $coach);
        $form->handleRequest($request);
        $sections = $entityManager
            ->getRepository(Section::class)
            ->findBy(['clubid' => $this->getUser()->getClubid()]);

        if ($form->isSubmitted() && $form->isValid()) {
            $coach->setCreatedAt(new \DateTime());
            $coach->setUpdatedAt(new \DateTime());
            $coach->setRoles(['ROLE_COACH']);
            $entityManager->persist($coach);
            $entityManager->flush();

            $this->user = $usr;
        return $this->redirectToRoute('app_coach_index', [], Response::HTTP_SEE_OTHER);
        }

        $this->user = $usr;
        return $this->renderForm('coach/new.html.twig', [
            'coach' => $coach,
            'form' => $form,
            'sections' => $sections,
        ]);
    }

    #[Route('/{id}', name: 'app_coach_show', methods: ['GET'])]
    public function show(Coach $coach,EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();
        $sections = $entityManager
            ->getRepository(Section::class)
            ->findBy(['clubid' => $this->getUser()->getClubid()]);
        $this->user = $usr;
        return $this->render('coach/show.html.twig', [
            'coach' => $coach,
            'sections' => $sections,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_coach_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Coach $coach, EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();
        $form = $this->createForm(CoachType::class, $coach);
        $form->handleRequest($request);
        $sections = $entityManager
            ->getRepository(Section::class)
            ->findBy(['clubid' => $this->getUser()->getClubid()]);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->user = $usr;
        return $this->redirectToRoute('app_coach_index', [], Response::HTTP_SEE_OTHER);
        }

        $this->user = $usr;
        return $this->renderForm('coach/edit.html.twig', [
            'coach' => $coach,
            'form' => $form,
            'sections' => $sections,
        ]);
    }

    #[Route('/{id}', name: 'app_coach_delete', methods: ['POST'])]
    public function delete(Request $request, Coach $coach, EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();
        if ($this->isCsrfTokenValid('delete'.$coach->getId(), $request->request->get('_token'))) {
            $entityManager->remove($coach);
            $entityManager->flush();
        }

        $this->user = $usr;
        return $this->redirectToRoute('app_coach_index', [], Response::HTTP_SEE_OTHER);
    }
}
