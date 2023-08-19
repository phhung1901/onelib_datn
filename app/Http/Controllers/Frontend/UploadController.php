<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\DocumentRequest;
use App\Libs\MimeHelper;
use App\Models\Document;
use App\Service\CountPages;
use App\Service\MakePDF;
use App\Service\MakeText;
use Illuminate\Support\Facades\Session;


class UploadController extends Controller
{
    public function getUpload()
    {
        return view('frontend_v4.pages.upload.upload');
    }

    public function postUpload(DocumentRequest $request)
    {
        try {
            if ($file_upload = $request->file('source_url')) {
                $mimeType = $file_upload->getMimeType();
                $type = MimeHelper::getCode($mimeType);
                $disk = "public";
                $destination_path = 'public/pdftest';
                $file_path = $file_upload->store($destination_path);
                $last_path = str_replace("public/", "", $file_path);
                $document = Document::create([
                    'title' => $request->title,
                    'category_id' => $request->category,
                    'price' => $request->price,
                    'type' => $type,
                    'disks' => $disk,
                    'source_url' => $last_path,
                    'path' => $last_path,
                    'language' => $request->language,
                    'country' => $request->country,
                    'active' => false,
                    'is_public' => false,
                    'is_approved' => 1,
                    'can_download' => true
                ]);
                $document = MakePDF::makePdf($document);
                // Format size
                $size = $file_upload->getSize();

                $formattedSize = $document->formatSizeUnits($size);

                $total_page = CountPages::TotalPages($document);

                // Get fulltext
                $full_text = MakeText::makeText($document);
                // Generate description
                $description = MakeText::makeDescription($full_text);
                $document->original_size = $size;
                $document->original_format = $formattedSize;
                $document->full_text = $full_text;
                $document->description = $description;
                $document->page_number = $total_page;
                $document->active = true;
                $document->is_public = true;
                $document->save();

                if ($tags = $request->tag) {
                    foreach ($tags as $tag) {
                        \DB::table('document_tag')->insert([
                            'document_id' => $document->id,
                            'tag_id' => $tag,
                        ]);
                    }
                }


                Session::flash('success', 'Upload success');
                return redirect()->back();
            } else {
                Session::flash('error', 'You have not selected a document');
                return redirect()->back();
            }

        } catch (\Exception $err) {
            Session::flash('error', 'Upload failed!!!');
            return redirect()->back();
        }
    }
}
