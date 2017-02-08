<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Socialite;

class FacebookController extends Controller
{
    /**
     * Redirect the user to the Facebook authentication page.
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function redirectToFacebook()
    {
        return Socialite::with('facebook')
            ->scopes([
                'email',
                'user_events',
                'rsvp_event'
            ])->redirect();
    }
    
    /**
     * Logs user in if exists, creates new user otherwise.
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function handleCallback()
    {
        try {
            $socialiteUser = Socialite::driver('facebook')->user();
        } catch (\Exception $e) {
            // TODO
            return redirect()->back();
        }
    
        $user = $this->findOrCreateUser($socialiteUser, 'facebook');
        
        Auth::login($user, true);
        
        return redirect('/');
    }
    
    /**
     * If a user has registered before using social auth, return the user
     * else, create a new user object.
     *
     * @param  \Laravel\Socialite\Two\User $user
     * @param string $provider Social auth provider
     *
     * @return  User
     */
    public function findOrCreateUser($user, $provider)
    {
        /** @var User $authUser */
        $authUser = User::whereProviderId($user->id)->first();
        
        if ($authUser) {
            // Update the token, if tokens don't match
            if(strcmp($user->token, $authUser->fb_token) != 0) {
                $authUser->update(['fb_token' => $user->token]);
            }
            
            return $authUser;
        }
        
        return User::create([
            'name'     => $user->name,
            'email'    => $user->email,
            'provider' => $provider,
            'provider_id' => $user->id,
            'fb_token' => $user->token
        ]);
    }
}
