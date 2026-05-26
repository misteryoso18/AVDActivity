@extends('format.layout')

@section('title', 'Add New Student')

@section('Content')
<style>
    .form-page-header {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }
    .back-btn {
        width: 38px; height: 38px;
        border-radius: 10px;
        background: #fff;
        border: 1.5px solid #e8e0d8;
        display: inline-flex; align-items: center; justify-content: center;
        color: #666;
        text-decoration: none;
        font-size: 0.9rem;
        transition: border-color 0.15s, color 0.15s;
        flex-shrink: 0;
    }
    .back-btn:hover { border-color: var(--primary); color: var(--primary); }
    .form-page-header h1 {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 1.55rem;
        font-weight: 800;
        color: var(--primary-dark, #e65100);
        margin: 0;
    }

    .form-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 2px 14px rgba(0,0,0,0.07);
        overflow: hidden;
        max-width: 760px;
        margin: 0 auto;
    }

    .form-section {
        padding: 1.5rem 1.75rem;
        border-bottom: 1px solid #faf5f0;
    }
    .form-section:last-child { border-bottom: none; }

    .form-section-label {
        display: flex;
        align-items: center;
        gap: 0.55rem;
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 0.8rem;
        font-weight: 700;
        letter-spacing: 0.8px;
        text-transform: uppercase;
        color: #aaa;
        margin-bottom: 1.1rem;
    }
    .form-section-label .sl-icon {
        width: 26px; height: 26px;
        border-radius: 7px;
        background: #fff3e0;
        display: flex; align-items: center; justify-content: center;
        font-size: 0.72rem;
        color: var(--primary);
    }

    .field-row { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
    .field-row.one { grid-template-columns: 1fr; }
    .field-row.three { grid-template-columns: 2fr 1fr; }

    .field-group { display: flex; flex-direction: column; }
    .field-group label {
        font-size: 0.8rem;
        font-weight: 600;
        color: #555;
        margin-bottom: 0.35rem;
    }
    .field-group label .req { color: #f57c00; margin-left: 2px; }

    .field-group input,
    .field-group select {
        padding: 0.65rem 0.9rem;
        border: 1.5px solid #e8e0d8;
        border-radius: 9px;
        font-size: 0.88rem;
        font-family: 'DM Sans', sans-serif;
        color: #1a1a1a;
        background: #fdf9f6;
        outline: none;
        transition: border-color 0.18s, box-shadow 0.18s;
    }
    .field-group input:focus,
    .field-group select:focus {
        border-color: var(--primary, #f57c00);
        background: #fff;
        box-shadow: 0 0 0 3px rgba(245,124,0,0.10);
    }
    .field-group input.is-invalid,
    .field-group select.is-invalid { border-color: #e53935; }
    .invalid-feedback {
        font-size: 0.75rem;
        color: #e53935;
        margin-top: 0.25rem;
    }

    /* form footer */
    .form-footer {
        padding: 1.25rem 1.75rem;
        background: #fdf9f6;
        display: flex;
        gap: 0.75rem;
        align-items: center;
    }
    .btn-submit {
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        padding: 0.65rem 1.5rem;
        background: linear-gradient(135deg, var(--primary, #f57c00), var(--primary-dark, #e65100));
        color: #fff;
        border: none;
        border-radius: 10px;
        font-size: 0.9rem;
        font-weight: 700;
        font-family: 'Plus Jakarta Sans', sans-serif;
        cursor: pointer;
        box-shadow: 0 3px 10px rgba(245,124,0,0.3);
        transition: opacity 0.15s, transform 0.15s;
    }
    .btn-submit:hover { opacity: 0.9; transform: translateY(-1px); }
    .btn-cancel {
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        padding: 0.65rem 1.2rem;
        background: #fff;
        color: #888;
        border: 1.5px solid #e8e0d8;
        border-radius: 10px;
        font-size: 0.88rem;
        font-weight: 600;
        font-family: 'DM Sans', sans-serif;
        text-decoration: none;
        transition: border-color 0.15s, color 0.15s;
    }
    .btn-cancel:hover { border-color: #999; color: #444; }

    .error-summary {
        margin: 0 1.75rem 0;
        padding: 0.85rem 1rem;
        background: #fff5f5;
        border: 1px solid #fca5a5;
        color: #b91c1c;
        border-radius: 10px;
        font-size: 0.84rem;
    }
    .error-summary ul { margin: 0.4rem 0 0 1rem; padding: 0; }

    @media (max-width: 600px) {
        .field-row, .field-row.three { grid-template-columns: 1fr; }
    }
</style>

<div class="form-page-header">
    <a href="{{ route('students.index') }}" data-ajax-link="true" data-target="#ajaxPageContent" class="back-btn">
        <i class="fas fa-arrow-left"></i>
    </a>
    <h1>Add New Student</h1>
</div>

<div class="form-card">
    <form
        method="POST"
        action="{{ route('students.store') }}"
        novalidate
        data-ajax="true"
        data-loading="false"
        data-redirect="{{ route('students.index') }}"
        data-redirect-delay="0">
        @csrf

        @if ($errors->any())
            <div class="error-summary" style="margin:1.25rem 1.75rem 0;">
                <strong>Please fix the following errors:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Personal Info --}}
        <div class="form-section">
            <div class="form-section-label">
                <span class="sl-icon"><i class="fas fa-user"></i></span>
                Personal Information
            </div>

            <div class="field-row three" style="margin-bottom:1rem;">
                <div class="field-group">
                    <label for="fname">First Name <span class="req">*</span></label>
                    <input type="text" id="fname" name="fname"
                        class="@error('fname') is-invalid @enderror"
                        placeholder="e.g. Juan" minlength="2"
                        value="{{ old('fname') }}" required>
                    @error('fname') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>
                <div class="field-group">
                    <label for="mname">Middle Initial <span class="req">*</span></label>
                    <input type="text" id="mname" name="mname"
                        class="@error('mname') is-invalid @enderror"
                        placeholder="e.g. D" maxlength="1"
                        value="{{ old('mname') }}" required>
                    @error('mname') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="field-row one">
                <div class="field-group">
                    <label for="lname">Last Name <span class="req">*</span></label>
                    <input type="text" id="lname" name="lname"
                        class="@error('lname') is-invalid @enderror"
                        placeholder="e.g. dela Cruz" minlength="2"
                        value="{{ old('lname') }}" required>
                    @error('lname') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>
            </div>
        </div>

        {{-- Contact Info --}}
        <div class="form-section">
            <div class="form-section-label">
                <span class="sl-icon"><i class="fas fa-address-card"></i></span>
                Contact & Enrollment
            </div>

            <div class="field-row" style="margin-bottom:1rem;">
                <div class="field-group">
                    <label for="email">Email <span class="req">*</span></label>
                    <input type="email" id="email" name="email"
                        class="@error('email') is-invalid @enderror"
                        placeholder="e.g. juan@school.edu"
                        value="{{ old('email') }}" required>
                    @error('email') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>
                <div class="field-group">
                    <label for="contact_no">Contact Number <span class="req">*</span></label>
                    <input type="text" id="contact_no" name="contact_no"
                        class="@error('contact_no') is-invalid @enderror"
                        placeholder="09XX-XXX-XXXX (11 digits)"
                        maxlength="11" inputmode="numeric"
                        value="{{ old('contact_no') }}" required>
                    @error('contact_no') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="field-row one">
                <div class="field-group">
                    <label for="degree_id">Degree Program <span class="req">*</span></label>
                    <select id="degree_id" name="degree_id"
                        class="@error('degree_id') is-invalid @enderror" required>
                        <option value="">-- Select Degree --</option>
                        @foreach($degrees as $degree)
                            <option value="{{ $degree->id }}" @if(old('degree_id') == $degree->id) selected @endif>
                                {{ $degree->title }}
                            </option>
                        @endforeach
                    </select>
                    @error('degree_id') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>
            </div>
        </div>

        {{-- Account Credentials --}}
        <div class="form-section">
            <div class="form-section-label">
                <span class="sl-icon"><i class="fas fa-key"></i></span>
                Account Credentials
            </div>

            <div class="field-row">
                <div class="field-group">
                    <label for="username">Username <span class="req">*</span></label>
                    <input type="text" id="username" name="username"
                        class="@error('username') is-invalid @enderror"
                        placeholder="min. 3 characters" minlength="3"
                        value="{{ old('username') }}" required>
                    @error('username') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>
                <div class="field-group">
                    <label for="password">Password <span class="req">*</span></label>
                    <input type="password" id="password" name="password"
                        class="@error('password') is-invalid @enderror"
                        placeholder="min. 6 characters" minlength="6" required>
                    @error('password') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>
            </div>
        </div>

        {{-- Footer --}}
        <div class="form-footer">
            <button type="submit" class="btn-submit" id="submitBtn">
                <i class="fas fa-user-plus"></i> Create Student
            </button>
            <a href="{{ route('students.index') }}" data-ajax-link="true" data-target="#ajaxPageContent" class="btn-cancel">
                Cancel
            </a>
        </div>

    </form>
</div>
@endsection
