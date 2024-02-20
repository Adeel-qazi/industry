<?php

namespace App\Http\Controllers;

use App\Exports\ExportOrganization;
use App\Http\Requests\StoreOrganizationProfileRequest;
use App\Http\Requests\UpdateOrganizationProfileRequest;
use App\Http\Requests\ValidateExcelRequest;
use App\Imports\ImportOrganization;
use App\Models\Organization;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;

class OrganizationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (auth()->check()) {
            $user = auth()->user();
            if ($user->role == 'admin') {

                $organizations = Organization::orderBy('id', 'DESC')->get();
                ;

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

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOrganizationProfileRequest $request)
    {
        if (auth()->check()) {
            $user = auth()->user();

            if ($user->role == 'admin') {
                $validatedData = $request->validated();

                $organization = $user->organizations()->create($validatedData);

                return response()->json([
                    'status' => true,
                    'message' => 'Profile of Organization created successfully',
                    'data' => $organization,
                ], 200);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'You do not have the permissions to create an organization profile.',
                ], 403);
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Authentication required to create an organization profile.',
            ], 401);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(Organization $organization)
    {
        try {
            return response()->json(['success' => true, 'message' => 'Successfully fetched the single data', 'data' => $organization], 200);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => 'Failed to fetch the data', 'error' => $th->getMessage()], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Organization $organization)
    {
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOrganizationProfileRequest $request, Organization $organization)
    {

        $validatedData = $request->validated();
        
        if (auth()->check()) {
            $user = auth()->user();

            if ($user && $user->role == 'admin') {
                $organization->user_id = $validatedData['user_id'] ?? $organization->user_id;
                $organization->update($validatedData);

                $notificationData = [
                    'sender_id' => $user->id,
                    'receiver_id' => $organization->user_id, 
                    'message' => "The " . collect(array_keys($validatedData))->join(", ", " and ") . " of profile has been updated by an admin",
                ];
                
                
                $user->sentNotifications()->create($notificationData);

                return response()->json([
                    'status' => true,
                    'message' => 'Profile of Organization updated successfully',
                    'data' => $organization,
                ], 200);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'You do not have the permissions to create an organization profile.',
                ], 403);
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Authentication required to create an organization profile.',
            ], 401);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Organization $organization)
    {
        if (auth()->check()) {
            $user = auth()->user();
            if ($user->role == 'admin') {
                $deletedOrganization = $organization;
                $organization->delete();
                return response()->json(['success' => true, 'message' => 'Successfully deleted the organization', 'data' => $deletedOrganization], 200);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'You do not have the permissions to delete an organization profile.',
                ], 403);
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Authentication required to delete an organization profile.',
            ], 401);
        }
    }


    public function import(ValidateExcelRequest $request)
    {
        try {
            $file = $request->file;
            if (!$file) {
                throw new \Exception('File not found in the request.');
            }
            $folderName = 'files';
            if (!Storage::exists($folderName)) {
                Storage::makeDirectory($folderName);
            }
            $filePath = $file->store($folderName);
            Excel::import(new ImportOrganization, $filePath);
            return response()->json(['success' => true, 'message' => 'File imported successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error importing file: ' . $e->getMessage()]);
        }
    }


    public function export()
    {
        return Excel::download(new ExportOrganization, 'organizations.xlsx');
    }



    public function followProfile($profile)
    {
        $organization = Organization::findOrFail($profile);

        if (!auth()->check()) {
            return response()->json([
                'status' => false,
                'message' => 'Authentication required to follow the profile.',
            ], 401);
        }

        $user = auth()->user();

        if ($user->role !== 'user') {
            return response()->json([
                'status' => false,
                'message' => 'You do not have the permissions to follow the profile.',
            ], 403);
        }

        if ($user->following->contains($organization->id)) {
            return response()->json([
                'status' => true,
                'message' => 'You are already following',
                'data' => $organization->first_nation,
            ], 200);
        }

        $user->following()->attach($organization->id);

        return response()->json([
            'status' => true,
            'message' => 'User followed the profile successfully',
            'data' => $organization->first_nation,
        ], 200);

    }


    public function getAllProfiles()
    {
        if (!auth()->check()) {
            return response()->json([
                'status' => false,
                'message' => 'Authentication required to fetch all profiles.',
            ], 401);
        }

        $user = auth()->user();

        if ($user->role !== 'user') {
            return response()->json([
                'status' => false,
                'message' => 'You do not have the permissions to tch all profiles.',
            ], 403);
        }

        $profiles = $user->following;



        return response()->json([
            'status' => true,
            'message' => 'Profiles retrieved successfully',
            'data' => $profiles,
        ], 200);
    }


}
