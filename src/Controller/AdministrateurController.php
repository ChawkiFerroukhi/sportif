<?php

namespace App\Controller;

use App\Entity\Administrateur;
use App\Entity\User;
use App\Entity\Section;
use App\Entity\Club;
use App\Form\AdministrateurType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


#[Route('/administrateur')]
class AdministrateurController extends AbstractController
{
    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }
    #[Route('/', name: 'app_administrateur_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();
        if(!isset($usr->getRoles()["app_administrateur_index"]) && !isset($usr->getRoles()['ROLE_MASTER'])&& !isset($usr->getRoles()['ROLE_ADMIN'])) {
            $this->user = $usr;
            return $this->redirectToRoute('app_club_show', ["id" => $usr->getClubid()->getId()], Response::HTTP_SEE_OTHER);
        }
        $administrateurs = [];
        if(isset($usr->getRoles()["ROLE_MASTER"])) {
            $administrateurs = $entityManager
                ->getRepository(Administrateur::class)
                ->findAll();
        } else {
            $administrateurs = $entityManager
                ->getRepository(Administrateur::class)
                ->findBy(['clubid' => $this->getUser()->getClubid()]);
        }
        $sections = $entityManager
            ->getRepository(Section::class)
            ->findBy(['clubid' => $this->getUser()->getClubid()]);
        $section = new Section();
        $this->user = $usr;
        return $this->render('administrateur/index.html.twig', [
            'administrateurs' => $administrateurs,
            'sections' => $sections,
            'section' => $section,
        ]);
    }

    #[Route('/organigramme', name: 'app_administrateur_gram', methods: ['GET'])]
    public function organigramme(EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();
        if(!isset($usr->getRoles()["app_administrateur_index"]) && !isset($usr->getRoles()['ROLE_MASTER'])&& !isset($usr->getRoles()['ROLE_ADMIN'])) {
            $this->user = $usr;
            return $this->redirectToRoute('app_club_show', ["id" => $usr->getClubid()->getId()], Response::HTTP_SEE_OTHER);
        }
        $administrateurs = [];
        if(isset($usr->getRoles()["ROLE_MASTER"])) {
            $administrateurs = $entityManager
                ->getRepository(Administrateur::class)
                ->findAll();
        } else {
            $administrateurs = $entityManager
                ->getRepository(Administrateur::class)
                ->findBy(['clubid' => $this->getUser()->getClubid()]);
        }
        $sections = $entityManager
            ->getRepository(Section::class)
            ->findBy(['clubid' => $this->getUser()->getClubid()]);
        $presidents = [];
        $vices = [];
        $tresoriers = [];
        $SGs = [];
        $chefs = [];
        foreach($administrateurs as $administrateur) {
            if($administrateur->getPoste()=="Président(e)"){
                array_push($presidents,$administrateur);
            } else if($administrateur->getPoste()=="Vice-président(e)"){
                array_push($vices,$administrateur);
            } else if($administrateur->getPoste()=="Trésorier"){
                array_push($tresoriers,$administrateur);
            } else if($administrateur->getPoste()=="Secrétaire Général(e)"){
                array_push($SGs,$administrateur);
            } else if($administrateur->getPoste()=="Chef de Section"){
                array_push($chefs,$administrateur);
            }
        }
        $section = new Section();
        $this->user = $usr;
        return $this->render('administrateur/organigramme.html.twig', [
            'administrateurs' => $administrateurs,
            'presidents' => $presidents,
            'vices' => $vices,
            'tresoriers' => $tresoriers,
            'SGs' => $SGs,
            'chefs' => $chefs,
            'sections' => $sections,
            'section' => $section,
        ]);
    }

    #[Route('/new', name: 'app_administrateur_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();
        if(!isset($usr->getRoles()["app_administrateur_new"]) && !isset($usr->getRoles()['ROLE_MASTER']) && !isset($usr->getRoles()['ROLE_ADMIN'])) {
            $this->user = $usr;
            return $this->redirectToRoute('app_club_show', ["id" => $usr->getClubid()->getId()], Response::HTTP_SEE_OTHER);
        }
        $administrateur = new Administrateur();
        $form = $this->createForm(AdministrateurType::class, $administrateur);
        $form->handleRequest($request);
        $sections = $entityManager
            ->getRepository(Section::class)
            ->findBy(['clubid' => $this->getUser()->getClubid()]);
        $section = new Section();
        if ($form->isSubmitted() && $form->isValid()) {
            $administrateur->setCreatedAt(new \DateTime());
            $administrateur->setUpdatedAt(new \DateTime());
            if(!empty($form->get('clubid')->getData())) {
                $administrateur->setClubid($form->get('clubid')->getData());
            } else {
                $administrateur->setClubid($usr->getClubid());
            }
            $administrateur->setPassword($this->passwordHasher->hashPassword(
                $administrateur,
                $form->get('password')->getData()
            ));
            $entityManager->persist($administrateur);
            $entityManager->flush();

            $this->user = $usr;
            return $this->redirectToRoute('app_administrateur_show', ['id' => $administrateur->getId()], Response::HTTP_SEE_OTHER);
        }

        $this->user = $usr;
        return $this->renderForm('administrateur/new.html.twig', [
            'administrateur' => $administrateur,
            'form' => $form,
            'sections' => $sections,
            'section' => $section,
        ]);
    }

    #[Route('/{id}', name: 'app_administrateur_show', methods: ['GET'])]
    public function show(Administrateur $administrateur, EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();
        $sections = $entityManager
            ->getRepository(Section::class)
            ->findBy(['clubid' => $this->getUser()->getClubid()]);
        $section = new Section();
        $this->user = $usr;
        return $this->render('administrateur/show.html.twig', [
            'administrateur' => $administrateur,
            'sections' => $sections,
            'section' => $section,

        ]);
    }

    #[Route('/{id}/edit', name: 'app_administrateur_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Administrateur $administrateur, EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();
        if(!isset($usr->getRoles()["app_administrateur_edit"]) && !isset($usr->getRoles()['ROLE_MASTER'])) {
            $this->user = $usr;
            return $this->redirectToRoute('app_club_show', ["id" => $usr->getClubid()->getId()], Response::HTTP_SEE_OTHER);
        }
        $form = $this->createForm(AdministrateurType::class, $administrateur);
        $form->handleRequest($request);
        $sections = $entityManager
            ->getRepository(Section::class)
            ->findBy(['clubid' => $this->getUser()->getClubid()]);

        if ($form->isSubmitted() && $form->isValid()) {
            if($form->get('password')->getData()!=null) {
                $administrateur->setPassword($this->passwordHasher->hashPassword(
                    $administrateur,
                    $form->get('password')->getData()
                ));
            }
            $entityManager->flush();

            $this->user = $usr;
        return $this->redirectToRoute('app_administrateur_index', [], Response::HTTP_SEE_OTHER);
        }
        $section = new Section();
        $this->user = $usr;
        return $this->renderForm('administrateur/edit.html.twig', [
            'administrateur' => $administrateur,
            'form' => $form,
            'sections' => $sections,
            'section' => $section,
        ]);
    }

    #[Route('/{id}', name: 'app_administrateur_delete', methods: ['POST'])]
    public function delete(Request $request, Administrateur $administrateur, EntityManagerInterface $entityManager, UserRepository $userRepo): Response
    {
        $usr = $this->getUser();
        if(!isset($usr->getRoles()["app_administrateur_delete"]) && !isset($usr->getRoles()['ROLE_MASTER'])) {
            $this->user = $usr;
            return $this->redirectToRoute('app_club_show', ["id" => $usr->getClubid()->getId()], Response::HTTP_SEE_OTHER);
        }
        if ($this->isCsrfTokenValid('delete'.$administrateur->getId(), $request->request->get('_token'))) {
            $entityManager->remove($administrateur);
            $userRepo->remove($administrateur);
            $entityManager->flush();
        }

        $this->user = $usr;
        return $this->redirectToRoute('app_administrateur_index', [], Response::HTTP_SEE_OTHER);
    }
}
