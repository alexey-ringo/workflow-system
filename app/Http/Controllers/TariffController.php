<?php

namespace App\Http\Controllers;

use App\Models\Tariff;
use Illuminate\Http\Request;
use App\Http\Resources\Tariff\TariffCollection;
use App\Http\Resources\Tariff\TariffResource;

class TariffController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return new TariffCollection(Tariff::all());        
    }

    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $tariff = Tariff::create([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'sku' => $request->input('sku'),
            'price' => $request->input('price'),
            'is_active' => $request->input('is_active'),
        ]);
        
        if($tariff) {
            return response()->json(['message' => 'Новый тариф ' . $tariff->name . ' успешно создан']);
        }
        else {
            return response()->json(['message' => 'Ошибка при создании нового тарифа']);
        }  
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Route  $route
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        $tariff = Tariff::find($id);
        if($tariff) {
            return new TariffResource($tariff);
        }
        else {
            return response()->json(['message' => 'Тариф с идентификатором id ' . $id . ' не найден!'], 422);
        }
    }

   

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Route  $route
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Route $route)
    {
        
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Route  $route
     * @return \Illuminate\Http\Response
     */
    public function destroy(Route $route)
    {
        
    }
}
