<div class="container-fluid container-fullw padding-bottom-10 bg-white">
    <hr>
    <table class="table table-responsive table-hover table-striped">
        <thead>
            <th>Tab</th>
            <th>Sequence</th>
            <th colspan="3">Feature Link</th>
        </thead>
        <tbody>
            @if(count($practice_plan_tabs))
                @foreach($practice_plan_tabs as $practice_plan_tab)
                    <tr>
                        <td>{{ $practice_plan_tab->name }}</td>
                        <td>{{ $practice_plan_tab->sequence }}</td>
                        <td style="width: 50%">{{ (implode(', ', $practice_plan_tab->features->pluck('name')->toArray()) ? : "No Features") }}</td>
                        <td style="width: 5%" class="text-center">
                            <a class="btn btn-info" href="{{ route('admin.plans.practice_plan.edit', $practice_plan_tab->id) }}">
                                <i class="ti-pencil"></i>
                            </a>
                        </td>
                        <td style="width: 5%" class="text-center">
                            <a class="btn btn-danger" href="{{ route('admin.plans.practice_plan.destroy', $practice_plan_tab->id) }}">
                                <i class="ti-close"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="3">No Tabs available</td>
                </tr>
            @endif
        </tbody>
    </table>
    <a href="{{ route('admin.plans.practice_plan.create') }}" class="btn btn-sm btn-primary">Add New</a>
    <div class="text-right">
        <div class="">{!! $practice_plan_tabs->render() !!}</div>
    </div>

</div>