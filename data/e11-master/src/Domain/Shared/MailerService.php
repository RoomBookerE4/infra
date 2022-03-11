<?php

namespace App\Domain\Shared;

use DomainException;
use Mailjet\Resources;
use Twig\Environment;

class MailerService
{
  
  public function __construct(
    private string $apiKey,
    private string $secretKey,
    private Environment $twig
  )
  {
    
  }
  
  public function sendEmail(string $toMail, string $toString, string $subject, string $text, ?string $template = null, ?array $parameters = []): array
  {
    $html = $this->twig->render($template, $parameters);
    try{
      $mj = new \Mailjet\Client($this->apiKey, $this->secretKey, true, ['version' => 'v3.1']);
      $body = [
        'Messages' => [
          [
            'From' => 
            [
              'Email' => "simon.duperray@reseau.eseo.fr",
              'Name' => "Simon Duperray"
            ],
            'To' => [
              [
                'Email' => $toMail,
                'Name' => $toString
              ]
            ],
            'Subject' => $subject,
            'TextPart' => $text,
            'HTMLPart' => $html
          ]
        ]
      ];
      $response = $mj->post(Resources::$Email, ['body' => $body]);
      return $response->getData();
    }
    catch(\Exception $e){
      throw new DomainException("Impossible d'envoyer un mail", 1);
    }
  }
}