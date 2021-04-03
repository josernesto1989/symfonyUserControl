<?php

namespace App\Controller;

use App\Entity\Day;
use App\Entity\Venta;
use App\Form\VentaType;
use App\Repository\DayRepository;
use App\Repository\WeekRepository;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;

class VentasmoduleController extends AbstractController
{
    /**
     * @Route("/ventasmodule", name="ventasmodule", methods={"GET","POST"})
     *
     */
    public function index(Request $request, WeekRepository $weekRepository, DayRepository $dayRepository)
    {
        $isOpenAWeek = $weekRepository->isOpenAWeek();
        if(!$isOpenAWeek){
            return $this->render('ventasmodule/errorpage.html.twig', [
                'controller_name' => 'Debe iniciar una semana',
                'error' => 'Inicie una nueva semana para continuar',
            ]);
        }
        $isOpenADay = $dayRepository->isOpenADay();
        if(!$isOpenADay){
            return $this->render('ventasmodule/errorpage.html.twig', [
                'controller_name' => 'Debe iniciar un nuevo día',
                'error' => 'Inicie un nuevo día para continuar',
                // 'link' =>Poner enlace
            ]);
        }

        $ventum = new Venta();
        $date = new \DateTime();
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
            $entityManager->persist($ventum);
            $entityManager->flush();

            return $this->redirectToRoute('ventasmodule');
        }

        return $this->render('ventasmodule/index.html.twig', [
            'controller_name' => 'Módulo de Ventas',
            'currentDay' => $currentDay,
            'formVenta' => $form->createView()
        ]);

    }
    /**
     * @Route("/ventasmodule/admin/createday", name="ventasmoduleCreateDay")
     *
     */
    public function createDay()
    {
        return $this->render('ventasmodule/index.html.twig', [
            'controller_name' => 'Módulo de Ventas',
        ]);
    }

    /**
     * @Route("/ventasmodule/admin/createWeek", name="ventasmoduleCreateWeek")
     *
     */
    public function createWeek(WeekRepository $weekRepository)
    {
//        dump($weekRepository->isOpenAWeek());
        $isOpenAWeek = $weekRepository->isOpenAWeek();
        if(!$isOpenAWeek){
            return $this->render('ventasmodule/errorpage.html.twig', [
                'controller_name' => 'Error',
                'error' => 'Existe ya una semana abierta. Debe cerrar todas las semanas antes de abrir una.',
            ]);
        }
        else{
            return $this->render('ventasmodule/index.html.twig', [
                'controller_name' => 'BBB',
            ]);
        }

    }

    /**
     * @Route("/ventasmodule/admin/closeday", name="day_cerrar")
     *
     */
    public function closeDay(DayRepository $dayRepository)
    {
        if(!$dayRepository->isOpenADay())
        {
            return $this->render('ventasmodule/errorpage.html.twig', [
                'controller_name' => 'No existe día abierto',
                'error' => 'Debe abrir un día para cerrarlo',
            ]);
        }
        $day = $dayRepository->getOpenDay()[0];
        $day->setOpen(false);
        $this->getDoctrine()->getManager()->persist($day);
        $this->getDoctrine()->getManager()->flush();

        return $this->render('ventasmodule/ventasbase.html.twig', [
            'controller_name' => 'Módulo de Ventas',
        ]);
    }

    /**
     * @Route("/ventasmodule/admin/closeweek", name="week_cerrar")
     *
     */
    public function closeWeek(DayRepository $dayRepository,WeekRepository $weekRepository)
    {

        if($dayRepository->isOpenADay())
        {
            return $this->render('ventasmodule/errorpage.html.twig', [
                'controller_name' => 'Existe un día abierto',
                'error' => 'Debe cerrar el día para cerrar la semana',
            ]);
        }
        if(!$weekRepository->isOpenAWeek())
        {
            return $this->render('ventasmodule/errorpage.html.twig', [
                'controller_name' => 'Existe un día abierto',
                'error' => 'Debe cerrar el día para cerrar la semana',
            ]);
        }

        $week = $weekRepository->getOpenWeek()[0];
//        $day = $dayRepository->getOpenDay()[0];
        $week->setOpen(false);
        $dayCant = count($week->getDay());
        if ($dayCant>7){
            return $this->render('ventasmodule/errorpage.html.twig', [
                'controller_name' => 'Semana muy larga',
                'error' => 'La semana tiene '.$dayCant.' días',
            ]);
        }
        $date = new \DateTime();
        $date->format('D');
        /**
         * comprobar que no este repetido ningun dia
         * por la fecha se puede poner que la diferencia entre la menor fecha y la maxima
         */
        $this->getDoctrine()->getManager()->persist($week);
        $this->getDoctrine()->getManager()->flush();

        return $this->render('ventasmodule/ventasbase.html.twig', [
            'controller_name' => 'Módulo de Ventas',
        ]);
    }

    /**
     * @Route("/toexcel", name="week_excel", methods={"GET","POST"})
     */
    public function weekToExcel(WeekRepository $weekRepository): Response
    {
        $week = $weekRepository->getLastWeek()[0];

        $excel = \PhpOffice\PhpSpreadsheet\IOFactory::load($this->getParameter('kernel.project_dir') . '/public/excel/weektemplate2.xlsx');
//        var_dump($this->get('templating.helper.assets')->getUrl(''));
//        $excel = new Spreadsheet();

        $cant =0;
        $days= $week->getDay();
        foreach($days as $day){
            while( $cant >=$excel->getSheetCount()){
                $nsheet = new Worksheet();
                $nsheet->setTitle($day->getFecha()->format('l'));
                $excel->addSheet($nsheet);
            }
            $excel->setActiveSheetIndex($cant);
            $cant = $cant+1;
            $sheet = $excel->getActiveSheet();
            $venta = $day->getVenta();

            $row = 3;
            foreach ($venta as $i){
                //            $sheet->setCellValue('C3','Hola Mundo');
                $sheet->setCellValue('A'.$row,$i->getHora()->format('h:i'));
                $sheet->setCellValue('C'.$row,strtoupper($i->getTrabajo()));
                $sheet->setCellValue('E'.$row,$i->getIngreso());
                $sheet->setCellValue('F'.$row,$i->getCosto());
                $sheet->setCellValue('G'.$row,$i->getCreditos());
                $sheet->setCellValue('L'.$row,$i->getUser()->getAlias());
                $row++;
            }

            $sheet->setCellValue('C'.$row,"TARJETA INTERNET");
            $sheet->setCellValue('H'.$row,0);

    }

        $writer = new Xlsx($excel);
        $name ='descargar.xlsx';
        $tempFile = tempnam(sys_get_temp_dir(),$name).'.xlsx';
        $writer->save($tempFile);
        $content = file_get_contents($tempFile);
        header("Content-Disposition:attachment; filename=".$tempFile);
        unlink($tempFile);
        exit($content);

    }


    /**
     * @Route("/ventasmodule/dayothers", options={"expose"=true},name="day_others")
     *
     */
    public function dayOthers(Request $request)
    {
//        echo dump("$request");
        if($request->isXmlHttpRequest()){
            $em = $this->getDoctrine()->getManager();
            $id = $request->request->get('id');
            $firmware = $request->request->get('firmware');
            $tarjetasInternet = $request->request->get('tarjetasInternet');
            $sobrante = $request->request->get('sobrante');
            $day = $em->getRepository(Day::class)->find($id);
            $day->setInternet($tarjetasInternet);
            $day->setSobrante($sobrante);
            $day->setFirmware($firmware);
            $em->flush();
            return new JsonResponse(['id'=>'SI']);
        }
    }


//        if(!$dayRepository->isOpenADay())
//        {
//            return $this->render('ventasmodule/errorpage.html.twig', [
//                'controller_name' => 'No existe día abierto',
//                'error' => 'Debe abrir un día para cerrarlo',
//            ]);
//        }
//        $day = $dayRepository->getOpenDay()[0];
////        $day->setOpen(false);
//
//        $day->setInternet($internet);
//        $day->setFirmware($fw);
//        $day->setSobrante($sob);
//
//        $this->getDoctrine()->getManager()->persist($day);
//        $this->getDoctrine()->getManager()->flush();
//
//        return $this->render('ventasmodule/ventasbase.html.twig', [
//            'controller_name' => 'Módulo de Ventas',
//        ]);
//    }

}
