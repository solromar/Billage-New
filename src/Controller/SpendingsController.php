<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SpendingsController extends AbstractController
{
    #[Route('/spendings', name: 'app_spendings')]
    public function index(): Response
    {
        return $this->render('spendings/index.html.twig', [
            'controller_name' => 'SpendingsController',
        ]);
    }
}
