@extends('tamplate.layout')

@section('content')

        <div class="container">
            <div class="panel panel-primary">
                    <div class="panel-heading"><h2>Upload file for signing</h2></div>
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
                
                        <form action="/manual-signing" style="width:100%;" method="POST" enctype="multipart/form-data" onsubmit = "return submitData(this)">
                            @csrf

                                <div class="col-md-6" style="width:100%;">
                                    <input type="text" name="name" placeholder="Name User Has File" class="form-control" required>
                                </div>
                                <br>

                                <div class="col-md-6" style="width:100%;">
                                    <input type="text" name="nim" placeholder="NIM" class="form-control" required>
                                </div>
                                <br>

                                <div class="col-md-6" style="width:100%;">
                                    <select name="major" class="form-control">
                                        <option value="D4 Teknologi Rekayasa Perangkat Lunak" selected="selected">D4 Teknologi Rekayasa Perangkat Lunak</option>
                                        <option value="D3 Teknologi Informasi">D3 Teknologi Informasi</option>
                                        <option value="D3 Teknik Komputer">D3 Teknik Komputer</option>
                                        <option value="S1 Sistem Informasi">S1 Sistem Informasi</option>
                                        <option value="S1 Teknik Informatika">S1 Teknik Informatika</option>
                                        <option value="S1 Teknik Elektro">S1 Teknik Elektro</option>
                                        <option value="S1 Teknik Bioproses">S1 Teknik Bioproses</option>
                                        <option value="S1 Manajemen Rekayasa">S1 Manajemen Rekayasa</option>
                                    </select>
                                </div>
                                <br>

                                <div class="form-group files">
                                    <label>Upload Your File : </label>
                                    <input type="file" class="form-control" name="file" required>
                                </div>
                                <br>
                    
                                <div class="col-md-6" style="width:100%;">
                                    <button style="width:100%;" type="submit" class="btn btn-outline-success">Upload</button>
                                </div>
                        </form>

                        <script>
                                function submitData(form) {
                                    swal({
                                        title: "Are you sure?",
                                        text: "This form will be submitted",
                                        icon: "warning",
                                        buttons: true,
                                        dangerMode: true,
                                    })
                                    .then(function (isOkay) {
                                        if (isOkay) {
                                            form.submit();
                                        }
                                    });
                                    return false;
                                }
                        </script>

                        <br><br>
                        @if ($message = Session::get('success'))
                        <div class="alert alert-success alert-block" style="width: 500px;">
                                <strong>{{ $message }}</strong>
                        </div>
                            <?php $filename = Session::get('file');?>
                            <table class="table">
                                <thead>
                                    <tr>
                                    <th scope="col">Name</th>
                                    <th scope="col">NIM</th>
                                    <th scope="col">Major</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $data = Session::get('data'); ?>
                                    <tr>
                                        <th scope="row">{{$data['name']}}</th>
                                        <td>{{$data['nim']}}</td>
                                        <td>{{$data['major']}}</a></td>
                                    </tr>
                                </tbody>
                            </table>
                            <iframe style="width:100%;" height="600px" src='{{asset("upload/{$filename}")}}'></iframe>
                        @elseif($message = Session::get('error'))
                            <div class="alert alert-danger alert-block" style="width: 500px;">
                                    <strong>{{ $message }}</strong>
                            </div>
                            <?php $filename = Session::get('file');?>
                            <table class="table">
                                <thead>
                                    <tr>
                                    <th scope="col">Name</th>
                                    <th scope="col">NIM</th>
                                    <th scope="col">Major</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $data = Session::get('data'); ?>
                                    <tr>
                                        <th scope="row">{{$data['name']}}</th>
                                        <td>{{$data['nim']}}</td>
                                        <td>{{$data['major']}}</a></td>
                                    </tr>
                                </tbody>
                            </table>
                            <iframe style="width:100%;" height="600px" src='{{asset("upload/{$filename}")}}'></iframe>
                        @endif
                    </div>
            </div>
        </div>

@endsection