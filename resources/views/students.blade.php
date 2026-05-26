@extends('format.layout')

@section('title', 'Students - Student Management Dashboard')

@section('Content')
    <h1>Student List</h1>
    @if(session('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
        @endif
    
    <table border="1" cellpadding="10" cellspacing="0">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Email</th>
                <th>Contact Number</th>
                <th>Degree</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($students as $student)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $student->lname }}, {{ $student->fname }} {{ $student->mname }}</td>
                    <td>{{ $student->email }}</td>
                    <td>{{ $student->contact_no }}</td>
                    <td>{{ $student['degree']['degree_title'] }}</td>
                    <td>
                        @if($student->age == 19)
                            Freshman Student
                        @elseif($student->age == 20)
                            Sophomore Student
                        @elseif($student->age == 21)
                            Junior Student
                        @elseif($student->age == 22)
                            Senior Student
                        @else
                         Irregular Student
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('students.show', $student->id) }}">View</a> |
                        <form method="POST" action="{{ route('students.destroy', $student->id) }}" style="display:inline;" onsubmit="return confirm('Are you sure?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="background:none; border:none; color:red; cursor:pointer;">Delete</button>
                        </form>
                    </td>
            @empty
                <tr>
                    <td colspan="8">No students available</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection
