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
       <br><br>

       @if ($message = Session::get('success'))
       <iframe width="850px" height="500px" src="verify_file/{{ Session::get('file') }}"></iframe>
       <div class="alert alert-success alert-block">
               <strong>{{ $message }}</strong>
       </div>
       <?php $data =  Session::get('data'); ?>
       <table class="table table-striped">
            <tbody>
                <tr>
                    <td>This file is verified by {{$data['name']}}</td>
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