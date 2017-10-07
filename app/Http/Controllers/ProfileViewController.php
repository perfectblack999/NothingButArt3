<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests;
use DB;

class ProfileViewController extends Controller
{
    public function index(){
        echo "index";
    }
    
    public function showRecruiterProfile($id)
    {  
        $user = Auth::user();
        
        if(!isset($user))
        {
            return redirect()->route('home');
        }
        
        $profileInfo = DB::select('select * from users where id = (?)', array($id));

        return view('viewRecruiterProfile', ['profile' => $profileInfo]);       
    }
    
    public function showArtistProfile(int $id)
    {
        $user = Auth::user();
        
        if(!isset($user))
        {
            return redirect()->route('home');
        }
        
        $profileInfo = DB::select('select * from users where id = (?)', array($id));

        return view('viewArtistProfile', ['profile' => $profileInfo]);
    }
}
