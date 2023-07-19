<?php

namespace App\Controller;

use App\Entity\Equipe;
use App\Entity\Niveau;
use App\Entity\Coach;
use App\Entity\Doctor;
use App\Entity\Section;
use App\Entity\Seance;
use App\Entity\Presence;
use App\Entity\Teste;
use App\Form\EquipeType;
use App\Repository\SeanceRepository;
use App\Repository\TesteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Route('/niveau')]
class EquipeController extends AbstractController
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }
    #[Route('/', name: 'app_equipe_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();
        if(!isset($usr->getRoles()['ROLE_MASTER'])) {
            $this->user = $usr;
            return $this->redirectToRoute('app_club_show', ["id" => $usr->getClubid()->getId()], Response::HTTP_SEE_OTHER);
        }
        $equipes = $entityManager
            ->getRepository(Equipe::class)
            ->findAll();
        $sections = $entityManager
            ->getRepository(Section::class)
            ->findBy(['clubid' => $this->getUser()->getClubid()]);
        $section = new Section();
        $this->user = $usr;
        return $this->render('equipe/index.html.twig', [
            'equipes' => $equipes,
            'sections' => $sections,
            'section' => $section,
        ]);
    }

    #[Route('/{id}/new', name: 'app_equipe_new', methods: ['GET', 'POST'])]
    public function new(Niveau $niveau,Request $request, EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();
        $equipe = new Equipe();
        $doctors = $entityManager
            ->getRepository(Doctor::class)
            ->findBy(['clubid' => $this->getUser()->getClubid()]);
        $coaches = $entityManager
            ->getRepository(Coach::class)
            ->findBy(['clubid' => $this->getUser()->getClubid()]);
        $form = $this->createForm(EquipeType::class, $equipe,[
            'doctors' => $doctors,
            'coachs' => $coaches,
        ]);
        $form->handleRequest($request);
        $sections = $entityManager
            ->getRepository(Section::class)
            ->findBy(['clubid' => $this->getUser()->getClubid()]);

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
                $coach->setEmail($form->get('coach_Email')->getData());
                $coach->setPassword($this->passwordHasher->hashPassword(
                    $coach,
                    $form->get('coach_password')->getData()
                ));
                $coach->setRef($form->get('coach_ref')->getData());
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
                $doctor->setEmail($form->get('doctor_Email')->getData());
                $doctor->setPassword($this->passwordHasher->hashPassword(
                    $doctor,
                    $form->get('doctor_password')->getData()
                ));
                $doctor->setRef($form->get('doctor_ref')->getData());
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

            $this->user = $usr;
        return $this->redirectToRoute('app_equipe_show', ['id'=>$equipe->getId()], Response::HTTP_SEE_OTHER);
        }

        $this->user = $usr;
        return $this->renderForm('equipe/new.html.twig', [
            'equipe' => $equipe,
            'form' => $form,
            'sections' => $sections,
            'section' => $niveau->getSectionid(),
        ]);
    }

    #[Route('/{id}', name: 'app_equipe_show', methods: ['GET'])]
    public function show(Equipe $equipe,EntityManagerInterface $entityManager, SeanceRepository $seanceRepo, TesteRepository $testeRepo): Response
    {
        $usr = $this->getUser();
        $sections = $entityManager
            ->getRepository(Section::class)
            ->findBy(['clubid' => $this->getUser()->getClubid()]);
        $testes = $entityManager
            ->getRepository(Teste::class)
            ->findBy(['equipeid' => $equipe->getId()]);
        $nbNotes = 0;
        $nts = 0;
        foreach($testes as $teste) {
            $notes = $teste->getNotes();
            foreach($notes as $note) {
                $nbNotes++;
                $nts += $note->getNote();
            }
        }
        if($nbNotes != 0){
            $nts = $nts/$nbNotes;
        } else {
            $nts = "N/A";
        }
        $adherants = $equipe->getAdherants();
        $nbPres = 0;
        foreach($adherants as $adherant) {
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
        $seances = $seanceRepo->getByOld($equipe->getId());
        
        if(count($adherants) != 0 && count($seances) != 0){
            $prs = $nbPres/(count($seances)*count($adherants));
            $prs *= 100;
        } else {
            $prs = "N/A";
        }
        
        $seances = $seanceRepo->getByNew($equipe->getId());
        $testes = $testeRepo->getByNew($equipe->getId());
        $this->user = $usr;
        return $this->render('equipe/show.html.twig', [
            'equipe' => $equipe,
            'prs' => $prs,
            'nts' => $nts,
            'seances' => count($seances),
            'testes' => count($testes),
            'sections' => $sections,
            'section' => $equipe->getNiveauid()->getSectionid(),
        ]);
    }

    #[Route('/{id}/edit', name: 'app_equipe_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Equipe $equipe, EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();
        $doctors = $entityManager
            ->getRepository(Doctor::class)
            ->findBy(['clubid' => $this->getUser()->getClubid()]);
        $coaches = $entityManager
            ->getRepository(Coach::class)
            ->findBy(['clubid' => $this->getUser()->getClubid()]);
        $form = $this->createForm(EquipeType::class, $equipe,[
            'doctors' => $doctors,
            'coachs' => $coaches,
        ]);
        $form->handleRequest($request);
        $sections = $entityManager
            ->getRepository(Section::class)
            ->findBy(['clubid' => $this->getUser()->getClubid()]);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->user = $usr;
            return $this->redirectToRoute('app_equipe_show', ['id' => $equipe->getId()], Response::HTTP_SEE_OTHER);
        }

        $this->user = $usr;
        return $this->renderForm('equipe/edit.html.twig', [
            'equipe' => $equipe,
            'form' => $form,
            'sections' => $sections,
            'section' => $equipe->getNiveauid()->getSectionid(),
        ]);
    }

    #[Route('/{id}', name: 'app_equipe_delete', methods: ['POST','GET'])]
    public function delete(Request $request, Equipe $equipe, EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();
        if ($this->isCsrfTokenValid('delete'.$equipe->getId(), $request->request->get('_token'))) {
            $entityManager->remove($equipe);
            $entityManager->flush();
        }

        $this->user = $usr;
        return $this->redirectToRoute('app_equipe_index', [], Response::HTTP_SEE_OTHER);
    }
}
