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
use Symfony\Contracts\HttpClient\HttpClientInterface;


#[Route('/payment')]
class PaymentController extends AbstractController
{
    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }
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
        if (isset($user->getRoles()['ROLE_ADHERANT']) && !isset($usr->getRoles()['ROLE_ADMIN'])) {
            if($usr->getId() != $user->getSupervisorid()->getId() ) {
                if($user->getSupervisor2id()!= null) {
                    if($user->getSupervisor2id()!=$usr->getId()) {
                        $this->user = $usr;
                        return $this->redirectToRoute('app_club_show', ["id" => $usr->getClubid()->getId()], Response::HTTP_SEE_OTHER);
                    }
                } else {
                    $this->user = $usr;
                }
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
            if($payment->getMode()=="En Ligne") {
                $response = $this->client->request('POST', 'https://api.konnect.network/api/v2/payments/init-payment', [
                    'headers' => [
                        'Accept' => 'application/json',
                        'x-api-key' => $_ENV['PAYMENT_API_KEY'],
                    ],
                    'body' => [
                            "receiverWalletId" => $_ENV['PAYMENT_RECEIVER_WALLET'],
                            "amount" => $payment->getTotal() * 1000,
                            "selectedPaymentMethod" => "gateway",
                            "firstName" => $usr->getNom(),
                            "lastName" => $usr->getPrenom(),
                            "phoneNumber" => $usr->getNumTel(),
                            "token" => "TND",
                            "orderId" => $payment->getId(),
                            "successUrl" => $_ENV['URL']."payment/success/".$payment->getId(),
                            "failUrl" => $_ENV['URL']."payment/fail/".$payment->getId()
                    ]
                ]);
                $content = json_decode($response->getContent(),true);
                print_r($content);
                $payment->setRef($content['paymentRef']);
                $payment->setStatus("En Cours");
                $entityManager->persist($payment);
                $entityManager->flush();
                $this->user = $usr;
                return $this->redirect($content['payUrl']);
            }
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

    #[Route('/success/{id}', name: 'app_payment_success', methods: ['GET', 'POST'])]
    public function success(Payment $payment, EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();
        $response = $this->client->request('GET', 'https://api.konnect.network/api/v1/payments/'.$payment->getRef(), [
            'headers' => [
                'Accept' => 'application/json',
            ]
        ]);
        $content = json_decode($response->getContent(),true);
        if($payment->getStatus() == "En Cours" && $content["status"] == "pending") {
            $payment->setStatus("Payé");
            $payment->setUpdatedat(new \DateTime("now"));
        } else  {
            if($content["status"] == "pending"){
                $payment->setStatus("En Cours");
            } else if($content["status"] == "failed") {
                $payment->setStatus("Refusé");
            }
        }
        $entityManager->persist($payment);
        $entityManager->flush();
        $this->user = $usr;
        return $this->redirectToRoute('app_payment_show', ['id' => $payment->getId()], Response::HTTP_SEE_OTHER);
    }
    #[Route('/fail/{id}', name: 'app_payment_fail', methods: ['GET', 'POST'])]
    public function fail(Payment $payment, EntityManagerInterface $entityManager): Response
    {
        return $this->redirectToRoute('app_payment_show', ['id' => $payment->getId(),'fail'=>true], Response::HTTP_SEE_OTHER);
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
            if($payment->getMode()=="En Ligne") {
                $response = $this->client->request('POST', 'https://api.preprod.konnect.network/api/v2/payments/init-payment', [
                    'headers' => [
                        'Accept' => 'application/json',
                        'x-api-key' => $_ENV['PAYMENT_API_KEY'],
                    ],
                    'body' => [
                            "receiverWalletId" => $_ENV['PAYMENT_RECEIVER_WALLET'],
                            "amount" => $payment->getTotal() * 1000,
                            "selectedPaymentMethod" => "gateway",
                            "firstName" => $user->getNom(),
                            "lastName" => $user->getPrenom(),
                            "phoneNumber" => $user->getNumTel(),
                            "token" => "TND",
                            "orderId" => $payment->getId(),
                            "successUrl" => $_ENV['URL']."payment/success/".$payment->getId(),
                            "failUrl" => $_ENV['URL']."payment/fail/".$payment->getId()
                    ]
                ]);
                $content = json_decode($response->getContent(),true);
                print_r($content);
                $payment->setRef($content['paymentRef']);
                $payment->setStatus("En Cours");
                $entityManager->persist($payment);
                $entityManager->flush();
                $this->user = $usr;
                return $this->redirect($content['payUrl']);
            }
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
            'section' => new Section(),
            'fail' => $_GET['fail'] ?? false
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
