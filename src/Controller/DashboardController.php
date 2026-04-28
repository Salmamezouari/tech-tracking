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
        $userRepo = $em->getRepository(User::class);
        $interRepo = $em->getRepository(Intervention::class);

        return $this->render('dashboard/index.html.twig', [

            'users_count' => $userRepo->count([]),
            'interventions_count' => $interRepo->count([]),

            'latest_users' => $userRepo->findBy([], ['id' => 'DESC'], 5),
            'latest_interventions' => $interRepo->findBy([], ['id' => 'DESC'], 10),
        ]);
    }
}