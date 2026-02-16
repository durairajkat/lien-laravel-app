<?php

namespace App\Http\Controllers;

use App\Helpers\StringHelper;
use App\Imports\UsersImport;
use App\Models\Master\ContactRole;
use App\Models\State;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExcelUploadController extends Controller
{
    public function index()
    {
        return view('excel-upload');
    }

    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xls,xlsx,csv'
        ]);

        Excel::import(new UsersImport, $request->file('file'));

        return redirect()->back()
            ->with('success', 'Excel file uploaded and data inserted successfully!');
    }


    public function uploadCustomerContact(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xls,xlsx,csv'
        ]);

        $file = $request->file('file');

        $data = Excel::toArray([], $file);

        // Assuming first sheet
        $rows = $data[0];

        // First row = headers
        $headers = array_shift($rows);

        $formattedData = [];

        foreach ($rows as $row) {
            info($row);
            info($headers);
            info('----------');

            $tmp = array_combine($headers, $row);
            if (!$tmp['Company']) {
                continue;
            }
            $state = $tmp['state'];
            $role = trim($tmp['role'] ?? '');
            $state = State::where('name', trim($state))->first();
            if (!$state) {
                continue;
            }
            $role_name = StringHelper::normalizeString($role);
            $roleInfo = ContactRole::where('normalized_name', $role_name)->where('role_type', 'customer')->first();
            if (!$roleInfo) {
                $roleInfo = ContactRole::create(['role_type' => 'customer', 'name' => $role, 'normalized_name' => $role_name]);
            }
            $newData = [
                'id' => rand(),
                'company' => $tmp['Company'],
                'website' => $tmp['Website'] ?? '',
                'address' => $tmp['address'] ?? '',
                'city' => $tmp['city'] ?? '',
                'state_id' => $state->id,
                'zip' => $tmp['zip'] ?? '',
                'phone' => $tmp['phone'] ?? '',
                'fax' => $tmp['fax'] ?? '',
                'is_new' => true,
                'contacts' => [
                    [
                        'role_id' => $roleInfo->id,
                        'firstName' => $tmp['firstname'] ?? '',
                        'lastName' => $tmp['lastname'] ?? '',
                        'email' => $tmp['email'] ?? '',
                        'directPhone' => $tmp['phone'] ?? '',
                        'cell' => $tmp['cell'] ?? '',
                    ]
                ],
            ];
            $formattedData[] = $newData;
        }

        return response()->json([
            'status' => true,
            'data' => $formattedData
        ]);
    }
}
