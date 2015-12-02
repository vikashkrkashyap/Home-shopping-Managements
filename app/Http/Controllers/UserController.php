<?php

namespace App\Http\Controllers;

use App\Item;
use App\lol;
use App\pivot_user_item;
use App\Store_model;
use App\Stores_item;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpKernel\HttpCache\Store;

class UserController extends Controller
{


    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $title = "welcome to evibe";

        return view('dashboard',compact('title'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), ['item_name'=>'required']);

        if($validator->fails())
        {
            return redirect('dashboard')
                ->withErrors($validator)
                ->withInput();

        }


        $item = $request->input('item_name');

        if(!Item::where('item_name',$item)->count()){

            //naya item insert hua hai
            $items = new Item;
            $item_id = $items->insertGetId([
                'item_name' => $item,
                'created_at'=>Carbon::now()
            ]);

            $data = new pivot_user_item;
            $data->user_id = Auth::user()->id;
            $data->item_id = $item_id;
            $data->save();
            $request->session()->flash('status', 'Item added.Add another item!');
        }

        else{
            $item_id = Item::where('item_name',$item)->value('id');
            if(pivot_user_item::where('item_id',$item_id)->where('user_id',Auth::user()->id)->count()){
                //item agar user ka hoga to added
                $request->session()->flash('list', 'you have already added this item!');
            }else{
                //item naya user add kia hai
                $data = new pivot_user_item;
                $data->user_id = Auth::user()->id;
                $data->item_id = $item_id;
                $data->save();
                $request->session()->flash('status', 'Item added.Add another item!');
            }



        }

        return redirect('items');
    }

    public function items()
    {


        $title ='Items';

        $items = pivot_user_item::select('item_id',DB::raw('(select(item_name) from items where id = item_id) as item_name'),
            DB::raw('(select date(created_at) from items where id = item_id) as date'))->orderBy('date','desc')
            ->where('pivot_user_item.is_purchase',0)->where('pivot_user_item.user_id',Auth()->user()->id)->get();

        //item list purchased by the user
        $purchased_items = pivot_user_item::select('pivot_user_item.item_id',DB::raw('(select(item_name) from items where items.id =
         pivot_user_item.item_id) as item_name'),DB::raw('date(pivot_user_item.updated_at) as date'),
            //DB::raw('(select (stores_items.item_price) from stores_items where stores_items.item_id = pivot_user_item.item_id) as price'),
                 DB::raw('(select(stores.store_name) from stores where stores.id = stores_items.store_id) as store'))
                  ->join('stores_items','pivot_user_item.item_id','=','stores_items.item_id')
                  ->groupBy('item_name')->where('stores_items.user_id',Auth::user()->id)
                  ->where('pivot_user_item.is_purchase',1)->get();

       //top 3 trending shops by all users over the website
        $trends = Stores_item::select(DB::raw('(select(store_name) from stores where id = store_id) as store'),
            DB::raw('sum(item_price) as price'),DB::raw('count(store_id) as visit'))
            ->groupBy('store_id')->orderBy('price','desc')->where('user_id',Auth::user()->id)->take(3)->get();



        return view('list',compact('title','items','trends','purchased_items'));
    }

    //function for storing the data of list,price and items in database
    public function postStorePrice(Request $request)
    {
        $title = 'post-data';

        $validator = Validator::make($request->all(),
                [
                    'store_name'=>'required|alpha',
                    'price'=>'required|numeric|min:0',
                ]);

        if($validator->fails())
        {
            return redirect('items')
                ->withErrors($validator)
                ->withInput();

        }

        $store_name = $request->input('store_name');

        //checking whether store name is repeated or not and if not repeated inserting into the table

        if(!Store_model::where('store_name',$store_name)->count()) {
            $store = new Store_model;
            $store_id = $store->insertGetId([
                'store_name' => $store_name
            ]);
        }
        else
        {
            $store_id = Store_model::where('store_name', $store_name)->value('id');
        }
        $price = $request->input('price');
        $item_id = $request->input('item-checkbox');

        //Storing the data into store-item table

        $data = new Stores_item;
        $data->user_id = Auth::user()->id;
        $data->item_price = $price;
        $data->item_id = $item_id;
        $data->store_id = $store_id;
        $data->save();

       //updating the items as purchased
        pivot_user_item::where('item_id',$item_id)->update(['is_purchase'=>true]);
        return redirect()->back();
    }



}


