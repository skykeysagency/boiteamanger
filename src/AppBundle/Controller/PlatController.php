<?php

namespace AppBundle\Controller;

use AppBundle\Entity\addReservationChild;
use AppBundle\Entity\Groupe;
use AppBundle\Entity\Plat;
use AppBundle\Entity\Reservation;
use AppBundle\Form\reservationFlow;
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
     * Create new plat with 2Step form
     * @Route("/new", name="plat_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(){
        $formData = new addReservationChild(); // Your form data class. Has to be an object, won't work properly with an array.

        $flow = $this->get('AppBundle.form.reservationFlow'); // must match the flow's service id
        $flow->bind($formData);

        $userId= $this->container->get('security.token_storage')->getToken()->getUser();
        $formData->getPlat()->setUserPoste($userId);


        // form of the current step
        $form = $flow->createForm();
        if ($flow->isValid($form)) {
            $flow->saveCurrentStepData($form);

            if ($flow->nextStep()) {
                // form for the next step
                $form = $flow->createForm();
            } else {

                $formData->getReservation()->setVendeur($userId);
                $formData->getReservation()->setPlat($formData->getPlat());
                dump($formData->getReservation());

                // flow finished
                $em = $this->getDoctrine()->getManager();
                $em->persist($formData->getPlat());
                $em->persist($formData->getReservation());
                $em->flush();

                $flow->reset(); // remove step data from the session

                return $this->redirect($this->generateUrl('menu')); // redirect when done
            }
        }

        return $this->render('plat/new.html.twig', array(
            'flow' => $flow,
            'form' => $form->createView(),
        ));
    }



//    /**
//     * Creates a new plat entity.
//     *
//     * @Route("/new", name="plat_new")
//     * @Method({"GET", "POST"})
//     */
//    public function newAction(Request $request)
//    {
//        $plat = new Plat();
//        $userId= $this->container->get('security.token_storage')->getToken()->getUser();
//        $plat->setUserPoste($userId);
//        $form = $this->createForm('AppBundle\Form\PlatType', $plat);
//
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            // Récupère user connecté
//            $user = $this->container->get('security.token_storage')->getToken()->getUser();
//
//            $user->setPlatsPoste($plat);
//            $plat->setUserPoste($user);
//
//
//            $em = $this->getDoctrine()->getManager();
//            $em->persist($plat);
//            $em->flush($user, $plat);
//
//            return $this->redirectToRoute('plat_show', array('id' => $plat->getId()));
//        }
//
//        return $this->render('plat/new.html.twig', array(
//            'plat' => $plat,
//            'form' => $form->createView(),
//        ));
//    }


    /**
     * Creates a new plat entity.
     *
     * @Route("/new/{id}", name="plat_newwithgroupe")
     * @Method({"GET", "POST"})
     */
    public function newWithGroupeAction(Request $request, $id)
    {
        $plat = new Plat();
        $userId= $this->container->get('security.token_storage')->getToken()->getUser();
        $plat->setUserPoste($userId);
        $repo = $this->getDoctrine()->getRepository(Groupe::class);
        $groupe = $repo->find($id);
        $plat->setGroupe($groupe);
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

        $plat->getReservation()->setAcheteur($user);

        $tel = $plat->getUserPoste()->getTel();

        $plat->addUser($user);
        $user->addPlat($plat);
        $em->persist($plat);
        $em->persist($user);
        $em->flush();
        $this->sendMailDemandeReservation($plat->getUserPoste()->getEmail(),$plat->getUserPoste()->getUsername(),$user,$plat->getNomPlat(),$plat->getReservation());

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
        $plat->getReservation()->setAcheteur(null);
        $plat->getReservation()->setIsClosed(false);

        $utilisateur = $em->getRepository('AppBundle\Entity\User')->findOneBy(['id' => $idUser]);
        $plat->removeUser($utilisateur);
        $utilisateur->removePlat($plat);
        $em->persist($plat);
        $em->persist($utilisateur);
        $em->flush();

        return $this->render('plat/commande.html.twig', array(
            'plat' => $plat,
            'user' => $utilisateur,
        ));

    }

    /**
     *
     * @Route("/{id}/confirm", name="plats_confirm")
     * @Method({"GET", "POST"})
     * @param Reservation $reservation
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function confirmAction(reservation $reservation)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->container->get('security.token_storage')->getToken()->getUser();

        $reservation->setIsClosed(true);
        $this->sendMailConfirmation($reservation->getAcheteur()->getEmail(),$user,$reservation->getVendeur(),$reservation->getPlat());

        $em->persist($reservation);
        $em->flush();

        return $this->render('reservation/index.html.twig', array(
            'reservations' => $reservation,
            'user' =>$user
        ));

    }

    /**
     * @Route("/{id}/reserv", name="reserv")
     *
     */
    public function reservAction(reservation $reservation)
    {
        return $this->render('confirm.html.twig', array(
        'reservation' => $reservation
        ));
    }

    public function sendMailDemandeReservation($mail,$vendeur,$acheteur,$plat,$reservation){

        $message = \Swift_Message::newInstance()
            ->setSubject("[Tup'My Lucnch] ".$acheteur.' veut manger avec vous !')
            ->setFrom('contact@skykeys.fr')
            ->setTo($mail)
            ->setBody(
                $this->renderView(
                    'emails/demandeReservation.html.twig',
                    array('acheteur' => $acheteur,
                        'plat' => $plat,
                        'mail' => $mail,
                        'vendeur' => $vendeur,
                        'reservation' => $reservation)
                ),
                'text/html'
            );
        $this->get('mailer')->send($message);
    }

    public function sendMailConfirmation($mail,$acheteur,$vendeur,$plat){

        $message = \Swift_Message::newInstance()
            ->setSubject("[Tup'My Lucnch] ".$vendeur.' a accepté de manger avec vous !')
            ->setFrom('contact@skykeys.fr')
            ->setTo($mail)
            ->setBody(
                $this->renderView(
                    'emails/confirmRdv.html.twig',
                    array('acheteur' => $acheteur,
                        'plat' => $plat,
                        'mail' => $mail,
                        'vendeur' => $vendeur,)
                ),
                'text/html'
            );
        $this->get('mailer')->send($message);
    }

    public function sendMailAnnulation($mail,$acheteur,$vendeur,$plat){

        $message = \Swift_Message::newInstance()
            ->setSubject("[Tup'My Lucnch] Information importante concernant une commande")
            ->setFrom('contact@skykeys.fr')
            ->setTo($mail)
            ->setBody(
                $this->renderView(
                    'emails/annulRdv.html.twig',
                    array('acheteur' => $acheteur,
                        'plat' => $plat,
                        'mail' => $mail,
                        'vendeur' => $vendeur,)
                ),
                'text/html'
            );
        $this->get('mailer')->send($message);
    }
}
