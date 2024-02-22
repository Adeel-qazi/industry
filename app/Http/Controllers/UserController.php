<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminAdduserRequest;
use App\Http\Requests\AdminUpdateUserRequest;
use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Http\Requests\UpdateProfileRequest;
use Carbon\Carbon;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    
    public function register(RegisterUserRequest $request)
    { 
        $validatedData = $request->validated();

        try {
            $user = User::create($validatedData);
            return response()->json(['success' => true, 'message' => 'User has been registered successfully Please login your account', 'user' => $user], 200);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => $th->getMessage()], 500);
        }

    }


    public function login(LoginUserRequest $request)
    {
        try {
            $validatedData = $request->validated();
            
            if (Auth::attempt(["email" => $validatedData["email"], "password" => $validatedData["password"]])) {
                $user = auth()->user();
                
                if ($user->email_verified == 1) {
                    $token = $user->createToken('user')->accessToken;

                    $userData = [
                        'user' => $user->toArray(),
                    ];

                    if ($user->subscriptions()->exists()) {
                        $userData['subscriptions'] = $user->subscriptions->toArray();
                    }
                    return response()->json(['status' => true, 'access_token' => $token, 'user' => $userData],200);
                } else {
                    return response()->json(['status' => false, 'message' => 'You are not authorized to access.'],403);
                }
            } else {
                return response()->json(['status' => false, 'message' => 'Invalid email or password'],401);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
        
    }




    public function show($userId)
    {
        try {
            $loggedInUser = auth()->user();

            if ($loggedInUser->id == $userId) {
                $user = User::with('subscriptions')->findOrFail($userId);
            
            $userSubscription = $user->subscriptions->sortByDesc('id')->first();
            if ($userSubscription) {
                $createdAt = Carbon::parse($userSubscription->pivot->created_at);
                
                $today = Carbon::parse(now());
                switch ($userSubscription->duration_unit) {
                    case 'weeks':
                        $unit = 'addWeeks';
                        break;
                    case 'months':
                        $unit = 'addMonths';
                        break;
                    case 'years':
                        $unit = 'addYears';
                        break;
                }
                
                $expire = $createdAt->$unit($userSubscription->duration);
            }
            
            return response()->json(['success' => true, 'message' => 'User found', 'user' => $user, 'expire' => $expire],200);
            }
            return response()->json(['success' => false, 'message' => 'Permission denied'], 403);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'User not found'], 404);
        }
    }



    public function update(UpdateProfileRequest $request, $userId)
    {
        try {
            $loggedInUser = auth()->user();

            if ($loggedInUser->id == $userId) {
                $user = User::findOrFail($userId);

                $user->update($request->validated());

                return response()->json([
                    'success' => true,
                    'message' => 'User updated successfully',
                    'user' => $user,
                ], 200);
            }

            return response()->json(['success' => false, 'message' => 'Permission denied'], 403);

        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => $th->getMessage()], 500);
        }
    }


    public function statusUpdated($userId)
    {
        try {
            $loggedInUser = auth()->user();
    
            if ($loggedInUser->role == 'admin') {
                $user = User::where('id', $userId)->where('role', 'user')->first();
            } else {
                return response()->json(['success' => false, 'message' => 'Permission denied.'], 403);
            }
    
            
            if ($user->email_verified == true) {
                $user->update(['email_verified' => 0]);
            } else {
                $user->update(['email_verified' => 1]);
            }
            $verifiedStatus = $user->email_verified == true ? 'approved': 'disapproved';
            return response()->json(['success' => true, 'message' => "User has been $verifiedStatus successfully.", 'user' => $user->name], 200);
    
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => $th->getMessage()], 500);
        }
    }
    
    


    public function disApproved($userId)
    {
        try {
            $loggedInUser = auth()->user();
    
            if ($loggedInUser->role == 'admin') {
                $user = User::where('id', $userId)->where('role', 'user')->first();
            } else {
                return response()->json(['success' => false, 'message' => 'Permission denied.'], 403);
            }
    
            if ($user) {
                $user->update(['email_verified' => 0]);
                // event(new ApprovedClient($user->id));
                return response()->json(['success' => true, 'message' => 'User disapproved successfully.', 'user' => $user], 200);
            } else {
                return response()->json(['success' => false, 'message' => 'User not found.'], 404);
            }
    
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => $th->getMessage()], 500);
        }
    }


    public function fetchUser()
    {
        try {
            $users = User::where('role','!=','admin')->get();
                return response()->json(['success' => true, 'message' => 'Successfully fetched All the Users', 'user' => $users],200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 404);
        }
    }


    public function allProfile()
    {
        if (auth()->check()) {
            $user = auth()->user();
            if ($user->role == 'user') {

                $organizations = Organization::with(['followers', 'followers.following'])->orderBy('id','DESC')->get();

                return response()->json([
                    'status' => true,
                    'message' => 'Successfully fetch All the profiles of organization',
                    'data' => $organizations,
                ], 200);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'You do not have the permissions to fetch the organizations.',
                ], 403);
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Authentication required to fetch an organization.',
            ], 401);
        }
    }

    public function addUser(AdminAdduserRequest $request)
    {
        $validatedData = $request->validated();

        if (auth()->check()) {
            $user = auth()->user();

            if ($user->role == 'admin') {
            $newUser = User::create($validatedData);
                return response()->json([
                    'status' => true,
                    'message' => "Successfully user has been created by admin ",
                    'data' => $newUser,
                ], 200);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'You do not have the permissions to create the user.',
                ], 403);
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Authentication required to create the user.',
            ], 401);
        }
    }


    public function editUser(User $userId)
    {

        if (auth()->check()) {
            $user = auth()->user();

            if ($user->role == 'admin') {
                return response()->json([
                    'status' => true,
                    'message' => "Successfully user has been retrived by admin ",
                    'data' => $userId,
                ], 200);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'You do not have the permissions to retrive the user.',
                ], 403);
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Authentication required to retrived the user.',
            ], 401);
        }

    }


    public function updateUser(AdminUpdateUserRequest $request, User $userId)
    {
        $validatedData = $request->validated();

        if (auth()->check()) {
            $user = auth()->user();

            if ($user->role == 'admin') {
                $userId->update($validatedData);

                return response()->json([
                    'status' => true,
                    'message' => "Successfully user has been updated by admin ",
                    'data' => $userId,
                ], 200);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'You do not have the permissions to update the user.',
                ], 403);
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Authentication required to update the user.',
            ], 401);
        }

    }

    public function destroyUser(User $userId)
    {
        if (auth()->check()) {
            $user = auth()->user();

            if ($user->role == 'admin') {
                $userId->delete();
                return response()->json([
                    'status' => true,
                    'message' => "Successfully user has been deleted by admin ",
                    'data' => $userId,
                ], 200);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'You do not have the permissions to delete the user.',
                ], 403);
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Authentication required to delete the user.',
            ], 401);
        }
    }
    


}
