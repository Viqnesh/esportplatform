<?php

namespace App\Controller;

use App\Entity\League;
use App\Form\LeagueType;
use App\Entity\Classement;
use App\Entity\FixedMatch;
use App\Entity\Team;
use App\Entity\Rewards;
use App\Entity\Challenge;
use App\Repository\LeagueRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @Route("/league")
 */
class LeagueController extends AbstractController
{
    /**
     * @Route("/", name="app_league_index", methods={"GET"})
     */
    public function index(LeagueRepository $leagueRepository): Response
    {
        return $this->render('league/index.html.twig', [
            'leagues' => $leagueRepository->findAll(),
        ]);
    }
    /**
     * @Route("/rules/{id}", name="app_league_rules", methods={"GET"})
     */
    public function showRules(League $league): Response
    {
        return $this->render('league/rules.html.twig', [
            'league' => $league,
        ]);
    }

    /**
     * @Route("/rewards/{id}", name="app_league_rewards", methods={"GET"})
     */
    public function showRewards(League $league, EntityManagerInterface $entityManager): Response
    {
        
        $rewards = $entityManager->getRepository(Rewards::class)->findBy(['league' => $league]);
        return $this->render('league/rewards.html.twig', [
            'league' => $league, 'rewards' => $rewards
        ]);
    }


    /**
     * @Route("/incoming-matches/{id}", name="app_league_incoming_matches", methods={"GET"})
     */
    public function showRMatchesbyLeague(League $league, EntityManagerInterface $entityManager): Response
    {
        
        $matches = $entityManager->getRepository(FixedMatch::class)->findBy(['league' => $league]);
        $availableMatches = $entityManager->getRepository(Challenge::class)->findBy([
            'league' => $league,
            'etat' => NULL,
        ]);
        $inscrit = TRUE ;
        $classement = $entityManager->getRepository(Classement::class)->findBy(['equipe' => $this->getUser()->getEquipe() , 'league' => $league ]);
        if(empty($classement)) {
            $inscrit = FALSE ;
        }
        
        return $this->render('league/incoming.html.twig', [
            'league' => $league, 'matches' => $matches , 'availableMatches' => $availableMatches , 'inscrit' => $inscrit
        ]);
    }

    /**
     * @Route("/new", name="app_league_new", methods={"GET", "POST"})
     */
    public function new(Request $request, LeagueRepository $leagueRepository): Response
    {
        $league = new League();
        $form = $this->createForm(LeagueType::class, $league);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $leagueRepository->add($league, true);

            return $this->redirectToRoute('app_league_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('league/new.html.twig', [
            'league' => $league,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_league_show", methods={"GET"})
     */
    public function show(League $league, EntityManagerInterface $entityManager): Response
    {
        $classement = $entityManager->getRepository(Classement::class)->findBy(['league' => $league ] , ['pts' => 'DESC'] );
        if ($this->getUser()) {
            $equipe = $entityManager->getRepository(Team::class)->find($this->getUser()->getEquipe()->getId());
            $classement2 = $entityManager->getRepository(Classement::class)->findBy(['equipe' => $this->getUser()->getEquipe() , 'league' => $league ]);
        }
        $inscrit = TRUE ;
        if(empty($classement2)) {
            $inscrit = FALSE ;
        }
        return $this->render('league/show.html.twig', [
            'league' => $league, 'classements' => $classement , 'inscrit' => $inscrit 
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_league_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, League $league, LeagueRepository $leagueRepository): Response
    {
        $form = $this->createForm(LeagueType::class, $league);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $leagueRepository->add($league, true);

            return $this->redirectToRoute('app_league_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('league/edit.html.twig', [
            'league' => $league,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_league_delete", methods={"POST"})
     */
    public function delete(Request $request, League $league, LeagueRepository $leagueRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$league->getId(), $request->request->get('_token'))) {
            $leagueRepository->remove($league, true);
        }

        return $this->redirectToRoute('app_league_index', [], Response::HTTP_SEE_OTHER);
    }
}
