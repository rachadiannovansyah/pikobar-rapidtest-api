<?php

namespace App\Http\Controllers\Master;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Entities\Fasyankes;

class FasyankesListController extends Controller
{
    public function __invoke(Fasyankes $fasyankes)
    {
        return response()->json($fasyankes->items(), 200);
    }
}
