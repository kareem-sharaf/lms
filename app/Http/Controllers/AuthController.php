<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Artisan;
use App\Models\User;
use App\Models\Year;
use App\Models\Address;

class AuthController extends Controller
{
    //  Create students (mobile)
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'father_name' => 'required|string',
            'phone_number' => 'required|unique:users|numeric|starts with:09|min_digits:10|max_digits:10',
            'password' => 'required|regex:/[a-z]/|regex:/[A-Z]/|regex:/[0-9]/|min:8',
            'device_id' => 'required|string',
            'email' => 'email|unique:users',
            'address_id' => 'required|numeric',
            'image_id' => 'required',
            'year_id' => 'numeric'
        ]);

        $year_id = $request->year_id;
        $stage_id = null;

        if ($year_id) {
            $year = Year::where('id', $year_id)
                ->first();
            $stage_id = $year->stage_id;
        }

        $user = new User([
            'name' => $request->name,
            'father_name' => $request->father_name,
            'phone_number' => $request->phone_number,
            'password' => Hash::make($request->password),
            'email' => $request->email,
            'address_id' => $request->address_id,
            'birth_date' => $request->birth_date,
            'device_id' => $request->device_id,
            'image_id' => $request->image_id,
            'role_id' => 4,
            'year_id' => $request->year_id,
            'stage_id' => $stage_id
        ]);

        if ($user->save()) {
            $token = $user->createToken('Personal Access Token')->plainTextToken;
            Auth::login($user, $remember = true);
            return response()->json(
                ['message' => 'successfully created user!', 'accessToken' => $token],
                201
            );
        } else {
            return response()->json(
                ['error' => 'provide proper details'],
                422
            );
        }
    }

    //  login students (mobile)
    public function login(Request $request)
    {
        $request->validate([
            'phone_number' => 'required|numeric',
            'password' => 'required|string',
            'device_id' => 'required|string'
        ]);

        $credentials = $request->only('phone_number', 'password');

        if (Auth::attempt($credentials)) {
            $user = User::where('phone_number', $request->phone_number)->first();

            if ($user->device_id === null || $user->device_id === $request->device_id) {
                $user->device_id = $request->device_id;
                $user->save();

                $token = $user->createToken('Personal Access Token')->plainTextToken;
                return response()->json(['accessToken' => $token], 200);
            } else {
                return response()->json(['error' => 'Unauthorized device.'], 401);
            }
        } else {
            return response()->json(['error' => 'Invalid credentials.'], 401);
        }
    }

    //  Auth requirments
    public function indexAddressYears()
    {
        $Addresses = Address::all();
        $years = Year::all();
        return response()->json(
            ['years' => $years, 'addresses' => $Addresses],
            200
        );
    }

    //   logout
    public function logout(Request $request)
    {
        $user = Auth::user();
        $request->user()->tokens()->delete();
        return response()->json(
            ['message' => 'Successfully logged out']
        );
    }

    //     seed users
    public function seedUsers(Request $request)
    {
        Artisan::call('db:seed', ['--class' => 'UserSeeder']);
    }
}
