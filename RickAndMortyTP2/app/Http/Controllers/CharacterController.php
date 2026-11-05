<?php

namespace App\Http\Controllers;

//use Illuminate\Http\Request;
use App\Models\Character;

class CharacterController extends Controller
{
    // mostrar listado de recursos de la tabla characters
    public function index()
    {
        $characters = Character::all(); // Eloquent obtiene todos los personajes de la tabla characters y los guarda en la variable $characters
        // return response()->json($characters);
        return view('characters.index', compact('characters'));
    }
}
