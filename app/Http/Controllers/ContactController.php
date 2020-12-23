<?php

namespace App\Http\Controllers;

use App\Contact;
use App\Rules\Contact as ContactRule;
use App\DrivingSchool;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;

class ContactController extends Controller
{
    public function storeOrUpdate(Request $request, string $drivingSchoolSlug) : Response
    {
        $drivingSchool = DrivingSchool::whereSlug($drivingSchoolSlug)->firstOrFail();
        $this->authorize('edit driving school', $drivingSchool);

        $validated = $this->validate($request, [
            'id' => 'nullable|integer|exists:contacts,id',
            'contact_type' => ['required', 'string', Rule::in(array_keys(Contact::getTypes()))],
            'value' => ['required', 'string', 'min:3', new ContactRule()],
        ]);

        $contact = Contact::findOrNew($validated['id']);
        $contact->contact_type = $validated['contact_type'];
        $contact->value = $validated['value'];
        $contact->contactable()->associate($drivingSchool);
        $contact->saveOrFail();

        return response($contact->id);
    }

    public function destroy(string $drivingSchoolSlug, Contact $contact) : Response
    {
        $drivingSchool = DrivingSchool::whereSlug($drivingSchoolSlug)->firstOrFail();
        $this->authorize('edit driving school', $drivingSchool);
        $contact->delete();
        return response('Ok');
    }
}
