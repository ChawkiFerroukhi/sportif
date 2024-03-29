<?php

namespace App\Controller;

use App\Entity\Doctor;
use App\Entity\Section;
use App\Form\DoctorType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Route('/doctor')]
class DoctorController extends AbstractController
{
    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }
    #[Route('/', name: 'app_doctor_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();
        if(!isset($usr->getRoles()["app_doctor_index"]) && !isset($usr->getRoles()['ROLE_MASTER']) ) {
            $this->user = $usr;
            return $this->redirectToRoute('app_home_access_denied', [], Response::HTTP_SEE_OTHER);
        }
        $doctors = [];
        if(isset($usr->getRoles()["ROLE_MASTER"])) {
            $doctors = $entityManager
                ->getRepository(Doctor::class)
                ->findAll();
        } else {
            $doctors = $entityManager
                ->getRepository(Doctor::class)
                ->findBy(['clubid' => $this->getUser()->getClubid()]);
        }
        $sections = $entityManager
            ->getRepository(Section::class)
            ->findBy(['clubid' => $this->getUser()->getClubid()]);
        $section = new Section();
        $this->user = $usr;
        return $this->render('doctor/index.html.twig', [
            'doctors' => $doctors,
            'sections' => $sections,
            'section' => $section,
        ]);
    }

    #[Route('/new', name: 'app_doctor_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();
        if(!isset($usr->getRoles()["app_doctor_new"]) && !isset($usr->getRoles()['ROLE_MASTER']) ) {
            $this->user = $usr;
            return $this->redirectToRoute('app_home_access_denied', [], Response::HTTP_SEE_OTHER);
        }
        $section = new Section();
        $doctor = new Doctor();
        $form = $this->createForm(DoctorType::class, $doctor);
        $form->handleRequest($request);
        $sections = $entityManager
            ->getRepository(Section::class)
            ->findBy(['clubid' => $this->getUser()->getClubid()]);

        if ($form->isSubmitted() && $form->isValid()) {
            $doctor->setCreatedAt(new \DateTime());
            $doctor->setUpdatedAt(new \DateTime());
            $image = $form->get('image')->getData();
            if($image != null) {
                $image->setClubid($doctor->getClubid());
                $image->setAdherantid($doctor);
                $image->setCreatedAt(new \DateTime());
                $image->setUpdatedAt(new \DateTime());
                $entityManager->persist($image);
            }
            if(!empty($form->get('clubid')->getData())) {
                $doctor->setClubid($form->get('clubid')->getData());
            } else {
                $doctor->setClubid($usr->getClubid());
            }
            $doctor->setPassword($this->passwordHasher->hashPassword(
                $doctor,
                $form->get('password')->getData()
            ));
            $entityManager->persist($doctor);
            $entityManager->flush();

            $this->user = $usr;
        return $this->redirectToRoute('app_doctor_show', ['id' => $doctor->getId()], Response::HTTP_SEE_OTHER);
        }

        $this->user = $usr;
        return $this->renderForm('doctor/new.html.twig', [
            'doctor' => $doctor,
            'form' => $form,
            'sections' => $sections,
            'section' => $section,
        ]);
    }

    #[Route('/{id}', name: 'app_doctor_show', methods: ['GET'])]
    public function show(Doctor $doctor, EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();
        if($usr->getId() != $doctor->getId() && !isset($usr->getRoles()["app_doctor_show"]) && !isset($usr->getRoles()["ROLE_MASTER"])) {
            $this->user = $usr;
            return $this->redirectToRoute('app_home_access_denied', [], Response::HTTP_SEE_OTHER);
        }
        $section = new Section();
        $sections = $entityManager
            ->getRepository(Section::class)
            ->findBy(['clubid' => $this->getUser()->getClubid()]);
        $this->user = $usr;
        return $this->render('doctor/show.html.twig', [
            'doctor' => $doctor,
            'sections' => $sections,
            'section' => $section,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_doctor_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Doctor $doctor, EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();
        if(!isset($usr->getRoles()["app_doctor_edit"]) && !isset($usr->getRoles()['ROLE_MASTER'])) {
            $this->user = $usr;
            return $this->redirectToRoute('app_home_access_denied', [], Response::HTTP_SEE_OTHER);
        }
        $section = new Section();
        $form = $this->createForm(DoctorType::class, $doctor);
        $form->handleRequest($request);
        $sections = $entityManager
            ->getRepository(Section::class)
            ->findBy(['clubid' => $this->getUser()->getClubid()]);

        if ($form->isSubmitted() && $form->isValid()) {
            $image = $form->get('image')->getData();
                if($image != null) {
                    $image->setClubid($doctor->getClubid());
                    $image->setAdherantid($doctor);
                    $image->setCreatedAt(new \DateTime());
                    $image->setUpdatedAt(new \DateTime());
                    $entityManager->persist($image);
                }
            if($form->get('password')->getData()!=null) {
                $doctor->setPassword($this->passwordHasher->hashPassword(
                    $doctor,
                    $form->get('password')->getData()
                ));
            }
            $entityManager->flush();

            $this->user = $usr;
        return $this->redirectToRoute('app_doctor_index', [], Response::HTTP_SEE_OTHER);
        }

        $this->user = $usr;
        return $this->renderForm('doctor/edit.html.twig', [
            'doctor' => $doctor,
            'form' => $form,
            'sections' => $sections,
            'section' => $section,
        ]);
    }

    #[Route('/{id}', name: 'app_doctor_delete', methods: ['POST'])]
    public function delete(Request $request, Doctor $doctor, EntityManagerInterface $entityManager, UserRepository $userRepo): Response
    {
        $usr = $this->getUser();
        if(!isset($usr->getRoles()["app_doctor_delete"]) && !isset($usr->getRoles()['ROLE_MASTER'])) {
            $this->user = $usr;
            return $this->redirectToRoute('app_home_access_denied', [], Response::HTTP_SEE_OTHER);
        }
        if ($this->isCsrfTokenValid('delete'.$doctor->getId(), $request->request->get('_token'))) {
            $entityManager->remove($doctor);
            $userRepo->remove($doctor);
            $entityManager->flush();
        }

        $this->user = $usr;
        return $this->redirectToRoute('app_doctor_index', [], Response::HTTP_SEE_OTHER);
    }
}
