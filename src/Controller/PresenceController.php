<?php

namespace App\Controller;

use App\Entity\Presence;
use App\Entity\Equipe;
use App\Entity\Adherant;
use App\Entity\Section;
use App\Form\PresenceType;
use App\Repository\PresenceRepository;
use App\Repository\SeanceRepository;
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
    public function indexAdherant(Adherant $adherant,EntityManagerInterface $entityManager, PresenceRepository $presenceRepo, SeanceRepository $seanceRepo): Response
    {
        $usr = $this->getUser();
        $user = $adherant;
        if (isset($user->getRoles()['ROLE_ADHERANT']) ) {
            if($user->getId() != $usr->getId() && $usr->getId() != $user->getSupervisorid()->getId()) {
                if($user->getSupervisor2id()!= null) {
                    if($user->getSupervisor2id()!=$usr->getId()) {
                        $this->user = $usr;
                        return $this->redirectToRoute('app_home_access_denied', [], Response::HTTP_SEE_OTHER);
                    }
                } else {
                    $this->user = $usr;
                    return $this->redirectToRoute('app_home_access_denied', [], Response::HTTP_SEE_OTHER);
                }
            }
        } else if(!isset($usr->getRoles()['ROLE_MASTER']) && !isset($usr->getRoles()['app_presence_index']) && $usr->getId() != $user->getId()) {
            $this->user = $usr;
            return $this->redirectToRoute('app_home_access_denied', [], Response::HTTP_SEE_OTHER);
        }        
        $presences = $presenceRepo->getOrdered($adherant->getId());
        if(isset($_GET['from']) && isset($_GET['to']) && !empty($_GET['from']) && !empty($_GET['to'])) {
            $tmp = [];
            foreach($presences as $presence) {
                if($presence->getDate() >= new \DateTime($_GET['from']) && $presence->getDate() <= new \DateTime($_GET['to'])) {
                    $tmp[] = $presence;
                }
            }
            $presences = $tmp;
        }
        $dates=[];
        foreach($presences as $presence) {
            $date = date('Y-m',$presence->getDate()->getTimestamp());
            if(isset($dates[$date])) {
                $dates[$date] ++;
            } else {
                $dates[$date] = 1;
            }
        }
        $seances = $seanceRepo->getOrdered($adherant->getEquipeid()->getId());
        $seances2 = [];
        if($adherant->getEquipe2id()!=null) {
            $seances2 = $seanceRepo->getOrdered($adherant->getEquipe2id()->getId());
        }
        $dts=[];
        foreach($seances as $presence) {
            $date = date('Y-m',$presence->getDate()->getTimestamp());
            if(isset($dts[$date])) {
                $dts[$date] ++;
            } else {
                $dts[$date] = 1;
            }
        }
        foreach($seances2 as $presence) {
            $date = date('Y-m',$presence->getDate()->getTimestamp());
            if(isset($dts[$date])) {
                $dts[$date] ++;
            } else {
                $dts[$date] = 1;
            }
        }
        $sections = $entityManager
            ->getRepository(Section::class)
            ->findBy(['clubid' => $this->getUser()->getClubid()]);
        $this->user = $usr;
        return $this->render('presence/index.html.twig', [
            'presences' => $presences,
            'dates' => $dates,
            'dts' => $dts,
            'GET' => $_GET,
            'adherant' => $adherant,
            'sections' => $sections,
            'section' => $adherant->getEquipeid()->getNiveauid()->getSectionid()
        ]);
    }
    #[Route('/', name: 'app_presence_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();
        if(!isset($usr->getRoles()['ROLE_MASTER']) && !isset($usr->getRoles()['app_presence_index']) ) {
            $this->user = $usr;
            return $this->redirectToRoute('app_home_access_denied', [], Response::HTTP_SEE_OTHER);
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
        if(!isset($usr->getRoles()['ROLE_MASTER']) && !isset($usr->getRoles()['app_presence_new']) ) {
            $this->user = $usr;
            return $this->redirectToRoute('app_home_access_denied', [], Response::HTTP_SEE_OTHER);
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

        if(!isset($usr->getRoles()['ROLE_MASTER']) && !isset($usr->getRoles()['app_presence_show']) && $usr->getId() != $adherant->getId() && $usr->getId() != $adherant->getSupervisorid()->getId() && $usr->getId() != $adherant->getSupervisor2id()->getId()) {
            $this->user = $usr;
            return $this->redirectToRoute('app_home_access_denied', [], Response::HTTP_SEE_OTHER);
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
        if(!isset($usr->getRoles()['ROLE_MASTER']) && !isset($usr->getRoles()['app_presence_edit']) ) {
            $this->user = $usr;
            return $this->redirectToRoute('app_home_access_denied', [], Response::HTTP_SEE_OTHER);
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
        if(!isset($usr->getRoles()['ROLE_MASTER']) && !isset($usr->getRoles()['app_presence_delete']) ) {
            $this->user = $usr;
            return $this->redirectToRoute('app_home_access_denied', [], Response::HTTP_SEE_OTHER);
        }
        if ($this->isCsrfTokenValid('delete'.$presence->getId(), $request->request->get('_token'))) {
            $entityManager->remove($presence);
            $entityManager->flush();
        }

        $this->user = $usr;
        return $this->redirectToRoute('app_presence_adherant_index', ['id'=>$presence->getAdherantid()->getId()], Response::HTTP_SEE_OTHER);
    }

    
}
