<?php

namespace App\Controller;

use App\Entity\Activity;
use App\Entity\Category;
use App\Entity\Player;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use App\Form\CategoryType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

    /**
     * @Route("/category/", name="category_")
     */

class CategoryController extends AbstractController
{
    /**
     * @Route("", name="index")
     */
    public function index(): Response
    {
        $categories = $this->getDoctrine()
        ->getRepository(Category::class)
        ->findAll();

        return $this->render('category/index.html.twig', [
            'categories' => $categories
        ]);
    }

    /**
     * The controller for the category add form
     * Display the form or deal with it
     *
     * @Route("new", name="new")
     */
    public function new(Request $request, MailerInterface $mailer) : Response
    {
        // Create a new Category Object
        $category = new Category();
        // Create the associated Form
        $form = $this->createForm(CategoryType::class, $category);
        // Get data from HTTP request
        $form->handleRequest($request);
        // Was the form submitted ?
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($category);
            $entityManager->flush();

            $email = (new Email())
            ->from($this->getParameter('mailer_from'))
            ->to('maff@gmail.com')
            ->subject('Une nouvelle categorie chez Newsport Association')
            ->html($this->renderView('category/newCategoryEmail.html.twig',
            ['category' => $category]));

            $mailer->send($email);

        return $this->redirectToRoute('category_index');
        }
        // Render the form
        return $this->render('category/new.html.twig', ["form" => $form->createView()]);
    }
}
