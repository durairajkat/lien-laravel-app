<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function index()
    {
        $companies = Company::select('company', 'id', 'address', 'city', 'phone', 'fax', 'zip')
            ->distinct()
            ->whereNotNull('company')
            ->where('company', '!=', '')
            ->orderBy('company')
            ->get();

        return response()->json([
            'success'   => true,
            'data' => $companies
        ], 200);
    }
}
