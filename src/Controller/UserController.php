<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Section;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


#[Route('/user')]
class UserController extends AbstractController
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }
    #[Route('/', name: 'app_user_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();
        if(!isset($usr->getRoles()['ROLE_MASTER']) && !isset($usr->getRoles()['ROLE_ADMIN']) ) {
            $this->user = $usr;
            return $this->redirectToRoute('app_club_index', [], Response::HTTP_SEE_OTHER);
        }
        $users = [];
        if(isset($usr->getRoles()['ROLE_MASTER'])) {
            $users = $entityManager
                ->getRepository(User::class)
                ->findAll();
        } else {
            $users = $entityManager
                ->getRepository(User::class)
                ->findBy(['clubid' => $this->getUser()->getClubid()]);
        }
        $sections = $entityManager
            ->getRepository(Section::class)
            ->findBy(['clubid' => $this->getUser()->getClubid()]);
        $this->user = $usr;
        return $this->render('user/index.html.twig', [
            'users' => $users,
            'sections' => $sections,
        ]);
    }

    #[Route('/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, UserRepository $userRepository): Response
    {
        $usr = $this->getUser();
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        
        $sections = $entityManager
        ->getRepository(Section::class)
        ->findAll();

        if ($form->isSubmitted() && $form->isValid()) {

            $user->setRoles(['ROLE_USER']);

            $user->setPassword($this->passwordHasher->hashPassword(
                $user,
                $form->get('password')->getData()
            ));

            $userRepository->add($user, true);
            if(!empty($this->user)) {

                $this->user = $usr;
                return $this->redirectToRoute('app_user_show', ['id' => $user->getId()], Response::HTTP_SEE_OTHER);
            } else {
                return $this->redirectToRoute('login', [], Response::HTTP_SEE_OTHER);
            }
        }

        $this->user = $usr;
        return $this->renderForm('user/new.html.twig', [
            'user' => $user,
            'form' => $form,
            'sections' => $sections,
        ]);
    }

    #[Route('/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        $usr = $this->getUser();
        if(!isset($usr->getRoles()['ROLE_MASTER']) ) {
            $this->user = $usr;
            return $this->redirectToRoute('app_club_index', [], Response::HTTP_SEE_OTHER);
        }
        $this->user = $usr;
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();
        if(!isset($usr->getRoles()['ROLE_MASTER']) ) {
            $this->user = $usr;
            return $this->redirectToRoute('app_club_index', [], Response::HTTP_SEE_OTHER);
        }
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        $sections = $entityManager
            ->getRepository(Section::class)
            ->findBy(['clubid' => $this->getUser()->getClubid()]);
        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword($this->passwordHasher->hashPassword(
                $user,
                $form->get('password')->getData()
            ));
            $entityManager->flush();

            $this->user = $usr;
        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        $this->user = $usr;
        return $this->renderForm('user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
            'sections' => $sections
        ]);
    }

    #[Route('/{id}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();

        if(!isset($usr->getRoles()['ROLE_MASTER']) ) {
            $this->user = $usr;
            return $this->redirectToRoute('app_club_index', [], Response::HTTP_SEE_OTHER);
        }
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        $this->user = $usr;
        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }
}
