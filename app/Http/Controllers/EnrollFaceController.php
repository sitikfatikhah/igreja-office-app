<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class EnrollFaceController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'descriptor' => 'required|string',
            'reference_photo' => 'required|string',
        ]);

        $user = auth()->user();
        $user->face_descriptor = $validated['descriptor'];
        $user->reference_photo = $validated['reference_photo'];
        $user->save();

        return response()->json([
            'message' => 'Face enrollment berhasil disimpan.',
        ]);
    }
}
