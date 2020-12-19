@extends('layouts/default')

@section('content')
    <edit-driving-school :driving-school="{{ $drivingSchool }}"
                         :all-driving-categories="{{ $drivingCategories }}"
                         :school-types="{{ $schoolTypes }}"
    ></edit-driving-school>
@endsection
