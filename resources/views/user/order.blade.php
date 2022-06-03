@extends('user.layout.style')

@section('content')
<div class="row mt-5 d-flex justify-content-center">

    <div class="col-4">
        <img src="{{ asset('uploads/'.$pizza->image) }}" class="img-thumbnail" width="100%">            <br>
        <a href="{{ route('user#index') }}">
            <button class="btn bg-dark text-white" style="margin-top: 20px;">
                <i class="fas fa-backspace"></i> Back
            </button>
        </a>
    </div>
    <div class="col-6">
        @if (Session::has('orderSuccess'))
        <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
            Order Success...Pizza will be ready in {{ Session::get('orderSuccess') }} minutes.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif
        <form action="" method="post">
            @csrf
            <div class="">
                <h5>
                    <small>Name : </small><br>
                    <small>{{ $pizza->pizza_name }}</small> <br><hr>
                </h5>
            </div>
            <div class="">
                <h5>
                    <small>Price : </small><br>
                    <small>{{ $pizza->price - $pizza->disctount_price }} kyats</small> kyats <br><hr>
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
                    <small>Pizza Count : </small><br>
                    <input class="form-control mt-3" type="number" name="pizzaCount" placeholder="Number of Pizza">
                </h5>
                @if ($errors->has('pizzaCount'))
                    <p class="text-danger">{{ $errors->first('pizzaCount') }}</p>

                @endif
            </div>
            <div class="">
                <h5>
                    <small>Payment Type : </small><br>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input small" type="radio" name="paymentType" id="inlineRadio1" value="1">
                        <label class="form-check-label small" for="inlineRadio1">Credit Card</label>
                      </div>
                      <div class="form-check form-check-inline">
                        <input class="form-check-input small" type="radio" name="paymentType" id="inlineRadio2" value="2">
                        <label class="form-check-label small" for="inlineRadio2">Cash</label>
                      </div>
                </h5>
                @if ($errors->has('pizzaCount'))
                    <p class="text-danger">{{ $errors->first('paymentType') }}</p>

                @endif
            </div>

            <a href="{{ route('user#placeOrder') }}">
                <button class="btn btn-primary mt-5 col-3"><i class="fas fa-shopping-cart"></i>Place Order</button>
            </a>
        </form>
    </div>
</div>
@endsection
