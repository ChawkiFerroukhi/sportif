<?php

namespace App\Controller;

use App\Entity\Adherant;
use App\Entity\Supervisor;
use App\Entity\Dossiermedical;
use App\Entity\Section;
use App\Form\AdherantType;
use App\Form\SupervisorType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Route('/adherant')]
class AdherantController extends AbstractController
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }
    #[Route('/', name: 'app_adherant_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();
        if(!isset($usr->getRoles()["app_adherant_index"]) && !isset($usr->getRoles()['ROLE_MASTER'])) {
            $this->user = $usr;
            return $this->redirectToRoute('app_club_show', ["id" => $usr->getClubid()->getId()], Response::HTTP_SEE_OTHER);
        }
        $adherants = $entityManager
            ->getRepository(Adherant::class)
            ->findAll();
        $sections = $entityManager
            ->getRepository(Section::class)
            ->findBy(['clubid' => $this->getUser()->getClubid()]);
        $section = new Section();
        $this->user = $usr;
        return $this->render('adherant/index.html.twig', [
            'adherants' => $adherants,
            'sections' => $sections,
            'section' => $section,
        ]);
    }

    #[Route('/new', name: 'app_adherant_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();
        if(!isset($usr->getRoles()["app_adherant_new"]) && !isset($usr->getRoles()['ROLE_MASTER'])) {
            $this->user = $usr;
            return $this->redirectToRoute('app_club_show', ["id" => $usr->getClubid()->getId()], Response::HTTP_SEE_OTHER);
        }
        $section = new Section();
        $adherant = new Adherant();
        $supervisors = $entityManager
            ->getRepository(Supervisor::class)
            ->findBy(['clubid' => $this->getUser()->getClubid()]);
        $form = $this->createForm(AdherantType::class, $adherant, ['supervisors' => $supervisors]);
        $form->handleRequest($request);
        $sections = $entityManager
            ->getRepository(Section::class)
            ->findBy(['clubid' => $this->getUser()->getClubid()]);
        if ($form->isSubmitted() && $form->isValid()) {

            $adherant->setCreatedAt(new \DateTime());
            $adherant->setUpdatedAt(new \DateTime());
            $adherant->setClubid($adherant->getEquipeid()->getClubid());
            $dossier = new Dossiermedical();
            $dossier->setCreatedAt(new \DateTime());
            $dossier->setUpdatedAt(new \DateTime());
            $dossier->setClubid($adherant->getEquipeid()->getClubid());
            $entityManager->persist($dossier);
            $adherant->setDossierMedicaId($dossier);
            $adherant->setRoles(['ROLE_ADHERANT']);
            $adherant->setPassword($this->passwordHasher->hashPassword(
                $adherant,
                $form->get('password')->getData()
            ));
            if($form->get('supervisorId')->getData()) {
                $adherant->setSupervisorId($form->get('supervisorId')->getData());
            } else if ($form->get('supervisor_nom')->getData()) {
                $supervisor = new Supervisor();
                $supervisor->setCreatedAt(new \DateTime());
                $supervisor->setUpdatedAt(new \DateTime());
                $supervisor->setEmail($form->get('supervisor_Email')->getData());
                $supervisor->setPassword($this->passwordHasher->hashPassword(
                    $supervisor,
                    $form->get('supervisor_password')->getData()
                ));
                $supervisor->setRef($form->get('supervisor_ref')->getData());
                $supervisor->setNom($form->get('supervisor_nom')->getData());
                $supervisor->setPrenom($form->get('supervisor_prenom')->getData());
                $supervisor->setNumTel($form->get('supervisor_numTel')->getData());
                $supervisor->setAdresse($form->get('supervisor_adresse')->getData());
                $supervisor->setCin($form->get('supervisor_cin')->getData());
                $supervisor->setRoles(['ROLE_SUPERVISOR']);
                $supervisor->setClubid($adherant->getClubid());
                $entityManager->persist($supervisor);
                $adherant->setSupervisorId($supervisor);
            } 
            $entityManager->persist($adherant);
            $entityManager->flush();

            $this->user = $usr;
        return $this->redirectToRoute('app_adherant_index', [], Response::HTTP_SEE_OTHER);
        }

        $this->user = $usr;
        return $this->renderForm('adherant/new.html.twig', [
            'adherant' => $adherant,
            'form' => $form,
            'sections' => $sections,
            'section' => $section,
        ]);
    }

    #[Route('/{id}', name: 'app_adherant_show', methods: ['GET'])]
    public function show(Adherant $adherant, EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();
        $sections = $entityManager
            ->getRepository(Section::class)
            ->findBy(['clubid' => $this->getUser()->getClubid()]);
        $section = new Section();
        $this->user = $usr;
        return $this->render('adherant/show.html.twig', [
            'adherant' => $adherant,
            'sections' => $sections,
            'section' => $section,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_adherant_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Adherant $adherant, EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();
        if(!isset($usr->getRoles()["app_adheerant_edit"]) && !isset($usr->getRoles()['ROLE_MASTER'])) {
            $this->user = $usr;
            return $this->redirectToRoute('app_club_show', ["id" => $usr->getClubid()->getId()], Response::HTTP_SEE_OTHER);
        }
        $supervisors = $entityManager
        ->getRepository(Supervisor::class)
        ->findBy(['clubid' => $this->getUser()->getClubid()]);
        $form = $this->createForm(AdherantType::class, $adherant, ['supervisors' => $supervisors]);        
        $form->handleRequest($request);
        $sections = $entityManager
            ->getRepository(Section::class)
            ->findBy(['clubid' => $this->getUser()->getClubid()]);
        $section = new Section();
        if ($form->isSubmitted() && $form->isValid()) {
            $adherant->setPassword($this->passwordHasher->hashPassword(
                $adherant,
                $form->get('password')->getData()
            ));
            $entityManager->flush();

            $this->user = $usr;
        return $this->redirectToRoute('app_adherant_index', [], Response::HTTP_SEE_OTHER);
        }

        $this->user = $usr;
        return $this->renderForm('adherant/edit.html.twig', [
            'adherant' => $adherant,
            'form' => $form,
            'sections' => $sections,
            'section' => $section,
        ]);
    }

    #[Route('/{id}', name: 'app_adherant_delete', methods: ['POST'])]
    public function delete(Request $request, Adherant $adherant, EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();
        if(!isset($usr->getRoles()["app_adheerant_delete"]) && !isset($usr->getRoles()['ROLE_MASTER'])) {
            $this->user = $usr;
            return $this->redirectToRoute('app_club_show', ["id" => $usr->getClubid()->getId()], Response::HTTP_SEE_OTHER);
        }
        if ($this->isCsrfTokenValid('delete'.$adherant->getId(), $request->request->get('_token'))) {
            $entityManager->remove($adherant);
            $entityManager->flush();
        }

        $this->user = $usr;
        return $this->redirectToRoute('app_adherant_index', [], Response::HTTP_SEE_OTHER);
    }
}
