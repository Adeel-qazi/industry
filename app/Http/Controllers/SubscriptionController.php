<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSubscriptionRequest;
use App\Http\Requests\UpdateSubscriptionRequest;
use App\Models\Subscription;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
     public function index()
    {
        if (auth()->check()) {
            $user = auth()->user();

            if ($user->role == 'admin') {

                $subscriptions = Subscription::orderBy('id','DESC')->get();

                return response()->json([
                    'status' => true,
                    'message' => 'Successfully fetch All subscriptions',
                    'data' => $subscriptions,
                ], 200);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'You do not have the permissions to fetch All subscriptions.',
                ], 403);
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Authentication required to fetch All subscriptions.',
            ], 401);
        }
    
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // return view('admin.Subscription.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSubscriptionRequest $request)
    {
        if (auth()->check()) {
            $user = auth()->user();
            if ($user->role == 'admin') {
                $validatedData = $request->validated();
                $subscription = Subscription::create($validatedData);

                return response()->json([
                    'status' => true,
                    'message' => 'Subscription created successfully',
                    'data' => $subscription,
                ], 200);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'You do not have the permissions to create subscription.',
                ], 403);
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Authentication required to create subscription.',
            ], 401);
        }
    
    }

    /**
     * Display the specified resource.
     */
    public function show(Subscription $subscription)
    {
        try {
            return response()->json(['success' => true, 'message' => 'Successfully fetched the single data', 'data' => $subscription], 200);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => 'Failed to fetch the data', 'error' => $th->getMessage()], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Subscription $subscription)
    {
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSubscriptionRequest $request, Subscription $subscription)
    {

        if (auth()->check()) {
            $user = auth()->user();

            if ($user->role == 'admin') {
                $validatedData = $request->validated();
                $subscription->update($validatedData);

                return response()->json([
                    'status' => true,
                    'message' => 'Subscription updated successfully',
                    'data' => $subscription,
                ], 200);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'You do not have the permissions to update Subscription.',
                ], 403);
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Authentication required to update the Subscription.',
            ], 401);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Subscription $subscription)
    {

        if (auth()->check()) {
            $user = auth()->user();
            if ($user->role == 'admin') {
                $deletedSubscription = $subscription;
                $subscription->delete();
                return response()->json(['success' => true, 'message' => 'Successfully deleted the subscription', 'data' => $deletedSubscription], 200);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'You do not have the permissions to delete subscription.',
                ], 403);
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Authentication required to delete subscription.',
            ], 401);
        }
    }


    public function statusUpdated($subscriptionId)
    {
        if (auth()->check()) {
            $user = auth()->user();

            if ($user->role == 'admin') {

                $subscription = Subscription::findOrFail($subscriptionId);
                if($subscription->active == true){
                    $subscription->update(['active'=>0]);
                }else{
                    $subscription->update(['active'=>true]);
                }
                $verifiedStatus = $subscription->active == true ? 'active': 'inactive';
                return response()->json([
                    'status' => true,
                    'message' => "Successfully subscription has been $verifiedStatus",
                    'data' => $subscription->plan_name,
                ], 200);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'You do not have the permissions to fetch All subscriptions.',
                ], 403);
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Authentication required to fetch All subscriptions.',
            ], 401);
        }
    }
}
