<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Technician;

class TechnicalController extends Controller
{
    public function index(Request $request)
    {
        // Get the district filter from the request
        $district = $request->input('district');

        // Fetch technicians, filtering by district if provided
        $technicians = Technician::when($district, function ($query, $district) {
            return $query->where('district', $district);
        })->get();

        // Fetch all unique districts for the dropdown
        $districts = Technician::select('district')->distinct()->pluck('district');

        return view('technical.network', compact('technicians', 'districts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'district' => 'required|string|max:255',
            'town' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'contact_number' => 'required|string|max:255',
        ]);

        Technician::create([
            'district' => $request->district,
            'town' => $request->town,
            'name' => $request->name,
            'contact_number' => $request->contact_number,
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'Technician added successfully.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'district' => 'required|string|max:255',
            'town' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'contact_number' => 'required|string|max:255',
        ]);

        $technician = Technician::findOrFail($id);
        $technician->update([
            'district' => $request->district,
            'town' => $request->town,
            'name' => $request->name,
            'contact_number' => $request->contact_number,
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'Technician updated successfully.');
    }

    public function destroy($id)
    {
        $technician = Technician::findOrFail($id);
        $technician->delete();

        return redirect()->route('admin.dashboard')->with('success', 'Technician deleted successfully.');
    }
}