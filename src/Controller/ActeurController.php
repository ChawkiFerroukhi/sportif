<?php

namespace App\Controller;

use App\Entity\Acteur;
use App\Entity\User;
use App\Entity\Section;
use App\Entity\Club;
use App\Entity\Poste;
use App\Form\ActeurType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


#[Route('/acteur')]
class ActeurController extends AbstractController
{
    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }
    #[Route('/', name: 'app_acteur_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();
        if(!isset($usr->getRoles()["app_acteur_index"]) && !isset($usr->getRoles()['ROLE_MASTER'])&& !isset($usr->getRoles()['ROLE_ADMIN'])) {
            $this->user = $usr;
            return $this->redirectToRoute('app_club_show', ["id" => $usr->getClubid()->getId()], Response::HTTP_SEE_OTHER);
        }
        $acteurs = [];
        if(isset($usr->getRoles()["ROLE_MASTER"])) {
            $acteurs = $entityManager
                ->getRepository(Acteur::class)
                ->findAll();
        } else {
            $acteurs = $entityManager
                ->getRepository(Acteur::class)
                ->findBy(['clubid' => $this->getUser()->getClubid()]);
        }
        $sections = $entityManager
            ->getRepository(Section::class)
            ->findBy(['clubid' => $this->getUser()->getClubid()]);
        $section = new Section();
        $this->user = $usr;
        return $this->render('acteur/index.html.twig', [
            'acteurs' => $acteurs,
            'sections' => $sections,
            'section' => $section,
        ]);
    }

    #[Route('/organigramme', name: 'app_acteur_gram', methods: ['GET'])]
    public function organigramme(EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();
        if(!isset($usr->getRoles()["app_acteur_index"]) && !isset($usr->getRoles()['ROLE_MASTER'])&& !isset($usr->getRoles()['ROLE_ADMIN'])) {
            $this->user = $usr;
            return $this->redirectToRoute('app_club_show', ["id" => $usr->getClubid()->getId()], Response::HTTP_SEE_OTHER);
        }
        $acteurs = [];
        if(isset($usr->getRoles()["ROLE_MASTER"])) {
            $acteurs = $entityManager
                ->getRepository(Acteur::class)
                ->findAll();
        } else {
            $acteurs = $entityManager
                ->getRepository(Acteur::class)
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
        $others = [];
        foreach($acteurs as $acteur) {
            if($acteur->getPoste() != null) {
                if($acteur->getPoste()->getNom()=="Président"){
                    array_push($presidents,$acteur);
                } else if($acteur->getPoste()->getNom()=="Vice-président"){
                    array_push($vices,$acteur);
                } else if($acteur->getPoste()->getNom()=="Trésorier"){
                    array_push($tresoriers,$acteur);
                } else if($acteur->getPoste()->getNom()=="Secrétaire Général"){
                    array_push($SGs,$acteur);
                } else {
                    if(isset($others[$acteur->getPoste()->getNom()])){
                        array_push($others[$acteur->getPoste()->getNom()],$acteur);
                    } else {
                        $others[$acteur->getPoste()->getNom()] = [$acteur];
                    }
                }
            }
            
        }
        $section = new Section();
        $this->user = $usr;
        return $this->render('acteur/organigramme.html.twig', [
            'acteurs' => $acteurs,
            'presidents' => $presidents,
            'vices' => $vices,
            'tresoriers' => $tresoriers,
            'SGs' => $SGs,
            'chefs' => $chefs,
            'others' => $others,
            'sections' => $sections,
            'section' => $section,
        ]);
    }

    #[Route('/new', name: 'app_acteur_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();
        if(!isset($usr->getRoles()["app_acteur_new"]) && !isset($usr->getRoles()['ROLE_MASTER']) && !isset($usr->getRoles()['ROLE_ADMIN'])) {
            $this->user = $usr;
            return $this->redirectToRoute('app_club_show', ["id" => $usr->getClubid()->getId()], Response::HTTP_SEE_OTHER);
        }
        $postes = [];
        if(isset($usr->getRoles()['ROLE_MASTER'])){
            $postes = $entityManager
                ->getRepository(Poste::class)
                ->findAll();
        } else {
            $postes = $entityManager
                ->getRepository(Poste::class)
                ->findBy(['clubid' => $usr->getClubid()]);
        }
        $acteur = new Acteur();
        $form = $this->createForm(ActeurType::class, $acteur, ['postes' => $postes]);
        $form->handleRequest($request);
        $sections = $entityManager
            ->getRepository(Section::class)
            ->findBy(['clubid' => $this->getUser()->getClubid()]);
        
        $section = new Section();
        if ($form->isSubmitted() && $form->isValid()) {
            $acteur->setCreatedAt(new \DateTime());
            $acteur->setUpdatedAt(new \DateTime());
            if(!empty($form->get('clubid')->getData())) {
                $acteur->setClubid($form->get('clubid')->getData());
            } else {
                $acteur->setClubid($usr->getClubid());
            }
            $entityManager->persist($acteur);
            $entityManager->flush();

            $this->user = $usr;
            return $this->redirectToRoute('app_acteur_show', ['id' => $acteur->getId()], Response::HTTP_SEE_OTHER);
        }

        $this->user = $usr;
        return $this->renderForm('acteur/new.html.twig', [
            'acteur' => $acteur,
            'form' => $form,
            'sections' => $sections,
            'section' => $section,
        ]);
    }

    #[Route('/{id}', name: 'app_acteur_show', methods: ['GET'])]
    public function show(Acteur $acteur, EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();
        $sections = $entityManager
            ->getRepository(Section::class)
            ->findBy(['clubid' => $this->getUser()->getClubid()]);
        $section = new Section();
        $this->user = $usr;
        return $this->render('acteur/show.html.twig', [
            'acteur' => $acteur,
            'sections' => $sections,
            'section' => $section,

        ]);
    }

    #[Route('/{id}/edit', name: 'app_acteur_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Acteur $acteur, EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();
        if(!isset($usr->getRoles()["app_administrateur_edit"]) && !isset($usr->getRoles()['ROLE_MASTER'])) {
            $this->user = $usr;
            return $this->redirectToRoute('app_club_show', ["id" => $usr->getClubid()->getId()], Response::HTTP_SEE_OTHER);
        }
        $postes = [];
        if(isset($usr->getRoles()['ROLE_MASTER'])){
            $postes = $entityManager
                ->getRepository(Poste::class)
                ->findAll();
        } else {
            $postes = $entityManager
                ->getRepository(Poste::class)
                ->findBy(['clubid' => $usr->getClubid()]);
        }
        $form = $this->createForm(ActeurType::class, $acteur,['postes' => $postes]);
        $form->handleRequest($request);
        $sections = $entityManager
            ->getRepository(Section::class)
            ->findBy(['clubid' => $this->getUser()->getClubid()]);

        if ($form->isSubmitted() && $form->isValid()) {
            $acteur->setUpdatedAt(new \DateTime());
            $entityManager->flush();

            $this->user = $usr;
        return $this->redirectToRoute('app_acteur_index', [], Response::HTTP_SEE_OTHER);
        }
        $section = new Section();
        $this->user = $usr;
        return $this->renderForm('acteur/edit.html.twig', [
            'acteur' => $acteur,
            'form' => $form,
            'sections' => $sections,
            'section' => $section,
        ]);
    }

    #[Route('/{id}', name: 'app_acteur_delete', methods: ['POST'])]
    public function delete(Request $request, Acteur $acteur, EntityManagerInterface $entityManager, UserRepository $userRepo): Response
    {
        $usr = $this->getUser();
        if(!isset($usr->getRoles()["app_acteur_delete"]) && !isset($usr->getRoles()['ROLE_MASTER'])) {
            $this->user = $usr;
            return $this->redirectToRoute('app_club_show', ["id" => $usr->getClubid()->getId()], Response::HTTP_SEE_OTHER);
        }
        if ($this->isCsrfTokenValid('delete'.$acteur->getId(), $request->request->get('_token'))) {
            $entityManager->remove($acteur);
            $userRepo->remove($acteur);
            $entityManager->flush();
        }

        $this->user = $usr;
        return $this->redirectToRoute('app_acteur_index', [], Response::HTTP_SEE_OTHER);
    }
}
