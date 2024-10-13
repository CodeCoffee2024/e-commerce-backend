<?php
namespace App\Services;

use App\Models\User;
use App\Models\UserGoogle;
use App\Models\LoginLog;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;

class UserService
{
    public function loginUserViaGoogle ($data) {

        $userGoogle = UserGoogle::where('uid', $data['google']['uid'])
        ->orWhere('email', $data['google']['email'])
        ->first();

        if ($userGoogle) {
            $user = User::where('email', $userGoogle->email)->first();
            $existingToken = $user->createToken(env('APP_NAME'))->plainTextToken;
            if ($user) {
                $token = $existingToken;
                $expiration = Carbon::now()->addMinutes(config('sanctum.expiration'));
                $user->loginLog->expires_at = $expiration;
                $user->loginLog->save();
                $user->load('userGoogle');
                $user->load('cart');
                $user->save();
                $userArray = $user->toArray();
                $userArray['token'] = $token;
                return $userArray;
            }
        } else {
            return $this->createUser($data);
        }
    }
    public function login($data) {
        $user = User::where('email', $data['email'])->first();
        if (!$user || !Hash::check($data['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }
        $existingToken = $user->createToken(env('APP_NAME'))->plainTextToken;
        if ($user) {
            $token = $existingToken;
            $expiration = Carbon::now()->addMinutes(config('sanctum.expiration'));
            $user->loginLog->expires_at = $expiration;
            $user->loginLog->save();
            $user->load('cart');
            $user->save();
            $userArray = $user->toArray();
            $userArray['token'] = $token;
            return $userArray;
        }
    }

    public function createUser($data)
    {   
        if (!$data['isGoogleAccount'] && !$data['isFacebookAccount']) {
            $data['name'] = substr($data['email'], 0, strpos($data['email'], '@'));
        }
        $user = User::create([
            'name' => isset($data['name']) ? $data['name'] : null,
            'email' => $data['email'],
            'isGoogleAccount' => $data['isGoogleAccount'],
            'isFacebookAccount' => $data['isFacebookAccount'],
            'password' => Hash::make(isset($data['password']) ? $data['password'] : ''), 
        ]);
        if (isset($data['google'])) {
            $userGoogle = UserGoogle::create([
            'email' => $data['google']['email'],
            'displayName' => $data['google']['displayName'],
            'photoUrl' => $data['google']['photoURL'],
            'uid' => $data['google']['uid']
        ]);
        $user->userGoogle()->save($userGoogle);
        }
        if (isset($data['google']['phoneNumber'])) {
            $userGoogle['phoneNumber'] = $data['google']['phoneNumber'];
        }
        $user->abilities = ['customer'];
        $token = $user->createToken(env('APP_NAME'))->plainTextToken;
        $expiration = Carbon::now()->addMinutes(config('sanctum.expiration'));
        
        $loginLog = LoginLog::create([
            'expires_at' => $expiration,
        ]);

        $user->expiration = $expiration->toISOString();
        $user->loginLog()->save($loginLog);
        $user->token = $token;
        $user->load('userGoogle');
        return $user;
    }
}