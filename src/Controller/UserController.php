<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/users', name: 'users_list')]
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        $search = $request->query->get('search');

        $repo = $em->getRepository(User::class);

        if ($search) {
            $users = $repo->createQueryBuilder('u')
                ->where('u.name LIKE :search OR u.email LIKE :search')
                ->setParameter('search', "%$search%")
                ->getQuery()
                ->getResult();
        } else {
            $users = $repo->findAll();
        }

        return $this->render('user/list.html.twig', [
            'users' => $users
        ]);
    }

    #[Route('/users/add', name: 'user_add', methods: ['POST'])]
    public function add(Request $request, EntityManagerInterface $em): Response
    {
        $user = new User();
        $user->setName($request->request->get('name'));
        $user->setEmail($request->request->get('email'));
        $user->setPassword($request->request->get('password'));
        $user->setRole('user');

        $em->persist($user);
        $em->flush();

        return $this->redirectToRoute('users_list');
    }

    #[Route('/users/edit/{id}', name: 'user_edit')]
    public function edit(User $user, Request $request, EntityManagerInterface $em): Response
    {
        if ($request->isMethod('POST')) {
            $user->setName($request->request->get('name'));
            $user->setEmail($request->request->get('email'));
            $user->setPassword($request->request->get('password'));
            $user->setRole($request->request->get('role'));

            $em->flush();

            return $this->redirectToRoute('users_list');
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user
        ]);
    }

    #[Route('/users/delete/{id}', name: 'user_delete')]
    public function delete(User $user, EntityManagerInterface $em): Response
    {
        $em->remove($user);
        $em->flush();

        return $this->redirectToRoute('users_list');
    }
}