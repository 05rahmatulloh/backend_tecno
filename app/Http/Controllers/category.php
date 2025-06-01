<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class category extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
return view('category');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
         $validator = Validator::make($request->all(), [
         'name' => 'required|unique:category,name',
         'description' => 'nullable|string',
         ]);

         if ($validator->fails()) {
         return response()->json([
         'errors' => $validator->errors(),
         ], 422);
         }

         $productId = DB::table('category')->insertGetId([
         'name' => $request->name,
         'description' => $request->description,
         'created_at' => now(),
         'updated_at' => now(),
         ]);
         return redirect()->back()->with('success', 'Data berhasil ditambahkan');
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
           $deleted = DB::table('category')->where('id', $id)->delete();

           if ($deleted) {
           return  redirect()->back()->with('success', 'Data berhasil dihapus');

           } else {
              return redirect()->back()->with('success', 'Data berhasil dihapus!');

           }
    }
}
