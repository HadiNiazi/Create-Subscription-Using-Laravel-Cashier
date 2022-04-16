@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('alert-success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('alert-success') }}
                        </div>
                    @endif

                    @if (count($subscriptions) > 0)
                    <h4><b>Your Subscriptions</b></h4>
                    <table class="table">
                        <thead>
                          <tr>
                            <th scope="col">Plan Name</th>
                            <th scope="col">Subs Name</th>
                            <th scope="col">Price</th>
                            <th scope="col">Quantity</th>
                            <th scope="col">Trial Start At</th>
                            <th scope="col">Trial Ends At</th>
                          </tr>
                        </thead>
                        <tbody>
                            @foreach ($subscriptions as $subscription)
                                <tr>
                                    <td>{{ $subscription->plan->name }}</td>
                                    <td>{{ $subscription->name }}</td>
                                    <td>{{ $subscription->plan->price }}</td>
                                    <td>{{ $subscription->quantity }}</td>
                                    <td>{{ $subscription->trial_ends_at }}</td>
                                    <td>{{ $subscription->created_at }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @else
                    <h4>You are not subscribed to any plan</h4>
                    @endif


                </div>
            </div>
        </div>
    </div>
</div>
@endsection

