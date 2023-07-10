<?php

namespace App\Http\Controllers\Auth\Voter;

use App\Http\Controllers\Controller;
use App\Http\Requests\VoterLoginRequest;
use App\Models\Voter;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class VoterLoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/';

    public function __construct()
    {
        $this->middleware('guest:voter')->except('logout');
    }

    public function showLoginForm()
    {
        return Inertia::render('voter/auth/login', ['title' => 'Login | Pemilos Stemba']);
    }

    public function login(VoterLoginRequest $request)
    {
        if ($this->hasTooManyLoginAttempts($request)) {
            return response()->json([
                "status" => 422,
                "message" => "Terlalu banyak percobaan login, silahkan coba lagi nanti",
            ], 422);
        }

        $request->validated();

        $creadentials = request()->only(['username', 'password', 'type']);


        $user = Voter::where(['username' => $creadentials['username'], 'password' => $creadentials['password'], 'type' => $creadentials['type']])->first();

        if (!$user) {

            $this->incrementLoginAttempts($request);
            return response()->json([
                "status" => 422,
                "message" => "Username atau password salah, silahkan coba lagi",
            ], 422);
        }

        Auth::guard('voter')->login($user);

        return response()->json([
            "status" => 200,
            "message" => "Success",
        ], 200);
    }

    public function logout(Request $request)
    {
        $this->guard()->logout();
        $request->session()->invalidate();

        if ($response = $this->loggedOut($request)) {
            return $response;
        }

        return response()->json([
            "status" => 200,
            "message" => "Success",
        ], 200);;
    }
}
