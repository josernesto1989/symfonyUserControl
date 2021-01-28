<?php

namespace App\Controller;

use App\Entity\PiezaAPagar;
use App\Form\PiezaAPagarType;
use App\Repository\DayRepository;
use App\Repository\PiezaAPagarRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/ventasmodule/admin/piezaapagar")
 */
class PiezaAPagarController extends AbstractController
{
    /**
     * @Route("/", name="pieza_a_pagar_index", methods={"GET"})
     */
    public function index(PiezaAPagarRepository $piezaAPagarRepository): Response
    {
        return $this->render('pieza_a_pagar/index.html.twig', [
            'pieza_a_pagars' => $piezaAPagarRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="pieza_a_pagar_new", methods={"GET","POST"})
     */
    public function new(Request $request,DayRepository $dayRepository): Response
    {
        if(!$dayRepository->isOpenADay())
        {
            return $this->render('ventasmodule/errorpage.html.twig',[
                'controller_name' => 'No existe día abierto',
                'error' => 'Abra un día para poder insertar piezas a pagar'
            ]);
        }

        $day = $dayRepository->getOpenDay()[0];
        $piezaAPagar = new PiezaAPagar();
        $piezaAPagar->setDay($day);
        $form = $this->createForm(PiezaAPagarType::class, $piezaAPagar);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($piezaAPagar);
            $entityManager->flush();

            return $this->redirectToRoute('ventasmodule');
        }

        return $this->render('pieza_a_pagar/new.html.twig', [
            'pieza_a_pagar' => $piezaAPagar,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="pieza_a_pagar_show", methods={"GET"})
     */
    public function show(PiezaAPagar $piezaAPagar): Response
    {
        return $this->render('pieza_a_pagar/show.html.twig', [
            'pieza_a_pagar' => $piezaAPagar,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="pieza_a_pagar_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, PiezaAPagar $piezaAPagar): Response
    {
        $form = $this->createForm(PiezaAPagarType::class, $piezaAPagar);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('pieza_a_pagar_index');
        }

        return $this->render('pieza_a_pagar/edit.html.twig', [
            'pieza_a_pagar' => $piezaAPagar,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="pieza_a_pagar_delete", methods={"DELETE"})
     */
    public function delete(Request $request, PiezaAPagar $piezaAPagar): Response
    {
        if ($this->isCsrfTokenValid('delete'.$piezaAPagar->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($piezaAPagar);
            $entityManager->flush();
        }

        return $this->redirectToRoute('pieza_a_pagar_index');
    }
}
