@extends('layouts.admin.app')
@section('content')

<h4 style="margin: 10px;">list of User</h4>






<form action="">
<table class="table table-sm table-hover">
                        <thead>
                            <th>Name</th>
                            <th>{{ $user->name}}</th>
                        <tbody>

                                <tr> 
                                <th>Email</th>
                            <th>{{  $user->email}}</th>
                                </tr>

                                <tr>
                                   <th>Phone Number</th>
                               <th>{{  $user->phone}}</th>
                                   </tr>
                                   <tr>
                                   <th>City Name</th>
                               <th>{{  $user->city}}</th>
                                   </tr>
                                   <tr>
                                   <th>Address</th>
                               <th>{{ $user->address}}</th>
                                   </tr>
                                   <tr>
                                   <th>Profile Photo</th>
                               <th><img src="{{ asset($user->profile_image) }}" alt="image" width="60" height="40"></th>
                                   </tr>
                                   <tr>
                                   <th>Car Photo</th>
                               <th><img src="{{ asset($user->car_docs) }}" alt="image" width="60" height="40"></th>
                                   </tr>
                                   <tr>
                                   <th>car license</th>
                               <th>{{  $user->car_license}}</th>
                                   </tr>
                                   <tr>
                                   <th>driving License</th>
                               <th>{{  $user->driving_license}}</th>
                                   </tr>
                                  
                                  
                                   <th>User Created Date</th>
                               <th>{{  $user->created_at}}</th>
                                   </tr>
                                   <tr>
                                   <th>User Updated date</th>
                               <th>{{ $user->updated_at}}</th>
                                   </tr>
                        

                       

                       
                        </tbody>

                       </thead>

                    </table>
                    @endsection