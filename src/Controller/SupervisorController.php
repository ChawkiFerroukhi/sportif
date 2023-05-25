<?php

namespace App\Controller;

use App\Entity\Supervisor;
use App\Entity\Section;
use App\Form\SupervisorType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


#[Route('/supervisor')]
class SupervisorController extends AbstractController
{
    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }
    #[Route('/', name: 'app_supervisor_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();
        if(!isset($usr->getRoles()["app_supervisor_index"]) && !isset($usr->getRoles()['ROLE_MASTER']) && !isset($usr->getRoles()['ROLE_ADMIN'])) {
            $this->user = $usr;
            return $this->redirectToRoute('app_club_show', ["id" => $usr->getClubid()->getId()], Response::HTTP_SEE_OTHER);
        }
        $section = new Section();
        $supervisors = [];
        if(isset($usr->getRoles()["ROLE_MASTER"])) {
            $supervisors = $entityManager
                ->getRepository(Supervisor::class)
                ->findAll();
        } else {
            $supervisors = $entityManager
                ->getRepository(Supervisor::class)
                ->findBy(['clubid' => $this->getUser()->getClubid()]);
        }
        $sections = $entityManager
            ->getRepository(Section::class)
            ->findBy(['clubid' => $this->getUser()->getClubid()]);

        $this->user = $usr;
        return $this->render('supervisor/index.html.twig', [
            'supervisors' => $supervisors,
            'sections' => $sections,
            'section' => $section,
        ]);
    }

    #[Route('/new', name: 'app_supervisor_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();
        if(!isset($usr->getRoles()["app_supervisor_new"]) && !isset($usr->getRoles()['ROLE_MASTER']) && !isset($usr->getRoles()['ROLE_ADMIN'])) {
            $this->user = $usr;
            return $this->redirectToRoute('app_club_show', ["id" => $usr->getClubid()->getId()], Response::HTTP_SEE_OTHER);
        }
        $section = new Section();
        $supervisor = new Supervisor();
        $form = $this->createForm(SupervisorType::class, $supervisor);
        $form->handleRequest($request);
        $sections = $entityManager
            ->getRepository(Section::class)
            ->findBy(['clubid' => $this->getUser()->getClubid()]);

        if ($form->isSubmitted() && $form->isValid()) {

            $supervisor->setCreatedAt(new \DateTime());
            $supervisor->setUpdatedAt(new \DateTime());
            if(!empty($form->get('clubid')->getData())) {
                $supervisor->setClubid($form->get('clubid')->getData());
            } else {
                $supervisor->setClubid($usr->getClubid());
            }
            $supervisor->setPassword($this->passwordHasher->hashPassword(
                $supervisor,
                $form->get('password')->getData()
            ));
            $entityManager->persist($supervisor);
            $entityManager->flush();

            $this->user = $usr;
        return $this->redirectToRoute('app_supervisor_show', ['id' => $supervisor->getId()], Response::HTTP_SEE_OTHER);
        }

        $this->user = $usr;
        return $this->renderForm('supervisor/new.html.twig', [
            'supervisor' => $supervisor,
            'form' => $form,
            'sections' => $sections,
            'section' => $section,
        ]);
    }

    #[Route('/{id}', name: 'app_supervisor_show', methods: ['GET'])]
    public function show(Supervisor $supervisor, EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();
        $sections = $entityManager
            ->getRepository(Section::class)
            ->findBy(['clubid' => $this->getUser()->getClubid()]);
        $section = new Section();
        $this->user = $usr;
        return $this->render('supervisor/show.html.twig', [
            'supervisor' => $supervisor,
            'sections' => $sections,
            'section' => $section,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_supervisor_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Supervisor $supervisor, EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();
        if(!isset($usr->getRoles()["app_supervisor_edit"]) && !isset($usr->getRoles()['ROLE_MASTER'])) {
            $this->user = $usr;
            return $this->redirectToRoute('app_club_show', ["id" => $usr->getClubid()->getId()], Response::HTTP_SEE_OTHER);
        }
        $section = new Section();
        $form = $this->createForm(SupervisorType::class, $supervisor);
        $form->handleRequest($request);
        $sections = $entityManager
            ->getRepository(Section::class)
            ->findBy(['clubid' => $this->getUser()->getClubid()]);

        if ($form->isSubmitted() && $form->isValid()) {
            if($form->get('password')->getData()!=null) {
                $supervisor->setPassword($this->passwordHasher->hashPassword(
                    $supervisor,
                    $form->get('password')->getData()
                ));
            }
            $entityManager->flush();

            $this->user = $usr;
        return $this->redirectToRoute('app_supervisor_index', [], Response::HTTP_SEE_OTHER);
        }

        $this->user = $usr;
        return $this->renderForm('supervisor/edit.html.twig', [
            'supervisor' => $supervisor,
            'form' => $form,
            'sections' => $sections,
            'section' => $section,
        ]);
    }

    #[Route('/{id}', name: 'app_supervisor_delete', methods: ['POST'])]
    public function delete(Request $request, Supervisor $supervisor, EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();
        if(!isset($usr->getRoles()["app_supervisor_delete"]) && !isset($usr->getRoles()['ROLE_MASTER'])) {
            $this->user = $usr;
            return $this->redirectToRoute('app_club_show', ["id" => $usr->getClubid()->getId()], Response::HTTP_SEE_OTHER);
        }
        if ($this->isCsrfTokenValid('delete'.$supervisor->getId(), $request->request->get('_token'))) {
            $entityManager->remove($supervisor);
            $entityManager->flush();
        }

        $this->user = $usr;
        return $this->redirectToRoute('app_supervisor_index', [], Response::HTTP_SEE_OTHER);
    }
}
