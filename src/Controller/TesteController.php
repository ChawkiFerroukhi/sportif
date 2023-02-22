<?php

namespace App\Controller;

use App\Entity\Teste;
use App\Form\TesteType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/teste')]
class TesteController extends AbstractController
{
    #[Route('/', name: 'app_teste_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $testes = $entityManager
            ->getRepository(Teste::class)
            ->findAll();

        return $this->render('teste/index.html.twig', [
            'testes' => $testes,
        ]);
    }

    #[Route('/new', name: 'app_teste_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $teste = new Teste();
        $form = $this->createForm(TesteType::class, $teste);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($teste);
            $entityManager->flush();

            return $this->redirectToRoute('app_teste_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('teste/new.html.twig', [
            'teste' => $teste,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_teste_show', methods: ['GET'])]
    public function show(Teste $teste): Response
    {
        return $this->render('teste/show.html.twig', [
            'teste' => $teste,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_teste_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Teste $teste, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TesteType::class, $teste);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_teste_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('teste/edit.html.twig', [
            'teste' => $teste,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_teste_delete', methods: ['POST'])]
    public function delete(Request $request, Teste $teste, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$teste->getId(), $request->request->get('_token'))) {
            $entityManager->remove($teste);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_teste_index', [], Response::HTTP_SEE_OTHER);
    }
}
