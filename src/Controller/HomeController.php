<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Section;
use App\Entity\User;
use App\Entity\Administrateur;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Newsletter;
use App\Form\NewsletterType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

#[Route('/')]
class HomeController extends AbstractController
{    
    #[Route('/', name: 'app_index', methods: ['GET'])]
    public function index(): Response
    {
        
        return $this->redirectToRoute('app_club_index', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/newsletter', name: 'app_newsletter_index', methods: ['POST','GET'])]
    public function newsletter(Request $request, EntityManagerInterface $entityManager, MailerInterface $mailer): Response
    {
        $newsletter = new Newsletter();
        $sections = $entityManager
            ->getRepository(Section::class)
            ->findBy(['clubid' => $this->getUser()->getClubid()]);
        $form = $this->createForm(NewsletterType::class, $newsletter);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $emails = [];
            $users = $entityManager
                ->getRepository(User::class)
                ->findBy(['clubid' => $this->getUser()->getClubid()]);

            
            foreach ($users as $user) {
                $emails[] = $user->getEmail();
            }
            
            $email = (new TemplatedEmail())
            ->from(new Address('w311940@gmail.com', 'IGMS'))
            ->to(...$emails)
            ->subject($newsletter->getSubject())
            ->html($newsletter->getBody());
            
            try {
                $mailer->send($email);
            } catch (TransportExceptionInterface $e) {
                
            }
            return $this->redirectToRoute('app_newsletter_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('dashboard/newsletter.html.twig', [
            'newsletter' => $newsletter,
            'form' => $form,
            'sections' => $sections,
            'section' => new Section(),
        ]);
    }
    #[Route('/newsletter/master', name: 'app_newsletter_master', methods: ['POST','GET'])]
    public function newsletter_master(Request $request, EntityManagerInterface $entityManager, MailerInterface $mailer): Response
    {
        $newsletter = new Newsletter();
        $sections = $entityManager
            ->getRepository(Section::class)
            ->findBy(['clubid' => $this->getUser()->getClubid()]);
        $form = $this->createForm(NewsletterType::class, $newsletter);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $emails = [];
            $administrateurs = $entityManager
                ->getRepository(Administrateur::class)
                ->findAll();
            foreach ($administrateurs as $administrateur) {
                $emails[] = $administrateur->getEmail();
            }
            
            $email = (new TemplatedEmail())
            ->from(new Address('w311940@gmail.com', 'IGMS MASTER'))
            ->to(...$emails)
            ->subject($newsletter->getSubject())
            ->html($newsletter->getBody());
            
            try {
                $mailer->send($email);
            } catch (TransportExceptionInterface $e) {
                
            }
            return $this->redirectToRoute('app_newsletter_master', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('dashboard/newsletter.html.twig', [
            'newsletter' => $newsletter,
            'form' => $form,
            'sections' => $sections,
            'section' => new Section(),
        ]);
    }
}
