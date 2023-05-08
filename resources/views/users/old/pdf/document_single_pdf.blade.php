<!DOCTYPE html>
<html>
<head>
    <title></title>
</head>
<body>
    @if($start_page > 0)
    @for($i=$start_page;$i<=$end_page;$i++)
        <img src="{{url('/').'/uploads/documents/getdoc/assign_doc/'.$assignid.'-'.$i.'.jpeg'}}"><br>
    @endfor
    @endif
    
</body>
</html>