<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use App\Upstox;

class SiteController extends Controller
{
    //
    function ins(){
        $api = new Upstox();
        return json_decode($api->getInstrument());
    }


    function opt(){
        $api = new Upstox();
        return $api->getOptionChian();
    }


    function exp(){
        $api = new Upstox();
        return json_decode($api->getExpiries());
    }
}
