@extends('layouts/default')

@section('content')
    <create-driving-school :all-driving-categories="{{ $drivingCategories }}"
                           :school-types="{{ $schoolTypes }}"
    ></create-driving-school>
@endsection
