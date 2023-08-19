<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Document;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function listDocument(Request $request, $slug){
        $category = Category::where('slug', $slug)->first();
        $documents = Document::select(['title', 'slug', 'viewed_count', 'downloaded_count', 'helpful_count', 'page_number','user_id', 'category_id', 'created_at'])
            ->where('category_id', $category->id)
        ->paginate(20);
        return view('frontend_v4.pages.document.list_document', compact('category','documents'));
    }
}
