<?php

namespace App\Controller;

use App\Repository\AuthorRepository;
use App\Entity\Author;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


class AuthorController extends AbstractController
{
    #[Route('/author', name: 'app_author')]
    public function index(): Response
    {
        return $this->render('author/index.html.twig', [
            'controller_name' => 'AuthorController',
        ]);
    }

    #[Route('/author/all', name: 'app_show_author_all')]
    public function showAll(AuthorRepository $authorRepository): Response
    {
        $authors = $authorRepository->findAll();
        return $this->render('author/showAll.html.twig', [
            'authors' => $authors,
        ]);
    }

    #[Route('/author/add', name: 'app_add_author')]
    public function addAuthor(ManagerRegistry $manager): Response
    {

        $author = new Author();
        $author->setUsername('Test test');
        $author->setEmail('test@test.com');

        $em = $manager->getManager();
        $em->persist($author);
        $em->flush();
        return $this->redirectToRoute("app_show_author_all");
    }
    #[Route("/author/delete/{id}", name: "app_delete_author")]
    public function deleteAuthor(AuthorRepository $authorRepository, ManagerRegistry $manager, $id): Response
    {
        $author = $authorRepository->find($id);
        $em = $manager->getManager();
        $em->remove($author);
        $em->flush();
        return $this->redirectToRoute("app_show_author_all");
    }

    #[Route("/author/update/{id}", name: "app_update_author")]
    public function updateAuthor(AuthorRepository $authorRepository, ManagerRegistry $manager, $id): Response
    {
        $author = $authorRepository->find($id);
        $author->setUsername('Test test');
        $author->setEmail("test@testUpdated.com");
        $em = $manager->getManager();
        $em->persist($author);
        $em->flush();
        return $this->redirectToRoute("app_show_author_all");
    }
}
