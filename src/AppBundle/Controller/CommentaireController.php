<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Commentaire;
use AppBundle\Entity\Plat;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Commentaire controller.
 *
 * @Route("commentaire")
 */
class CommentaireController extends Controller
{
    /**
     * Lists all commentaire entities.
     *
     * @Route("/", name="commentaire_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $commentaires = $em->getRepository('AppBundle:Commentaire')->findAll();

        return $this->render('commentaire/index.html.twig', array(
            'commentaires' => $commentaires,
        ));
    }

    /**
     * Creates a new commentaire entity.
     *
     * @Route("/new", name="commentaire_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $idPlat = $request->query->get('id');
        $em = $this->getDoctrine()->getManager();
        $commentaire = new Commentaire();

        $form = $this->createForm('AppBundle\Form\CommentaireType', $commentaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $plat = $em->getRepository('AppBundle\Entity\Plat')->find($idPlat);

            $plat->setCommentaires($commentaire);
            $commentaire->setPlat($plat);

            $em->persist($commentaire);
            $em->flush($plat, $commentaire);

            return $this->redirectToRoute('plat_fiche', array(
                'id' => $commentaire->getId(),
                'nom' => $plat->getNomPlat(),
            ));
        }

        return $this->render('commentaire/new.html.twig', array(
            'commentaire' => $commentaire,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a commentaire entity.
     *
     * @Route("/{id}", name="commentaire_show")
     * @Method("GET")
     */
    public function showAction(Commentaire $commentaire)
    {
        $deleteForm = $this->createDeleteForm($commentaire);

        return $this->render('commentaire/show.html.twig', array(
            'commentaire' => $commentaire,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing commentaire entity.
     *
     * @Route("/{id}/edit", name="commentaire_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Commentaire $commentaire)
    {
        $deleteForm = $this->createDeleteForm($commentaire);
        $editForm = $this->createForm('AppBundle\Form\CommentaireType', $commentaire);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('commentaire_edit', array('id' => $commentaire->getId()));
        }

        return $this->render('commentaire/edit.html.twig', array(
            'commentaire' => $commentaire,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a commentaire entity.
     *
     * @Route("/{id}", name="commentaire_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Commentaire $commentaire)
    {
        $form = $this->createDeleteForm($commentaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($commentaire);
            $em->flush();
        }

        return $this->redirectToRoute('commentaire_index');
    }

    /**
     * Creates a form to delete a commentaire entity.
     *
     * @param Commentaire $commentaire The commentaire entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Commentaire $commentaire)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('commentaire_delete', array('id' => $commentaire->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }
}
