<?php

namespace App\Http\Controllers;

use App\Models\Tariff;
use Illuminate\Http\Request;
use App\Http\Requests\TariffCreateRequest;
use App\Http\Requests\TariffUpdateRequest;
use App\Http\Resources\Tariff\TariffCollection;
use App\Http\Resources\Tariff\TariffResource;
use Gate;

class TariffController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Gate::denies('index', Tariff::class)) {
            return response()->json(['message' => 'У Вас недостаточно прав на просмотр списка тарифов!'], 422);
        }
        
        return new TariffCollection(Tariff::all());        
    }

    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TariffCreateRequest $request)
    {
        if(Gate::denies('store', Tariff::class)) {
            return response()->json(['message' => 'У Вас недостаточно прав на создание нового тарифа!'], 422);
        }
        
        $tariff = Tariff::create($request->only(['title', 'description', 'sku', 'price', 'is_active']));
        
        if($tariff) {
            return response()->json(['message' => 'Новый тариф ' . $tariff->title . ' успешно создан']);
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
        if(Gate::denies('show', Tariff::class)) {
            return response()->json(['message' => 'У Вас недостаточно прав на просмотр данного тарифа!'], 422);
        }
        
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
     * @param  \App\Tariff  $tariff
     * @return \Illuminate\Http\Response
     */
    public function update(TariffUpdateRequest $request, Tariff $tariff)
    {
        if(Gate::denies('update', Tariff::class)) {
            return response()->json(['message' => 'У Вас недостаточно прав на редактирование данного тарифа!'], 422);
        }
        
        if($tariff->update($request->only(['description', 'is_active']))) {
            return response()->json(['message' => 'Изменения для маршрута успешно применены']);
        }
        else {
            return response()->json(['message' => 'Внутренняя ошибка сервера при сохранении изменений в тарифе!'], 500);
        }  
    }
}
