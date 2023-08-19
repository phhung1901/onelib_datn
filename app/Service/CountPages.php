<?php

namespace App\Service;

class CountPages
{
    public static function TotalPages($document){
        $pdfPath = 'storage/'.$document->path;
        $pdf_content = file_get_contents(public_path($pdfPath));
        return preg_match_all("/\/Page\W/", $pdf_content, $dummy);
    }
}
