@extends('tamplate.layout')

    @section('content')
        @if ($message = Session::get('success'))
            <div class="alert alert-success alert-block">
                    <strong>{{ $message }}</strong>
            </div>
        @elseif($message = Session::get('error'))
            <div class="alert alert-danger alert-block">
                    <strong>{{ $message }}</strong>
            </div>
       @endif

        <?php $file = $data['file_name']; ?>
        <div class="container">
            <iframe style="width:100%;" height="600px" src='{{asset("upload/{$data["file_name"]}")}}'></iframe>

            <table class="table">
                <thead>
                    <tr>
                    <th scope="col">Name</th>
                    <th scope="col">User's Status</th>
                    <th scope="col">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{$data['name']}}</td>
                        <td>{{$data['status']}}</td>
                        @if($data['status_file'] == "Requested")
                        <td class="text-info">{{$data['status_file']}}</td>
                        @else
                        <td class="text-success">{{$data['status_file']}}</td>
                        @endif
                    </tr>
                </tbody>
            </table>


            @if(Auth()->user()->status == "Admin")
                <form action="/signing" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <input type="hidden" name="file_id" value="{{$data['id']}}" class="form-control">
                        <input type="hidden" name="file_name" value="{{$data['file_name']}}" class="form-control">

                        @if($data['status_file'] == "Requested")
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-outline-info">Signing This File</button>
                        </div>
                        @endif
                    </div>
                </form>
            @endif
        </div>

    @endsection