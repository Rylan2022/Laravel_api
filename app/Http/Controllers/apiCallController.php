<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class apiCallController extends Controller
{
   public function index() {
        $response = Http::get('https://pokeapi.co/api/v2/pokemon/ditto');
        $data = $response->json();
        return $data;
    }
}
