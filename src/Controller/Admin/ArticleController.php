<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


#[Route(path: '/admin/article', name: 'admin_article')]
class ArticleController extends AbstractController {

    public function __construct(
        private EntityManagerInterface $em,
        private ArticleRepository $articleRepository,
    ){    }

    #[Route(path:'/create', name: '_create', methods: ['GET', 'POST'])]
    public function create(Request $request): Response {
        
        $article= new Article();

        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);

        // ici on vérifie toutes les sécurité
        if($form->isSubmitted() && $form->isValid()){
            $this->em->persist($article); // préparation en file d'attente 
            $this->em->flush(); // exécuter la file d'attente
            $this->addFlash('success', 'L\'article a bien été créer.');

            return $this->redirectToRoute('admin_article_index');
        }

        return $this->render('Admin/Article/create.html.twig', [
            'form'=> $form,
        ]);
    }

    #[Route(path:'/', name: '_index', methods: ['GET'])]
    public function index() : Response 
    {
        return $this->render('Admin/Article/index.html.twig', [
            'articles' => $this->articleRepository->findAll()
        ]);
    }
    #[Route('/edit/{id}', name: '_edit', methods: ['GET', 'POST'])]
    public function update(Article $article, Request $request) : Response /// ici en paramètre le params converter 
    {
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $this->em->persist($article);
            $this->em->flush();

            $this->addFlash('success', 'L\'article a bien été modifié.');
            return $this->redirectToRoute('admin_article_index');
        }

        return $this->render('Admin/Article/update.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/delete/{id}', name: '_delete', methods: ['POST'])]
    public function delete(Article $article, Request $request) : RedirectResponse 
    {
        if($this->isCsrfTokenValid('delete'.$article->getId(), $request->request->get('token'))) {
            $this->em->remove($article);
            $this->em->flush();

            $this->addFlash('success', 'L\'article a bien été supprimé.');
        }
        else {
            $this->addFlash('danger', 'Le token est invalide.');
        }

        return $this->redirectToRoute('admin_article_index');
    }

}