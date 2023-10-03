<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Section;
use App\Entity\User;
use App\Entity\Administrateur;
use App\Entity\Coach;
use App\Entity\Supervisor;
use App\Entity\Adherant;
use App\Entity\Niveau;
use App\Entity\Equipe;
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
    #[Route('/access-denied', name: 'app_home_access_denied', methods: ['GET'])]
    public function accessDenied(): Response
    {
        return $this->renderForm('security/access_denied.html.twig', [
        ]);    
    }
    #[Route('/limit-reached', name: 'app_home_limit_reached', methods: ['GET'])]
    public function limitReached(): Response
    {
        return $this->renderForm('security/limit_reached.html.twig', [
        ]);    
    }
    #[Route('/newsletter/{id}', name: 'app_newsletter_index', methods: ['POST','GET'])]
    public function newsletter(Section $section,Request $request, EntityManagerInterface $entityManager, MailerInterface $mailer): Response
    {
        $usr = $this->getUser();
        if(!isset($usr->getRoles()['ROLE_MASTER']) && !isset($usr->getRoles()['app_newsletter_index'])) {
            $this->user = $usr;
            return $this->redirectToRoute('app_home_access_denied', [], Response::HTTP_SEE_OTHER);
        }
        $newsletter = new Newsletter();
        $sections = $entityManager
            ->getRepository(Section::class)
            ->findBy(['clubid' => $this->getUser()->getClubid()]);
        $form = $this->createForm(NewsletterType::class, $newsletter);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $emails = [];
            $users = [];
            $niveaux = $entityManager
                ->getRepository(Niveau::class)
                ->findBy(['sectionid' => $section->getId()]);
            foreach ($niveaux as $niveau) {
                $equipes = $entityManager
                    ->getRepository(Equipe::class)
                    ->findBy(['niveauid' => $niveau->getId()]);
                foreach($equipes as $equipe) {
                    foreach($equipe->getAdherants() as $adherant) {
                        $users[] = $adherant->getEmail();
                        $users[] = $adherant->getSupervisorId()->getEmail();
                        if($adherant->getSupervisor2Id() != null) {
                            $users[] = $adherant->getSupervisor2Id()->getEmail();
                        }
                    }
                    $users[] = $equipe->getCoachid()->getEmail();
                    $users[] = $equipe->getDoctorid()->getEmail();
                }
            }
            
            $admins = $entityManager
                ->getRepository(Administrateur::class)
                ->findBy(['clubid' => $this->getUser()->getClubid()]);
            foreach ($admins as $admin) {
                $users[] = $admin->getEmail();
            }
            $users = array_unique($users);
            
            $email = (new TemplatedEmail())
            ->from(new Address('w311940@gmail.com', 'IGMS'))
            ->to(...$users)
            ->subject($newsletter->getSubject())
            ->html($newsletter->getBody());
            
            try {
                $mailer->send($email);
            } catch (TransportExceptionInterface $e) {
                
            }
            return $this->redirectToRoute('app_newsletter_index', ['id'=>$section->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('dashboard/newsletter.html.twig', [
            'newsletter' => $newsletter,
            'form' => $form,
            'sections' => $sections,
            'section' => $section,
        ]);
    }
    #[Route('/newsletter/master', name: 'app_newsletter_master', methods: ['POST','GET'])]
    public function newsletter_master(Request $request, EntityManagerInterface $entityManager, MailerInterface $mailer): Response
    {
        $usr = $this->getUser();
        if(!isset($usr->getRoles()['ROLE_MASTER'])) {
            $this->user = $usr;
            return $this->redirectToRoute('app_home_access_denied', [], Response::HTTP_SEE_OTHER);
        }
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
