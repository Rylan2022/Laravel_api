<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UploadController extends Controller
{
    public function viewUpload() {
        return view('FileUpload.fileupload');
    }


    public function fileUpload(Request $request) {

        $path = $request->file('file')->store('public');
        return $path;
    }
}
