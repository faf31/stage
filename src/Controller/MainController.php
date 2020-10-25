<?php

namespace App\Controller;

use App\Entity\Commentaires;
use App\Form\AnnonceContactType;
use App\Form\CommentairesFormType;
use App\Form\ContactType;
use App\Form\SearchAnnonceType;
use App\Repository\AnnoncesRepository;
use Knp\Component\Pager\PaginatorInterface; // Nous appelons le bundle KNP Paginator
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     */
    public function index(AnnoncesRepository $repo ,Request $request,PaginatorInterface $paginator)
    {
        $donnees=$repo->findBy([],['created_at' => 'desc']);
       
        $form= $this->createForm(SearchAnnonceType::class);
        $search=$form->handleRequest($request);
        if($form->isSubmitted()&& $form->isValid()){
            //on recherche les annonces correspondante au mots cles
            $donnees= $repo->search(
                $search->get('mots')->getData(),
                $search->get('categorie')->getData());
        }
        $annonces=$paginator->paginate(
            $donnees,
            $request->query->getInt('page',1),6 );
        
        return $this->render('main/index.html.twig', [
            'annonces'=>$annonces,
            'form'=>$form->createView()
            
        ]);
    }
    /**
     * @Route("/show/{id}", name="app_annonce")
     */
    public function show($id,AnnoncesRepository $repo,Request $request,MailerInterface $mailer)
    {
        $annonce=$repo->find($id);
        $commentaire= new Commentaires();
        $form=$this->createForm(CommentairesFormType::class,$commentaire);
        $form->handleRequest($request);
        if($form->isSubmitted()&& $form->isValid()){
            $commentaire->setAnnonces($annonce);
            $doctrine=$this->getDoctrine()->getManager();
            $doctrine->persist($commentaire);
            $doctrine->flush();

        }
        $formu=$this->createForm(AnnonceContactType::class);
        $contact=$formu->handleRequest($request);
        if($formu->isSubmitted()&& $formu->isValid()){
//on crée le mail
            $email = (new TemplatedEmail())
            ->from($contact->get('email')->getData())
            ->to($annonce->getUsers()->getEmail())
            ->subject('Contact au sujet de votre annonce"'. $annonce->getTitle() . '"')
            ->htmlTemplate('emails/contact_annonce.html.twig')
            ->context([
                'annonce'=>$annonce,
                'mail'=> $contact->get('email')->getData(),
                'message'=>$contact->get('message')->getData()
            ]);
            //on envoie le mail
            $mailer->send($email);
            $this->addFlash('message', 'votre e-mail a bien ete envoyé');
            return $this->redirectToRoute('app_annonce',['id'=>$annonce->getId()]);
        }


        return $this->render('main/show.html.twig', [
            'annonce'=>$annonce,
            'form'=>$form->createView(),
            'formu'=>$formu->createView()
        ]);
    }
    /**
     * @Route("/all", name="app_all")
     */
    public function allAnnonce(AnnoncesRepository $repo ,Request $request,PaginatorInterface $paginator)
    {
        $donnees=$repo->findBy([],['created_at' => 'desc']);
        $annonces=$paginator->paginate(
            $donnees,
            $request->query->getInt('page',1),9);
        return $this->render('main/all.html.twig', [
            'annonces'=>$annonces
        ]);
    }
    
    /**
     * @Route("/contact", name="app_contact")
     */
public function contact(Request $request ,MailerInterface $mailer){

    $form=$this->createForm(ContactType::class);
    $contact=$form->handleRequest($request);
if($form->isSubmitted()&& $form->isValid()){
    $email=(new TemplatedEmail())
    ->from($contact->get('email')->getData())
    ->to('no-reply@mairie.test')
    ->subject('Contact depuis le site sport connect')
    ->htmlTemplate('emails/contact.html.twig')
    ->context([
        'mail'=>$contact->get('email')->getData(),
        'sujet'=>$contact->get('sujet')->getData(),
        'message'=>$contact->get('message')->getData()
    ]);
    $mailer->send($email);
    $this->addFlash('message','Votre email a bien été envoyé');
    return $this->redirectToRoute('app_contact');

}


    return $this->render('main/contact.html.twig',[
        'form'=>$form->createView()
    ]);

}
/**
     * @Route("/mention", name="app_mention")
     */

    public function mention(){
        return $this->render('main/mention.html.twig', 

            


        );        
    }
    /**
     * @Route("/club", name="app_club")
     */

    public function club(){
        return $this->render('main/club.html.twig', 

            


        );        
    }
}


