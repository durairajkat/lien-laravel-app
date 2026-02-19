<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Subuser\StoreSubuserRequest;
use App\Http\Requests\Subuser\UpdateSubUserRequest;
use App\Http\Resources\master\CountyResource;
use App\Http\Resources\SubUserResource;
use App\Http\Resources\SubUserInfoResource;
use App\Models\Company;
use App\Services\Profile\UserDetailService;
use App\User as SubUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubUserController extends Controller
{
    public function __construct(
        protected UserDetailService $userDetailsService
    ) {}
    /**
     * -----------------------------
     * Server-side Datatable API
     * -----------------------------
     * GET /api/sub-users/datatable
     */
    public function datatable(Request $request)
    {
        $request->validate([
            'search'    => 'nullable|string|max:100',
            'page'      => 'nullable|integer|min:1',
            'per_page'  => 'nullable|integer|min:1|max:100',
            'sort_by'   => 'nullable|string',
            'sort_dir'  => 'nullable|in:asc,desc',
        ]);

        $user = Auth::user();

        $query = SubUser::with(['details.getCompany'])->where('parent_id', $user->id);

        /* -------------------------
         | Search
         --------------------------*/
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")

                    ->orWhereHas('details', function ($q2) use ($search) {
                        $q2->where('phone', 'like', "%{$search}%")
                            ->orWhere('zip', 'like', "%{$search}%")
                            ->orWhere('city', 'like', "%{$search}%")
                            ->orWhere('country', 'like', "%{$search}%");
                    })
                    ->orWhereHas('details.state', function($q4) use ($search) {
                        $q4->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('details.getCompany', function ($cq) use ($search) {
                        $cq->where('company', 'like', "%{$search}%")
                            ->orWhere('website', 'like', "%{$search}%");
                    });
            });
        }

        $perPage = (int) $request->get('per_page', 10);
        $subUsers = $query->paginate($perPage);

        return response()->json([
            'data' => SubUserResource::collection($subUsers),
            'meta' => [
                'current_page' => $subUsers->currentPage(),
                'per_page'     => $subUsers->perPage(),
                'total'        => $subUsers->total(),
                'last_page'    => $subUsers->lastPage(),
            ],
        ]);
    }


    public function getAllSubUser()
    {
        return CountyResource::collection(SubUser::select('id', 'name')->where('parent_id', auth()->id())->get());
    }

    /**
     * -----------------------------
     * Create Sub User
     * -----------------------------
     * POST /api/sub-users
     */
    public function store(StoreSubuserRequest $request)
    {
        $data = $request->validated();

        $data['name'] = trim($data['first_name'] . ' ' . $data['last_name']);
        $data['user_name'] = trim($data['first_name'] . ' ' . $data['last_name']).'_'.Auth::id();
        $data['created_by'] = Auth::id();
        $data['role'] = '6';
        $data['status'] = '0';
        $data['parent_id'] = Auth::id();
        $subUser = SubUser::create($data);
        if (isset($data['company_id'])) 
        {
            $companyInfo = Company::findOrFail($data['company_id']);
        }

        $this->userDetailsService->updateUserDetails($companyInfo ?? null, $subUser->id, $data);

        return response()->json([
            'message' => 'Sub user created successfully',
            'data'    => $subUser,
            'status' => true,
        ], 201);
    }

    /**
     * -----------------------------
     * Update Sub User
     * -----------------------------
     * PUT /api/sub-users/{subUser}
     */
    public function update(UpdateSubUserRequest $request, SubUser $subUser)
    {
        $data = $request->validated();
        $subUser->name = trim($data['first_name'] . ' ' . $data['last_name']);
        $subUser->user_name = $data['user_name'];
        $subUser->save();
        $companyInfo = Company::findOrFail($data['company_id']);

        $this->userDetailsService->updateUserDetails($companyInfo, $subUser->id, $data);

        return response()->json([
            'message' => 'Sub user updated successfully',
            'data'    => $subUser,
        ]);
    }
    /**
     * -----------------------------
     * Delete Sub User
     * -----------------------------
     * DELETE /api/sub-users/{subUser}
     */
    public function destroy(SubUser $subUser)
    {

        $subUser->delete();

        return response()->json([
            'message' => 'Sub user deleted successfully',
        ]);
    }

    public function view(SubUser $subUser)
    {
        $this->authorize('view', $subUser);
        $subUser->load([
            'details',
            'details.state',
            'details.getCompany'
        ]);
        return new SubUserResource($subUser);
    }
}
