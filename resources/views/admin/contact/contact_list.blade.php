@extends('admin.layouts.app')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">

          {{-- @if (Session::has('deleteSuccess'))
          <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
              {{ Session::get('deleteSuccess') }}
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        @endif

        @if (Session::has('updateSuccess'))
          <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
              {{ Session::get('updateSuccess') }}
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        @endif  --}}

        <div class="row mt-4">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <span class="fs-3 ml-5">Total - {{ $contact->total() }}</span>
                <div class="card-tools mt-2">
                    <form action="{{ route('admin#searchContact') }}" method="get">
                        @csrf
                        <div class="input-group input-group-sm" style="width: 150px;">
                            <input type="text" name="searchContact" class="form-control float-right" placeholder="Search" value="{{ Session::get('SEARCH_CONTACT') }}">
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
                      <th>Name</th>
                      <th>Email</th>
                      <th>Message</th>
                      {{-- <th></th> --}}
                    </tr>
                  </thead>
                  <tbody>

                    {{-- @if ($emptyStatus == 0)
                    <tr>
                        <td colspan="4">
                            <small class="text-muted">There is no data.</small>
                        </td>
                    </tr>

                @else () --}}
                            @foreach ($contact as $item)
                            <tr>
                                <td>{{ $item->contact_id }}</td>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->email }}</td>
                                <td>{{ $item->message }}</td>
                                {{-- <td>
                                <a href="{{ route('admin#editCategory', $item->category_id) }}">
                                    <button class="btn btn-sm bg-dark text-white"><i class="fas fa-edit"></i></button>
                                </a>
                                <a href="{{ route('admin#deleteCategory',$item->category_id) }}">
                                    <button class="btn btn-sm bg-danger text-white"><i class="fas fa-trash-alt"></i></button>
                                </a>
                                </td> --}}
                            </tr>
                        @endforeach
                      {{-- @endif --}}
                  </tbody>
                </table>

                <div class="mt-3 ms-3">{{ $contact->links() }}</div>
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
@endsection


