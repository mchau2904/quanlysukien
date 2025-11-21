<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\AdminsImport;

class AdminImportController extends Controller
{
    public function show()
    {
        return view('admins.import');
    }

    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:20480',
        ], [
            'file.mimes' => 'Chỉ chấp nhận tập tin .xlsx, .xls, .csv',
        ]);

        $import = new AdminsImport;
        Excel::import($import, $request->file('file'));

        $total = $import->getTotal();
        $inserted = $import->getInserted();
        $updated = $import->getUpdated();
        $skipped = $import->getSkipped();
        $errors = $import->getErrors();

        return redirect()
            ->route('admins.index')
            ->with('status', "✅ Import xong: Tổng {$total}, thêm {$inserted}, cập nhật {$updated}, bỏ qua {$skipped}.")
            ->with('import_errors', $errors);
    }
}
