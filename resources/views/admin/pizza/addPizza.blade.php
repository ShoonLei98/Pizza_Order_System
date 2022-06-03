@extends('admin.layouts.app')

@section('content')

<div class="content-wrapper">
    <section class="content">
      <div class="container-fluid">
        <div class="row mt-4">
          <div class="col-8 offset-3 mt-5">
            <div class="col-md-9">
                <a href="{{ route('admin#pizza') }}" class="text-decoration-none text-black">
                    <div class="mb-3">
                        <i class="fa-solid fa-arrow-left"></i>
                        Back
                    </div>
                </a>
              <div class="card">
                <div class="card-header p-2">
                  <legend class="text-center">Add Pizza</legend>
                </div>
                <div class="card-body">
                  <div class="tab-content">
                    <div class="active tab-pane" id="activity">
                      <form class="form-horizontal" action="{{ route('admin#insertPizza') }}" method="post" enctype="multipart/form-data">
                          @csrf
                        <div class="form-group row">
                          <label for="inputName" class="col-sm-3 col-form-label">Pizza Name</label>
                          <div class="col-sm-9">
                            <input type="text" class="form-control" placeholder="Pizza Name" name="pizzaName">
                            @if ($errors->has('pizzaName'))
                                <p class='text-danger'>{{ $errors->first('pizzaName') }}</p>
                            @endif
                          </div>
                        </div>

                        <div class="form-group row">
                            <label for="inputName" class="col-sm-3 col-form-label">Image</label>
                            <div class="col-sm-9">
                              <input type="file" class="form-control" placeholder="Image" name="image">
                              @if ($errors->has('image'))
                                  <p class='text-danger'>{{ $errors->first('image') }}</p>
                              @endif
                            </div>
                          </div>

                          <div class="form-group row">
                            <label for="inputName" class="col-sm-3 col-form-label">Price</label>
                            <div class="col-sm-9">
                              <input type="number" class="form-control" placeholder="Price" name="price">
                              @if ($errors->has('price'))
                                  <p class='text-danger'>{{ $errors->first('price') }}</p>
                              @endif
                            </div>
                          </div>

                          <div class="form-group row">
                            <label for="inputName" class="col-sm-3 col-form-label">Publish Status</label>
                            <div class="col-sm-9">
                              {{-- <input type="text" class="form-control" placeholder="Publish Status" name="category_name"> --}}
                              <select name="publishStatus" class="form-control">
                                <option value="">Choose Options</option>
                                <option value="1">Publish</option>
                                <option value="0">Unpublish</option>
                              </select>
                              @if ($errors->has('publishStatus'))
                                  <p class='text-danger'>{{ $errors->first('publishStatus') }}</p>
                              @endif
                            </div>
                          </div>

                          <div class="form-group row">
                            <label for="inputName" class="col-sm-3 col-form-label">Category</label>
                            <div class="col-sm-9">
                            <select name="category" class="form-control">
                                <option value="">Choose Caegory</option>
                                @foreach ($category as $item)
                                <option value="{{ $item->category_id }}">{{ $item->category_name }}</option>
                                @endforeach

                            </select>
                              @if ($errors->has('category'))
                                  <p class='text-danger'>{{ $errors->first('category') }}</p>
                              @endif
                            </div>
                          </div>

                          <div class="form-group row">
                            <label for="inputName" class="col-sm-3 col-form-label">Discount Price</label>
                            <div class="col-sm-9">
                              <input type="number" class="form-control" placeholder="Discount Price" name="discountPrice">
                              @if ($errors->has('discountPrice'))
                                  <p class='text-danger'>{{ $errors->first('discountPrice') }}</p>
                              @endif
                            </div>
                          </div>

                          <div class="form-group row">
                            <label for="inputName" class="col-sm-3 col-form-label">Buy One Get One</label>
                            <div class="col-sm-9 mt-2">
                              <input type="radio" class="form-input-check" name="buy1get1" value="1">Yes
                              <input type="radio" class="form-input-check" name="buy1get1" value="0">No
                              @if ($errors->has('buy1get1'))
                                  <p class='text-danger'>{{ $errors->first('buy1get1') }}</p>
                              @endif
                            </div>
                          </div>

                          <div class="form-group row">
                            <label for="inputName" class="col-sm-3 col-form-label">Waiting Time</label>
                            <div class="col-sm-9">
                              <input type="text" class="form-control" placeholder="Waiting Time" name="waitingTime">
                              @if ($errors->has('waitingTime'))
                                  <p class='text-danger'>{{ $errors->first('waitingTime') }}</p>
                              @endif
                            </div>
                          </div>

                          <div class="form-group row">
                            <label for="inputName" class="col-sm-3 col-form-label">Description</label>
                            <div class="col-sm-9">
                                <textarea name="description" cols="30" rows="3" class="form-control" placeholder="Description"></textarea>
                                @if ($errors->has('description'))
                                  <p class='text-danger'>{{ $errors->first('description') }}</p>
                                @endif
                            </div>
                          </div>

                        <div class="form-group row">
                          <div class="offset-sm-2 col-sm-10 text-center">
                            <button type="submit" class="btn bg-dark text-white">Add</button>
                          </div>
                        </div>
                      </form>

                    </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>

@endsection
