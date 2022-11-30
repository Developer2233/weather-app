<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use \Illuminate\Http\RedirectResponse as IRedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Symfony\Component\HttpFoundation\RedirectResponse;

class LoginController extends Controller
{
    /**
     * @param UserRepository $userRepository
     */
    public function __construct(private readonly UserRepository $userRepository)
    {
    }

    /**
     * @param Request $request
     * @return RedirectResponse|IRedirectResponse
     */
    public function redirectToProvider(Request $request): RedirectResponse|IRedirectResponse
    {
        $request->session()->put('lat', $request->get('lat'));
        $request->session()->put('lon', $request->get('lon'));
        return Socialite::driver('google')->redirect();
    }

    /**
     * @param Request $request
     * @return Response|ResponseFactory
     */
    public function handleProviderCallback(Request $request): Response|ResponseFactory
    {
        Auth::logout();
        $lat = $request->session()->get('lat');
        $lon = $request->session()->get('lon');

        $googleUser = Socialite::driver('google')->stateless()->user();

        $user = $this->userRepository->updateOrCreate([
            'google_id' => $googleUser->id
        ], [
            'name' => $googleUser->name,
            'email' => $googleUser->email,
            'google_id' => $googleUser->id,
            'avatar' => $googleUser->avatar,
            'lat' => $lat,
            'lon' => $lon
        ]);

        Auth::login($user);

        $token = $user->createToken('Laravel Password Grant Client')->accessToken;
        $response = ['token' => $token];
        return response($response, 200);
    }

    /**
     * @return Redirector|IRedirectResponse
     */
    public function logout(): Redirector|IRedirectResponse
    {
        Auth::logout();
        return redirect('/login');
    }
}

