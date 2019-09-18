<?php

namespace App\Http\Controllers;

use App\Models\Process;
use Illuminate\Http\Request;
use App\Http\Resources\Process\ProcessCollection;
use App\Http\Resources\Process\ProcessResource;

class ProcessController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return new ProcessCollection(Process::with('route')->get());
    }
    

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        /*
        $validator = $request->validate([
            'name' => 'required|string|max:255|unique:processes',
            'sequence' => 'required|unique:processes',
            'is_super' => 'required',
            'is_final' => 'required',
        ]);
        */
        
        $process = Process::create([
            'route_id' => $request->input('route_id'),
            'name' => $request->input('name'),
            'slug' => $request->input('slug'),
            'sequence' => $request->input('sequence'),
            'is_super' => $request->input('is_super'),
            'is_final' => $request->input('is_final'),
        ]);
        
        return new ProcessResource($process);   
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Process  $process
     * @return \Illuminate\Http\Response
     */
    public function show(/*Process $process*/ int $id)
    {
        return new ProcessResource(Process::FindOrFail($id));
        //return new ProcessResource($process);
    }
    

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Process  $process
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Process $process)
    {
        /*
        $validator = $request->validate([
            'name' => 'required|string|max:255',
            'sequence' => 'required',
            'is_super' => 'required',
            'is_final' => 'required',
        ]);
        */
        $process->name = $request->input('route_id');
        $process->name = $request->input('name');
        $process->sequence = $request->input('sequence');
        $process->is_super = $request->input('is_super');
        $process->is_final = $request->input('is_final');
        $process->save();
        
        //$process->update($request->all());
        
        return new ProcessResource($process);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Process  $process
     * @return \Illuminate\Http\Response
     */
    public function destroy(Process $process)
    {
        $process->groups()->detach();
        $process->delete();
        
        return new ProcessCollection(Process::all());
        //return response()->json('successfully deleted');
    }
}
