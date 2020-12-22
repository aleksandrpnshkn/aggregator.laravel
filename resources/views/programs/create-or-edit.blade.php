@extends('layouts/default')

@section('content')
    <edit-program :driving-school="{{ $drivingSchool }}"
                  :program="{{ $program }}"
                  :price-types="{{ $priceTypes }}"
    ></edit-program>
@endsection
