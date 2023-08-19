<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\UserRequest;
use App\Libs\MakePath;
use App\Models\Comment;
use App\Models\Document;
use App\Models\Download;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function setting(Request $request)
    {
        $id = \Auth::id();
        $user = User::where('id', $id)->first();
        return view('frontend_v4.pages.users.setting', compact('user'));
    }

    public function UpdateSetting(Request $request, $id)
    {
        try {
//            $user = User::where('id', $id)->first();
            $user = \Auth::user();
            if ($request->file('user_avatar')) {
                $oldImage = $user->getOriginal('avatar');
                \Storage::disk('public')->delete($oldImage);

                $name_img = $request->file('user_avatar')->getClientOriginalName();
                $path = 'public/avatar/' . $id;

                $file_path = $request->file('user_avatar')->store($path);
                $user->update([
                    'avatar_disk' => 'public',
                    'avatar' => str_replace("public/", "", $file_path)
                ]);
            }

            $user->update([
                'name' => $request->user_name,
                'phone' => $request->user_phone,
                'country' => $request->country,
                'language' => $request->language,
            ]);
            Session::flash('success', 'Update information success');
            return redirect()->back();
        } catch (\Exception $err) {
            Session::flash('error', 'Update information error');
            Log::info($err->getMessage());
            return redirect()->back();
        }
    }

    public function changePass(UserRequest $request, $id){

//        $user = User::where('id', $id)->first();
        $user = \Auth::user();
        if (!Hash::check($request->input('current_password'), $user->password)) {
            return redirect()->back()->withErrors(['current_password' => 'Incorrect current password']);
        }

        $user->update([
            'password' => Hash::make($request->input('new_password')),
        ]);
        Session::flash('success', 'Update password success');
        return redirect()->back();
    }

    public function profile(Request $request, $id)
    {
        $id = \Auth::id();
        $downloads = Download::with(['document', 'user'])->where('user_id', $id)->paginate(20);
        return view('frontend_v4.pages.users.profile', compact('downloads'));
    }

    public function documentUpload(Request $request, $id){
        $user = \Auth::user();
        $documents = Document::where('user_id', $user->id)
            ->where('active', true)
            ->where('is_public', true)
            ->orderByDesc('id')
            ->paginate(20);
        $comments = Comment::with('documents')->where('user_id', $user->id)->paginate(20);
        return view('frontend_v4.pages.users.document_upload', compact('documents', 'comments'));
    }

}
