@extends('admin.layouts.master')

@section('title', 'Subscribers')
@section('description', 'All of our Subscribers registered to our website')

@section('content')
    <br>
    <div class="row">
        <div class="col-sm-12">
            @if(count($plans))
                @foreach($plans as $plan)
                    <h1>{{ $plan->name }} <span>{{ count($plan->subscriptions) }}</span></h1>
                    <textarea name="{{ $plan->slug }}" id="{{ $plan->slug }}" cols="30" rows="10" style="width: 100%;" class="selectme">EMAIL, FIRST_NAME,LAST_NAME,CELL
                    @foreach($plan->subscriptions as $subscription)
                        @if($subscription->user)
                            {{ $subscription->user->email }}, {{ $subscription->user->first_name }}, {{ $subscription->user->last_name }}
                        @endif
                    @endforeach
                    </textarea>
                @endforeach
            @endif
        </div>
    </div>
@stop

@section('scripts')
    <script src="/admin/assets/js/index.js"></script>
    <script>
        jQuery(document).ready(function () {
            Main.init();
        });
    </script>
    <script>
        $('.selectme').focus(function() {
            var $this = $(this);

            $this.select();

            window.setTimeout(function() {
                $this.select();
            }, 1);

            // Work around WebKit's little problem
            function mouseUpHandler() {
                // Prevent further mouseup intervention
                $this.off("mouseup", mouseUpHandler);
                return false;
            }

            $this.mouseup(mouseUpHandler);
        });
    </script>
@stop