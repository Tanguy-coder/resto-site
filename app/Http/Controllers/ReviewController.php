<?php

namespace App\Http\Controllers;

use App\Models\Testimonial;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:100',
            'content'  => 'required|string|max:1000',
            'rating'   => 'required|integer|min:1|max:5',
            'location' => 'nullable|string|max:100',
        ]);

        Testimonial::create([
            'name'      => $request->name,
            'content'   => $request->content,
            'rating'    => $request->rating,
            'location'  => $request->location,
            'is_active' => false,
            'sort_order' => 99,
        ]);

        return back()->with('review_sent', true);
    }
}
