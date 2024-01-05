<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Excel;
use App\Imports\ClientImport;
use Carbon\Carbon;


class ClientController extends Controller
{
    public function index()
    {
        $data = DB::table('clients')->orderBy('id', 'DESC')->get();
        return view('import_excel', compact('data'));
    }

    public function import(Request $request)
{
    $request->validate([
        'select_file' => 'required|mimes:csv,xls,xlsx',
    ]);

    $path = $request->file('select_file')->getRealPath();
    $data = Excel::toCollection(new ClientImport, $path);

    if ($data->count() > 0) {
        $insert_data = [];

        foreach ($data->first() as $row) {
            // Check if 'ClientName' is not null and 'date_end' is not null before adding to $insert_data
            if ($row['client_name'] !== null && $row['date_end'] !== null) {
                $dateStart = $this->formatDate($row['date_start']);
                $dateEnd = $this->formatDate($row['date_end']);
                $insert_data[] = [
                    'ClientName' => $row['client_name'],
                    'DateStart' => $dateStart,
                    'DateEnd' => $dateEnd,
                    'Status' => $row['status'],
                ];
            }
        }

        if (!empty($insert_data)) {
            DB::table('clients')->insert($insert_data);
            return back()->with('success', 'Excel Data Imported successfully');
        }
    }

    return back()->with('error', 'No valid data found in the Excel file.');
}

private function formatDate($date)
{
    // Use Carbon to parse and format the date
    return Carbon::createFromFormat('m/d/Y', $date)->format('Y-m-d');
}
}