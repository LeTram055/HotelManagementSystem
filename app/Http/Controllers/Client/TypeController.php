<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Types;
use Illuminate\Support\Facades\Storage;

class TypeController extends Controller
{
    public function index()
        {
            $types = Types::with(['images', 'facilities'])->get();
            $types->map(function ($type) {
                
                $type->images->map(function ($image) {
                    $image->image_url = url(Storage::url('uploads/' . $image->image_url));
                    return $image;
                });
                $type->available_rooms = $type->availableRooms();
                return $type;
            });

            return response()->json($types);
        }
        

    public function show($id)
        {
            $type = Types::with(['images', 'facilities'])->find($id);
            $type->images->map(function ($image) {
                $image->image_url = url(Storage::url('uploads/' . $image->image_url));
                return $image;
            });
            $type->available_rooms = $type->availableRooms();
            return response()->json($type);
        }
}