<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;

class HumanResourceController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function index(): Response
    {
        return Inertia::render('Hr/Index', []);
    }
}
