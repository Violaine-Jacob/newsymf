<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class ProductController extends AbstractController
{
    /**
     * @Route("/detailProduit/{id}", name="detailProduit")
     */
    public function index(Product $product): Response
    {
        return $this->render('product/detailProduit.html.twig', [
            'product' => $product,
        ]);
    }

    /**
     * @Route("/product/add",name="ajoutProduit")
     */
    public function addProduct(KernelInterface $appKernel, Request $request, EntityManagerInterface $em, SluggerInterface $slugger): Response
    {
        $path  = $appKernel->getProjectDir() . '/public';
        $path = $this-> getParameter('app.dir.public') . '/img';
        $product = new Product;
        $form = $this->createForm(ProductFormType::class, $product);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $product->setSlug($slugger->slug($product->getName()));

            $file = $form['img']->getData();

            if  ($file) {
                //recup. le nom du fichier sans extension
                $originalFileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $newFileName = $originalFileName . '-' . uniqid() . '.' . $file->guessExtension();
                //set nom dans la proprieté img
                $product->setImg($newFileName);

                //Deplacer le fichier dans le repertoire public + sous repertoire
                try{
                    
                    $file->move($path, $newFileName);
                } catch (FileException $e){
                    echo $e ->getMessage();
                }
            }

            $em->persist($product);
            $em->flush();

            return $this->redirectToRoute('success');
        }

        return $this->render('product/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/product/edit/{id}",name="editProduit")
     */
    public function editProduct(Request $request, EntityManagerInterface $em, $id): Response
    {
        $path = $this-> getParameter('app.dir.public') . '/img';

        $product = $em->getRepository(Product::class)->find($id);
        $form = $this->createForm(ProductFormType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $file = $form['img']->getData();

            if  ($file) {
                //recup. le nom du fichier sans extension
                $originalFileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $newFileName = $originalFileName . '-' . uniqid() . '.' . $file->guessExtension();
                //set nom dans la proprieté img
                $product->setImg($newFileName);

                //Deplacer le fichier dans le repertoire public + sous repertoire
                try{
                    
                    $file->move($path, $newFileName);
                } catch (FileException $e){
                    echo $e ->getMessage();
                }
            }
        }

        return $this->render('product/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/product/delete/{id}",name="deleteProduit")
     */
    public function deleteProduct(Product $product, EntityManagerInterface $em)
    {
        // public function deleteProduct(ProductRepository $productRepository, $id, EntityManagerInterface $em)

        //$product = $productRepository->find($id);
        // paramConverter
        $em->remove($product);
        $em->flush();

        return $this->redirectToRoute('success');
    }
}
