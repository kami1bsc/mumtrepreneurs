@extends('layouts.admin.app')
@section('content')

<div class="mt-3">
    <div class="row">
        <div class="col-md-12 text-center">
            <h4>Verified Recruiters</h4>
            @if(session()->has('message'))
                <div class="alert alert-success text-center">
                    {{ session()->get('message') }}
                </div>
            @endif
            @if(session()->has('error'))
                <div class="alert alert-danger text-center">
                    {{ session()->get('error') }}
                </div>
            @endif
        </div>
    </div> 
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Verified Recruiters Data</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>ID</th>                        
                                    <th>Name</th>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th>Image</th>                                      
                                    <th>Phone</th>
                                    <th>Nationality</th>
                                    <th>Group Type</th>
                                    <th>Document Front Image</th> 
                                    <th>Document Back Image</th>                                              
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>ID</th>                        
                                    <th>Name</th>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th>Image</th>                                      
                                    <th>Phone</th>
                                    <th>Nationality</th>
                                    <th>Group Type</th>
                                    <th>Document Front Image</th> 
                                    <th>Document Back Image</th>                                              
                                    <th>Action</th>
                                </tr>
                            </tfoot>
                            <tbody>
                            @foreach($users as $user)
                                        <tr>
                                            <td>{{ $user->id }}</td>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->username }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>
                                                <img src="{{ asset($user->profile_image) }}" alt="Profile Image" style = "width: 60px; height: 40px;">                                           
                                            </td>
                                            <td>{{ $user->phone }}</td>
                                            <td>{{ $user->nationality }}</td>
                                            <td>{{ $user->group_type }}</td>
                                            <td>
                                                <img src="{{ asset($user->document_front_image) }}" alt="Document Front Image" style = "width: 60px; height: 40px;">
                                            </td>
                                            <td>
                                                <img src="{{ asset($user->document_back_image) }}" alt="Document Back Image" style = "width: 60px; height: 40px;">
                                            </td>
                                            <td>
                                                <!-- <a href="{{ route('admin.verify_recruiter', $user->id) }}" class = "btn btn-sm btn-outline-primary btn-circle"><i class = "fa fa-check"></i></a> -->
                                                <a href="{{ route('admin.delete_user', $user->id) }}" onclick = "return confirm('Are your sure to Delete?');" class = "btn btn-sm btn-outline-primary btn-circle"><i class = "fa fa-trash"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div> 
</div> 
    
@endsection