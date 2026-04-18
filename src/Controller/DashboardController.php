<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Intervention;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'dashboard')]
    public function index(EntityManagerInterface $em): Response
    {
        return $this->render('dashboard/index.html.twig', [
            'users' => $em->getRepository(User::class)->count([]),
            'interventions' => $em->getRepository(Intervention::class)->count([]),
        ]);
    }
}