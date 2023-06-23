@extends('tamplate.layout')

@section('content')

        <div class="container">
            <div class="panel panel-primary">
                    <div class="panel-heading"><h2>Upload file for manual signing</h2></div>
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
                
                        <form action="/manual-signing" method="POST" enctype="multipart/form-data">
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
                        <?php $filename = Session::get('file');?>
                        <iframe width="850px" height="600px" src='{{asset("upload/{$filename}")}}'></iframe>
                        @endif
                
                    </div>
            </div>

            <table class="table">
                <thead>
                    <tr>
                    <th scope="col">No</th>
                    <th scope="col">File</th>
                    <th scope="col" colspan="2">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $data)
                        <tr>
                            <th scope="row">{{$data->id}}</th>
                            <td>{{$data->file_name}}</td>
                            <td><a href="/detail/{{$data->id}}/manual"><button type="button" class="btn btn-success">Detail</button></a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

@endsection