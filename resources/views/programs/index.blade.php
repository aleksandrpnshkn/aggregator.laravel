@extends('layouts/default')

@section('content')
    <programs-table :driving-school="{{ $drivingSchool }}"></programs-table>
@endsection
