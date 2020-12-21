<?php

namespace App\Http\Controllers;

use App\Address;
use App\DrivingSchool;
use App\LearningPlace;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class LearningPlaceController extends Controller
{
    public function index(string $drivingSchoolSlug)
    {
        $drivingSchool = DrivingSchool::whereSlug($drivingSchoolSlug)->with(['learning_places.address'])->firstOrFail();

        $this->authorize('edit driving school', $drivingSchool);

        return view('learning-places.index', [
            'drivingSchool' => json_encode($drivingSchool, JSON_UNESCAPED_UNICODE),
        ]);
    }

    public function createOrEdit(string $drivingSchoolSlug, LearningPlace $learningPlace = null)
    {
        $drivingSchool = DrivingSchool::whereSlug($drivingSchoolSlug)->with(['learning_places'])->firstOrFail();
        $this->authorize('edit driving school', $drivingSchool);

        $learningPlace = $learningPlace ? $learningPlace->load(['address']) : null;

        return view('learning-places.create-or-edit', [
            'drivingSchool' => json_encode($drivingSchool, JSON_UNESCAPED_UNICODE),
            'learningPlace' => json_encode($learningPlace, JSON_UNESCAPED_UNICODE),
            'placeTypes' => json_encode(LearningPlace::getTypes(), JSON_UNESCAPED_UNICODE),
        ]);
    }

    public function storeOrUpdate(Request $request, string $drivingSchoolSlug)
    {
        $validated = $request->validate([
            'id' => 'nullable|integer|exists:learning_places,id',
            'type' => ['required', Rule::in(array_keys(LearningPlace::getTypes()))],
            'address' => 'required|array',
            'address.value' => 'required|string',
            'description' => 'string|nullable|max:64000',
        ]);

        $drivingSchool = DrivingSchool::whereSlug($drivingSchoolSlug)->firstOrFail();

        $this->authorize('edit driving school', $drivingSchool);

        $learningPlace = LearningPlace::findOrNew($validated['id']);
        $learningPlace->type = $validated['type'];
        $learningPlace->description = $validated['description'];
        $learningPlace->address()->associate(Address::getExistedOrCreate($validated['address']));
        $learningPlace->driving_school()->associate($drivingSchool);

        $learningPlace->saveOrFail();

        return response($learningPlace->id, Response::HTTP_OK);
    }

    public function destroy(string $drivingSchoolSlug, LearningPlace $learningPlace)
    {
        $this->authorize('edit driving school', DrivingSchool::whereSlug($drivingSchoolSlug)->firstOrFail());
        $learningPlace->delete();
        return response('Ok', Response::HTTP_OK);
    }
}
