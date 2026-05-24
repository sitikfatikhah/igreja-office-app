<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;

class AllowanceController extends Controller
{
    use SoftDeletes;

    protected $fillable = [
        'name',
    ];
}
