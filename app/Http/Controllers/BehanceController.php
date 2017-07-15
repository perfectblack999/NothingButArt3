<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Behance;

class BehanceController extends Controller
{
    public function ShowForm()
    {
        $display = view('behance');
        
        return $display;
    }
    
    public function GetData(Request $request)
    {
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
            
            $images = $this->GetUserImages($dataProjectIds, $apiKey);
        }
        else
        {
            $dataProjects = [];
        }
        
        
        $display = view('behance', ['images' => $images]);
        
        return $display;
    }
    
    private function GetUserImages($dataProjectIds, $apiKey)
    {
        $images = [];
        
        foreach ($dataProjectIds as $dataProjectId)
        {
            foreach (json_decode(file_get_contents("https://api.behance.net/v2/projects/".
                    $dataProjectId."?api_key=".$apiKey))->project->modules as $module)
            {
                array_push($images, $module->src);
            }
        }
        
        return $images;
    }
    
    public function ImportImages(Request $request)
    {
        
    }
}
