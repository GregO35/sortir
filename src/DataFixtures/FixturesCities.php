<?php

namespace App\DataFixtures;

use App\Entity\City;
use App\Entity\Place;
use App\Entity\Site;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class FixturesCities extends Command
{
    protected static $defaultName = 'app:fixturesCities';
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
            ->setDescription('Fixtures pour créeer des villes et des lieux')
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

        $conn->query('TRUNCATE site');
        $conn->query('TRUNCATE place');
        $conn->query('TRUNCATE city');

        $conn->query('SET FOREIGN_KEY_CHECKS = 1');

        $io->text("Tables are now empty...");

        $io->progressStart(40);

        //Stocker des villes dans la table Site
        $sites = ['NANTES','RENNES','LA ROCHE SUR YON', 'CLISSON','CHOLET','VANNES','LA BAULE','ANGERS','LE MANS','LAVAL'];
        $allSites = [];
        foreach ($sites as $siteName){
            $io->progressAdvance(1);
            $site = new Site();
            $site->setName($siteName);
            $this->em->persist($site);

            $allSites[]=$site;
        }
        $this->em->flush();

        // stocker et utiliser la table City dans les fixtures
        for($i=0;$i<10;$i++) {
            $io->progressAdvance(3);
            $city= new City();
            $city->setName($faker->city);
            $city->setZip($faker->postcode);
            $this->em->persist($city);
            $allCities[]=$city;
        }
        $this->em->flush();

        // stocker et utiliser la table Place dans les fixtures
        $places = ['Stade de foot', 'Musée histoire naturelle', 'Cinéma', 'Patinoire', 'Parc', 'Machines de l île', 'Marché', 'Aérodrome', 'Parc de jeux', 'Plage', 'Circuit de kart'];
        $allPlaces = [];

        foreach ($places as $name){
            $io->progressAdvance(2);
            $place=new Place();
            $place->setName($name);
            $place->setStreet($faker->streetAddress);
            $place->setLatitude($faker->latitude);
            $place->setLongitude($faker->longitude);
            $place->setCity($faker->randomElement($allCities));
            $this->em->persist($place);

            $allPlaces[]=$place;
        }
        $this->em->flush();



        $io->progressFinish();

        $io->success("!Done");
    }
}