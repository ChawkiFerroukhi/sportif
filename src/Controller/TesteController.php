<?php

namespace App\Controller;

use App\Entity\Teste;
use App\Entity\Section;
use App\Entity\Equipe;
use App\Entity\Cycle;
use App\Form\TesteType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/teste')]
class TesteController extends AbstractController
{
    #[Route('/equipe/{id}', name: 'app_teste_index', methods: ['GET'])]
    public function index(Equipe $equipe,EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();
        $testes = $entityManager
            ->getRepository(Teste::class)
            ->findBy(['equipeid' => $equipe->getId()]);
        $sections = $entityManager
            ->getRepository(Section::class)
            ->findBy(['clubid' => $this->getUser()->getClubid()]);
        $this->user = $usr;
        return $this->render('teste/index.html.twig', [
            'testes' => $testes,
            'sections' => $sections,
            'section' => $equipe->getNiveauid()->getSectionid()
        ]);
    }

    #[Route('/{id}/new', name: 'app_teste_new', methods: ['GET', 'POST'])]
    public function new(Cycle $cycle,Request $request, EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();
        if(!isset($usr->getRoles()['ROLE_MASTER']) && !isset($usr->getRoles()['app_teste_new']) ) {
            $this->user = $usr;
            return $this->redirectToRoute('app_home_access_denied', [], Response::HTTP_SEE_OTHER);
        }
        $teste = new Teste();
        $form = $this->createForm(TesteType::class, $teste,[
            'choices' => $cycle->getNiveauid()->getEquipes()
        ]);
        $form->handleRequest($request);
        $sections = $entityManager
            ->getRepository(Section::class)
            ->findBy(['clubid' => $this->getUser()->getClubid()]);
        if ($form->isSubmitted() && $form->isValid()) {
            $teste->setCreatedAt(new \DateTime());
            $teste->setUpdatedAt(new \DateTime());
            $teste->setCycleid($cycle);
            $teste->setClubid($cycle->getClubid());
            $entityManager->persist($teste);
            $entityManager->flush();
            $this->user = $usr;
            return $this->redirectToRoute('app_teste_show', ['id' => $teste->getId()], Response::HTTP_SEE_OTHER);
        }

        $this->user = $usr;
        return $this->renderForm('teste/new.html.twig', [
            'teste' => $teste,
            'form' => $form,
            'sections' => $sections,
            'section' => $cycle->getNiveauid()->getSectionid()
        ]);
    }

    #[Route('/{id}', name: 'app_teste_show', methods: ['GET'])]
    public function show(Teste $teste,EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();
        $sections = $entityManager
            ->getRepository(Section::class)
            ->findBy(['clubid' => $this->getUser()->getClubid()]);
        $this->user = $usr;
        return $this->render('teste/show.html.twig', [
            'teste' => $teste,
            'sections' => $sections,
            'section' => $teste->getEquipeid()->getNiveauid()->getSectionid()
        ]);
    }

    #[Route('/{id}/edit', name: 'app_teste_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Teste $teste, EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();
        if(!isset($usr->getRoles()['ROLE_MASTER']) && !isset($usr->getRoles()['app_teste_edit']) ) {
            $this->user = $usr;
            return $this->redirectToRoute('app_home_access_denied', [], Response::HTTP_SEE_OTHER);
        }
        $form = $this->createForm(TesteType::class, $teste,[
            'choices' => $teste->getCycleid()->getNiveauid()->getEquipes()
        ]);
        $form->handleRequest($request);
        $sections = $entityManager
            ->getRepository(Section::class)
            ->findBy(['clubid' => $this->getUser()->getClubid()]);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->user = $usr;
            return $this->redirectToRoute('app_teste_show', ['id' => $teste->getId()], Response::HTTP_SEE_OTHER);
        }

        $this->user = $usr;
        return $this->renderForm('teste/edit.html.twig', [
            'teste' => $teste,
            'form' => $form,
            'sections' => $sections,
            'section' => $teste->getEquipeid()->getNiveauid()->getSectionid()
        ]);
    }

    #[Route('/{id}/edit/status', name: 'app_teste_edit_status', methods: ['GET', 'POST'])]
    public function editStatus(Request $request, Teste $teste, EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();
        if(!isset($usr->getRoles()['ROLE_MASTER']) && !isset($usr->getRoles()['app_teste_edit']) ) {
            $this->user = $usr;
            return $this->redirectToRoute('app_home_access_denied', [], Response::HTTP_SEE_OTHER);
        }
        $status = $teste->getStatus() == 'effectué' ? 'non-effectué' : 'effectué';
        $teste->setStatus($status);
        $entityManager->persist($teste, true);
        $entityManager->flush();
        $this->user = $usr;
        return $this->redirectToRoute('app_teste_index', ['id' => $teste->getEquipeid()->getId()], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}', name: 'app_teste_delete', methods: ['POST'])]
    public function delete(Request $request, Teste $teste, EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();
        if(!isset($usr->getRoles()['ROLE_MASTER']) && !isset($usr->getRoles()['app_teste_delete']) ) {
            $this->user = $usr;
            return $this->redirectToRoute('app_home_access_denied', [], Response::HTTP_SEE_OTHER);
        }
        if ($this->isCsrfTokenValid('delete'.$teste->getId(), $request->request->get('_token'))) {
            $entityManager->remove($teste);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_teste_index', [], Response::HTTP_SEE_OTHER);
    }
}
