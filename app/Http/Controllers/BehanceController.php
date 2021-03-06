<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Behance;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BehanceController extends Controller
{
    public function ShowForm()
    {
        $user = Auth::user();
        
        if(!isset($user))
        {
            return redirect()->route('home');
        }
        
        $display = view('behance');
        
        return $display;
    }
    
    public function GetData(Request $request)
    {
        $user = Auth::user();
        
        if(!isset($user))
        {
            return redirect()->route('home');
        }
        
        $apiKey = "1BC1t2W67XUIaT8q4yiMyiibgxXPnytd";
        $apiUsername = $request->username;

        $client = new Behance\Client($apiKey);
        $images = [];

        if(!empty($apiUsername))
        {
            $dataProjects = $client->getUserProjects($apiUsername);
            $dataProjectIds = [];
            
            foreach($dataProjects as $dataProject)
            {
                array_push($dataProjectIds, $dataProject->id);
            }

            $gridInputs = $this->GetUserImages($dataProjectIds, $apiKey);
        }
        else
        {
            $dataProjects = [];
        }
        
        
        $display = view('behance', ['images' => $gridInputs[0], 'numberOfScreens' => $gridInputs[1],
                'screenNumber' => 1]);
        
        return $display;
    }
    
    private function GetUserImages($dataProjectIds, $apiKey)
    {
        $images = [];
        $imagesPerScreen = 8;
        
        try
        {
            foreach ($dataProjectIds as $dataProjectId)
            {
                foreach (json_decode(file_get_contents("https://api.behance.net/v2/projects/".
                        $dataProjectId."?api_key=".$apiKey))->project->modules as $module)
                {
                    if (isset($module->src))
                    {
                        array_push($images, $module->src);
                    }
                }
            }
        }
        catch (ErrorException $e)
        {
            return [0, 0];
        }
        
        $numberOfScreens = ceil(count($images)/$imagesPerScreen);
        
        return [$images, $numberOfScreens];
    }
    
    public function ImportImages()
    {
        $selectedImages = filter_input(INPUT_POST, 'selected_images', FILTER_SANITIZE_STRING);
        $selectedImagesArray = explode(",", $selectedImages);
        $fileNames = $this->StoreArt($selectedImagesArray);
        $this->PutArtInDB($fileNames);
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
    
    public function HomeBehanceImport()
    {
        $user = Auth::user();
        $images = $this->GetImages($user);
        
        return redirect()->route('home', ['user' => $user]);
    }
    
    private function StoreArt($imageURLs)
    {
        $fileNames = array();
        
        foreach ($imageURLs as $imageURL)
        {
            $destinationPath = 'art';
            $image = file_get_contents($imageURL);
            $extension = "jpg"; 
            $fileName = rand(111111111111,999999999999).'.'.$extension;
            file_put_contents($destinationPath.'/'.$fileName, $image);
            array_push($fileNames, $fileName);
        }
        
        return $fileNames;
    }
    
    private function PutArtInDB($imageFileNames)
    {        
        $user = Auth::user();
        
        foreach ($imageFileNames as $imageFileName)
        {
            $insertSuceess = DB::insert('insert into images (path, user) values(?,?)', [$imageFileName, $user->id]);
            
            if($insertSuceess)
            {
                $artID = DB::select('select id from images where path = (?)', array($imageFileName));
            }
            else
            {
                echo "Upload failed.";
            }
            
            $this->AddArtID($artID[0]->id);
        }
    }
    
    private function AddArtID($id)
    {
        $user = Auth::user();
        
        if($user->image_ids == "")
        {
            $user->image_ids = $id;
        }
        else
        {
            $user->image_ids = $user->image_ids.",".$id;
        }
        
        $user->save();
    }
    
    public function NextBehancePage()
    {   
        $screenNumber = filter_input(INPUT_GET, 'screen_number', FILTER_SANITIZE_STRING);
        $images = explode(",", filter_input(INPUT_GET, 'images', FILTER_SANITIZE_STRING));

        for ($i = ($screenNumber - 1) * 8; $i < (($screenNumber - 1) * 8) + 8; $i++)
        {
            if (isset($images[$i]))
            {
                echo "<option data-img-src='".$images[$i]."' value='".$images[$i]."'>Image ".$images[$i]."</option>";
            }
        }
    }
}
