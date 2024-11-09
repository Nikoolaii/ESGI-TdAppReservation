<?php

namespace App\Controller;

use App\Form\ServiceType;
use App\Repository\ServiceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    public function show(ServiceRepository $serviceRepository, int $id): Response
    {
        $service = $serviceRepository->find($id);

        if (!$service) {
            throw $this->createNotFoundException('The service does not exist');
        }

        return $this->render('service/show.html.twig', [
            'service' => $service,
        ]);
    }

    #[Route('/services/new', name: 'app_service_new')]
    public function create(): Response
    {
//        Check if user is logged in
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $form = $this->createForm(ServiceType::class);

        if ($form->isSubmitted() && $form->isValid()) {
            $service = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($service);
            $entityManager->flush();

            return $this->redirectToRoute('app_service_index');
        }
        return $this->render('service/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}