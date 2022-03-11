<?php

namespace App\Controller;

use App\Domain\Booking\BookingService;
use App\Domain\Shared\MailerService;
use App\Domain\Shared\WeatherService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    public function __construct(private BookingService $bookingService, private MailerService $mailerService)
    {
        
    }

    #[Route('/', name: 'home')]
    #[IsGranted('ROLE_USER')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'meetings' => $this->bookingService->upcomingMeetings($this->getUser())
        ]);
    }

    #[Route('/weather', name: 'weather')]
    #[IsGranted('ROLE_USER')]
    public function weather(WeatherService $weatherService): Response
    {
        dump($weatherService->getWeather("Angers"));
        return $this->render('base.html.twig');
    }
}
