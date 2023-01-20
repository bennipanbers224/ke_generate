@extends('tamplate.layout')

@section('content')
<div class="container">
   <div class="panel panel-primary">
        <div class="panel-heading"><h2>Upload file for generate key</h2></div>
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
    
            <form action="{{ route('signature.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
        
                    <div class="col-md-6">
                        <input type="file" name="file" class="form-control">
                    </div>
        
                    <div class="col-md-6">
                        <button type="submit" class="btn btn-success">Upload</button>
                    </div>
        
                </div>
            </form>
            <br><br>
            @if ($message = Session::get('success'))
            <div class="alert alert-success alert-block" style="width: 500px;">
                    <strong>{{ $message }}</strong>
            </div>
            <iframe width="850px" height="500px" src="upload/{{ Session::get('file') }}"></iframe>
            @endif
    
        </div>
   </div>
</div>
@endsection