<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use App\Repository\ContactRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class ContactController extends AbstractController
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    #[Route('/contact', name: 'app_contact')]
    public function index(Request $request): Response
    {

        $date = new \DateTime("now");
        $contactEntity = new Contact();
        $contactEntity->setCreatedAt($date);
        $contactEntity->setOrderStatus("not-treated");
        $form = $this->createForm(ContactType::class, $contactEntity);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($contactEntity);
            $this->em->flush();
        }

        return $this->render('contact/index.html.twig', [
            "contactForm" => $form->createView(),
        ]);
    }

    #[Route('contact/nontreated', name: 'app_contact_nontreated')]
    #[IsGranted('ROLE_ADMIN')]
    public function nonTreatedOrders(ContactRepository $contactRepository)
    {

        $nonTreatedOrders = $contactRepository->findBy(["orderStatus" => "not-treated"]);


        return $this->render("contact/nontreatedorders.html.twig", [

            "orders" => $nonTreatedOrders
        ]);
    }


    #[Route('contact/nontreated/delete/{id}', name: 'app_contact_delete')]
    #[IsGranted('ROLE_ADMIN')]
    public function deleteANonTreatedOrder(ContactRepository $contactRepository , $id)

    {
        $entityContact = $contactRepository->find($id);
        $entityContact->setOrderStatus("treated");
        $this->em->persist($entityContact);
        $this->em->flush();



        return $this->redirectToRoute("app_contact_nontreated");
    }
}
