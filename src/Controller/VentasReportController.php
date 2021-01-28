<?php

namespace App\Controller;


use App\Entity\Day;
use App\Entity\Venta;
use App\Form\VentaType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\DayRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class VentasReportController extends AbstractController
{

    /**
     * @Route("/admin/ventas/report", name="ventas_report", methods={"GET","POST"})
     */
    public function index(Request $request, DayRepository $dayRepository)
    {

        return $this->render('ventas_report/index.html.twig', [
            'controller_name' => 'VentasReportController',
        ]);
    }

    /**
     * @Route("/admin/ventas/report/getventas", options={"expose"=true},name="getventasreport")
     *
     */
    public function getVentasReport(Request $request)
    {
    //    echo dump("$request");

        if($request->isXmlHttpRequest()){
            // $startDate = $request->request->get('startDate');
            // $endDate = $request->request->get('endDate');

            $em = $this->getDoctrine()->getManager();
           
            $dateInital =  $request->get('iniDate');
            $dateEnd =  $request->get('endDate');
            
            $dateInital =date("Y-m-d", strtotime($dateInital));
            $dateEnd =date("Y-m-d", strtotime($dateEnd));

            $days = $em->getRepository(Day::class)->findByDateInterval($dateInital,$dateEnd);

            // $days = $em->getRepository(Day::class)->findAll();
            
            $ventas = [];
            foreach ($days as $day) {
                foreach ($day->getVenta() as $vent) {
                    $ventas[]=$vent->__toJson();
                }
            }

            // $day = $em->getRepository(Day::class)->find($id);
            // $day->setInternet($tarjetasInternet);
            // $day->setSobrante($sobrante);
            // $day->setFirmware($firmware);
            // $em->flush();
            return new JsonResponse(['ventas'=>$ventas]);


        }
    }

    //TODO: hacer cargar ventas de un dia
    //TODO: hacer cargar ventas de un rango de dias(semana)
    //TODO: hacer cargar ventas segun criterio
}
