<?php

namespace App\Controller;

use App\Entity\Blog;
use App\Entity\Club;
use App\Entity\Section;
use App\Form\BlogType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/blog')]
class BlogController extends AbstractController
{
    #[Route('/front/{id}', name: 'app_blog_front', methods: ['GET'])]
    public function front(Blog $blog,EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();
        if(!$blog->getIsVisible()) {
            if(!isset($usr) || !($usr->getRoles()['ROLE_ADMIN'] && $usr->getClubid() == $blog->getClubid())) {
                return $this->redirectToRoute('app_blog_front_list', ['id' => $blog->getSectionid()->getId() ], Response::HTTP_SEE_OTHER);
            }
        }
       
        $blogs = $entityManager->getRepository(Blog::class)
        ->findBy(["sectionid" => $blog->getSectionid()]);
        $sections = $entityManager
        ->getRepository(Section::class)
        ->findBy(['clubid' => $blog->getSectionid()->getClubid()]);
        $this->user = $usr;
        return $this->render('blog/front.html.twig', [
            'blogs' => $blogs,
            'blog' => $blog,
            "sections" => $sections,
        ]);
    }
    #[Route('/list/{id}', name: 'app_blog_front_list', methods: ['GET'])]
    public function front_list(Section $section,EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();
        $blogs = $entityManager->getRepository(Blog::class)
        ->findBy(["sectionid" => $section->getId()]);
        $sections = $entityManager
        ->getRepository(Section::class)
        ->findBy(['clubid' => $section->getClubid()]);
        $this->user = $usr;
        return $this->render('blog/front_list.html.twig', [
            'blogs' => $blogs,
            'section' => $section,
            'sections' => $sections,
        ]);
    }
    #[Route('/section/{id}', name: 'app_blog_index', methods: ['GET'])]
    public function index(Section $section,EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();
        $blogs = $entityManager
            ->getRepository(Blog::class)
            ->findBy(['sectionid'=> $section->getId()]);
        $sections = $entityManager
            ->getRepository(Section::class)
            ->findBy(['clubid' => $this->getUser()->getClubid()]);
        $this->user = $usr;
        return $this->render('blog/index.html.twig', [
            'blogs' => $blogs,
            'sections' => $sections,
            'section' => $section,
        ]);
    }
    

    #[Route('/{id}/new', name: 'app_blog_new', methods: ['GET', 'POST'])]
    public function new(Section $section,Request $request, EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();
        if($usr->getClubid() != $section->getClubid() || !$usr->getRoles()['ROLE_ADMIN']){
            return $this->redirectToRoute('app_club_show', ['id' => $usr->getClubid()->getId() ], Response::HTTP_SEE_OTHER);
        }
            
        $sections = $entityManager
            ->getRepository(Section::class)
            ->findBy(['clubid' => $this->getUser()->getClubid()]);
        $blog = new Blog();
        $form = $this->createForm(BlogType::class, $blog);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $blog->setCreatedAt(new \DateTime());
            $blog->setUpdatedAt(new \DateTime());
            $blog->setSectionid($section);
            $blog->setClubid($section->getClubid());
            if($blog->getVideo() != null) {
                $video = $blog->getVideo();
                
                $video = str_replace("watch?v=","embed/",$video);
                
                if(strpos($video,'&')) {
                    $video = substr($video,0,strpos($video,'&'));
                }
                $blog->setVideo($video);
            }
            $entityManager->persist($blog);
            $entityManager->flush();
            $this->user = $usr;
            return $this->redirectToRoute('app_blog_index', ['id' => $section->getId() ], Response::HTTP_SEE_OTHER);
        }

        $this->user = $usr;
        return $this->renderForm('blog/new.html.twig', [
            'blog' => $blog,
            'form' => $form,
            'sections' => $sections,
            'section' => new Section(),
        ]);
    }

    #[Route('/{id}', name: 'app_blog_show', methods: ['GET'])]
    public function show(Blog $blog, EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();
        $sections = $entityManager
            ->getRepository(Section::class)
            ->findBy(['clubid' => $this->getUser()->getClubid()]);
        $section = $blog->getSectionid();
        $this->user = $usr;
        return $this->render('blog/show.html.twig', [
            'blog' => $blog,
            'sections' => $sections,
            'section' => $section,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_blog_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Blog $blog, EntityManagerInterface $entityManager): Response
    {
        $usr = $this->getUser();

        if($usr->getClubid() != $blog->getClubid() || !$usr->getRoles()['ROLE_ADMIN']){
            return $this->redirectToRoute('app_club_show', ['id' => $usr->getClubid()->getId() ], Response::HTTP_SEE_OTHER);
        }
        $sections = $entityManager
            ->getRepository(Section::class)
            ->findBy(['clubid' => $this->getUser()->getClubid()]);
        $form = $this->createForm(BlogType::class, $blog);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $blog->setUpdatedat(new \DateTime());
            if($blog->getVideo() != null) {
                $video = $blog->getVideo();
                
                $video = str_replace("watch?v=","embed/",$video);
                
                if(strpos($video,'&')) {
                    $video = substr($video,0,strpos($video,'&'));
                }
                $blog->setVideo($video);
            }
            $entityManager->flush();

            return $this->redirectToRoute('app_blog_show', ['id' => $blog->getId()], Response::HTTP_SEE_OTHER);
        }

        $this->user = $usr;
        return $this->renderForm('blog/edit.html.twig', [
            'blog' => $blog,
            'form' => $form,
            'sections' => $sections,
            'section' => new Section(),
        ]);
    }

    #[Route('/{id}', name: 'app_blog_delete', methods: ['POST'])]
    public function delete(Request $request, Blog $blog, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$blog->getId(), $request->request->get('_token'))) {
            $entityManager->remove($blog);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_blog_index', [], Response::HTTP_SEE_OTHER);
    }
}
