@extends('admin.layouts.master')

@section('title', 'Store')
@section('description', 'Edit product')

@section('content')
    <br>
    @include('admin.store.products.includes.forms',['edit' => true, 'product' => $product, 'listings' => $product->listings]);
@stop

@section('scripts')
    <script>
        $('.select2').select2();
    </script>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap3-dialog/1.34.7/css/bootstrap-dialog.min.css"></link>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap3-dialog/1.34.7/js/bootstrap-dialog.min.js"></script>
    @include('admin.store.products.includes.modals',['product' => $product])
    <script>
        $(function () {
            var addToListingModal = new BootstrapDialog({
                type: BootstrapDialog.TYPE_PRIMARY,
//            size: BootstrapDialog.SIZE_SMALL,
                title: '<strong>Add to listing</strong>',
                message: $('#add-to-listing').html(),
                buttons: [
                    {
                        label: 'Cancel',
                        cssClass: 'btn-default',
                        action: function (dialogRef) {
                            dialogRef.close();
                        }
                    },
                    {
                        label: 'Add to listing',
                        cssClass: 'btn-primary',
                        action: function () {
                            $('#add-to-listing-form').submit();
                        }
                    }
                ]
            });
            addToListingModal.realize();
            //addToListingModal.getModalBody().css('height','200px');
            $('#add-to-listing-button').click(function () {
                addToListingModal.open();
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