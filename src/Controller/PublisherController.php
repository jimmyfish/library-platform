<?php

namespace App\Controller;

use App\Entity\Publisher;
use App\Form\PublisherType;
use App\Repository\PublisherRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/publisher")
 */
class PublisherController extends AbstractController
{
    /**
     * @Route("/", name="publisher_index", methods="GET")
     */
    public function index(PublisherRepository $publisherRepository): Response
    {
        return $this->render('publisher/index.html.twig', ['publishers' => $publisherRepository->findAll()]);
    }

    /**
     * @Route("/new", name="publisher_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $publisher = new Publisher();
        $form = $this->createForm(PublisherType::class, $publisher);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($publisher);
            $em->flush();

            return $this->redirectToRoute('publisher_index');
        }

        return $this->render('publisher/new.html.twig', [
            'publisher' => $publisher,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="publisher_show", methods="GET")
     */
    public function show(Publisher $publisher): Response
    {
        return $this->render('publisher/show.html.twig', ['publisher' => $publisher]);
    }

    /**
     * @Route("/{id}/edit", name="publisher_edit", methods="GET|POST")
     */
    public function edit(Request $request, Publisher $publisher): Response
    {
        $form = $this->createForm(PublisherType::class, $publisher);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('publisher_edit', ['id' => $publisher->getId()]);
        }

        return $this->render('publisher/edit.html.twig', [
            'publisher' => $publisher,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="publisher_delete", methods="DELETE")
     */
    public function delete(Request $request, Publisher $publisher): Response
    {
        if ($this->isCsrfTokenValid('delete'.$publisher->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($publisher);
            $em->flush();
        }

        return $this->redirectToRoute('publisher_index');
    }
}
