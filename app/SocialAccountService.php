<?php


namespace App;

use Laravel\Socialite\Contracts\User as ProviderUser;

class SocialAccountService
{
    protected $activationService;
    
    public function __construct(ActivationService $activationService)
    {
        $this->activationService = $activationService;
    }
    
    public function createOrGetUser(ProviderUser $providerUser)
    {
        $account = SocialAccount::whereProvider('facebook')
            ->whereProviderUserId($providerUser->getId())
            ->first();

        if ($account) {
            return $account->user;
        } else {

            $account = new SocialAccount([
                'provider_user_id' => $providerUser->getId(),
                'provider' => 'facebook'
            ]);

            $user = User::whereEmail($providerUser->getEmail())->first();

            if (!$user) {
                $nameArray = $this->split_name($providerUser->getName());
                
                $user = User::create([
                    'email' => $providerUser->getEmail(),
                    'first_name' => $nameArray[0],
                    'last_name' => $nameArray[1],
                ]);
            }

            $account->user()->associate($user);
            $account->save();
            $this->activationService->sendActivationMail($user);
            
            return $user;

        }

    }
    
    // uses regex that accepts any word character or hyphen in last name
    private function split_name($name) 
    {
        $name = trim($name);
        $last_name = (strpos($name, ' ') === false) ? '' : preg_replace('#.*\s([\w-]*)$#', '$1', $name);
        $first_name = trim( preg_replace('#'.$last_name.'#', '', $name ) );
        return array($first_name, $last_name);
    }
}