<?php

namespace App\Http\Controllers;

use App\Models\Skill;
use Illuminate\Http\Request;


class SkillController extends Controller
{
    public function store(Request $request)
{
    $request->validate([
        'name' => 'required'
    ]);

    Skill::create([
        'name' => $request->name
    ]);

    return redirect('/dashboard');
}
}
