<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Materi;

class MateriController extends Controller
{
    public function index()
    {
        $materis = Materi::latest()->get();
        return view('siswa.materi', compact('materis'));
    }
}
