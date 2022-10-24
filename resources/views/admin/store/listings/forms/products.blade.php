<hr>
<br>
<h3>Products</h3>
<store-listings-admin-add-product :products="{{ $products->toJson() }}"
                                  :selected-products="{{ json_encode($selectedProducts) }}"
                                  inline-template>
    <div class="panel panel-default">
        <div class="panel-heading">
            <input v-model="search" type="text" class="form-control" placeholder="Search">
        </div>
        <div class="panel-body">
            <ul class="list-group" style="max-height: 400px; overflow-y: scroll;">
                <li v-for="product in products | filterBy search | filterBySelectedListingsAndTitle"
                    class="list-group-item">
                    <div class="input-group">
                        <span class="input-group-addon" style="background-color: #f7f7f8; border-color: #F8F8F8">
                                <input v-model="selectedProducts" name="products[]" type="checkbox"
                                       value="@{{ product.id }}">
                            </span>
                                <span v-on:click.prevent="toggleSelectProduct(product)">
                                    <input style="cursor: pointer;" type="text" class="form-control disabled" disabled
                                           value="@{{ product.topic }}: @{{ product.title }} (@{{ product.year }})">
                                </span>
                                <span class="input-group-addon"
                                      style="background-color: #f7f7f8; border-color: #F8F8F8">
                                    <span class="label label-default">@{{ product.listings.length }}</span>
                                    </span>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</store-listings-admin-add-product>