<?php

namespace App\Http\Controllers;

use App\Models\CpesLocCd;
use Illuminate\Http\Request;

class CpesController extends Controller
{
    public function index()
    {
        $data['pagetitle'] = "Mapping CPES With LACMS";
        $data['query_cpes'] = $this->queryCPES();

        $data['total'] = $data['query_cpes']->total();
        $view = "cpes.list_cpes";

        if(request("json_opt") == 1){ //if request from app
            return response()->json(['status'=>200,'result'=> $data]);
        }
        return view($view, [ "adata" => $data ]);
    }

    function queryCPES()
    {
        $cpes = CpesLocCD::orderBy('LOC_SEQ', 'ASC')->paginate(10);

//        $users->appends([
//        ]);
        return $cpes;

    }

    public function update(Request $request, $id)
    {
        $record = CpesLocCd::findOrFail($id);

        // Update all except primary key
        $record->update($request->except('LOC_SEQ'));

        return redirect()->back()->with('success', 'Record updated successfully!');
    }
}
