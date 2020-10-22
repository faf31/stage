<?php

namespace App\Controller;

use App\Entity\Images;
use App\Entity\Players;
use App\Form\PlayersType;
use App\Repository\ImagesRepository;
use App\Repository\PlayersRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/players")
 */
class PlayersController extends AbstractController
{
    /**
     * @Route("/", name="players_index", methods={"GET"})
     */
    public function index(PlayersRepository $playersRepository): Response
    {
        return $this->render('players/index.html.twig', [
            'players' => $playersRepository->findAll(),
        ]);
    }
    /**
     * @Route("/vue/{id}", name="players_vue")
     */
    public function vue($id,PlayersRepository $player): Response
    {
        return $this->render('players/vue.html.twig', [
            'player' => $player->find($id)
        ]);
    }
     /**
     * @Route("/accueil", name="players_accueil")
     */
    public function play(PlayersRepository $playersRepository,Request $request,PaginatorInterface $paginator): Response
    {
        $donnees=$playersRepository->findBy([],['createdAt' => 'desc']);
        $players=$paginator->paginate(
            $donnees,
            $request->query->getInt('page',1),4 );
        return $this->render('players/all.html.twig', [
            'players' =>$players
        ]);
    }
    


    /**
     * @Route("/new", name="players_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $player = new Players();
        $form = $this->createForm(PlayersType::class, $player);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //on recupere les images transmises
            $images = $form->get('images')->getData();
            //on boucle sur les images 
            foreach($images as $image){
                //on genere un nouveau nom de fichier
                $fichier =md5(uniqid()). '.' . $image->guessExtension();
                //on copie le fichier dans le dossier uploads
                $image->move(
                    $this->getParameter('images_directory'),
                    $fichier
                );

                //on stock l'image en bd (son nom)
                $img= new Images();
                $img->setName($fichier);
                $player->addImage($img);

            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($player);
            $entityManager->flush();

            return $this->redirectToRoute('players_accueil');
        }

        return $this->render('players/new.html.twig', [
            'player' => $player,
            'form' => $form->createView(),
        ]);
    }
    

    /**
     * @Route("/{id}", name="players_show", methods={"GET"})
     */
    public function show(Players $player): Response
    {
        return $this->render('players/show.html.twig', [
            'player' => $player,
        ]);
    }
     

    /**
     * @Route("/{id}/edit", name="players_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Players $player): Response
    {
        $form = $this->createForm(PlayersType::class, $player);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
             //on recupere les images transmises
             $images = $form->get('images')->getData();
             //on boucle sur les images 
             foreach($images as $image){
                 //on genere un nouveau nom de fichier
                 $fichier =md5(uniqid()). '.' . $image->guessExtension();
                 //on copie le fichier dans le dossier uploads
                 $image->move(
                     $this->getParameter('images_directory'),
                     $fichier
                 );
 
                 //on stock l'image en bd (son nom)
                 $img= new Images();
                 $img->setName($fichier);
                 $player->addImage($img);
 
             }
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('players_accueil');
        }

        return $this->render('players/edit.html.twig', [
            'player' => $player,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="players_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Players $player): Response
    {
        if ($this->isCsrfTokenValid('delete'.$player->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($player);
            $entityManager->flush();
        }
 
        return $this->redirectToRoute('players_accueil');
    }
    /**
     * @Route("/supprime/image/{id}", name="players_delete_image", methods={"DELETE"})
     */

     public function deleteImage(Images $image,Request $request){
        $data= json_decode($request->getContent(),true);
        // on verifie si le token est valide
        if($this->isCsrfTokenValid('delete'.$image->getId(), $data['_token'])){
            $nom= $image->getName();
            //on supprime le fichier
            unlink($this->getParameter('images_directory').'/'.$nom);
            $em=$this->getDoctrine()->getManager();
            // on supprime l 'entrée de la base
            $em->remove($image);
            $em->flush();
            //on repond en json
            return new JsonResponse(['success'=>1]);

        }else{
            return new JsonResponse(['error'=>'Token Invalide'],400);

        }

     }
     
    
}
