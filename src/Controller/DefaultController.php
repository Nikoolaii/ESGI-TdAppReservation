<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'app_default')]
    public function index(): Response
    {
        $user = $this->getUser();
        if (!$user) {
            $bookings = [];
        } else {
            $bookings = $user->getBookings();
        }

        return $this->render('default/index.html.twig', [
            'controller_name' => 'DefaultController',
            'bookings' => $bookings
        ]);
    }
}
