<?php

namespace App\Controller;

use App\Entity\UserEntreprise;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountController extends AbstractController
{
    #[Route('/account', name: 'app_account')]
    public function index(): Response
    {
        $user = $this->getUser();
        if ($user->getRoles()[0] === 'ROLE_ADMIN') {
            return $this->redirectToRoute('admin');
        }
        if ($user->getUserEntreprise() !== null || $user->getUserProfil() !== null) {
            if ($user->getRoles()[0] === 'ROLE_PRO') {
                return $this->redirectToRoute('app_user_entreprise_show', ['slug' => $user->getUserEntreprise()->getSlug()]);
            } elseif ($user->getRoles()[0] === 'ROLE_USER') {
                return $this->redirectToRoute('app_user_profil_show', ['slug' => $user->getUserProfil()->getSlug()]);
            }
        } else {
            if ($user->getRoles()[0] === 'ROLE_PRO') {
                return $this->redirectToRoute('app_user_entreprise');
            } elseif ($user->getRoles()[0] === 'ROLE_USER') {
                return $this->redirectToRoute('app_user_profil');
            }
        }
        return $this->render('account/index.html.twig', [
            'user' => $user,
        ]);
    }
}
