<?php

namespace App\Controller;

use App\Entity\Activity;
use App\Entity\Category;
use App\Entity\Player;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use App\Form\PlayerType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

    /**
     * @Route("/player/", name="player_")
     */

class PlayerController extends AbstractController
{
    /**
     * @Route("", name="index")
     */
    public function index(): Response
    {
        $players = $this->getDoctrine()
        ->getRepository(Player::class)
        ->findAll();

        return $this->render('player/index.html.twig', [
            'players' => $players
        ]);
    }

    /**
     * The controller for the player add form
     * Display the form or deal with it
     *
     * @Route("new", name="new")
     */
    public function new(Request $request, MailerInterface $mailer) : Response
    {
        // Create a new Player Object
        $player = new Player();
        // Create the associated Form
        $form = $this->createForm(PlayerType::class, $player);
        // Get data from HTTP request
        $form->handleRequest($request);
        // Was the form submitted ?
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($player);
            $entityManager->flush();

            $email = (new Email())
            ->from($this->getParameter('mailer_from'))
            ->to('maff@gmail.com')
            ->subject('Votre inscription')
            ->html($this->renderView('player/newPlayerEmail.html.twig',
            ['player' => $player]));

            $mailer->send($email);

        return $this->redirectToRoute('home_index');
        }
        // Render the form
        return $this->render('player/new.html.twig', ["form" => $form->createView()]);
    }
}
