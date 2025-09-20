<?php

namespace App\Http\Controllers\Api;

use App\Exports\TableAExport;
use App\Http\Controllers\Api\Controller;
use App\Models\TableA;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\TableAImport;
use Illuminate\Support\Facades\Validator;


class TableAController extends Controller
{
    // GET all records
    public function index(Request $request)
    {
        $search   = $request->get('search', '');
        $perPage  = (int) $request->get('per_page', 10);

        $query = TableA::query();

        if (!empty($search)) {
            $query->where('kode_toko_baru', 'like', "%{$search}%");
        }

        $data = $query->paginate($perPage);

        return response()->json([
            'data' => $data->items(),
            'total' => $data->total(),
            'current_page' => $data->currentPage(),
            'per_page' => $data->perPage(),
        ]);
    }

    // GET single record
    public function show($id)
    {
        $data = TableA::find($id);

        if (!$data) {
            return response()->json([
                "code" => 404,
                "message" => "Data not found"
            ], 404);
        }

        return $this->responsePayload([
            "data" => $data
        ]);
    }

    /**
     * Create a new record
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode_toko_baru' => 'required|integer|unique:table_a,kode_toko_baru',
            'kode_toko_lama' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $tableA = TableA::create($request->only(['kode_toko_baru', 'kode_toko_lama']));

        return response()->json($tableA, 201);
    }

    /**
     * Update a record
     */
    public function update(Request $request, $id)
    {
        $tableA = TableA::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'kode_toko_baru' => 'required|integer|unique:table_a,kode_toko_baru,' . $id . ',kode_toko_baru',
            'kode_toko_lama' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $tableA->update($request->only(['kode_toko_baru', 'kode_toko_lama']));

        return response()->json($tableA);
    }

    /**
     * Delete a record
     */
    public function destroy($id)
    {
        $tableA = TableA::findOrFail($id);
        $tableA->delete();

        return response()->json(['message' => 'Deleted successfully']);
    }

    /**
     * Upload Excel and import data
     */
    public function uploadExcel(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $file = $request->file('file');

        // Example: Using raw PhpSpreadsheet instead of Laravel Excel
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file->getPathname());
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();

        // Skip header row (index 0)
        foreach ($rows as $index => $row) {
            if ($index === 0) continue;

            $kodeBaru = $row[0] ?? null;
            $kodeLama = $row[1] ?? null;

            if ($kodeBaru) {
                TableA::updateOrCreate(
                    ['kode_toko_baru' => $kodeBaru],
                    ['kode_toko_lama' => $kodeLama]
                );
            }
        }

        return response()->json(['message' => 'Excel uploaded successfully']);
    }

    public function exportExcel(Request $request)
    {
        $query = TableA::query();

        if ($request->search) {
            $query->where('kode_toko_baru', 'like', "%{$request->search}%");
        }

        $data = $query->get();
        return Excel::download(new TableAExport($data), 'table_a.xlsx');
    }

    public function exportPdf(Request $request)
    {
        $query = TableA::query();

        if ($request->search) {
            $query->where('kode_toko_baru', 'like', "%{$request->search}%")
                ->orWhere('kode_toko_lama', 'like', "%{$request->search}%");
        }

        $data = $query->get();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('exports.tableA', compact('data'));
        return $pdf->download('table_a.pdf');
    }
}
