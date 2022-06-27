<?php
// src/Controller/ProgramController.php
namespace App\Controller;


use App\Entity\User;
use App\Entity\Actor;
use App\Entity\Season;
use App\Entity\Comment;
use App\Entity\Episode;
use App\Entity\Program;
use App\Service\Slugify;
use App\Form\CommentType;
use App\Form\ProgramType;
use App\Service\ConnectedUser;
use App\Repository\ActorRepository;
use App\Repository\SeasonRepository;
use App\Repository\CommentRepository;
use App\Repository\EpisodeRepository;
use App\Repository\ProgramRepository;
use App\Repository\CategoryRepository;
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
    public function index(ProgramRepository $programRepository,CategoryRepository $categoryRepository): Response
    {
        $programs =  $programRepository->findAll();
        return $this->render('program/index.html.twig', [
            'website' => 'Wild Series',
            'programs' => $programs,
            'categories'=>$categoryRepository->findAll()
        ]);
    }

    #[Route('/program/new', name: 'program_new')]
    public function new(Request $request, MailerInterface $mailer, ProgramRepository $programRepository, Slugify $slugify,CategoryRepository $categoryRepository)
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

        return $this->renderForm('program/new.html.twig', ['categories'=>$categoryRepository->findAll(),'form' => $form]);
    }

    #[Route('/program/{program}', requirements: ['id' => '\d+'], methods: ['GET'], name: 'program_show')]
    public function show(Program $program, SeasonRepository $seasonRepository,CategoryRepository $categoryRepository): Response
    {

        $seasons = $seasonRepository->findByProgram($program);


        if (!$program) {
            throw $this->createNotFoundException('No program found for id ' . $program);
        } else {
            return $this->render('program/show.html.twig', ['seasons' => $seasons, 'program' => $program,'categories'=>$categoryRepository->findAll()]);
        }
    }

    #[Route('/program/{program}/seasons/{season}', requirements: ['programId' => '\d+', 'seasonId' => '\d+'], methods: ['GET' , 'POST'], name: 'program_season_show')]
    public function showSeason(Program $program, Season $season, EpisodeRepository $episodeRepository, CategoryRepository $categoryRepository): Response
    {

        $episodes = $episodeRepository->findBySeason([$season], ['number' => 'ASC']);
        return $this->render('program/season_show.html.twig', ['categories'=>$categoryRepository->findAll(),'episodes' => $episodes, 'program' => $program, 'season' => $season]);
    }

    #[Route('/program/{program}/seasons/{season}/episodes/{episode}', requirements: ['programId' => '\d+', 'seasonId' => '\d+', 'episodeId' => '\d+'], methods: ['GET','POST'], name: 'episode_show')]
    public function showEpisode(Request $request, Program $program, Season $season, Episode $episode,CategoryRepository $categoryRepository, CommentRepository $commentRepository): Response
    {
        $comments = $commentRepository->findall();
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);
        if($this->getUser()){
            $user = $this->container->get('security.token_storage')->getToken()->getUser()->getemail();
            $id = $this->container->get('security.token_storage')->getToken()->getUser()->getId();
            if ($form->isSubmitted() && $form->isValid()) {
                $comment->setAuthor($id);
                $comment->setEpisode($episode->getId());
                $commentRepository->add($comment, true);
                $this->addFlash('success', 'Your comment was submitted');
                return $this->redirectToRoute('program_index',[],Response::HTTP_SEE_OTHER);
            }
        }

        return $this->render('program/episode_show.html.twig', 
        ['categories'=>$categoryRepository->findAll(),
        'episode' => $episode, 'program' => $program, 
        'season' => $season, 'form'=>$form->createView(),
        'comments'=>$comments,
        
    ]);

}

    #[Route('/program/{program}/edit', requirements: ['id' => '\d+'], methods: ['GET', 'POST'], name: 'program_edit')]
    public function edit(Program $program, ProgramRepository $programRepository,CategoryRepository $categoryRepository ,Request $request, Slugify $slugify): Response
    {
        $form = $this->createForm(ProgramType::class, $program);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $slug = $slugify->generate($program->getTitle());
            $program->setSlug($slug);
            $programRepository->add($program, true);

            return $this->redirectToRoute('program_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('program/edit.html.twig', ['form' => $form,'categories'=>$categoryRepository->findAll()]);
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
