<?php

namespace App\Controller;

use App\Entity\Presence;
use App\Entity\Equipe;
use App\Entity\Adherant;
use App\Entity\Section;
use App\Form\PresenceType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
date_default_timezone_set("Africa/Tunis");

#[Route('/presence')]
class PresenceController extends AbstractController
{
    #[Route('/adherant/{id}', name: 'app_presence_adherant_index', methods: ['GET'])]
    public function indexAdherant(Adherant $adherant,EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();
        if(!isset($usr->getRoles()['ROLE_ADMIN']) && !isset($usr->getRoles()['ROLE_MASTER']) && !isset($usr->getRoles()['ROLE_COACH']) && $usr->getId() != $adherant->getId() && $usr->getId() != $adherant->getSupervisorid()->getId() && $usr->getId() != $adherant->getSupervisor2id()->getId()) {
            $this->user = $usr;
            return $this->redirectToRoute('app_club_show', ["id" => $usr->getClubid()->getId()], Response::HTTP_SEE_OTHER);
        }        
        $presences = $entityManager
            ->getRepository(Presence::class)
            ->findBy(['adherantid' => $adherant->getId()]);
        $sections = $entityManager
            ->getRepository(Section::class)
            ->findBy(['clubid' => $this->getUser()->getClubid()]);
        $this->user = $usr;
        return $this->render('presence/index.html.twig', [
            'presences' => $presences,
            'sections' => $sections,
            'section' => $adherant->getEquipeid()->getNiveauid()->getSectionid()
        ]);
    }
    #[Route('/', name: 'app_presence_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();
        if(!isset($usr->getRoles()['ROLE_ADMIN']) && !isset($usr->getRoles()['ROLE_MASTER']) && !isset($usr->getRoles()['ROLE_COACH']) ) {
            $this->user = $usr;
            return $this->redirectToRoute('app_club_show', ["id" => $usr->getClubid()->getId()], Response::HTTP_SEE_OTHER);
        }
        if(isset($usr->getRoles()['ROLE_MASTER'])) {
            $presences = $entityManager
                ->getRepository(Presence::class)
                ->findAll();
        } else {
            $presences = $entityManager
                ->getRepository(Presence::class)
                ->findBy(['clubid' => $usr->getClubid()]);
        }
        $sections = $entityManager
            ->getRepository(Section::class)
            ->findBy(['clubid' => $this->getUser()->getClubid()]);
        $this->user = $usr;
        return $this->render('presence/index.html.twig', [
            'presences' => $presences,
            'sections' => $sections,
            'section' => new Section()
        ]);
    }

    #[Route('/api/new', name: 'api_presence_new', methods: ['GET', 'POST'])]
    public function newAPI(Request $request, EntityManagerInterface $entityManager): Response
    {
        $ref = $request->query->get('ref');
        $adherant = $entityManager
            ->getRepository(Adherant::class)
            ->findOneBy(['ref' => $ref]);
        $date = new \DateTime();
        $presence = new Presence();
        $presence->setCreatedAt($date);
        $presence->setDate($date);
        $presence->setUpdatedAt($date);
        $presence->setClubid($adherant->getClubid());
        $presence->setAdherantid($adherant);
        $entityManager->persist($presence);
        $entityManager->flush();
        $response = new Response(json_encode(array(
            'success' => true,
            'date' => $date,
        )));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    #[Route('/{id}/new', name: 'app_presence_new', methods: ['GET', 'POST'])]
    public function new(Equipe $equipe,Request $request, EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();

        if(!isset($usr->getRoles()['ROLE_ADMIN']) && !isset($usr->getRoles()['ROLE_MASTER']) && !isset($usr->getRoles()['ROLE_COACH']) ) {
            $this->user = $usr;
            return $this->redirectToRoute('app_club_show', ["id" => $usr->getClubid()->getId()], Response::HTTP_SEE_OTHER);
        }
        $sections = $entityManager
            ->getRepository(Section::class)
            ->findBy(['clubid' => $this->getUser()->getClubid()]);
        $adherants = $equipe->getAdherants();
        $presence = new Presence();
        $form = $this->createForm(PresenceType::class, $presence,[
            'choices' => $adherants
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $presence->setCreatedAt(new \DateTime());
            $presence->setUpdatedAt(new \DateTime());
            $presence->setClubid($presence->getAdherantid()->getClubid());
            $entityManager->persist($presence);
            $entityManager->flush();

            $this->user = $usr;
        return $this->redirectToRoute('app_presence_show', ['id' => $presence->getId()], Response::HTTP_SEE_OTHER);
        }

        $this->user = $usr;
        return $this->renderForm('presence/new.html.twig', [
            'presence' => $presence,
            'form' => $form,
            'sections' => $sections,
            'section' => $equipe->getNiveauid()->getSectionid()
        ]);
    }

    #[Route('/{id}', name: 'app_presence_show', methods: ['GET'])]
    public function show(Presence $presence, EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();

        if(!isset($usr->getRoles()['ROLE_ADMIN']) && !isset($usr->getRoles()['ROLE_MASTER']) && !isset($usr->getRoles()['ROLE_COACH']) && $usr->getId() != $adherant->getId() && $usr->getId() != $adherant->getSupervisorid()->getId() && $usr->getId() != $adherant->getSupervisor2id()->getId()) {
            $this->user = $usr;
            return $this->redirectToRoute('app_club_show', ["id" => $usr->getClubid()->getId()], Response::HTTP_SEE_OTHER);
        } 
        $sections = $entityManager
            ->getRepository(Section::class)
            ->findBy(['clubid' => $this->getUser()->getClubid()]);
        $this->user = $usr;
        return $this->render('presence/show.html.twig', [
            'presence' => $presence,
            'sections' => $sections,
            'section' => $presence->getAdherantid()->getEquipeid()->getNiveauid()->getSectionid()
        ]);
    }

    #[Route('/{id}/edit', name: 'app_presence_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Presence $presence, EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();
        if(!isset($usr->getRoles()['ROLE_ADMIN']) && !isset($usr->getRoles()['ROLE_MASTER']) && !isset($usr->getRoles()['ROLE_COACH']) ) {
            $this->user = $usr;
            return $this->redirectToRoute('app_club_show', ["id" => $usr->getClubid()->getId()], Response::HTTP_SEE_OTHER);
        }
        $sections = $entityManager
            ->getRepository(Section::class)
            ->findBy(['clubid' => $this->getUser()->getClubid()]);
        $adherants = $entityManager
            ->getRepository(Adherant::class)
            ->findBy(['clubid' => $presence->getClubid()]);
        $form = $this->createForm(PresenceType::class, $presence,[
            'choices' => $adherants
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->user = $usr;
        return $this->redirectToRoute('app_presence_show', ['id' => $presence->getId()], Response::HTTP_SEE_OTHER);
        }

        $this->user = $usr;
        return $this->renderForm('presence/edit.html.twig', [
            'presence' => $presence,
            'form' => $form,
            'sections' => $sections,
            'section' => $presence->getAdherantid()->getEquipeid()->getNiveauid()->getSectionid()

        ]);
    }

    #[Route('/{id}', name: 'app_presence_delete', methods: ['POST'])]
    public function delete(Request $request, Presence $presence, EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();
        if(!isset($usr->getRoles()['ROLE_ADMIN']) && !isset($usr->getRoles()['ROLE_MASTER']) && !isset($usr->getRoles()['ROLE_COACH']) ) {
            $this->user = $usr;
            return $this->redirectToRoute('app_club_show', ["id" => $usr->getClubid()->getId()], Response::HTTP_SEE_OTHER);
        }
        if ($this->isCsrfTokenValid('delete'.$presence->getId(), $request->request->get('_token'))) {
            $entityManager->remove($presence);
            $entityManager->flush();
        }

        $this->user = $usr;
        return $this->redirectToRoute('app_presence_adherant_index', ['id'=>$presence->getAdherantid()->getId()], Response::HTTP_SEE_OTHER);
    }

    
}
