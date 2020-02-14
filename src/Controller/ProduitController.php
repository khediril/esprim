<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\Produit;
use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ProduitController extends AbstractController
{
    /**
     * @Route("/produit/add/{name}/{quantite}/{prix}/{description}/{idcateg}", name="produit")
     */
    public function add($name,$quantite,$prix,$description,$idcateg,ValidatorInterface $validator)
    {
        $produit=new Produit();
        $produit->setName($name);
        $produit->setQuatite($quantite);
        $produit->setPrix($prix);
        $produit->setDescription($description);
        $categorie=$this->getDoctrine()->getRepository(Categorie::class)->find($idcateg);
        $produit->setCategorie($categorie);
        $errors = $validator->validate($produit);
        
        if (count($errors) > 0) {
            
    
            return $this->render('produit/add.html.twig',['errors'=>$errors]);
        }
    
        
        $em=$this->getDoctrine()->getManager();
        $em->persist($produit);
        $em->flush();
        return $this->redirectToRoute('listprod');

        
        
        
    }
    /**
     * @Route("/produit/list", name="listprod")
     */
    public function list()
    {
        
        $produits=$this->getDoctrine()->getRepository(Produit::class)->findAll();
        
        
        return $this->render('produit/list.html.twig',['produits'=>$produits]);
    }
    /**
     * @Route("/produit/show/{id}", name="showprod")
     */
    public function show($id)
    {
        
        $produit=$this->getDoctrine()->getRepository(Produit::class)->find($id);
        if (!$produit) {
            /*throw $this->createNotFoundException(
                'Produit inexistant pour id '.$id
            );*/
            return $this->render('produit/erreur.html.twig',['msg'=>'Le produit est inexistant']);
        }

        
        return $this->render('produit/show.html.twig',['p'=>$produit]);
    }
     /**
     * @Route("/produit/delete/{id}", name="deleteprod")
     */
    public function delete($id)
    {
        
        $produit=$this->getDoctrine()->getRepository(Produit::class)->find($id);
        if (!$produit) {
            throw $this->createNotFoundException(
                'Produit inexistant pour id '.$id
            );
        }
        
        $em=$this->getDoctrine()->getManager();
        $em->remove($produit);
        $em->flush();
        
        return $this->redirectToRoute('listprod');
    }
     /**
     * @Route("/produit/find/{pmin}/{pmax}", name="chercherprod")
     */
    public function chercher($pmin,$pmax,ProduitRepository $rep)
    {
        
        $produits=$rep->test($pmin,$pmax);
        
        
        return $this->render('produit/list.html.twig',['produits'=>$produits]);
    }
    /**
     * @Route("/test1", name="listprod1")
     */
    public function test()
    {
        
        $produits=$this->getDoctrine()->getRepository(Produit::class)->findAll();
        
        return new JsonResponse(json_encode( $produits));
        //return $this->render('produit/list.html.twig',['produits'=>$produits]);
    }
     /**
     * @Route("/produit/add2", name="add2produit")
     */
    public function add2(Request $request)
    {
        $produit=new Produit();
        $form = $this->createFormBuilder($produit)
            ->add('name', TextType::class)
            ->add('quatite', IntegerType::class)
            ->add('prix', IntegerType::class)
            ->add('description', TextType::class)
            ->add('categorie', EntityType::class,['class' => Categorie::class,              
                                                  'choice_label' => 'name',])
            ->add('save', SubmitType::class, ['label' => 'Ajout Produit'])
            ->getForm();
        
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
            
               $em=$this->getDoctrine()->getManager();
               $em->persist($produit);
               $em->flush();
               return $this->redirectToRoute('listprod');
            }
        
        return $this->render('produit/add2.html.twig', [
            'form' => $form->createView(),
        ]);
    }

}
