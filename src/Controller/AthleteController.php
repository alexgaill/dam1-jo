<?php

namespace App\Controller;

use App\Entity\Athlete;
use App\Form\AthleteType;
use App\Repository\AthleteRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AthleteController extends AbstractController
{
    private ?AthleteRepository $repository;

    public function __construct(ManagerRegistry $manager)
    {
        $this->repository = $manager->getRepository(Athlete::class);
    }

    #[Route('/athlete', name: 'app_athlete')]
    public function index(ManagerRegistry $manager, Request $request): Response
    {
        $athlete = new Athlete;
        $form = $this->createForm(AthleteType::class, $athlete);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->repository->add($athlete, true);
            $this->addFlash('success', "Athlete enregistré");
            return $this->redirectToRoute('app_athlete');
        }

        return $this->renderForm('athlete/index.html.twig', [
            'athletes' => $this->repository->findAll(),
            'form' => $form
        ]);
    }
}
