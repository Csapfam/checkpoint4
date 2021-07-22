<?php

namespace App\Controller;

use App\Entity\Activity;
use App\Entity\Category;
use App\Entity\Player;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use App\Form\ActivityType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

    /**
     * @Route("/activity/", name="activity_")
     */

class ActivityController extends AbstractController
{
    /**
     * @Route("", name="index")
     */
    public function index(): Response
    {
        $activities = $this->getDoctrine()
        ->getRepository(Activity::class)
        ->findAll();

        return $this->render('activity/index.html.twig', [
            'activities' => $activities
        ]);
    }

    /**
     * The controller for the program add form
     * Display the form or deal with it
     *
     * @Route("new", name="new")
     */
    public function new(Request $request, MailerInterface $mailer) : Response
    {
        // Create a new Activity Object
        $activity = new Activity();
        // Create the associated Form
        $form = $this->createForm(ActivityType::class, $activity);
        // Get data from HTTP request
        $form->handleRequest($request);
        // Was the form submitted ?
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($activity);
            $entityManager->flush();

            $email = (new Email())
            ->from($this->getParameter('mailer_from'))
            ->to('maff@gmail.com')
            ->subject('Une nouvelle activité chez Newsport Association')
            ->html($this->renderView('activity/newActivityEmail.html.twig',
            ['activity' => $activity]));

            $mailer->send($email);

        return $this->redirectToRoute('activity_index');
        }
        // Render the form
        return $this->render('activity/new.html.twig', ["form" => $form->createView()]);
    }

    /**
     * @Route("show/{id<^[0-9]+$>}", name="show", methods={"GET"})
     */
    public function show(Activity $activity): Response
    {
        if (!$activity){
            throw $this->createNotFoundException(
                'Aucune activité avec id'.$activity.' trouvée dans la liste des activités'
            );
        }
        return $this->render('activity/show.html.twig', [
            'activity'=>$activity
            ]);
    }

}
