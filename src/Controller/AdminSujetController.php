<?php

namespace App\Controller;

use App\Entity\Sujet;
use App\Form\SujetType;
use App\Repository\SujetRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/sujet")
 */
class AdminSujetController extends AbstractController
{
    /**
     * @Route("/", name="admin_sujet_index", methods={"GET"})
     */
    public function index(SujetRepository $sujetRepository): Response
    {
        return $this->render('admin_sujet/index.html.twig', [
            'sujets' => $sujetRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="admin_sujet_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $sujet = new Sujet();
        $form = $this->createForm(SujetType::class, $sujet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($sujet);
            $entityManager->flush();

            return $this->redirectToRoute('admin_sujet_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin_sujet/new.html.twig', [
            'sujet' => $sujet,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="admin_sujet_show", methods={"GET"})
     */
    public function show(Sujet $sujet): Response
    {
        return $this->render('admin_sujet/show.html.twig', [
            'sujet' => $sujet,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="admin_sujet_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Sujet $sujet): Response
    {
        $form = $this->createForm(SujetType::class, $sujet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_sujet_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin_sujet/edit.html.twig', [
            'sujet' => $sujet,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="admin_sujet_delete", methods={"POST"})
     */
    public function delete(Request $request, Sujet $sujet): Response
    {
        if ($this->isCsrfTokenValid('delete'.$sujet->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($sujet);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_sujet_index', [], Response::HTTP_SEE_OTHER);
    }
}
