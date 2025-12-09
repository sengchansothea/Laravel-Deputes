<?php

namespace App\Http\Controllers;

use App\Models\CpesLocCD;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!allowAccessFromHeadOffice()) {
            abort(403, 'You do not have permission to access this page.');
        }
        $data['pagetitle'] = "Mapping CPES With LACMS";
        $data['query_cpes'] = $this->queryCPES();

        $data['total'] = $data['query_cpes']->total();
        //        $data['page_title'] = __('g1.officer_list');
        $view = "cpes.list_cpes";

        if (request("json_opt") == 1) { //if request from app
            return response()->json(['status' => 200, 'result' => $data]);
        }
        return view($view, ["adata" => $data]);
    }

    function queryCPES()
    {
        //        $cpes = $users->orderBy('id', 'ASC')->paginate(10);
        $cpes = CpesLocCD::query();
        //        $users->appends([
        //        ]);
        return $cpes;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    
}
