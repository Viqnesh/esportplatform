<?php

namespace App\Controller;

use App\Entity\Rewards;
use App\Form\RewardsType;
use App\Repository\RewardsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/rewards")
 */
class RewardsController extends AbstractController
{
    /**
     * @Route("/", name="app_rewards_index", methods={"GET"})
     */
    public function index(RewardsRepository $rewardsRepository): Response
    {
        return $this->render('rewards/index.html.twig', [
            'rewards' => $rewardsRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_rewards_new", methods={"GET", "POST"})
     */
    public function new(Request $request, RewardsRepository $rewardsRepository): Response
    {
        $reward = new Rewards();
        $form = $this->createForm(RewardsType::class, $reward);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $rewardsRepository->add($reward, true);

            return $this->redirectToRoute('app_rewards_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('rewards/new.html.twig', [
            'reward' => $reward,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_rewards_show", methods={"GET"})
     */
    public function show(Rewards $reward): Response
    {
        return $this->render('rewards/show.html.twig', [
            'reward' => $reward,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_rewards_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Rewards $reward, RewardsRepository $rewardsRepository): Response
    {
        $form = $this->createForm(RewardsType::class, $reward);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $rewardsRepository->add($reward, true);

            return $this->redirectToRoute('app_rewards_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('rewards/edit.html.twig', [
            'reward' => $reward,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_rewards_delete", methods={"POST"})
     */
    public function delete(Request $request, Rewards $reward, RewardsRepository $rewardsRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reward->getId(), $request->request->get('_token'))) {
            $rewardsRepository->remove($reward, true);
        }

        return $this->redirectToRoute('app_rewards_index', [], Response::HTTP_SEE_OTHER);
    }
}
