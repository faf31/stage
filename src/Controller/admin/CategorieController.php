<?php

namespace App\Controller\admin;

use App\Entity\Categories;
use App\Form\CategoriesType;
use App\Repository\CategoriesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @route("/admin/categorie", name="admin_categorie")
 * @package App\Controller
 */
class CategorieController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(CategoriesRepository $repo)
    {
        return $this->render('admin/categories/index.html.twig', [
            'categories' => $repo->findAll()
        ]);
    }

     /**
     * @Route("/ajout", name="ajout")
     */
    public function ajoutCat(Request $request)

    {
        $categorie= new Categories;
        $form= $this->createForm(CategoriesType::class, $categorie);
        $form->handleRequest($request);
        if($form->isSubmitted()&& $form->isValid()){
            $em= $this->getDoctrine()->getManager();
            $em->persist($categorie);
            $em->flush();
            return $this->redirectToRoute('admin_categoriehome');
        }


        return $this->render('admin/categories/ajout.html.twig', [
            'form'=>$form->createView()
        ]);
    }
     /**
     * @Route("/modifier/{id}", name="modifier")
     */
    public function modifCat(Request $request,Categories $categorie)

    {
        $form= $this->createForm(CategoriesType::class, $categorie);
        $form->handleRequest($request);
        if($form->isSubmitted()&& $form->isValid()){
            $em= $this->getDoctrine()->getManager();
            $em->persist($categorie);
            $em->flush();
            return $this->redirectToRoute('admin_categoriehome');
        }


        return $this->render('admin/categories/ajout.html.twig', [
            'form'=>$form->createView()
        ]);
    }
    /**
     * @Route("/deletecat/{id}", name="deletecat")
     */
    public function supprimercat(Categories $categorie)

    {
        $em=$this->getDoctrine()->getManager();
        $em->remove($categorie);
        $em->flush();
        $this->addFlash('message', 'Categorie supprimée avec succes');
        return $this->redirectToRoute('admin_categoriehome')
            
        ;
    }
}
