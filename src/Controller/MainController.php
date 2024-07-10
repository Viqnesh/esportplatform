<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\FixedMatch;
class MainController extends AbstractController
{
    /**
     * @Route("/", name="app_main")
     */
    public function index(): Response
    {
        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }

    /**
     * @Route("/profile", name="app_profile")
     */
    public function profile(EntityManagerInterface $entityManager): Response
    {
        $fixedMatchA = $entityManager->getRepository(FixedMatch::class)->findBy(['equipeA' => $this->getUser()->getEquipe() ]);
        $fixedMatchB = $entityManager->getRepository(FixedMatch::class)->findBy(['equipeB' => $this->getUser()->getEquipe() ]);

        return $this->render('main/profile.html.twig', [ 'fixedMatchA' => $fixedMatchA, 'fixedMatchB' => $fixedMatchB ] );
    }

    

}
