<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller {
    
    public function __invoke(Request $request) {
        if ($request->user()->isAdmin()) {
            return redirect()->route('users.index');
        }

        if ($request->user()->isProjectManager()) {
            return view('reports.latest');
        }

        return redirect()->route('reports.create');
    }
}
