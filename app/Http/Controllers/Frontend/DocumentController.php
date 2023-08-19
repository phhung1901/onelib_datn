<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Document;
use App\Models\Report;
use App\Models\Tag;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class DocumentController extends Controller
{

    public function index()
    {
        $documents = Document::with('categories')
            ->where('active', true)
            ->where('is_public', true)
            ->where('page_number', "<=", 200)
            ->orderByDesc('created_at')
            ->limit(9)
            ->get();

        $top_documents = Document::with('categories')
            ->where('active', true)
            ->where('is_public', true)
            ->orderByDesc('viewed_count')
            ->limit(20)
            ->get();

        $categories = Category::all();

        return view('frontend_v4.pages.home.index', compact('documents', 'top_documents', 'categories'));
    }

    public function view(Request $request, $slug)
    {
        $document = Document::where('slug', $slug)
            ->first();
        if (!$document) {
            $document = Document::where('id', 1)
                ->where('active', true)
                ->where('is_public', true)
                ->first();
        }
        $document->viewed_count++;
        $document->save();

        $top_documents = Document::with('categories')
            ->where('active', true)
            ->where('is_public', true)
            ->where('category_id', $document->category_id)
            ->where('id', '!=',$document->id)
            ->orderByDesc('viewed_count')
            ->limit(6)
            ->get();

        $tags = Tag::whereHas('documents', function ($query) use ($document){
            $query->where('document_id', $document->id);
        })->get();

        $comments = Comment::with('users')->where('document_id', $document->id)->paginate('20');
        return view('frontend_v4.pages.document.detail', compact('document', 'comments', 'top_documents', 'tags'));
    }

    public function search(Request $request)
    {
        $searchQuery = $request->input('search');
        $documents = Document::where('active', true)
            ->where('is_public', true)
            ->where('title', 'like', '%' . $searchQuery . '%')
            ->paginate(20);
        return view('frontend_v4.pages.search.search', compact('documents'));
    }

    public function edit(Request $request, $id)
    {
        $document = Document::where('user_id', \Auth::id())->where('id', $id)->first();
        return view('frontend_v4.pages.upload.update', compact('document'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'title' => 'required|min:5|max:255',
            'price' => 'required|min:0',
        ]);
        try {
            $document = Document::where('user_id', \Auth::id())->where('id', $id)->first();
            $document->title = $request->title;
            $document->category_id = $request->category;
            $document->price = $request->price;
            $document->language = $request->language;
            $document->country = $request->country;
            $document->save();
            Session::flash('success', 'Update document success');
            return redirect()->back();
        } catch (\Exception $err) {
            Session::flash('error', 'Update document failed!!!');
            return redirect()->back();
        }
    }

    public function delete($id)
    {
        try {
            $document = Document::where('user_id', \Auth::id())->where('id', $id)->first();
            $document->active = false;
            $document->is_public = false;
            $document->save();
//            Session::flash('success', 'Delete document success');
            return redirect()->route('frontend_v4.users.document_upload', ['id' => \Auth::id()]);
        } catch (\Exception $err) {
//            Session::flash('error', 'Delete document failed!!!');
            return redirect()->route('frontend_v4.users.document_upload', ['id' => \Auth::id()]);
        }
    }

    public function like(Request $request, $slug)
    {
        $document = Document::where('slug', $slug)
            ->first();
        $currentTime = strtotime(Carbon::now()->toTimeString());
        $lastHelpfulTime = strtotime(Carbon::parse($document->updated_at)->toTimeString());
        $timeDiff = $currentTime - $lastHelpfulTime;
//        if ($timeDiff >= 60){
        $document->helpful_count++;
        $document->save();
//            }
        return redirect()->back();
    }

    public function dislike(Request $request, $slug)
    {
        $document = Document::where('slug', $slug)
            ->first();
        $currentTime = Carbon::now();
        $lastHelpfulTime = Carbon::parse($document->updated_at);
        $timeDiff = $currentTime->diffInMinutes($lastHelpfulTime);
//        if ($timeDiff >= 1) {
        $document->unhelpful_count++;
        $document->save();
//        }
        return redirect()->back();
    }

    public function report(Request $request, $slug)
    {
        $message_report = "";
        if ($request->report_radio == 'other') {
            $message_report = $request->message_other;
        } else {
            $report_values = config('report_value');
            foreach ($report_values as $key => $report_value) {
                if ($request->report_radio == $key) {
                    $message_report = $report_value;
                }
            }
        }
        $document = Document::where('slug', $slug)
            ->first();
        Report::create([
            'document_id' => $document->id,
            'message' => $message_report
        ]);
        return "REPORT SUCCESS";
    }

    public function comment(Request $request, $slug)
    {
        $message = $request->message_comment;
        $document = Document::where('slug', $slug)
            ->first();
        Comment::create([
            'document_id' => $document->id,
            'user_id' => \Auth::id(),
            'content' => $message
        ]);
        return redirect()->back();
    }

}
