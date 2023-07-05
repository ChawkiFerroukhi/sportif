<?php

namespace App\Controller;

use App\Entity\Note;
use App\Entity\Teste;
use App\Entity\Section;
use App\Entity\Adherant;
use App\Form\NoteType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/note')]
class NoteController extends AbstractController
{
    #[Route('/adherant/{id}', name: 'app_note_index', methods: ['GET'])]
    public function index(Adherant $adherant,EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();
        if(!isset($usr->getRoles()['ROLE_ADMIN']) && !isset($usr->getRoles()['ROLE_MASTER']) && !isset($usr->getRoles()['ROLE_COACH']) && $usr->getId() != $adherant->getId() && $usr->getId() != $adherant->getSupervisorid()->getId() && $usr->getId() != $adherant->getSupervisor2id()->getId()) {
            $this->user = $usr;
            return $this->redirectToRoute('app_club_show', ["id" => $usr->getClubid()->getId()], Response::HTTP_SEE_OTHER);
        }
        $notes = $entityManager
            ->getRepository(Note::class)
            ->findBy(['adherantid' => $adherant->getId()]);
        $sections = $entityManager
            ->getRepository(Section::class)
            ->findBy(['clubid' => $this->getUser()->getClubid()]);
        $this->user = $usr;
        return $this->render('note/index.html.twig', [
            'notes' => $notes,
            'sections' => $sections,
            'section' => $adherant->getEquipeid()->getNiveauid()->getSectionid()
        ]);
    }

    #[Route('/{id}/new', name: 'app_note_new', methods: ['GET', 'POST'])]
    public function new(Teste $teste, Request $request, EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();

        if(!isset($usr->getRoles()['ROLE_ADMIN']) && !isset($usr->getRoles()['ROLE_MASTER']) && !isset($usr->getRoles()['ROLE_COACH'])) {
            $this->user = $usr;
            return $this->redirectToRoute('app_club_show', ["id" => $usr->getClubid()->getId()], Response::HTTP_SEE_OTHER);
        }
        $sections = $entityManager
            ->getRepository(Section::class)
            ->findBy(['clubid' => $this->getUser()->getClubid()]);
        $note = new Note();
        $form = $this->createForm(NoteType::class, $note,[
            'choices_adh' => $teste->getEquipeid()->getAdherants(),
            'choices_obj' => $teste->getCycleid()->getObjectifs()
        ]);        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $note->setCreatedAt(new \DateTime());
            $note->setUpdatedAt(new \DateTime());
            $note->setTesteid($teste);
            $note->setClubid($teste->getClubid());
            $entityManager->persist($note);
            $entityManager->flush();

            $this->user = $usr;
        return $this->redirectToRoute('app_note_show', ['id' => $note->getId()], Response::HTTP_SEE_OTHER);
        }

        $this->user = $usr;
        return $this->renderForm('note/new.html.twig', [
            'note' => $note,
            'form' => $form,
            'sections' => $sections,
            'section' => $teste->getEquipeid()->getNiveauid()->getSectionid()
        ]);
    }

    #[Route('/{id}', name: 'app_note_show', methods: ['GET'])]
    public function show(Note $note, EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();
        $adherant = $note->getAdherantid();
        if(!isset($usr->getRoles()['ROLE_ADMIN']) && !isset($usr->getRoles()['ROLE_MASTER']) && !isset($usr->getRoles()['ROLE_COACH']) && $usr->getId() != $adherant->getId() && $usr->getId() != $adherant->getSupervisorid()->getId() && $usr->getId() != $adherant->getSupervisor2id()->getId()) {
            $this->user = $usr;
            return $this->redirectToRoute('app_club_show', ["id" => $usr->getClubid()->getId()], Response::HTTP_SEE_OTHER);
        }
        $sections = $entityManager
            ->getRepository(Section::class)
            ->findBy(['clubid' => $this->getUser()->getClubid()]);
        $this->user = $usr;
        return $this->render('note/show.html.twig', [
            'note' => $note,
            'sections' => $sections,
            'section' => $note->getTesteid()->getEquipeid()->getNiveauid()->getSectionid()
        ]);
    }

    #[Route('/{id}/edit', name: 'app_note_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Note $note, EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();
        if(!isset($usr->getRoles()['ROLE_ADMIN']) && !isset($usr->getRoles()['ROLE_MASTER']) && !isset($usr->getRoles()['ROLE_COACH'])) {
            $this->user = $usr;
            return $this->redirectToRoute('app_club_show', ["id" => $usr->getClubid()->getId()], Response::HTTP_SEE_OTHER);
        }
        $sections = $entityManager
            ->getRepository(Section::class)
            ->findBy(['clubid' => $this->getUser()->getClubid()]);
        $form = $this->createForm(NoteType::class, $note,[
                'choices_adh' => $note->getTesteid()->getEquipeid()->getAdherants(),
                'choices_obj' => $note->getTesteid()->getCycleid()->getObjectifs()
            ]);        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->user = $usr;
        return $this->redirectToRoute('app_note_show', ['id' => $note->getId()], Response::HTTP_SEE_OTHER);
        }

        $this->user = $usr;
        return $this->renderForm('note/edit.html.twig', [
            'note' => $note,
            'form' => $form,
            'sections' => $sections,
            'section' => $note->getTesteid()->getEquipeid()->getNiveauid()->getSectionid()
        ]);
    }

    #[Route('/{id}', name: 'app_note_delete', methods: ['POST'])]
    public function delete(Request $request, Note $note, EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();
        if(!isset($usr->getRoles()['ROLE_ADMIN']) && !isset($usr->getRoles()['ROLE_MASTER']) && !isset($usr->getRoles()['ROLE_COACH'])) {
            $this->user = $usr;
            return $this->redirectToRoute('app_club_show', ["id" => $usr->getClubid()->getId()], Response::HTTP_SEE_OTHER);
        }
        if ($this->isCsrfTokenValid('delete'.$note->getId(), $request->request->get('_token'))) {
            $entityManager->remove($note);
            $entityManager->flush();
        }

        $this->user = $usr;
        return $this->redirectToRoute('app_note_index', [], Response::HTTP_SEE_OTHER);
    }
}
