@extends('tamplate.layout')

@section('content')

        <div class="container">
            <div class="panel panel-primary">
                    <div class="panel-heading"><h2>Upload file for request signature</h2></div>
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
                
                        <form action="/file-post" method="POST" enctype="multipart/form-data">
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
                        @endif
                
                    </div>
            </div>

            <table class="table">
                <thead>
                    <tr>
                    <th scope="col">No</th>
                    <th scope="col">File</th>
                    <th scope="col">Status</th>
                    <th scope="col" colspan="2">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $data)
                        <tr>
                            <th scope="row">{{$data->id}}</th>
                            <td>{{$data->file_name}}</td>
                            @if($data->status == "Requested")
                            <td class="text-info">{{$data->status}}</td>
                            @else
                            <td class="text-success">{{$data->status}}</td>
                            @endif
                            <td><a href="/detail/{{$data->id}}"><button type="button" class="btn btn-success">Detail</button></a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

@endsection