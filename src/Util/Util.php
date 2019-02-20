<?php

namespace App\Util;

class Util
{

    function randomString()
    {
        $string = "";
        $chaine = "abcdefghijklmnpqrstuvwxy123456789";
        srand((double)microtime() * 1000000);

        for ($i = 0; $i < 40; $i++) {
            $string .= $chaine[rand() % strlen($chaine)];
        }

        return $string;
    }

}