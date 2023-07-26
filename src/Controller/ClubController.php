<?php

namespace App\Controller;

use App\Entity\Club;
use App\Entity\Teste;
use App\Entity\Presence;
use App\Entity\Seance;
use App\Entity\Section;
use App\Form\ClubType;
use App\Repository\SeanceRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/club')]
class ClubController extends AbstractController
{
    #[Route('/', name: 'app_club_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();
        if(!isset($usr->getRoles()['ROLE_MASTER'])) {
            $this->user = $usr;
            return $this->redirectToRoute('app_club_show', ['id' => $usr->getClubid()->getId()], Response::HTTP_SEE_OTHER);
        }
        $clubs = $entityManager
            ->getRepository(Club::class)
            ->findAll();
        $sections = $entityManager
            ->getRepository(Section::class)
            ->findBy(['clubid' => $this->getUser()->getClubid()]);
        $section = new Section();
        $this->user = $usr;
        return $this->render('club/index.html.twig', [
            'clubs' => $clubs,
            'sections' => $sections,
            'section' => $section
        ]);
    }

    #[Route('/new', name: 'app_club_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();
        if(!isset($usr->getRoles()['ROLE_MASTER']) ) {
            $this->user = $usr;
            return $this->redirectToRoute('app_club_index', [], Response::HTTP_SEE_OTHER);
        }
        $section = new Section();
        $club = new Club();
        $form = $this->createForm(ClubType::class, $club);
        $form->handleRequest($request);
        $sections = $entityManager
            ->getRepository(Section::class)
            ->findBy(['clubid' => $this->getUser()->getClubid()]);
        if ($form->isSubmitted() && $form->isValid()) {
            $club->setCreatedAt(new \DateTime());
            $club->setUpdatedAt(new \DateTime());
            $entityManager->persist($club);
            $entityManager->flush();
            $this->user = $usr;
            return $this->redirectToRoute('app_club_show', ['id' => $club->getId()], Response::HTTP_SEE_OTHER);
        }
        $this->user = $usr;
        return $this->renderForm('club/new.html.twig', [
            'club' => $club,
            'form' => $form,
            'sections' => $sections,
            'section' => $section
        ]);
    }

    #[Route('/{id}', name: 'app_club_show', methods: ['GET'])]
    public function show(Club $club, EntityManagerInterface $entityManager, SeanceRepository $seanceRepo): Response
    {
        $usr = $this->getUser();
        $sections = $entityManager
            ->getRepository(Section::class)
            ->findBy(['clubid' => $this->getUser()->getClubid()]);
        $section = new Section();
        $F = [];
        $M = [];
        $IMC = [];
        $TESTES = [];
        $PRESENCE = [];
        foreach($sections as $sctn) {
            $F[$sctn->getId()] = [];
            $M[$sctn->getId()] = [];
            $imc = 0;
            $nb = 0;
            $nb = 0;
            $nbNotes = 0;
            $nts = 0;
            $nbPres = 0;
            $seances = 0;
            foreach($sctn->getNiveaux() as $niveau) {
                foreach($niveau->getEquipes() as $equipe) {

                    $testes = $entityManager
                        ->getRepository(Teste::class)
                        ->findBy(['equipeid' => $equipe->getId()]);
                    foreach($testes as $teste) {
                        $notes = $teste->getNotes();
                        foreach($notes as $note) {
                            $nbNotes++;
                            $nts += $note->getNote();
                        }
                    }
                    foreach($equipe->getAdherants() as $adherant) {
                        if(!isset($F[$sctn->getId()][$adherant->getId()]) && !isset($F[$sctn->getId()][$adherant->getId()])){
                            $nb++;
                            $imc += $adherant->getDossiermedicalid()->getMesure()->getImc();
                            $presences = $entityManager
                                ->getRepository(Presence::class)
                                ->findBy(['adherantid' => $adherant->getId()]);
                            foreach($presences as $presence) {
                                $date = $presence->getDate()->format( 'Y-m-d' );
                                $dt = new \DateTime($date);
                                $seance = $entityManager
                                    ->getRepository(Seance::class)
                                    ->findOneBy(['equipeid' => $adherant->getEquipeid(),'date' => $dt]);
                                if($seance) {
                                    $nbPres++;
                                }
                            }
                        }
                        if($adherant->getSexe() === 'F') {
                            $F[$sctn->getId()][$adherant->getId()] = $adherant;
                        } else {
                            $M[$sctn->getId()][$adherant->getId()] = $adherant;
                        }
                    }
                    $seances += count($seanceRepo->getByOld($equipe->getId()));
                    
                }
            }
            $IMC[$sctn->getId()] = $nb != 0 ? $imc / $nb : 'N/A';
            $TESTES[$sctn->getId()] = $nbNotes != 0 ? $nts/$nbNotes : 'N/A';
            $PRESENCE[$sctn->getId()] = $nb != 0 && $seances !=0 ? $nbPres/($seances*$nb) * 100 : 'N/A';
        }
        $this->user = $usr;
        return $this->render('club/show.html.twig', [
            'club' => $club,
            'sections' => $sections,
            'section' => $section,
            'F' => $F,
            'M' => $M,
            'IMC' => $IMC,
            'TESTES' => $TESTES,
            'PRESENCE' => $PRESENCE

        ]);
    }

    #[Route('/{id}/edit', name: 'app_club_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Club $club, EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();
        if(!isset($usr->getRoles()['ROLE_MASTER']) && !(isset($usr->getRoles()['ROLE_ADMIN']) && $usr->getClubid()->getId() === $club->getId()) ) {
            $this->user = $usr;
            return $this->redirectToRoute('app_club_index', [], Response::HTTP_SEE_OTHER);
        }
        $section = new Section();
        $form = $this->createForm(ClubType::class, $club);
        $form->handleRequest($request);
        $sections = $entityManager
            ->getRepository(Section::class)
            ->findBy(['clubid' => $this->getUser()->getClubid()]);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->user = $usr;
            return $this->redirectToRoute('app_club_show', ['id' => $club->getId()], Response::HTTP_SEE_OTHER);
        }
        $this->user = $usr;
        return $this->renderForm('club/edit.html.twig', [
            'club' => $club,
            'form' => $form,
            'sections' => $sections,
            'section' => $section
        ]);
    }

    #[Route('/{id}', name: 'app_club_delete', methods: ['POST'])]
    public function delete(Request $request, Club $club, EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();
        if ($this->isCsrfTokenValid('delete'.$club->getId(), $request->request->get('_token'))) {
            $entityManager->remove($club);
            $entityManager->flush();
        }
        $this->user = $usr;
        return $this->redirectToRoute('app_club_index', [], Response::HTTP_SEE_OTHER);
    }
}
