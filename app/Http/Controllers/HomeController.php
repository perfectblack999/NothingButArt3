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
    
//    public function login()
//    {
//        $numberOfPreviews = 6;
//        $totalImageCount = DB::select('SELECT COUNT(id) as count FROM images WHERE user <> 0');
//        $numberOfBatches = floor($totalImageCount[0]->count / $numberOfPreviews);
//        $offsetBatch = rand(0, $numberOfBatches);
//        $offset = $offsetBatch * $numberOfBatches;
//        $previewIDPaths = DB::select("SELECT id,path FROM images WHERE user <> 0 LIMIT $numberOfPreviews OFFSET $offset");
//
//        return view('login', ['previewIDPaths' => $previewIDPaths]);
//    }
    
    private function checkUser($user)
    {
        $valid = false;
        
        if($user != null)
        {
            $valid = true;
        }
        
        return $valid;
    }
    
    private function checkProfileState()
    {
        $display = '';
        $user = Auth::user();
        
        if(!isset($user))
        {
            return redirect()->route('home');
        }
        
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
            $progressDetail = $this->calculateProfileProgress();
            $progress = array_sum($progressDetail) * 20;
            
            $display = view('home', ['user' => $user, 'imageDisplayLines' => $gridDetails[0], 
            'numberOfScreens' => $gridDetails[1], 'screenNumber' => 1, 'gridArtIDs' => $gridDetails[2],
            'imagePaths' => $gridDetails[3], 'homeView' => 2, 'progress' => $progress, 
            'progressDetail' => $progressDetail]);            
        }
        else
        {
            $progressDetail = $this->calculateProfileProgress();
            $progress = array_sum($progressDetail) * 20;
            
            $display = view('home', ['user' => $user, 'progress' => $progress, 
            'progressDetail' => $progressDetail]);
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
    
    private function calculateProfileProgress()
    {
        $user = Auth::user();
        $progressMeter = array("fields" => 0, "portfolio" => 0, "resume" => 0, "pics" => 0, "stories" => 0, );
        
        $completionMeterAttributes = array($user->first_name, $user->last_name,
            $user->email, $user->zip, $user->portfolio, $user->resume, $user->image_ids);
        
        if ($completionMeterAttributes[0] != "" && 
            $completionMeterAttributes[1] != "" &&
            $completionMeterAttributes[2] != "" &&
            $completionMeterAttributes[3] != "")
        {
            $progressMeter["fields"] = 1;
        }
        
        if ($completionMeterAttributes[4] != "")
        {
            $progressMeter["portfolio"] = 1;
        }
        
        if ($completionMeterAttributes[5] != "")
        {
            $progressMeter["resume"] = 1;
        }
        
        if (count(explode(",", $completionMeterAttributes[6])) > 4)
        {
            $progressMeter["pics"] = 1;
        }
        
        if($this->allStoriesComplete($completionMeterAttributes[6]) == 1)
        {
            $progressMeter["stories"] = 1;
        }
        
        return $progressMeter;
    }
    
    private function allStoriesComplete($imageIDs)
    {
        $allStoriesComplete = 1;
        $imageIDsArray = explode(",", $imageIDs);
        $input = join(',', array_fill(0, count($imageIDsArray), '?'));
        $stories = DB::select("SELECT story FROM images WHERE id IN ($input)", $imageIDsArray);
        
        if (isset($stories[0]))
        {
            foreach ($stories as $story)
            {
                if (!isset($story))
                {
                   $allStoriesComplete = 0;
                   break;
                }
            }
        }
        else 
        {
            $allStoriesComplete = 0;
        }
        
        return $allStoriesComplete;
    }
    
    public function downloadResume()
    {
        $user = Auth::user();
        
        $pathToFile = "resume/".$user->resume;
        return response()->download($pathToFile);
    }
}
