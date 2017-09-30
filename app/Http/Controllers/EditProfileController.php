<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Utilities\Enumerations;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use DB;

class editProfileController extends Controller
{
    public function ActivateProfile(Request $request)
    {
        $user = Auth::user();
        $user->profile_state = Enumerations::ACTIVATED;
        if($request->input('type') !== null)
        {
            $user->type = $request->input('type');
        }
        $user->save();
        
        if ($user->type == "recruiter")
        {
            $profileView = redirect()->route('editRecruiterProfile', ['user' => $user]);
        }
        else
        {
            $profileView = redirect()->route('editArtistProfile', ['user' => $user]);
        }
        
        return $profileView;
    }
    
    public function EditRecruiterProfile(Request $request)
    {
        $user = Auth::user();
        $dOption = $request->input('dOption');

        return view('editRecruiterProfile', ['user' => $user, 'dOption' => $dOption]);
    }
    
    public function EditArtistProfile(Request $request)
    {
        $user = Auth::user();
        $images = $this->GetImages($user);
        $dOption = $request->input('dOption');
        
        return view('editArtistProfile', ['user' => $user, 'images' => $images, 'dOption' => $dOption]);
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
    
    public function UpdateRecruiterProfile(Request $request)
    {
        $this->CompleteRecruiterProfileValidator($request);
        $zipCodeLatLon = DB::select('SELECT latitude, longitude from zip_codes where zip = (?)', array($request->input('zip_code')));
        
        $user = Auth::user();
        $user->first_name = $request->input('first_name');
        $user->last_name = $request->input('last_name');
        $user->phone = $request->input('phone');
        $user->company = $request->input('company');
        $user->street_address1 = $request->input('street_address1');
        $user->street_address2 = $request->input('street_address2');
        $user->city = $request->input('city');
        $user->state = $request->input('state');
        $user->zip = $request->input('zip_code');
        $user->latitude = $zipCodeLatLon[0]->latitude;
        $user->longitude = $zipCodeLatLon[0]->longitude;
        $user->profile_state = Enumerations::COMPLETE;
        $user->type = "recruiter";
        $user->save();
        
        return redirect()->route('home', ['user' => $user]);
    }
       
    private function CompleteRecruiterProfileValidator($request)
    {
        $this->validate($request, [
            'phone' => 'required',
            'company' => 'required',
            'zip_code' => 'required',
        ]);
    }

    public function UpdateArtistProfile(Request $request)
    {
        $this->CompleteArtistProfileValidator($request);
        $zipCodeLatLon = DB::select('SELECT latitude, longitude from zip_codes where zip = (?)', array($request->input('zip_code')));

        $user = Auth::user();
        $user->first_name = $request->input('first_name');
        $user->last_name = $request->input('last_name');
        $user->phone = $request->input('phone');
        $user->city = $request->input('city');
        $user->state = $request->input('state');
        $user->zip = $request->input('zip_code');
        $user->latitude = $zipCodeLatLon[0]->latitude;
        $user->longitude = $zipCodeLatLon[0]->longitude;
        if($request->hasFile('resume'))
        {
            $resumeName = $this->StoreResume($request->file('resume'));
            $user->resume = $resumeName;
        }
        $user->profile_state = Enumerations::COMPLETE;
        $user->type = "artist";
        $user->save();
        
        return redirect()->route('home', ['user' => $user]);
    }
    
    private function CompleteArtistProfileValidator($request)
    {
        $this->validate($request, [
            'phone' => 'required',
            'resume' => 'required',
            'portfolio' => 'required',
            'zip_code' => 'required'
        ]);
    }
    
    private function StoreResume($file)
    {
        $destinationPath = 'resume';
        $extension = $file->getClientOriginalExtension(); 
        $fileName = rand(111111111111,999999999999).'.'.$extension;
        $file->move($destinationPath, $fileName);
        
        
        return $fileName;
    }
    
    public function EditArt(Request $request)
    {
        $user = Auth::user();
        
        if(!isset($user))
        {
            return redirect()->route('home');
        }

        return view('editArt', ['fileTypeError' => $request->input('fileTypeError')]);
    }
    
    public function UploadArt(Request $request)
    {
        $user = Auth::user();
        
        if($request->hasFile('art_upload'))
        {          
            $files = Input::file('art_upload');
            $allowed =  array('png' ,'jpg', 'jpeg');
            
            foreach($files as $file)
            {
                $ext = strtolower($file->getClientOriginalExtension());
                
                if(!in_array($ext, $allowed)) 
                {
                    $fileTypeError = 1;
                    return redirect()->route('editArt', ['fileTypeError' => $fileTypeError]);
                }
            }
            
            $imageFileNames = $this->StoreArt($files);
            
            $this->PutArtInDB($imageFileNames);
        }
        
        return redirect()->route('tagArt');
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
    
    private function StoreArt($files)
    {
        $fileNames = array();
                
        foreach ($files as $file)
        {
            $extension = $file->getClientOriginalExtension();
            $fileName = rand(111111111111,999999999999).'.'.$extension;
            $destinationDir = 'art';
            $compressedImage = $this->CompressImage($file, $fileName, $extension);
            rename($compressedImage, $destinationDir."/".$fileName);
            array_push($fileNames, $fileName);
        }
                
        return $fileNames;
    }
    
    private function CompressImage($file, $fileName, $extension)
    {
        $tmpDir = "tmp";
        $tmpPath = "";
        echo phpinfo();
        
        if(strtolower($extension) == 'jpg')
        {
            $img = imagecreatefromjpeg($file);
            $tmpPath = $tmpDir."/".$fileName;
            
            if(filesize($file) >= 5000000)
            {
                imagejpeg($img, $tmpPath, 12); 
            }
            elseif(filesize($file) >= 4000000)
            {
                imagejpeg($img, $tmpPath, 12);
            }
            elseif(filesize($file) >= 3000000)
            {
                imagejpeg($img, $tmpPath, 18);
            }
            elseif(filesize($file) >= 2000000)
            {
                imagejpeg($img, $tmpPath, 30);
            }
            elseif(filesize($file) >= 1000000)
            {
                imagejpeg($img, $tmpPath, 60);
            }
            elseif(filesize($file) >= 800000)
            {   
                imagejpeg($img, $tmpPath, 72);
            }
            elseif(filesize($file) >= 600000)
            {
                imagejpeg($img, $tmpPath, 90);
            }
            elseif(filesize($file) >= 400000)
            {
                imagejpeg($img, $tmpPath, 90);
            }
            elseif(filesize($file) >= 200000)
            {
                imagejpeg($img, $tmpPath, 90);
            }
            else
            {
                imagejpeg($img, $tmpPath, 100);
            }
        }
        elseif(strtolower($extension) == 'jpeg')
        {
            $img = imagecreatefromjpeg($file);
            $tmpPath = $tmpDir."/".$fileName;
            
            if(filesize($file) >= 5000000)
            {
                imagejpeg($img, $tmpPath, 6); 
            }
            elseif(filesize($file) >= 4000000)
            {
                imagejpeg($img, $tmpPath, 6);
            }
            elseif(filesize($file) >= 3000000)
            {
                imagejpeg($img, $tmpPath, 9);
            }
            elseif(filesize($file) >= 2000000)
            {
                imagejpeg($img, $tmpPath, 15);
            }
            elseif(filesize($file) >= 1000000)
            {
                imagejpeg($img, $tmpPath, 30);
            }
            elseif(filesize($file) >= 800000)
            {
                imagejpeg($img, $tmpPath, 36);
            }
            elseif(filesize($file) >= 600000)
            {
                imagejpeg($img, $tmpPath, 48);
            }
            elseif(filesize($file) >= 400000)
            {
                imagejpeg($img, $tmpPath, 75);
            }
            elseif(filesize($file) >= 200000)
            {
                imagejpeg($img, $tmpPath, 90);
            }
            else
            {
                imagejpeg($img, $tmpPath, 100);
            }
        }
        elseif(strtolower($extension) == 'png')
        {
            $img = imagecreatefrompng($file);
            $tmpPath = $tmpDir."/".$fileName;
            
            if(filesize($file) >= 5000000)
            {
                imagepng($img, $tmpPath, 2); 
            }
            elseif(filesize($file) >= 4000000)
            {
                imagepng($img, $tmpPath, 2);
            }
            elseif(filesize($file) >= 3000000)
            {
                imagepng($img, $tmpPath, 3);
            }
            elseif(filesize($file) >= 2000000)
            {
                imagepng($img, $tmpPath, 5);
            }
            elseif(filesize($file) >= 1000000)
            {
                imagepng($img, $tmpPath, 10);
            }
            elseif(filesize($file) >= 800000)
            {
                imagepng($img, $tmpPath, 12);
            }
            elseif(filesize($file) >= 600000)
            {
                imagepng($img, $tmpPath, 16);
            }
            elseif(filesize($file) >= 400000)
            {
                imagepng($img, $tmpPath, 25);
            }
            elseif(filesize($file) >= 200000)
            {
                imagepng($img, $tmpPath, 50);
            }
            else
            {
                imagejpeg($img, $tmpPath, 100);
            }
        }
        
        return $tmpPath;
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
    
    public function DeleteProfile(Request $request)
    {
        $user = $request->user();
        
        return view('deleteProfile', ['user' => $user]);
    }
    
    public function ConfirmDeleteProfile()
    {
        $user = Auth::user();
        $user->destroy($user->id);
        
        return redirect()->route('home');;
    }
}
