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
        
        if(!isset($user))
        {
            return redirect()->route('home');
        }
        
        if($this->CheckUser($user))
        {
            
            $gridDetails = $this->GetImages($user);

            return view('tagArt', ['user' => $user, 'imageDisplayLines' => $gridDetails[0], 
            'numberOfScreens' => $gridDetails[1], 'screenNumber' => 1, 'gridArtIDs' => $gridDetails[2],
            'imagePaths' => $gridDetails[3], 'view' => 3]); 
            
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
        
        $imagesPerScreen = 8;
        $gridArtIDPaths = DB::select("SELECT id,path FROM images WHERE user = (?) LIMIT 90", array($user->id));
        $numberOfScreens = ceil(count($gridArtIDPaths)/$imagesPerScreen);
        $artArrays = $this->createGrid($gridArtIDPaths);
        return array($artArrays[2], $numberOfScreens, $artArrays[0], $artArrays[1]);
    }
    
    private function createGrid($gridArtIDPaths)
    {
        // initialized with blank so nothing is initially selected
        $imageDisplayLines = array('<option value="0"></option>');
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
        
        $imageID = filter_input(INPUT_POST, 'imageID', FILTER_SANITIZE_STRING);
        $tag1 = filter_input(INPUT_POST, 'tag1', FILTER_SANITIZE_STRING);
        $tag2 = filter_input(INPUT_POST, 'tag2', FILTER_SANITIZE_STRING);
        $tag3 = filter_input(INPUT_POST, 'tag3', FILTER_SANITIZE_STRING);
        $story = filter_input(INPUT_POST, 'story', FILTER_SANITIZE_STRING);

        $picRow = DB::update('UPDATE images SET tag1 = (?), tag2 = (?), tag3 = (?), '
                . 'story = (?) where id = (?)', array($tag1, $tag2, $tag3, $story, $imageID));
        
        return $picRow;
    }
    
    public function GetTag()
    {
        $imageID = filter_input(INPUT_GET, 'imageID', FILTER_SANITIZE_STRING);

        $picRow = DB::select('SELECT tag1, tag2, tag3, story from images where '
                . 'id = (?)', array($imageID));
        
        return $picRow;
    }
    
    public function DeletePic()
    {
        try
        {
            $imageID = filter_input(INPUT_POST, 'imageID', FILTER_SANITIZE_STRING);
            $picIdAndUser = DB::select('SELECT id, user FROM images WHERE id = (?)', array($imageID));
            $picRowResult = DB::update('UPDATE images SET user = (?) WHERE id = (?)', array("", $imageID));
            
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
