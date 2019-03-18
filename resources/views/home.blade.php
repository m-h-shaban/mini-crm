@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
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
                    Click on companies or employees to start adding data
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
