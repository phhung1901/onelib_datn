<?php

namespace App\Service;

use App\DocumentProcess\Builder;
use App\Libs\Nlp\Description\TextRankGenerator;
use App\Models\Document;

class MakeText
{
    /**
     * @throws \Exception
     */
    public static function makeText(Document $document){

        $document_process = Builder::fromDocument($document)->get();
        return $document_process->makeFulltext();
    }

    public static function makeDescription($full_text){
        $generator = TextRankGenerator::fromDSFullText($full_text);
        $description = $generator->getDescription();
        return mb_substr($description, 0, 186) . "[r]";
    }
}
