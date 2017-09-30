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
        $browseArtView = 1;
        $imagesPerScreen = 8;
        $user = Auth::user();
        $totalImageCount = DB::select('SELECT COUNT(id) as count FROM images WHERE user <> 0');
        $numberOfImageRequests = floor($totalImageCount[0]->count/80);
        $offset = rand(0, $numberOfImageRequests);
        $offset = $offset * 80;
        $gridArtIDPaths = DB::select("SELECT id,path FROM images WHERE user <> 0 LIMIT 90 OFFSET $offset");
        shuffle($gridArtIDPaths);
        $numberOfScreens = ceil(count($gridArtIDPaths)/$imagesPerScreen);
        $artArrays = $this->createGrid($gridArtIDPaths);
        
        return view('browseArtGrid', ['user' => $user, 'imageDisplayLines' => $artArrays[2], 
            'numberOfScreens' => $numberOfScreens, 'screenNumber' => 1, 'gridArtIDs' => $artArrays[0],
            'imagePaths' => $artArrays[1], 'browseArtView' => $browseArtView]);
    }
    
    public function NextBrowsePage()
    {
        $screenNumber = filter_input(INPUT_GET, 'screen_number', FILTER_SANITIZE_STRING);
        $gridArtIDs = explode(",", filter_input(INPUT_GET, 'grid_art_ids', FILTER_SANITIZE_STRING));
        $imagePaths = explode(",", filter_input(INPUT_GET, 'image_paths', FILTER_SANITIZE_STRING));

        for ($i = ($screenNumber - 1) * 8; $i < (($screenNumber - 1) * 8) + 8; $i++)
        {
            if (isset($gridArtIDs[$i]) && isset($imagePaths[$i]))
            {
                echo "<option data-img-src='art/".$imagePaths[$i]."' value='".$gridArtIDs[$i]."'>Image ".$gridArtIDs[$i]."</option>";
            }
        }
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
}
