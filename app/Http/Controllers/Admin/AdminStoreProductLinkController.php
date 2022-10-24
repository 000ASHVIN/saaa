<?php

namespace App\Http\Controllers\Admin;

use App\Store\Link;
use App\Store\Product;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class AdminStoreProductLinkController extends Controller
{
    public function store(Request $request, $product)
    {
        $this->validate($request, ['url' => 'required', 'name' => 'required']);
        $product = Product::find($product);

        $link = new Link($request->except('_token'));
        $product->links()->save($link);

        alert()->success('Your link has been added', 'Success!');
        return back();
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, ['url' => 'required', 'name' => 'required']);
        $link = Link::find($id);
        $link->update($request->all());

        alert()->success('Your link has been updated', 'Success!');
        return back();
    }

    public function destroy($id)
    {
        $link = Link::find($id);
        $link->delete();

        alert()->success('Your link has been deleted', 'Success!');
        return back();
    }
}
