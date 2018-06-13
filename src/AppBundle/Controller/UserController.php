<?php
/**
 * Created by PhpStorm.
 * User: Mbape
 * Date: 20/02/2018
 * Time: 21:40
 */

namespace AppBundle\Controller;


use AppBundle\Entity\User;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * User controller.
 *
 * @Route("user")
 */
class UserController extends Controller
{
    /**
     * Finds and displays a etudiant entity.
     *
     * @Route("/{id}", name="user_show",  requirements={"page"="\d+"})
     * @Method("GET")
     */
    public function showAction(User $user,Request $request)
    {

        $db = $this->getDoctrine()->getManager();

        //$listPlat = $db->getRepository('AppBundle:Plat')->findByTopFour(
        //    4,
        //    $user
        //);

        $listPlat = $db->getRepository('AppBundle:Plat')->findBy(['userPoste' => $user->getId()],['creeA' => 'ASC'],4);

        $listCom = $db->getRepository('AppBundle:Commentaire')->findByPage(
            $request->query->getInt('page', 1),
            4,
            $user
        );





        return $this->render('user/show.html.twig', array(
            'user' => $user,
            'listPlat' => $listPlat,
            'listCom' => $listCom
        ));
    }

    /**
     * Finds and displays a etudiant entity.
     *
     * @Route("/{id}/com", name="user_showCom")
     * @Method("GET")
     */
    public function commentaireAction(Request $request){
        $user=$request->get('user');
        $db = $this->getDoctrine()->getManager();


        $listCom = $db->getRepository('AppBundle:Commentaire')->findByPage(
            $request->query->getInt('page', 1),
            4,
            $user
        );

        dump($user);
        return $this->render('user/commentaireShow.html.twig', array(
            "listCom" => $listCom
        ));

    }


    /**
     * Finds and displays a etudiant entity.
     *
     * @Route("/{id}/plat", name="user_showPlat")
     * @Method("GET")
     */
    public function platAction(User $user,Request $request){

        $db = $this->getDoctrine()->getManager();

        $listPlat = $db->getRepository('AppBundle:Plat')->findByPage(
            $request->query->getInt('page', 1),
            4,
            $user
        );

        return $this->render('user/platShow.html.twig', array(
            'user' => $user,
            "listPlat" => $listPlat
        ));

    }

}