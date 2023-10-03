<?php

namespace App\Controller;

use App\Entity\Dossier;
use App\Entity\Section;
use App\Entity\Adherant;
use App\Form\DossierType;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/dossier')]
class DossierController extends AbstractController
{
    #[Route('/adherant/{id}', name: 'app_dossier_index', methods: ['GET'])]
    public function index(Adherant $adherant, EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();
        if( $usr->getId() != $adherant->getId() && $usr->getId() != $adherant->getSupervisorId()->getId() && ( $adherant->getSupervisor2id() != null && $usr->getId() != $adherant->getSupervisor2id()->getId()) && !isset($usr->getRoles()['app_dossier_index']) && !isset($usr->getRoles()['ROLE_MASTER']) ) {
            $this->user = $usr;
            return $this->redirectToRoute('app_adherant_show', ['id' => $adherant->getId()], Response::HTTP_SEE_OTHER);
        }
        $dossiers = $entityManager
            ->getRepository(Dossier::class)
            ->findBy(['adherantid' => $adherant->getId()]);
        $sections = $entityManager
            ->getRepository(Section::class)
            ->findBy(['clubid' => $this->getUser()->getClubid()]);
        $section = $adherant->getEquipeid()->getNiveauid()->getSectionid();
        $this->user = $usr;
        return $this->render('dossier/index.html.twig', [
            'dossiers' => $dossiers,
            'adherant' => $adherant,
            'sections' => $sections,
            'section' => $section
        ]);
    }

    #[Route('/{id}/new', name: 'app_dossier_new', methods: ['GET', 'POST'])]
    public function new(Adherant $adherant, Request $request, EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();
        if( $usr->getId() == $adherant->getId() && $usr->getId() != $adherant->getSupervisorId() && $usr->getId() != $adherant->getSupervisor2Id() && !isset($usr->getRoles()['app_dossier_new']) && !isset($usr->getRoles()['ROLE_MASTER']) ) {
            $this->user = $usr;
            return $this->redirectToRoute('app_adherant_show', ['id' => $adherant->getId()], Response::HTTP_SEE_OTHER);
        }
        $section = new Section();
        $dossier = new Dossier();
        $form = $this->createForm(DossierType::class, $dossier);
        $form->handleRequest($request);
        $sections = $entityManager
            ->getRepository(Section::class)
            ->findBy(['clubid' => $this->getUser()->getClubid()]);
        if ($form->isSubmitted() && $form->isValid()) {
            $dossier->setCreatedAt(new \DateTime());
            $dossier->setUpdatedAt(new \DateTime());
            $dossier->setClubid($adherant->getClubid());
            $dossier->setAdherantid($adherant);
            $entityManager->persist($dossier);
            $entityManager->flush();
            $this->user = $usr;
            return $this->redirectToRoute('app_dossier_index', ['id' => $adherant->getId()], Response::HTTP_SEE_OTHER);
        }
        $this->user = $usr;
        return $this->renderForm('dossier/new.html.twig', [
            'dossier' => $dossier,
            'form' => $form,
            'sections' => $sections,
            'section' => $section
        ]);
    }

    #[Route('/{id}', name: 'app_dossier_show', methods: ['GET'])]
    public function show(Dossier $dossier, EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();
        if($usr->getId() == $dossier->getAdherantid()->getId() && $usr->getId() != $dossier->getAdherantid()->getSupervisorId() && $usr->getId() != $dossier->getAdherantid()->getSupervisor2Id() && !isset($usr->getRoles()["app_dossier_show"]) && !isset($usr->getRoles()['ROLE_MASTER'])) {
            $this->user = $usr;
            return $this->redirectToRoute('app_home_access_denied', [], Response::HTTP_SEE_OTHER);
        }
        $sections = $entityManager
            ->getRepository(Section::class)
            ->findBy(['dossierid' => $this->getUser()->getDossierid()]);
        $section = new Section();
        $this->user = $usr;
        return $this->render('dossier/show.html.twig', [
            'dossier' => $dossier,
            'sections' => $sections,
            'section' => $section
        ]);
    }

    #[Route('/{id}/edit', name: 'app_dossier_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Dossier $dossier, EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();
        $adherant = $dossier->getAdherantid();
        if( $usr->getId() == $adherant->getId() && $usr->getId() != $adherant->getSupervisorId() && $usr->getId() != $adherant->getSupervisor2Id() && !isset($usr->getRoles()["app_dossier_edit"]) && !isset($usr->getRoles()['ROLE_MASTER']) ) {
            $this->user = $usr;
            return $this->redirectToRoute('app_adherant_show', ['id' => $adherant->getId()], Response::HTTP_SEE_OTHER);
        }
        $section = new Section();
        $form = $this->createForm(DossierType::class, $dossier);
        $form->handleRequest($request);
        $sections = $entityManager
            ->getRepository(Section::class)
            ->findBy(['clubid' => $this->getUser()->getClubid()]);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->user = $usr;
            return $this->redirectToRoute('app_dossier_index', ['id' => $adherant->getId()], Response::HTTP_SEE_OTHER);
        }
        $this->user = $usr;
        return $this->renderForm('dossier/edit.html.twig', [
            'dossier' => $dossier,
            'form' => $form,
            'sections' => $sections,
            'section' => $section
        ]);
    }

    #[Route('/{id}', name: 'app_dossier_delete', methods: ['POST'])]
    public function delete(Request $request, Dossier $dossier, EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();
        if(!isset($usr->getRoles()["app_dossier_delete"]) && !isset($usr->getRoles()['ROLE_MASTER'])) {
            $this->user = $usr;
            return $this->redirectToRoute('app_home_access_denied', [], Response::HTTP_SEE_OTHER);
        }
        if ($this->isCsrfTokenValid('delete'.$dossier->getId(), $request->request->get('_token'))) {
            $entityManager->remove($dossier);
            $entityManager->flush();
        }
        $this->user = $usr;
        return $this->redirectToRoute('app_dossier_index', ['id' => $dossier->getAdherantid()->getId()], Response::HTTP_SEE_OTHER);
    }
}
