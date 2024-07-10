<?php

namespace App\Controller;

use App\Entity\Scoresheet;
use App\Entity\FixedMatch;
use App\Entity\Tribunal;
use App\Entity\Topic;
use App\Entity\Classement;
use App\Entity\MessageTopic;
use App\Form\ScoresheetEquipeAType;
use App\Form\ScoresheetEquipeBType;
use App\Repository\ScoresheetRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @Route("/scoresheet")
 */
class ScoresheetController extends AbstractController
{
    /**
     * @Route("/", name="app_scoresheet_index", methods={"GET"})
     */
    public function index(ScoresheetRepository $scoresheetRepository): Response
    {
        return $this->render('scoresheet/index.html.twig', [
            'scoresheets' => $scoresheetRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new-team/{id}", name="app_scoresheet_team_new", methods={"GET", "POST"})
     */
    public function new(Request $request, int $id, ScoresheetRepository $scoresheetRepository, EntityManagerInterface $entityManager): Response
    {
        $fixedMatch = $entityManager->getRepository(FixedMatch::class)->find($id);
        $scoresheet = $entityManager->getRepository(Scoresheet::class)->findOneBy(['fixedMatch' => $fixedMatch , 'team' => $this->getUser()->getEquipe() ]);
        $form = $this->createForm(ScoresheetEquipeAType::class, $scoresheet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $query = $entityManager->createQuery(
                'SELECT s
                FROM App\Entity\Scoresheet s
                WHERE s.fixedMatch = :match
                AND s.team != :team '
            )->setParameters(array('team'=> $this->getUser()->getEquipe() , 'match' => $fixedMatch));
    
            // returns an array of Product objects
            $scoresheet2 = $query->getResult();

            if ($scoresheet2[0]->getScoreReport() !== null) {
                if ($scoresheet->getScoreReport() === $scoresheet2[0]->getScoreReport() && $scoresheet->getState() ==! $scoresheet2[0]->getState()) {
                    $scoresheetRepository->add($scoresheet, true);

                    $fixedMatch->setScore($scoresheet->getScoreReport());
                    $fixedMatch->setState("Done");
                    
                    if ($scoresheet->getState() === "W") {
                        $classement = $entityManager->getRepository(Classement::class)->findBy(['equipe' => $scoresheet->getTeam() , 'league' => $fixedMatch->getLeague() ]);
                        $classement[0]->setMP($classement[0]->getMP() + 1 );
                        $classement[0]->setW($classement[0]->getW() + 1);
                        $classement[0]->setPts($classement[0]->getPts() + 3);

                        $classement2 = $entityManager->getRepository(Classement::class)->findBy(['equipe' => $scoresheet2[0]->getTeam() , 'league' => $fixedMatch->getLeague() ]);
                        $classement2[0]->setMP($classement2[0]->getMP() + 1 );
                        $classement2[0]->setL($classement2[0]->getL() + 1);
                    }
                    else {
                        $classement = $entityManager->getRepository(Classement::class)->findBy(['equipe' => $scoresheet->getTeam() , 'league' => $fixedMatch->getLeague() ]);
                        $classement[0]->setMP($classement[0]->getMP() + 1 );
                        $classement[0]->setL($classement[0]->getL() + 1);
                    
                        $classement2 = $entityManager->getRepository(Classement::class)->findBy(['equipe' => $scoresheet2[0]->getTeam() , 'league' => $fixedMatch->getLeague() ]);
                        $classement2[0]->setMP($classement2[0]->getMP() + 1 );
                        $classement2[0]->setW($classement2[0]->getW() + 1);
                        $classement2[0]->setPts($classement2[0]->getPts() + 3);
                    }

                    $entityManager->persist($classement[0]);
                    $entityManager->persist($classement2[0]);
                    $entityManager->persist($fixedMatch);
                    $entityManager->flush();

                    $this->addFlash(
                        'notice',
                        'Félicitations, votre match à été validé'
                    );
                    return $this->redirectToRoute('app_profile', [], Response::HTTP_SEE_OTHER);
                }
                else {
                    $tribunal = new Tribunal();
                    $topic = new Topic();
                    $botMessage = new MessageTopic();
                    $tribunal->setFixedMatch($fixedMatch);
                    $topic->setTribunal($tribunal);
                    $botMessage->setTopic($topic);
                    $entityManager->persist($tribunal);
                    $entityManager->flush();
                    $this->addFlash(
                        'notice',
                        'Les scores ne sont pas bon, du coup direction le tribunal :('
                    );
                    return $this->redirectToRoute('app_profile', [], Response::HTTP_SEE_OTHER);
                }
            }
            else {
                $scoresheetRepository->add($scoresheet, true);
            }
            return $this->redirectToRoute('app_scoresheet_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('scoresheet/new.html.twig', [
            'scoresheet' => $scoresheet,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_scoresheet_show", methods={"GET"})
     */
    public function show(Scoresheet $scoresheet): Response
    {
        return $this->render('scoresheet/show.html.twig', [
            'scoresheet' => $scoresheet,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_scoresheet_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Scoresheet $scoresheet, ScoresheetRepository $scoresheetRepository): Response
    {
        $form = $this->createForm(ScoresheetEquipeAType::class, $scoresheet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $scoresheetRepository->add($scoresheet, true);

            return $this->redirectToRoute('app_scoresheet_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('scoresheet/edit.html.twig', [
            'scoresheet' => $scoresheet,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_scoresheet_delete", methods={"POST"})
     */
    public function delete(Request $request, Scoresheet $scoresheet, ScoresheetRepository $scoresheetRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$scoresheet->getId(), $request->request->get('_token'))) {
            $scoresheetRepository->remove($scoresheet, true);
        }

        return $this->redirectToRoute('app_scoresheet_index', [], Response::HTTP_SEE_OTHER);
    }
}
