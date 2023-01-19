<html>
<head>
    <title>Signature Pad</title>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/css/bootstrap.css">
  
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script> 
    <link type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/south-street/jquery-ui.css" rel="stylesheet"> 
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script type="text/javascript" src="http://keith-wood.name/js/jquery.signature.js"></script>
  
    <link rel="stylesheet" type="text/css" href="http://keith-wood.name/css/jquery.signature.css">
  
    <style>
        .kbw-signature { width: 100%; height: 200px;}
        #sig canvas{
            width: 100% !important;
            height: auto;
        }
    </style>
  
</head>
<body class="bg-dark">
<div class="container">
   
   <div class="panel panel-primary">
     <div class="panel-heading"><h2>laravel 6 file upload example - ItSolutionStuff.com.com</h2></div>
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

       @if ($message = Session::get('success'))
       <div class="alert alert-success alert-block">
           <button type="button" class="close" data-dismiss="alert">×</button>
               <strong>{{ $message }}</strong>
       </div>
       <iframe width="1000px" height="1000px" src="upload/{{ Session::get('file') }}"></iframe>
       @endif
 
     </div>
   </div>
</div>
</body>
</html>