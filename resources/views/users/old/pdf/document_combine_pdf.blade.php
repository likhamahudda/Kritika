<!DOCTYPE html>
<html>
<head>
    <title></title>
</head>
<body>
    @if($total_pages > 0)
    @for($i=1;$i<=$total_pages;$i++)
        <img src="{{url('/').'/uploads/documents/getdoc/assign_doc/'.$assignid.'-'.$i.'.jpeg'}}"><br>
    @endfor
    @endif
    
</body>
</html>