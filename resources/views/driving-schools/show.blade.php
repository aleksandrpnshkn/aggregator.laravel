<?php
/**
 * @var \App\DrivingSchool $drivingSchool
 */
?>

@extends('layouts.default')

@section('content')
    <header class="driving-school__header mb-6">
        <div class="driving-school__logo">
            @if($drivingSchool->logo)
                <img src="{{ $drivingSchool->logo }}"
                     alt="Логотип {{ $drivingSchool->name ?? $drivingSchool->legal_name }}">
            @else
                <div class="driving-school__logo-placeholder">
                    <i class="fas fa-image"></i>
                </div>
            @endif
        </div>

        <div class="driving-school__headinfo">
            @if($drivingSchool->name)
                <h1 class="title is-2">{{ $drivingSchool->name }}</h1>
                <p class="subtitle is-5 mb-2">{{ $drivingSchool->legal_name }}</p>
            @else
                <h1 class="title is-2">{{ $drivingSchool->legal_name }}</h1>
            @endif
            <p class="mb-2">{{ $drivingSchool->address->value }}</p>

            @can('edit driving school', $drivingSchool)
                <div class="buttons">
                    <a href="/driving-schools/{{ $drivingSchool->slug }}/edit" class="button is-primary" aria-label="Редактировать">
                        <i class="fas fa-edit"></i>
                    </a>
                </div>
            @endcan
        </div>
    </header>

    <hr>

    <h2 class="title is-4">Описание</h2>

    <div class="driving-school__columns">
        <div class="driving-school__description content">
            @if($drivingSchool->description)
                {{{ $drivingSchool->description }}}
            @else
                <p>Нет описания</p>
            @endif
        </div>

        <div class="driving-school__sideinfo">
            <section class="mb-6">
                <h3 class="subtitle is-5 is-inline-block">Водительские категории</h3>

                @if($drivingSchool->driving_categories->count())
                    <table class="table is-bordered is-narrow is-fullwidth">
                        @foreach($drivingSchool->driving_categories as $drivingCategory)
                            <tr>
                                <td><b>{{ $drivingCategory->name }}</b></td>
                                <td>{{ $drivingCategory->short_description }}</td>
                            </tr>
                        @endforeach
                    </table>
                @else
                    <p class="is-small">Информация не заполнена</p>
                @endif
            </section>

            <section class="mb-6">
                <div class="driving-school__subheader">
                    <h3 class="subtitle is-5 is-inline-block">Учебные места</h3>

                    @can('edit driving school', $drivingSchool)
                        <a href="{{ route('learningPlaces', [$drivingSchool->slug]) }}"
                           class="button is-small is-primary"
                           aria-label="Редактировать учебные места"
                        >
                            <i class="fas fa-edit"></i>
                        </a>
                    @endcan
                </div>

                @if($drivingSchool->learning_places->count())
                    <table class="table is-bordered is-narrow is-fullwidth">
                        @foreach($drivingSchool->learning_places as $learningPlace)
                            <tr>
                                <td>{{ $learningPlace->getTypeLabel() }}</td>
                                <td>{{ $learningPlace->address->value }}</td>
                            </tr>
                        @endforeach
                    </table>
                @else
                    <p class="is-small">Информация не заполнена</p>
                @endif
            </section>

            <section class="mb-6">
                <div class="driving-school__subheader">
                    <h3 class="subtitle is-5 is-inline-block">Программы</h3>

                    @can('edit driving school', $drivingSchool)
                        <a href="{{ route('programs', $drivingSchool->slug) }}"
                           class="button is-small is-primary"
                           aria-label="Редактировать программы"
                        >
                            <i class="fas fa-edit"></i>
                        </a>
                    @endcan
                </div>

                @if($drivingSchool->programs->count())
                    <table class="table is-bordered is-narrow is-fullwidth">
                        @foreach($drivingSchool->programs as $program)
                            <tr>
                                <td>{{ $program->name }}</td>
                                <td>{{ $program->driving_categories->implode('name', ', ') }}</td>
                            </tr>
                        @endforeach
                    </table>
                @else
                    <p class="is-small">Информация не заполнена</p>
                @endif
            </section>
        </div>
    </div>
@endsection
