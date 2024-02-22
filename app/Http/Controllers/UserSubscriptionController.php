<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubscribedPackageRequest;
use App\Models\Subscription;
use App\Models\UserSubscription;
use Illuminate\Http\Request;

// use Illuminate\Support\Facades\Request;

class UserSubscriptionController extends Controller
{
    public function index()
    {

        if (auth()->check()) {
            $user = auth()->user();

            if ($user->role == 'user') {

                $subscriptions = Subscription::orderBy('id', 'DESC')->get();

                return response()->json([
                    'status' => true,
                    'message' => 'Successfully fetch All packages',
                    'data' => $subscriptions,
                ], 200);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'You do not have the permissions to fetch All packages.',
                ], 403);
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Authentication required to fetch All packages.',
            ], 401);
        }
    }


    public function storePackage(SubscribedPackageRequest $request)
    {

        try {
            $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));

            $response = $stripe->charges->create([
                'amount' => $request->amount * 100,
                'currency' => 'usd',
                'source' => $request->source,
                'description' => 'Testing Charge',
            ]);


            if (auth()->check()) {
                $user = auth()->user();

                if ($user->role == 'user') {
                    UserSubscription::create(['user_id' => $user->id, 'package_id' => $request->packageId, 'payment_id' => $response->id]);

                    return response()->json([
                        'status' => true,
                        'message' => "Successfully package has been subscribed ",
                    ], 200);
                } else {
                    return response()->json([
                        'status' => false,
                        'message' => 'You do not have the permissions to subscribe the package.',
                    ], 403);
                }
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Authentication required to subscribe the package.',
                ], 401);
            }



        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'error' => $th->getMessage()], 500);
        }


    }

    public function cancelPackage()
    {
        return response()->json(['status' => true, 'message' => 'Package has been cancelled']);

    }
}
