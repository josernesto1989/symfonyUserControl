<?php

namespace App\Controller;

use App\Entity\Day;
use App\Entity\Trace;
use App\Form\DayType;
use App\Repository\DayRepository;
use App\Repository\WeekRepository;
use PhpOffice\PhpSpreadsheetTests\Calculation\Functions\Logical\FalseTest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/ventasmodule/admin/day")
 */
class DayController extends AbstractController
{
    /**
     * @Route("/", name="day_index", methods={"GET"})
     */
    public function index(DayRepository $dayRepository): Response
    {
        return $this->render('day/index.html.twig', [
            'days' => $dayRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="day_new", methods={"GET","POST"})
     */
    public function new(Request $request, WeekRepository $weekRepository, DayRepository $dayRepository): Response
    {
        $isOpenAWeek = $weekRepository->isOpenAWeek();
        if(!$isOpenAWeek){
            return $this->render('ventasmodule/errorpage.html.twig', [
                'controller_name' => 'Error',
                'error' => 'No existe una semana abierta. Debe abrir una semana antes de abrir un día.',
            ]);
        }
        $isOpenADay = $dayRepository->isOpenADay();
        if($isOpenADay){
            return $this->render('ventasmodule/errorpage.html.twig', [
                'controller_name' => 'Error',
                'error' => 'Existe un día abierto. Cerrar un día antes de abrir un nuevo día.',
            ]);
        }



        $day = new Day();
        $day->setFecha(new \DateTime());
        $week = $weekRepository->getOpenWeek()[0];
        $day->setWeek($week);
        $form = $this->createForm(DayType::class, $day);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $user = $this->getUser();
            if($user != null){
                $user = $user->getUsername();
            }
            else
            {
                $user = 'USUARIO_DESCONOCIDO';
            }

            $trace = new Trace('El usuario "'.$user.'" ha iniciado un nuevo día');
            $entityManager->persist($trace);
            $entityManager->persist($day);
            $entityManager->flush();

            return $this->redirectToRoute('ventasmodule');
        }

        return $this->render('day/new.html.twig', [
            'day' => $day,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="day_show", methods={"GET"})
     */
    public function show(Day $day): Response
    {
        return $this->render('day/show.html.twig', [
            'day' => $day,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="day_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Day $day): Response
    {
        $form = $this->createForm(DayType::class, $day);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();
            if($user != null){
                $user = $user->getUsername();
            }
            else
            {
                $user = 'USUARIO_DESCONOCIDO';
            }
            $trace = new Trace('El usuario "'.$user.'" ha editado día'.$day->getFecha()->format('Y-m-d'));
            $this->getDoctrine()->getManager()->persist($trace);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('day_index');
        }

        return $this->render('day/edit.html.twig', [
            'day' => $day,
            'form' => $form->createView(),
        ]);
    }




    /**
     * @Route("/{id}", name="day_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Day $day): Response
    {
        if ($this->isCsrfTokenValid('delete'.$day->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($day);
            $entityManager->flush();
        }

        return $this->redirectToRoute('day_index');
    }
}
