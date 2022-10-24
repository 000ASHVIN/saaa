<?php
if (!isset($edit)) $edit = false;
if (!isset($listings)) $listings = [];
?>
<div class="row">
    <div class="col-sm-12 col-md-6">
        @include('admin.store.products.forms.create-edit',compact('edit'))
    </div>
    <div class="col-sm-12 col-md-6">
        @include('admin.store.products.forms.listings',compact('edit','product','listings'))
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="col-sm-12 col-md-8">
                    <h4>Assign Product Links</h4>
                </div>
                @if($edit)
                    <button id="createLink" class="btn btn-xs btn-success pull-right" data-toggle="modal" data-target="#assign_link"><i class="fa fa-link"></i>&nbsp;Create A New Link</button>
                    @include('admin.store.products.includes.assign_link')
                @endif
            </div>
            <div class="panel-body">
                <div class="row">
                    <table class="table table-striped">
                        <thead>
                        <th>Link Url</th>
                        <th>Name</th>
                        <th>Password</th>
                        <th>Edit</th>
                        <th>Remove</th>
                        </thead>
                        <tbody>
                        @if(isset($product))
                            @if(count($product->links))
                                @foreach($product->links as $link)
                                    <tr>
                                        <td>
                                            <div class="form-inline">
                                                <input id="link{{$link->id}}" style="width: 60%;" class="form-control" value="{{ $link->url }}">
                                                <button class="btn btn-success" id="copy-button" data-clipboard-target="#link{{$link->id}}">Copy</button>
                                                <a href="{{ $link->url }}" target="_blank" class="btn btn-info">View</a>
                                            </div>
                                        </td>

                                        <td>{{ str_limit($link->name, 10) }}</td>
                                        {{--<td>{{ ($link->instructions ? : "No Instructions") }}</td>--}}
                                        <td>{{ ($link->secret ? : "No Password") }}</td>
                                        <td><a href="#" data-target="#assign_link_{{$link->id}}" data-toggle="modal"><span class="label label-info">Edit</span></a></td>
                                        <td><a href="{{ route('admin.product.link.destroy', $link->id) }}" data-confirm-content="Are you sure you want to delete selected link"><span class="label label-danger">Remove</span></a></td>
                                    </tr>
                                    @include('admin.store.products.includes.edit_form_link')
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="6">No Links available for this product</td>
                                </tr>
                            @endif
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="col-sm-12 col-md-8">
                    <h4>Assign Product Assessments</h4>
                </div>
                @if($edit)
                    <button id="createLink" class="btn btn-xs btn-success pull-right" data-toggle="modal" data-target="#assign_assessment"><i class="fa fa-question"></i>&nbsp;Assign New Assessment</button>
                    @include('admin.store.products.includes.modals.assign_assessments')
                @endif
            </div>
            <div class="panel-body">
                <div class="row">
                    <table class="table table-striped">
                        <thead>
                        <th>Title</th>
                        <th>CPD Hours</th>
                        <th>Pass Percentage</th>
                        <th>Max Attempts</th>
                        </thead>
                        <tbody>
                        @if(isset($product))
                            @if(count($product->assessments))
                                @foreach($product->assessments as $assessment)
                                    <tr>
                                        <td>{{ $assessment->title }}</td>
                                        <td>{{ $assessment->cpd_hours }} Hours CPD</td>
                                        <td>{{ $assessment->pass_percentage }}%</td>
                                        <td>{{ $assessment->maximum_attempts }} Attempts</td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="4">There is no assessments linked</td>
                                </tr>
                            @endif
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>