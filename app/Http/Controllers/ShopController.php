<?php

namespace App\Http\Controllers;

use App\Http\WebTransformers\ShopTransformer;
use App\Shop;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        if ($request->has('market_type')) {

            $market_type = $request->market_type;
            $market = $market_type[0];
            $type = $market_type[1];
            $shop = Shop::where('market_id', 'like', $market)
                ->where('type_id', 'like', $type)
                ->orderBy('created_at', 'desc')
                ->paginate($request->size);

        } else {
            $shop = Shop::where('name', 'like', '%' . $request->search . '%')
                ->orWhere('manager', 'like', '%' . $request->search . '%')
                ->orWhere('number', 'like', '%' . $request->search . '%')
                ->orderBy('created_at', 'desc')
                ->paginate($request->size);
        }
        return $this->response()->paginator($shop, new ShopTransformer());
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
        $shop = Shop::create($request->all());
        if ($shop) {
            return response()->json(['code' => 1, 'data' => $shop]);
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
        $shop = Shop::find($id);
        return $this->response()->item($shop, new ShopTransformer());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

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
        $shop = Shop::where('id', $id)->update($request->only(['name', 'manager', 'number', 'market_id', 'type_id']));
        if ($shop) {
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
    }
}
