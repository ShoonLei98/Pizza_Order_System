@extends('admin.layouts.app')

@section('content')

<div class="content-wrapper">
    <section class="content">
      <div class="container-fluid">
        <div class="row mt-4">
          <div class="col-8 offset-3 mt-5">
            @if (Session::has('updateSuccess'))
            <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                {{ Session::get('updateSuccess') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
          @endif
            <div class="col-md-9">
                <a href="{{ route('admin#adminList') }}" class="text-decoration-none text-black">
                    <div class="mb-3">
                        <i class="fa-solid fa-arrow-left"></i>
                        Back
                    </div>
                </a>
              <div class="card">
                <div class="card-header p-2">
                  <legend class="text-center">Update Admin</legend>
                </div>
                <div class="card-body">
                  <div class="tab-content">
                    <div class="active tab-pane" id="activity">
                      <form class="form-horizontal" action="{{ route('admin#updateAdmin', $admin->id) }}" method="get">
                          @csrf
                        <div class="form-group row">
                          <label for="inputName" class="col-sm-3 col-form-label mb-3">Name</label>
                          <div class="col-sm-9">
                            {{-- <input type="hidden" name="id" value="{{ $category->category_id }}"> --}}
                            <input type="text" class="form-control" placeholder="Name" name="name" value="{{ old('name',$admin->name) }}">
                            @if ($errors->has('name'))
                                <p class='text-danger'>{{ $errors->first('name') }}</p>
                            @endif
                          </div>

                          <label for="inputName" class="col-sm-3 col-form-label mb-3">Email</label>
                          <div class="col-sm-9">
                            <input type="text" class="form-control" placeholder="Email" name="email" value="{{ old('email',$admin->email) }}">
                            @if ($errors->has('email'))
                                <p class='text-danger'>{{ $errors->first('email') }}</p>
                            @endif
                          </div>

                          <label for="inputName" class="col-sm-3 col-form-label mb-3">Phone</label>
                          <div class="col-sm-9">
                            <input type="text" class="form-control" placeholder="Phone" name="phone" value="{{ old('phone',$admin->phone) }}">
                            @if ($errors->has('phone'))
                                <p class='text-danger'>{{ $errors->first('phone') }}</p>
                            @endif
                          </div>

                          <label for="inputName" class="col-sm-3 col-form-label mb-3">Address</label>
                          <div class="col-sm-9">
                            <input type="text" class="form-control" placeholder="Address" name="address" value="{{ old('address',$admin->address) }}">
                            @if ($errors->has('address'))
                                <p class='text-danger'>{{ $errors->first('address') }}</p>
                            @endif
                          </div>

                          <label for="inputName" class="col-sm-3 col-form-label">Account Role</label>
                          <div class="col-sm-9">
                            <input type="text" class="form-control" placeholder="Account Role" name="role" value="{{ old('role',$admin->role) }}">
                            @if ($errors->has('role'))
                                <p class='text-danger'>{{ $errors->first('role') }}</p>
                            @endif
                          </div>
                        </div>
                        <div class="form-group row">
                          <div class="offset-sm-2 col-sm-10 text-center">
                            <button type="submit" class="btn bg-dark text-white">Submit</button>
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
