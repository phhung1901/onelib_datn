<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    public function view(Request $request, $filename){
        $pdf_path = 'storage/pdffile/'.$filename;
        return view('frontend.layouts.detail', compact('pdf_path'));
    }
}
