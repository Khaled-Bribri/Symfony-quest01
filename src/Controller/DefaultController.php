<?php
// src/Controller/ProgramController.php
namespace App\Controller;

use App\Repository\CategoryRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'app_index')]

    public function index(CategoryRepository $categoryRepository): Response
    {
        return $this->render('app/index.html.twig',['categories'=>$categoryRepository->findAll()]);
    }

}