<?php

namespace App\Http\Controllers;

use App\Item;
use App\lol;
use App\Store_model;
use App\Stores_item;
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

        if(Item::where('item_name',$item)->count()){
            if(Item::where('item_name',$item)->where('user_id',Auth::user()->id)->count()){
                //item agar user ka hoga to added
                $request->session()->flash('list', 'you have already added this item!');
            }else{
                //item naya user add kia hai
                $request->session()->flash('status', 'Item added.Add another item!');
            }
        }
        else{
            //naya item insert hua hai
            $items = new Item;
            $items->item_name = $item;
            $items->user_id = Auth::user()->id;
            $items->save();
            $request->session()->flash('status', 'Item added.Add another item!');
        }

        return redirect('dashboard');
    }

    public function items()
    {

        $title ='Items';

        //item list created by the user
        $items = Item::select('id','item_name',DB::raw('date(created_at) as date'),'created_at')->orderBy('created_at','desc')
           ->where('is_purchase',0)->where('user_id',Auth::user()->id)->get();

        //item list purchased by the user
        $purchased_items = Item::select('items.id','items.item_name',DB::raw('date(items.updated_at) as date'),'items.created_at',
                  DB::raw('(select (item_price) from stores_items where item_id = items.id) as price'),
                  DB::raw('(select(stores.store_name) from stores where stores.id = stores_items.store_id) as store'))
                  ->join('stores_items','items.id','=','stores_items.item_id')
                  ->orderBy('created_at','desc')
                  ->where('items.is_purchase',1)->where('stores_items.user_id',Auth()->user()->id)->get();


       //top 3 trending shops by all users over the website
        $trends = Stores_item::select(DB::raw('(select(store_name) from stores where id = store_id) as store'),
            DB::raw('sum(item_price) as price'),DB::raw('count(store_id) as visit'))
            ->groupBy('store_id')->orderBy('price','desc')->take(3)->get();


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
        Item::where('id',$item_id)->update(['is_purchase'=>true]);
        return redirect()->back();
    }



}


