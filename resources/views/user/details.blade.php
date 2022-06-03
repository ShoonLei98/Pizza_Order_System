@extends('user.layout.style')

@section('content')
<div class="row mt-5 d-flex justify-content-center">

    <div class="col-4">
        <img src="{{ asset('uploads/'.$pizza->image) }}" class="img-thumbnail" width="100%">            <br>
        <a href="{{ route('user#order') }}">
            <button class="btn btn-primary float-end mt-2 col-12"><i class="fas fa-shopping-cart"></i> Order</button>
        </a>
        <a href="{{ route('user#index') }}">
            <button class="btn bg-dark text-white" style="margin-top: 20px;">
                <i class="fas fa-backspace"></i> Back
            </button>
        </a>
    </div>
    <div class="col-6">
        <div class="">
            <h5>
                <small>Name : </small><br>
                <small>{{ $pizza->pizza_name }}</small> <br><hr>
            </h5>
        </div>
        <div class="">
            <h5>
                <small>Price : </small><br>
                <small>{{ $pizza->price }}</small> kyats <br><hr>
            </h5>
        </div>
        <div class="">
            <h5>
                <small>Discount Price : </small><br>
                <small>{{ $pizza->discount_price }}</small> kyats <br><hr>
            </h5>
        </div>
        <div class="">
            <h5>
                <small>Buy One Get One : </small><br>
                @if ($pizza->buy_one_get_one_status == 1)
                <small>Abailable</small> <br><hr>
                @else
                <small>Not Available</small> <br><hr>
                @endif
            </h5>
        </div>
        <div class="">
            <h5>
                <small>Waiting Time : </small><br>
                <small>{{ $pizza->waiting_time }}</small> mins <br><hr>
            </h5>
        </div>
        <div class="">
            <h5>
                <small>Description : </small><br>
                <small>{{ $pizza->description }}</small> <br><hr>
            </h5>
        </div>
        <div class="">
            <h5>
                <small>Total Price : </small><br>
                <small class="text-secondary">{{ $pizza->price - $pizza->disctount_price }} kyats </small> <br><hr>
            </h5>
        </div>
    </div>
</div>
@endsection
