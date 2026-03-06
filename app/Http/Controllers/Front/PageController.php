<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Blog;

class PageController extends Controller
{
    /**
     * Display the home page
     */
    public function index() 
    { 
        // blog filter for landing
        $landingBlogs = Blog::with(['blogCategories:id,name,slug'])
            ->where('is_published', true)
            ->whereHas('blogCategories', function ($query) {
                $query->where(function ($q) {
                    $q->where('slug', 'landing-blog')
                        ->orWhereRaw('LOWER(name) = ?', ['Landing blog']);
                });
            })
            ->orderByDesc('published_at')
            ->orderByDesc('created_at')
            ->take(4)
            ->get();

        return view('front.index', compact('landingBlogs'));
    }

    /**
     * Display ABB products page
     */
    public function abb() 
    { 
        // newest blog filter
        $NewestBlogs = Blog::with(['blogCategories:id,name,slug'])
            ->where('is_published', true)
            ->whereHas('blogCategories', function ($query) {
                $query->where(function ($q) {
                    $q->where('slug', 'newest-blog')
                        ->orWhereRaw('LOWER(name) = ?', ['Newest blog']);
                });
            })
            ->orderByDesc('published_at')
            ->orderByDesc('created_at')
            ->take(4)
            ->get();

        return view('front.abb', compact('NewestBlogs'));
    }

    /**
     * Display Legrand products page
     */
    public function legrand() 
    { 
        // newest blog filter
        $NewestBlogs = Blog::with(['blogCategories:id,name,slug'])
            ->where('is_published', true)
            ->whereHas('blogCategories', function ($query) {
                $query->where(function ($q) {
                    $q->where('slug', 'newest-blog')
                        ->orWhereRaw('LOWER(name) = ?', ['Newest blog']);
                });
            })
            ->orderByDesc('published_at')
            ->orderByDesc('created_at')
            ->take(4)
            ->get();

        return view('front.legrand', compact('NewestBlogs'));
    }

    /**
     * Display CP Electronics products page
     */
    public function cpElectronics() 
    { 
        // newest blog filter
        $NewestBlogs = Blog::with(['blogCategories:id,name,slug'])
            ->where('is_published', true)
            ->whereHas('blogCategories', function ($query) {
                $query->where(function ($q) {
                    $q->where('slug', 'newest-blog')
                        ->orWhereRaw('LOWER(name) = ?', ['Newest blog']);
                });
            })
            ->orderByDesc('published_at')
            ->orderByDesc('created_at')
            ->take(4)
            ->get();

        return view('front.cpElectronics', compact('NewestBlogs'));
    }

    /**
     * Display Vantage products page
     */
    public function vantage() 
    { 
        // newest blog filter
        $NewestBlogs = Blog::with(['blogCategories:id,name,slug'])
            ->where('is_published', true)
            ->whereHas('blogCategories', function ($query) {
                $query->where(function ($q) {
                    $q->where('slug', 'newest-blog')
                        ->orWhereRaw('LOWER(name) = ?', ['Newest blog']);
                });
            })
            ->orderByDesc('published_at')
            ->orderByDesc('created_at')
            ->take(4)
            ->get();
        return view('front.vantage', compact('NewestBlogs'));
    }

    /**
     * Display lighting control solutions page
     */
    public function lightingControl() 
    { 
        // newest blog filter
        $lightingControlBlog = Blog::with(['blogCategories:id,name,slug'])
            ->where('is_published', true)
            ->whereHas('blogCategories', function ($query) {
                $query->where(function ($q) {
                    $q->where('slug', 'dieu-khien-chieu-sang-blog')
                        ->orWhereRaw('LOWER(name) = ?', ['Điều khiển chiếu sáng blog']);
                });
            })
            ->orderByDesc('published_at')
            ->orderByDesc('created_at')
            ->take(4)
            ->get();
        return view('front.lighting_control_solutions', compact('lightingControlBlog'));
    }
    /**
     * Display shading solutions page
     */
    public function shade() 
    { 
        // newest blog filter
        $AutoShadeBlogs = Blog::with(['blogCategories:id,name,slug'])
            ->where('is_published', true)
            ->whereHas('blogCategories', function ($query) {
                $query->where(function ($q) {
                    $q->where('slug', 'rem-tu-dong')
                        ->orWhereRaw('LOWER(name) = ?', ['Rèm tự động']);
                });
            })
            ->orderByDesc('published_at')
            ->orderByDesc('created_at')
            ->take(4)
            ->get();
        return view('front.automatic_blind_solutions', compact('AutoShadeBlogs'));
    }

    /**
     * Display HVAC control solutions page
     */
    public function hvacControl() 
    { 
        // blog filter
        $HvacControlBlog = Blog::with(['blogCategories:id,name,slug'])
            ->where('is_published', true)
            ->whereHas('blogCategories', function ($query) {
                $query->where(function ($q) {
                    $q->where('slug', 'dieu-khien-hvac')
                        ->orWhereRaw('LOWER(name) = ?', ['Điều khiển HVAC']);
                });
            })
            ->orderByDesc('published_at')
            ->orderByDesc('created_at')
            ->take(4)
            ->get();
        return view('front.hvac_control_solutions', compact('HvacControlBlog'));
    }

    /**
     * Display security solutions page
     */
    public function security() 
    { 
        // blog filter
        $SecurityBlog = Blog::with(['blogCategories:id,name,slug'])
            ->where('is_published', true)
            ->whereHas('blogCategories', function ($query) {
                $query->where(function ($q) {
                    $q->where('slug', 'an-ninh')
                        ->orWhereRaw('LOWER(name) = ?', ['An ninh']);
                });
            })
            ->orderByDesc('published_at')
            ->orderByDesc('created_at')
            ->take(4)
            ->get();
        return view('front.security_solutions', compact('SecurityBlog'));
    }

    /**
     * Display BMS page
     */
    public function bms()
    {
     $NewestBlogs = Blog::with(['blogCategories:id,name,slug'])
            ->where('is_published', true)
            ->whereHas('blogCategories', function ($query) {
                $query->where(function ($q) {
                    $q->where('slug', 'newest-blog')
                        ->orWhereRaw('LOWER(name) = ?', ['Newest blog']);
                });
            })
            ->orderByDesc('published_at')
            ->orderByDesc('created_at')
            ->take(4)
            ->get();

        return view('front.bms', compact('NewestBlogs'));
    }

   

    /**
     * Display Hotel Control page
     */
    public function hotelControl() 
    { 
        // newest blog filter
        $NewestBlogs = Blog::with(['blogCategories:id,name,slug'])
            ->where('is_published', true)
            ->whereHas('blogCategories', function ($query) {
                $query->where(function ($q) {
                    $q->where('slug', 'newest-blog')
                        ->orWhereRaw('LOWER(name) = ?', ['Newest blog']);
                });
            })
            ->orderByDesc('published_at')
            ->orderByDesc('created_at')
            ->take(4)
            ->get();

        return view('front.holtelcontrol', compact('NewestBlogs'));
    }
  
    /**
     * Display contact us page
     */
    public function contact() 
    { 
        return view('front.contact-us-light'); 
    }

    
    /**
     * Display shop page
     */
    public function shop() 
    { 
        return view('front.shop'); 
    }

    /**
     * Display product details page
     */
    public function productDetails() 
    { 
        return view('front.productDetails'); 
    }

    /**
     * Display blog page
     */
    public function blogs() 
    { 
        return view('front.blogs'); 
    }



        /**
     * Display the homev2 page
     */
    public function homev2() 
    { 
        // blog filter for landing
        $landingBlogs = Blog::with(['blogCategories:id,name,slug'])
            ->where('is_published', true)
            ->whereHas('blogCategories', function ($query) {
                $query->where(function ($q) {
                    $q->where('slug', 'landing-blog')
                        ->orWhereRaw('LOWER(name) = ?', ['Landing blog']);
                });
            })
            ->orderByDesc('published_at')
            ->orderByDesc('created_at')
            ->take(4)
            ->get();

        return view('front.homev2', compact('landingBlogs'));
    }
}



