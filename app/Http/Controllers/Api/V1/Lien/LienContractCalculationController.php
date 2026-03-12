<?php

namespace App\Http\Controllers\Api\V1\Lien;

use App\Http\Controllers\Controller;
use App\Models\MemberLienMap;
use App\Models\ProjectDetail;
use Illuminate\Support\Facades\Auth;

class LienContractCalculationController extends Controller
{
    public function contractTotal()
    {
        $lienUserId = Auth::user()->lienUser->id;

        $membersAssociated = MemberLienMap::where('lien_id', $lienUserId)
            ->pluck('user_id')
            ->toArray();

        $baseQuery = ProjectDetail::query()
            ->whereIn('project_details.user_id', $membersAssociated)
            ->leftJoin('project_contracts', 'project_contracts.project_id', '=', 'project_details.id');

        // Total contracts (projects with contract)
        $totalContracts = (clone $baseQuery)
            ->whereNotNull('project_contracts.id')
            ->count('project_contracts.id');

        // Total contract amount
        $totalAmount = (clone $baseQuery)
            ->sum('project_contracts.base_amount');

        // Average contract amount
        $averageAmount = (clone $baseQuery)
            ->avg('project_contracts.base_amount');

        return response()->json([
            'success' => true,
            'data' => [
                'total_contracts' => $totalContracts,
                'total_amount' => round($totalAmount, 2),
                'average_amount' => round($averageAmount, 2),
            ]
        ]);
    }
}
