<?php

namespace App\Controller;

use App\Entity\Week;
use App\Form\WeekType;
use App\Repository\WeekRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/ventasmodule/admin/week")
 */
class WeekController extends AbstractController
{
    /**
     * @Route("/", name="week_index", methods={"GET"})
     */
    public function index(WeekRepository $weekRepository): Response
    {
        return $this->render('week/index.html.twig', [
            'weeks' => $weekRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="week_new", methods={"GET","POST"})
     */
    public function new(Request $request,WeekRepository $weekRepository): Response
    {
        $isOpenAWeek = $weekRepository->isOpenAWeek();
        if($isOpenAWeek){
            return $this->render('ventasmodule/errorpage.html.twig', [
                'controller_name' => 'Error',
                'error' => 'Existe ya una semana abierta. Debe cerrar todas las semanas antes de abrir una.',
            ]);
        }
        $week = new Week();
        $form = $this->createForm(WeekType::class, $week);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($week);
            $entityManager->flush();

            return $this->redirectToRoute('ventasmodule');
        }

        return $this->render('week/new.html.twig', [
            'week' => $week,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="week_show", methods={"GET"})
     */
    public function show(Week $week): Response
    {
        return $this->render('week/show.html.twig', [
            'week' => $week,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="week_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Week $week): Response
    {
        $form = $this->createForm(WeekType::class, $week);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('week_index');
        }

        return $this->render('week/edit.html.twig', [
            'week' => $week,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="week_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Week $week): Response
    {
        if ($this->isCsrfTokenValid('delete'.$week->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($week);
            $entityManager->flush();
        }

        return $this->redirectToRoute('week_index');
    }
}
