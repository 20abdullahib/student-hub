<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;


class HomeController extends Controller
{
    public function index()  {
        $generations = DB::table('generations')->where('publish', true)->get();
        $data = [];

        $departments = DB::table('departments')->get();
        $branches = DB::table('branches')->get();
        foreach ($generations->take(10) as $generation) {
            $data[] = $generation;
        }
        return view('website.pages.home', compact('data', 'departments', 'branches'));
    }
 
}
