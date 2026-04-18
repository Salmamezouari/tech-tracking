<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Intervention;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $users = [];

        $names = [
            'Ali Ben', 'Sara Trabelsi', 'Omar Haddad', 'Yassine Mejri',
            'Ines Ben Ali', 'Mohamed Ali', 'Amira Saidi', 'Khalil Jaziri',
            'Nour Ben Salah', 'Firas Kacem', 'Salma Ben', 'Ahmed Tounsi',
            'Maya Hamdi', 'Hedi Chatti', 'Rania Bensaid', 'Walid Jlassi',
            'Marwa Gharbi', 'Aymen Feki', 'Nader Kraiem', 'Chahd Maalej'
        ];

        // USERS
        foreach ($names as $index => $name) {
            $user = new User();
            $user->setName($name);
            $user->setEmail("user$index@test.com");
            $user->setPassword("1234");
            $user->setRole($index < 15 ? "technician" : "admin");

            $manager->persist($user);
            $users[] = $user;
        }

        $clients = ['Orange', 'SFR', 'Bouygues', 'Free'];
        $statuses = ['pending', 'in_progress', 'done'];

        // INTERVENTIONS
        for ($i = 1; $i <= 20; $i++) {
            $intervention = new Intervention();
            $intervention->setTitle("Installation Fibre #$i");
            $intervention->setDescription("Intervention chez client");
            $intervention->setStatus($statuses[array_rand($statuses)]);
            $intervention->setClientName($clients[array_rand($clients)]);
            $intervention->setAddress("Paris " . rand(1, 20));
            $intervention->setScheduledDate(new \DateTime("+".rand(0,5)." days"));
            $intervention->setAssignedTechnician($users[array_rand($users)]);

            $manager->persist($intervention);
        }

        $manager->flush();
    }
}