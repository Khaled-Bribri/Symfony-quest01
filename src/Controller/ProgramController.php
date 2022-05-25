<?php
// src/Controller/ProgramController.php
namespace App\Controller;

use App\Repository\ProgramRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
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

    #[Route('/program/{id}',requirements: ['id'=>'\d+'], methods: ['GET'], name: 'program_show')]
    public function show(int $id, ProgramRepository $programRepository): Response
    {
        $program = $programRepository->findOneBy(['id' => $id]);
        if(!$program)
        {
            throw $this->createNotFoundException('No program found for id '.$id);
        }
        else
        {
            return $this->render('program/show.html.twig', ['program' => $program,]);
        }
    }

}