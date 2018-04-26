<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Plat;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\Query\AST\Functions\CurrentTimestampFunction;

/**
 * Plat controller.
 *
 * @Route("plat")
 */
class PlatController extends Controller
{
    /**
     * Lists all plat entities.
     *
     * @Route("/", name="plat_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $plats = $em->getRepository('AppBundle:Plat')->findAll();

        return $this->render('plat/index.html.twig', array(
            'plats' => $plats,
        ));
    }

    /**
     * Creates a new plat entity.
     *
     * @Route("/new", name="plat_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $plat = new Plat();
        $userId= $this->container->get('security.token_storage')->getToken()->getUser()->getId();
        $plat->setUserPoste($userId);
        $form = $this->createForm('AppBundle\Form\PlatType', $plat);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Récupère user connecté
            $user = $this->container->get('security.token_storage')->getToken()->getUser();

            $user->setPlatsPoste($plat);
            $plat->setUserPoste($user);


            $em = $this->getDoctrine()->getManager();
            $em->persist($plat);
            $em->flush($user, $plat);

            return $this->redirectToRoute('plat_show', array('id' => $plat->getId()));
        }

        return $this->render('plat/new.html.twig', array(
            'plat' => $plat,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a plat entity.
     *
     * @Route("/{id}", name="plat_show")
     * @Method("GET")
     */
    public function showAction(Plat $plat)
    {
        $deleteForm = $this->createDeleteForm($plat);

        return $this->render('plat/show.html.twig', array(
            'plat' => $plat,
            'delete_form' => $deleteForm->createView(),
        ));


    }



    /**
     * Displays a form to edit an existing plat entity.
     *
     * @Route("/{id}/edit", name="plat_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Plat $plat)
    {
        $deleteForm = $this->createDeleteForm($plat);
        $editForm = $this->createForm('AppBundle\Form\PlatType', $plat);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('plat_edit', array('id' => $plat->getId()));
        }

        return $this->render('plat/edit.html.twig', array(
            'plat' => $plat,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a plat entity.
     *
     * @Route("/{id}", name="plat_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Plat $plat)
    {
        $form = $this->createDeleteForm($plat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($plat);
            $em->flush();
        }

        return $this->redirectToRoute('plat_index');
    }

    /**
     * Creates a form to delete a plat entity.
     *
     * @param Plat $plat The plat entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Plat $plat)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('plat_delete', array('id' => $plat->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }

    /**
     * Commander un plat avec ManyToMany.
     *
     * @Route("/{id}/reserve", name="plat_reserve")
     * @Method({"GET", "POST"})
     */
    public function reserveAction(Plat $plat)
    {
        $em = $this->getDoctrine()->getManager();

        // Récupère user connecté
        $user = $this->container->get('security.token_storage')->getToken()->getUser();

        $tel = $plat->getUserPoste()->getTel();

        $plat->addUser($user);
        $user->addPlat($plat);
        $em->flush($plat, $user);

        return $this->render('plat/confirmation.html.twig', array(
            'plat' => $plat,
            'user' => $user,
            'tel' => $tel,
        ));
    }

    /**
     * Commander un plat avec ManyToMany.
     *
     * @Route("/{id}/listeCommande", name="plat_listeCommande")
     * @Method({"GET", "POST"})
     */
    public function listeAction(Plat $plat)
    {
        // Récupère user connecté
        $user = $this->container->get('security.token_storage')->getToken()->getUser();

        $tel = $plat->getUserPoste()->getTel();

        return $this->render('plat/commande.html.twig', array(
            'plat' => $plat,
            'user' => $user,
            'tel' => $tel,
        ));


    }

    /**
     * Annuler commande d'un plat avec ManyToMany.
     *
     * @Route("/{id}/annuler", name="plats_annuler")
     * @Method({"GET", "POST"})
     */
    public function annulerAction(Plat $plat)
    {
        $em = $this->getDoctrine()->getManager();

        $idUser = $this->container->get('security.token_storage')->getToken()->getUser()->getId();

        $utilisateur = $em->getRepository('AppBundle\Entity\User')->findOneBy(['id' => $idUser]);
        $plat->removeUser($utilisateur);
        $utilisateur->removePlat($plat);
        $em->flush($plat, $utilisateur);

        return $this->render('plat/commande.html.twig', array(
            'plat' => $plat,
            'user' => $utilisateur,
        ));

    }
}
