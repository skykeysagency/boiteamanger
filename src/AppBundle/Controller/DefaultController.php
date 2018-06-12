<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="accueil")
     */
    public function accueilAction(Request $request)
    {
        // retourne à l'accueil
        return $this->render('accueil.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')) . DIRECTORY_SEPARATOR,
        ]);

    }

    /**
     * @Route("/menu", name="menu")
     *
     */
    public function menuAction(Request $request)
    {
        $arr = $request->query->get('arr');

        $em = $this->getDoctrine()->getManager();


        if(!$arr){
            $plats = $em->getRepository('AppBundle:Plat')->findAll();
            return $this->render('menu.html.twig', array(
                'plats' => $plats,
            ));


        }else{

            $platsArr = $em->getRepository('AppBundle:Plat')->findByArrondissement($arr);


            return $this->render('menu.html.twig', array(
                'plats' => $platsArr,
            ));

        }


    }
}
