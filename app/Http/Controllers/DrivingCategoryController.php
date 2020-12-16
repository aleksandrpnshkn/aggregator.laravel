<?php

namespace App\Http\Controllers;

use App\DrivingCategory;
use Cache;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DrivingCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\DrivingCategory  $drivingCategory
     * @return \Illuminate\Http\Response
     */
    public function show(DrivingCategory $drivingCategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\DrivingCategory  $drivingCategory
     * @return \Illuminate\Http\Response
     */
    public function edit(DrivingCategory $drivingCategory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\DrivingCategory  $drivingCategory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DrivingCategory $drivingCategory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\DrivingCategory  $drivingCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy(DrivingCategory $drivingCategory)
    {
        //
    }

    public function getAll() : JsonResponse
    {
        $drivingCategories = Cache::remember('driving_categories', now()->addHour(), function () {
            return DrivingCategory::orderBy('id')->get(['id', 'name'])->toArray();
        });

        return response()->json($drivingCategories);
    }
}
