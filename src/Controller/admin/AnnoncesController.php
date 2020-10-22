<?php

namespace App\Controller\admin;

use App\Entity\Annonces;
use App\Form\AnnoncesType;
use App\Repository\AnnoncesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @route("/admin/annonces", name="admin_annonces")
 * @package App\Controller\Admin
 */
class AnnoncesController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(AnnoncesRepository $repo)
    {
        return $this->render('admin/annonces/index.html.twig', [
            'annonces' => $repo->findAll()
        ]);
    }
/**
     * @Route("/delete/{id}", name="delete")
     */
    public function supprimer(Annonces $annonce)

    {
        $em=$this->getDoctrine()->getManager();
        $em->remove($annonce);
        $em->flush();
        $this->addFlash('message', 'Annonce supprimée avec succes');
        return $this->redirectToRoute('admin_annonceshome')
            
        ;
    }
 /**
     * @Route("/modifierannonce/{id}", name="modifier")
     */
    public function modifCat(Request $request,Annonces $annonce)

    {
        $form= $this->createForm(AnnoncesType::class, $annonce);
        $form->handleRequest($request);
        if($form->isSubmitted()&& $form->isValid()){
            $em= $this->getDoctrine()->getManager();
            $em->persist($annonce);
            $em->flush();
            return $this->redirectToRoute('admin_annonceshome');
        }


        return $this->render('admin/annonces/ajout.html.twig', [
            'form'=>$form->createView()
        ]);
    }
     
}
