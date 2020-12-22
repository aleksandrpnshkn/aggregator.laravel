<?php

namespace App\Http\Controllers;

use App\DrivingSchool;
use App\Program;
use App\Rules\Money;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class ProgramController extends Controller
{
    public function index(string $drivingSchoolSlug)
    {
        $drivingSchool = DrivingSchool::whereSlug($drivingSchoolSlug)
            ->with(['programs.driving_categories'])
            ->firstOrFail();

        $this->authorize('edit driving school', $drivingSchool);

        return view('programs.index', [
            'drivingSchool' => json_encode($drivingSchool, JSON_UNESCAPED_UNICODE),
        ]);
    }

    public function createOrEdit(string $drivingSchoolSlug, Program $program = null)
    {
        $drivingSchool = DrivingSchool::whereSlug($drivingSchoolSlug)
            ->with(['learning_places.address', 'driving_categories'])
            ->firstOrFail();

        $this->authorize('edit driving school', $drivingSchool);

        $program = $program ? $program->load(['driving_categories', 'learning_places']) : null;

        return view('programs.create-or-edit', [
            'drivingSchool' => json_encode($drivingSchool, JSON_UNESCAPED_UNICODE),
            'program' => json_encode($program, JSON_UNESCAPED_UNICODE),
            'priceTypes' => json_encode(Program::getPriceTypes(), JSON_UNESCAPED_UNICODE),
        ]);
    }

    public function storeOrUpdate(Request $request, string $slug)
    {
        $validated = $request->validate([
            'id' => 'nullable|integer|exists:programs,id',
            'name' => 'required|string|max:255',
            'starts_at' => ['nullable', 'date', function ($attribute, $value, $fail) use ($request) {
                $ends_at = $request->get('ends_at');
                if ($ends_at && Carbon::make($ends_at)->lessThan(Carbon::make($value))) {
                    $fail('Дата начала не может быть меньше даты окончания.');
                }
            }],
            'ends_at' => 'nullable|date',
            'is_akpp' => 'boolean',
            'is_retraining' => 'boolean',
            'price_type' => ['nullable', 'required_with:price', Rule::in(array_keys(Program::getPriceTypes()))],
            'price' => ['nullable', 'numeric', 'max:999999.99', new Money()],
            'description' => 'string|nullable|max:64000',
            'driving_categories' => 'nullable|array',
            'driving_categories.*' => 'integer|exists:driving_categories,id',
            'learning_places' => 'nullable|array',
            'learning_places.*' => 'integer|exists:learning_places,id',
        ]);

        $drivingSchool = DrivingSchool::whereSlug($slug)->firstOrFail();
        $this->authorize('edit driving school', $drivingSchool);

        $program = Program::findOrNew($validated['id']);
        $program->name = $validated['name'];
        $program->is_akpp = $validated['is_akpp'];
        $program->is_retraining = $validated['is_retraining'];
        $program->price_type = $validated['price_type'];
        $program->price = $validated['price'];
        $program->description = $validated['description'];
        $program->starts_at = Carbon::make($validated['starts_at']);
        $program->ends_at = Carbon::make($validated['ends_at']);
        $program->driving_school()->associate($drivingSchool);

        DB::transaction(function() use ($program, $validated) {
            $program->saveOrFail();
            $program->driving_categories()->sync($validated['driving_categories']);
            $program->learning_places()->sync($validated['learning_places']);
        });

        return response($program->id, Response::HTTP_OK);
    }

    public function destroy(string $drivingSchoolSlug, Program $program)
    {
        $this->authorize('edit driving school', DrivingSchool::whereSlug($drivingSchoolSlug)->firstOrFail());
        $program->delete();
        return response('Ok', Response::HTTP_OK);
    }
}
