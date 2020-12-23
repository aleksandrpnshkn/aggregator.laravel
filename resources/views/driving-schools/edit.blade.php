@extends('layouts/default')

@section('content')
    <edit-driving-school :driving-school="{{ $drivingSchool }}"
                         :all-driving-categories="{{ $drivingCategories }}"
                         :school-types="{{ $schoolTypes }}"
                         :contact-types="{{ $contactTypes }}"
    ></edit-driving-school>
@endsection
