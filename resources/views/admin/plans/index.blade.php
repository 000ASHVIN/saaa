@extends('admin.layouts.master')

@section('title', 'Membership Plans')
@section('description', 'All Membership Plans')

@section('content')
    <section>

        <div class="container-fluid container-fullw padding-bottom-10 bg-white">
            <div class="form-group @if ($errors->has('plan_selection')) has-error @endif">
                {!! Form::label('plan_selection', 'Please Select Your Plans') !!}
                <select name="plan_selection" class="form-control" v-model="plan_selection">
                    <option value="null" selected>Please Select</option>
                    <option value="active">Active Plans</option>
                    <option value="disabled">Disabled Plans</option>
                </select>
                @if ($errors->has('plan_selection')) <p class="help-block">{{ $errors->first('plan_selection') }}</p> @endif
            </div>

            <div v-if="plan_selection == 'active'">
                <table class="table table-responsive table-striped">
                    <thead>
                        <th style="width: 20%">Title</th>
                        <th style="width: 5%" class="text-center">Topics</th>
                        <th style="width: 10%" class="text-center">Plan Price</th>
                        <th style="width: 10%" class="text-center">interval</th>
                        <th style="width: 20%" class="text-center" colspan="1">Status</th>
                        <th style="width: 20%" class="text-center" colspan="1">Invoices</th>
                        <th style="width: 5%" class="text-center">Edit</th>
                    </thead>

                    <tbody>
                        @if(count($plans->where('inactive', 0)))
                            @foreach($plans->where('inactive', 0)->sortBy('name')->sortBy('price') as $plan)
                                <td>{{ ucwords(strtolower($plan->name)) }}</td>
                                <td class="text-center">{{ $plan->features()->count() }}</td>
                                <td class="text-center">R{{ number_format($plan->price, 2) }}</td>
                                <td class="text-center">{{ ucfirst($plan->interval).'ly' }}</td>
                                <td class="text-center">
                                    @if($plan->inactive)
                                        <i style="font-size: 25px; color: darkorange" class="fa fa-times-circle-o"></i>
                                    @else
                                        <i style="font-size: 25px; color: limegreen" class="fa fa-check-circle-o"></i>
                                    @endif
                                </td>
                                <td style="width: 5%;" class="text-center">
                                    {!! Form::open(['method' => 'Post', 'route' => ['admin.plans.export_invoices', $plan->id]]) !!}
                                    <button type="submit" class="btn btn-warning" href="#"><i class="ti-download"></i></button>
                                    {!! Form::close() !!}
                                </td>
                                <td style="width: 5%;" class="text-center">
                                    <a class="btn btn-info" href="{{ route('admin.plans.edit', $plan->id) }}"><i class="ti-pencil"></i></a>
                                </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>

            <div v-if="plan_selection == 'disabled'">
                <table class="table table-responsive table-striped">
                    <thead>
                    <th style="width: 20%">Title</th>
                    <th style="width: 5%" class="text-center">Topics</th>
                    <th style="width: 10%" class="text-center">Plan Price</th>
                    <th style="width: 10%" class="text-center">interval</th>
                    <th style="width: 20%" class="text-center" colspan="1">Status</th>
                    <th style="width: 20%" class="text-center" colspan="1">Invoices</th>
                    <th style="width: 5%"></th>
                    </thead>

                    <tbody>
                    @if(count($plans->where('inactive', 1)))
                        @foreach($plans->where('inactive', 1)->sortBy('name') as $plan)
                            <td>{{ $plan->name }}</td>
                            <td class="text-center">{{ $plan->features()->count() }}</td>
                            <td class="text-center">{{ money_format('%2n', $plan->price) }}</td>
                            <td class="text-center">{{ ucfirst($plan->interval).'ly' }}</td>
                            <td class="text-center">
                                @if($plan->inactive)
                                    <i style="font-size: 25px; color: darkorange" class="fa fa-times-circle-o"></i>
                                @else
                                    <i style="font-size: 25px; color: limegreen" class="fa fa-check-circle-o"></i>
                                @endif
                            </td>
                            <td style="width: 5%;" class="text-center">
                                {!! Form::open(['method' => 'Post', 'route' => ['admin.plans.export_invoices', $plan->id]]) !!}
                                <button type="submit" class="btn btn-warning" href="#"><i class="ti-download"></i></button>
                                {!! Form::close() !!}
                            </td>
                            <td style="width: 5%;" class="text-center">
                                <a class="btn btn-info" href="{{ route('admin.plans.edit', $plan->id) }}"><i class="ti-pencil"></i></a>
                            </td>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
            </div>
            </table>
            <a href="{{ route('admin.plans.create') }}" class="btn btn-wide btn-success">Create new plan</a>
        </div>    </section>
@stop

@section('scripts')
    <script src="/js/app.js"></script>
    <script>
        jQuery(document).ready(function () {
            Main.init();
        });
    </script>
@stop