<?php

namespace App\Service;

use App\DocumentProcess\Converter\DocumentConverter;
use App\Libs\MakePath;
use App\Models\Document;

class MakePDF
{
    public static function makePdf(Document $document): Document
    {
        $path = $document->source_url ?? $document->path;
        $original_file = \Storage::disk('public')->get($path);
        $convertor = new DocumentConverter();
        if ($path) {
            $pdf_content = $convertor->convert(
                content: $original_file,
                input_format: $document->type,
                output_format: 'pdf',
            );
        } else {
            $pdf_content = $original_file;
        }
        $path = 'pdf_maked/' . MakePath::make($document->id, '') . ".pdf.pdf";
        $saved = \Storage::disk('public')->put($path, $pdf_content);

        if ($saved) {
            $document->update([
                'path' => $path
            ]);
        }
        return $document;
    }
}
