@extends('format.layout')

@section('title', 'Activity Logs - Student Management')

@section('Content')
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="mb-0">Activity Logs</h1>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('students.index') }}" class="btn btn-secondary">
                Back to Students
            </a>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            @if(count($logs) > 0)
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>Action</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Email</th>
                                <th>Contact Number</th>
                                <th>Degree</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($logs as $log)
                                <tr>
                                    <td>
                                        <strong>{{ $log['message'] }}</strong>
                                    </td>
                                    <td>
                                        @if($log['data'])
                                            @php
                                                $studentName = $log['data']['student_name'] ?? $log['data']['new_name'] ?? $log['data']['old_name'] ?? '';
                                                $nameParts = explode(' ', $studentName);
                                                $firstName = $nameParts[0] ?? '';
                                            @endphp
                                            {{ $firstName }}
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td>
                                        @if($log['data'])
                                            @php
                                                $studentName = $log['data']['student_name'] ?? $log['data']['new_name'] ?? $log['data']['old_name'] ?? '';
                                                $nameParts = explode(' ', $studentName);
                                                $lastName = end($nameParts) ?? '';
                                            @endphp
                                            {{ $lastName }}
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td>
                                        @if($log['data'] && isset($log['data']['email']))
                                            {{ $log['data']['email'] }}
                                        @elseif($log['data'] && isset($log['data']['new_email']))
                                            {{ $log['data']['new_email'] }}
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td>
                                        @if($log['data'] && isset($log['data']['contact_no']))
                                            {{ $log['data']['contact_no'] }}
                                        @elseif($log['data'] && isset($log['data']['new_phone']))
                                            {{ $log['data']['new_phone'] }}
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td>
                                        @if($log['data'])
                                            @if(isset($log['data']['old_degree']) && isset($log['data']['new_degree']))
                                                {{ $log['data']['old_degree'] }} → {{ $log['data']['new_degree'] }}
                                            @elseif(isset($log['data']['degree']))
                                                {{ $log['data']['degree'] }}
                                                @if(strpos($log['message'], 'created') !== false)
                                                    (Added)
                                                @elseif(strpos($log['message'], 'deleted') !== false)
                                                    (Deleted)
                                                @endif
                                            @elseif(isset($log['data']['new_degree']))
                                                {{ $log['data']['new_degree'] }}
                                                @if(strpos($log['message'], 'created') !== false)
                                                    (Added)
                                                @endif
                                            @else
                                                N/A
                                            @endif
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-info text-center" role="alert">
                    No activity logs found yet. Student operations will appear here.
                </div>
            @endif
        </div>
    </div>
@endsection


