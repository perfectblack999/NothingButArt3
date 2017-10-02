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
            $display = view('editRecruiterProfile', ['user' => $user, 'first_time_completing' => 1]);
        }
        elseif($user->profile_state == Enumerations::ACTIVATED && $user->type == "artist")
        {
            $images = $this->GetImages($user);
            $display = view('editArtistProfile', ['user' => $user, 'images' => $images, 'first_time_completing' => 1]);
        }
        elseif($user->profile_state == Enumerations::COMPLETE && $user->type == "artist")
        {
            $gridDetails = $this->GetImages($user);
            
            $display = view('home', ['user' => $user, 'imageDisplayLines' => $gridDetails[0], 
            'numberOfScreens' => $gridDetails[1], 'screenNumber' => 1, 'gridArtIDs' => $gridDetails[2],
            'imagePaths' => $gridDetails[3], 'homeView' => 2]);            
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
        
        if($user->image_ids != "")
        {
            $artIDs = explode(",", $user->image_ids);
        }
        
        $imagesPerScreen = 8;
        $gridArtIDPaths = DB::select("SELECT id,path FROM images WHERE user = (?) LIMIT 80", array($user->id));
        $numberOfScreens = ceil(count($gridArtIDPaths)/$imagesPerScreen);
        $artArrays = $this->createGrid($gridArtIDPaths);
        return array($artArrays[2], $numberOfScreens, $artArrays[0], $artArrays[1]);
    }
    
    private function createGrid($gridArtIDPaths)
    {
        $imageDisplayLines = array();
        $imagePaths = array();
        $imageIDs = array();
        
        foreach ($gridArtIDPaths as $gridArtIDPath)
        {
            array_push($imageDisplayLines, "<option data-img-src='art/".$gridArtIDPath->path."' value='".$gridArtIDPath->id."'>Image ".$gridArtIDPath->id."</option>");
            array_push($imagePaths, $gridArtIDPath->path);
            array_push($imageIDs, $gridArtIDPath->id);
        }
        
        $artArrays = array($imageIDs, $imagePaths, $imageDisplayLines);
        
        return $artArrays;
    }
    
    public function downloadResume()
    {
        $user = Auth::user();
        
        $pathToFile = "resume/".$user->resume;
        return response()->download($pathToFile);
    }
}
