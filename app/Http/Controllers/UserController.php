<?php

namespace App\Http\Controllers;

use App\Models\Pizza;
use App\Models\Category;
use App\Models\Order;
use Illuminate\Http\Request;
use BaconQrCode\Renderer\Color\Rgb;
use Carbon\Carbon;
use Illuminate\Auth\Events\Validated;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index()
    {
        $pizza = Pizza::where('publish_status', 1)->paginate(9);
        $status = count($pizza) == 0 ? 0 : 1;
        $category = Category::get();
        // dd($category->toArray());
        return view('user.home')->with(['pizza' => $pizza, 'category' => $category, 'status' => $status]);
    }

    // pizza search with category
    public function categorySearch($id)
    {
        $data = Pizza::where('category_id', $id)
                     ->where('publish_status', 1)
                     ->paginate(9);
        $status = count($data) == 0 ? 0 : 1;
        $category = Category::get();
        return view('user.home')->with(['pizza' => $data, 'category' => $category, 'status' => $status]);
    }


    // pizza name search with search bar
    public function searchItem(Request $request)
    {
        // Session::put('SEARCH_ITEM', $request->searchItem);
        $data = Pizza::where('pizza_name', 'like', '%'.$request->searchItem.'%')
                     ->where('publish_status' , 1)
                     ->paginate(9);

        $data->appends($request->all());

        $status = count($data) == 0 ? 0 : 1;
        $category = Category::get();
        return view('user.home')->with(['pizza' => $data, 'category' => $category, 'status' => $status]);
    }

    public function searchPizzaItem(Request $request)
    {
        $minPrice = $request->minPrice;
        $maxPrice = $request->maxPrice;
        $startDate = $request->startDate;
        $endDate = $request->endDate;


        $query = Pizza::select('*')->where('publish_status', 1);

        if(!(is_null($startDate)) && is_null($endDate) ){
            $query = $query->where('created_at', '>=', $startDate);
        }
        elseif(is_null($startDate) && !(is_null($endDate))){
            $query = $query->where('created_at', '<=', $endDate);
        }

        elseif(!(is_null($startDate)) && !(is_null($endDate))){
            $query = $query->where('created_at', '>=', $startDate)
                        ->where('created_at', '<=', $endDate);
        }

        if(!(is_null($minPrice)) && is_null($maxPrice) ){
            $query = $query->where('price', '>=', $minPrice);
        }
        elseif(is_null($minPrice) && !(is_null($maxPrice))){
            $query = $query->where('price', '<=', $maxPrice);
        }

        elseif(!(is_null($minPrice)) && !(is_null($maxPrice))){
            $query = $query->where('price', '>=', $minPrice)
                        ->where('price', '<=', $maxPrice);
        }

        $query = $query->paginate(9);
        $query->appends($request->all());
        $status = count($query) == 0 ? 0 : 1;
        $category = Category::get();
        return view('user.home')->with(['pizza' => $query, 'category' => $category, 'status' => $status]);
    }

    // pizza see more in user page
    public function pizzaDetails($id)
    {
        $data = Pizza::where('pizza_id', $id)->first();
        Session::put('PIZZA_INFO', $data);
        return view('user.details')->with(['pizza' => $data]);
    }

    // order page
    public function order()
    {
        $data = Session::get('PIZZA_INFO');
        return view('user.order')->with(['pizza' => $data]);

    }

    // placeOrder
    public function placeOrder(Request $request)
    {
        $pizzaInfo = Session::get('PIZZA_INFO');
        $userId = auth()->user()->id;
        $pizzaCount = $request->pizzaCount;

        $validator = Validator::make($request->all(),[
            'pizzaCount' => 'required',
            'paymentType' => 'required',
        ]);

        if($validator->fails()){
            return back()->withErrors($validator)
                         ->withInput();
        }

        $orderdata = $this->requestOrderData($request, $userId, $pizzaInfo);

        for($i = 1; $i<= $pizzaCount; $i++)
        {
            Order::create($orderdata);
        }

        $waitingTime = $pizzaInfo['waiting_time'] * $pizzaCount;

        return back()->with(['orderSuccess' => $waitingTime]);
    }

    private function requestOrderData($request, $userId, $pizzaInfo)
    {
        return [
            'customer_id' => $userId,
            'pizza_id' => $pizzaInfo['pizza_id'],
            'carrier_id' => 1,
            'payment_status' => $request->paymentType,
            'order_time' => Carbon::now()
        ];
    }

}
