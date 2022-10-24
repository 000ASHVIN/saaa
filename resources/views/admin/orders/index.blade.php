@extends('admin.layouts.master')

@section('title', 'Store')
@section('description', 'Products')

@section('content')
    <br>
    <store-admin-index :orders="{{ $orders->toJson() }}"
                       :shipping-information-statuses="{{ App\Store\ShippingInformationStatus::all()->toJson() }}"
                       :countries="{{ json_encode(App\Country::countriesByCode) }}"
                       :provinces="{{ json_encode(App\Province::provincesByCode) }}"
                       inline-template>
        <!-- Modal -->
        <div class="modal fade" id="shippingInfoModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog modal-sm" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Shipping Information</h4>
                    </div>
                    <form v-on:submit.prevent="updateShippingInformation" action="{{ route('admin.shipping.update') }}" method="POST">
                        <div class="modal-body">
                            <div v-if="showingShippingInformation">
                                {{--@{{ order.shipping_information.tracking_code }}--}}
                                {{--<br>--}}
                                <p><strong>User</strong></p>
                                <div class="form-group">
                                    <a target="_blank"
                                       href="@{{ '/admin/members/'+showingShippingInformation.address.user.id }}">
                                        @{{ showingShippingInformation.address.user.first_name }}
                                        @{{ showingShippingInformation.address.user.last_name }}
                                    </a>
                                    <br>
                                    ( @{{ showingShippingInformation.address.user.email }} )
                                </div>
                                <hr>
                                <p><strong>Address</strong></p>
                                <div class="form-group">
                                <span v-if="showingShippingInformation.address.line_one">
                                    @{{ showingShippingInformation.address.line_one }},
                                    <br>
                                </span>
                                <span v-if="showingShippingInformation.address.line_two">
                                    @{{ showingShippingInformation.address.line_two }},
                                    <br>
                                </span>
                                <span v-if="showingShippingInformation.address.city">
                                    @{{ showingShippingInformation.address.city }},
                                    <br>
                                </span>
                                <span v-if="showingShippingInformation.address.area_code">
                                    @{{ showingShippingInformation.address.area_code }},
                                    <br>
                                </span>
                                <span v-if="showingShippingInformation.address.province">
                                    @{{ provinces[showingShippingInformation.address.province] }},
                                    <br>
                                </span>
                                <span v-if="showingShippingInformation.address.country">
                                    @{{ countries[showingShippingInformation.address.country] }}
                                </span>
                                </div>
                                <hr>

                                <div class="form-group">
                                    <label for="tracking-code"><strong>Tracking code</strong></label>
                                    <input v-model="shippingInformationUpdate.tracking_code" name="tracking_code"
                                           class="form-control" id="tracking-code">
                                </div>

                                <div class="form-group">
                                    <label for="status"><strong>Status</strong></label>
                                    <select v-model="shippingInformationUpdate.status" name="status_id"
                                            class="form-control"
                                            id="status">
                                        <option v-for="shippingInformationStatus in shippingInformationStatuses"
                                                value="@{{ shippingInformationStatus }}">@{{ shippingInformationStatus.title }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <h3>Total: @{{ orders.length }}</h3>

        <div class="input-group">
            <input class="form-control" type="text" placeholder="Search" v-model="search">
            <span class="input-group-btn">
                <button v-on:click="search = ''" class="btn btn-default" type="button">&times;</button>
            </span>
        </div><!-- /input-group -->

        <table id="orders-grid" class="table table-condensed table-hover table-striped">
            <thead>
            <tr>
                <th>Received at</th>
                <th>Product</th>
                <th>Qty</th>
                <th>Invoice no</th>
                <th>Invoice status</th>
                <th>Pending</th>
                <th>Shipping status</th>
                <th>Shipping info</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="order in filteredOrders | orderBy 'created_at' | filterBy search">
                <td>
                    @{{ order.created_at | momentFromNow }}
                </td>
                <td>
                    @{{ order.product.detailedTitle }}
                </td>
                <td>
                    @{{ order.quantity }}
                </td>
                <td>
                    <a target="_blank" href="/invoices/view/@{{ order.invoice.id }}">@{{ order.invoice.reference }}</a>
                    <button v-on:click="search = order.invoice.reference" type="button" class="btn btn-xs btn-default">
                        <i class="fa fa-filter"></i></button>
                </td>
                <td>
                    @{{ order.invoice.status.toUpperCase() }}
                </td>
                <td>
                    @{{ (order.is_pending ? "Yes": "No") }}
                </td>
                <td>
                    <span v-if="order.shipping_information">@{{ order.shipping_information.status.title }}</span>
                    <span v-else>N/A</span>
                </td>
                <td>
                    <button v-on:click.sync="showShippingInfoModal(order.shipping_information)" type="button"
                            class="btn btn-xs btn-default">
                        View / Update
                    </button>
                </td>
            </tr>
            </tbody>
        </table>
    </store-admin-index>
@stop

@section('scripts')
    <script src="/js/app.js"></script>
    <script src="/assets/admin/vendor/moment/moment.min.js"></script>
    <script>
        jQuery(document).ready(function () {
            Main.init();
        });
    </script>
@stop