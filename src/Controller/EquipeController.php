<?php

namespace App\Controller;

use App\Entity\Equipe;
use App\Entity\Niveau;
use App\Entity\Coach;
use App\Entity\Doctor;
use App\Entity\Section;
use App\Form\EquipeType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/equipe')]
class EquipeController extends AbstractController
{
    #[Route('/', name: 'app_equipe_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $equipes = $entityManager
            ->getRepository(Equipe::class)
            ->findAll();
        $sections = $entityManager
            ->getRepository(Section::class)
            ->findAll();

        return $this->render('equipe/index.html.twig', [
            'equipes' => $equipes,
            'sections' => $sections,
        ]);
    }

    #[Route('/{id}/new', name: 'app_equipe_new', methods: ['GET', 'POST'])]
    public function new(Niveau $niveau,Request $request, EntityManagerInterface $entityManager): Response
    {
        $equipe = new Equipe();
        $form = $this->createForm(EquipeType::class, $equipe);
        $form->handleRequest($request);
        $sections = $entityManager
            ->getRepository(Section::class)
            ->findAll();

        if ($form->isSubmitted() && $form->isValid()) {
            $equipe->setCreatedAt(new \DateTime());
            $equipe->setUpdatedAt(new \DateTime());
            $equipe->setNiveauid($niveau);
            $equipe->setClubid($niveau->getClubid());
            
            if($form->get('coachid')->getData()) {
                $equipe->setCoachId($form->get('coachid')->getData());
            } else if ($form->get('coach_nom')->getData()) {
                $coach = new Coach();
                $coach->setCreatedAt(new \DateTime());
                $coach->setUpdatedAt(new \DateTime());
                $coach->setNom($form->get('coach_nom')->getData());
                $coach->setPrenom($form->get('coach_prenom')->getData());
                $coach->setNumTel($form->get('coach_numTel')->getData());
                $coach->setAdresse($form->get('coach_adresse')->getData());
                $coach->setCin($form->get('coach_cin')->getData());
                $coach->setClubid($equipe->getClubid());
                $entityManager->persist($coach);
                $equipe->setCoachId($coach);
            } 
            if($form->get('doctorid')->getData()) {
                $equipe->setDoctorId($form->get('doctorid')->getData());
            } else if ($form->get('doctor_nom')->getData()) {
                $doctor = new Doctor();
                $doctor->setCreatedAt(new \DateTime());
                $doctor->setUpdatedAt(new \DateTime());
                $doctor->setNom($form->get('doctor_nom')->getData());
                $doctor->setPrenom($form->get('doctor_prenom')->getData());
                $doctor->setNumTel($form->get('doctor_numTel')->getData());
                $doctor->setAdresse($form->get('doctor_adresse')->getData());
                $doctor->setCin($form->get('doctor_cin')->getData());
                $doctor->setClubid($equipe->getClubid());
                $entityManager->persist($doctor);
                $equipe->setDoctorId($doctor);
            } 
            $entityManager->persist($equipe);
            $entityManager->flush();

            return $this->redirectToRoute('app_equipe_show', ['id'=>$equipe->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('equipe/new.html.twig', [
            'equipe' => $equipe,
            'form' => $form,
            'sections' => $sections,
        ]);
    }

    #[Route('/{id}', name: 'app_equipe_show', methods: ['GET'])]
    public function show(Equipe $equipe,EntityManagerInterface $entityManager): Response
    {

        $sections = $entityManager
            ->getRepository(Section::class)
            ->findAll();
        return $this->render('equipe/show.html.twig', [
            'equipe' => $equipe,
            'sections' => $sections,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_equipe_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Equipe $equipe, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EquipeType::class, $equipe);
        $form->handleRequest($request);
        $sections = $entityManager
            ->getRepository(Section::class)
            ->findAll();

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_equipe_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('equipe/edit.html.twig', [
            'equipe' => $equipe,
            'form' => $form,
            'sections' => $sections,
        ]);
    }

    #[Route('/{id}', name: 'app_equipe_delete', methods: ['POST','GET'])]
    public function delete(Request $request, Equipe $equipe, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$equipe->getId(), $request->request->get('_token'))) {
            $entityManager->remove($equipe);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_equipe_index', [], Response::HTTP_SEE_OTHER);
    }
}
