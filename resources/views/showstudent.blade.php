@extends('format.layout')

@section('title', 'View Student - Student Management Dashboard')

@section('Content')
    <h1>Student Details</h1>
    <p><strong>First Name:</strong> {{ $student->fname }}</p>
    <p><strong>Middle Name:</strong> {{ $student->mname }}</p>
    <p><strong>Last Name:</strong> {{ $student->lname }}</p>
    <p><strong>Email:</strong> {{ $student->email }}</p>
    <p><strong>Contact No.:</strong> {{ $student->contact_no }}</p>
    <a href="{{ route('students.index') }}">Back to Student List</a>
@endsection
