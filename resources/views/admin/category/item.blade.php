@extends('admin.layouts.app')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row mt-4">
          <div class="col-8 offset-2 mt-4">
              <h3 class="text-center my-3">{{ $pizza[0]->category_name }}</h3>
            <div class="card">
              <div class="card-header ">
                <span class="fs-3 ml-3 ">Total - {{ $pizza->total() }}</span>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap text-center ">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Image</th>
                      <th>Name</th>
                      <th>Price</th>
                    </tr>
                  </thead>
                  <tbody>
                      @foreach ($pizza as $item)
                        <tr>
                            <td>{{ $item->pizza_id }}</td>
                            <td>
                                <img src="{{ asset('uploads/'.$item->image) }}" alt="No Image" class="img-thumbnail" width="100px">
                            </td>
                            <td>{{ $item->pizza_name }}</td>
                            <td>{{ $item->price }}</td>
                        </tr>
                      @endforeach

                  </tbody>
                </table>

                <div class="mt-3 ms-3">{{ $pizza->links() }}</div>
              </div>
              <!-- /.card-body -->
              <div class="card-footer">
                  <a href="{{ route('admin#category') }}" class="btn bg-black text-white float-right">Back</a>
              </div>
            </div>
            <!-- /.card -->
          </div>
        </div>

      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
@endsection


