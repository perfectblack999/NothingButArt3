<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use Illuminate\Mail\Mailer;
use Illuminate\Mail\Message;

class InviteFriendsController extends Controller
{
    protected $mailer;

    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }
    
    public function Display()
    {
        $user = Auth::user();
        
        if(!isset($user))
        {
            return redirect()->route('home');
        }
        
        $display = view('inviteFriends', ['user' => $user]);
        
        return $display;
    }
    
    public function EmailInvites(Request $request)
    {
        $user = Auth::user();
        
        if(!isset($user))
        {
            return redirect()->route('home');
        }
        
        $images = $this->GetImages($user);
        $emails = explode(",", $request->input('emails'));
        $this->sendMail($user, $emails);
        $display = view('home', ['user' => $user, 'images' => $images]);
        
        return $display;
    }
    
    public function sendMail($user, $emails)
    {
        $link = 'www.nothingbutart.com';
        $subject = sprintf("%s %s invited you to NothingButArt!", $user->first_name, $user->last_name);
        $message = sprintf("Your friend, %s %s, invited you to Nothing But Art. "
                . "Looking to hire someone for a graphic design position or looking "
                . "for a position yourself? We're here to help! \r\n\r\n"
                . "Sign up here:\r\n"
                . "%s", $user->first_name, $user->last_name, $link);

        foreach ($emails as $email)
        {
            $this->mailer->raw($message, function (Message $m) use ($email, $subject){
                $m->to($email)->subject($subject);
            });
        }
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
}
