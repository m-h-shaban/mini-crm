@extends('../layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    Dashboard
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <br><br>
                    <div>
                        <a href="{{ route('companies.index') }}">Companies</a>
                        <a href="{{ route('employees.index') }}">Employees</a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            @if ($message = Session::get('message'))
                                <div id="message-block" class="alert alert-success alert-block ">
                                    <button type="button" class="close" data-dismiss="alert">×</button>	
                                        <strong>{{ $message }}</strong>
                                </div>
                            @endif
                            <div>
                                <a href="{{ route('companies.create') }}">New Company</a>
                            </div>
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col">id</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Email</th>
                                        <th scope="col">Logo</th>
                                        <th scope="col">Website</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($companies as $company)
                                        <tr id="row-{{ $company->id }}">
                                            <td>{{ $company->id }}</td>
                                            <td>{{ $company->name }}</td>
                                            <td>{{ $company->email }}</td>
                                            <td>
                                                @isset($company->logo)
                                                    <img class="card-img-top" src="{{ asset('/public/storage/'.$company->logo)}}" alt="no image">
                                                @endisset
                                            </td>
                                            <td>{{ $company->website }}</td>
                                            <th>
                                                <a href="{{ route('companies.edit', [$company->id]) }}">Update</a>
                                                <button onclick="deleteCompany({{$company->id}})" >delete</button>
                                            </th>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <div class="row">
                                {{ $companies->links() }}
                            </div>
                            
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function deleteCompany(id){

        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax(
            {
                url: "companies/"+id,
                type: 'delete',
                dataType: "JSON",
                data: {
                },
                success: function ()
                {
                    var row = document.getElementById("row-"+id);

                    var parent = row.parentNode

                    parent.removeChild(row)

                    Swal.fire(
                        'Deleted!',
                        'Company has been deleted.',
                        'success'
                    )
                }
                ,error: function(xhr) {
                    
                }
            });
        })
    }
    $(document).ready(function() {
        // $('#example').DataTable();
        
    } );

</script>
@endsection
