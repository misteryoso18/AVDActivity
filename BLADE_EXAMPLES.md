# Blade Directives Examples & Practical Use Cases

## Complete Blade Directive Examples

### 1. Layout Inheritance

**Master Layout** (`format/layout.blade.php`)
```blade
<!DOCTYPE html>
<html>
<head>
    <title>@yield('title', 'Default Title')</title>
</head>
<body>
    <header>
        <!-- Navigation -->
    </header>
    
    <main>
        @yield('Content')
    </main>
    
    <footer>
        @section('Footer')
            <p>Default footer content</p>
        @show
    </footer>
</body>
</html>
```

**Child Page** (`students.blade.php`)
```blade
@extends('format.layout')

@section('title', 'Students - Student Management Dashboard')

@section('Content')
    <h1>Student List</h1>
    <!-- Page content -->
@endsection

@section('Footer')
    <p>Custom footer for students page</p>
@endsection
```

---

### 2. Looping with @forelse

**Basic Loop**
```blade
@forelse($students as $student)
    <p>{{ $student->fname }}</p>
@empty
    <p>No students found.</p>
@endforelse
```

**Loop with First/Last/Iteration**
```blade
@forelse($students as $student)
    @if($loop->first)
        <table>
            <tr>
                <td>#</td>
                <td>Name</td>
            </tr>
    @endif
    
    <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ $student->fname }}</td>
    </tr>
    
    @if($loop->last)
        </table>
    @endif
@empty
    <p>No data</p>
@endforelse
```

---

### 3. Conditional Rendering

**Single Condition**
```blade
@if($student->age >= 18)
    <p>Adult Student</p>
@endif
```

**Multiple Conditions (if-elseif-else)**
```blade
@if($student->age == 19)
    <span class="badge bg-success">Freshman</span>
@elseif($student->age == 20)
    <span class="badge bg-info">Sophomore</span>
@elseif($student->age == 21)
    <span class="badge bg-warning">Junior</span>
@elseif($student->age == 22)
    <span class="badge bg-danger">Senior</span>
@else
    <span class="badge bg-secondary">Other</span>
@endif
```

**Ternary Operator**
```blade
{{ $student->course ?? 'Not Assigned' }}
```

---

### 4. Loop with Conditions

**Check Loop Status**
```blade
@foreach($students as $student)
    @if($loop->first)
        <div class="first-student">
    @endif
    
    <p>{{ $loop->count }} total items</p>
    <p>Iteration: {{ $loop->iteration }} of {{ $loop->count }}</p>
    
    @if($loop->last)
        </div>
    @endif
@endforeach
```

**Loop Index and Parity**
```blade
@foreach($students as $index => $student)
    <tr class="@if($loop->odd) odd @else even @endif">
        <td>{{ $loop->index }}</td>
        <td>{{ $student->fname }}</td>
    </tr>
@endforeach
```

---

### 5. Data Interpolation

**Basic Variable Output**
```blade
{{ $student->fname }}
{{ $student->lname }}
{{ $student->email }}
```

**Method Calls**
```blade
{{ strtoupper($student->fname) }}
{{ strlen($student->lname) }}
```

**Calculations**
```blade
{{ count($students) }}
{{ $student->age + 1 }}
{{ 100 - $student->age }}
```

**Conditional Output**
```blade
{{ $student->course ? $student->course : 'Not Assigned' }}
{{ $student->mname ?: 'No Middle Name' }}
```

---

### 6. Route and URL Generation

**Named Routes**
```blade
<a href="{{ route('home') }}">Home</a>
<a href="{{ route('students.index') }}">Students</a>
<a href="{{ route('about') }}">About</a>
```

**Routes with Parameters**
```blade
<a href="{{ route('students.show', $student->id) }}">View Student</a>
<a href="{{ route('students.edit', ['student' => $student->id]) }}">Edit</a>
```

**URL Generation**
```blade
<a href="{{ url('/students') }}">Students</a>
<a href="{{ url('/') }}">Home</a>
```

---

### 7. HTML Escaping

**Default (Escaped)**
```blade
{{ $user->name }}  <!-- Outputs escaped HTML -->
```

**Raw HTML (Unescaped)**
```blade
{!! $user->bio !!}  <!-- Outputs raw HTML -->
```

---

### 8. Array Access

**Access Array Elements**
```blade
{{ $student['fname'] }}
{{ $array[0] }}
```

**Null Coalescing**
```blade
{{ $student['middle_name'] ?? 'N/A' }}
```

---

### 9. Comments

**Blade Comments (Hidden from output)**
```blade
{{-- This is a blade comment and won't be shown --}}
```

**HTML Comments (Visible in source)**
```blade
<!-- This is an HTML comment -->
```

---

### 10. Unless Directive

**Basic Unless**
```blade
@unless($student->age < 18)
    <p>Adult Student</p>
@endunless
```

Equivalent to: `@if(!($age < 18))`

---

## Current Dashboard Blade Code Examples

### Students Table - Professional Bootstrap Implementation

```blade
<div class="table-responsive shadow-sm rounded">
    <table class="table table-hover table-striped mb-0">
        <thead class="table-dark">
            <tr>
                <th scope="col" class="text-center">#</th>
                <th scope="col">Full Name</th>
                <th scope="col">Email</th>
                <th scope="col">Contact No.</th>
                <th scope="col">Age</th>
                <th scope="col">Course/Program</th>
                <th scope="col" class="text-center">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($students as $student)
                <tr>
                    <td scope="row" class="text-center fw-bold text-primary">
                        {{ $loop->iteration }}
                    </td>
                    <td>
                        <strong>{{ $student->lname }}, {{ $student->fname }}</strong>
                        @if($student->mname)
                            <br><small class="text-muted">{{ $student->mname }}</small>
                        @endif
                    </td>
                    <td>{{ $student->email }}</td>
                    <td>{{ $student->contact_no }}</td>
                    <td class="text-center">
                        <span class="badge bg-secondary">{{ $student->age }} yrs</span>
                    </td>
                    <td><small>{{ $student->course }}</small></td>
                    <td class="text-center">
                        @if($student->age == 19)
                            <span class="badge bg-success">Freshman</span>
                        @elseif($student->age == 20)
                            <span class="badge bg-info">Sophomore</span>
                        @elseif($student->age == 21)
                            <span class="badge bg-warning">Junior</span>
                        @elseif($student->age == 22)
                            <span class="badge bg-danger">Senior</span>
                        @else
                            <span class="badge bg-secondary">Other</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center p-4">
                        <i class="fas fa-inbox text-muted"></i>
                        <p class="text-muted">No students available.</p>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
```

---

## Advanced Blade Patterns Used

### Pattern 1: Conditional Table Opening/Closing

The dashboard uses an advanced technique to wrap multiple rows in a single table:

```blade
@forelse($students as $student)
    @if($loop->first)
        <table>  <!-- Opens only for first item -->
            <thead>
                <tr><th>...</th></tr>
            </thead>
            <tbody>
    @endif
    
    <tr>
        <!-- Row data -->
    </tr>
    
    @if($loop->last)
            </tbody>
        </table>  <!-- Closes only for last item -->
    @endif
@empty
    <!-- Empty state -->
@endforelse
```

---

### Pattern 2: Conditional Content Display

```blade
@if(condition)
    <div>Conditional content</div>
@else
    <div>Alternative content</div>
@endif
```

---

### Pattern 3: Dynamic Badge Colors Based on Data

```blade
@forelse($students as $student)
    <span class="badge 
        @if($age == 19)
            bg-success
        @elseif($age == 20)
            bg-info
        @elseif($age == 21)
            bg-warning
        @else
            bg-danger
        @endif
    ">{{ $status }}</span>
@empty
    <span class="badge bg-secondary">N/A</span>
@endforelse
```

---

## Real-World Usage Scenarios

### Scenario 1: Display Filter Status

```blade
@if(request('status') == 'freshman')
    Showing: Freshman Students
@elseif(request('status') == 'sophomore')
    Showing: Sophomore Students
@else
    Showing: All Students
@endif
```

### Scenario 2: Display Pagination

```blade
@forelse($students as $student)
    <div>{{ $student->name }}</div>
@empty
    <p>No students found</p>
@endforelse

{{ $students->links() }}
```

### Scenario 3: Nested Loops

```blade
@foreach($departments as $dept)
    <h3>{{ $dept->name }}</h3>
    @forelse($dept->students as $student)
        <p>{{ $student->fname }}</p>
    @empty
        <p>No students in this department</p>
    @endforelse
@endforeach
```

---

## Syntax Cheat Sheet

| Syntax | Purpose |
|--------|---------|
| `@extends()` | Inherit from a parent template |
| `@section()` | Define a section |
| `@endsection` | End a section |
| `@yield()` | Display a section |
| `@forelse()` | Loop with empty fallback |
| `@endforelse` | End forelse |
| `@empty` | Handle empty collection |
| `@if()` | Start conditional |
| `@elseif()` | Else if condition |
| `@else` | Else condition |
| `@endif` | End conditional |
| `@foreach()` | Loop through items |
| `@endforeach` | End foreach |
| `@unless()` | Inverse if |
| `@endunless` | End unless |
| `{{ }}` | Echo variable (escaped) |
| `{!! !!}` | Echo variable (unescaped) |
| `{{ route() }}` | Generate route URL |
| `{{ url() }}` | Generate URL |
| `@auth` | Check if authenticated |
| `@endauth` | End auth check |
| `{{-- --}}` | Blade comment |

---

## Extending the Dashboard

### Add Edit Student Page

```blade
@extends('format.layout')

@section('title', 'Edit Student')

@section('Content')
    <form action="{{ route('students.update', $student->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="form-group">
            <label>First Name</label>
            <input type="text" class="form-control" name="fname" value="{{ $student->fname }}">
        </div>
        
        <div class="form-group">
            <label>Age</label>
            <input type="number" class="form-control" name="age" value="{{ $student->age }}">
        </div>
        
        <div class="form-group">
            <label>Course</label>
            <input type="text" class="form-control" name="course" value="{{ $student->course }}">
        </div>
        
        <button type="submit" class="btn btn-primary">Update Student</button>
    </form>
@endsection
```

---

### Add Search Filter

```blade
<div class="mb-3">
    <form action="{{ route('students.index') }}" method="GET">
        <div class="row">
            <div class="col-md-8">
                <input type="text" class="form-control" name="search" placeholder="Search students..." value="{{ request('search') }}">
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary w-100">Search</button>
            </div>
        </div>
    </form>
</div>

@if(request('search'))
    <p class="text-muted">Showing results for: {{ request('search') }}</p>
@endif
```

---

## Performance Best Practices

1. **Use `@forelse` instead of `@foreach` + `@if` empty check**
   ```blade
   ✓ Good
   @forelse($items as $item)
       ...
   @empty
       ...
   @endforelse
   
   ✗ Avoid
   @if(count($items) > 0)
       @foreach($items as $item)
           ...
       @endforeach
   @endif
   ```

2. **Cache computed values**
   ```blade
   @php
       $studentCount = count($students);
       $freshmanCount = count(array_filter($students, fn($s) => $s->age == 19));
   @endphp
   
   Total: {{ $studentCount }}
   Freshman: {{ $freshmanCount }}
   ```

3. **Use `{{ }}` instead of `{!! !!}` when possible** (for security)

---

## Debugging Tips

### View Variables in Blade
```blade
{{-- Debug output --}}
@dump($students)
@dd($students)  <!-- Dies and dumps -->
```

### Check Loop Information
```blade
@forelse($students as $student)
    @if($loop->first)
        <!-- First iteration -->
    @elseif($loop->last)
        <!-- Last iteration -->
    @else
        <!-- Middle iterations -->
    @endif
@empty
    <p>No data</p>
@endforelse
```

---

## Bootstrap Integration Best Practices

Used in the dashboard:
- `table-hover` - Row highlight on hover
- `table-striped` - Alternate row colors
- `badge` - Status indicators
- `card` - Content containers
- `shadow-sm` - Subtle shadows
- `border-0` - Remove borders
- `text-center`, `text-start`, `text-end` - Text alignment
- `me-*`, `ms-*`, `mb-*`, `mt-*` - Margin utilities
- `fw-bold` - Font weight
- `text-muted` - Subtle text color

