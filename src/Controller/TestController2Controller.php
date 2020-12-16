<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController2Controller extends AbstractController
{
    /**
     * @Route("/test/controller2", name="test_controller2")
     */
    public function index(): Response
    {
        return $this->render('test_controller2/index.html.twig', [
            'controller_name' => 'TestController2Controller',
        ]);
    }
}
