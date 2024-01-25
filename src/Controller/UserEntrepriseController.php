<?php

namespace App\Controller;

use Cocur\Slugify\Slugify;
use App\Entity\UserEntreprise;
use App\Form\UserEntrepriseType;
use App\Services\UploadFilesServices;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UserEntrepriseRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

#[Route('/account')]

class UserEntrepriseController extends AbstractController
{
    #[Route('/entreprise/profil/', name: 'app_user_entreprise')]
    public function index(Request $request, EntityManagerInterface $em, UploadFilesServices $uploadFilesServices): Response
    {
        $userEntreprise = new UserEntreprise();
        $slugify = new Slugify();
        $form = $this->createForm(UserEntrepriseType::class, $userEntreprise);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userEntreprise->setUser($this->getUser());
            $userEntreprise->setSlug($slugify->slugify($userEntreprise->getName()));
            $file = $form['logoEntreprise']->getData();
            if ($file) {
                $file_name = $uploadFilesServices->saveFileUpload($file);
                $userEntreprise->setLogo($file_name);
            } else {
                $userEntreprise->setLogo('default.png');
            }
            $em->persist($userEntreprise);
            $em->flush();
            $this->addFlash('success', 'Les informations du profil entreprise ont bien été enregistrés.');
            return $this->redirectToRoute('app_user_entreprise_show', ['slug' => $userEntreprise->getSlug()]);
        }

        return $this->render('user_entreprise/index.html.twig', [
            'userEntrepriseForm' => $form->createView(),
        ]);
    }
    #[Route('/entreprise/profil/{slug}', name: 'app_user_entreprise_show')]
    public function userEntreprise(UserEntreprise $userEntreprise): Response
    {
        return $this->render('user_entreprise/show.html.twig', [
            'userEntreprise' => $userEntreprise,
        ]);
    }

    #[Route('/entreprise/profil/edit/{slug}', name: 'app_user_entreprise_edit')]
    public function editUserEntreprise(string $slug, UserEntrepriseRepository $userEntrepriseRepository, Request $request, EntityManagerInterface $em, UploadFilesServices $uploadFilesServices): Response
    {
        $slugify = new Slugify();
        $userEntreprise = $userEntrepriseRepository->findOneBy(['slug' => $slug]);
        $user = $this->getUser();
        if ($user !== $userEntreprise->getUser()) {
            return $this->redirectToRoute('app_user_entreprise_show', ['slug' => $userEntreprise->getSlug()]);
        }
        $form = $this->createForm(UserEntrepriseType::class, $userEntreprise);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $userEntreprise->setUser($this->getUser());
            $userEntreprise->setSlug($slugify->slugify($userEntreprise->getName()));
            $file = $form['logoEntreprise']->getData();
            if ($file) {
                $file_name = $uploadFilesServices->saveFileUpload($file, $userEntreprise->getLogo());
                $userEntreprise->setLogo($file_name);
            } else {
                $userEntreprise->setLogo('default.png');
            }


            $em->flush();
            $this->addFlash('success', 'Les informations du profil entreprise ont bien été modifiés.');
            return $this->redirectToRoute('app_user_entreprise_show', ['slug' => $userEntreprise->getSlug()]);
        }

        return $this->render('user_Entreprise/edit.html.twig', [
            'userEntrepriseForm' => $form->createView(),
        ]);
    }

    #[Route('/entreprise/profil/delete/{slug}', name: 'app_user_entreprise_delete')]
    public function userEntrepriseDelete(string $slug, UserEntrepriseRepository $userEntrepriseRepository, EntityManagerInterface $em, UploadFilesServices $uploadFilesServices, TokenStorageInterface $tokenStorageInterface, Session $session): Response
    {
        $user = $this->getUser();
        $userEntreprise = $userEntrepriseRepository->findOneBy(['slug' => $slug]);

        if ($user !== $userEntreprise->getUser()) {
            return $this->redirectToRoute('app_user_entreprise_show', ['slug' => $userEntreprise->getSlug()]);
        }
        // Suppression de l'image du profil utilisateur
        $uploadFilesServices->deleteFileUpload($userEntreprise->getLogo());

        $em->remove($userEntreprise);
        $em->flush();

        // tokenStorageInterface permet de déconnecter l'utilisateur et de supprimer son token de session
        $tokenStorageInterface->setToken(null);

        // On supprime la session(invalidation de la session)
        $session->invalidate();
        $this->addFlash('success', 'Votre compte a bien été supprimé.');

        return $this->redirectToRoute('app_home');
    }
}
