<?php

namespace App\Http\Controllers;

use App\Type;
use Illuminate\Http\Request;

class TypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $type = Type::paginate($request->size);
        return response()->json($type);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $type = Type::create([
            'name' => $request->name,
            'market_id' => $request->market_id
        ]);
        if ($type) {
            return response()->json(['code' => 1, 'data' => $type]);
        } else {
            return response()->json(['code' => 0]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\ $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $bo = Type::destroy((int)$id);
        if ($bo) {
            return response()->json(['code' => 1]);
        } else {
            return response()->json(['code' => 0]);
        }
    }
}
