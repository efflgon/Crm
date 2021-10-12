<?php

namespace App\Http\Controllers;

use App\Models\Integration;
use App\Traits\ApiResponser;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class IntegrationController extends Controller
{
    use ApiResponser;

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getAll(Request $request)
    {
        return $this->collection(Integration::all());
    }


}
