@extends('tamplate.layout')

    @section('content')

        <div class="container">

        <div role="group" aria-label="Basic example">
            <button type="button" class="btn btn-outline-primary">Request</button>
            <button type="button" class="btn btn-outline-success">Done</button>
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
                            <td><a href="/detail/{{$data->id}}"><button type="button" class="btn btn-success">Detail</button></a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    @endsection