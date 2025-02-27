<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Generation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TeamMemberController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'branch_id'   => 'required|integer',
            'year_joined' => 'required|integer',
            'image'       => 'required|url',
            'patch'       => 'required|integer',
            'role'        => 'required|string',
        ]);

        $teamMember = Generation::create($validated);

        // return response()->json([
        //     'status'  => 'success',
        //     'message' => 'Team member added successfully.',
        //     'data'    => $teamMember,
        // ]);
        return redirect()->route('settings.index')->with('success', 'Team member added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Generation $teamMember)
    {
        $field = $request->input('field');
        $value = $request->input('value');

        // Only allow updates on these fields
        if (!in_array($field, ['name', 'branch_id', 'year_joined', 'patch', 'image', 'publish', 'role'])) {
            return response()->json(['status' => 'error', 'message' => 'Invalid field.'], 400);
        }

        // Optionally, perform validation for the specific field here

        $teamMember->$field = $value;
        $teamMember->save();

        return response()->json([
            'status'  => 'success',
            'message' => 'Team member updated successfully.',
            'data'    => $teamMember,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $teamMember = Generation::findOrFail($id);
        $teamMember->delete();

        return redirect()->route('settings.index')->with('success', 'Team member deleted successfully.');
    }
}
