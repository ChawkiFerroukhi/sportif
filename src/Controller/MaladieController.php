<?php

namespace App\Controller;

use App\Entity\Maladie;
use App\Entity\Cycle;
use App\Entity\Section;
use App\Form\MaladieType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/maladie')]
class MaladieController extends AbstractController
{
    #[Route('/', name: 'app_maladie_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();
        if(!isset($usr->getRoles()['ROLE_ADMIN'])) {
            $this->user = $usr;
            return $this->redirectToRoute('app_club_show', ["id" => $usr->getClubid()->getId()], Response::HTTP_SEE_OTHER);
        }
        if(isset($usr->getRoles()['ROLE_MASTER'])){
            $maladies = $entityManager
                ->getRepository(Maladie::class)
                ->findAll();
            } else {
            $maladies = $entityManager
                ->getRepository(Maladie::class)
                ->findBy(['clubid' => $this->getUser()->getClubid()]);
        }
        $sections = $entityManager
            ->getRepository(Section::class)
            ->findBy(['clubid' => $this->getUser()->getClubid()]);
        $this->user = $usr;
        return $this->render('maladie/index.html.twig', [
            'maladies' => $maladies,
            'sections' => $sections,
            'section' => new Section()
        ]);
    }

    #[Route('/new', name: 'app_maladie_new', methods: ['GET', 'POST'])]
    public function new(Request $request,EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();
        $maladie = new Maladie();
        $form = $this->createForm(MaladieType::class, $maladie);
        $form->handleRequest($request);
        $sections = $entityManager
            ->getRepository(Section::class)
            ->findBy(['clubid' => $this->getUser()->getClubid()]);

        if ($form->isSubmitted() && $form->isValid()) {
            $maladie->setCreatedAt(new \DateTime());
            $maladie->setUpdatedAt(new \DateTime());
            if(!empty($form->get('clubid')->getData())) {
                $maladie->setClubid($form->get('clubid')->getData());
            } else {
                $maladie->setClubid($usr->getClubid());
            }
            $entityManager->persist($maladie);
            $entityManager->flush();

            $this->user = $usr;
        return $this->redirectToRoute('app_maladie_show', ['id' => $maladie->getId()], Response::HTTP_SEE_OTHER);
        }

        $this->user = $usr;
        return $this->renderForm('maladie/new.html.twig', [
            'maladie' => $maladie,
            'form' => $form,
            'sections' => $sections,
            'section' => new Section()
        ]);
    }

    #[Route('/{id}', name: 'app_maladie_show', methods: ['GET'])]
    public function show(Maladie $maladie,EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();
        $this->user = $usr;
        $sections = $entityManager
            ->getRepository(Section::class)
            ->findBy(['clubid' => $this->getUser()->getClubid()]);
        return $this->render('maladie/show.html.twig', [
            'maladie' => $maladie,
            'sections' => $sections,
            'section' => new Section()
        ]);
    }

    #[Route('/{id}/edit', name: 'app_maladie_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Maladie $maladie, EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();
        $form = $this->createForm(MaladieType::class, $maladie);
        $form->handleRequest($request);
        $sections = $entityManager
            ->getRepository(Section::class)
            ->findBy(['clubid' => $this->getUser()->getClubid()]);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->user = $usr;
            return $this->redirectToRoute('app_maladie_show', ['id' => $maladie->getId()], Response::HTTP_SEE_OTHER);
        }

        $this->user = $usr;
        return $this->renderForm('maladie/edit.html.twig', [
            'maladie' => $maladie,
            'form' => $form,
            'sections' => $sections,
            'section' => new Section()
        ]);
    }

    #[Route('/{id}', name: 'app_maladie_delete', methods: ['POST'])]
    public function delete(Request $request, Maladie $maladie, EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();
        if ($this->isCsrfTokenValid('delete'.$maladie->getId(), $request->request->get('_token'))) {
            $entityManager->remove($maladie);
            $entityManager->flush();
        }

        $this->user = $usr;
        return $this->redirectToRoute('app_cycle_show', ['id' => $maladie->getCycleid()->getId()], Response::HTTP_SEE_OTHER);
    }
}
