<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


class ImportController extends AbstractController
{
    /**
     * @Route("/import", name="import", methods={"POST"})
     */
    public function importDataCSV ()
    {
        // tab qui va contenir les éléments extraits du fichier CSV
        $users =array();
        //représente la ligne
        $row = 0;

        //ouverture du fichier, lecture de la première à la dernière ligne
        if (($handle = fopen(__DIR__ . "/public/csv/participants.csv", "r")) !== FALSE){
            while (($data= fgetcsv($handle, 100, ";")) !== FALSE) {
                // nombre d'éléments sur la ligne traitée
                $num = count($data);
                $row++;
                for ($c=0; $c < $num; $c++){
                    $users[$row] = array(
                        "username"=>$data[0],
                        "name"=>$data[1],
                        "firstname"=>$data[2],
                        "phone"=>$data[3],
                        "email"=>$data[4],
                    );
                }
            }
            fclose($handle);
        }

        //Entity Mamnager pour la base de données
        $em = $this->getDoctrine()->getManager();

        //Lecture du tableau contenant les utilisateurs et ajout dans la base de données
        foreach ($users as $user) {
            $user=new User();

            $user->setName($user["username"]);
            $user->setMail($user["name"]);
            $user->setFirstName($user["firstname"]);
            $user->setPhone($user["phone"]);
            $user->setUsername($user["email"]);

            $em->persist($user);
        }
        // écriture en base de données
        $em->flush();
    }


}
