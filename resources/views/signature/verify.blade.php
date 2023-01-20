@extends('tamplate.layout')

@section('content')
<div class="container">
   
   <div class="panel panel-primary">
     <div class="panel-heading"><h2>Upload file for verification</h2></div>
     <div class="panel-body">
 
       @if (count($errors) > 0)
           <div class="alert alert-danger">
               <strong>Whoops!</strong> There were some problems with your input.
               <ul>
                   @foreach ($errors->all() as $error)
                       <li>{{ $error }}</li>
                   @endforeach
               </ul>
           </div>
       @endif
 
       <form action="/verify-file" method="POST" enctype="multipart/form-data">
           @csrf
           <div class="row">
 
               <div class="col-md-6">
                   <input type="file" name="file" class="form-control">
               </div>
  
               <div class="col-md-6">
                   <button type="submit" class="btn btn-success">Verify</button>
               </div>
  
           </div>
       </form>

       @if ($message = Session::get('success'))
       <iframe width="850px" height="500px" src="verify_file/{{ Session::get('file') }}"></iframe>
       <div class="alert alert-success alert-block">
               <strong>{{ $message }}</strong>
       </div>
       <?php $data =  Session::get('data'); ?>
       <table class="table table-striped">
            <thead>
                <tr>
                <th scope="col">Certificate Number</th>
                <th scope="col">Name</th>
                <th scope="col">Major</th>
                <th scope="col">Title</th>
                <th scope="col">Predicate</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    @foreach($data as $data)
                        <td>{{$data->certificate_number}}</td>
                        <td>{{$data->name}}</td>
                        <td>{{$data->major}}</td>
                        <td>{{$data->title}}</td>
                        <td>{{$data->predicate}}</td>
                    @endforeach
                </tr>
            </tbody>
        </table>
        @elseif($message = Session::get('error'))
        <iframe width="850px" height="500px" src="verify_file/{{ Session::get('file') }}"></iframe>
        <div class="alert alert-danger alert-block">
                <strong>{{ $message }}</strong>
        </div>
       @endif
 
     </div>
   </div>
</div>
@endsection