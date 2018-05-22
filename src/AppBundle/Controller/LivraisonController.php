<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Livraison;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Livraison controller.
 *
 * @Route("livraison")
 */
class LivraisonController extends Controller
{
    /**
     * Lists all livraison entities.
     *
     * @Route("/", name="livraison_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $livraisons = $em->getRepository('AppBundle:Livraison')->findAll();

        return $this->render('livraison/index.html.twig', array(
            'livraisons' => $livraisons,
        ));
    }

    /**
     * Finds and displays a livraison entity.
     *
     * @Route("/{id}", name="livraison_show")
     * @Method("GET")
     */
    public function showAction(Livraison $livraison)
    {

        return $this->render('livraison/show.html.twig', array(
            'livraison' => $livraison,
        ));
    }
}
