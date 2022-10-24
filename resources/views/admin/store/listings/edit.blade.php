@extends('admin.layouts.master')

@section('title', 'Store')
@section('description', 'Edit listing')

@section('content')
    <br>
    @include('admin.store.listings.includes.forms',['edit' => true, 'listing' => $listing, 'discounts' => $listing->discounts])
@stop

@section('scripts')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap3-dialog/1.34.7/css/bootstrap-dialog.min.css"></link>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap3-dialog/1.34.7/js/bootstrap-dialog.min.js"></script>
    @include('admin.store.listings.includes.modals',['listing' => $listing])
    <script>
        $(function () {
            var addDiscountModal = new BootstrapDialog({
                type: BootstrapDialog.TYPE_PRIMARY,
//            size: BootstrapDialog.SIZE_SMALL,
                title: '<strong>Add discount</strong>',
                message: $('#add-discount').html(),
                buttons: [
                    {
                        label: 'Cancel',
                        cssClass: 'btn-default',
                        action: function (dialogRef) {
                            dialogRef.close();
                        }
                    },
                    {
                        label: 'Add discount',
                        cssClass: 'btn-primary',
                        action: function () {
                            $('#add-discount-form').submit();
                        }
                    }
                ]
            });
            addDiscountModal.realize();
            $('#add-discount-button').click(function () {
                addDiscountModal.open();
            });
        });
    </script>
    <script src="/js/app.js"></script>
    <script>
        jQuery(document).ready(function () {
            Main.init();
        });
    </script>
@stop