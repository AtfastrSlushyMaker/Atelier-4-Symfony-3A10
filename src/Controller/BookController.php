<?php

namespace App\Controller;

use App\Entity\Book;
use App\Repository\BookRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookController extends AbstractController
{
    #[Route('/book', name: 'app_book')]
    public function index(): Response
    {
        return $this->render('book/index.html.twig', [
            'controller_name' => 'BookController',
        ]);
    }

    #[Route(path: '/book/add', name: 'app_book_add')]
    public function addBook(ManagerRegistry $manager): Response
    {
        $book = new Book();
        $book->setTitle("Book");
        $book->setPublicationDate(new \DateTime());
        $book->setEnabled(true);

        $em = $manager->getManager();
        $em->persist($book);
        $em->flush();

        return new Response("Book added.");
    }

    #[Route(path: '/book/update/{id}', name: 'app_book_update')]
    public function updateBook($id, ManagerRegistry $manager, BookRepository $repo): Response
    {
        $book = $repo->find($id);
        if (!$book) {
            return new Response('Book not found', 404);
        }

        $book->setTitle('Book 2');
        $em = $manager->getManager();
        $em->flush();

        return new Response("Book updated.");
    }

    #[Route(path: '/book/getall', name: 'app_book_getall')]
    public function getAllBooks(BookRepository $repo): Response
    {
        return $this->render('book/booksList.html.twig', [
            'books' => $repo->findAll(), // Changed "book" to "books" to reflect a list
        ]);
    }

    #[Route(path: '/book/delete/{id}', name: 'app_book_delete')]
    public function deleteBook($id, ManagerRegistry $manager, BookRepository $repo): Response
    {
        $book = $repo->find($id);
        if (!$book) {
            return new Response('Book not found', 404);
        }

        $em = $manager->getManager();
        $em->remove($book);
        $em->flush();

        return $this->redirectToRoute('app_book_getall');
    }
}
