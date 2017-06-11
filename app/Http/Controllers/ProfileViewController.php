<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use DB;

class ProfileViewController extends Controller
{
    public function index(){
        echo "index";
    }
    
    public function showRecruiterProfile($id){  
        
        $profileInfo = DB::select('select * from users where id = (?)', array($id));

        return view('viewRecruiterProfile', ['profile' => $profileInfo]);       
    }
    
    public function showArtistProfile(int $id){
        
        $profileInfo = DB::select('select * from users where id = (?)', array($id));

        return view('viewArtistProfile', ['profile' => $profileInfo]);
    }
}
