<?php

namespace App\Controller;

use App\Entity\Staff;
use App\Entity\Section;
use App\Form\StaffType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Route('/staff')]
class StaffController extends AbstractController
{
    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }
    #[Route('/', name: 'app_staff_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();
        if(!isset($usr->getRoles()["app_staff_index"]) && !isset($usr->getRoles()['ROLE_MASTER'])) {
            $this->user = $usr;
            return $this->redirectToRoute('app_home_access_denied', [], Response::HTTP_SEE_OTHER);
        }
        $section = new Section();
        $staffs = [];
        if(isset($usr->getRoles()["ROLE_MASTER"])) {
            $staffs = $entityManager
                ->getRepository(Staff::class)
                ->findAll();
        } else {
            $staffs = $entityManager
                ->getRepository(Staff::class)
                ->findBy(['clubid' => $this->getUser()->getClubid()]);
        }
        $sections = $entityManager
            ->getRepository(Section::class)
            ->findBy(['clubid' => $this->getUser()->getClubid()]);

        $this->user = $usr;
        return $this->render('staff/index.html.twig', [
            'staffs' => $staffs,
            'sections' => $sections,
            'section' => $section,
        ]);
    }

    #[Route('/new', name: 'app_staff_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();
        if(!isset($usr->getRoles()["app_staff_new"]) && !isset($usr->getRoles()['ROLE_MASTER']) ) {
            $this->user = $usr;
            return $this->redirectToRoute('app_home_access_denied', [], Response::HTTP_SEE_OTHER);
        }
        $section = new Section();
        $staff = new Staff();
        $form = $this->createForm(StaffType::class, $staff);
        $form->handleRequest($request);
        $sections = $entityManager
            ->getRepository(Section::class)
            ->findBy(['clubid' => $this->getUser()->getClubid()]);

        if ($form->isSubmitted() && $form->isValid()) {
            $staff->setCreatedAt(new \DateTime());
            $staff->setUpdatedAt(new \DateTime());
            $image = $form->get('image')->getData();
            if($image != null) {
                $image->setClubid($staff->getClubid());
                $image->setAdherantid($staff);
                $image->setCreatedAt(new \DateTime());
                $image->setUpdatedAt(new \DateTime());
                $entityManager->persist($image);
            }
            if(!empty($form->get('clubid')->getData())) {
                $staff->setClubid($form->get('clubid')->getData());
            } else {
                $staff->setClubid($usr->getClubid());
            }
            $staff->setPassword($this->passwordHasher->hashPassword(
                $staff,
                $form->get('password')->getData()
            ));
            $entityManager->persist($staff);
            $entityManager->flush();

            $this->user = $usr;
            return $this->redirectToRoute('app_staff_show', ['id' => $staff->getId()], Response::HTTP_SEE_OTHER);
        }

        $this->user = $usr;
        return $this->renderForm('staff/new.html.twig', [
            'staff' => $staff,
            'form' => $form,
            'sections' => $sections,
            'section' => $section,
        ]);
    }


    #[Route('/{id}', name: 'app_staff_show', methods: ['GET'])]
    public function show(Staff $staff,EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();
        if($usr->getId() != $staff->getId() && !isset($usr->getRoles()["app_staff_show"]) && !isset($usr->getRoles()["ROLE_MASTER"])) {
            $this->user = $usr;
            return $this->redirectToRoute('app_home_access_denied', [], Response::HTTP_SEE_OTHER);
        }

        $sections = $entityManager
            ->getRepository(Section::class)
            ->findBy(['clubid' => $this->getUser()->getClubid()]);
        $section = new Section();
        $this->user = $usr;
        return $this->render('staff/show.html.twig', [
            'staff' => $staff,
            'sections' => $sections,
            'section' => $section,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_staff_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Staff $staff, EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();
        if(!isset($usr->getRoles()["app_staff_edit"]) && !isset($usr->getRoles()['ROLE_MASTER'])) {
            $this->user = $usr;
            return $this->redirectToRoute('app_home_access_denied', [], Response::HTTP_SEE_OTHER);
        }
        $section = new Section();
        $form = $this->createForm(StaffType::class, $staff);
        $form->handleRequest($request);
        $sections = $entityManager
            ->getRepository(Section::class)
            ->findBy(['clubid' => $this->getUser()->getClubid()]);

        if ($form->isSubmitted() && $form->isValid()) {
            $image = $form->get('image')->getData();
            if($image != null) {
                $image->setClubid($staff->getClubid());
                $image->setAdherantid($staff);
                $image->setCreatedAt(new \DateTime());
                $image->setUpdatedAt(new \DateTime());
                $entityManager->persist($image);
            }
            if($form->get('password')->getData()!=null) {
                $staff->setPassword($this->passwordHasher->hashPassword(
                    $staff,
                    $form->get('password')->getData()
                ));
            }
            $entityManager->flush();

            $this->user = $usr;
        return $this->redirectToRoute('app_staff_index', [], Response::HTTP_SEE_OTHER);
        }

        $this->user = $usr;
        return $this->renderForm('staff/edit.html.twig', [
            'staff' => $staff,
            'form' => $form,
            'sections' => $sections,
            'section' => $section,
        ]);
    }

    #[Route('/{id}', name: 'app_staff_delete', methods: ['POST'])]
    public function delete(Request $request, Staff $staff, EntityManagerInterface $entityManager, UserRepository $userRepo): Response
    {
        $usr = $this->getUser();
        if(!isset($usr->getRoles()["app_staff_delete"]) && !isset($usr->getRoles()['ROLE_MASTER'])) {
            $this->user = $usr;
            return $this->redirectToRoute('app_home_access_denied', [], Response::HTTP_SEE_OTHER);
        }
        if ($this->isCsrfTokenValid('delete'.$staff->getId(), $request->request->get('_token'))) {
            $entityManager->remove($staff);
            $userRepo->remove($staff);
            $entityManager->flush();
        }

        $this->user = $usr;
        return $this->redirectToRoute('app_staff_index', [], Response::HTTP_SEE_OTHER);
    }
}
