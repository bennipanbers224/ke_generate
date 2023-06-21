@extends('tamplate.layout')

    @section('content')

        <?php $file = $data['file_name']; ?>
        <div class="container">
            <iframe width="850px" height="600px" src='{{asset("upload/{$data["file_name"]}")}}'></iframe>

            <table class="table">
                <thead>
                    <tr>
                    <th scope="col">Name</th>
                    <th scope="col">User's Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{$data['name']}}</td>
                        <td>{{$data['status']}}</td>
                    </tr>
                </tbody>
            </table>


            <form action="/signing" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <input type="hidden" name="file_id" value="{{$data['id']}}" class="form-control">
                    <input type="hidden" name="file_name" value="{{$data['file_name']}}" class="form-control">

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-outline-info">Signing This File</button>
                    </div>
        
                </div>
            </form>
        </div>

    @endsection