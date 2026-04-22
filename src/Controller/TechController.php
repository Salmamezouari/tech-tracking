<?php

namespace App\Controller;

use App\Entity\Intervention;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TechController extends AbstractController
{
    #[Route('/mes-interventions', name: 'tech_interventions')]
    public function index(EntityManagerInterface $em): Response
    {
        $user = $this->getUser();

        $interventions = $em->getRepository(Intervention::class)
            ->findBy(['assignedTechnician' => $user]);

        return $this->render('intervention/tech.html.twig', [
            'interventions' => $interventions
        ]);
    }
}