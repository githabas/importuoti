<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Imported;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;


class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm('AppBundle\Form\ImportType');
        $form->handleRequest($request);
        $fs = new Filesystem();
        if(!$fs->exists('data')) {
            $fs->mkdir('data');
        }
        $fhead = 'data/imported_head.json';

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $file = $data['csvfile'];
            $extension = $file->guessExtension();
            $found = false;
            if ($extension === "txt") { // check if the file extension is as required; you can also check the mime type itself: $file->getMimeType()
                $imported = 0;

                $f = fopen($file->getPathname(),"r");
                while(!feof($f)) {
                    $row = fgetcsv($f, 0, '|');
                    if(count($row) === 4) {
                        $imported += 1;
                        $rows[] = $row;
                    }
                }
                if($imported) {
                    $this->backupImported();
                    $first = true;
                    foreach ($rows as $row) {
                        if ($first) {
                            if ($fs->exists($fhead)) {
                                $fs->remove($fhead);
                            }
                            $fs->appendToFile($fhead, json_encode($row));
                            $first = false;
                            continue;
                        }
                        $entity = new Imported();
                        $entity->setFieldOne($row[0]);
                        $entity->setFieldTwo($row[1]);
                        $entity->setFieldThree($row[2]);
                        $entity->setFieldFour($row[3]);
                        $em->persist($entity);
                    }
                    $em->flush($entity);
                    $found = true;
                }
            }
            if ($found) {
                $this->addFlash('success', 'Importuota eilučių: '.($imported - 1));
            } else {
                $this->addFlash('error', 'Netinkamas, arba tuščias failas');
            }
        }

        $table = $em->getRepository('AppBundle:Imported')->findAll();
        $paginator = $this->get('knp_paginator');
        $table = $paginator->paginate($table, $request->get('page', 1), 20);

        $header = array();
        if ($fs->exists($fhead)) {
            $header = json_decode(file_get_contents($fhead));
        }
        return $this->render('default/index.html.twig', [
            'imported' => $table,
            'form' => $form->createView(),
            'header' => $header
        ]);
    }

    /**
     *
     * @Route("/imported-update", name="imported_update")
     */
    public function importedUpdateAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $importedId = $request->query->get('importedId');
        $column = $request->query->get('column');
        $text = $request->query->get('text');
        $entity = $em->getRepository('AppBundle:Imported')->find($importedId);

        $status = 'fail';
        if(in_array($column, array("0", "1", "2", "3")) && !empty($entity)) {
            switch ($column) {
                case 0:
                    $entity->setFieldOne($text);
                    break;
                case 1:
                    $entity->setFieldTwo($text);
                    break;
                case 2:
                    $entity->setFieldThree($text);
                    break;
                case 3:
                    $entity->setFieldFour($text);
            }
            $em->persist($entity);
            $em->flush($entity);
            $status = 'success';
        }

        $response = new JsonResponse();
        $response->setData(array(
            'data' => array(
                'status' => $status,
            )
        ));
        return $response;
    }

    /**
     * Deletes a imported entity.
     *
     * @Route("/imported-delete", name="imported_delete")
     */
    public function deleteAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AppBundle:Imported')->find($request->request->get('id'));

        if ($entity) {
            $em->remove($entity);
            $em->flush($entity);
        }

        return $this->redirectToRoute('homepage');
    }

    private function backupImported() {
        $em = $this->getDoctrine()->getManager();
        $backupTable = 'imported_'.time();
        $sql = 'CREATE TABLE `'.$backupTable.'` (
          `id` int(11) NOT NULL,
          `field_one` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
          `field_two` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
          `field_three` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
          `field_four` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci';
        $result = $em->getConnection()->prepare($sql)->execute();

        $sql = 'INSERT INTO `'.$backupTable.'` SELECT * FROM `imported`';
        $result = $em->getConnection()->prepare($sql)->execute();

        $sql = 'TRUNCATE TABLE `imported`';
        $result = $em->getConnection()->prepare($sql)->execute();

        return $result;
    }

}
