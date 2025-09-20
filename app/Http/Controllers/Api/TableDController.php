<?php

namespace App\Http\Controllers\Api;

use App\Exports\TableDExport;
use App\Models\TableD;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class TableDController extends Controller
{
    public function index(Request $request)
    {
        $search   = $request->get('search', '');
        $perPage  = (int) $request->get('per_page', 10);

        $query = TableD::query();

        if (!empty($search)) {
            $query->where('kode_sales', 'like', "%{$search}%")
                ->orWhere('nama_sales', 'like', "%{$search}%");
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
            'kode_sales' => 'required|string|max:255|unique:table_d,kode_sales',
            'nama_sales' => 'required|string|max:20',
        ]);

        if ($validator->fails()) return response()->json(['errors' => $validator->errors()], 422);

        $row = TableD::create($request->only(['kode_sales', 'nama_sales']));
        return response()->json($row, 201);
    }

    public function show($id)
    {
        return response()->json(TableD::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $row = TableD::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'kode_sales' => 'required|string|max:255|unique:table_d,kode_sales,' . $id . ',kode_sales',
            'nama_sales' => 'required|string|max:20',
        ]);

        if ($validator->fails()) return response()->json(['errors' => $validator->errors()], 422);

        $row->update($request->only(['kode_sales', 'nama_sales']));
        return response()->json($row);
    }

    public function destroy($id)
    {
        TableD::findOrFail($id)->delete();
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
            $nama = $row[1] ?? null;
            if ($kode) {
                TableD::updateOrCreate(['kode_sales' => $kode], ['nama_sales' => $nama]);
            }
        }

        return response()->json(['message' => 'Excel uploaded successfully']);
    }

    public function exportExcel(Request $request)
    {
        $search   = $request->get('search', '');
        $query = TableD::query();


        if (!empty($search)) {
            $query->where('kode_sales', 'like', "%{$search}%")
                ->orWhere('nama_sales', 'like', "%{$search}%");
        }

        $data = $query->get();
        return Excel::download(new TableDExport($data), 'table_d.xlsx');
    }

    public function exportPdf(Request $request)
    {
        $search   = $request->get('search', '');
        $query = TableD::query();

        if (!empty($search)) {
            $query->where('kode_sales', 'like', "%{$search}%")
                ->orWhere('nama_sales', 'like', "%{$search}%");
        }

        $data = $query->get();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('exports.tableD', compact('data'));
        return $pdf->download('table_d.pdf');
    }
}
