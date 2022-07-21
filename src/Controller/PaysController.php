<?php

namespace App\Controller;

use App\Entity\Pays;
use App\Form\PaysType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PaysController extends AbstractController
{
    #[Route('/pays', name: 'app_pays', methods:['GET', 'POST'])]
    public function index(ManagerRegistry $manager, Request $request): Response
    {
        $pays = new Pays;
        $form = $this->createForm(PaysType::class, $pays);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $flag = $form->get('drapeau')->getData();

            $flagName = md5(uniqid()) . '.' . $flag->guessExtension();

            $flag->move(
                $this->getParameter('upload_flag'),
                $flagName
            );
            $pays->setDrapeau($flagName);

            $manager->getRepository(Pays::class)->add($pays, true);
            $this->addFlash('success', "Pays ajouté");
            return $this->redirectToRoute('app_pays');
        }

        return $this->renderForm('pays/index.html.twig', [
            'paysList' => $manager->getRepository(Pays::class)->findAll(),
            'form' => $form
        ]);
    }

    #[Route("/pays/{id}/update", name:"update_pays")]
    public function update(Pays $pays, ManagerRegistry $manager, Request $request): Response
    {
        if ($pays->getDrapeau()) {
            $oldDrapeau = new File($this->getParameter('upload_flag') .'/'. $pays->getDrapeau());
            $oldDrapeauName = $pays->getDrapeau();
            $pays->setDrapeau($oldDrapeau);
        } else {
            $oldDrapeauName = null;
        }

        $form = $this->createForm(PaysType::class, $pays);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $flag = $form->get('drapeau')->getData();

            if ($flag) {
                if ($oldDrapeauName) {
                    unlink($this->getParameter('upload_flag') .'/'. $oldDrapeauName);
                }
                $flagName = md5(uniqid()) . '.' . $flag->guessExtension();
                $flag->move(
                    $this->getParameter('upload_flag'),
                    $flagName
                );
                $pays->setDrapeau($flagName);
            } else {
                $pays->setDrapeau($oldDrapeauName);
            }

            $manager->getRepository(Pays::class)->add($pays, true);
            $this->addFlash('success', "Pays ajouté");
            return $this->redirectToRoute('app_pays');
        }

        return $this->renderForm('pays/index.html.twig', [
            'paysList' => $manager->getRepository(Pays::class)->findAll(),
            'form' => $form
        ]);
    }

    #[Route("/pays/{id}/delete", name:"delete_pays", methods:["GET"], requirements:['id' => "\d+"])]
    public function delete(Pays $pays, ManagerRegistry $manager): Response
    {
        $manager->getRepository(Pays::class)->delete($pays, true);
        $this->addFlash("warning", "Pays supprimé");
        return $this->redirectToRoute('app_pays');
    }
}
