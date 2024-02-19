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

            $subscriptions = Subscription::orderBy('id','DESC')->get();

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


    public function storePackage(Request $request, Subscription $subscription)
    {

    try {

        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));

      $res = $stripe->tokens->create([
            'card' => [
              'number' => $request->number,
              'exp_month' => $request->exp_month,
              'exp_year' => $request->exp_year,
              'cvc' => $request->cvc,
            ],
          ]);

        $response =  $stripe->charges->create([
            'package' => $request->packageId,
            // 'amount' => round($request->amount, 2) * 100,
            'currency' => "USD",
            'source' => $request->token,
            ]);

            return response()->json([$response->status],201);

    } catch (\Throwable $th) {
                return response()->json(['status' => false, 'error' => $th->getMessage()]);
    }
    }

    public function cancelPackage()
    {
        return response()->json(['status' => true,'message'=>'Package has been cancelled']);

    }
}
