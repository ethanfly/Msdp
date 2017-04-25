<?php

namespace App\Http\Controllers;

use App\Http\WebTransformers\MarketTransformer;
use App\Market;
use Illuminate\Http\Request;

class MarketController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        if ($request->has('all') && $request->all == true) {
            $market = Market::all();
            return $this->response()->collection($market, new MarketTransformer());
        } else {
            $market = Market::where('name', 'like', '%' . $request->search . '%')
                ->orderBy('created_at', 'desc')
                ->paginate($request->size);
            return $this->response()->paginator($market, new MarketTransformer());
        }
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
        $form = $request->form;
        $market = Market::create($form);
        if ($market) {
            return response()->json(['code' => 1]);
        } else {
            return response()->json(['code' => 0]);
        }
//        Market::create();
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
        $market = Market::find($id);
        if ($market) {
            return response()->json(['code' => 1, 'data' => $market]);
        } else {
            return response()->json(['code' => 0]);
        }
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
        $market = Market::where('id', $id);
        $form = $request->form;
        $update = $market->update($form);
        if ($update) {
            return response()->json(['code' => 1]);
        } else {
            return response()->json(['code' => 0]);
        }

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
        $bo = Market::destroy((int)$id);
        if ($bo) {
            return response()->json(['code' => 1]);
        } else {
            return response()->json(['code' => 0]);
        }
    }
}
