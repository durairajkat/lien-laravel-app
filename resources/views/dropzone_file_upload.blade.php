<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel: upload multiple file using drag and drop feature</title>
    <link href="http://demo.expertphp.in/css/dropzone.css" rel="stylesheet">
    <script src="http://demo.expertphp.in/js/dropzone.js"></script>
</head>

<body>
    <h3>Laravel 5.4 : Upload multiple file using dropzone</h3>
    {!! Form::open(['route' => ['dropzone.uploadfile'], 'files' => true, 'class' => 'dropzone', 'id' => 'image-upload']) !!}
    {!! Form::close() !!}
</body>

</html>
