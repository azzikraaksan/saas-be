<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

Route::get('/test-upload', function () {
    return view('upload');
});

Route::post('/test-upload', function (Request $request) {
    if ($request->hasFile('file') && $request->file('file')->isValid()) {
        $uploaded = Cloudinary::upload(
            $request->file('file')->getRealPath(),
            ['folder' => 'test']
        )->getSecurePath();

        return "Uploaded: <a href='$uploaded'>$uploaded</a>";
    }

    return 'Upload failed 😢';
});

// Route::get('/cek-cloudinary', function () {
//     dd(config('cloudinary'));
// });

Route::get('/cek-cloudinary', function () {
    dd(config('services.cloudinary'));
});
