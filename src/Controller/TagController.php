<?php

namespace App\Controller;

use App\Entity\Tag;
use App\Form\TagFormType;
use App\Repository\TagRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TagController extends AbstractController
{
    #[Route('/tag', name: 'app_tag')]
    public function index(Request $request, EntityManagerInterface $em, TagRepository $tagRepository): Response
    {
        $tag = new Tag();
        $user = $this->getUser();
        $data = $tagRepository->findAll();

        if ($user->getRoles()[0] !== 'ROLE_PRO') {
            return $this->redirectToRoute('app_account');
        }
        $form = $this->createForm(TagFormType::class, $tag);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($tag);
            $em->flush();
            return $this->redirectToRoute('app_tag');
        }
        return $this->render('tag/index.html.twig', [
            'tagForm' => $form->createView(),
            'data' => $data

        ]);
    }
}
