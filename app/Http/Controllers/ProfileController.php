<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Rules\MatchOldPassword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function profile()
    {
        $profile = Auth::user();

        return view('adminData.profile', compact(['profile']));
    }


    public function profileUpdate(ProfileUpdateRequest $request)
    {
        $update = DB::table('users')
            ->where('id', Auth::user()->id)
            ->update(['name' => $request->name, 'email' => $request->email]);

        if ($update) {
            return response()->json(['status' => '200', 'msg' => 'Profile Updated']);
        }
    }


    public function profilePasswordUpdate(Request $request)
    {
        $request->validate([
            'old_password' => ['required', new MatchOldPassword],
            'new_password' => ['required'],
        ]);

        $update = User::find(auth()->user()->id)
            ->update(['password' => Hash::make($request->new_password)]);

        if ($update) {
            return response()->json(['status' => '200', 'msg' => 'Password Changed']);
        }
    }
}
