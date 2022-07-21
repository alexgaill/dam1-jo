<?php

namespace App\Controller;

use App\Entity\Discipline;
use App\Form\DisciplineType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class DisciplineController extends AbstractController
{
    #[Route('/discipline', name: 'app_discipline', methods:["GET", "POST"])]
    public function index(ManagerRegistry $manager, Request $request): Response
    {
        $discipline = new Discipline;
        $form = $this->createForm(DisciplineType::class, $discipline);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->getRepository(Discipline::class)->add($discipline, true);
            $this->addFlash('success', "La discipline '".$discipline->getNom()."' a bien été ajoutée");

            return $this->redirectToRoute('app_discipline');
        }

        return $this->renderForm('discipline/index.html.twig', [
            'disciplines' => $manager->getRepository(Discipline::class)->findAll(),
            'form' => $form
        ]);
    }

    #[Route('/discipline/{id}/update', name:'update_discipline', methods:["GET", "POST"], requirements:['id' => "\d+"])]
    public function update(Discipline $discipline, ManagerRegistry $manager, Request $request): Response
    {
        $form = $this->createForm(DisciplineType::class, $discipline);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->getRepository(Discipline::class)->add($discipline, true);
        $this->addFlash('success', "La discipline '".$discipline->getNom()."' a bien été modifiée");

            return $this->redirectToRoute('app_discipline');
        }

        return $this->renderForm('discipline/update.html.twig', [
            'form' => $form
        ]);
    }

    #[Route("/dsicipline/{id}/delete", name:"delete_discipline", methods:['GET'], requirements:['id' => "\d+"])]
    public function delete(Discipline $discipline, ManagerRegistry $manager): Response
    {
        $manager->getRepository(Discipline::class)->remove($discipline, true);

        $this->addFlash('warning', "La discipline '".$discipline->getNom()."' a bien été supprimée");
        return $this->redirectToRoute('app_discipline');
    }
}
