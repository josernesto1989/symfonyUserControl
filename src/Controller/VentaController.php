<?php

namespace App\Controller;

use App\Entity\Trace;
use App\Entity\Venta;
use App\Form\VentaType;
use App\Repository\DayRepository;
use App\Repository\VentaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/ventasmodule/venta")
 */
class VentaController extends AbstractController
{
    /**
     * @Route("/", name="venta_index", methods={"GET"})
     */
    public function index(VentaRepository $ventaRepository): Response
    {
        return $this->render('venta/index.html.twig', [
            'ventas' => $ventaRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="venta_new", methods={"GET","POST"})
     */
    public function new(Request $request,DayRepository $dayRepository): Response
    {
        $ventum = new Venta();
        $date = new \DateTime();
        $isOpenADay = $dayRepository->isOpenADay();
        $date = $date->sub(new \DateInterval("PT6H"));
        if($isOpenADay) {
            $currentDay = $dayRepository->getOpenDay()[0];
            $ventum->setDay($currentDay);
            $ventum->setFecha($date);
            $ventum->setHora($date);
            $ventum->setIngreso(0);
            $ventum->setCosto(0);
            $ventum->setCreditos(0);

            $form = $this->createForm(VentaType::class, $ventum);
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
                $trabajo = $ventum->getTrabajo();
                $trace = new Trace('El usuario "'.$user.'" ha creado una nueva venta '.($trabajo).'');
                $entityManager->persist($trace);
                $entityManager->persist($ventum);
                $entityManager->flush();

                return $this->redirectToRoute('ventasmodule');
            }

            return $this->render('venta/new.html.twig', [
                'ventum' => $ventum,
                'form' => $form->createView(),
            ]);
        }
        else{
            return $this->render('ventasmodule/errorpage.html.twig',[
                'controller_name' => 'No existe día abierto',
                'error' => 'Abra un día para poder insertar ventas'
            ]);
        }
    }

    /**
     * @Route("/{id}", name="venta_show", methods={"GET"})
     */
    public function show(Venta $ventum): Response
    {
        return $this->render('venta/show.html.twig', [
            'ventum' => $ventum,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="venta_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Venta $ventum): Response
    {
        $form = $this->createForm(VentaType::class, $ventum);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('ventasmodule');
        }

        return $this->render('venta/edit.html.twig', [
            'ventum' => $ventum,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="venta_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Venta $ventum): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ventum->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($ventum);
            $entityManager->flush();
        }

        return $this->redirectToRoute('venta_index');
    }
}
