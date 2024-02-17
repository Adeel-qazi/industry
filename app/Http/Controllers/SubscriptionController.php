<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSubscriptionRequest;
use App\Models\Subscription;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
     public function index()
    {
      $subscriptions = Subscription::orderBy('id','DESC')->get();
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
        $validatedData = $request->validated();
        Subscription::create($validatedData);
        return redirect()->route('Subscriptions.index')->with('success','Subscription added successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Subscription $subscription)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Subscription $subscription)
    {

        return view('admin.Subscription.edit',compact('Subscription'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update( $request, Subscription $subscription)
    {
        $validatedData = $request->validated();
        $subscription->update($validatedData);
        return redirect()->route('Subscriptions.index')->with('success','Subscription updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Subscription $subscription)
    {
        $subscription->delete();
        return redirect()->route('Subscriptions.index')->with('success','Subscription deleted successfully');
    }
}
