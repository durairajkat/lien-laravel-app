<?php

namespace App\Http\Controllers\Api\V1\Lien;

use App\Http\Controllers\Controller;
use App\Http\Resources\LienProjectResource;
use App\Models\MemberLienMap;
use App\Models\ProjectDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LienProjectsController extends Controller
{
    public function index(Request $request)
    {
        $lienUserId =  Auth::user()->lienUser->id;
        $membersAssociated = MemberLienMap::where('lien_id', $lienUserId)->pluck('user_id')->toArray();

        $baseQuery = ProjectDetail::query()
            ->whereIn('project_details.user_id', $membersAssociated);

        $overallTotal = (clone $baseQuery)->count();

        $query = $baseQuery
            ->with(['tasks', 'originalCustomer', 'project_contract', 'project_date', 'documents', 'project_type', 'customer_contract.getContacts'])
            ->join('states', 'states.id', '=', 'project_details.state_id')
            ->leftJoin('project_contracts', 'project_contracts.project_id', '=', 'project_details.id')
            ->select(
                'project_details.*',
                'states.name as state',
                'project_contracts.base_amount'
            );

        /* -------- SEARCH -------- */
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('project_name', 'like', "%{$request->search}%")
                    ->orWhere('states.name', 'like', "%{$request->search}%")
                    ->orWhere('city', 'like', "%{$request->search}%")
                    ->orWhere('zip', 'like', "%{$request->search}%")
                    ->orWhere('project_contracts.base_amount', 'like', "%{$request->search}%");
            });
        }

        /* -------- FILTER STATUS -------- */
        if ($request->status) {
            $status = $request->status === 'active' ? '1' : '0';
            $query->where('project_details.status', $status);
        }

        /* -------- SORT -------- */
        $sortBy = $request->sort_by ?? 'project_details.created_at';
        $sortDir = $request->sort_dir ?? 'desc';

        $query->orderBy($sortBy, $sortDir);

        /* -------- PAGINATION -------- */
        $projects = $query->paginate($request->per_page ?? 10);

        return response()->json([
            'success' => true,
            'data' => LienProjectResource::collection($projects),
            'overall_total' => $overallTotal,
        ]);
    }
}
