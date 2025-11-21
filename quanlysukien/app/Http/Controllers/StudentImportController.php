<?php
// app/Http/Controllers/StudentImportController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\StudentsImport;

class StudentImportController extends Controller
{
    public function show()
    {
        return view('students.import');
    }

    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:20480', // <= 20MB
        ], [
            'file.mimes' => 'Chỉ chấp nhận tập tin .xlsx, .xls, .csv',
        ]);

        $import = new StudentsImport;
        Excel::import($import, $request->file('file'));

        // Lấy thống kê
        $total   = $import->getTotal();
        $inserted = $import->getInserted();
        $updated = $import->getUpdated();
        $skipped = $import->getSkipped();
        $errors  = $import->getErrors();

        return redirect()
            ->route('students.index')
            ->with('status', "Import xong: Tổng {$total}, thêm {$inserted}, cập nhật {$updated}, bỏ qua {$skipped}.")
            ->with('import_errors', $errors);
    }
}
