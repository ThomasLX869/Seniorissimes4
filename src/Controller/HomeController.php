<?php

namespace App\Controller;



use App\Entity\Activity;
use App\Repository\ActivityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(Request $request, SerializerInterface $serializer, ActivityRepository $repo, SessionInterface $session): Response
    {
        $activity = $repo->findAll();
        $em = $this->getDoctrine()->getManager();
        $defaultData = ['message' => ''];
        $form = $this->createFormBuilder($defaultData)
            ->add('search', SearchType::class, [
                'attr' => [
                    'placeholder' => 'Votre ville',
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Chercher',
            ])
            ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $city = $data["search"];
            $session->set('searchField', $city);
            return $this->redirectToRoute('activity');
        }
        return $this->render('home/index.html.twig', [
            'form' => $form->createView(),
            'activity' => $activity
        ]);

    }
}
