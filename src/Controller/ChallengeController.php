<?php

namespace App\Controller;

use App\Entity\Challenge;
use App\Entity\FixedMatch;
use App\Entity\Classement;
use App\Entity\Team;
use App\Entity\Scoresheet;
use App\Entity\League;
use App\Form\ChallengeType;
use App\Repository\ChallengeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @Route("/challenge")
 */
class ChallengeController extends AbstractController
{
    /**
     * @Route("/", name="app_challenge_index", methods={"GET"})
     */
    public function index(ChallengeRepository $challengeRepository): Response
    {
        return $this->render('challenge/index.html.twig', [
            'challenges' => $challengeRepository->findBy(['etat' => NULL]),
        ]);
    }

    /**
     * @Route("/new/{id}", name="app_challenge_new", methods={"GET", "POST"})
     */
    public function new(Request $request,int $id, ChallengeRepository $challengeRepository, EntityManagerInterface $entityManager): Response
    {
        $challenge = new Challenge();
        $league = $entityManager->getRepository(League::class)->find($id);
        $challenge->setUser($this->getUser());
        $challenge->setLeague($league);
        $form = $this->createForm(ChallengeType::class, $challenge);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $challengeRepository->add($challenge, true);
            return $this->redirectToRoute('app_league_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('challenge/new.html.twig', [
            'challenge' => $challenge,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_challenge_show", methods={"GET"})
     */
    public function show(Challenge $challenge): Response
    {
        return $this->render('challenge/show.html.twig', [
            'challenge' => $challenge,
        ]);
    }

    /**
     * @Route("/accept-challenge/{id}", name="app_accept_challenge", methods={"GET"})
     */
    public function acceptChallenge(Challenge $challenge, EntityManagerInterface $entityManager): Response
    {
        $challenge->setEtat("A");

        $fixedMatch = new FixedMatch() ;
        $scoresheet = new Scoresheet();
        $scoresheet2 = new Scoresheet();

        $fixedMatch->setEquipeA($challenge->getUser()->getEquipe());
        $fixedMatch->setEquipeB($this->getUser()->getEquipe());
        $fixedMatch->setState("Scheduled");
        $fixedMatch->setScore("0 - 0");
        $fixedMatch->setLeague($challenge->getLeague());
        $scoresheet->setTeam($challenge->getUser()->getEquipe());
        $scoresheet2->setTeam($this->getUser()->getEquipe());
        $scoresheet->setFixedMatch($fixedMatch);
        $scoresheet2->setFixedMatch($fixedMatch);

        $entityManager->persist($challenge);
        $entityManager->persist($fixedMatch);
        $entityManager->persist($scoresheet);
        $entityManager->persist($scoresheet2);
        $entityManager->flush();

        return $this->redirectToRoute('app_profile', [], Response::HTTP_SEE_OTHER);
    
    }
    /**
     * @Route("/league-signup/{id}", name="app_league_signup", methods={"GET"})
     */
    public function leagueSignup(int $id, EntityManagerInterface $entityManager): Response
    {
        $equipe = $entityManager->getRepository(Team::class)->find($this->getUser()->getEquipe()->getId());
        $league = $entityManager->getRepository(League::class)->find($id);
        $classement2 = new Classement();
        $classement2->setEquipe($equipe);
        $classement2->setLeague($league);
        $classement2->setL(0);
        $classement2->setW(0);
        $classement2->setMP(0);
        $entityManager->persist($classement2);
        $entityManager->flush();
        return $this->redirectToRoute('app_league_show', array(
            'id' => $league->getId()
        ), Response::HTTP_SEE_OTHER);
    }
    /**
     * @Route("/{id}/edit", name="app_challenge_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Challenge $challenge, ChallengeRepository $challengeRepository): Response
    {
        $form = $this->createForm(ChallengeType::class, $challenge);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $challengeRepository->add($challenge, true);

            return $this->redirectToRoute('app_challenge_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('challenge/edit.html.twig', [
            'challenge' => $challenge,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_challenge_delete", methods={"POST"})
     */
    public function delete(Request $request, Challenge $challenge, ChallengeRepository $challengeRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$challenge->getId(), $request->request->get('_token'))) {
            $challengeRepository->remove($challenge, true);
        }

        return $this->redirectToRoute('app_challenge_index', [], Response::HTTP_SEE_OTHER);
    }
}
