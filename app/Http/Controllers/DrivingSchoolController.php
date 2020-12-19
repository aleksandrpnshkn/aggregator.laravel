<?php

namespace App\Http\Controllers;

use App\Address;
use App\DrivingCategory;
use App\DrivingSchool;
use App\Helpers\Helper;
use App\Rules\Inn;
use Auth;
use Cache;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Str;
use Symfony\Component\HttpFoundation\Response;

class DrivingSchoolController extends Controller
{
    public function index()
    {
        return view('driving-schools.index');
    }

    public function create()
    {
        return view('driving-schools.create', [
            'drivingCategories' => json_encode(self::getAllDrivingCategories(), JSON_UNESCAPED_UNICODE),
            'schoolTypes' => json_encode(DrivingSchool::getTypes(), JSON_UNESCAPED_UNICODE),
        ]);
    }

    public function store(Request $request) : Response
    {
        $validated = $request->validate([
            'name' => 'nullable|string|min:3|max:100',
            'legal_name' => 'required|string|min:3|max:255',
            // Владельцем может быть не только ЮР.лицо, но и ИП
            'inn' => ['nullable', new Inn(), 'unique:driving_schools,inn'],
            'type' => ['nullable', Rule::in(array_keys(DrivingSchool::getTypes()))],
            'address' => 'required|array',
            'address.value' => 'required|string',
            'driving_categories' => 'nullable|array',
            'driving_categories.*' => 'integer|exists:driving_categories,id',
        ]);

        $drivingSchool = DrivingSchool::make();
        $drivingSchool->name = $validated['name'];
        $drivingSchool->legal_name = $validated['legal_name'];
        $drivingSchool->inn = $validated['inn'];
        $drivingSchool->type = $validated['type'];

        // Если такой слаг уже есть - прибавить число
        $name = $drivingSchool->name ? $drivingSchool->name : $drivingSchool->legal_name;
        $slug = Str::limit(Str::slug($name), 100, '');
        while (DrivingSchool::where('slug', $slug)->exists()) {
            $slug = Helper::incrementSlug($slug, 100);
        }
        $drivingSchool->slug = $slug;

        $drivingSchool->post_status = DrivingSchool::POST_STATUS_PUBLISH;
        $drivingSchool->address()->associate(Address::getExistedOrCreate($validated['address']));
        $drivingSchool->author()->associate(Auth::user());
        $drivingSchool->saveOrFail();

        $drivingSchool->driving_categories()->sync($validated['driving_categories']);

        return response($drivingSchool->slug, Response::HTTP_CREATED);
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

    public function edit(string $slug)
    {
        $drivingSchool = DrivingSchool::where('slug', $slug)
            ->with(['address', 'driving_categories'])
            ->firstOrFail();

        return view('driving-schools.edit', [
            'drivingSchool' => json_encode($drivingSchool, JSON_UNESCAPED_UNICODE),
            'drivingCategories' => json_encode(self::getAllDrivingCategories(), JSON_UNESCAPED_UNICODE),
            'schoolTypes' => json_encode(DrivingSchool::getTypes(), JSON_UNESCAPED_UNICODE),
        ]);
    }

    public function update(Request $request, string $slug)
    {
        $drivingSchool = DrivingSchool::where('slug', $slug)->firstOrFail();

        $this->authorize('edit driving school', $drivingSchool);

        $validated = $request->validate([
            'name' => 'nullable|string|min:3|max:100',
            'legal_name' => 'required|string|min:3|max:255',
            'inn' => ['nullable', new Inn(), Rule::unique('driving_schools')->ignore($drivingSchool)],
            'type' => ['nullable', Rule::in(array_keys(DrivingSchool::getTypes()))],
            'address' => 'required|array',
            'address.value' => 'required|string',
            'driving_categories' => 'nullable|array',
            'driving_categories.*' => 'integer|exists:driving_categories,id',
        ]);

        $drivingSchool->name = $validated['name'];
        $drivingSchool->legal_name = $validated['legal_name'];
        $drivingSchool->inn = $validated['inn'];
        $drivingSchool->type = $validated['type'];
        $drivingSchool->post_status = DrivingSchool::POST_STATUS_PUBLISH;
        $drivingSchool->address()->associate(Address::getExistedOrCreate($validated['address']));
        $drivingSchool->saveOrFail();

        $drivingSchool->driving_categories()->sync($validated['driving_categories']);

        return response($drivingSchool->slug, Response::HTTP_OK);
    }

    public function destroy(string $slug)
    {
        $drivingSchool = DrivingSchool::whereSlug($slug)->firstOrFail();
        $drivingSchool->delete();
        return response('Ok', Response::HTTP_OK);
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

    public static function getAllDrivingCategories()
    {
        return Cache::remember('driving_categories', now()->addHour(), function () {
            return DrivingCategory::get(['id', 'name'])->toArray();
        });
    }
}
