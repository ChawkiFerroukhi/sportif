<?php

namespace App\Controller;

use App\Entity\Payment;
use App\Entity\Section;
use App\Entity\Niveau;
use App\Entity\Equipe;
use App\Entity\User;
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
        if(isset($_GET['from']) && isset($_GET['to']) && !empty($_GET['from']) && !empty($_GET['to'])) {
            $tmp = [];
            foreach($payments as $payment) {
                if($payment->getDate() >= new \DateTime($_GET['from']) && $payment->getDate() <= new \DateTime($_GET['to'])) {
                    $tmp[] = $payment;
                }
            }
            $payments = $tmp;
        }
        $sections = $entityManager
            ->getRepository(Section::class)
            ->findBy(['clubid' => $this->getUser()->getClubid()]);

        $pymnts = [];
        foreach($payments as $payment) {
            if(!isset($pymnts[$payment->getDate()->format('Y-m-d')])) {
                $pymnts[$payment->getDate()->format('Y-m-d')] = [];
            }
            $pymnts[$payment->getDate()->format('Y-m-d')][] = $payment;
        }
        $this->user = $usr;
        return $this->render('payment/index.html.twig', [
            'payments' => $payments,
            'pymnts' => $pymnts,
            'sections' => $sections,
            'section' => new Section(),
            'GET' => $_GET
        ]);
    }
    #[Route('/user/{id}', name: 'app_payment_index_user', methods: ['GET'])]
    public function indexUser(User $user, EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();
        if (isset($user->getRoles()['ROLE_ADHERANT'])) {
            if($usr->getId() != $user->getSupervisorid()->getId() && $usr->getId() != $user->getSupervisor2id()->getId() ) {
                $this->user = $usr;
            return $this->redirectToRoute('app_club_show', ["id" => $usr->getClubid()->getId()], Response::HTTP_SEE_OTHER);
            }
        } else if(!isset($usr->getRoles()['ROLE_ADMIN']) && !isset($usr->getRoles()['ROLE_MASTER']) && $usr->getId() != $user->getId()) {
            $this->user = $usr;
            return $this->redirectToRoute('app_club_show', ["id" => $usr->getClubid()->getId()], Response::HTTP_SEE_OTHER);
        }
        $payments = $entityManager
            ->getRepository(Payment::class)
            ->findBy(['userid' => $user->getId()]);
        
        if(isset($_GET['from']) && isset($_GET['to']) && !empty($_GET['from']) && !empty($_GET['to'])) {
            $tmp = [];
            foreach($payments as $payment) {
                if($payment->getDate() >= new \DateTime($_GET['from']) && $payment->getDate() <= new \DateTime($_GET['to'])) {
                    $tmp[] = $payment;
                }
            }
            $payments = $tmp;
        }
        $sections = $entityManager
            ->getRepository(Section::class)
            ->findBy(['clubid' => $this->getUser()->getClubid()]);
        
        $pymnts = [];
        foreach($payments as $payment) {
            if(!isset($pymnts[$payment->getDate()->format('Y-m-d')])) {
                $pymnts[$payment->getDate()->format('Y-m-d')] = [];
            }
            $pymnts[$payment->getDate()->format('Y-m-d')][] = $payment;
        }
        $this->user = $usr;
        return $this->render('payment/index.html.twig', [
            'payments' => $payments,
            'pymnts' => $pymnts,
            'sections' => $sections,
            'section' => new Section(),
            'user' => $user,
            'GET' => $_GET
        ]);
    }

    #[Route('/new', name: 'app_payment_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();
        $sections = $entityManager
            ->getRepository(Section::class)
            ->findBy(['clubid' => $this->getUser()->getClubid()]);
        $users = [];
        if(isset($usr->getRoles()["ROLE_MASTER"])) {
            $users = $entityManager
                ->getRepository(User::class)
                ->findAll();
        } else {
            $users = $entityManager
            ->getRepository(User::class)
            ->findBy(['clubid' => $usr->getClubid()]);
        }
        $payment = new Payment();
        $form = $this->createForm(PaymentType::class, $payment,[
            'users' => $users
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $payment->setCreatedAt(new \DateTime());
            $payment->setUpdatedAt(new \DateTime());
            $payment->setClubid($payment->getUserid()->getClubid());
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


    #[Route('/user/{id}/new', name: 'app_payment_new_user', methods: ['GET', 'POST'])]
    public function newAdh(User $user,Request $request, EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();
        $sections = $entityManager
            ->getRepository(Section::class)
            ->findBy(['clubid' => $this->getUser()->getClubid()]);
        $users = [];
        if(isset($usr->getRoles()["ROLE_MASTER"])) {
            $users = $entityManager
                ->getRepository(User::class)
                ->findAll();
        } else {
            $users = $entityManager
            ->getRepository(User::class)
            ->findBy(['clubid' => $usr->getClubid()]);
        }
        $payment = new Payment();
        $payment->setUserid($user);
        $form = $this->createForm(PaymentType::class, $payment,[
            'users' => $users
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $payment->setCreatedAt(new \DateTime());
            $payment->setUpdatedAt(new \DateTime());
            $payment->setClubid($payment->getUserid()->getClubid());
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
        $section = new Section();
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
        $section = $payment->getUserid()->getEquipeid()->getNiveauid()->getSectionid();
        $users = [];
        if(isset($usr->getRoles()["ROLE_MASTER"])) {
            $users = $entityManager
                ->getRepository(User::class)
                ->findAll();
        } else {
            $users = $entityManager
            ->getRepository(User::class)
            ->findBy(['clubid' => $usr->getClubid()]);
        }
        $form = $this->createForm(PaymentType::class, $payment,[
            'users' => $users
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
