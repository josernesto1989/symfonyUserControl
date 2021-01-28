<?php

namespace App\Controller;

use App\Entity\TipoPieza;
use App\Form\TipoPiezaType;
use App\Repository\TipoPiezaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/tipo/pieza")
 */
class TipoPiezaController extends AbstractController
{
    /**
     * @Route("/", name="tipo_pieza_index", methods={"GET"})
     */
    public function index(TipoPiezaRepository $tipoPiezaRepository): Response
    {
        return $this->render('tipo_pieza/index.html.twig', [
            'tipo_piezas' => $tipoPiezaRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="tipo_pieza_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $tipoPieza = new TipoPieza();
        $form = $this->createForm(TipoPiezaType::class, $tipoPieza);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($tipoPieza);
            $entityManager->flush();

            return $this->redirectToRoute('tipo_pieza_index');
        }

        return $this->render('tipo_pieza/new.html.twig', [
            'tipo_pieza' => $tipoPieza,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="tipo_pieza_show", methods={"GET"})
     */
    public function show(TipoPieza $tipoPieza): Response
    {
        return $this->render('tipo_pieza/show.html.twig', [
            'tipo_pieza' => $tipoPieza,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="tipo_pieza_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, TipoPieza $tipoPieza): Response
    {
        $form = $this->createForm(TipoPiezaType::class, $tipoPieza);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('tipo_pieza_index');
        }

        return $this->render('tipo_pieza/edit.html.twig', [
            'tipo_pieza' => $tipoPieza,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="tipo_pieza_delete", methods={"DELETE"})
     */
    public function delete(Request $request, TipoPieza $tipoPieza): Response
    {
        if ($this->isCsrfTokenValid('delete'.$tipoPieza->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($tipoPieza);
            $entityManager->flush();
        }

        return $this->redirectToRoute('tipo_pieza_index');
    }
}
