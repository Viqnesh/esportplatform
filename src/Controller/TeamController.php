<?php

namespace App\Controller;

use App\Entity\Team;
use App\Form\TeamType;
use App\Entity\Challenge;
use App\Entity\FixedMatch;
use App\Repository\TeamRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;

/**
 * @Route("/team")
 */
class TeamController extends AbstractController
{
    /**
     * @Route("/", name="app_team_index", methods={"GET"})
     */
    public function index(TeamRepository $teamRepository): Response
    {
        return $this->render('team/index.html.twig', [
            'teams' => $teamRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_team_new", methods={"GET", "POST"})
     */
    public function new(Request $request, TeamRepository $teamRepository, EntityManagerInterface $entityManager): Response
    {
        $team = new Team();
        $form = $this->createForm(TeamType::class, $team);
        $user = $entityManager->getRepository(User::class)->find($this->getUser()->getId());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $team->setOwner($this->getUser());
            $teamRepository->add($team, true);
            $user->setEquipe($team);
            $entityManager->flush();

            return $this->redirectToRoute('app_team_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('team/new.html.twig', [
            'team' => $team,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_team_show", methods={"GET"})
     */
    public function show(Team $team, EntityManagerInterface $entityManager): Response
    {

        return $this->render('team/show.html.twig', [
            'team' => $team
        ]);
    }

    /**
     * @Route("/matches/{id}", name="app_team_show_matches", methods={"GET"})
     */
    public function showMatches(Team $team, EntityManagerInterface $entityManager): Response
    {

        //$matches = $entityManager->getRepository(FixedMatch::class)->findBy($this->getUser()->getId());
        $matchmaking = $entityManager->getRepository(Challenge::class)->findBy(['User' => $this->getUser()->getId() ]);
        $query = $entityManager->createQuery(
            'SELECT c
            FROM App\Entity\FixedMatch c
            WHERE c.equipeA = :equipe OR c.equipeB = :equipe'
        )->setParameter('equipe', $team);

        // returns an array of Product objects
        $matches = $query->getResult();

        return $this->render('league/showMatches.html.twig', [
            'team' => $team, 'matches' => $matches , 'matchmaking' => $matchmaking
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_team_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Team $team, TeamRepository $teamRepository): Response
    {
        $form = $this->createForm(TeamType::class, $team);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $teamRepository->add($team, true);

            return $this->redirectToRoute('app_team_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('team/edit.html.twig', [
            'team' => $team,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_team_delete", methods={"POST"})
     */
    public function delete(Request $request, Team $team, TeamRepository $teamRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$team->getId(), $request->request->get('_token'))) {
            $teamRepository->remove($team, true);
        }

        return $this->redirectToRoute('app_team_index', [], Response::HTTP_SEE_OTHER);
    }
}
