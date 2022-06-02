<?php
// src/Controller/ProgramController.php
namespace App\Controller;
use App\Entity\Actor;
use App\Entity\Episode;
use App\Entity\Program;
use App\Entity\Season;
use App\Repository\ActorRepository;
use App\Repository\EpisodeRepository;
use App\Repository\ProgramRepository;
use App\Repository\SeasonRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Form\ProgramType;
use doctrine\Persistence\ManagerRegistry;

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
    public function new(Request $request, ProgramRepository $programRepository)
    {
        $program = new Program();

        // Create the form, linked with $program
        $form = $this->createForm(ProgramType::class, $program);

        $form->HandleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $programRepository->add($program, true);
            return $this->redirectToRoute('program_new');
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
            return $this->render('program/show.html.twig', [ 'seasons' => $seasons, 'program' => $program,]);
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
        
      
        return $this->render('program/episode_show.html.twig', ['episode' => $episode, 'program' => $program, 'season' => $season ]);
    }
}
