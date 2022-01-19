<?php

namespace App\Http\Controllers;

use App\Exports\AgentsExport;
use App\Imports\AgentsImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExportImportController extends Controller
{
    //
    public function currentDate()
    {
        return date('_Y_m_d');
    }
    public function agents()
    {
        $agents_dt = session('agents');
        $agents = $agents_dt->getData()->data;

        return Excel::download(new AgentsExport($agents), "agents{$this->currentDate()}.xlsx");
    }

    public function importAgents(Request $request)
    {
        if ($request->hasFile('file')) {

            Excel::import(new AgentsImport,$request->file('file'));
            return back();
        }
    }
}
