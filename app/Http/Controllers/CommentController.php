<?php

namespace App\Http\Controllers;

use App\Evaluate;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $evaluates = Evaluate::all();
        $evaluates = $evaluates->map(function ($item) {
            $user = $item->user;
            $market = empty($item->market_id) ? '' : $item->market->name;
            $shop = empty($item->shop_id) ? '' : $item->shop->name;
            return [
                'id' => $item->id,
                'nickname' => $user->nickname,
                'avatarUrl' => $user->avatarUrl,
                'created_at' => date('Y-m-d H:i:s', strtotime($item->created_at)),
                'field1' => $item->field1,
                'field2' => $item->field2,
                'field3' => $item->field3,
                'field4' => $item->field4,
                'feel' => $item->feel ?: '',
                'others' => $item->others ?: '',
                'market' => $market,
                'shop' => $shop,
            ];
        });
        return response()->json(paginate($request, $evaluates->toArray(), $request->size));
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
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
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
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $bo = Evaluate::destroy((int)$id);
        if ($bo) {
            return response()->json(['code' => 1]);
        } else {
            return response()->json(['code' => 0]);
        }
    }
}
