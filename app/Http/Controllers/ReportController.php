<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function index()
    {
        $reports = Report::with(["user", "event"])
            ->where("generated_by", Auth::id())
            ->orderByDesc("id")
            ->get();

        return view('reports.index', compact('reports'));
    }
}
