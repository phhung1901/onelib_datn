<?php

namespace App\Service;

use App\Models\Document;
use App\Models\Download;
use App\Models\User;

class DownloadService
{
    public static function download($document)
    {
        if (session()->has('download_file')) {
            session()->forget('download_file');
        }

        $public_path = public_path('storage/'.$document->source_url);
        $user = \Auth::user() ?? User::first();
        $user->total_downloaded++;
        $user->save();

        $price = $document->price;
        if (file_exists($public_path)) {
            Download::create([
                'user_id' => $user->id,
                'document_id' => $document->id,
                'payload' => [
                    'price' => $price
                ]
            ]);
            $document->downloaded_count++;
            $document->save();
            return response()->download($public_path, $document->id.'.'.$document->type);
        } else {
            return response()->json(['error' => 'File not found.'], 404);
        }
    }
}
