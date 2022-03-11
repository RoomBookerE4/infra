<?php

namespace App\Tests\Controller;

use App\Tests\WebTestCase;

class BookingControllerTest extends WebTestCase
{

    public function setUp(): void
    {
        parent::setUp();
    }

    public function testHomeRedirectsIfNotLoggedIn(): void
    {
        $crawler = $this->client->request('GET', '/home');
        
        $this->assertResponseRedirects(null, 302, "L'utilisateur a été redirigé vers la page de connexion.");
        //$this->assertRouteSame('login', [], "L'utilisateur doit se connecter.");
    }

    public function testCardsArePresent(): void
    {
        $this->loginUser('student1_angers@reseau.eseo.fr');
        $crawler = $this->client->request('GET', '/home');

        $this->assertSelectorTextContains('h4', 'Réserver une salle');
        //$this->assertSelectorTextContains('h5', 'Mes réservations');
    }

    public function testUserNamesAreDisplayed(): void
    {
        $this->loginUser('student1_angers@reseau.eseo.fr');
        $crawler = $this->client->request('GET', '/home');

        $this->assertSelectorTextSame('//span[@id="username"]', "Bonjour, Student 1", "Le bonjour à l'utilisateur est bien affiché.");
    }


}
