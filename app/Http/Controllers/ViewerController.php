<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ViewerController extends Controller
{
    public function view(Request $request, $filename){
        $pdf_path = 'storage/pdffile/'.$filename;
        return view('viewer.index', compact('pdf_path'));
    }
}
