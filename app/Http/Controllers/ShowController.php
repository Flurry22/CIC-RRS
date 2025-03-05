<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ShowController extends Controller
{
    public function show()
    {
        // You might want to fetch data here to pass to the view
        return view('addnewproject');
    }
}
