<!--<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Page</title>
</head>
<body>
    <ul>
        <li><a href="">Home</a></li>
                <li><a href="">News</a></li>
                        <li><a href="">Contacts</a></li>

     </ul>
    <p>This is the Dashboard Page for our client.</p>
</body>
</html>-->


@extends('format.layout')

@section('Content')
    <h1>Profile</h1>
    <p>This is the Profile page for our client.</p>
    
    {{$grade}}<br>
    {{$name}}<br>
    {{$sex}}<br>
    {{$address}}<br>
    
    @if($grade % 2 == 0)
        Grade {{$grade}} is Even
    @else
        Grade {{$grade}} is Odd
    @endif
    <br>

    @if($grade>=75 && $grade <= 100)
        Your grade {{$grade}} is Passed!
    @else
        Your grade {{$grade}} is Failed!
    @endif
    <br><br>

    @php
     $i = 1;
    @endphp
    @while($i<=10)
        {{$i}}
        @php
         $i++;
        @endphp
    @endwhile
    <br><br>
    
    @for($a=1; $a<=5; $a++)
        @for($b=1; $b<=$a; $b++)
         *
        @endfor
        <br>
    @endfor
    <br>

    <table border="1" cellpadding="5" cellspacing="0">
        <tr>
            <th>Name</th>
            <th>Sex</th>
            <th>Address</th>
        </tr>
        @foreach($clients as $client)
            <tr>
                <td>{{$client['name']}}</td>
                <td>{{$client['sex']}}</td>
                <td>{{$client['address']}}</td>
            </tr>
        @endforeach
    </table>

@endsection