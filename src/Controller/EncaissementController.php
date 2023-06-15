<?php

namespace App\Controller;

use App\Entity\Encaissement;
use App\Entity\Payment;
use App\Entity\Section;
use App\Entity\Niveau;
use App\Entity\Equipe;
use App\Entity\Adherant;
use App\Form\EncaissementType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/encaissement')]
class EncaissementController extends AbstractController
{
    #[Route('/', name: 'app_encaissement_index', methods: ['GET'])]
    public function index( EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();
        if(!isset($usr->getRoles()['ROLE_ADMIN']) && !isset($usr->getRoles()['ROLE_MASTER'])) {
            $this->user = $usr;
            return $this->redirectToRoute('app_club_show', ["id" => $usr->getClubid()->getId()], Response::HTTP_SEE_OTHER);
        }
        if(isset($usr->getRoles()['ROLE_MASTER'])) {
            $encaissements = $entityManager
            ->getRepository(Encaissement::class)
            ->findAll();
        } else {
            $encaissements = $entityManager
            ->getRepository(Encaissement::class)
            ->findBy(['clubid' => $this->getUser()->getClubid()]);
        }
        if(isset($_GET['from']) && isset($_GET['to']) && !empty($_GET['from']) && !empty($_GET['to'])) {
            $tmp = [];
            foreach($encaissements as $encaissement) {
                if($encaissement->getDate() >= new \DateTime($_GET['from']) && $encaissement->getDate() <= new \DateTime($_GET['to'])) {
                    $tmp[] = $encaissement;
                }
            }
            $encaissements = $tmp;
        }
        $sections = $entityManager
            ->getRepository(Section::class)
            ->findBy(['clubid' => $this->getUser()->getClubid()]);

        $pymnts = [];
        foreach($encaissements as $encaissement) {
            if(!isset($pymnts[$encaissement->getDate()->format('Y-m-d')])) {
                $pymnts[$encaissement->getDate()->format('Y-m-d')] = [];
            }
            $pymnts[$encaissement->getDate()->format('Y-m-d')][] = $encaissement;
        }
        $this->user = $usr;
        return $this->render('encaissement/index.html.twig', [
            'encaissements' => $encaissements,
            'pymnts' => $pymnts,
            'sections' => $sections,
            'section' => new Section(),
            'GET' => $_GET
        ]);
    }
    #[Route('/new', name: 'app_encaissement_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();
        $sections = $entityManager
            ->getRepository(Section::class)
            ->findBy(['clubid' => $this->getUser()->getClubid()]);
        $encaissement = new Encaissement();
        $form = $this->createForm(EncaissementType::class, $encaissement,[]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $encaissement->setCreatedAt(new \DateTime());
            $encaissement->setUpdatedAt(new \DateTime());
            $encaissement->setClubid($this->getUser()->getClubid());
            $entityManager->persist($encaissement);
            $entityManager->flush();
            $this->user = $usr;
            return $this->redirectToRoute('app_encaissement_show', ['id' => $encaissement->getId()], Response::HTTP_SEE_OTHER);
        }
        $this->user = $usr;
        return $this->renderForm('encaissement/new.html.twig', [
            'encaissement' => $encaissement,
            'form' => $form,
            'sections' => $sections,
            'section' => new Section()
        ]);
    }


    
    #[Route('/{id}', name: 'app_encaissement_show', methods: ['GET'])]
    public function show(Encaissement $encaissement, EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();
        $sections = $entityManager
            ->getRepository(Section::class)
            ->findBy(['clubid' => $this->getUser()->getClubid()]);
        $section = new Section();
        $this->user = $usr;
        return $this->render('encaissement/show.html.twig', [
            'encaissement' => $encaissement,
            'sections' => $sections,
            'section' => new Section()
        ]);
    }

    #[Route('/{id}/edit', name: 'app_encaissement_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Encaissement $encaissement, EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();
        $sections = $entityManager
            ->getRepository(Section::class)
            ->findBy(['clubid' => $this->getUser()->getClubid()]);
        $section = $encaissement->getAdherantid()->getEquipeid()->getNiveauid()->getSectionid();
        $adherants = [];
        $form = $this->createForm(EncaissementType::class, $encaissement,[]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $encaissement->setUpdatedAt(new \DateTime());
            $entityManager->persist($encaissement);
            $entityManager->flush();
            $this->user = $usr;
            return $this->redirectToRoute('app_encaissement_show', ['id' => $encaissement->getId()], Response::HTTP_SEE_OTHER);
        }
        $this->user = $usr;
        return $this->renderForm('encaissement/edit.html.twig', [
            'encaissement' => $encaissement,
            'form' => $form,
            'sections' => $sections,
            'section' => $section
        ]);
    }

    #[Route('/{id}', name: 'app_encaissement_delete', methods: ['POST'])]
    public function delete(Request $request, Encaissement $encaissement, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$encaissement->getId(), $request->request->get('_token'))) {
            $entityManager->remove($encaissement);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_encaissement_index', [], Response::HTTP_SEE_OTHER);
    }
}
