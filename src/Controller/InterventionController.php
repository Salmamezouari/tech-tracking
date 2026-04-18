<?php

namespace App\Controller;

use App\Entity\Intervention;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class InterventionController extends AbstractController
{
    #[Route('/interventions', name: 'interventions_list')]
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        $search = $request->query->get('search');

        $repo = $em->getRepository(Intervention::class);

        if ($search) {
            $interventions = $repo->createQueryBuilder('i')
                ->where('i.title LIKE :search OR i.clientName LIKE :search')
                ->setParameter('search', "%$search%")
                ->getQuery()
                ->getResult();
        } else {
            $interventions = $repo->findAll();
        }

        return $this->render('intervention/list.html.twig', [
            'interventions' => $interventions,
            'users' => $em->getRepository(User::class)->findAll()
        ]);
    }

    #[Route('/interventions/add', name: 'intervention_add', methods: ['POST'])]
    public function add(Request $request, EntityManagerInterface $em): Response
    {
        $i = new Intervention();

        $i->setTitle($request->request->get('title'));
        $i->setDescription($request->request->get('description'));
        $i->setClientName($request->request->get('client_name'));
        $i->setAddress($request->request->get('address'));
        $i->setStatus($request->request->get('status') ?? 'pending');

        $i->setScheduledDate(new \DateTime($request->request->get('scheduled_date')));

        $techId = $request->request->get('tech');
        if ($techId) {
            $tech = $em->getRepository(User::class)->find($techId);
            $i->setAssignedTechnician($tech);
        }

        $em->persist($i);
        $em->flush();

        return $this->redirectToRoute('interventions_list');
    }

    #[Route('/interventions/edit/{id}', name: 'intervention_edit')]
    public function edit(Intervention $i, Request $request, EntityManagerInterface $em): Response
    {
        if ($request->isMethod('POST')) {

            $i->setTitle($request->request->get('title'));
            $i->setDescription($request->request->get('description'));
            $i->setClientName($request->request->get('client_name'));
            $i->setAddress($request->request->get('address'));
            $i->setStatus($request->request->get('status'));

            $em->flush();

            return $this->redirectToRoute('interventions_list');
        }

        return $this->render('intervention/edit.html.twig', [
            'intervention' => $i
        ]);
    }

    #[Route('/interventions/delete/{id}', name: 'intervention_delete')]
    public function delete(Intervention $i, EntityManagerInterface $em): Response
    {
        $em->remove($i);
        $em->flush();

        return $this->redirectToRoute('interventions_list');
    }
}