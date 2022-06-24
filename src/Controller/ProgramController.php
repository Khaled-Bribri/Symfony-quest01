<?php
// src/Controller/ProgramController.php
namespace App\Controller;

use App\Entity\Actor;
use App\Entity\Season;
use App\Entity\Episode;
use App\Entity\Program;
use App\Service\Slugify;
use App\Form\ProgramType;
use App\Repository\ActorRepository;
use App\Repository\SeasonRepository;
use App\Repository\EpisodeRepository;
use App\Repository\ProgramRepository;
use doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProgramController extends AbstractController
{
    #[Route('/program/', name: 'program_index')]
    public function index(ProgramRepository $programRepository): Response
    {
        $programs =  $programRepository->findAll();
        return $this->render('program/index.html.twig', [
            'website' => 'Wild Series',
            'programs' => $programs,
        ]);
    }

    #[Route('/program/new', name: 'program_new')]
    public function new(Request $request, MailerInterface $mailer, ProgramRepository $programRepository, Slugify $slugify)
    {
        $program = new Program();

        // Create the form, linked with $program
        $form = $this->createForm(ProgramType::class, $program);

        $form->HandleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $slug = $slugify->generate($program->getTitle());
            $program->setSlug($slug);
            $programRepository->add($program, true);

            $email = (new TemplatedEmail())
                ->from('khaledbribri506@gmail.com')
                ->to('your_email@example.com')
                ->subject('Une nouvelle série vient d\'être publiée !')
                ->html($this->renderView('Email/newProgramEmail.html.twig', ['program' => $program]));

            $mailer->send($email);



            return $this->redirectToRoute('program_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('program/new.html.twig', ['form' => $form]);
    }

    #[Route('/program/{program}', requirements: ['id' => '\d+'], methods: ['GET'], name: 'program_show')]
    public function show(Program $program, SeasonRepository $seasonRepository): Response
    {

        $seasons = $seasonRepository->findByProgram($program);


        if (!$program) {
            throw $this->createNotFoundException('No program found for id ' . $program);
        } else {
            return $this->render('program/show.html.twig', ['seasons' => $seasons, 'program' => $program,]);
        }
    }

    #[Route('/program/{program}/seasons/{season}', requirements: ['programId' => '\d+', 'seasonId' => '\d+'], methods: ['GET'], name: 'program_season_show')]
    public function showSeason(Program $program, Season $season, EpisodeRepository $episodeRepository): Response
    {

        $episodes = $episodeRepository->findBySeason([$season], ['number' => 'ASC']);
        return $this->render('program/season_show.html.twig', ['episodes' => $episodes, 'program' => $program, 'season' => $season]);
    }

    #[Route('/program/{program}/seasons/{season}/episodes/{episode}', requirements: ['programId' => '\d+', 'seasonId' => '\d+', 'episodeId' => '\d+'], methods: ['GET'], name: 'episode_show')]
    public function showEpisode(Program $program, Season $season, Episode $episode): Response
    {


        return $this->render('program/episode_show.html.twig', ['episode' => $episode, 'program' => $program, 'season' => $season]);
    }

    #[Route('/program/{program}/edit', requirements: ['id' => '\d+'], methods: ['GET', 'POST'], name: 'program_edit')]
    public function edit(Program $program, ProgramRepository $programRepository, Request $request, Slugify $slugify): Response
    {
        $form = $this->createForm(ProgramType::class, $program);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $slug = $slugify->generate($program->getTitle());
            $program->setSlug($slug);
            $programRepository->add($program, true);

            return $this->redirectToRoute('program_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('program/edit.html.twig', ['form' => $form]);
    }

    #[Route('/program/{program}/delete', requirements: ['id' => '\d+'], methods: ['GET', 'POST'], name: 'program_delete')]
    public function delete(Request $request, Program $program, ProgramRepository $programRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $program->getId(), $request->request->get('_token'))) {
            $programRepository->remove($program);
        }
        return $this->redirectToRoute('program_index');
    }
}
