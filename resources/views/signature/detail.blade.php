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
        </div>

    @endsection