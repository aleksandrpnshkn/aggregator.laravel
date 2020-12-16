<?php

namespace App\Http\Controllers;

use App\Address;
use Cache;
use DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AddressController extends Controller
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
     * @param  \App\Address  $address
     * @return \Illuminate\Http\Response
     */
    public function show(Address $address)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Address  $address
     * @return \Illuminate\Http\Response
     */
    public function edit(Address $address)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Address  $address
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Address $address)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Address  $address
     * @return \Illuminate\Http\Response
     */
    public function destroy(Address $address)
    {
        //
    }

    /**
     * Получить все регионы, в которых есть автошколы
     */
    public function getRegions() : JsonResponse
    {
        $regions = Cache::remember('filter_regions', now()->addHour(), function () {
            return DB::table('addresses')
                ->select('region_with_type')
                ->distinct()
                ->join('driving_schools', 'addresses.id', '=', 'address_id')
                ->whereNotNull('region_with_type')
                ->pluck('region_with_type')
                ->toArray();
        });

        return response()->json($regions);
    }

    public function getCities(string $region) : JsonResponse
    {
        $cacheKey = 'filter_' . $region;

        $cities = Cache::remember($cacheKey, now()->addHour(), function () use ($region) {
            return DB::table('addresses')
                ->select('city_with_type')
                ->distinct()
                ->join('driving_schools', 'addresses.id', '=', 'address_id')
                ->whereNotNull('city_with_type')
                ->where('region_with_type', '=', $region)
                ->pluck('city_with_type')
                ->toArray();
        });

        return response()->json($cities);
    }

    public function getDistricts(string $region, string $city) : JsonResponse
    {
        $cacheKey = 'filter_' . $region . '_' . $city;

        $districts = Cache::remember($cacheKey, now()->addHour(), function () use ($region, $city) {
            return DB::table('addresses')
                ->select('city_district_with_type')
                ->distinct()
                ->join('driving_schools', 'addresses.id', '=', 'address_id')
                ->whereNotNull('city_district_with_type')
                ->where('region_with_type', '=', $region)
                ->where('city_with_type', '=', $city)
                ->pluck('city_district_with_type')
                ->toArray();
        });

        return response()->json($districts);
    }
}
