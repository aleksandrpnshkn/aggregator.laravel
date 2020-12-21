@extends('layouts/default')

@section('content')
    <edit-learning-place :driving-school="{{ $drivingSchool }}"
                         :learning-place="{{ $learningPlace }}"
                         :place-types="{{ $placeTypes }}"
    ></edit-learning-place>
@endsection
