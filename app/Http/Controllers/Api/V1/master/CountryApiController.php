<?php

namespace App\Http\Controllers\Api\V1\master;

use App\Http\Controllers\Controller;
use App\Http\Resources\StateResource;
use App\Models\Country;

class CountryApiController extends Controller
{
    public function getCountries()
    {

        return StateResource::collection(Country::select('id', 'name', 'code')->get());
    }
}
