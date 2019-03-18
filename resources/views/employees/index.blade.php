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
                                <a href="{{ route('employees.create') }}">New Employee</a>
                            </div>
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col">id</th>
                                        <th scope="col">FirstName</th>
                                        <th scope="col">LastName</th>
                                        <th scope="col">Email</th>
                                        <th scope="col">Phone</th>
                                        <th scope="col">Company</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($employees as $employee)
                                        <tr id="row-{{ $employee->id }}">
                                            <td>{{ $employee->id }}</td>
                                            <td>{{ $employee->first_name }}</td>
                                            <td>{{ $employee->last_name }}</td>
                                            <td>{{ $employee->email }}</td>
                                            <td>{{ $employee->phone }}</td>
                                            <td><a href="{{ route('companies.edit', [$employee->company->id]) }}">{{ $employee->company->name }}</a></td>
                                            <th>
                                                <a href="{{ route('employees.edit', [$employee->id]) }}">Update</a>
                                                <button onclick="deleteEmployee({{$employee->id}})" >delete</button>
                                            </th>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <div class="row">
                                {{ $employees->links() }}
                            </div>
                            
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function deleteEmployee(id){

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
                url: "employees/"+id,
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
                        'Employee has been deleted.',
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
