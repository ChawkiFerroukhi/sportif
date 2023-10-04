<?php

namespace App\Controller;

use App\Entity\Income;
use App\Entity\Section;
use App\Entity\Niveau;
use App\Entity\Equipe;
use App\Entity\User;
use App\Form\IncomeType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/income')]
class IncomeController extends AbstractController
{
    #[Route('/', name: 'app_income_index', methods: ['GET'])]
    public function index( EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();
        if(!isset($usr->getRoles()['ROLE_MASTER']) && !isset($usr->getRoles()['app_income_index'])) {
            $this->user = $usr;
            return $this->redirectToRoute('app_home_access_denied', [], Response::HTTP_SEE_OTHER);
        }
        if(isset($usr->getRoles()['ROLE_MASTER'])) {
            $incomes = $entityManager
            ->getRepository(Income::class)
            ->findAll();
        } else {
            $incomes = $entityManager
            ->getRepository(Income::class)
            ->findBy(['clubid' => $this->getUser()->getClubid()]);
        }
        if(isset($_GET['from']) && isset($_GET['to']) && !empty($_GET['from']) && !empty($_GET['to'])) {
            $tmp = [];
            foreach($incomes as $income) {
                if($income->getDate() >= new \DateTime($_GET['from']) && $income->getDate() <= new \DateTime($_GET['to'])) {
                    $tmp[] = $income;
                }
            }
            $incomes = $tmp;
        }
        $sections = $entityManager
            ->getRepository(Section::class)
            ->findBy(['clubid' => $this->getUser()->getClubid()]);

        $pymnts = [];
        foreach($incomes as $income) {
            if(!isset($pymnts[$income->getDate()->format('Y-m-d')])) {
                $pymnts[$income->getDate()->format('Y-m-d')] = [];
            }
            $pymnts[$income->getDate()->format('Y-m-d')][] = $income;
        }
        $this->user = $usr;
        return $this->render('income/index.html.twig', [
            'incomes' => $incomes,
            'pymnts' => $pymnts,
            'sections' => $sections,
            'section' => new Section(),
            'GET' => $_GET
        ]);
    }
    #[Route('/user/{id}', name: 'app_income_index_user', methods: ['GET'])]
    public function indexUser(User $user, EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();
        if (isset($user->getRoles()['ROLE_ADHERANT']) ) {
            if($user->getId() != $usr->getId() && $usr->getId() != $user->getSupervisorid()->getId()) {
                if($user->getSupervisor2id()!= null) {
                    if($user->getSupervisor2id()!=$usr->getId()) {
                        $this->user = $usr;
                        return $this->redirectToRoute('app_home_access_denied', [], Response::HTTP_SEE_OTHER);
                    }
                } else {
                    $this->user = $usr;
                    return $this->redirectToRoute('app_home_access_denied', [], Response::HTTP_SEE_OTHER);
                }
            }
        } else if(!isset($usr->getRoles()['ROLE_MASTER']) && !isset($usr->getRoles()['app_income_index']) && $usr->getId() != $user->getId()) {
            $this->user = $usr;
            return $this->redirectToRoute('app_home_access_denied', [], Response::HTTP_SEE_OTHER);
        }
        $incomes = $entityManager
            ->getRepository(Income::class)
            ->findBy(['userid' => $user->getId()]);
        
        if(isset($_GET['from']) && isset($_GET['to']) && !empty($_GET['from']) && !empty($_GET['to'])) {
            $tmp = [];
            foreach($incomes as $income) {
                if($income->getDate() >= new \DateTime($_GET['from']) && $income->getDate() <= new \DateTime($_GET['to'])) {
                    $tmp[] = $income;
                }
            }
            $incomes = $tmp;
        }
        $sections = $entityManager
            ->getRepository(Section::class)
            ->findBy(['clubid' => $this->getUser()->getClubid()]);
        
        $pymnts = [];
        foreach($incomes as $income) {
            if(!isset($pymnts[$income->getDate()->format('Y-m-d')])) {
                $pymnts[$income->getDate()->format('Y-m-d')] = [];
            }
            $pymnts[$income->getDate()->format('Y-m-d')][] = $income;
        }
        $this->user = $usr;
        return $this->render('income/index.html.twig', [
            'incomes' => $incomes,
            'pymnts' => $pymnts,
            'sections' => $sections,
            'section' => new Section(),
            'user' => $user,
            'GET' => $_GET
        ]);
    }

    #[Route('/new', name: 'app_income_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();
        if(!isset($usr->getRoles()['ROLE_MASTER']) && !isset($usr->getRoles()['app_income_new'])) {
            $this->user = $usr;
            return $this->redirectToRoute('app_home_access_denied', [], Response::HTTP_SEE_OTHER);
        }
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
        $income = new Income();
        $form = $this->createForm(IncomeType::class, $income,[
            'users' => $users
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $income->setCreatedAt(new \DateTime());
            $income->setUpdatedAt(new \DateTime());
            $income->setClubid($income->getUserid()->getClubid());
            $entityManager->persist($income);
            $entityManager->flush();
            $this->user = $usr;
            return $this->redirectToRoute('app_income_show', ['id' => $income->getId()], Response::HTTP_SEE_OTHER);
        }
        $this->user = $usr;
        return $this->renderForm('income/new.html.twig', [
            'income' => $income,
            'form' => $form,
            'sections' => $sections,
            'section' => new Section()
        ]);
    }


    #[Route('/user/{id}/new', name: 'app_income_new_user', methods: ['GET', 'POST'])]
    public function newAdh(User $user,Request $request, EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();
        if(!isset($usr->getRoles()['ROLE_MASTER']) && !isset($usr->getRoles()['app_income_new_user']) && $usr->getId() != $user->getId()) {
            $this->user = $usr;
            return $this->redirectToRoute('app_home_access_denied', [], Response::HTTP_SEE_OTHER);
        }
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
        $income = new Income();
        $income->setUserid($user);
        $form = $this->createForm(IncomeType::class, $income,[
            'users' => $users
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $income->setCreatedAt(new \DateTime());
            $income->setUpdatedAt(new \DateTime());
            $income->setClubid($income->getUserid()->getClubid());
            $entityManager->persist($income);
            $entityManager->flush();
            $this->user = $usr;
            return $this->redirectToRoute('app_income_show', ['id' => $income->getId()], Response::HTTP_SEE_OTHER);
        }
        $this->user = $usr;
        return $this->renderForm('income/new.html.twig', [
            'income' => $income,
            'form' => $form,
            'sections' => $sections,
            'section' => new Section()
        ]);
    }

    #[Route('/{id}', name: 'app_income_show', methods: ['GET'])]
    public function show(Income $income, EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();
        $user = $income->getUserid();
        if(!isset($usr->getRoles()['ROLE_MASTER']) && !isset($usr->getRoles()['app_income_new_user']) && $usr->getId() != $user->getId()) {
            $this->user = $usr;
            return $this->redirectToRoute('app_home_access_denied', [], Response::HTTP_SEE_OTHER);
        }
        $sections = $entityManager
            ->getRepository(Section::class)
            ->findBy(['clubid' => $this->getUser()->getClubid()]);
        $section = new Section();
        $this->user = $usr;
        return $this->render('income/show.html.twig', [
            'income' => $income,
            'sections' => $sections,
            'section' => new Section()
        ]);
    }

    #[Route('/{id}/edit', name: 'app_income_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Income $income, EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();
        if(!isset($usr->getRoles()['ROLE_MASTER']) && !isset($usr->getRoles()['app_income_edit'])) {
            $this->user = $usr;
            return $this->redirectToRoute('app_home_access_denied', [], Response::HTTP_SEE_OTHER);
        }
        $sections = $entityManager
            ->getRepository(Section::class)
            ->findBy(['clubid' => $this->getUser()->getClubid()]);
        $section = $income->getUserid()->getEquipeid()->getNiveauid()->getSectionid();
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
        $form = $this->createForm(IncomeType::class, $income,[
            'users' => $users
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $income->setUpdatedAt(new \DateTime());
            $entityManager->persist($income);
            $entityManager->flush();
            $this->user = $usr;
            return $this->redirectToRoute('app_income_show', ['id' => $income->getId()], Response::HTTP_SEE_OTHER);
        }
        $this->user = $usr;
        return $this->renderForm('income/edit.html.twig', [
            'income' => $income,
            'form' => $form,
            'sections' => $sections,
            'section' => $section
        ]);
    }

    #[Route('/{id}', name: 'app_income_delete', methods: ['POST'])]
    public function delete(Request $request, Income $income, EntityManagerInterface $entityManager): Response
    {

        if(!isset($usr->getRoles()['ROLE_MASTER']) && !isset($usr->getRoles()['app_income_delete'])) {
            $this->user = $usr;
            return $this->redirectToRoute('app_home_access_denied', [], Response::HTTP_SEE_OTHER);
        }
        if ($this->isCsrfTokenValid('delete'.$income->getId(), $request->request->get('_token'))) {
            $entityManager->remove($income);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_income_index', [], Response::HTTP_SEE_OTHER);
    }
}
