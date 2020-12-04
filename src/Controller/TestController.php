<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    /**
     * @Route("/",name="index")
     *  */
    public function index()
    {
        return $this->render('base.html.twig');
    }

    /**
     * @Route("/test",name="test")
     *  */
    public function test(ProductRepository $productRepository )
    {
        $products = $productRepository->findAll();
 
        return $this->render("test.html.twig", ['products' => $products]);
       // return $this->render("test.html.twig", ['products' => $products, 'category' => []]);
    }

    /**
     * http://127.0.0.1:8000/category
     * @Route("/category",name="category")
     *  */
    public function category(CategoryRepository $categoryRepository )
    {
        $category = $categoryRepository->findAll();
 
        return $this->render("category.html.twig", ['category' => $category]);
    }
}
