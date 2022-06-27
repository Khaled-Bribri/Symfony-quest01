<?php

namespace App\Controller;

use App\Entity\Episode;
use App\Service\Slugify;
use App\Form\EpisodeType;
use App\Repository\CategoryRepository;
use App\Repository\EpisodeRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


#[Route('/episode')]
class EpisodeController extends AbstractController
{
    #[Route('/', name: 'app_episode_index', methods: ['GET'])]
    public function index(EpisodeRepository $episodeRepository, CategoryRepository $categoryRepository): Response
    {
        return $this->render('episode/index.html.twig', [
            'episodes' => $episodeRepository->findAll(),
            'categries'=>$categoryRepository->findAll()
        ]);
    }

    #[Route('/new', name: 'app_episode_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EpisodeRepository $episodeRepository,Slugify $slugify,MailerInterface $mailer,CategoryRepository $categoryRepository): Response
    {
        $episode = new Episode();
        $form = $this->createForm(EpisodeType::class, $episode);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $slug = $slugify->generate($episode->gettitle());
            $episode->setSlug($slug);
            
            $episodeRepository->add($episode, true);

            $email = (new TemplatedEmail())
                ->from('khaledbribri506@gmail.com')
                ->to('your_email@example.com')
                ->subject('Un nouvel episode vient d\'être publiée !')
                ->html($this->renderView('Email/newepisodeEmail.html.twig', ['episode' => $episode]));

            $mailer->send($email);

            return $this->redirectToRoute('app_episode_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('episode/new.html.twig', [
            'episode' => $episode,
            'form' => $form,
            'categories'=>$categoryRepository->findAll()
        ]);
    }

    #[Route('/{id}', name: 'app_episode_show', methods: ['GET'])]
    public function show(Episode $episode, CategoryRepository $categoryRepository ): Response
    {
        return $this->render('episode/show.html.twig', [
            'episode' => $episode,
            'categories'=>$categoryRepository->finAll()
        ]);
    }

    #[Route('/{id}/edit', name: 'app_episode_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Episode $episode, EpisodeRepository $episodeRepository,CategoryRepository $categoryRepository): Response
    {
        $form = $this->createForm(EpisodeType::class, $episode);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $episodeRepository->add($episode, true);

            return $this->redirectToRoute('app_episode_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('episode/edit.html.twig', [
            'episode' => $episode,
            'form' => $form,
            'categories'=>$categoryRepository->finAll()
        ]);
    }

    #[Route('/{id}', name: 'app_episode_delete', methods: ['POST'])]
    public function delete(Request $request, Episode $episode, EpisodeRepository $episodeRepository,CategoryRepository $categoryRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$episode->getId(), $request->request->get('_token'))) {
            $episodeRepository->remove($episode, true);
        }

        return $this->redirectToRoute('app_episode_index', [], Response::HTTP_SEE_OTHER);
    }
}
