<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use DB;

class BioController extends Controller
{
    public function LoadBio()
    {
        $user = Auth::user();

        if(!isset($user))
        {
            return redirect()->route('home');
        }
        
        $bio = DB::select("SELECT bio FROM users WHERE id = (?)", array($user->id))[0]->bio;
        
        return view('artistBio', ['bio' => $bio]);
    }
    
    public function SaveBio()
    {
        $user = Auth::user();
        $bio = filter_input(INPUT_POST, 'bio', FILTER_SANITIZE_STRING);
        
        $user->bio = $bio;
        $user->save();
        
        return redirect()->route('home');
    }
}
