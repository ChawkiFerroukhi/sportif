<?php

namespace App\Controller;

use App\Entity\Payment;
use App\Entity\Section;
use App\Entity\Niveau;
use App\Entity\Equipe;
use App\Entity\Adherant;
use App\Form\PaymentType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/payment')]
class PaymentController extends AbstractController
{
    #[Route('/', name: 'app_payment_index', methods: ['GET'])]
    public function index( EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();
        if(!isset($usr->getRoles()['ROLE_ADMIN']) && !isset($usr->getRoles()['ROLE_MASTER'])) {
            $this->user = $usr;
            return $this->redirectToRoute('app_club_show', ["id" => $usr->getClubid()->getId()], Response::HTTP_SEE_OTHER);
        }
        if(isset($usr->getRoles()['ROLE_MASTER'])) {
            $payments = $entityManager
            ->getRepository(Payment::class)
            ->findAll();
        } else {
            $payments = $entityManager
            ->getRepository(Payment::class)
            ->findBy(['clubid' => $this->getUser()->getClubid()]);
        }
        
        $sections = $entityManager
            ->getRepository(Section::class)
            ->findBy(['clubid' => $this->getUser()->getClubid()]);
        $this->user = $usr;
        return $this->render('payment/index.html.twig', [
            'payments' => $payments,
            'sections' => $sections,
            'section' => new Section()
        ]);
    }
    #[Route('/adherant/{id}', name: 'app_payment_index_adherant', methods: ['GET'])]
    public function indexAdherant(Adherant $adherant, EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();
        if(!isset($usr->getRoles()['ROLE_ADMIN']) && !isset($usr->getRoles()['ROLE_MASTER'])) {
            $this->user = $usr;
            return $this->redirectToRoute('app_club_show', ["id" => $usr->getClubid()->getId()], Response::HTTP_SEE_OTHER);
        }
        $payments = $entityManager
            ->getRepository(Payment::class)
            ->findBy(['adherantid' => $adherant->getId()]);
        
        $sections = $entityManager
            ->getRepository(Section::class)
            ->findBy(['clubid' => $this->getUser()->getClubid()]);
        $this->user = $usr;
        return $this->render('payment/index.html.twig', [
            'payments' => $payments,
            'sections' => $sections,
            'section' => new Section()
        ]);
    }

    #[Route('/new', name: 'app_payment_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();
        $sections = $entityManager
            ->getRepository(Section::class)
            ->findBy(['clubid' => $this->getUser()->getClubid()]);
        $adherants = [];
        if(isset($usr->getRoles()["ROLE_MASTER"])) {
            $adherants = $entityManager
                ->getRepository(Adherant::class)
                ->findAll();
        } else {
            $adherants = $entityManager
            ->getRepository(Adherant::class)
            ->findBy(['clubid' => $usr->getClubid()]);
        }
        $payment = new Payment();
        $form = $this->createForm(PaymentType::class, $payment,[
            'adherants' => $adherants
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $payment->setCreatedAt(new \DateTime());
            $payment->setUpdatedAt(new \DateTime());
            $payment->setClubid($payment->getAdherantid()->getClubid());
            $entityManager->persist($payment);
            $entityManager->flush();
            $this->user = $usr;
            return $this->redirectToRoute('app_payment_show', ['id' => $payment->getId()], Response::HTTP_SEE_OTHER);
        }
        $this->user = $usr;
        return $this->renderForm('payment/new.html.twig', [
            'payment' => $payment,
            'form' => $form,
            'sections' => $sections,
            'section' => new Section()
        ]);
    }

    #[Route('/{id}', name: 'app_payment_show', methods: ['GET'])]
    public function show(Payment $payment, EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();
        $sections = $entityManager
            ->getRepository(Section::class)
            ->findBy(['clubid' => $this->getUser()->getClubid()]);
        $section = $payment->getAdherantid()->getEquipeid()->getNiveauid()->getSectionid();
        $this->user = $usr;
        return $this->render('payment/show.html.twig', [
            'payment' => $payment,
            'sections' => $sections,
            'section' => new Section()
        ]);
    }

    #[Route('/{id}/edit', name: 'app_payment_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Payment $payment, EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();
        $sections = $entityManager
            ->getRepository(Section::class)
            ->findBy(['clubid' => $this->getUser()->getClubid()]);
        $section = $payment->getAdherantid()->getEquipeid()->getNiveauid()->getSectionid();
        $adherants = [];
        if(isset($usr->getRoles()["ROLE_MASTER"])) {
            $adherants = $entityManager
                ->getRepository(Adherant::class)
                ->findAll();
        } else {
            $adherants = $entityManager
            ->getRepository(Adherant::class)
            ->findBy(['clubid' => $usr->getClubid()]);
        }
        $form = $this->createForm(PaymentType::class, $payment,[
            'adherants' => $adherants
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $payment->setUpdatedAt(new \DateTime());
            $entityManager->persist($payment);
            $entityManager->flush();
            $this->user = $usr;
            return $this->redirectToRoute('app_payment_show', ['id' => $payment->getId()], Response::HTTP_SEE_OTHER);
        }
        $this->user = $usr;
        return $this->renderForm('payment/edit.html.twig', [
            'payment' => $payment,
            'form' => $form,
            'sections' => $sections,
            'section' => $section
        ]);
    }

    #[Route('/{id}', name: 'app_payment_delete', methods: ['POST'])]
    public function delete(Request $request, Payment $payment, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$payment->getId(), $request->request->get('_token'))) {
            $entityManager->remove($payment);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_payment_index', [], Response::HTTP_SEE_OTHER);
    }
}
