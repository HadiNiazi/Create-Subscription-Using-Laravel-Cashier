@extends('layouts.app')


@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif


                    <form method="POST" action="{{ route('plans.store') }}">
                        @csrf
                        <div class="form-group">
                          <label>Plan Name</label>
                          <input type="text" name="name" class="form-control" placeholder="Enter email">
                        </div>
                        <div class="form-group">
                            <label>Amount</label>
                            <input type="number" name="amount" class="form-control" placeholder="Enter amount">
                          </div>
                          <div class="form-group">
                            <label>Currency</label>
                            <input type="text" name="currency" class="form-control" placeholder="Enter currency">
                          </div>
                          <div class="form-group">
                            <label>Interval Count</label>
                            <input type="number" name="interval_count" class="form-control" placeholder="Enter count">
                          </div>
                        <div class="form-group">
                          <label>Billing Period</label>
                          <select name="billing_period" class="form-control">
                              <option disabled selected>Choose billing method</option>
                              <option value="week">Weekly</option>
                              <option value="month">Monthly</option>
                              <option value="year">Yearly</option>
                          </select>
                        </div><br>
                        <button type="submit" class="btn btn-primary">Save</button>
                      </form>


                </div>
            </div>
        </div>
    </div>
</div>
@endsection

