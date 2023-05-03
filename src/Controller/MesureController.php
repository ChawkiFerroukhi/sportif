<?php

namespace App\Controller;

use App\Entity\Mesure;
use App\Entity\Section;
use App\Entity\Dossiermedical;
use App\Entity\Doctor;
use App\Form\MesureType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/mesure')]
class MesureController extends AbstractController
{
    #[Route('/dossier/{id}', name: 'app_mesure_index', methods: ['GET'])]
    public function index(Dossiermedical $dossier,EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();
        $mesures = $entityManager
            ->getRepository(Mesure::class)
            ->findBy(['dossierMedicalid'=> $dossier->getId()]);
        $sections = $entityManager
            ->getRepository(Section::class)
            ->findBy(['clubid' => $this->getUser()->getClubid()]);

        $this->user = $usr;
        return $this->render('mesure/index.html.twig', [
            'mesures' => $mesures,
            'sections' => $sections,
            'section' => new Section(),
        ]);
    }

    #[Route('/{id}/new', name: 'app_mesure_new', methods: ['GET', 'POST'])]
    public function new(Dossiermedical $dossier,Request $request, EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();
        $mesure = new Mesure();
        $sections = $entityManager
            ->getRepository(Section::class)
            ->findBy(['clubid' => $this->getUser()->getClubid()]);
        $doctors = $entityManager
        ->getRepository(Doctor::class)
        ->findBy(['clubid' => $this->getUser()->getClubid()]);
        $form = $this->createForm(MesureType::class, $mesure, [
            'doctors' => $doctors,
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $mesure->setCreatedAt(new \DateTime());
            $mesure->setUpdatedAt(new \DateTime());
            $mesure->setDossiermedicalid($dossier);
            $mesure->setClubid($dossier->getClubid());
            $mesure->setImc($mesure->getPoids() / ($mesure->getTaille() * $mesure->getTaille()));
            if($form->get('doctorid')->getData() == null) {
                $doctor = $entityManager
                    ->getRepository(Doctor::class)
                    ->find($this->getUser()->getId());
                $mesure->setDoctorid($doctor);
            }
            $entityManager->persist($mesure);
            $entityManager->flush();

            $this->user = $usr;
        return $this->redirectToRoute('app_dossier_show', ['id' => $dossier->getId()], Response::HTTP_SEE_OTHER);
        }

        $this->user = $usr;
        return $this->renderForm('mesure/new.html.twig', [
            'mesure' => $mesure,
            'form' => $form,
            'sections' => $sections,
            'section' => $dossier->getAdherantid()->getEquipeid()->getNiveauid()->getSectionid(),
        ]);
    }

    #[Route('/{id}', name: 'app_mesure_show', methods: ['GET'])]
    public function show(Mesure $mesure, EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();
        $sections = $entityManager
            ->getRepository(Section::class)
            ->findBy(['clubid' => $this->getUser()->getClubid()]);
        $this->user = $usr;
        return $this->render('mesure/show.html.twig', [
            'mesure' => $mesure,
            'sections' => $sections,
            'section' => $mesure->getDossiermedicalid()->getAdherantid()->getEquipeid()->getNiveauid()->getSectionid(),
        ]);
    }

    #[Route('/{id}/edit', name: 'app_mesure_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Mesure $mesure, EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();
        $doctors = $entityManager
        ->getRepository(Doctor::class)
        ->findBy(['clubid' => $this->getUser()->getClubid()]);
        $form = $this->createForm(MesureType::class, $mesure, [
            'doctors' => $doctors,
        ]);
        $form->handleRequest($request);
        $sections = $entityManager
            ->getRepository(Section::class)
            ->findBy(['clubid' => $this->getUser()->getClubid()]);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->user = $usr;
        return $this->redirectToRoute('app_mesure_index', [], Response::HTTP_SEE_OTHER);
        }

        $this->user = $usr;
        return $this->renderForm('mesure/edit.html.twig', [
            'mesure' => $mesure,
            'form' => $form,
            'sections' => $sections,
        ]);
    }

    #[Route('/{id}', name: 'app_mesure_delete', methods: ['POST'])]
    public function delete(Request $request, Mesure $mesure, EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();
        if ($this->isCsrfTokenValid('delete'.$mesure->getId(), $request->request->get('_token'))) {
            $entityManager->remove($mesure);
            $entityManager->flush();
        }

        $this->user = $usr;
        return $this->redirectToRoute('app_mesure_index', [], Response::HTTP_SEE_OTHER);
    }
}
