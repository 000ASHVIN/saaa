@extends('admin.layouts.master')

@section('css')
    <link rel="stylesheet" type="text/css" href="/assets/admin/vendor/bootstrap-daterangepicker/daterangepicker.css"/>
@endsection

@section('title', 'CPDs & Courses')
@section('description', 'CPDs & Courses')

@section('content')
    <br>
    <div class="row">

        {!! Form::open(['method' => 'post']) !!}
            <div class="col-md-4">
                <div class="form-group @if ($errors->has('from')) has-error @endif">
                    {!! Form::label('from', 'Select From Date') !!}
                    {!! Form::input('text', 'from', null, ['class' => 'form-control is-date']) !!}
                    @if ($errors->has('from')) <p class="help-block">{{ $errors->first('from') }}</p> @endif
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group @if ($errors->has('to')) has-error @endif">
                    {!! Form::label('to', 'Select To Date') !!}
                    {!! Form::input('text', 'to', null, ['class' => 'form-control is-date']) !!}
                    @if ($errors->has('to')) <p class="help-block">{{ $errors->first('to') }}</p> @endif
                </div>
            </div>
            <div class="col-md-12">
                <button type="submit" name="download_cpds" value="export_report" class="btn btn-success">Download CPD subscribers</button>
                <button type="submit" name="download_courses" value="view_report" class="btn btn-success">Download Courses</button>
            </div>

        {!! Form::close() !!}
    </div>
    <br/>
    <div class="row">
        <div class="col-md-12">

            <table class="table">
                <tr>
                    <td>CPD SUBS</td>
                    @foreach ($months as $month)
                        <td><strong>{{ $month['name'] }}<strong></td>
                    @endforeach
                </tr>

                <!-- Debit orders -->
                <tr>
                    <td><strong>DEBIT ORDERS:</strong></td>
                    @foreach ($months as $month)
                        <td></td>
                    @endforeach
                </tr>

                <?php
                    $debit_orders = $data['cpds']['debit_orders'];
                ?>
                <tr>
                    <td>PAID</td>
                    @foreach ($months as $month)
                        <td>
                            {{ isset($debit_orders[$month['key']])? ''.round($debit_orders[$month['key']]['paid']['amount'],2) : '0'  }}
                        </td>
                    @endforeach
                </tr>

                <tr>
                    <td>UNPAID</td>
                    @foreach ($months as $month)
                        <td>{{ isset($debit_orders[$month['key']])? ''.round($debit_orders[$month['key']]['unpaid']['amount'],2) : '0'  }}</td>
                    @endforeach
                </tr>

                <tr>
                    <td>CANCELLED</td>
                    @foreach ($months as $month)
                        <td>{{ isset($debit_orders[$month['key']])? ''.round($debit_orders[$month['key']]['cancelled']['amount'],2) : '0'  }}</td>
                    @endforeach
                </tr>

                <tr>
                    <td>Number of Subs</td>
                    @foreach ($months as $month)
                        <td>{{ isset($debit_orders[$month['key']])? ''.$debit_orders[$month['key']]['paid']['orders'] : '0'  }}</td>
                    @endforeach
                </tr>

                <tr>
                    <td colspan="{{ count($months) +1 }}">
                </tr>

                <!-- Once off -->
                <tr>
                    <td><strong>ONCE OFF:</strong></td>
                    @foreach ($months as $month)
                        <td></td>
                    @endforeach
                </tr>

                <?php
                    $once_off = $data['cpds']['once_off'];
                ?>
                <tr>
                    <td>PAID</td>
                    @foreach ($months as $month)
                        <td>{{ isset($once_off[$month['key']])? ''.round($once_off[$month['key']]['paid']['amount'],2) : '0'  }}</td>
                    @endforeach
                </tr>

                <tr>
                    <td>UNPAID</td>
                    @foreach ($months as $month)
                        <td>{{ isset($once_off[$month['key']])? ''.round($once_off[$month['key']]['unpaid']['amount'], 2) : '0'  }}</td>
                    @endforeach
                </tr>

                <tr>
                    <td>CANCELLED</td>
                    @foreach ($months as $month)
                        <td>{{ isset($once_off[$month['key']])? ''.round($once_off[$month['key']]['cancelled']['amount'], 2) : '0'  }}</td>
                    @endforeach
                </tr>

                <tr>
                    <td>Number of Subs</td>
                    @foreach ($months as $month)
                        <td>{{ isset($once_off[$month['key']])? ''.$once_off[$month['key']]['paid']['orders'] : '0'  }}</td>
                    @endforeach
                </tr>

                <tr>
                    <td colspan="{{ count($months) +1 }}">
                </tr>

                <!-- CPD subscribers -->
                <tr>
                    <td><strong>AMOUNT OF <br/>CPD SUBSCRIBERS:</strong></td>
                    <td colspan="{{ count($months) }}">
                        {{ $totalSubscribers }}
                    </td>
                </tr>

                <tr>
                    <td colspan="{{ count($months) +1 }}">
                </tr>

                <tr>
                    <td colspan="{{ count($months) +1 }}">
                </tr>
                <tr>
                    <td>COURSES</td>
                    @foreach ($months as $month)
                        <td></td>
                    @endforeach
                </tr>

                <!-- Debit orders -->
                <tr>
                    <td><strong>DEBIT ORDERS:</strong></td>
                    @foreach ($months as $month)
                        <td></td>
                    @endforeach
                </tr>

                <?php
                    $debit_orders = $data['courses']['debit_orders'];
                ?>
                <tr>
                    <td>PAID</td>
                    @foreach ($months as $month)
                        <td>{{ isset($debit_orders[$month['key']])? ''.round($debit_orders[$month['key']]['paid']['amount'], 2) : '0'  }}</td>
                    @endforeach
                </tr>

                <tr>
                    <td>UNPAID</td>
                    @foreach ($months as $month)
                        <td>{{ isset($debit_orders[$month['key']])? ''.round($debit_orders[$month['key']]['unpaid']['amount'], 2) : '0'  }}</td>
                    @endforeach
                </tr>

                <tr>
                    <td>CANCELLED</td>
                    @foreach ($months as $month)
                        <td>{{ isset($debit_orders[$month['key']])? ''.round($debit_orders[$month['key']]['cancelled']['amount'], 2) : '0'  }}</td>
                    @endforeach
                </tr>

                <tr>
                    <td colspan="{{ count($months) +1 }}">
                </tr>

                <!-- Once off -->
                <tr>
                    <td><strong>ONCE OFF:</strong></td>
                    @foreach ($months as $month)
                        <td></td>
                    @endforeach
                </tr>

                <?php
                    $once_off = $data['courses']['once_off'];
                ?>
                <tr>
                    <td>PAID</td>
                    @foreach ($months as $month)
                        <td>{{ isset($once_off[$month['key']])? ''.round($once_off[$month['key']]['paid']['amount'], 2) : '0'  }}</td>
                    @endforeach
                </tr>

                <tr>
                    <td>UNPAID</td>
                    @foreach ($months as $month)
                        <td>{{ isset($once_off[$month['key']])? ''.round($once_off[$month['key']]['unpaid']['amount'], 1) : '0'  }}</td>
                    @endforeach
                </tr>

                <tr>
                    <td>CANCELLED</td>
                    @foreach ($months as $month)
                        <td>{{ isset($once_off[$month['key']])? ''.round($once_off[$month['key']]['cancelled']['amount'], 1) : '0'  }}</td>
                    @endforeach
                </tr>

            </table>
            
            {{-- <form method="post">
                {{ csrf_field() }}
                <input type="submit" name="extract_cpd_course" value="Download" class="btn btn-primary">
                <br/><br/>
            </form> --}}
        </div>
    </div>
@endsection

@section('scripts')
    <script src="/js/app.js"></script>
    <script>
        jQuery(document).ready(function () {
            Main.init();
        });
    </script>
@endsection