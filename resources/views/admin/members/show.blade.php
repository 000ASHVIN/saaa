@extends('admin.layouts.master')
@section('title', $member->first_name . ' ' . $member->last_name)
@section('description', 'User Profile')

@section('css')
    <link href="/assets/admin/vendor/bootstrap-fileinput/jasny-bootstrap.min.css" rel="stylesheet" media="screen">
    <link rel="stylesheet" type="text/css" href="/assets/admin/vendor/bootstrap-datepicker/bootstrap-datepicker.min.css">
    <style>
        .daterangepicker{
            z-index:99999!important;
        }
        .select2-dropdown {
            z-index: 9999;
        }
        #merge_profile .modal-dialog {
            top: 30%;
        }
    </style>
@stop

@section('content')
    <div class="container-fluid container-fullw bg-white">
        <div class="row">
            @include('admin.members.includes.nav')
            <div class="col-md-9 col-sm-9 nopadding">
                <fieldset>
                    <legend>General Information</legend>
                    <table class="table table-responsive">
                        <tbody>
                        <tr>
                            <td>Full Name:</td>
                            <td class="text-right">{{ $member->first_name.' '.$member->last_name }}</td>
                        </tr>
                        <tr>
                            <td>ID Number:</td>
                            <td class="text-right">{{ ($member->id_number? : "None") }}</td>
                        </tr>
                        {{-- <tr>
                            <td>Subscription:</td>
                            <td class="text-right">
                                @if(count($member->hasCompany()))
                                    <a target="_blank" href="{{ route('admin.members.show', $member->hasCompany()->company->admin()->id) }}">
                                        <div class="label label-info">{{ ucfirst($member->hasCompany()->company->title) }}</div>
                                    </a>
                                @endif
                                <div class="label label-info">{{ ($member->subscription('cpd')? $member->subscription('cpd')->plan->name.' - '.ucfirst($member->subscription('cpd')->plan->interval).'ly' : "None") }}</div>
                            </td>
                        </tr> --}}
                        <tr>
                            <td>Current Active Subscription:</td>
                            <td class="text-right">
                                @if(count($member->hasCompany()) && $member->hasCompany()->company->admin()->subscription('cpd') && $member->hasCompany()->company->admin()->subscription('cpd')->plan->is_practice)
                                    <a target="_blank" href="{{ route('admin.members.show', $member->hasCompany()->company->admin()->id) }}">
                                        <div class="label label-info">{{ ucfirst($member->hasCompany()->company->title) }}</div>
                                    </a>
                                @endif
                                <div class="label label-info">{{ ($member->activeCPDSubscription() ? $member->activeCPDSubscription()->plan->name.' - '.ucfirst($member->activeCPDSubscription()->plan->interval).'ly' : "None") }}</div>
                            </td>
                        </tr>
                        <tr>
                            <td>Previous Subscription Plan:</td>
                            <td class="text-right">
                                <div class="label label-info">{{ ($member->previousSubscription()? $member->previousSubscription()->plan->name.' - '.ucfirst($member->previousSubscription()->plan->interval).'ly' : "None") }}</div>
                            </td>
                        </tr>
                        <tr>
                            <td>Subscription Expiry Date:</td>
                            @if($member->subscription('cpd'))
                            <td class="text-right">{{ date_format($member->subscription('cpd')->ends_at, 'Y-m-d') }}</td>
                            @else
                                <td class="text-right">None</td>
                            @endif
                            
                        </tr>
                        <tr>
                            <td>Company:</td>
                            @if($member->profile && $member->profile->company)
                                <td class="text-right">{{ $member->profile->company }}</td>
                            @else
                                <td class="text-right">None</td>
                            @endif
                        </tr>
                        <tr>
                            <td>Member Since:</td>
                            <td class="text-right">{{ \Carbon\Carbon::parse($member->created_at)->diffForHumans() }}</td>
                        </tr>
                        <tr>
                            <td>Member Position:</td>
                            <td class="text-right">{{ ($member->profile && $member->profile->position ? $member->profile->position : "None") }}</td>
                        </tr>
                        <tr style="background-color: #fafafa">
                            <td><strong>Account Belongs to</strong></td>
                            @if($member->subscription('cpd')&& $member->subscription('cpd')->SalesAgent())
                                <td class="text-right">
                                    @if (auth()->user()->hasRole('super'))
                                        <a class="btn btn-xs btn-primary" href="#" data-toggle="modal" data-target="#assignOwnership"><i class="fa fa-user-plus"></i> Assign Ownership</a>
                                    @endif
                                    <div class="label label-warning">{{ ucfirst($member->subscription('cpd')->SalesAgent()->first_name).' '.ucfirst($member->subscription('cpd')->SalesAgent()->last_name) }}</div>
                                </td>
                            @else
                                @if (auth()->user()->hasRole('super'))
                                    <td class="text-right">
                                        @if (auth()->user()->hasRole('super'))
                                            <a class="btn btn-xs btn-primary" href="#" data-toggle="modal" data-target="#assignOwnership"><i class="fa fa-user-plus"></i> Assign Ownership</a>
                                        @endif
                                    </td>
                                @else
                                <td class="text-right"> - </td>
                                @endif
                            @endif
                        </tr>

                        <tr>
                            <td>Credit Card</td>
                            @if(count($member->primaryCard))
                                <td class="text-right"><div class="label label-info">{!! $member->primaryCard->number !!} </div> &nbsp;
                                    <div class="label label-danger">
                                        <a data-toggle="modal" data-target="#remove_card{{$member->id}}" style="color: white!important;"><span class="fa fa-times"></span> Delete</a>
                                    </div>
                                </td>
                                @include('admin.members.includes.delete_card')
                            @else
                                <td class="text-right"><div class="label label-warning">No Credit Card</div></td>
                            @endif
                        </tr>
                        </tbody>
                    </table>
                </fieldset>

                <fieldset>
                    <legend>Professional Body</legend>
                    <table class="table table-responsive">
                        <tbody>
                        <tr>
                            <td>Professional Body:</td>
                            @if($member->body)
                                <td class="text-right">{{ $member->body->title }}</td>
                            @else
                                <td class="text-right">None</td>
                            @endif
                        </tr>
                        <tr>
                            <td>Membership Number:</td>
                            <td class="text-right"><strong>{{ ($member->membership_number ? : "None") }}</strong></td>
                        </tr>
                        <tr>
                            <td>Membership Verified:</td>
                            <td class="text-right">{{ ($member->membership_verified ? "Yes" : "No") }}</td>
                        </tr>
                        </tbody>
                    </table>
                </fieldset>

                <fieldset>
                    <legend>Contact Information</legend>
                    <table class="table table-responsive">
                        <tbody>
                        <tr>
                            <td>Website:</td>
                            <td class="text-right">{{ ($member->profile && $member->profile->website ? $member->profile->website : "None") }}</td>
                        </tr>
                        <tr>
                            <td>Email Address:</td>
                            <td class="text-right">{{ $member->email }}</td>
                        </tr>
                        <tr>
                            <td><strong>Billing Email Address:</strong></td>
                            <td class="text-right">{{ ($member->billing_email_address ? : "None") }}</td>
                        </tr>

                        <tr>
                            <td>Cellphone Number:</td>
                            <td class="text-right">{{ ($member->cell ? : "None") }}</td>
                        </tr>
                        <tr>
                            <td>Alternative Number:</td>
                            <td class="text-right">{{ ($member->alternative_cell ? : "None") }}</td>
                        </tr>
                        </tbody>
                    </table>
                </fieldset>

                <fieldset>
                    <legend>Account Billing</legend>
                    <table class="table table-responsive">
                        <tr>
                            <td><strong>Payment Method</strong></td>
                            <td class="text-right">
                                {{ (strtoupper(str_replace('_', ' ', $member->payment_method)) ? : "None") }}
                                <a href="#" data-target="#changePaymentMethod" data-toggle="modal" style="margin-left: 5px;"><span class="btn btn-xs btn-primary">Change</span></a>
                            </td>
                        </tr>
                        <tr>
                            <td>Account Statement</td>
                            <td class="text-right"><a href="#" data-target="#statement_sending_{{$member->id}}" data-toggle="modal"><span class="label label-success"><i class="fa fa-send"></i> Send Account Statement</span></a></td>
                        </tr>
                        <tr>
                            <td>Latest Invoice</td>
                            @if(count($member->invoices))
                                <td class="text-right">
                                    <a target="_blank" href="{{ route('invoices.show', [$member->invoices->first()->id]) }}"><span class="label label-info">#{{ $member->invoices->first()->reference }}</span></a>
                                    | <div class="label label-info">{{ $member->invoices->first()->status }}</div> | <a onclick="send(this)" href="{{ route('resend_invoice', $member->invoices->first()->id) }}"><span class="label label-success"><i class="fa fa-send"></i> Resend</span></a>
                                </td>
                            @else
                                <td class="text-right">No Invoices</td>
                            @endif
                        </tr>
                    </table>
                </fieldset>


                <fieldset>
                    <legend>More Information</legend>
                    <table class="table table-responsive">
                        <tr>
                            <td>Account Status:</td>
                            <td class="text-right">
                    <span class="label label-sm label-info">
                        {{ ucwords($member->status) }}
                    </span>
                            </td>
                        </tr>
                        <tr>
                            <td>Account in arrears :</td>
                            <td class="text-right">
                   <span class=" {{ ($member->ageAnalysis()? "label-sm label label-warning" : "label-sm label label-success") }}">
                        @if($member->ageAnalysis())
                           {{ ($member->ageAnalysis() <= 1? : $member->ageAnalysis().' '.'Days') }}
                       @else
                           Account in good standing
                       @endif
                    </span>
                            </td>
                        </tr>

                        @if(auth()->user()->is('super'))
                            <tr>
                                <td>Cancel Subscription</td>
                                <td class="text-right">
                                    <a data-toggle="modal" data-target="#cancel_subscription" class="label label-sm label-warning" href="#">Cancel Subscription</a>
                                    @include('admin.members.includes.cancel_subscription')
                                </td>
                            </tr>
                        @endif
                        <tr>
                            <td>Force Suspend:</td>
                            <td class="text-right">
                                @if($member->force_suspend)
                                    <a class="label label-sm label-warning" href="{{ route('member_force_suspend', $member->id) }}">Un-Suspend Account</a>
                                @else
                                    <a class="label label-sm label-danger" href="{{ route('member_force_suspend', $member->id) }}">Suspend Account</a>
                                @endif
                            </td>
                        </tr>

                        <tr>
                            <td>Terminate Account:</td>
                            <td class="text-right">
                                <a data-toggle="modal" data-target="#cancel_profile" class="label label-sm label-danger" href="#">Cancel Account</a>
                                @include('admin.members.includes.cancel_profile')
                            </td>
                        </tr>

                        {{--  @if ($member->status == 'suspended')  --}}
                        <tr>
                            <td>Active user and subscription:</td>
                            <td class="text-right">
                                <a class="label label-sm label-warning" href="{{ route('active_user_and_subscription', $member->id) }}">Active user and subscription</a>
                            </td>
                        </tr>
                        {{--  @endif  --}}

                        @if(auth()->user()->is('super'))
                        <tr>
                            <td>Merge Profile:</td>
                            <td class="text-right">
                                <a data-toggle="modal" data-target="#merge_profile" class="label label-sm label-danger" href="#">Merge Profile</a>
                                @include('admin.members.includes.merge_profile')
                            </td>
                        </tr>
                        @endif

                        <tr>
                            <td>Register With Moodle:</td>
                            <td class="text-right">
                                
                                @if($member->moodle_user_id == 0 || $member->moodle_user_id == null)
                                    <a href="{{ route('member.add_moodle', $member->id) }}" class="label-sm label label-success">Register</a>
                                @else
                                    <span class="label label-sm label-info">Already Registered</span>
                                @endif
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </fieldset>
            </div>
        </div>
    </div>
    @include('admin.members.includes.statement.confirm')
    @include('admin.members.includes.assign_ownership')
    @include('admin.members.includes.change_payment_method')
@stop

@section('scripts')
    <script src="/js/app.js"></script>
    <script src="/assets/admin/assets/js/profile.js"></script>
    <script src="/assets/admin/vendor/bootstrap-fileinput/jasny-bootstrap.js"></script>
    <script src="/assets/admin/vendor/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
    <script src="/assets/admin/assets/js/bootstrap-confirm-delete.js"></script>
    <script>
        $( document ).ready( function( )
        {$( '.delete' ).bootstrap_confirm_delete(
                {
                    debug:    false,
                    heading:  'Remove Ticket',
                    message:  'Are you sure you want to delete this ticket?',
                    data_type:'post',
                    callback: function ( event )
                    {
                        var button = event.data.originalObject;
                        button.closest( 'form' ).submit();
                    },
                }
            );
        } );
    </script>

    <script>
        jQuery(document).ready(function () {
            Profile.init();
            $('#profile-tabs a[href="#{{old('tab')}}"]').tab('show')
        });
    </script>

    <script type="text/javascript">
        $('.custom-select').select2();
    </script>

    @if(count($member->invoices))
        <script>
            $('.delete-me').on('click', function(){
                $(this).closest('form').submit();
            });
        </script>
    @endif

    @if(Request::has('edit'))
        <script>
            $(document).ready(function () {
                $('#myTab4 li:eq(1) a').tab('show');
            });
        </script>
    @endif

    <script>
        $('#merge_profile_email').select2({
            ajax: {
                url: "{{ route('member.find_user') }}",
                dataType: 'json',
                width: 'resolve'
            }
        });

        $("#merge_profile_submit").click(function(e){
            e.preventDefault()
            const user_id = $('#merge_profile_email').val();
            let merge_email = "";
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                'url': '/admin/member/'+user_id+'/details',
                'type': 'get',
                'data': {
                    "_token": "{{ csrf_token() }}"
                },
                'success': function(data) {
                    let msg = "Are you sure you want to merge this profile {{ $member->email }} into this profile "+data.email+", click to confirm.";
                    swal({
                        title: "Are you sure?",
                        text: msg,
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "confirm",
                        closeOnConfirm: false
                    }, function(isConfirm){
                        if (isConfirm) {
                            $("#merge_profile_form").submit();
                        }
                    });
                }
            });
            
        })
    </script>

    @include('admin.members.includes.spin')
@stop

