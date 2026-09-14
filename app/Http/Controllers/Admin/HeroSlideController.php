<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HeroSlide;
use Illuminate\Http\Request;

class HeroSlideController extends Controller
{
    public function index()
    {
        return view('admin.hero-slides.index', [
            'slides' => HeroSlide::ordered()->paginate(20),
        ]);
    }

    public function create()
    {
        return view('admin.hero-slides.form', ['slide' => new HeroSlide()]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
            'image' => 'required|image|max:2048',
        ]);

        $data['is_active'] = $request->boolean('is_active');
        $data['image'] = $request->file('image')->store('slides', 'public');

        HeroSlide::create($data);

        return redirect()->route('admin.hero-slides.index')->with('success', 'Slide créé.');
    }

    public function edit(HeroSlide $heroSlide)
    {
        return view('admin.hero-slides.form', ['slide' => $heroSlide]);
    }

    public function update(Request $request, HeroSlide $heroSlide)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
            'image' => 'nullable|image|max:2048',
        ]);

        $data['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('slides', 'public');
        }

        $heroSlide->update($data);

        return redirect()->route('admin.hero-slides.index')->with('success', 'Slide mis à jour.');
    }

    public function destroy(HeroSlide $heroSlide)
    {
        $heroSlide->delete();
        return redirect()->route('admin.hero-slides.index')->with('success', 'Slide supprimé.');
    }
}
