<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Utilities\Enumerations;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $display = null;
        
        if($this->checkUser($user))
        {
            $display = $this->checkProfileState($user);
        }
        
        return $display;
    }
    
    private function checkUser($user)
    {
        $valid = false;
        
        if($user != null)
        {
            $valid = true;
        }
        
        return $valid;
    }
    
    private function checkProfileState($user)
    {
        $display = '';
        
        if($user->profile_state == Enumerations::REGISTERED)
        {
            auth()->logout();
            return back()->with('warning', 'You need to confirm your account. We have sent you an activation code, please check your email.');;
        }
        elseif($user->profile_state == Enumerations::EMAIL_CONFIRMED)
        {
            $display = view('activate', ['user' => $user]);
        }
        elseif($user->profile_state == Enumerations::ACTIVATED && $user->type == "recruiter")
        {
            $display = view('editRecruiterProfile', ['user' => $user]);
        }
        elseif($user->profile_state == Enumerations::ACTIVATED && $user->type == "artist")
        {
            $images = $this->GetImages($user);
            $display = view('editArtistProfile', ['user' => $user, 'images' => $images]);
        }
        elseif($user->profile_state == Enumerations::COMPLETE && $user->type == "artist")
        {
            $images = $this->GetImages($user);
            $display = view('home', ['user' => $user, 'images' => $images]);
        }
        else
        {
            $display = view('home', ['user' => $user]);
        }
        
        return $display;
    }
    
    private function GetImages($user)
    {
        $artIDs = null;
        $images = array();
        
        if($user->image_ids != "")
        {
            $artIDs = explode(",", $user->image_ids);
        }
        
        if($artIDs != null)
        {
            foreach ($artIDs as $artID)
            {
                $artFile = DB::select('select path from images where id = (?)', array($artID));
                $image = '<img src="art/'.$artFile[0]->path.'" style="width:50%;height:50%;">';
                $idAndImage = array($artID, $image);

                array_push($images, $idAndImage);
            }
        }
        
        return $images;
    }
    
    public function downloadResume()
    {
        $user = Auth::user();
        
        $pathToFile = "resume/".$user->resume;
        return response()->download($pathToFile);
    }
    
}
