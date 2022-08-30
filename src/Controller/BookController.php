<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry as PersistenceManagerRegistry;
use App\Entity\Book;
use App\Form\BookType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function home(PersistenceManagerRegistry $doctrine): Response
    {
        $books = $doctrine->getRepository(Book::class)->findAll();
        return $this->render('book/home.html.twig', [
            'books' => $books,
        ]);
        
    }

    #[Route('/add', name: 'app_create')]
    public function add(Request $request, PersistenceManagerRegistry $doctrine): Response
    {
        $book = new Book();

        $form = $this->createForm(BookType::class, $book);

        $form->handleRequest(($request));

        if($form->isSubmitted() && $form->isValid())
        {
            $em = $doctrine->getManager();
            $em->persist($book);
            $em->flush();

            $this->addFlash('notice', 'Submitted successfully!');

            return $this->redirectToRoute('app_home');
        }

        return $this->render('book/add.html.twig', [
            'form' => $form->createView(),
        ]);

    }

    #[Route('/edit/{id}', name: 'app_update')]
    public function edit($id, Request $request, PersistenceManagerRegistry $doctrine): Response
    {
        $book = $doctrine->getRepository(Book::class)->find($id);

        $form = $this->createForm(BookType::class, $book);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $em = $doctrine->getManager();
            $em->persist($book);
            $em->flush();

            $this->addFlash('notice', 'Updated successfully!');

            return $this->redirectToRoute('app_home');
        }

        return $this->render('book/edit.html.twig', [
            'form' => $form->createView(),
        ]);

    }

    #[Route('/delete/{id}', name: 'app_remove')]
    public function remove($id, Request $request, PersistenceManagerRegistry $doctrine): Response
    {
        $book = $doctrine->getRepository(Book::class)->find($id);

        $form = $this->createForm(BookType::class, $book);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $em = $doctrine->getManager();
            $em->remove($book);
            $em->flush();

            $this->addFlash('notice', 'Deleted successfully!');

            return $this->redirectToRoute('app_home');
        }
        
        return $this->render('book/remove.html.twig', [
            'form' => $form->createView(),
        ]);

    }
}
