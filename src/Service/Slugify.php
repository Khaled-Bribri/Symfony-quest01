<?php

namespace App\Service;

class Slugify
{
    public function generate(string $input): string
    {
        
        //les caractères accentués sont remplacés par leur équivalent non accentué;
        $input=iconv('UTF-8', 'ASCII//TRANSLIT',$input);
        //!, apostrophes et autres ponctuations sont supprimées;
        $input=preg_replace(['/[.,\/#!$%\^&\*;:{}=\-_`~()]/','/--+/',],"",strtolower($input));
        //les espaces en début et fin de chaînes sont supprimées
        $input=trim($input);

        return $input;
    }
} 


?>