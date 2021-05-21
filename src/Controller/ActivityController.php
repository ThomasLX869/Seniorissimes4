<?php

namespace App\Controller;



use App\Entity\Activity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class ActivityController extends AbstractController
{
    /**
     * @Route("/activite", name="activity")
     */
    public function index(Request $request, SerializerInterface $serializer): Response
    {
        $em = $this->getDoctrine()->getManager();
        $defaultData = ['message' => ''];
        $form = $this->createFormBuilder($defaultData)
            ->add('search', SearchType::class)
            ->add('submit', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $city = $data["search"];

            try{
                $callApi = file_get_contents('http://api.openweathermap.org/data/2.5/weather?q=' . $city . '&appid=4bc926ca38cf408b902392a4a9164238');
                $weather = $serializer->decode($callApi, "json");
                $temperature = ($weather['main']['temp'] - 273.1);
                $activities = $em->getRepository(Activity::class)->findAll();

                dump($weather);
                $isWeatherClear = false;

                switch ($weather['weather'][0]['main']) {
                    case 'clear sky':
                        $isWeatherClear = true;
                        break;
                    case 'few clouds':
                        $isWeatherClear = true;
                        break;
                    case 'scattered clouds':
                        $isWeatherClear = true;
                        break;
                    case 'Clouds':
                        $isWeatherClear = true;
                        break;
                    case 'Snow':
                        $isWeatherClear = false;
                        break;
                    case 'Thunderstorm':
                        $isWeatherClear = false;
                        break;
                    case 'Rain':
                        $isWeatherClear = false;
                        break;
                    case 'Drizzle':
                        $isWeatherClear = false;
                        break;
                }

                $activitiesToPurpose = [];
                foreach ($activities as $activity) {
                    if(!$activity->getIsOutdoor()) {
                        $activitiesToPurpose[] = $activity;
                    }elseif ($activity->getTemperatureMax() > $temperature && $activity->getTemperatureMin() < $temperature && $isWeatherClear) {
                        $activitiesToPurpose[] = $activity;
                    }
                }
                dump($activitiesToPurpose);
            }
            catch(\Exception $e){
                $this->addFlash(
                    'error',
                    "Cette ville n'est pas référencée ! "
                );
            }



            return $this->render('activity/index.html.twig', [
                'form' => $form->createView()
            ]);
        }


        return $this->render('activity/index.html.twig', [
            'form' => $form->createView()
        ]);

    }
}
