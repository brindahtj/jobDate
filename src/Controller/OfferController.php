<?php

namespace App\Controller;

use App\Entity\Offer;
use App\Form\ApplyStatusType;
use App\Form\OfferType;
use App\Repository\ApplicationRepository;
use App\Repository\OfferRepository;
use App\Repository\UserEntrepriseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/account')]

class OfferController extends AbstractController
{
    #[Route('/entreprise/offer/', name: 'app_offer')]
    public function index(OfferRepository $offerRepository): Response
    {
        $user = $this->getUser();

        $company = $user->getUserEntreprise();
        $data = $offerRepository->findByEntreprise($company);

        return $this->render('user_entreprise/offer/index.html.twig', ['data' => $data]);
    }

    #[Route('/entreprise/offer/new', name: 'app_offer_create')]
    public function show(UserEntrepriseRepository $userEntrepriseRepository, Request $request, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        $company = $user->getUserEntreprise();
        if ($company === null) {

            return $this->redirectToRoute('app_user_entreprise');
        }
        $offer = new Offer();
        $form = $this->createForm(OfferType::class, $offer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $offer->setEntreprise($company);
            $em->persist($offer);
            $em->flush();
            notyf()->position('x', 'right')->position('y', 'top')->addSuccess('L\'offre a bien été créée.');
            return $this->redirectToRoute('app_offer');
        }
        return $this->render('user_entreprise/offer/create.html.twig', [
            'offerForm' => $form->createView(),

        ]);
    }
    #[Route('/entreprise/offer/{slug}', name: 'app_offer_detail')]
    public function detail(string $slug, OfferRepository $offerRepository, ApplicationRepository $applicationRepository): Response
    {
        $user = $this->getUser();
        $offer = $offerRepository->findOneBy(['slug' => $slug]);
        $candidates = $applicationRepository->findBy(['offer' => $offer]);
        $company = $user->getUserEntreprise();
        if (!$company) {
            return $this->redirectToRoute('app_user_entreprise');
        }
        if (!$offer) {
            return $this->redirectToRoute('app_offer');
        }
        if ($offer->getEntreprise() !== $company) {
            return $this->redirectToRoute('app_offer');
        }
        return $this->render('user_entreprise/offer/detail.html.twig', ['offer' => $offer, 'candidates' => $candidates]);
    }
    #[Route('/entreprise/offer/edit/{slug}', name: 'app_offer_edit')]
    public function edit(string $slug,  OfferRepository $offerRepository, Request $request, EntityManagerInterface $em): Response
    {

        $offer = $offerRepository->findOneBy(['slug' => $slug]);
        $form = $this->createForm(OfferType::class, $offer);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            notyf()->position('x', 'right')->position('y', 'top')->addSuccess('L\'offre a bien été modifiée.');
            return $this->redirectToRoute('app_offer');
        }

        return $this->render('user_entreprise/offer/edit.html.twig', ['offerForm' => $form->createView()]);
    }

    #[Route('/entreprise/offer/delete/{slug}', name: 'app_offer_delete')]
    public function delete(string $slug,  OfferRepository $offerRepository, EntityManagerInterface $em): Response
    {
        $offer = $offerRepository->findOneBy(['slug' => $slug]);
        $user = $this->getUser();
        $company = $user->getUserEntreprise();
        if ($offer->getEntreprise() !== $company) {
            return $this->redirectToRoute('app_offer');
        }
        $em->remove($offer);
        $em->flush();
        notyf()->position('x', 'right')->position('y', 'top')->addSuccess('L\'offre a bien été supprimée.');
        return $this->redirectToRoute('app_offer');
    }

    #[Route('/entreprise/offer/{slug}/candidate/{id}', name: 'app_offer_candidate')]
    public function getCandidate(string $id, string $slug, Request $request, EntityManagerInterface $em,  OfferRepository $offerRepository, ApplicationRepository $applicationRepository): Response
    {
        $offer = $offerRepository->findOneBy(['slug' => $slug]);

        $candidate = $applicationRepository->findOneBy(['id' => $id]);
        $form = $this->createForm(ApplyStatusType::class, $candidate);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            notyf()->position('x', 'right')->position('y', 'top')->addSuccess('La candidature a bien été modifiée.');
            return $this->redirectToRoute('app_offer_detail', ['slug' => $offer->getSlug()]);
        }
        return $this->render('user_entreprise/offer/candidate.html.twig', ['candidate' => $candidate, 'statusForm' => $form->createView()]);
    }
}
