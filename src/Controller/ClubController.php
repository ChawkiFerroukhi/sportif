<?php

namespace App\Controller;

use App\Entity\Club;
use App\Entity\Teste;
use App\Entity\Presence;
use App\Entity\Seance;
use App\Entity\Section;
use App\Entity\Payment;
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
            return $this->redirectToRoute('app_home_access_denied', [], Response::HTTP_SEE_OTHER);
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
    #[Route('/stats', name: 'app_club_stats', methods: ['GET', 'POST'])]
    public function stats(EntityManagerInterface $entityManager, SeanceRepository $seanceRepo): Response
    {
        $usr = $this->getUser();

        if(!isset($usr->getRoles()['ROLE_MASTER']) && !isset($usr->getRoles()['app_club_stats'])) {
            $this->user = $usr;
            return $this->redirectToRoute('app_home_access_denied', ['id' => $usr->getClubid()->getId()], Response::HTTP_SEE_OTHER);
        }
        $sections = $entityManager
            ->getRepository(Section::class)
            ->findBy(['clubid' => $this->getUser()->getClubid()]);
        if(isset($_GET['section'])) {
            $section = $entityManager
                ->getRepository(Section::class)
                ->findOneBy(['id' => $_GET['section']]);
        } else if(isset($sections[0])){
            $section = $sections[0] ;
        } else {
            $section = new Section();
        }
        $seances = [];
        $dts = [];
        $niveaux = [];
        $dates = [];
        $F = [];
        $M = [];
        $IMC = [];
        $TESTES = [];
        $PRESENCE = [];
        $PAYMENT = [];
        $nbTESTES = [];
        $nbIMC = [];
        $nbSEANCES = [];
        $totalAdherants = 0;
        $totalTests = 0;
        $totalSeances = 0;
        $totalPresences = 0;
        $totalNotes = 0;
        $totalImc = 0;
        $totalRevenues = 0;
        foreach($section->getNiveaux() as $niveau) {
            $F[$niveau->getId()] = [];
            $M[$niveau->getId()] = [];
            $seances[$niveau->getId()] = [];
            $dts[$niveau->getId()] = [];
            $niveaux[$niveau->getId()] = $niveau;
            $testes[$niveau->getId()] = 0;
            $imc = 0;
            $nb = 0;
            $nbNotes = 0;
            $nts = 0;
            $nbPres = 0;
            $nbSeances = 0;
            $nbTestes = 0;
            $nbImc = 0;
            $totalPayment = 0;
            foreach($niveau->getEquipes() as $equipe) {
                $testes = $entityManager
                    ->getRepository(Teste::class)
                    ->findBy(['equipeid' => $equipe->getId()]);
                if(isset($_GET['from']) && isset($_GET['to']) && !empty($_GET['from']) && !empty($_GET['to'])) {
                    $tmp = [];
                    foreach($testes as $teste) {
                        if($teste->getDate() >= new \DateTime($_GET['from']) && $teste->getDate() <= new \DateTime($_GET['to'])) {
                            $tmp[] = $teste;
                        }
                    }
                    $testes = $tmp;
                }
                $nbTestes += count($testes);
                foreach($testes as $teste) {
                    $notes = $teste->getNotes();
                    foreach($notes as $note) {
                        $nbNotes++;
                        $nts += $note->getNote();
                    }
                }
                $pres = [];
                foreach($equipe->getAdherants() as $adherant) {
                    if(!isset($F[$niveau->getId()][$adherant->getId()]) && !isset($M[$niveau->getId()][$adherant->getId()])){
                        $nb++;
                        $imc += $adherant->getDossiermedicalid()->getMesure()->getImc();
                        $nbImc += count($adherant->getDossiermedicalid()->getMesures());
                        $presences = $entityManager
                            ->getRepository(Presence::class)
                            ->findBy(['adherantid' => $adherant->getId()]);
                        if(isset($_GET['from']) && isset($_GET['to']) && !empty($_GET['from']) && !empty($_GET['to'])) {
                            $tmp = [];
                            foreach($presences as $presence) {
                                if($presence->getDate() >= new \DateTime($_GET['from']) && $presence->getDate() <= new \DateTime($_GET['to'])) {
                                    $tmp[] = $presence;
                                }
                            }
                            $presences = $tmp;
                        }
                        foreach($presences as $presence) {
                            $day = $presence->getDate()->format( 'N' );
                            switch($day) {
                                case 1:
                                    $day = "Lundi";
                                    break;
                                case 2:
                                    $day = "Mardi";
                                    break;
                                case 3:
                                    $day = "Mercredi";
                                    break;
                                case 4:
                                    $day = "Jeudi";
                                    break;
                                case 5:
                                    $day = "Vendredi";
                                    break;
                                case 6:
                                    $day = "Samedi";
                                    break;
                                case 7:
                                    $day = "Dimanche";
                                    break;
                            }
                            $seance = $entityManager
                                ->getRepository(Seance::class)
                                ->findOneBy(['equipeid' => $adherant->getEquipeid(),'day' => $day]);
                            if($seance) {
                                $nbPres++;
                                array_push($pres,$presence);
                            }
                        }
                        
                        $payments = $entityManager
                            ->getRepository(Payment::class)
                            ->findBy(['userid' => $adherant->getId()]);
                        if(isset($_GET['from']) && isset($_GET['to']) && !empty($_GET['from']) && !empty($_GET['to'])) {
                            $tmp = [];
                            foreach($payments as $payment) {
                                if($payment->getDate() >= new \DateTime($_GET['from']) && $payment->getDate() <= new \DateTime($_GET['to'])) {
                                    $tmp[] = $payment;
                                }
                            }
                            $payments = $tmp;
                        }
                        foreach($payments as $payment) {
                            if($payment->getStatus() === "Payé") {
                                $totalPayment += $payment->getTotal();
                            }
                        }
                    }
                    if($adherant->getSexe() === 'F') {
                        $F[$niveau->getId()][$adherant->getId()] = $adherant;
                    } else {
                        $M[$niveau->getId()][$adherant->getId()] = $adherant;
                    }
                }
                $uniqueDates = [];
                foreach ($pres as $obj) {
                    $date = $obj->getDate()->format('Y-m-d');
                    // Check if date is already in uniqueDates array
                    if (!in_array($date, $uniqueDates)) {
                        $uniqueDates[] = $date;
                        $nbSeances++;
                        
                    }
                }
                
                $seances[$niveau->getId()] = array_merge($seances[$niveau->getId()],$uniqueDates);
            }
            echo '
                <script>
                    console.log("'.$niveau->getNom().'","'.$nbSeances.'","'.$nbPres.'");
                </script>';
            foreach($seances[$niveau->getId()] as $seance) {
                $date = new \DateTime($seance);
                
                $date = $date->format( 'Y-m' );
                if(isset($dts[$niveau->getId()][$date])) {
                    $dts[$niveau->getId()][$date] ++;
                } else {
                    $dts[$niveau->getId()][$date] = 1;
                    
                    $dates[] = $date;
                }
            }
            
            $IMC[$niveau->getId()] = $nb != 0 && $imc != 0 ? $imc / $nb : 'N/A';
            $TESTES[$niveau->getId()] = $nbNotes != 0 && $nts != 0 ? $nts/$nbNotes : 'N/A';
            $PRESENCE[$niveau->getId()] = $nb != 0 && $nbSeances !=0 ? $nbPres/($nbSeances*$nb) * 100 : 'N/A';
            $nbTESTES[$niveau->getId()] = $nbTestes;
            $nbIMC[$niveau->getId()] = $nbImc;
            $nbSEANCES[$niveau->getId()] = $nbSeances;
            $totalTests += $nbTestes;
            $totalAdherants += $nb;
            $totalSeances += $nbSeances;
            $totalPresences += $nbPres;
            $totalNotes += $nbNotes;
            $totalImc += $nbImc;
            $PAYMENT[$niveau->getId()] = $totalPayment;
            $totalRevenues += $totalPayment;
        }
        foreach($niveaux as $niveau) {
            foreach($dates as $date) {
                if(!isset($dts[$niveau->getId()][$date])) {
                    $dts[$niveau->getId()][$date] = 0;
                }
            }
        }
        $dates = array_unique($dates);
        
        $this->user = $usr;
        return $this->render('club/stats.html.twig', [
            'club' => $section->getClubid(),
            'sections' => $sections,
            'section' => $section,
            'dts' => $dts,
            'dates' => $dates,
            'niveaux' => $niveaux,
            'F' => $F,
            'M' => $M,
            'IMC' => $IMC,
            'TESTES' => $TESTES,
            'PRESENCE' => $PRESENCE,
            'testes' => $nbTESTES,
            'totalTests' => $totalTests,
            'totalAdherants' => $totalAdherants,
            'totalSeances' => $totalSeances,
            'totalPresences' => $totalPresences,
            'totalNotes' => $totalNotes,
            'totalImc' => $totalImc,
            'totalRevenues' => $totalRevenues,
            'seances' => $nbSEANCES,
            'payments' => $PAYMENT,
            'imc' => $nbIMC,
            'GET' => $_GET
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
        $nbTESTES = [];
        $nbIMC = [];
        $nbSEANCES = [];
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
            $nbTestes = 0;
            $nbImc = 0;
            foreach($sctn->getNiveaux() as $niveau) {
                foreach($niveau->getEquipes() as $equipe) {

                    $testes = $entityManager
                        ->getRepository(Teste::class)
                        ->findBy(['equipeid' => $equipe->getId()]);
                    $nbTestes += count($testes);
                    foreach($testes as $teste) {
                        $notes = $teste->getNotes();
                        foreach($notes as $note) {
                            $nbNotes++;
                            $nts += $note->getNote();
                        }
                    }
                    foreach($equipe->getAdherants() as $adherant) {
                        if(!isset($F[$sctn->getId()][$adherant->getId()]) && !isset($M[$sctn->getId()][$adherant->getId()])){
                            $nb++;
                            $imc += $adherant->getDossiermedicalid()->getMesure()->getImc();
                            $nbImc += count($adherant->getDossiermedicalid()->getMesures());
                            $presences = $entityManager
                                ->getRepository(Presence::class)
                                ->findBy(['adherantid' => $adherant->getId()]);
                            foreach($presences as $presence) {
                                $date = $presence->getDate()->format( 'Y-m-d' );
                                $dt = new \DateTime($date);
                                $seance = $entityManager
                                    ->getRepository(Seance::class)
                                    ->findOneBy(['equipeid' => $adherant->getEquipeid(),'date' => $dt]);
                                $seance2 = $entityManager
                                    ->getRepository(Seance::class)
                                    ->findOneBy(['equipeid' => $adherant->getEquipe2id(),'date' => $dt]);
                                if($seance || $seance2) {
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
            $IMC[$sctn->getId()] = $nb != 0 && $imc != 0 ? $imc / $nb : 'N/A';
            $TESTES[$sctn->getId()] = $nbNotes != 0 && $nts != 0 ? $nts/$nbNotes : 'N/A';
            $PRESENCE[$sctn->getId()] = $nb != 0 && $seances !=0 ? $nbPres/($seances*$nb) * 100 : 'N/A';
            $nbTESTES[$sctn->getId()] = $nbTestes;
            $nbIMC[$sctn->getId()] = $nbImc;
            $nbSEANCES[$sctn->getId()] = $seances;
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
            'PRESENCE' => $PRESENCE,
            'testes' => $nbTESTES,
            'seances' => $nbSEANCES,
            'imc' => $nbIMC

        ]);
    }

    #[Route('/{id}/edit', name: 'app_club_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Club $club, EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();
        if(!isset($usr->getRoles()['app_club_edit']) && !isset($usr->getRoles()['ROLE_MASTER']) ) {
            $this->user = $usr;
            return $this->redirectToRoute('app_home_access_denied', [], Response::HTTP_SEE_OTHER);
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
        if(!isset($usr->getRoles()['app_club_delete']) && !isset($usr->getRoles()['ROLE_MASTER']) ) {
            $this->user = $usr;
            return $this->redirectToRoute('app_home_access_denied', [], Response::HTTP_SEE_OTHER);
        }
        if ($this->isCsrfTokenValid('delete'.$club->getId(), $request->request->get('_token'))) {
            $entityManager->remove($club);
            $entityManager->flush();
        }
        $this->user = $usr;
        return $this->redirectToRoute('app_club_index', [], Response::HTTP_SEE_OTHER);
    }
}
