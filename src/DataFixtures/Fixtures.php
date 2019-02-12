<?php

namespace App\DataFixtures;

use App\Entity\Excursion;
use App\Entity\State;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class Fixtures extends Command
{
    protected static $defaultName = 'app:fixtures';
    protected $em;
    protected $encoder;

    public function __construct(
        EntityManagerInterface $em,
        UserPasswordEncoderInterface $encoder,
        ?string$name = null)
    {
        $this->em = $em;
        $this->encoder = $encoder;
        parent::__construct($name);
    }

    protected function configure()
    {
        $this
            ->setDescription('Add a short description for your command')
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $faker = \Faker\Factory::create('fr_FR');

        $answer = $io->ask("Truncating all tables... Sure ? [yes/no], no");
        if($answer !== "yes"){
            $io->text("aborttttttting");
            die();
        }

        $conn = $this->em->getConnection();

        $conn->query('SET FOREIGN_KEY_CHECKS = 0');
            //effacer le contenu des tables existantes
        $conn->query('TRUNCATE user');
        $conn->query('TRUNCATE excursion');
        $conn->query('TRUNCATE state');
        $conn->query('TRUNCATE excursion_user');

        $conn->query('SET FOREIGN_KEY_CHECKS = 1');

        $io->text("Tables are now empty...");

        // stocker et utiliser la table state dans les fixtures
        $states = ["En cours", "Fermé", "Ouvert", "En création"];

        $allStates = [];

        foreach ($states as $libelle){
            $state=new State();
            $state->setLibelle($libelle);
            $this->em->persist($state);

            $allStates[]=$state;
        }
        $this->em->flush();

        $io->progressStart(20);

        $roles = ["admin","user"];
        $categoriesName = ["Outil","Véhicule","Meuble","Jeu"];
        $categories = [];
        $users = [];

        $user = new User();
        $user->setUsername("test");
        $user->setMail("test@gmail.com");
        $user->setPassword($this->encoder->encodePassword($user, $user->getUsername()));
        $user->setRoles(["user"]);
        $user->setFirstName("William");
        $user->setName("Dufour");
        $user->setPhone("0652358417");
        $user->setActif(true);
        $user->setAdministrator(false);

        $this->em->persist($user);

        $users[] = $user;

        for($i=0;$i<20;$i++) {
            $io->progressAdvance(1);

            $user = new User();
            $user->setUsername($faker->name);
            $user->setMail($faker->email);
            $user->setPassword($this->encoder->encodePassword($user, $user->getUsername()));
            $user->setRoles($faker->randomElements($roles));
            $user->setFirstName($faker->name);
            $user->setName($faker->name);
            $user->setPhone($faker->numerify("##########"));
            $user->setActif(true);
            $user->setAdministrator(false);

            $this->em->persist($user);

            $users[] = $user;
        }

        //fixtures pour la table sortie pour affichage dans le tableau de la page accueil
            for($j=0;$j<10;$j++){
                $excursion=new Excursion();

                $excursion->setName($faker->city);
                $excursion->setStartDate($faker->dateTime);
                $excursion->setEndDate($faker->dateTime);
                $excursion->setRegistrationNumberMax($faker->numberBetween(5,20));
                $excursion->setState($faker->randomElement($allStates));
                $excursion->setOrganizer($faker->randomElement($users));

                $this->em->persist($excursion);
            }

        $this->em->flush();
        $io->progressFinish();


        $io->success("!Done");
    }
}