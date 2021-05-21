<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="contact")
     */
    public function index(Request $request): Response
    {$form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $contactFormData = $form->getData();
            dump($contactFormData);
            $em->persist($contactFormData);
            $em->flush();
            $this->addFlash('success', 'Votre message a bien ete envoyer');

            return $this->redirectToRoute('contact');
        }

        return $this->render('contact/index.html.twig', [
            'email_form' => $form->createView(),
        ]);
    }
}
