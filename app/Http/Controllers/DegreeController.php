<?php

namespace App\Http\Controllers;

use App\Models\Degree;
use Illuminate\Http\Request;

class DegreeController extends Controller
{
    public function index(Request $request)
    {
        $search  = trim((string) $request->query('search', ''));
        $query   = Degree::withCount('students');

        if ($search !== '') {
            $query->where('title', 'like', "%{$search}%");
        }

        $degrees = $query->paginate(10)->appends($request->only('search'));

        // AJAX + table=1 → partial only (for loadTable() polling)
        if ($request->ajax() && $request->boolean('table')) {
            return view('degrees.partials.table', compact('degrees'));
        }

        return view('degrees.index', compact('degrees'));
    }

    public function create()
    {
        return view('degrees.create');
    }

    public function store(Request $request)
    {
        $validator = validator($request->all(), [
            'title' => 'required|string|max:255|unique:degrees,title',
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $degree = Degree::create(['title' => $request->title]);

        if ($request->expectsJson()) {
            return response()->json([
                'success'      => true,
                'message'      => 'Degree created successfully!',
                'degree'       => $degree,
                'redirect_url' => route('degrees.index'),
            ], 201);
        }

        return redirect()->route('degrees.index')->with('success', 'Degree created successfully!');
    }

    public function show(string $id)
    {
        $degree = Degree::with('students')->find($id);

        if (!$degree) {
            if (request()->expectsJson()) {
                return response()->json(['error' => 'Degree not found!'], 404);
            }
            return redirect()->route('degrees.index')->with('error', 'Degree not found!');
        }

        return view('degrees.show', compact('degree'));
    }

    public function edit(string $id)
    {
        $degree = Degree::find($id);

        if (!$degree) {
            return redirect()->route('degrees.index')->with('error', 'Degree not found!');
        }

        return view('degrees.edit', compact('degree'));
    }

    public function update(Request $request, string $id)
    {
        $degree = Degree::find($id);

        if (!$degree) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Degree not found!'], 404);
            }
            return redirect()->route('degrees.index')->with('error', 'Degree not found!');
        }

        $validator = validator($request->all(), [
            'title' => 'required|string|max:255|unique:degrees,title,' . $id,
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $degree->update(['title' => $request->title]);

        if ($request->expectsJson()) {
            return response()->json([
                'success'      => true,
                'message'      => 'Degree updated successfully!',
                'degree'       => $degree,
                'redirect_url' => route('degrees.index'),
            ]);
        }

        return redirect()->route('degrees.index')->with('success', 'Degree updated successfully!');
    }

    public function destroy(Request $request, string $id)
    {
        $degree = Degree::find($id);

        if (!$degree) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Degree not found!'], 404);
            }
            return redirect()->route('degrees.index')->with('error', 'Degree not found!');
        }

        $degree->delete();

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Degree deleted successfully!']);
        }

        return redirect()->route('degrees.index')->with('success', 'Degree deleted successfully!');
    }
}
