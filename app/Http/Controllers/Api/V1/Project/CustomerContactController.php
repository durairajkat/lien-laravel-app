<?php

namespace App\Http\Controllers\Api\V1\Project;

use App\Http\Controllers\Controller;
use App\Http\Resources\CustomerContactResource;
use App\Models\CompanyContact;
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

    public function deleteCustomerContact(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
        ]);

        $id = $request->input('id');

        $customerContact = MapCompanyContact::find($id);

        if (!$customerContact) {
            return response()->json(['message' => 'Customer contact not found.'], 404);
        }

        $contactId = $customerContact->company_contact_id;

        CompanyContact::where('id', $contactId)->delete();

        $customerContact->delete();

        return response()->json(['message' => 'Customer contact deleted successfully.']);
    }
}
