<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Entity\Service;
use App\Form\BookingFormType;
use App\Form\ServiceType;
use App\Repository\BookingRepository;
use App\Repository\CategoryRepository;
use App\Repository\ServiceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ServiceController extends AbstractController
{
    #[Route('/services', name: 'app_service_index')]
    public function index(ServiceRepository $serviceRepository): Response
    {
        $services = $serviceRepository->findAll();

        return $this->render('service/index.html.twig', [
            'services' => $services,
        ]);
    }

     #[Route('/services/show/{id}', name: 'app_service_show')]
    public function show(Service $service, Request $request, EntityManagerInterface $entityManager, BookingRepository $bookingRepository): Response
    {
        $booking = new Booking();
        $form = $this->createForm(BookingFormType::class, $booking);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

            $booking->setService($service);
            $booking->setUser($this->getUser());
            $dateStart = $booking->getDateStart();
            $duration = $service->getDuration();
            $dateEnd = (clone $dateStart)->modify("+{$duration} minutes");
            $booking->setDateEnd($dateEnd);

            $conflictingBookings = $bookingRepository->findConflictingBookings($dateStart, $dateEnd, $service->getId());
            if (count($conflictingBookings) > 0) {
                return $this->redirectToRoute('booking_fail');
            } else {
                $entityManager->persist($booking);
                $entityManager->flush();

                return $this->redirectToRoute('booking_success');
            }
        }

        return $this->render('service/show.html.twig', [
            'service' => $service,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/services/new', name: 'app_service_new')]
    public function create(Request $request, EntityManagerInterface $entityManager, CategoryRepository $categoryRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $form = $this->createForm(ServiceType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $service = $form->getData();
            $entityManager->persist($service);
            $entityManager->flush();

            return $this->redirectToRoute('app_service_index');
        }
        $category = $categoryRepository->findAll();

        return $this->render('service/create.html.twig', [
            'form' => $form->createView(),
            'categories' => $category
        ]);
    }
}