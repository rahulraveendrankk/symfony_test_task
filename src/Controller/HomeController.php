<?php

namespace App\Controller;

use App\Entity\Posts;
use App\Repository\PostsRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route; 

class HomeController extends AbstractController
{
    public function __construct(ManagerRegistry $doctrine,PostsRepository $postrepo)
    {
        $this->doctrine=$doctrine; 
        $this->postrepo=$postrepo; 
    }
    #[Route('/home', name: 'home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    #[Route('/list', name: 'list')]
    public function list(): Response
    {
        if( $this->isGranted('IS_AUTHENTICATED_FULLY') ){
            
            $posts = $this->doctrine->getrepository(Posts::class)->findAll(); 
                
            return $this->render('home/list.html.twig', [
                'posts' => $posts,
            ]);
        }else{
            return $this->redirect('/login');
        }
    }

    #[Route('/deletePost/{id}',methods:['GET','DELETE'], name: 'deletePost')]
    public function deletePost(int $id)
    { 
         
        $entityManager = $this->doctrine->getManager();
        $single_post = $this->postrepo->find($id); 
        if($single_post){
            $entityManager->remove($single_post); 
            $entityManager->flush();  
        }  
        
        return $this->redirect('/list');
    }

    #[Route('/posts',methods:['GET'], name: 'posts')]
    public function posts():Response
    {
        $posts = $this->doctrine->getrepository(Posts::class)->findAll(); 
        
        return $this->json($posts);
    }
}
