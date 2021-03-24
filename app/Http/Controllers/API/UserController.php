<?php

namespace App\Http\Controllers\API;

use App\Actions\Fortify\PasswordValidationRules;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class UserController extends Controller
{
    use PasswordValidationRules;

    public function sign()
    {
        var_dump('ini sign'); die;
    }
    
    public function login(Request $request)
    {
        // var_dump('test regis');
        // die;
        try {
            //validasi input login
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->error(), 400);
            }

            //mengecek credentials (login)
            $credentials = request(['email', 'password']);
            if(!Auth::attempt($credentials)) {
                return ResponseFormatter::error([
                    'message' => 'Unauthorized'
                ], 'Authentication Failed', 500);
            }

            //jika berhasil tidak sesuai maka beri error
            $user = User::where('email', $request->email)->first();
            if(!Hash::check($request->password, $user->password, [])) {
                throw new \Exception('Invalid Credentils');
            }

             //jika berhasil maka login
             $tokenResault = $user->createToken('authToken')->plainTextToken;
             return ResponseFormatter::success([
                 'access_token' => $tokenResault,
                 'token_type' => 'Bearer',
                 'user' => $user,
             ], 'Authenticated');

        } catch(Exception $error) {
            return ResponseFormatter::Error([
                'message' => 'Something went wrong',
                'error' => $error,
            ], 'Authenticated Failed', 500 );
        }
    }

    public function register (Request $request)
    {
        // var_dump('test regis');
        // die;

        try {
            //request validasi
            $request->validate([
                'name' => ['required','string','max:255'],
                'email' => ['required','string','email','max:255','unique:users'],
                'password' => $this->passwordRules(),
            ]);
            
            //create user
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'address' => $request->address,
                'houseNumber' => $request->houseNumber,
                'phoneNumber' => $request->phoneNumber,
                'city' => $request->city,
                'password' => Hash::make($request->password),
            ]);

            //untuk mengambil data didatabase user
            $user = User::where('email', $request->email)->first();

            //untuk mengambil token
            $tokenResault = $user->createToken('authToken')->plainTextToken;

            //mengembalika token beserta data user
            return ResponseFormatter::success([
                'access_token' => $tokenResault,
                'token_type' => 'Bearer',
                'user' => $user,
            ]);
        //untuk mengecek eroor dan mengmablikan
        } catch (Exception $error) {
            return ResponseFormatter::error([
                'message' => 'Something went wrong',
                'error' => $error,
            ], 'Authenticated Failed', 500);
        }
    }

    public function logout (Request $request)
    {
        $token = $request->user()->currentAccessToken()->delete();

        return ResponseFormatter::success($token, 'Token Revoked');
    }

    public function fetch(Request $request)
    {
        return ResponseFormatter::success(
            $request->user(), 'Data profile user berhasil diubah');
    }

    public function updateProfile(Request $request)
    {
        $data = $request->all();

        $user = Auth::user();
        $user->update($data);

        return ResponseFormatter::success($user, 'Profile Updated');
    }

    public function updatePhoto(Request $request)
    {
        //validasi file
        $validator = Validator::make($request->all(), [
            'file' => 'required|image|max:2048',
        ]);
        
        //jika validasi gagal
        if($validator->fails())
        {
            return ResponseFormatter::error(
                ['error' => $validator->error()],
                'Update profile fails',
                401
            );
        }

        //untuk check file jika ada
        if($request->file('file'))
        {
            $file = $request->file->store('assets/user', 'public');

            //untuk simpan photo kedalam database (urlnya)
            $user = Auth::user();
            $user->profile_photo_path = $file;
            $user->update();

            return ResponseFormatter::success([$file], 'File successfully uploaded');
        }
    }
}


