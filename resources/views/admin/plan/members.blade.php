<!-- Extends main layout form layout folder -->
@extends('layout.main')
<!-- Addind Dynamic layout -->
@section('title', 'Member Plans')
@section('style')
    <style>
        input[type=number]::-webkit-inner-spin-button,
        input[type=number]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            margin: 0 !important;
        }

    </style>
@endsection
<!-- Main Content -->
@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="content-wrapper">
        <section class="content-header">
            @if (Session::has('success'))
                <div class="alert alert-success">
                    {{ Session::get('success') }}
                </div>
            @endif
        </section>
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Members Plans
            </h1>
        </section>

        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title">Member Plans</h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body table-responsive no-padding">
                            <table class="table table-hover">
                                <tr>
                                    <th>#</th>
                                    <th>State Count</th>
                                    <th>Monthly ($)</th>
                                    <th>Annually (9% discount)</th>
                                </tr>
                                <tbody>
                                    @if (count($packages) > 0)
                                        <form action="{{ route('update.member.plan') }}" method="post">
                                            {{ csrf_field() }}
                                            @foreach ($packages as $key => $package)
                                                <tr>
                                                    <td>{{ $key + 1 }}</td>
                                                    <td>{{ $package->state_lower_range . ' - ' . $package->state_upper_range }}
                                                    </td>
                                                    <td><input type="number" min="0" step="0.01"
                                                            value="{{ $package->price }}"
                                                            name="price[{{ $package->id }}][monthly]"></td>
                                                    <td><input readonly type="number" min="0" step="0.01"
                                                            value="{{ $package->price * 12 - ($package->price * 12 * 9) / 100 }}"
                                                            name="price[{{ $package->id }}][yearly]"></td>
                                                </tr>
                                            @endforeach
                                            <tr>
                                                <td colspan="4">
                                                    <input type="submit" class="btn btn-info">
                                                </td>
                                            </tr>
                                        </form>
                                    @else
                                        <tr>
                                            <td colspan="4">No packages available.</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>

                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->
                </div>
            </div>
        </section>
    </div>
@endsection
