<?php
// src/Controller/ProgramController.php
namespace App\Controller;
use App\Entity\Category;
use App\Entity\Program;
use App\Repository\CategoryRepository;
use App\Repository\ProgramRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use doctrine\Persistence\ManagerRegistry;
use App\Form\CategoryType;
use Symfony\Component\HttpFoundation\Request;



class CategoryController extends AbstractController
{
    #[Route('/category/', name: 'category_index')]
    public function index(CategoryRepository $categoryRepository):Response
    {
        $categories = $categoryRepository->findAll();
        return $this->render('category/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    #[Route('/category/new', name: 'category_new')]
    public function new(Request $request, CategoryRepository $categoryRepository): Response
    {
        $category = new Category();

        // Create the form, linked with $category
        $form = $this->createForm(CategoryType::class, $category);
        
        // Render the form (best practice)
        $form->handleRequest($request);


        if ($form->isSubmitted()) {
            $categoryRepository->add($category, true);            
            return $this->redirectToRoute('category_index');
        }

        return $this->renderForm('category/new.html.twig', [
            'form' => $form,
        ]);

        // Alternative
        // return $this->render('category/new.html.twig', [
        //   'form' => $form->createView(),
        // ]);
    }


    #[Route ('/category/{categoryName}', name: 'category_show')]
    public function show(string $categoryName, CategoryRepository $categoryRepository,ProgramRepository $programRepository ):Response
    {
        $category = $categoryRepository->findBy(['name' => $categoryName]);
        $programs = $programRepository->findBy(['category' => $category], ['id' => 'DESC'],3,0);
        if (!$category)
        {
            throw $this->createNotFoundException('No category found for id '.$category);
        }
        else
        {
            return $this->render('category/show.html.twig', ['category' => $category, 'programs' => $programs,]);
        }
        
    }

}

