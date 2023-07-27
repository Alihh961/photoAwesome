<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Media;
use App\Form\CategorySearchType;
use App\Form\CategoryType;
use App\Form\MediaType;
use App\Form\SearchType;
use App\Repository\CategoryRepository;
use App\Repository\MediaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/admin')]
class AdminController extends AbstractController
{

    public function __construct(private CategoryRepository     $categoryRepository,
                                private MediaRepository        $mediaRepository,
                                private EntityManagerInterface $entityManager,
                                private PaginatorInterface     $paginator)
    {
    }

    #[Route('/', name: 'app_admin_index')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    #[Route('/category', name: 'app_category_index')]
    public function category(Request $request): Response
    {
        $qb = $this->categoryRepository->getQbAll();
        $form = $this->createForm(CategorySearchType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $categoryLabel = $data["categoryLabel"];

            if ($categoryLabel !== null) {
                $qb->where("categoryTable.label = :toto")
                    ->setParameter("toto", $categoryLabel);
            }


        }
        $panigation = $this->paginator->paginate(
            $qb,
            $request->query->getInt("page", 1),
            10
        );

        return $this->render('category/index.html.twig', [
            'categories' => $panigation,
            "searchForm" => $form->createView(),
        ]);
    }

    #[Route('/category/show/{id}', name: 'app_category_show')]
    public function showCategory($id): Response
    {
        $categoryEntity = $this->categoryRepository->find($id);

        if ($categoryEntity === null) {
            return $this->redirectToRoute('app_home');
        }
        return $this->render('category/show.html.twig', [
            'category' => $categoryEntity,
        ]);
    }

    #[Route('/category/delete/{id}', name: 'app_category_delete')]
    public function deleteCategory($id): Response
    {
        $categoryEntity = $this->categoryRepository->find($id);

        if ($categoryEntity === null) {
            return $this->redirectToRoute('app_home');
        }

        $this->entityManager->remove($categoryEntity);
        $this->entityManager->flush();

        return $this->redirectToRoute("app_category_index");
    }

    #[Route('/category/new', name: "app_category_new")]
    public function newCategory(Request $request): Response
    {

        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request); // Request contains all super globals

        if ($form->isSubmitted() && $form->isValid()) {

            $this->entityManager->persist($category);
            $this->entityManager->flush();
            return $this->redirectToRoute("app_category_index");

        }

        return $this->render("category/new.html.twig", [
            "form" => $form->createView()
        ]);

    }

    #[Route('/category/update/{id}', name: 'app_category_update')]
    public function editCategory($id, Request $request): Response
    {

        $category = $this->categoryRepository->find($id);
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->entityManager->persist($category);
            $this->entityManager->flush();

            return $this->redirectToRoute("app_category_index");
        }

        return $this->render("category/update.html.twig", [
            "updateForm" => $form->createView(),
        ]);

    }


    #[Route("/media", name: "app_media_index")]
    public function media(Request $request): Response
    {


        $qb = $this->mediaRepository->getQbAll();

        $form = $this->createForm(SearchType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $title = $data["mediaTitle"];
            $userEmail = $data["userEmail"];
            $date = $data["createdAt"];

            if ($title !== null) {
                //select * from media as m where m.title like '%$title%';
                $qb->where('m.title LIKE :toto')
                    ->setParameter("toto", "%" . $title . "%");
            }

            if ($userEmail !== null) {
                $qb->innerJoin("m.user", "u")
                    ->andWhere("u.email = :email")
                    ->setParameter("email", $userEmail);
            }

            if ($date !== null) {
                $qb->andWhere("m.createdAt > :toto")
                    ->setParameter("toto", $date);
            }
        }
        $pagination = $this->paginator->paginate(
            $qb, // the query
            $request->query->getInt("page", 1), // receive the get from url , 1 in default if there is no parameters in the url
            10); // number of Entities per page

        return $this->render("/media/index.html.twig", [
            "medias" => $pagination,
            "searchForm" => $form->createView(),
        ]);

    }

    #[Route("/media/show/{id}", name: "app_media_show")]
    public function showMedia($id): Response
    {

        $media = $this->mediaRepository->find($id);

        return $this->render("/media/show.html.twig", [
            "media" => $media
        ]);

    }

    #[Route("/media/new", name: "app_media_new")]
    public function addMedia(Request $request ,SluggerInterface $slugger): Response
    {


        $mediaEntity = new Media();
        $currentDate = new \DateTime("now");
        $currentUser = $this->getUser();
        $mediaEntity->setCreatedAt($currentDate);
        $mediaEntity->setUser($currentUser);

        $form = $this->createForm(MediaType::class, $mediaEntity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $slug = $slugger->slug($mediaEntity->getTitle());
            $mediaEntity->setSlug($slug);

            $this->entityManager->persist($mediaEntity);
            $this->entityManager->flush();
        }
        return $this->render("/media/new.html.twig", [
            'form' => $form->createView(),
        ]);
    }
}