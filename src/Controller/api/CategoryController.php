<?php

namespace App\Controller\api;

use App\Entity\Contact;
use App\Form\ContactType;
use App\Repository\CategoryRepository;
use App\Repository\ContactRepository;
use App\Repository\MediaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class CategoryController extends AbstractController
{
    public function __construct(private CategoryRepository $categoryRepository , private MediaRepository $mediaRepository)
    {
    }

    #[Route('/api/category' , name: "app_api_category")]
    public function apicategoryCollection(Request $request): Response
    {
        $categories = $this->categoryRepository->findAll();


        return $this->json($categories , context: ["groups"=>"toto"]);


    }


    #[Route('/api/media' , name: "app_api_category")]
    public function apiMedia(Request $request): Response
    {
        $medias = $this->mediaRepository->findAll();


        return $this->json($medias , context: ["groups"=>"media"]);


    }


}
