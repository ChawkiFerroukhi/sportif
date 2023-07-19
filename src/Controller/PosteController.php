<?php

namespace App\Controller;

use App\Entity\Poste;
use App\Entity\Administrateur;
use App\Entity\Cycle;
use App\Entity\Section;
use App\Form\PosteType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/poste')]
class PosteController extends AbstractController
{
    #[Route('/', name: 'app_poste_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();
        if(isset($usr->getRoles()['ROLE_MASTER'])){
            $postes = $entityManager
                ->getRepository(Poste::class)
                ->findAll();
            } else {
            $postes = $entityManager
                ->getRepository(Poste::class)
                ->findBy(['clubid' => $this->getUser()->getClubid()]);
        }
        $sections = $entityManager
            ->getRepository(Section::class)
            ->findBy(['clubid' => $this->getUser()->getClubid()]);
        $this->user = $usr;
        return $this->render('poste/index.html.twig', [
            'postes' => $postes,
            'sections' => $sections,
            'section' => new Section()
        ]);
    }

    #[Route('/new', name: 'app_poste_new', methods: ['GET', 'POST'])]
    public function new(Request $request,EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();
        if(!isset($usr->getRoles()['ROLE_ADMIN']) ) {
            $this->user = $usr;
            return $this->redirectToRoute('app_club_show', ["id" => $usr->getClubid()->getId()], Response::HTTP_SEE_OTHER);
        }
        $poste = new Poste();
        $form = $this->createForm(PosteType::class, $poste);
        $form->handleRequest($request);
        $sections = $entityManager
            ->getRepository(Section::class)
            ->findBy(['clubid' => $this->getUser()->getClubid()]);

        if ($form->isSubmitted() && $form->isValid()) {
            $poste->setCreatedAt(new \DateTime());
            $poste->setUpdatedAt(new \DateTime());
            if(!empty($form->get('clubid')->getData())) {
                $poste->setClubid($form->get('clubid')->getData());
            } else {
                $poste->setClubid($usr->getClubid());
            }
            $entityManager->persist($poste);
            $entityManager->flush();

            $this->user = $usr;
        return $this->redirectToRoute('app_poste_show', ['id' => $poste->getId()], Response::HTTP_SEE_OTHER);
        }

        $this->user = $usr;
        return $this->renderForm('poste/new.html.twig', [
            'poste' => $poste,
            'form' => $form,
            'sections' => $sections,
            'section' => new Section()
        ]);
    }

    #[Route('/{id}', name: 'app_poste_show', methods: ['GET'])]
    public function show(Poste $poste,EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();
        $this->user = $usr;
        $sections = $entityManager
            ->getRepository(Section::class)
            ->findBy(['clubid' => $this->getUser()->getClubid()]);
        $administrateurs = $entityManager
            ->getRepository(Administrateur::class)
            ->findBy(['clubid'=>$poste->getClubid(),'poste'=>$poste->getNom()]);
        return $this->render('poste/show.html.twig', [
            'poste' => $poste,
            'administrateurs' => $administrateurs,
            'sections' => $sections,
            'section' => new Section()
        ]);
    }

    #[Route('/{id}/edit', name: 'app_poste_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Poste $poste, EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();

        if(!isset($usr->getRoles()['ROLE_ADMIN']) ) {
            $this->user = $usr;
            return $this->redirectToRoute('app_club_show', ["id" => $usr->getClubid()->getId()], Response::HTTP_SEE_OTHER);
        }
        $form = $this->createForm(PosteType::class, $poste);
        $form->handleRequest($request);
        $sections = $entityManager
            ->getRepository(Section::class)
            ->findBy(['clubid' => $this->getUser()->getClubid()]);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->user = $usr;
            return $this->redirectToRoute('app_poste_show', ['id' => $poste->getId()], Response::HTTP_SEE_OTHER);
        }

        $this->user = $usr;
        return $this->renderForm('poste/edit.html.twig', [
            'poste' => $poste,
            'form' => $form,
            'sections' => $sections,
            'section' => new Section()
        ]);
    }

    #[Route('/{id}', name: 'app_poste_delete', methods: ['POST'])]
    public function delete(Request $request, Poste $poste, EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();
        if(!isset($usr->getRoles()['ROLE_ADMIN']) ) {
            $this->user = $usr;
            return $this->redirectToRoute('app_club_show', ["id" => $usr->getClubid()->getId()], Response::HTTP_SEE_OTHER);
        }
        if ($this->isCsrfTokenValid('delete'.$poste->getId(), $request->request->get('_token'))) {
            $entityManager->remove($poste);
            $entityManager->flush();
        }

        $this->user = $usr;
        return $this->redirectToRoute('app_cycle_show', ['id' => $poste->getCycleid()->getId()], Response::HTTP_SEE_OTHER);
    }
}
