<?php

namespace App\Controller;

use App\Entity\Cours;
use App\Entity\Niveau;
use App\Entity\Section;
use App\Form\CoursType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/cours')]
class CoursController extends AbstractController
{
    #[Route('/', name: 'app_cours_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();
        $cours = $entityManager
            ->getRepository(Cours::class)
            ->findAll();
        $sections = $entityManager
            ->getRepository(Section::class)
            ->findBy(['clubid' => $this->getUser()->getClubid()]);
        $section = new Section();
        $this->user = $usr;
        return $this->render('cours/index.html.twig', [
            'cours' => $cours,
            'sections' => $sections,
            'section' => $section,
        ]);
    }

    #[Route('/{id}/new', name: 'app_cours_new', methods: ['GET', 'POST'])]
    public function new(Niveau $niveau,Request $request, EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();
        $sections = $entityManager
            ->getRepository(Section::class)
            ->findBy(['clubid' => $this->getUser()->getClubid()]);
        $cour = new Cours();
        $form = $this->createForm(CoursType::class, $cour);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $cour->setCreatedAt(new \DateTime());
            $cour->setUpdatedAt(new \DateTime());
            $cour->setNiveauid($niveau);  
            $cour->setClubid($niveau->getClubid());            
            $entityManager->persist($cour);
            $entityManager->flush();

            $this->user = $usr;
        return $this->redirectToRoute('app_niveau_show', ['id' => $niveau->getId()], Response::HTTP_SEE_OTHER);
        }

        $this->user = $usr;
        return $this->renderForm('cours/new.html.twig', [
            'cour' => $cour,
            'form' => $form,
            'sections' => $sections,
            'section' => $niveau->getSectionid()
        ]);
    }

    #[Route('/{id}', name: 'app_cours_show', methods: ['GET'])]
    public function show(Cours $cour, EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();
        $sections = $entityManager
            ->getRepository(Section::class)
            ->findBy(['clubid' => $this->getUser()->getClubid()]);
        $this->user = $usr;
        return $this->render('cours/show.html.twig', [
            'cour' => $cour,
            'sections' => $sections,
            'section' => $cour->getNiveauid()->getSectionid(),
        ]);
    }

    #[Route('/{id}/edit', name: 'app_cours_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Cours $cour, EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();
        $sections = $entityManager
            ->getRepository(Section::class)
            ->findBy(['clubid' => $this->getUser()->getClubid()]);
        $form = $this->createForm(CoursType::class, $cour);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->user = $usr;
            return $this->redirectToRoute('app_cours_show', ['id' => $cour->getId()], Response::HTTP_SEE_OTHER);
        }

        $this->user = $usr;
        return $this->renderForm('cours/edit.html.twig', [
            'cour' => $cour,
            'form' => $form,
            'sections' => $sections,
            'section' => $cour->getNiveauid()->getSectionid(),
        ]);
    }

    #[Route('/{id}', name: 'app_cours_delete', methods: ['POST'])]
    public function delete(Request $request, Cours $cour, EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();
        
        if ($this->isCsrfTokenValid('delete'.$cour->getId(), $request->request->get('_token'))) {
            $entityManager->remove($cour);
            $entityManager->flush();
        }

        $this->user = $usr;
        return $this->redirectToRoute('app_niveau_show', ['id' => $cour->getNiveauid()->getId()], Response::HTTP_SEE_OTHER);
    }
}
