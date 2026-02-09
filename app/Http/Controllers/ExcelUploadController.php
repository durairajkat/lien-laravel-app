<?php

namespace App\Http\Controllers;

use App\Imports\UsersImport;
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
}
