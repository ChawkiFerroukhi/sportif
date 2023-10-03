<?php

namespace App\Controller;

use App\Entity\Dossiermedical;
use App\Entity\Adherant;
use App\Entity\Section;
use App\Form\DossiermedicalType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/dossiermedical')]
class DossiermedicalController extends AbstractController
{
    #[Route('/', name: 'app_dossiermedical_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();
        if(!isset($usr->getRoles()["app_dossiermedical_index"]) && !isset($usr->getRoles()['ROLE_MASTER'])) {
            $this->user = $usr;
            return $this->redirectToRoute('app_home_access_denied', [], Response::HTTP_SEE_OTHER);
        }
        $dossiermedicals = $entityManager
            ->getRepository(Dossiermedical::class)
            ->findBy(['clubid' => $this->getUser()->getClubid()]);
        $sections = $entityManager
            ->getRepository(Section::class)
            ->findBy(['clubid' => $this->getUser()->getClubid()]);
        $this->user = $usr;
        return $this->render('dossiermedical/index.html.twig', [
            'dossiermedicals' => $dossiermedicals,
            'sections' => $sections,
            'section' => new Section(),
        ]);
    }

    #[Route('/new', name: 'app_dossiermedical_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();
        
        if(!isset($usr->getRoles()["app_dossiermedical_new"]) && !isset($usr->getRoles()["ROLE_DOCTOR"]) && !isset($usr->getRoles()['ROLE_MASTER'])) {
            $this->user = $usr;
            return $this->redirectToRoute('app_home_access_denied', [], Response::HTTP_SEE_OTHER);
        }
        $dossiermedical = new Dossiermedical();
        $form = $this->createForm(DossiermedicalType::class, $dossiermedical);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($dossiermedical);
            $entityManager->flush();

            $this->user = $usr;
        return $this->redirectToRoute('app_dossiermedical_show', ['id'=> $dossiermedical->getId()], Response::HTTP_SEE_OTHER);
        }

        $this->user = $usr;
        return $this->renderForm('dossiermedical/new.html.twig', [
            'dossiermedical' => $dossiermedical,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_dossiermedical_show', methods: ['GET'])]
    public function show(Dossiermedical $dossiermedical, EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();
        $adherant = $dossiermedical->getAdherantid();
        if(!isset($usr->getRoles()['ROLE_MASTER']) && !isset($usr->getRoles()['ROLE_DOCTOR']) && !isset($usr->getRoles()['app_dossiermedical_show']) && $usr->getId() != $adherant->getId() && $usr->getId() != $adherant->getSupervisorid()->getId() && $usr->getId() != $adherant->getSupervisor2id()->getId()) {
            $this->user = $usr;
            return $this->redirectToRoute('app_home_access_denied', [], Response::HTTP_SEE_OTHER);
        }
        $sections = $entityManager
            ->getRepository(Section::class)
            ->findBy(['clubid' => $this->getUser()->getClubid()]);
        $this->user = $usr;
        return $this->render('dossiermedical/show.html.twig', [
            'dossiermedical' => $dossiermedical,
            'sections' => $sections,
            'section' => $dossiermedical->getAdherantid()->getEquipeid()->getNiveauid()->getSectionid(),
        ]);
    }

    #[Route('/{id}/edit', name: 'app_dossiermedical_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Dossiermedical $dossiermedical, EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();
        $adherant = $dossiermedical->getAdherantid();
        if(!isset($usr->getRoles()['ROLE_MASTER']) && !isset($usr->getRoles()['ROLE_DOCTOR']) && !isset($usr->getRoles()['app_dossiermedical_edit']) && $usr->getId() != $adherant->getId() && $usr->getId() != $adherant->getSupervisorid()->getId() && $usr->getId() != $adherant->getSupervisor2id()->getId()) {
            $this->user = $usr;
            return $this->redirectToRoute('app_home_access_denied', [], Response::HTTP_SEE_OTHER);
        }
        $form = $this->createForm(DossiermedicalType::class, $dossiermedical);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->user = $usr;
        return $this->redirectToRoute('app_dossiermedical_index', [], Response::HTTP_SEE_OTHER);
        }

        $this->user = $usr;
        return $this->renderForm('dossiermedical/edit.html.twig', [
            'dossiermedical' => $dossiermedical,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_dossiermedical_delete', methods: ['POST'])]
    public function delete(Request $request, Dossiermedical $dossiermedical, EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();
        if(!isset($usr->getRoles()["app_dossiermedical_delete"]) && !isset($usr->getRoles()["ROLE_DOCTOR"]) && !isset($usr->getRoles()['ROLE_MASTER'])) {
            $this->user = $usr;
            return $this->redirectToRoute('app_home_access_denied', [], Response::HTTP_SEE_OTHER);
        }
        if ($this->isCsrfTokenValid('delete'.$dossiermedical->getId(), $request->request->get('_token'))) {
            $entityManager->remove($dossiermedical);
            $entityManager->flush();
        }

        $this->user = $usr;
        return $this->redirectToRoute('app_dossiermedical_index', [], Response::HTTP_SEE_OTHER);
    }
}
