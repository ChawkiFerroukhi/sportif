<?php

namespace App\Controller;

use App\Entity\Decaissement;
use App\Entity\Payment;
use App\Entity\Section;
use App\Entity\Niveau;
use App\Entity\Equipe;
use App\Entity\Adherant;
use App\Form\DecaissementType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/decaissement')]
class DecaissementController extends AbstractController
{
    #[Route('/', name: 'app_decaissement_index', methods: ['GET'])]
    public function index( EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();
        if(!isset($usr->getRoles()['ROLE_ADMIN']) && !isset($usr->getRoles()['ROLE_MASTER'])) {
            $this->user = $usr;
            return $this->redirectToRoute('app_club_show', ["id" => $usr->getClubid()->getId()], Response::HTTP_SEE_OTHER);
        }
        if(isset($usr->getRoles()['ROLE_MASTER'])) {
            $decaissements = $entityManager
            ->getRepository(Decaissement::class)
            ->findAll();
        } else {
            $decaissements = $entityManager
            ->getRepository(Decaissement::class)
            ->findBy(['clubid' => $this->getUser()->getClubid()]);
        }
        if(isset($_GET['from']) && isset($_GET['to']) && !empty($_GET['from']) && !empty($_GET['to'])) {
            $tmp = [];
            foreach($decaissements as $decaissement) {
                if($decaissement->getDate() >= new \DateTime($_GET['from']) && $decaissement->getDate() <= new \DateTime($_GET['to'])) {
                    $tmp[] = $decaissement;
                }
            }
            $decaissements = $tmp;
        }
        $sections = $entityManager
            ->getRepository(Section::class)
            ->findBy(['clubid' => $this->getUser()->getClubid()]);

        $pymnts = [];
        foreach($decaissements as $decaissement) {
            if(!isset($pymnts[$decaissement->getDate()->format('Y-m-d')])) {
                $pymnts[$decaissement->getDate()->format('Y-m-d')] = [];
            }
            $pymnts[$decaissement->getDate()->format('Y-m-d')][] = $decaissement;
        }
        $this->user = $usr;
        return $this->render('decaissement/index.html.twig', [
            'decaissements' => $decaissements,
            'pymnts' => $pymnts,
            'sections' => $sections,
            'section' => new Section(),
            'GET' => $_GET
        ]);
    }
    #[Route('/new', name: 'app_decaissement_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();
        $sections = $entityManager
            ->getRepository(Section::class)
            ->findBy(['clubid' => $this->getUser()->getClubid()]);
        $decaissement = new Decaissement();
        $form = $this->createForm(DecaissementType::class, $decaissement,[]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $decaissement->setCreatedAt(new \DateTime());
            $decaissement->setUpdatedAt(new \DateTime());
            $decaissement->setClubid($this->getUser()->getClubid());
            $entityManager->persist($decaissement);
            $entityManager->flush();
            $this->user = $usr;
            return $this->redirectToRoute('app_decaissement_show', ['id' => $decaissement->getId()], Response::HTTP_SEE_OTHER);
        }
        $this->user = $usr;
        return $this->renderForm('decaissement/new.html.twig', [
            'decaissement' => $decaissement,
            'form' => $form,
            'sections' => $sections,
            'section' => new Section()
        ]);
    }


    
    #[Route('/{id}', name: 'app_decaissement_show', methods: ['GET'])]
    public function show(Decaissement $decaissement, EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();
        $sections = $entityManager
            ->getRepository(Section::class)
            ->findBy(['clubid' => $this->getUser()->getClubid()]);
        $section = new Section();
        $this->user = $usr;
        return $this->render('decaissement/show.html.twig', [
            'decaissement' => $decaissement,
            'sections' => $sections,
            'section' => new Section()
        ]);
    }

    #[Route('/{id}/edit', name: 'app_decaissement_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Decaissement $decaissement, EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();
        $sections = $entityManager
            ->getRepository(Section::class)
            ->findBy(['clubid' => $this->getUser()->getClubid()]);
        $section = new Section();
        $adherants = [];
        $form = $this->createForm(DecaissementType::class, $decaissement,[]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $decaissement->setUpdatedAt(new \DateTime());
            $entityManager->persist($decaissement);
            $entityManager->flush();
            $this->user = $usr;
            return $this->redirectToRoute('app_decaissement_show', ['id' => $decaissement->getId()], Response::HTTP_SEE_OTHER);
        }
        $this->user = $usr;
        return $this->renderForm('decaissement/edit.html.twig', [
            'decaissement' => $decaissement,
            'form' => $form,
            'sections' => $sections,
            'section' => $section
        ]);
    }

    #[Route('/{id}', name: 'app_decaissement_delete', methods: ['POST'])]
    public function delete(Request $request, Decaissement $decaissement, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$decaissement->getId(), $request->request->get('_token'))) {
            $entityManager->remove($decaissement);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_decaissement_index', [], Response::HTTP_SEE_OTHER);
    }
}
