<?php

namespace App\Command;

use App\Domain\Auth\Entity\User;
use App\Domain\Auth\UserRoles;
use App\Domain\Auth\UserService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:create-user',
    description: 'Add a short description for your command',
)]
class CreateUserCommand extends Command
{
    protected static $defaultDescription = 'Crée un utilisateur';

    public function __construct(private UserService $userService, private UserPasswordHasherInterface $passwordHasher)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription(self::$defaultDescription)
            ->addArgument('firstName', InputArgument::OPTIONAL, 'Prénom')
            ->addArgument('lastName', InputArgument::OPTIONAL, 'Nom')
            ->addArgument('email', InputArgument::OPTIONAL, 'Email')
            ->setHelp("Créer un utilisateur dans un workspace ou non")
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->section("Utilisateur");

        if(!$input->getArgument('firstName')){
            $firstName = $io->ask("Prenom ? ");
        }
        else{
            $firstName = $input->getArgument('firstName');
        }

        $output->writeln('Prenom : ' . $firstName );

        if(!$input->getArgument('lastName')){
            $lastName = $io->ask("Nom ? ");
        }
        else{
            $lastName = $input->getArgument('lastName');
        }

        $output->writeln($firstName . " " . $lastName);

        if(!$input->getArgument('email')){
            $email = $io->ask("Email ? ");
        }
        else{
            $email = $input->getArgument('email');
        }

        $output->writeln($firstName . " " . $lastName . " <".$email.">");

        $password = $io->ask("Mot de passe ?");
        
        $user = (new User())
            ->setEmail($email)
            ->setName((string)$firstName)
            ->setSurname((string)$lastName)
            ->setRole(UserRoles::STUDENT)
        ;

        $user->setPassword($this->passwordHasher->hashPassword($user, $password));

        $this->userService->createUser($user);

        $io->success("L'utilisateur a bien ete cree !");

        return Command::SUCCESS;
    }
}
