<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class BrowseArtController extends Controller
{
    public function Display(Request $request)
    {
        $request_number = 0;
        $imagesPerScreen = 9;
        $user = Auth::user();
        $totalImageCount = DB::select('SELECT COUNT(id) as count FROM images WHERE user <> 0');
        $numberOfImageRequests = floor($totalImageCount[0]->count/90);
        $imageRequestArray = range(0, $numberOfImageRequests);
        shuffle($imageRequestArray);
        $firstOffset = array_shift($imageRequestArray) * 90;
        $gridArtIDPaths = DB::select("SELECT id,path FROM images WHERE user <> 0 LIMIT 90 OFFSET $firstOffset");
        shuffle($gridArtIDPaths);
        $numberOfScreens = ceil(count($gridArtIDPaths)/$imagesPerScreen);
        $artArrays = $this->createGrid($gridArtIDPaths);
        
        return view('browseArtGrid', ['user' => $user, 'imageDisplayLines' => $artArrays[2], 
            'numberOfScreens' => $numberOfScreens, 'screenNumber' => 1, 'gridArtIDs' => $artArrays[0],
            'imageRequestArray' => $imageRequestArray, 'imagePaths' => $artArrays[1]]);
    }
    
    public function NextBrowsePage()
    {
        $screenNumber = filter_input(INPUT_GET, 'screen_number', FILTER_SANITIZE_STRING);
        $gridArtIDs = explode(",", filter_input(INPUT_GET, 'grid_art_ids', FILTER_SANITIZE_STRING));
        $imagePaths = explode(",", filter_input(INPUT_GET, 'image_paths', FILTER_SANITIZE_STRING));
        var_dump($imagePaths);
        for ($i = ($screenNumber - 1) * 9; $i < (($screenNumber - 1) * 9) + 9; $i++)
        {
            if (isset($gridArtIDs[$i]) && isset($imagePaths[$i]))
            {
                echo "<option data-img-src='art/".$imagePaths[$i]."' value='".$gridArtIDs[$i]."'>Image ".$gridArtIDs[$i]."</option>";
            }
        }
    }
    
    private function createGrid($gridArtIDPaths)
    {
        $artArrays = array();
        $imageDisplayLines = array();
        $imagePaths = array();
        $imageIDs = array();
        
        foreach ($gridArtIDPaths as $gridArtIDPath)
        {
            array_push($imageDisplayLines, "<option data-img-src='art/".$gridArtIDPath->path."' value='".$gridArtIDPath->id."'>Image ".$gridArtIDPath->id."</option>");
            array_push($imagePaths, $gridArtIDPath->path);
            array_push($imageIDs, $gridArtIDPath->id);
        }
        
        array_push($artArrays, $imageIDs);
        array_push($artArrays, $imagePaths);
        array_push($artArrays, $imageDisplayLines);
        
        return $artArrays;
    }
}
