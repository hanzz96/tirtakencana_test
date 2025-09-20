<?php

namespace App\Http\Controllers\Api;

use App\Exports\TableBExport;
use App\Http\Controllers\Api\Controller;
use App\Models\TableB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str;

class TableBController extends Controller
{
    public function index(Request $request)
    {
        $search   = $request->get('search', '');
        $perPage  = (int) $request->get('per_page', 10);

        $query = TableB::query();

        if (!empty($search)) {
            $query->where('kode_toko', 'like', "%{$search}%");
        }

        $data = $query->paginate($perPage);

        return response()->json([
            'data' => $data->items(),
            'total' => $data->total(),
            'current_page' => $data->currentPage(),
            'per_page' => $data->perPage(),
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode_toko' => 'required|integer|unique:table_b,kode_toko',
            'nominal_transaksi' => 'required|numeric',
        ]);

        if ($validator->fails()) return response()->json(['errors' => $validator->errors()], 422);

        $row = TableB::create($request->only(['kode_toko', 'nominal_transaksi']));
        return response()->json($row, 201);
    }

    public function show($id)
    {
        return response()->json(TableB::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $row = TableB::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'kode_toko' => 'required|integer|unique:table_b,kode_toko,' . $id . ',kode_toko',
            'nominal_transaksi' => 'required|numeric',
        ]);

        if ($validator->fails()) return response()->json(['errors' => $validator->errors()], 422);

        $row->update($request->only(['kode_toko', 'nominal_transaksi']));
        return response()->json($row);
    }

    public function destroy($id)
    {
        TableB::findOrFail($id)->delete();
        return response()->json(['message' => 'Deleted successfully']);
    }

    public function uploadExcel(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        if ($validator->fails()) return response()->json(['errors' => $validator->errors()], 422);

        $sheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($request->file('file')->getPathname())->getActiveSheet();
        foreach ($sheet->toArray() as $i => $row) {
            if ($i === 0) continue;
            $kode = $row[0] ?? null;
            $nominal = $row[1] ?? null;
            if ($kode) {
                TableB::updateOrCreate(['kode_toko' => $kode], ['nominal_transaksi' => $nominal]);
            }
        }

        return response()->json(['message' => 'Excel uploaded successfully']);
    }

    public function exportExcel(Request $request)
    {
        $search   = $request->get('search', '');
        $query = TableB::query();

        if (!empty($search)) {
            $query->where('kode_toko', 'like', "%{$search}%");
        }

        $data = $query->get();
        return Excel::download(new TableBExport($data), 'table_b.xlsx');
    }

    public function exportPdf(Request $request)
    {
        $search   = $request->get('search', '');
        $query = TableB::query();

        if ($request->search) {
            $query->where('kode_toko', 'like', "%{$search}%");
        }

        $data = $query->get();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('exports.tableB', compact('data'));
        return $pdf->download('table_b.pdf');
    }
}
