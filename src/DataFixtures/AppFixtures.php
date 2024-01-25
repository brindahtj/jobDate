<?php

namespace App\DataFixtures;

use App\Entity\Application;
use Faker\Factory;
use App\Entity\Tag;
use App\Entity\User;
use App\Entity\Offer;
use App\Entity\UserProfil;
use App\Entity\ContractType;
use App\Entity\HomeSetting;
use App\Entity\UserEntreprise;
use Cocur\Slugify\Slugify;
use DateTimeImmutable;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraints\Length;

class AppFixtures extends Fixture
{


    public function load(ObjectManager $em): void
    {

        $tabImage = ['https://images.unsplash.com/photo-1529400971008-f566de0e6dfc?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D', 'https://images.unsplash.com/photo-1542626991-cbc4e32524cc?q=80&w=2069&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D', 'https://images.unsplash.com/photo-1521791136064-7986c2920216?q=80&w=2069&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D'];
        $sexe = ['men', 'women'];
        $status = ['Candidat', 'Professionnel'];
        $sexeFaker = ['male', 'female'];
        $contractTypes = [
            'CDD', 'CDI', 'Interim', 'Freelance', 'Stage', 'Alternance', 'Autre', 'stage alterné', 'Contrat de professionnalisation'
        ];
        $tags = [
            'PHP',
            'Symfony',
            'Javascript',
            'React',
            'Angular',
            'VueJS',
            'NodeJS',
            'Python',
            'Java',
            'C#',
            'C++',
            'Ruby',
            'HTML',
            'CSS',
            'SQL',
            'NoSQL',
            'MongoDB',
            'MySQL',
            'PostgreSQL',
            'Oracle',
            'MariaDB',
            'SQLite',
            'Git',
            'GitHub',
            'GitLab',
            'BitBucket',
            'Docker',
            'Kubernetes',
            'Linux',
            'Windows',
            'MacOS',
            'Android',
            'iOS',
            'AWS',
            'Azure',
            'Google Cloud',
            'Heroku',
            'Digital Ocean',
            'Vultr',
            'OVH',
            '1&1',
            'GoDaddy',
            'Namecheap'
        ];;
        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < 5; $i++) {
            $homeSetting = new HomeSetting();
            $homeSetting->setImage($faker->randomElement($tabImage))->setMessage($faker->sentence())->setCallToAction($faker->word());
            $em->persist($homeSetting);
        }

        foreach ($contractTypes as $item) {
            $random = mt_rand(0, count($contractTypes));
            $contractType = new ContractType();
            $contractType->setName($item);
            $em->persist($contractType);
        }

        foreach ($tags as $item) {
            $tag = new Tag();
            $tag->setName($item);
            $em->persist($tag);
        }

        for ($i = 0; $i < 100; $i++) {

            $random = mt_rand(0, 1);
            $user = new User();
            $user->setEmail($faker->email())->setStatus($status[$random])->setPassword(password_hash('Lilili', PASSWORD_DEFAULT));
            if ($status[$random] === 'Professionnel') {
                $user->setUsername($faker->company());
                $user->setRoles(['ROLE_PRO']);
            } else {
                $user->setUsername($faker->userName());
            }
            $em->persist($user);
        }
        $em->flush();

        $userProfils = $em->getRepository(User::class)->findByStatus('Candidat');
        foreach ($userProfils as $userProfil) {
            $random = mt_rand(0, 1);
            $profil = new UserProfil();
            $slugify = new Slugify();
            $profil
                ->setUser($userProfil)
                ->setFirstName($faker->firstName($sexeFaker[$random]))
                ->setLastName($faker->lastName())
                ->setPhoneNumber($faker->phoneNumber())
                ->setAddress($faker->address())
                ->setZipCode($faker->postcode())
                ->setCity($faker->city())
                ->setCountry($faker->country())
                ->setPicture('https://randomuser.me/api/portraits/' . $sexe[$random] . '/' . mt_rand(1, 99) . '.jpg')
                ->setPresentation($faker->realTextBetween($minNbChars = 160, $maxNbChars = 200, $indexSize = 2))
                ->setWebsite($faker->url())
                ->setJobSought($faker->jobTitle())
                ->setAvailability(true)
                ->setSlug($slugify->slugify($profil->getFirstName() . ' ' . $profil->getLastName()));

            $em->persist($profil);
        }


        $userEntreprises = $em->getRepository(User::class)->findByStatus('Professionnel');

        foreach ($userEntreprises as $userEntreprise) {
            $random = mt_rand(0, 1);
            $entreprise = new UserEntreprise();

            $entreprise
                ->setUser($userEntreprise)
                ->setName($faker->company())
                ->setPhoneNumber($faker->phoneNumber())
                ->setAddress($faker->address())
                ->setZipCode($faker->postcode())
                ->setCity($faker->city())
                ->setCountry($faker->country())
                ->setLogo('https://api.dicebear.com/7.x/initials/svg?seed=' . $entreprise->getName())
                ->setDescription($faker->realTextBetween($minNbChars = 160, $maxNbChars = 200, $indexSize = 2))
                ->setWebsite($faker->url())
                ->setActivityArea($faker->jobTitle())
                ->setSlug($slugify->slugify($entreprise->getName()));

            $em->persist($entreprise);
        }
        $em->flush();

        $recruteurs = $em->getRepository(UserEntreprise::class)->findAll();
        $tags = $em->getRepository(Tag::class)->findAll();

        $contractTypes = $em->getRepository(ContractType::class)->findAll();
        for ($i = 0; $i < 50; $i++) {
            $random = mt_rand(0, 1);
            $offer = new Offer();
            $offer
                ->setTitle($faker->jobTitle())
                ->setEntreprise($faker->randomElement($recruteurs))
                ->setShortDescription($faker->sentence())
                ->setSalary(mt_rand(30000, 100000))
                ->setLocation($faker->city())
                ->setContractType($faker->randomElement($contractTypes))
                ->setContent($faker->realTextBetween($minNbChars = 160, $maxNbChars = 200, $indexSize = 2));
            $randomTags = $faker->randomElements($tags, mt_rand(3, 8));


            foreach ($randomTags as $tag) {
                $offer->addTag($tag);
            }

            $em->persist($offer);
        }
        $em->flush();
        //Insertion de candidature
        $candidat = $em->getRepository(User::class)->findByStatus('Candidat');
        $offers = $em->getRepository(Offer::class)->findAll();
        for ($i = 0; $i < 200; $i++) {
            $application = new Application;

            $randomCandidat = $faker->randomElement($candidat);
            $randomOffer = $faker->randomElement($offers);
            $application
                ->setUser($randomCandidat)
                ->setStatus($faker->randomElement(['STATUS_PENDING', 'STATUS_ACCEPTED', 'STATUS_REFUSED']))
                ->setOffer($randomOffer)
                ->setEntreprise($randomOffer->getEntreprise())
                ->setMessage($faker->paragraph(mt_rand(1, 3)))
                ->setCreatedAt(new DateTimeImmutable());
            $em->persist($application);
        }
        //Création d'un compte admin 
        $user = new User();
        $user
            ->setEmail('brinda.hount@hotmail.com')
            ->setPassword(password_hash('Lilili', PASSWORD_DEFAULT))
            ->setStatus('Admin')->setRoles(['ROLE_ADMIN'])
            ->setUsername("Brinda")
            ->setIsVerified(true);
        $em->persist($user);

        $em->flush();
    }
}
