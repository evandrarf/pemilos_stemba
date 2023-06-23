<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CandidatesController extends Controller
{
    public function index()
    {
        return Inertia::render('admin/candidates/index', ['title' => 'Pemilos | Candidates']);
    }
}
