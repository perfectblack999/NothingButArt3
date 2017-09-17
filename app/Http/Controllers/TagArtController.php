<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use DB;

class TagArtController extends Controller
{
    public function ShowArt()
    {
        $user = Auth::user();
        
        if($this->CheckUser($user))
        {
//            $images = $this->GetImages($user);
            
            $gridDetails = $this->GetImages($user);

            return view('tagArt', ['user' => $user, 'imageDisplayLines' => $gridDetails[0], 
            'numberOfScreens' => $gridDetails[1], 'screenNumber' => 1, 'gridArtIDs' => $gridDetails[2],
            'imagePaths' => $gridDetails[3], 'view' => 3]); 
            
//            return view('tagArt', ['user' => $user, 'images' => $images]);
        }
        else
        {
            return redirect()->route('home', ['homeView' => 1]);
        }
    }
    
    private function CheckUser($user)
    {
        $valid = false;
        
        if($user != null)
        {
            $valid = true;
        }
        
        return $valid;
    }
    
    private function GetImages($user)
    {
        $artIDs = null;
        
        if($user->image_ids != "")
        {
            $artIDs = explode(",", $user->image_ids);
        }
        
        $imagesPerScreen = 9;
        $gridArtIDPaths = DB::select("SELECT id,path FROM images WHERE user = (?) LIMIT 90", array($user->id));
        $numberOfScreens = ceil(count($gridArtIDPaths)/$imagesPerScreen);
        $artArrays = $this->createGrid($gridArtIDPaths);
        return array($artArrays[2], $numberOfScreens, $artArrays[0], $artArrays[1]);
    }
    
    private function createGrid($gridArtIDPaths)
    {
        // initialized with blank so nothing is initially selected
        $imageDisplayLines = array('<option value=""></option>');
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
    
    public function SaveTag()
    {
        
        $fileName = filter_input(INPUT_POST, 'fileName', FILTER_SANITIZE_STRING);
        $tag1 = filter_input(INPUT_POST, 'tag1', FILTER_SANITIZE_STRING);
        $tag2 = filter_input(INPUT_POST, 'tag2', FILTER_SANITIZE_STRING);
        $tag3 = filter_input(INPUT_POST, 'tag3', FILTER_SANITIZE_STRING);

        $picRow = DB::update('UPDATE images SET tag1 = (?), tag2 = (?), tag3 = (?) '
                . 'where path = (?)', array($tag1, $tag2, $tag3, $fileName));
        
        return $picRow;
    }
    
    public function GetTag()
    {
        $fileName = filter_input(INPUT_GET, 'fileName', FILTER_SANITIZE_STRING);

        $picRow = DB::select('SELECT tag1, tag2, tag3 from images where '
                . 'path = (?)', array($fileName));
        
        return $picRow;
    }
    
    public function DeletePic()
    {
        $user = Auth::user();
        
        try
        {
            $fileName = filter_input(INPUT_POST, 'fileName', FILTER_SANITIZE_STRING);
            $picIdAndUser = DB::select('SELECT id, user FROM images WHERE path = (?)', array($fileName));
            $picRowResult = DB::update('UPDATE images SET user = (?) WHERE path = (?)', array("", $fileName));
            
            if($picRowResult)
            {
                $userImageIDs = DB::select('SELECT image_ids FROM users WHERE id = (?)', array($picIdAndUser[0]->user));
                $userImageIDs = explode(",", DB::select('SELECT image_ids FROM users WHERE id = (?)', array($picIdAndUser[0]->user))[0]->image_ids);
                $idIndex = array_search($picIdAndUser[0]->id, $userImageIDs);
                unset($userImageIDs[$idIndex]);
                $userImageIDs = implode(",", $userImageIDs);
                $picRowResult = DB::update('UPDATE users SET image_ids = (?) WHERE id = (?)', array($userImageIDs, $picIdAndUser[0]->user));
            }
        }
        catch(Exception $e)
        {
            return $e->getMessage();
        }

    }
}
