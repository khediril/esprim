<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ProduitController extends AbstractController
{
    /**
     * @Route("/produit/add/{name}/{quantite}/{prix}/{description}", name="produit")
     */
    public function add($name,$quantite,$prix,$description)
    {
        $produit=new Produit();
        $produit->setName($name);
        $produit->setQuatite($quantite);
        $produit->setPrix($prix);
        $produit->setDescription($description);

        $em=$this->getDoctrine()->getManager();
        $em->persist($produit);
        $em->flush();
        
        return $this->render('produit/add.html.twig',['prod'=>$produit]);
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
}
