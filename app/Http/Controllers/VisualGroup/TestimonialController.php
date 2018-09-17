<?php

namespace App\Http\Controllers\VisualGroup;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class TestimonialController extends Controller
{
    public function testimonials(){
        return DB::table('reviews')->get();
    }

}