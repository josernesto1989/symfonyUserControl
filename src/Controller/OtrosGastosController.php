<?php

namespace App\Controller;

use App\Entity\OtrosGastos;
use App\Form\OtrosGastosType;
use App\Repository\DayRepository;
use App\Repository\OtrosGastosRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/ventasmodule/otros/gastos")
 */
class OtrosGastosController extends AbstractController
{
    /**
     * @Route("/", name="otros_gastos_index", methods={"GET"})
     */
    public function index(OtrosGastosRepository $otrosGastosRepository): Response
    {
        return $this->render('otros_gastos/index.html.twig', [
            'otros_gastos' => $otrosGastosRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="otros_gastos_new", methods={"GET","POST"})
     */
    public function new(Request $request,DayRepository $dayRepository): Response
    {
        $isAOpenDay = $dayRepository->isOpenADay();
        if(!$isAOpenDay){

            return $this->render('ventasmodule/errorpage.html.twig',[
                'controller_name' => 'No existe día abierto',
                'error' => 'Abra un día para poder insertar gastos'
            ]);
        }
        $day = $dayRepository->getOpenDay()[0];
        $otrosGasto = new OtrosGastos();
        $otrosGasto->setDay($day);
        $otrosGasto->setCosto(0);
        $form = $this->createForm(OtrosGastosType::class, $otrosGasto);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($otrosGasto);
            $entityManager->flush();

            return $this->redirectToRoute('ventasmodule');
        }

        return $this->render('otros_gastos/new.html.twig', [
            'otros_gasto' => $otrosGasto,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="otros_gastos_show", methods={"GET"})
     */
    public function show(OtrosGastos $otrosGasto): Response
    {
        return $this->render('otros_gastos/show.html.twig', [
            'otros_gasto' => $otrosGasto,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="otros_gastos_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, OtrosGastos $otrosGasto): Response
    {
        $form = $this->createForm(OtrosGastosType::class, $otrosGasto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('otros_gastos_index');
        }

        return $this->render('otros_gastos/edit.html.twig', [
            'otros_gasto' => $otrosGasto,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="otros_gastos_delete", methods={"DELETE"})
     */
    public function delete(Request $request, OtrosGastos $otrosGasto): Response
    {
        if ($this->isCsrfTokenValid('delete'.$otrosGasto->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($otrosGasto);
            $entityManager->flush();
        }

        return $this->redirectToRoute('otros_gastos_index');
    }
}
