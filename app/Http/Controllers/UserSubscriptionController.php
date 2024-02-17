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


    // public function createPackage(Subscription $subscription)
    // {
    //     $amount = $subscription->price;
    //     try {
    //         $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
    //                 $checkout_session = $stripe->checkout->sessions->create([
    //             'line_items' => [
    //                 [
    //                     'price_data' => [
    //                         'currency' => 'USD',
    //                         'profile_data' => [
    //                             'name' => 'Package',
    //                         ],
    //                         'unit_amount' => $amount * 100 ,
    //                     ],
    //                     'quantity' => 1,
    //                 ]],
    //                 'customer_email' => auth()->user()->email,
                
    //             'metadata' => [
    //                 'package' => $subscription->id,
    //                 'user' => auth()->user()->id,
    //             ],
    //             'mode' => 'payment',
    //             'success_url' => route('checkout.success') . '?session_id={CHECKOUT_SESSION_ID}',
    //             'cancel_url' => route('checkout.cancel'),
                
    //         ]);
          
    //         // return redirect($checkout_session->url);
    //         return response()->json(['id' => $checkout_session->url]);
           
    //     } catch (\Throwable $th) {
    //                 return response()->json(['status' => false, 'error' => $th->getMessage()]);
    //     }

    // }


    public function storePackage(SubscribedPackageRequest $request, Subscription $subscription)
    {
       

    // $checkout_session_id = $_GET['session_id'];

    try {

        $user = auth()->user();
        $amount = $subscription->price;
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));

        $charge = Stripe\Charge::create([
            "amount" => round($amount, 2) * 100,
            "currency" => "USD",
            "source" => $request->source,
            "description" => "Test payment from HNHTECHSOLUTIONS."
        ]);
        if(UserSubscription::where('payment_id',$charge->id)->exists()){
                return response()->json(['status' => false,'message'=>'invalid request payment already exists on this session id']);
        }
        UserSubscription::create(['user_id' => $user->id, 'subscription_id'=>$subscription->id]);
            return response()->json(['status' => true,'message'=>'Thank you for subscribing to our platform ']);

               
       
    } catch (\Throwable $th) {
                return response()->json(['status' => false, 'error' => $th->getMessage()]);
    }
    }

    public function cancelPackage()
    {
        return response()->json(['status' => true,'message'=>'Package has been cancelled']);

    }
}
