<?php

namespace App\Tests;

use Doctrine\ORM\EntityNotFoundException;
use App\Domain\Auth\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;

abstract class WebTestCase extends \Symfony\Bundle\FrameworkBundle\Test\WebTestCase
{
    protected KernelBrowser $client;
    protected AbstractDatabaseTool $databaseTool;

    public function setUp(): void
    {
        parent::setUp();
        $this->client = static::createClient();

        $this->databaseTool = static::getContainer()->get(DatabaseToolCollection::class)->get();
    }

    /**
     * Load specified fixtures.
     *
     * @param array $fixtures
     * @return void
     */
    public function loadFixtures(array $fixtures): void
    {
        $this->databaseTool->loadFixtures($fixtures);
    }

    /**
     * Overrides the client method to login a User.
     *
     * @return void
     */
    public function loginUser(string $email): void
    {
        $userRepository = static::getContainer()->get(UserRepository::class);
        /** @var UserRepository $userRepository */
        $user = $userRepository->findOneByEmail($email);

        if($user === null){
            throw new EntityNotFoundException("L'utilisateur demandé n'existe pas pour ce test.");
        }

        $this->client->loginUser($user);
    }

}