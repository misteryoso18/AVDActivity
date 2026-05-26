@extends('format.layout')

@section('title', 'Students - Student Management Dashboard')

@section('Content')
    <h1>Add Student</h1>
    <form method="POST" action="{{ route('students.store') }}">
        @csrf
        First Name: <input type="text" name="fname" value ="{{ old('fname') }}"><br>
        @error('fname')
        <small class="text-danger">{{ $message }}</small>
        @enderror
        
        Middle Name: <input type="text" name="mname" value ="{{ old('mname') }}"><br>
        @error('mname')
        <small class="text-danger">{{ $message }}</small>
        @enderror

        Last Name: <input type="text" name="lname" value ="{{ old('lname') }}"><br>
        @error('lname')
        <small class="text-danger">{{ $message }}</small>
        @enderror

        Email: <input type="email" name="email" value ="{{ old('email') }}"><br>
        @error('email')
        <small class="text-danger">{{ $message }}</small>
        @enderror

        Contact No.: <input type="text" name="contact_no" value ="{{ old('contact no') }}"><br>
        @error('contact_no')
        <small class="text-danger">{{ $message }}</small>
        @enderror

         First Name: <input type="text" name="fname" value ="{{ old('fname') }}"><br>
        @error('fname')
        <small class="text-danger">{{ $message }}</small>
        @enderror
        
        Middle Name: <input type="text" name="mname" value ="{{ old('mname') }}"><br>
        @error('mname')
        <small class="text-danger">{{ $message }}</small>
        @enderror

        Last Name: <input type="text" name="lname" value ="{{ old('lname') }}"><br>
        @error('lname')
        <small class="text-danger">{{ $message }}</small>
        @enderror

        <input type="submit" value="Submit">
    </form>
    @if ($errors->any())
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error )
                {{ $error }} <br>
            @endforeach
        </div>
    @endif
    @endsection