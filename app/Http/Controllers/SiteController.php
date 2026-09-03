<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use App\Upstox;

class SiteController extends Controller
{
    function home(){
        return view('welcome');
    }


    function exp(){
        $api = new Upstox();
        return json_decode($api->getExpiries());
    }
}
