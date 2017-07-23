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
            $images = $this->GetImages($user);

            return view('tagArt', ['user' => $user, 'images' => $images]);
        }
        else
        {
            return redirect()->route('home');
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
}