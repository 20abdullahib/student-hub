<?php

namespace App\Http\Controllers\Website;

use App\Models\Branch;
use App\Models\Subject;
use App\Models\Generation;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Request;



class AboutController extends Controller
{
    public function index()
    {
        $years = Generation::select('year_joined')
            ->distinct()
            ->orderBy('year_joined', 'asc')
            ->get()
            ->pluck('year_joined');  // Get distinct years
        $generations = Generation::with('branch')->get();  // Fetch generations with branch relationship
        // dd($generations);
        $branches = Branch::get();

        return view('website.pages.about.about', compact('years', 'generations', 'branches'));
    }

    public function showGeneration($year)
    {
        $generations = Generation::where('year_joined', $year)->get();
        // dd($generation);

        return view('website.pages.about.genration.generation', compact('generations'));
    }

    public function getSuggestions(Request $request)
    {
        $query = $request->input('query');

        // Fetch subjects based on name or code
        $subjects = Subject::where('name', 'LIKE', "%{$query}%")
            ->orWhere('code', 'LIKE', "%{$query}%")
            ->get(['name', 'code']); // Return only necessary fields

        dd($subjects);
        return response()->json($subjects); // Return suggestions as JSON
    }
}
