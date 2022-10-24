@extends('admin.layouts.master')
@section('title', $member->first_name . ' ' . $member->last_name)
@section('description', 'User Profile')

@section('css')
    <link href="/assets/admin/vendor/bootstrap-fileinput/jasny-bootstrap.min.css" rel="stylesheet" media="screen">
    <link rel="stylesheet" type="text/css" href="/assets/admin/vendor/bootstrap-datepicker/bootstrap-datepicker.min.css">
    <style>
        .daterangepicker {
            z-index: 99999 !important;
        }
    </style>

    <style>
        .label-container {
            display: block;
            position: relative;
            padding-left: 23px;
            cursor: pointer;
            font-size: 14px;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }

        /* Hide the browser's default checkbox */
        .label-container input {
            position: absolute;
            opacity: 0;
            cursor: pointer;
            height: 0;
            width: 0;
        }

        /* Create a custom checkbox */
        .checkmark {
            position: absolute;
            top: 0;
            left: 0;
            height: 15px;
            width: 15px;
            background-color: #eee;
        }

        /* On mouse-over, add a grey background color */
        .label-container:hover input ~ .checkmark {
            background-color: #ccc;
        }

        /* When the checkbox is checked, add a blue background */
        .label-container input:checked ~ .checkmark {
            background-color: #2196F3;
        }

        /* Create the checkmark/indicator (hidden when not checked) */
        .checkmark:after {
            content: "";
            position: absolute;
            display: none;
        }

        /* Show the checkmark when checked */
        .label-container input:checked ~ .checkmark:after {
            display: block;
        }

        /* Style the checkmark/indicator */
        .label-container .checkmark:after {
            left: 5px;
            top: 2px;
            width: 5px;
            height: 10px;
            border: solid white;
            border-width: 0 3px 3px 0;
            -webkit-transform: rotate(45deg);
            -ms-transform: rotate(45deg);
            transform: rotate(45deg);
        }
    </style>
@stop

@section('content')

<?php $i=0; ?>
    <div class="container-fluid container-fullw bg-white">
        <div class="row">
            @include('admin.members.includes.nav')
            <div class="col-md-9 col-sm-9 nopadding">
              
                   
                <table class="table table-striped table-hover table-bordered" id="projects">
                    <thead>
                    <tr>
                        <th>Title</th>
                        <th>Start Date </th>
                        <th>End Date</th>
                        <th>Purchased Date</th>
                        <th class="text-center" colspan="2">Tools</th>

                    </tr>
                    </thead>
                    <tbody>
                        @if(count($memmberCourses)>0)
                            @foreach($memmberCourses->sortByDesc('id') as $course)
                                <tr>
                                   
                                    <td>
                                        <a target="_blank" href="{{ route('courses.show',$course->id) }}"
                                           class=" btn-link">{{ $course->title }}</a>
                                    </td>
                                    <td>
                                        {{ Date('Y-m-d',strtotime($course->start_date)) }}
                                    </td>
                                    <td>
                                        {{ Date('Y-m-d',strtotime($course->end_date)) }}
                                    </td>
                                    <td>
                                        <?php 
                                        $invoice = $course->GetOrderByCourse($member,$course->id);
                                        ?>
                                        {{ (empty($invoice)?$course->created_at->toFormattedDateString():$invoice->created_at->toFormattedDateString()) }}
                                    </td>
                                   
                                    <td class="text-center">
                                    @if( !$course->CouponDiscount()->where('user_id',$member->id)->first())    
                                    <a href="#" class="btn btn-xs btn-default" data-toggle="modal" data-target="#course_edit{{ $course->id }}">Apply Coupon Code</a>
                                    @else
                                    <a href="#" class="btn btn-xs btn-default" data-toggle="modal" data-target="">Coupon Code Applied</a>
                                    @endif
                                    @include('admin.members.includes.update_debit_order')
                                        <a href="/admin/{{$member->id}}/{{ $course->id }}/change" data-toggle="tooltip" title="Change Course"
                                           class="btn btn-xs btn-success">
                                            <i class="fa fa-check"></i>
                                        </a>

                                       

                                       
                                        <a href="/admin/{{$member->id}}/{{ $course->id }}/cancel" data-toggle="tooltip" title="Cancel Course"
                                            class="btn btn-xs btn-danger">
                                             <i class="fa fa-close"></i>
                                         </a>
 
                                       
                                    </td>

                                </tr>
                                <?php  $i++; ?>
                            @endforeach
                     
                        @endif
                        @if($i == 0)
                        <tr>
                            <td colspan="8">No Courses records found.</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
                 
              
            </div>
        </div>
    </div>
@stop

@section('scripts')
    <script src="/js/app.js"></script>
    <script src="/assets/admin/assets/js/profile.js"></script>
    <script src="/assets/admin/vendor/bootstrap-fileinput/jasny-bootstrap.js"></script>
    <script src="/assets/admin/vendor/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
    <script src="/assets/admin/assets/js/bootstrap-confirm-delete.js"></script>
    <script>
        jQuery(document).ready(function () {
            Profile.init();
        });
    </script>
    <script src="/assets/admin/assets/js/bootstrap-confirm-delete.js"></script>
    @include('admin.members.includes.spin')
@stop