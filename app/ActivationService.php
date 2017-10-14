<?php

namespace App;


use Illuminate\Mail\Mailer;
use Illuminate\Mail\Message;

class ActivationService
{

    protected $mailer;

    protected $activationRepo;

    protected $resendAfter = 24;

    public function __construct(Mailer $mailer, ActivationRepository $activationRepo)
    {
        $this->mailer = $mailer;
        $this->activationRepo = $activationRepo;
    }

    public function sendActivationMail($user)
    {

        if ($user->profile_state || !$this->shouldSend($user)) {
            return;
        }

        $token = $this->activationRepo->createActivation($user);

        $link = route('user.activate', $token);
        $HTMLheader = "<h3 style='text-align:center;'>Almost done. Complete your registration now.</h3>";
        $HTMLmessage = sprintf('<p>Thank you for signing up to #NothingButArt! Complete your registration here:</p>'
                . '<p style="text-align:center;">%s</p>', $link, $link);
        $HTMLemail = $HTMLheader.$HTMLmessage;
        
        $textHeader = "Almost done. Complete your regitration now. \r\n \r\n";
        $textMessage = sprintf('Thank you for signing up to #NothingButArt! Complete your registration here: %s', $link, $link);
        $textEmail = $textHeader.$textMessage;

//        $this->mailer->raw($message, function (Message $m) use ($user) {
//            $m->to($user->email)->subject('Complete Your Registration');
//        });

        $url = 'https://api.elasticemail.com/v2/email/send';
        
        

        try
        {
            $post = array('from' => 'contact@nothingbutart.co',
                'fromName' => 'Nothing But Art',
                'apikey' => 'e2076340-b467-4e59-96f7-01cab30dee9b',
                'subject' => 'Complete Your Registration',
                'to' => $user->email,
                'bodyHtml' => $HTMLemail,
                'bodyText' => $textEmail,
                'isTransactional' => false);

                $ch = curl_init();
                curl_setopt_array($ch, array(
                    CURLOPT_URL => $url,
                    CURLOPT_POST => true,
                    CURLOPT_POSTFIELDS => $post,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_HEADER => false,
                    CURLOPT_SSL_VERIFYPEER => false
                ));

            $result=curl_exec ($ch);
            curl_close ($ch);

            echo $result;	
        }
        catch(Exception $ex)
        {
            echo $ex->getMessage();
        }

    }

    public function activateUser($token)
    {
        $activation = $this->activationRepo->getActivationByToken($token);

        if ($activation === null) {
            return null;
        }

        $user = User::find($activation->user_id);

        $user->profile_state = true;

        $user->save();

        $this->activationRepo->deleteActivation($token);

        return $user;

    }

    private function shouldSend($user)
    {
        $activation = $this->activationRepo->getActivation($user);
        return $activation === null || strtotime($activation->created_at) + 60 * 60 * $this->resendAfter < time();
    }

}