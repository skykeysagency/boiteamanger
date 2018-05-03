<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Groupe;
use AppBundle\Entity\User;
use AppBundle\Repository\GroupeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Groupe controller.
 *
 * @Route("groupe")
 */
class GroupeController extends Controller
{
    /**
     * Lists all groupe entities.
     *
     * @Route("/", name="groupe_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $groupes = $em->getRepository('AppBundle:Groupe')->findAll();

        return $this->render('groupe/index.html.twig', array(
            'groupes' => $groupes,
        ));
    }

    /**
     * Creates a new groupe entity.
     *
     * @Route("/new", name="groupe_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $groupe = new Groupe();

        //Current user=proprietaire
        $groupe->setProprietaire($this->getUser()->getId());

        //current user fait partie du groupe par defaut
        $groupe->addUser($this->getUser());

        //tout groupe est privé
        $groupe->setIsPrivate(true);

        $form = $this->createForm('AppBundle\Form\GroupeType', $groupe);
        $part=$groupe->getParticipant();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $repository = $this->getDoctrine()->getRepository(Groupe::class);


            $em = $this->getDoctrine()->getManager();
            $em->persist($groupe);
            $em->flush();

            foreach ($part as $p){
                $groupe->addUser($p);
                if($groupe->getProprietaire() != $p->getId()){
                    $this->sendMailGroupe($p->getEmail(), $p->getUsername(), $groupe->getNom(),$this->getUser()->getUsername());
                }
                $trad=$p->getId();
                $repository->addParticipant($trad,$groupe->getId());
            }

            return $this->redirectToRoute('groupe_show', array('id' => $groupe->getId()));
        }
        $groupe->getParticipant();
        return $this->render('groupe/new.html.twig', array(
            'groupe' => $groupe,
            'form' => $form->createView(),
        ));
    }


    /**
     * Creates a new groupe entity.
     *
     * @Route("/newMod", name="groupe_newModal")
     * @Method({"GET", "POST"})
     */
    public function newModalAction(Request $request)
    {
        $groupe = new Groupe();

        //Current user=proprietaire
        $groupe->setProprietaire($this->getUser()->getId());

        //current user fait partie du groupe par defaut
        $groupe->addUser($this->getUser());

        //tout groupe est privé
        $groupe->setIsPrivate(true);

        $form = $this->createForm('AppBundle\Form\GroupeType', $groupe);
        $part=$groupe->getParticipant();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $repository = $this->getDoctrine()->getRepository(Groupe::class);


            $em = $this->getDoctrine()->getManager();
            $em->persist($groupe);
            $em->flush();

            foreach ($part as $p){
                $groupe->addUser($p);
                if($groupe->getProprietaire() != $p->getId()) {
                    $this->sendMailGroupe($p->getEmail(), $p->getUsername(), $groupe->getNom(), $this->getUser()->getNom());
                }
                $trad=$p->getId();
                $repository->addParticipant($trad,$groupe->getId());
            }

            return $this->redirectToRoute('plat_new');
        }
        $groupe->getParticipant();
        return $this->render('groupe/newModal.html.twig', array(
            'groupe' => $groupe,
            'form' => $form->createView(),
        ));
    }






    /**
     * Finds and displays a groupe entity.
     *
     * @Route("/{id}", name="groupe_show")
     * @Method("GET")
     */
    public function showAction(Groupe $groupe)
    {
        $deleteForm = $this->createDeleteForm($groupe);

        return $this->render('groupe/show.html.twig', array(
            'groupe' => $groupe,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing groupe entity.
     *
     * @Route("/{id}/edit", name="groupe_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Groupe $groupe)
    {
        $deleteForm = $this->createDeleteForm($groupe);
        $editForm = $this->createForm('AppBundle\Form\GroupeType', $groupe);
        $editForm->handleRequest($request);
        $users=$groupe->getParticipant();



        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $repository = $this->getDoctrine()->getRepository(Groupe::class);
            $this->getDoctrine()->getManager()->flush();

            foreach ($users as $v) {
                    $groupe->addUser($v);
                    $this->sendMailGroupe($v->getEmail(), $v->getUsername() ,$groupe->getNom(),$this->getUser()->getUsername());
                    $trad=$v->getId();
                    $repository->addParticipant($trad,$groupe->getId());

                       // $this->addParticipantAction($user, $groupe);
            }
            return $this->redirectToRoute('groupe_edit', array('id' => $groupe->getId()));
        }

        return $this->render('groupe/edit.html.twig', array(
            'groupe' => $groupe,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a groupe entity.
     *
     * @Route("/{id}", name="groupe_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Groupe $groupe)
    {
        $form = $this->createDeleteForm($groupe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($groupe);
            $em->flush();
        }

        return $this->redirectToRoute('groupe_index');
    }


    /**
     * @Route("/delPart/userId={userId}/groupeId={groupeId}", name="del_participant")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function delParticipantAction(Request $request)
    {
      // Récupère paramètre de mon url
       $userId = $request->attributes->get('userId');
       $groupeId = $request->attributes->get('groupeId');

       $repository = $this->getDoctrine()->getRepository(Groupe::class);
      // Cherche plat grâce au nom
       $repository->delParticipantById($userId,$groupeId); // Renvoie un objet
       return $this->redirectToRoute('groupe_show', array(
         'id' => $groupeId ));

    }

    /**
     * @param User $user
     * @param Groupe $groupe
     */
    public function addParticipantAction($user,$groupe)
    {
        $repository = $this->getDoctrine()->getRepository(Groupe::class);

        $groupe->addUser($user);
        $this->sendMailGroupe($user->getEmail(), $user->getUsername() ,$groupe->getNom(),$this->getUser()->getNom());
        $userId=$user->getId();
        $repository->addParticipant($userId,$groupe->getId());


    }




    /**
     * Creates a form to delete a groupe entity.
     *
     * @param Groupe $groupe The groupe entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Groupe $groupe)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('groupe_delete', array('id' => $groupe->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }


    public function sendMailGroupe($mail,$nom,$groupe,$nomChef){

        $message = \Swift_Message::newInstance()
            ->setSubject("[Tup'My Lucnch] ".$nomChef.' vous a ajouté à un groupe')
            ->setFrom('contact@skykeys.fr')
            ->setTo($mail)
            ->setBody(
                $this->renderView(
                // app/Resources/views/Emails/registration.html.twig
                    'Emails/confirmationAjoutGroupe.html.twig',
                    array('nom' => $nom,
                        'groupe' => $groupe,
                        'chef' => $nomChef)
                ),
                'text/html'
            );
        $this->get('mailer')->send($message);
    }
}
