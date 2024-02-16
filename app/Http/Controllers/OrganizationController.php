<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrganizationProfileRequest;
use App\Http\Requests\UpdateOrganizationProfileRequest;
use App\Models\Organization;
use Illuminate\Http\Request;

class OrganizationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        if (auth()->check()) {
            $user = auth()->user();

            if ($user->role == 'admin') {
                $validatedData = $request->validated();
                $organization->user_id = $validatedData['user_id'] ?? $organization->user_id;

                $organization->update($validatedData);

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



}
