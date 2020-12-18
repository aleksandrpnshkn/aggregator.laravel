<?php

namespace App\Http\Controllers;

use App\DrivingSchool;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DrivingSchoolController extends Controller
{
    public function index()
    {
        return view('driving-schools.index');
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

    public function show(string $slug)
    {
        $drivingSchool = DrivingSchool::where('slug', $slug)
            ->with(['learning_places', 'address', 'driving_categories', 'programs'])
            ->firstOrFail();

        return view('driving-schools.show', [
            'drivingSchool' => $drivingSchool,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\DrivingSchool  $drivingSchool
     * @return \Illuminate\Http\Response
     */
    public function edit(DrivingSchool $drivingSchool)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\DrivingSchool  $drivingSchool
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DrivingSchool $drivingSchool)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\DrivingSchool  $drivingSchool
     * @return \Illuminate\Http\Response
     */
    public function destroy(DrivingSchool $drivingSchool)
    {
        //
    }

    public function filter(Request $request) : JsonResponse
    {
        $drivingSchools = DrivingSchool::query();

        $drivingSchools->whereHas('address', function (Builder $query) use ($request) {
            $region = $request->input('region');
            $city = $request->input('city');
            $district = $request->input('district');

            if ($region) {
                $query->where('region_with_type', $region);

                if ($city) {
                    $query->where('city_with_type', $city);

                    if ($district) {
                        $query->where('city_district_with_type', $district);
                    }
                }
            }
        });

        $drivingCategories = $request->input('driving-categories');

        if ($drivingCategories) {
            $drivingCategories = explode(',', $drivingCategories);

            $drivingSchools->whereHas('driving_categories', function (Builder $query) use ($drivingCategories) {
                $query->whereIn('driving_categories.id', $drivingCategories);
            });
        }

        if ($request->input('is-akpp') === 'true') {
            $drivingSchools->whereHas('programs', function (Builder $query) {
                $query->where('is_akpp', true);
            });
        }

        if ($request->input('is-retraining') === 'true') {
            $drivingSchools->whereHas('programs', function (Builder $query) {
                $query->where('is_retraining', true);
            });
        }

        if ($request->input('has-conclusions') === 'true') {
            $drivingSchools->whereHas('conclusions', function (Builder $query) {
                $query->whereHas('conclusion_results', function (Builder $query) {
                    $query->whereNull('ends_at')
                        ->orWhere('ends_at', '>', Carbon::now());
                });
            });
        }

        $drivingSchools->with(['address']);

        return response()->json($drivingSchools->paginate(6)->toArray());
    }
}
