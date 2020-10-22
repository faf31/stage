<?php

namespace App\Controller;

use App\Entity\Annonces;
use App\Form\AnnoncesType;
use App\Form\EditProfileType;
use App\Repository\PlayersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UsersController extends AbstractController
{
    /**
     * @Route("/users", name="users")
     */
    public function index()
    {
        return $this->render('users/index.html.twig' );
    }
    /**
     * @Route("/users/annonces/ajout", name="users_annonces_ajout")
     */
    public function ajoutAnnonce(Request $request)
    {
        $annonce =new Annonces;
        $form=$this->createForm(AnnoncesType::class, $annonce);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $annonce->setUsers($this->getUser());
            $em= $this->getDoctrine()->getManager();
            $em->persist($annonce);
            $em->flush();
            return $this->redirectToRoute('users');
        }

        return $this->render('users/annonces/ajout.html.twig', [
            'form'=>$form->createView()
        ]);
    }

    /**
     * @Route("/users/profile/modifier", name="users_modif_profile")
     */
    public function edithProfile(Request $request)
    {
        $user=$this->getUser();
        $form=$this->createForm(EditProfileType::class, $user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em= $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            $this->addFlash('message','Profil mis à jour');
            return $this->redirectToRoute('users');
        }

        return $this->render('users/editeProfile.html.twig', [
            'form'=>$form->createView()
        ]);
    }

     /**
     * @Route("/users/pass/modifier", name="users_modif_pass")
     */
    public function edithPass(Request $request,UserPasswordEncoderInterface $passwordEncoder)
    {
       if($request->isMethod('POST')){
           $em=$this->getDoctrine()->getManager();
           $user=$this->getUser();
           //on verifie si les 2 mdp sont identiques
           if($request->request->get('pass')==$request->request->get('pass2')){
               $user->setPassword($passwordEncoder->encodePassword($user,$request->request->get('pass')));
               $em->flush();
               $this->addFlash('success','mot de passe mis à jour');
               return $this->redirectToRoute('users');

           }else{
               $this->addFlash('error','les mots de passe ne sont pas identiques');
           }
       }
        return $this->render('users/editPass.html.twig' );
    }
}
