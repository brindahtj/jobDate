<?php

namespace App\Controller;

use App\Entity\UserProfil;
use Cocur\Slugify\Slugify;
use App\Form\UserProfilType;
use App\Repository\UserProfilRepository;
use App\Services\UploadFilesServices;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

#[Route('/account')]
class UserProfilController extends AbstractController
{
    #[Route('/user/profil', name: 'app_user_profil')]
    public function index(Request $request, EntityManagerInterface $em, UploadFilesServices $uploadFilesServices): Response
    {
        $userProfil = new UserProfil();
        $slugify = new Slugify();
        $form = $this->createForm(UserProfilType::class, $userProfil);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userProfil->setUser($this->getUser());
            $userProfil->setSlug($slugify->slugify($userProfil->getFirstName() . ' ' . $userProfil->getLastName()));
            $file = $form['imageFile']->getData();
            if ($file) {
                $file_name = $uploadFilesServices->saveFileUpload($file);
                $userProfil->setPicture($file_name);
            } else {
                $userProfil->setPicture('default.png');
            }
            $em->persist($userProfil);
            $em->flush();
            $this->addFlash('success', 'Les informations du profil ont bien été enregistrés.');
            return $this->redirectToRoute('app_user_profil_show', ['slug' => $userProfil->getSlug()]);
        }


        return $this->render('user_profil/index.html.twig', [
            'userProfilForm' => $form->createView(),
        ]);
    }

    #[Route('/user/profil/{slug}', name: 'app_user_profil_show')]
    public function userProfil(string $slug, UserProfilRepository $userProfilRepository): Response
    //pour recupere user profil-> soit cette façon
    {
        $user = $this->getUser();
        $userProfil = $userProfilRepository->findOneBy(['slug' => $slug]);
        $connectedUSerProfil = $userProfilRepository->findOneBy(['user' => $user]);
        if (!$userProfil) {
            return $this->redirectToRoute('app_user_profil_show', ['slug' => $connectedUSerProfil->getSlug()]);
        }
        if ($userProfil->getSlug() !== $userProfil->getSlug()) {
            return $this->redirectToRoute('app_user_profil_show', ['slug' => $connectedUSerProfil->getSlug()]);
        }

        return $this->render('user_profil/show.html.twig', [
            'userProfil' => $userProfil,
        ]);
    }
    #[Route('/user/profil/candidatures/{slug}', name: 'app_user_profil_candidatures')]
    public function candidatures(string $slug, UserProfilRepository $userProfilRepository): Response
    {
        $user = $this->getUser();
        $applications = $user->getApplications();
        return $this->render('user_profil/candidatures.html.twig', [
            'applications' => $applications,
        ]);
    }


    #[Route('/user/profil/edit/{slug}', name: 'app_user_profil_edit')]
    public function editUserProfil(string $slug, UserProfilRepository $userProfilRepository, Request $request, EntityManagerInterface $em, UploadFilesServices $uploadFilesServices): Response
    {
        //->soit cette façon
        $slugify = new Slugify();
        $userProfil = $userProfilRepository->findOneBy(['slug' => $slug]);
        $user = $this->getUser();
        if ($user !== $userProfil->getUser()) {
            return $this->redirectToRoute('app_user_profil_show', ['slug' => $userProfil->getSlug()]);
        }
        $form = $this->createForm(UserProfilType::class, $userProfil);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $userProfil->setSlug($slugify->slugify($userProfil->getFirstName() . ' ' . $userProfil->getLastName()));
            $file = $form['imageFile']->getData();
            if ($file) {
                $file_name = $uploadFilesServices->saveFileUpload($file, $userProfil->getPicture());
                $userProfil->setPicture($file_name);
            } else {
                $userProfil->setPicture('default.png');
            }
            $em->flush();
            $this->addFlash('success', 'Les informations du profil ont bien été modifiés.');
            return $this->redirectToRoute('app_user_profil_show', ['slug' => $userProfil->getSlug()]);
        }

        return $this->render('user_profil/edit.html.twig', [
            'userProfilForm' => $form->createView(),
        ]);
    }

    #[Route('/user/profil/delete/{slug}', name: 'app_user_profil_delete')]
    public function userProfilDelete(string $slug, UserProfilRepository $userProfilRepository,  EntityManagerInterface $em, TokenStorageInterface $tokenStorageInterface, Session $session, UploadFilesServices $uploadFilesServices): Response
    {
        $user = $this->getUser();
        $userProfil = $userProfilRepository->findOneBy(['slug' => $slug]);

        if ($user !== $userProfil->getUser()) {
            return $this->redirectToRoute('app_user_profil_show', ['slug' => $userProfil->getSlug()]);
        }

        // Suppression de l'image du profil utilisateur
        $uploadFilesServices->deleteFileUpload($userProfil->getPicture());

        $em->remove($userProfil);
        $em->flush();

        // tokenStorageInterface permet de déconnecter l'utilisateur et de supprimer son token de session
        $tokenStorageInterface->setToken(null);

        // On supprime la session(invalidation de la session)
        $session->invalidate();
        $this->addFlash('success', 'Votre compte a bien été supprimé.');

        return $this->redirectToRoute('app_home');
    }
    #[Route('/user/profil/candidatures/{id}/profil/{slug}', name: 'app_user_profil_candidatures_detail')]

    public function candidaturesDetail(string $slug, UserProfilRepository $userProfilRepository): Response
    {
        $user = $this->getUser();
        $applications = $user->getApplications();
    }
}
