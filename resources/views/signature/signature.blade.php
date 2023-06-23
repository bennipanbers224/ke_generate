@extends('tamplate.layout')

    @section('content')

        <div class="container">

        <div class="d-flex h-100">
            <div class="align-self-start mr-auto">
                <button type="button" class="btn btn-outline-info">
                  Requested
                </button>
            </div>
            <div class="align-self-center mx-auto">
                <button type="button" class="btn btn-outline-success">
                  Done
                </button>
            </div>
            <div class="align-self-end ml-auto">
                <a href="/manual">
                    <button type="button" class="btn btn-outline-secondary">
                        Manual Signing
                    </button>
                </a>
            </div>
        </div>
            
            <table class="table">
                <thead>
                    <tr>
                    <th scope="col">No</th>
                    <th scope="col">Name</th>
                    <th scope="col">User Status</th>
                    <th scope="col">File</th>
                    <th scope="col">Status</th>
                    <th scope="col" colspan="2">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $data)
                        <tr>
                            <th scope="row">{{$data->id}}</th>
                            <td>{{$data->name}}</td>
                            <td>{{$data->status}}</td>
                            <td>{{$data->file_name}}</td>
                            @if($data->status_file == "Requested")
                            <td class="text-info">{{$data->status_file}}</td>
                            @else
                            <td class="text-success">{{$data->status_file}}</td>
                            @endif
                            <td><a href="/detail/{{$data->id}}/not-manual"><button type="button" class="btn btn-success">Detail</button></a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    @endsection