@extends('layouts/default')

@section('content')
    <learning-places-table :driving-school="{{ $drivingSchool }}"></learning-places-table>
@endsection
