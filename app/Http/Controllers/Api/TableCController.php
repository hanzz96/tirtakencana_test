<?php

namespace App\Http\Controllers\Api;

use App\Exports\TableCExport;
use App\Models\TableC;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str;

class TableCController extends Controller
{
    public function index(Request $request)
    {
        $search   = $request->get('search', '');
        $perPage  = (int) $request->get('per_page', 10);

        $query = TableC::query();

        if (!empty($search)) {
            $query->where('kode_toko', 'like', "%{$search}%")
                ->orWhere('area_sales', 'like', "%{$search}%");
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
            'kode_toko' => 'required|integer|unique:table_c,kode_toko',
            'area_sales' => 'required|string|max:10',
        ]);

        if ($validator->fails()) return response()->json(['errors' => $validator->errors()], 422);

        $row = TableC::create($request->only(['kode_toko', 'area_sales']));
        return response()->json($row, 201);
    }

    public function show($id)
    {
        return response()->json(TableC::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $row = TableC::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'kode_toko' => 'required|integer|unique:table_c,kode_toko,' . $id . ',kode_toko',
            'area_sales' => 'required|string|max:10',
        ]);

        if ($validator->fails()) return response()->json(['errors' => $validator->errors()], 422);

        $row->update($request->only(['kode_toko', 'area_sales']));
        return response()->json($row);
    }

    public function destroy($id)
    {
        TableC::findOrFail($id)->delete();
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
            $area = $row[1] ?? null;
            if ($kode) {
                TableC::updateOrCreate(['kode_toko' => $kode], ['area_sales' => $area]);
            }
        }

        return response()->json(['message' => 'Excel uploaded successfully']);
    }

    public function exportExcel(Request $request)
    {
        $search   = $request->get('search', '');
        $query = TableC::query();


        if (!empty($search)) {
            $query->where('kode_toko', 'like', "%{$search}%")
                ->orWhere('area_sales', 'like', "%{$search}%");
        }

        $data = $query->get();
        return Excel::download(new TableCExport($data), 'table_c.xlsx');
    }

    public function exportPdf(Request $request)
    {
        $search   = $request->get('search', '');
        $query = TableC::query();

        if (!empty($search)) {
            $query->where('kode_toko', 'like', "%{$search}%")
                ->orWhere('area_sales', 'like', "%{$search}%");
        }

        $data = $query->get();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('exports.tableC', compact('data'));
        return $pdf->download('table_c.pdf');
    }
}
