@extends('admin.layouts.app')

@section('content')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">


    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        @if (Session::has('createSuccess'))
        <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
            {{ Session::get('createSuccess') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      @endif

      @if (Session::has('updateSuccess'))
      <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
          {{ Session::get('updateSuccess') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif

      @if (Session::has('deleteSuccess'))
      <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
          {{ Session::get('deleteSuccess') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif
        <div class="row mt-4">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">
                    <a href="{{ route('admin#createPizza') }}">
                        <button class="btn btn-sm bg-dark text-white mt-1">
                            <i class="fa-solid fa-plus"></i>
                        </button>
                    </a>
                </h3>
                <span class="fs-4 ml-5">Total - {{ $pizza->total() }}</span>
                <div class="card-tools d-flex">
                    <a href="{{ route('admin#downloadPizza') }}">
                        <button class="btn btn-sm bg-success mr-3 mt-1">Download CSV</button>
                    </a>
                    <form action="{{ route('admin#searchPizza') }}" method="get">
                        <div class="input-group input-group-sm mt-1" style="width: 150px;">
                            @csrf
                            <input type="text" name="table_search" class="form-control float-right" placeholder="Search" value="{{ Session::get('PIZZA') }}">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-default">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap text-center">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Pizza Name</th>
                      <th>Image</th>
                      <th>Price</th>
                      <th>Publish Status</th>
                      <th>Buy 1 Get 1 Status</th>
                      <th></th>
                    </tr>
                  </thead>
                  <tbody>

                    @if ($emptyStatus == 0)
                        <tr>
                            <td colspan="7">
                                <small class="text-muted">There is no data.</small>
                            </td>
                        </tr>

                    @else ()
                        @foreach ($pizza as $item)
                            <tr>
                                <td>{{ $item->pizza_id }}</td>
                                <td>{{ $item->pizza_name }}</td>
                                <td>
                                <img src="{{ asset('uploads/'.$item->image) }}" class="img-thumbnail" width="100px">
                                </td>
                                <td>{{ $item->price }}</td>
                                <td>
                                    @if ($item->publish_status == 1)
                                        Publish
                                    @elseif ($item->publish_stats == 0)
                                        Unpublish
                                    @endif
                                </td>
                                <td>
                                    @if ($item->buy_one_get_one_status == 1)
                                        Yes
                                    @elseif ($item->buy_one_get_one_status == 0)
                                        No
                                    @endif
                                </td>
                                <td>
                                <a href="{{ route('admin#editPizza', $item->pizza_id) }}"><button class="btn btn-sm bg-dark text-white"><i class="fas fa-edit"></i></button></a>
                                <a href="{{ route('admin#deletePizza', $item->pizza_id) }}"><button class="btn btn-sm bg-danger text-white"><i class="fas fa-trash-alt"></i></button></a>
                                <a href="{{ route('admin#pizzaInfo', $item->pizza_id) }}"><button class="btn btn-sm bg-warning text-white"><i class="fas fa-eye"></i></button></a>
                                </td>
                            </tr>
                        @endforeach

                    @endif
                  </tbody>
                </table>
                <div class="mt-3 ms-3">
                    {{ $pizza->links() }}
                </div>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
        </div>

      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
@endsection
