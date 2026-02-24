<?php

namespace App\Http\Controllers\Api\V1\Project;

use App\Http\Controllers\Controller;
use App\Http\Resources\AllTypeContactResource;
use App\Models\Company;
use App\Models\CompanyContact;
use App\Models\MapCompanyContact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProjectContactController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        $data = MapCompanyContact::where('user_id', $userId)
            ->whereHas('getContacts')
            ->with(['company', 'contacts'])
            ->get();

        // Group by contact type
        $grouped = $data->groupBy(function ($item) {
            return $item->contacts?->type;
        });

        return response()->json([
            'customerContact' => AllTypeContactResource::collection($grouped->get(0, collect())),
            'projectContact'  => AllTypeContactResource::collection($grouped->get(1, collect())),
        ]);
    }

    public function saveCustomerContact(Request $request)
    {
        DB::transaction(function () use ($request) {
            $userId = auth()->id();
            $companyId = $request->companyId;
            if (!$companyId) {
                $companyIno = Company::create([
                    'user_id' => $userId,
                    'company' => $request->company,
                    'address' => $request->address ?? null,
                    'city' => $request->city ?? null,
                    'state_id' => $request->state_id ?? null,
                    'zip' => $request->zip ?? null,
                    'phone' => $request->phone ?? null,
                    'fax' => $request->fax ?? null,
                    'website' => $request->website ?? null,
                    'contact_type' => 'customer',
                ]);
            } else {

                $companyIno = Company::find($companyId);
            }

            $contacts = $request->contacts;
            if (!empty($contacts) && is_array($contacts)) {
                foreach ($contacts as $cont) {
                    $cons_ins = [
                        'user_id' => $userId,
                        'contact_role_id' => $request->role_id ?? null,
                        'type' => "0",
                        'role_id' => $cont['role_id'] ?? null,
                        'first_name' => $cont['firstName'] ?? null,
                        'last_name' => $cont['lastName'] ?? null,
                        'email' => $cont['email'] ?? null,
                        'phone' => $cont['directPhone'] ?? null,
                        'cell' => $cont['cell'] ?? null,
                    ];

                    $companyContact = CompanyContact::Create($cons_ins);
                    $map_ins = [
                        'company_id' => $companyIno->id,
                        'company_contact_id' => $companyContact->id,
                        'user_id' => $userId,
                        'is_user' => 1,
                        'address' => $request->address ?? null,
                        'city' => $request->city ?? null,
                        'state_id' => $request->state_id ?? null,
                        'zip' => $request->zip ?? null,
                        'phone' => $request->phone ?? null,
                        'fax' => $request->fax ?? null,
                        'website' => $request->website ?? null,
                    ];
                    MapCompanyContact::create($map_ins);
                }
            }
        });

        return response()->json([
            'status' => true,
            'message' => 'Project Customer Contact added successfully',
        ], 200);
    }

    public function saveProjectContact(Request $request)
    {
        DB::transaction(function () use ($request) {
            $userId = auth()->id();
            $companyId = $request->companyId;
            if (!$companyId) {
                $companyIno = Company::create([
                    'user_id' => $userId,
                    'company' => $request->company,
                    'address' => $request->address ?? null,
                    'city' => $request->city ?? null,
                    'state_id' => $request->state_id ?? null,
                    'zip' => $request->zip ?? null,
                    'phone' => $request->phone ?? null,
                    'fax' => $request->fax ?? null,
                    'website' => $request->website ?? null,
                    'contact_type' => 'customer',
                ]);
            } else {

                $companyIno = Company::find($companyId);
            }

            $contacts = $request->contacts;
            if (!empty($contacts) && is_array($contacts)) {
                foreach ($contacts as $cont) {
                    $cons_ins = [
                        'user_id' => $userId,
                        'contact_role_id' => $request->role_id ?? null,
                        'type' => "1",
                        'role_id' => $cont['role_id'] ?? null,
                        'first_name' => $cont['firstName'] ?? null,
                        'last_name' => $cont['lastName'] ?? null,
                        'email' => $cont['email'] ?? null,
                        'phone' => $cont['directPhone'] ?? null,
                        'cell' => $cont['cell'] ?? null,
                    ];

                    $companyContact = CompanyContact::Create($cons_ins);
                    $map_ins = [
                        'company_id' => $companyIno->id,
                        'company_contact_id' => $companyContact->id,
                        'user_id' => $userId,
                        'is_user' => 1,
                        'address' => $request->address ?? null,
                        'city' => $request->city ?? null,
                        'state_id' => $request->state_id ?? null,
                        'zip' => $request->zip ?? null,
                        'phone' => $request->phone ?? null,
                        'fax' => $request->fax ?? null,
                        'website' => $request->website ?? null,
                    ];
                    MapCompanyContact::create($map_ins);
                }
            }
        });

        return response()->json([
            'status' => true,
            'message' => 'Project Contact added successfully',
        ], 200);
    }
}
