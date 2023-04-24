<?php

namespace App\Controller;

use App\Entity\Master;
use App\Entity\Section;
use App\Form\MasterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


#[Route('/master')]
class MasterController extends AbstractController
{
    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }
    #[Route('/', name: 'app_master_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();
        if(!isset($usr->getRoles()['ROLE_MASTER']) ) {
            $this->user = $usr;
            return $this->redirectToRoute('app_club_index', [], Response::HTTP_SEE_OTHER);
        }
        $masters = $entityManager
            ->getRepository(Master::class)
            ->findAll();
        $sections = $entityManager
            ->getRepository(Section::class)
            ->findBy(['clubid' => $this->getUser()->getClubid()]);
        $this->user = $usr;
        return $this->render('master/index.html.twig', [
            'masters' => $masters,
            'section' => $sections
        ]);
    }

    #[Route('/new', name: 'app_master_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();
        if(!isset($usr->getRoles()['ROLE_MASTER']) ) {
            $this->user = $usr;
            return $this->redirectToRoute('app_club_index', [], Response::HTTP_SEE_OTHER);
        }
        $sections = $entityManager
            ->getRepository(Section::class)
            ->findBy(['clubid' => $this->getUser()->getClubid()]);
        $master = new Master();
        $form = $this->createForm(MasterType::class, $master);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $master->setRoles(['ROLE_MASTER']);
            $master->setPassword($this->passwordHasher->hashPassword(
                $master,
                $form->get('password')->getData()
            ));
            $entityManager->persist($master);
            $entityManager->flush();

            $this->user = $usr;
        return $this->redirectToRoute('app_master_index', [], Response::HTTP_SEE_OTHER);
        }

        $this->user = $usr;
        return $this->renderForm('master/new.html.twig', [
            'master' => $master,
            'form' => $form,
            'sections' => $sections
        ]);
    }

    #[Route('/{id}', name: 'app_master_show', methods: ['GET'])]
    public function show(Master $master): Response
    {
        $usr = $this->getUser();
        if(!isset($usr->getRoles()['ROLE_MASTER']) ) {
            $this->user = $usr;
            return $this->redirectToRoute('app_club_index', [], Response::HTTP_SEE_OTHER);
        }
        $sections = $entityManager
            ->getRepository(Section::class)
            ->findBy(['clubid' => $this->getUser()->getClubid()]);
        $this->user = $usr;
        return $this->render('master/show.html.twig', [
            'master' => $master,
            'sections' => $sections
        ]);
    }

    #[Route('/{id}/edit', name: 'app_master_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Master $master, EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();
        if(!isset($usr->getRoles()['ROLE_MASTER']) ) {
            $this->user = $usr;
            return $this->redirectToRoute('app_club_index', [], Response::HTTP_SEE_OTHER);
        }
        $sections = $entityManager
            ->getRepository(Section::class)
            ->findBy(['clubid' => $this->getUser()->getClubid()]);
        $form = $this->createForm(MasterType::class, $master);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $master->setPassword($this->passwordHasher->hashPassword(
                $master,
                $form->get('password')->getData()
            ));
            $entityManager->flush();

            $this->user = $usr;
        return $this->redirectToRoute('app_master_index', [], Response::HTTP_SEE_OTHER);
        }

        $this->user = $usr;
        return $this->renderForm('master/edit.html.twig', [
            'master' => $master,
            'form' => $form,
            'sections' => $sections
        ]);
    }

    #[Route('/{id}', name: 'app_master_delete', methods: ['POST'])]
    public function delete(Request $request, Master $master, EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();
        if(!isset($usr->getRoles()['ROLE_MASTER']) ) {
            $this->user = $usr;
            return $this->redirectToRoute('app_club_index', [], Response::HTTP_SEE_OTHER);
        }
        $sections = $entityManager
            ->getRepository(Section::class)
            ->findBy(['clubid' => $this->getUser()->getClubid()]);
        if ($this->isCsrfTokenValid('delete'.$master->getId(), $request->request->get('_token'))) {
            $entityManager->remove($master);
            $entityManager->flush();
        }

        $this->user = $usr;
        return $this->redirectToRoute('app_master_index', [], Response::HTTP_SEE_OTHER);
    }
}
