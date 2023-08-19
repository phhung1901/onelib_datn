<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function listDocument(Request $request, $slug){
        $tag = Tag::where('slug', $slug)->first();
        $documents = Document::whereHas('tags', function ($query) use ($tag){
            $query->where('tag_id', $tag->id);
        })->paginate(20);
        return view('frontend_v4.pages.document.list_by_tag', compact('tag','documents'));
    }
}
