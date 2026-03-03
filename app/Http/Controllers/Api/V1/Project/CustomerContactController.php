<?php

namespace App\Http\Controllers\Api\V1\Project;

use App\Http\Controllers\Controller;
use App\Http\Resources\CustomerContactResource;
use App\Models\MapCompanyContact;
use Illuminate\Http\Request;

class CustomerContactController extends Controller
{
    public function customerContacts(Request $request)
    {
        $userId = auth()->id();
        $perPage = $request->get('per_page', 10);

        $customerContacts = MapCompanyContact::where('user_id', $userId)
            ->whereHas('contacts', function ($query) {
                $query->where('type', '0');
            })
            ->with(['company', 'contacts', 'state'])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        return CustomerContactResource::collection($customerContacts);
    }
}
