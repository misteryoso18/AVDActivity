    <!--<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Profile Page</title>
    </head>
    <body>
        <ul>
            <li><a href="">Home</a></li>
                    <li><a href="">News</a></li>
                            <li><a href="">Contacts</a></li>

        </ul>
        <p>This is the Profile Page for our client.</p>
    </body>
    </html>-->

    @extends('format.layout')

    @section('Content')
        <h1>Profile</h1>
        <p>This is the Profile Page for our client.</p>
    @endsection

    @section('title')
        About Us Page
    @endsection

    @section('header')
        @parent
    @endsection

    @section('content')
        This is the Profile page for our client.
    @endsection

    @section('footer')
        @parent
    @endsection