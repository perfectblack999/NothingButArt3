<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class SearchArtistController extends Controller
{
    public function NewSearch()
    {
        $user = Auth::user();

        return view('startArtistSearch', ['user' => $user]);
    }
    
    public function GetArt(Request $request)
    {
        $imagesPerScreen = 9;
        $user = Auth::user();
        $this->CompleteArtSearchValidator($request);
        $searchCriteria = $this->GetSearchCriteria();
        $localArtists = $this->GetArtists();
        $scoreOneImage = array();
        $scoreTwoImages = array();
        $scoreThreeImages = array();
        $scores = array($scoreOneImage, $scoreTwoImages, $scoreThreeImages);
        $searchID = $this->createNewArtSearch($searchCriteria);
        $scores = $this->GetScores($scores, $localArtists, $searchCriteria);
        $gridArtIDs = $this->GetGridArtIDs($scores);
        $numberOfScreens = ceil(count($gridArtIDs)/$imagesPerScreen);
        $imageDisplayLines = $this->createGrid($gridArtIDs);
        
        return view('artGrid', ['user' => $user, 'imageDisplayLines' => $imageDisplayLines, 
            'numberOfScreens' => $numberOfScreens, 'screenNumber' => 1, 'searchID' => $searchID,
                'gridArtIDs' => $gridArtIDs]);
    }
    
    private function CompleteArtSearchValidator($request)
    {
        $this->validate($request, [
            'zip' => 'required',
            'distance' => 'required',
        ]);
    }
    
    private function GetScores($scores, $localArtists, $searchCriteria)
    {
        for($i = 0; $i < count($localArtists); $i++)
        {            
//            echo "Number of local artists: ".count($localArtists)."<br>";
            if($localArtists[$i]->image_ids != "")
            {
                $artIDs = explode(",", $localArtists[$i]->image_ids);
            }
            $scores = $this->CalculateScores($localArtists[$i]->id, $artIDs, $searchCriteria, $scores);
        } 
        
        return $scores;
    }
    
    private function createNewArtSearch($searchCriteria)
    {        
        $numberOfCriteria = count($searchCriteria);
        $searchData = array('tag1' => "", 'tag2' => "", 'tag3' => "");
        
        if($numberOfCriteria == 3)
        {
            $searchData = array('tag1' => $searchCriteria[0], 'tag2' => $searchCriteria[1], 'tag3' => $searchCriteria[2]);
        }
        elseif($numberOfCriteria == 2)
        {
            $searchData = array('tag1' => $searchCriteria[0], 'tag2' => $searchCriteria[1]);
        }
        else
        {
            $searchData = array('tag1' => $searchCriteria[0]);
        }
        
        $searchID = DB::table('searches')->insertGetId($searchData,'id');
        
        return $searchID;
    }
    
    // Need to change to make less queries
    private function createGrid($gridArtIDs)
    {
        $imageDisplayLines = array();
        
        foreach ($gridArtIDs as $gridArtID)
        {
            $imageURL = DB::select("SELECT path FROM images WHERE id = (?)", array($gridArtID));
            array_push($imageDisplayLines, "<option data-img-src='art/".$imageURL[0]->path."' value='".$gridArtID."'>Image ".$gridArtID."</option>");
        }
        
        return $imageDisplayLines;
    }
    
    private function CalculateScores($userID, $artIDs, $searchCriteria, $scores)
    {           
        foreach ($artIDs as $artID)
        {
            $tag1 = DB::select('select tag1 from images where id = (?)', array($artID));
            $tag2 = DB::select('select tag2 from images where id = (?)', array($artID));
            $tag3 = DB::select('select tag3 from images where id = (?)', array($artID));          

            if($this->ThreeMatches($searchCriteria, $tag1, $tag2, $tag3))
            {
                array_push($scores[0], array($userID, $artID));
            }
            else if($this->TwoMatches($searchCriteria, $tag1, $tag2, $tag3))
            {
                array_push($scores[1], array($userID, $artID));
            }
            else if($this->OneMatch($searchCriteria, $tag1, $tag2, $tag3))
            {
                array_push($scores[2], array($userID, $artID));
            }
        }
            
        return $scores;
    }
    
    private function ThreeMatches($searchCriteria, $tag1, $tag2, $tag3)
    {
        return in_array($tag1[0]->tag1, $searchCriteria) && in_array($tag2[0]->tag2, $searchCriteria) && in_array($tag3[0]->tag3, $searchCriteria);
    }
    
    private function TwoMatches($searchCriteria, $tag1, $tag2, $tag3)
    {
        return (in_array($tag1[0]->tag1, $searchCriteria) && in_array($tag2[0]->tag2, $searchCriteria)) ||
                        (in_array($tag1[0]->tag1, $searchCriteria) && in_array($tag3[0]->tag3, $searchCriteria)) ||
                        (in_array($tag2[0]->tag2, $searchCriteria) && in_array($tag3[0]->tag3, $searchCriteria));
    }
    
    private function OneMatch($searchCriteria, $tag1, $tag2, $tag3)
    {
        return in_array($tag1[0]->tag1, $searchCriteria) || in_array($tag2[0]->tag2, $searchCriteria) || in_array($tag3[0]->tag3, $searchCriteria);
    }
    
    private function GetSearchCriteria()
    {
        $searchCriteria = array();
        
        if(filter_input(INPUT_POST, 'business_cards', FILTER_SANITIZE_STRING) != null)
        {
            array_push($searchCriteria, 'business_cards');
        }
        if(filter_input(INPUT_POST, 'logos', FILTER_SANITIZE_STRING) != null)
        {
            array_push($searchCriteria, 'logos');
        }
        if(filter_input(INPUT_POST, 'letterhead', FILTER_SANITIZE_STRING) != null)
        {
            array_push($searchCriteria, 'letterhead');
        }
        if(filter_input(INPUT_POST, 'brochures', FILTER_SANITIZE_STRING) != null)
        {
            array_push($searchCriteria, 'brochures');
        }
        if(filter_input(INPUT_POST, 'flyers', FILTER_SANITIZE_STRING) != null)
        {
            array_push($searchCriteria, 'flyers');
        }
        if(filter_input(INPUT_POST, 'postcards', FILTER_SANITIZE_STRING) != null)
        {
            array_push($searchCriteria, 'postcards');
        }
        if(filter_input(INPUT_POST, 'magazines', FILTER_SANITIZE_STRING) != null)
        {
            array_push($searchCriteria, 'magazines');
        }
        if(filter_input(INPUT_POST, 'books', FILTER_SANITIZE_STRING) != null)
        {
            array_push($searchCriteria, 'books');
        }
        if(filter_input(INPUT_POST, 'catalogs', FILTER_SANITIZE_STRING) != null)
        {
            array_push($searchCriteria, 'catalogs');
        }
        if(filter_input(INPUT_POST, 'packaging', FILTER_SANITIZE_STRING) != null)
        {
            array_push($searchCriteria, 'packaging');
        }
        if(filter_input(INPUT_POST, 'presentations', FILTER_SANITIZE_STRING) != null)
        {
            array_push($searchCriteria, 'presentations');
        }
        if(filter_input(INPUT_POST, 'illustrations', FILTER_SANITIZE_STRING) != null)
        {
            array_push($searchCriteria, 'illustrations');
        }
        if(filter_input(INPUT_POST, 'website', FILTER_SANITIZE_STRING) != null)
        {
            array_push($searchCriteria, 'website');
        }
        if(filter_input(INPUT_POST, 'mobile_app', FILTER_SANITIZE_STRING) != null)
        {
            array_push($searchCriteria, 'mobile_app');
        }
        if(filter_input(INPUT_POST, 'web_app', FILTER_SANITIZE_STRING) != null)
        {
            array_push($searchCriteria, 'web_app');
        }
        if(filter_input(INPUT_POST, '3d', FILTER_SANITIZE_STRING) != null)
        {
            array_push($searchCriteria, '3d');
        }
        if(filter_input(INPUT_POST, 'desktop_app', FILTER_SANITIZE_STRING) != null)
        {
            array_push($searchCriteria, 'desktop_app');
        }
        
        return $searchCriteria;
    }
    
    private function GetArtists()
    {
        $users = null;
        $zipCode = filter_input(INPUT_POST, 'zip', FILTER_SANITIZE_NUMBER_INT);
        $distance = filter_input(INPUT_POST, 'distance', FILTER_SANITIZE_NUMBER_INT);
        $radius = 3959;  //earth's radius in miles
        $zipCodeEntry = DB::select("SELECT * FROM zip_codes WHERE zip = (?)", array($zipCode));
        $latitude = $zipCodeEntry[0]->latitude;
        $longitude = $zipCodeEntry[0]->longitude;
        $latN = rad2deg(asin(sin(deg2rad($latitude)) * cos($distance / $radius) + cos(deg2rad($latitude)) * sin($distance / $radius) * cos(deg2rad(0))));
        $latS = rad2deg(asin(sin(deg2rad($latitude)) * cos($distance / $radius) + cos(deg2rad($latitude)) * sin($distance / $radius) * cos(deg2rad(180))));
        $lonE = rad2deg(deg2rad($longitude) + atan2(sin(deg2rad(90)) * sin($distance / $radius) * cos(deg2rad($latitude)), cos($distance / $radius) - sin(deg2rad($latitude)) * sin(deg2rad($latN))));
        $lonW = rad2deg(deg2rad($longitude) + atan2(sin(deg2rad(270)) * sin($distance / $radius) * cos(deg2rad($latitude)), cos($distance / $radius) - sin(deg2rad($latitude)) * sin(deg2rad($latN))));
        $type = 'artist';
        
        $usersInRange = DB::select("SELECT * FROM users WHERE (latitude <= (?) AND latitude >= (?) "
                . "AND longitude <= (?) AND longitude >= (?)) AND type = (?)", 
                array($latN, $latS, $lonE, $lonW, $type));
        
        if($usersInRange != null)
        {
            $users = $usersInRange;
        }
        
        return $users;
    }
    
    private function GetGridArtIDs($scores)
    {
        $gridIDs = array();
        $selectedArtistCount = array();
        $imageCount = count($scores[0]) + count($scores[1]) + count($scores[2]);
        $imagesPerScreen = 9;
        $numberOfScreens = 5;
        $numTotalImages = $imagesPerScreen * $numberOfScreens;
        
        // find out if there are even enough images total to fill 45 spots
        if($imageCount < $numTotalImages)
        {
            foreach($scores[0] as $artistAndArt)
            {
                array_push($gridIDs, $artistAndArt[1]);
            }
            
            foreach($scores[1] as $artistAndArt)
            {
                array_push($gridIDs, $artistAndArt[1]);
            } 
            
            foreach($scores[2] as $artistAndArt)
            {
                array_push($gridIDs, $artistAndArt[1]);
            } 
        }
        else
        {
            $score3ArtistCount = count($this->GetArtistIDs($scores[0]));
            $score2ArtistCount = count($this->GetArtistIDs($scores[1]));
            $score1ArtistCount = count($this->GetArtistIDs($scores[2]));
            
            $numberOfScore3ImagesPerArtist = $this->GetNumberOfImagesPerArtist($score3ArtistCount);
            $numberOfScore2ImagesPerArtist = $this->GetNumberOfImagesPerArtist($score2ArtistCount);
            $numberOfScore1ImagesPerArtist = $this->GetNumberOfImagesPerArtist($score1ArtistCount);
            
            $score3ArtistAndIDsArray = $this->GetArtistAndIDsArray($scores[0]);
            $score2ArtistAndIDsArray = $this->GetArtistAndIDsArray($scores[1]);
            $score1ArtistAndIDsArray = $this->GetArtistAndIDsArray($scores[2]);
            
            // if we can fill up the array with just score 3 images, do it
            if(count($scores[0]) > $numTotalImages)
            {
                // fill grid with score 3 images
                while(count($gridIDs) < $numTotalImages)
                {
                    // pick a random artist from the score 3 array
                    $randomArtistIndex = rand(0, count($score3ArtistAndIDsArray)-1); 
                   
                    for($i=0; $i < $numberOfScore3ImagesPerArtist; $i++)
                    {
                        $numberOfImagesFromArtist = count($score3ArtistAndIDsArray[$randomArtistIndex]);
                        
                        // pick a random art id from the random artist
                        $randomArtIndex = rand(0, $numberOfImagesFromArtist - 1);
                        
                        // push the random art into the grid id array
                        array_push($gridIDs, $score3ArtistAndIDsArray[$randomArtistIndex][$randomArtIndex]);
                    }
                }
            }
            // if we can fill up the array with just score 3 & score 2 images, do it
            else if (count($scores[0]) + count($scores[1]) > $numTotalImages)
            {
                #number of score 2 & 3 images
                $numberOfScore3Images = $score3ArtistCount * $numberOfScore3ImagesPerArtist;
                $numberOfScore2Images = $score2ArtistCount * $numberOfScore2ImagesPerArtist;
                
                // fill grid with score 3 images
                while(count($gridIDs) < $numberOfScore3Images)
                {
                    // pick a random artist from the score 3 array
                    $randomScore3ArtistIndex = rand(0, count($score3ArtistAndIDsArray)-1);
                   
                    for($i=0; $i < $numberOfScore3ImagesPerArtist; $i++)
                    {
                        $numberOfImagesFromArtist = count($score3ArtistAndIDsArray[$randomScore3ArtistIndex]);
                        
                        // pick a random art id from the random artist
                        $randomArtIndex = rand(0, $numberOfImagesFromArtist - 1);
                        
                        // push the random art into the grid id array
                        array_push($gridIDs, $score3ArtistAndIDsArray[$randomArtistIndex][$randomArtIndex]);
                    }
                }
                
                // fill grid with score 2 images
                while(count($gridIDs) < $numberOfScore2Images)
                {
                    // pick a random artist from the score 2 arrays
                    $randomScore2ArtistIndex = rand(0, count($score2ArtistAndIDsArray)-1); 
                   
                    for($i=0; $i < $numberOfScore2ImagesPerArtist; $i++)
                    {
                        $numberOfImagesFromArtist = count($score2ArtistAndIDsArray[$randomScore2ArtistIndex]);
                        
                        // pick a random art id from the random artist
                        $randomArtIndex = rand(0, $numberOfImagesFromArtist - 1);
                        
                        // push the random art into the grid id array
                        array_push($gridIDs, $score2ArtistAndIDsArray[$randomArtistIndex][$randomArtIndex]);
                    }
                }
            }
            // it'll take all 3 tiers to fill up the grid array
            else
            {
                #number of score 2 & 3 images
                $numberOfScore3Images = $score3ArtistCount * $numberOfScore3ImagesPerArtist;
                $numberOfScore2Images = $score2ArtistCount * $numberOfScore2ImagesPerArtist;
                
                // fill grid with score 3 images
                while(count($gridIDs) < $numberOfScore3Images)
                {
                    // pick a random artist from the score 3 array
                    $randomScore3ArtistIndex = rand(0, count($score3ArtistAndIDsArray)-1);
                   
                    for($i=0; $i < $numberOfScore3ImagesPerArtist; $i++)
                    {
                        $numberOfImagesFromArtist = count($score3ArtistAndIDsArray[$randomScore3ArtistIndex]);
                        
                        // pick a random art id from the random artist
                        $randomArtIndex = rand(0, $numberOfImagesFromArtist - 1);
                        
                        // push the random art into the grid id array
                        array_push($gridIDs, $score3ArtistAndIDsArray[$randomArtistIndex][$randomArtIndex]);
                    }
                }
                
                // fill grid with score 2 images
                while(count($gridIDs) < $numberOfScore2Images)
                {
                    // pick a random artist from the score 2 arrays
                    $randomScore2ArtistIndex = rand(0, count($score2ArtistAndIDsArray)-1); 
                   
                    for($i=0; $i < $numberOfScore2ImagesPerArtist; $i++)
                    {
                        $numberOfImagesFromArtist = count($score2ArtistAndIDsArray[$randomScore2ArtistIndex]);
                        
                        // pick a random art id from the random artist
                        $randomArtIndex = rand(0, $numberOfImagesFromArtist - 1);
                        
                        // push the random art into the grid id array
                        array_push($gridIDs, $score2ArtistAndIDsArray[$randomArtistIndex][$randomArtIndex]);
                    }
                }
                
                // fill grid with score 1 images
                while(count($gridIDs) < $numberOfScore1Images)
                {
                    // pick a random artist from the score 2 arrays
                    $randomScore1ArtistIndex = rand(0, count($score1ArtistAndIDsArray)-1); 
                   
                    for($i=0; $i < $numberOfScore1ImagesPerArtist; $i++)
                    {
                        $numberOfImagesFromArtist = count($score1ArtistAndIDsArray[$randomScore1ArtistIndex]);
                        
                        // pick a random art id from the random artist
                        $randomArtIndex = rand(0, $numberOfImagesFromArtist - 1);
                        
                        // push the random art into the grid id array
                        array_push($gridIDs, $score1ArtistAndIDsArray[$randomArtistIndex][$randomArtIndex]);
                    }
                }
            } 
        }
        
        return $gridIDs;
    }
    
    private function GetArtistIDs($scoresArray)
    {
        $artistIDs = array();
        
        // Iterate through scores array and add IDs to ID array if it ID isnt' already there
        foreach ($scoresArray as $artistAndArtPair)
        {
            if(!(in_array($artistAndArtPair[0], $artistIDs)))
            {
                array_push($artistIDs, $artistAndArtPair[0]);
            }
        }
        
        return $artistIDs;
    }
    
    private function GetArtistIdImageCountPair($scoresArray)
    {
        $artistIDsAndImageCounts = array();
        
        // Iterate through scores array and increment the value of the image count if image is found
        foreach ($scoresArray as $artistAndArtPair)
        {
            $artistIDsAndImageCounts[$artistAndArtPair[0]]++;
        }
        
        return $artistIDsAndImageCounts;
    }
    
    private function GetNumberOfImagesPerArtist($artistCount)
    {
        $numberOfImagesPerArtist = 1;

        // get the number of images we can select per artist
        if(floor(45/$artistCount) < 1)
        {
            $numberOfImagesPerArtist = 1;
        }
        else
        {
            $numberOfImagesPerArtist = floor(45/$artistCount);
        }
        
        return $numberOfImagesPerArtist;
    }
    
    private function GetArtistAndIDsArray($scoresArray)
    {
        $getArtistAndIDsArray = array();
        
        // Iterate through scores array and put artist id and image ids array in
        // array. Then add to artist and IDs array
        foreach ($scoresArray as $artistAndArtPair)
        {
            if($getArtistAndIDsArray[$artistAndArtPair[0]] == null)
            {
                $artistIDsAndImageCounts[$artistAndArtPair[0]] = array();
                array_push($artistIDsAndImageCounts[$artistAndArtPair[0]], $artistAndArtPair[1]);
            }
            else
            {
                array_push($artistIDsAndImageCounts[$artistAndArtPair[0]], $artistAndArtPair[1]);
            }
        }
        
        return $getArtistAndIDsArray;
    }

    public function NextSearchImages()
    {
        $screenNumber = filter_input(INPUT_GET, 'screen_number', FILTER_SANITIZE_STRING);
        $gridArtIDs = explode(",", filter_input(INPUT_GET, 'grid_art_ids', FILTER_SANITIZE_STRING));
        $imageDisplayLines = $this->createGrid($gridArtIDs);
        
        for ($i = ($screenNumber - 1) * 9; $i < (($screenNumber - 1) * 9) + 9; $i++)
        {
            if (isset($imageDisplayLines[$i]))
            {
                echo $imageDisplayLines[$i];
            }
        }
    }
    
    public function SaveSelectedImages()
    {
        $searchID = filter_input(INPUT_POST, 'search_id', FILTER_SANITIZE_STRING);
        $selectedImages = filter_input(INPUT_POST, 'selected_images', FILTER_SANITIZE_STRING);
        
        $this->UpdateSearchTable($selectedImages, $searchID);
    }
    
    private function UpdateSearchTable($selectedImages, $searchID)
    { 
        $previousSelectedImages = DB::select("SELECT selected_images FROM searches WHERE id = (?)", array($searchID));
        
        if($previousSelectedImages[0]->selected_images != '')
        {
            $allSelectedImages = $previousSelectedImages[0]->selected_images.",".$selectedImages;
        }
        else
        {
            $allSelectedImages = $selectedImages;
        }
        
        $searchRow = DB::update('UPDATE searches SET selected_images = (?)'
                . 'where id = (?)', array($allSelectedImages, $searchID));
    }
    
    public function SearchResults()
    {     
        $searchID = filter_input(INPUT_GET, 'search_id', FILTER_SANITIZE_STRING);
        $topArtists = $this->GetTopArtists($searchID);
        $topArtistsString = implode(",", $topArtists);
        $topArtistData = DB::select("SELECT id, first_name, last_name, email, "
                . "phone FROM users WHERE FIND_IN_SET(id, (?))", array($topArtistsString));
        
        return view('searchResults', ['topArtistData' => $topArtistData]);
    }
    
    private function GetTopArtists($searchID)
    {
        $imageNames = explode(",", DB::select("SELECT selected_images FROM searches WHERE id = (?)", 
                array($searchID))[0]->selected_images);
        $artistCounts = $this->GetArtistCounts($imageNames);
        $topArtists = $this->GetTopArtistsString($artistCounts);
        $topArtistArray = explode(",", $topArtists);
        
        DB::update("UPDATE searches SET results = (?) WHERE id = (?)", array($topArtists, $searchID));
        
        return $topArtistArray;
    }
    
    private function GetArtistCounts($imageNames)
    {
        $artistCounts = [];
        
        // Get artist associated w/each image
        foreach($imageNames as $imageName)
        {
            $user = DB::select('SELECT user FROM images WHERE path = (?)', array($imageName))[0]->user;

            if(isset($artistCounts[$user]))
            {
                $artistCounts[$user] += 1;
            }
            else
            {
                $artistCounts[$user] = 1;
            }
        }
        
        return $artistCounts;
    }
    
    private function GetTopArtistsString($artistCounts)
    {
        $topArtists = "";
        $firstTime = 1;
        
        foreach($artistCounts as $key => $artistCount)
        {
            if($firstTime)
            {
                $topArtists = $topArtists.$key;
                $firstTime = 0;
            }
            else
            {
                $topArtists = $topArtists.",".$key;
            }
        }
        
        return $topArtists;
    }
    
    public function DownloadResume(Request $request)
    {
        $resumeFileName = DB::select("SELECT resume FROM users WHERE id = (?)", 
                array($request->input('artist_id')));
        
        $pathToFile = "resume/".$resumeFileName[0]->resume;
        return response()->download($pathToFile);
    }
}
