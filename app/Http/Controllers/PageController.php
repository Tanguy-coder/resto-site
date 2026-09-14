<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\HeroSlide;
use App\Models\Location;
use App\Models\Product;
use App\Models\SiteSetting;
use App\Models\Step;
use App\Models\Testimonial;

class PageController extends Controller
{
    public function home()
    {
        return view('pages.home', [
            'slides' => HeroSlide::active()->ordered()->get(),
            'bestSellers' => Product::active()->bestSeller()->with('subCategory.category', 'variants')->take(6)->get(),
            'steps' => Step::active()->ordered()->get(),
            'testimonials' => Testimonial::active()->ordered()->get(),
            'locations' => Location::active()->ordered()->get(),
            'settings' => SiteSetting::all()->pluck('value', 'key'),
        ]);
    }

    public function menu()
    {
        return view('pages.menu', [
            'categories' => Category::active()->ordered()->with(['subCategories' => function ($q) {
                $q->active()->ordered()->with(['products' => function ($q) {
                    $q->active()->ordered()->with('variants');
                }]);
            }])->get(),
            'settings' => SiteSetting::all()->pluck('value', 'key'),
        ]);
    }

    public function checkout()
    {
        return view('pages.checkout', [
            'locations' => Location::active()->ordered()->get(),
            'settings' => SiteSetting::all()->pluck('value', 'key'),
        ]);
    }

    public function tracking()
    {
        return view('pages.tracking', [
            'locations' => Location::active()->ordered()->get(),
            'settings' => SiteSetting::all()->pluck('value', 'key'),
        ]);
    }

    public function about()
    {
        return view('pages.about', [
            'settings' => SiteSetting::all()->pluck('value', 'key'),
        ]);
    }

    public function contact()
    {
        return view('pages.contact', [
            'locations' => Location::active()->ordered()->get(),
            'settings' => SiteSetting::all()->pluck('value', 'key'),
        ]);
    }
}
