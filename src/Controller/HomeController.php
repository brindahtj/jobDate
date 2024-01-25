<?php

namespace App\Controller;

use App\Form\PostulerType;
use App\Entity\Application;
use App\Entity\HomeSetting;
use App\Repository\OfferRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ApplicationRepository;
use App\Repository\TagRepository;
use App\Repository\UserEntrepriseRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(EntityManagerInterface $em, OfferRepository $offerRepository, UserEntrepriseRepository $userEntrepriseRepository): Response
    {

        $settings = $em->getRepository(HomeSetting::class)->findAll();
        $offers = $offerRepository->findBy([], ['createdAt' => 'DESC'], 6);
        $entreprises = $userEntrepriseRepository->findBy([], ['id' => 'DESC'], 4);

        return $this->render('home/index.html.twig', [
            'settings' => $settings,
            'offers' => $offers,
            'entreprises' => $entreprises
        ]);
    }
    #[Route('/offre-emploi', name: 'app_offre_emploi')]
    public function getOffers(EntityManagerInterface $em, OfferRepository $offerRepository, UserEntrepriseRepository $userEntrepriseRepository, Request $request, TagRepository $tagRepository): Response
    {

        // $offers = $offerRepository->findBy([], ['createdAt' => 'DESC']);
        $tags = $tagRepository->findAll();

        $page = $request->query->get('page', 1);
        $offers = $offerRepository->findPaginatedoffers($page, 8);
        return $this->render('home/offers.html.twig', [
            'offers' => $offers,
            'tags' => $tags
        ]);
    }
    #[Route('/offre-emploi/{id}', name: 'app_offre_emploi_detail')]
    public function getOneOffer(string $id, EntityManagerInterface $em, OfferRepository $offerRepository, ApplicationRepository $applicationRepository, Request $request): Response
    {
        $offre = $offerRepository->findOneBy(['id' => $id]);
        if (!$offre) {
            throw $this->createNotFoundException("L'offre demandée n'existe pas");
        }
        $user = $this->getUser();


        $existingsApplication = $applicationRepository->findOneBy(
            ['offer' => $offre, 'user' => $user],

        );

        if ($existingsApplication) {
            notyf()
                ->position('x', 'right')
                ->position('y', 'top')
                ->addWarning('Vous avez déjà postulé à cette offre');
        }

        $application = new Application;
        $form = $this->createForm(PostulerType::class, $application);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $application->setUser($user);
            $application->setOffer($offre);
            $application->setCreatedAt(new \DateTimeImmutable());
            $application->setMessage($form->get('message')->getData());
            $application->setEntreprise($offre->getEntreprise());
            $application->setStatus('STATUS_PENDING');
            $em->persist($application);
            $em->flush();


            notyf()
                ->position('x', 'right')
                ->position('y', 'top')
                ->addSuccess('Votre candidature a bien été envoyé ');
            return $this->redirectToRoute('app_offre_emploi_detail', ['id' => $id]);
        }


        return $this->render('home/detail.html.twig', ['offre' => $offre, 'form' => $form->createView(), 'existingsApplication' => $existingsApplication]);
    }
    #[Route('/entreprises', name: 'app_entreprise')]
    public function entreprise(EntityManagerInterface $em, OfferRepository $offerRepository, UserEntrepriseRepository $userEntrepriseRepository): Response
    {

        $entreprises = $userEntrepriseRepository->findBy([], ['id' => 'DESC']);

        return $this->render('home/index.html.twig', [
            'entreprises' => $entreprises
        ]);
    }
}
