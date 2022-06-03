<?php

namespace App\Http\Controllers\Admin;

use App\Models\Pizza;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class PizzaController extends Controller
{
    // pizza list page
    public function pizza()
    {
        if(Session::has('PIZZA')){
            Session::forget('PIZZA');
        }

        $data = Pizza::paginate(5);

        if(count($data) == 0){
            $emptyStatus = 0;
        }
        else{
            $emptyStatus = 1;
        }

        return view('admin.pizza.list')->with(['pizza' => $data, 'emptyStatus' => $emptyStatus]);
    }

    // add pizza form page
    public function createPizza()
    {
        $data = Category::get();
        return view('admin.pizza.addPizza')->with(['category' => $data]);
    }

    // insert pizza data
    public function insertPizza(Request $request)
    {


        $validator = Validator::make($request->all(), [
            'pizzaName' => 'required',
            'image' => 'required',
            'price' => 'required',
            'publishStatus' => 'required',
            'category' => 'required',
            'discountPrice' => 'required',
            'buy1get1' => 'required',
            'waitingTime' => 'required',
            'description' => 'required',
        ]);

        if ($validator->fails()) {
            return back()
                    ->withErrors($validator)
                    ->withInput();
        }

        //image save to public folder
        $file = $request->file('image');
        $imageName = uniqid().'_'.$file->getClientOriginalName();
        $file->move(public_path().'/uploads/',$imageName);

        $pizzaData = $this->getPizzaData($request,$imageName);
        Pizza::create($pizzaData);
        return redirect()->route('admin#pizza')->with(['createSuccess' => 'Insert Pizza Successfully...']);
    }

    // delete pizza
    public function deletePizza($id)
    {
        $data = Pizza::select('image')->where('pizza_id', $id)->first();
        $imageName = $data['image'];

        Pizza::where('pizza_id', $id)->delete($id); // delete from database

        // delete image from public file
        if(File::exists(public_path().'/uploads/'.$imageName))
        {
            File::delete(public_path().'/uploads/'.$imageName);
        }
        Pizza::where('pizza_id', $id)->delete($id);
        return back()->with(['deleteSuccess' => 'Deleted Successfully']);
    }

    // pizza see more
    public function pizzaInfo($id)
    {
        $pizzaData = Pizza::where('pizza_id', $id)->first();
        return view('admin.pizza.info')->with(['pizza' => $pizzaData]);
    }

    // edit pizza
    public function editPizza($id)
    {
        $categoryData = Category::get();
        // dd($categoryData->toArray());
        $pizzaData = Pizza::select('pizzas.*','categories.category_id','categories.category_name')
                          ->join('categories','pizzas.category_id','=','categories.category_id')
                          ->where('pizza_id', $id)->first();
        // dd($pizzaData->toArray());
        return view('admin.pizza.edit')->with(['pizza' => $pizzaData, 'category' => $categoryData]);
    }

    // update pizza in database
    public function updatePizza(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'pizzaName' => 'required',
            'price' => 'required',
            'publishStatus' => 'required',
            'category' => 'required',
            'discountPrice' => 'required',
            'buy1get1' => 'required',
            'waitingTime' => 'required',
            'description' => 'required',
        ]);

        if ($validator->fails()) {
            return back()
                    ->withErrors($validator)
                    ->withInput();
        }
        $updatepizzaData = $this->getUpdatePizzaData($request);
        // dd($pizzaData);
        if(isset($updatepizzaData['image']))
        {
            // get old image from database
            $data = Pizza::select('image')->where('pizza_id',$id)->first();
            $image = $data['image'];

            // delete old image from public path
            if(File::exists(public_path().'/uploads/'.$image)){
                File::delete(public_path().'/uploads/'.$image);
            }

            // update new image to public path
            $imageFile = $request->file('image');
            $imageName = uniqid().'_'.$imageFile->getClientOriginalName();
            $imageFile->move(public_path().'/uploads/',$imageName);

            // overwrite image file with image name to update in database
            $updatepizzaData['image'] = $imageName;

            // update pizza data to database
            Pizza::where('pizza_id', $id)->update($updatepizzaData);
            return redirect()->route('admin#pizza')->with(['updateSuccess' => 'Updated Successfully...']);
        }

        Pizza::where('pizza_id', $id)->update($updatepizzaData);
        return redirect()->route('admin#pizza')->with(['updateSuccess' => 'Updated Successfully...']);
    }

    // search pizza
    public function searchPizza(Request $request)
    {
        $serarchKey = $request->table_search;
        $searchData = Pizza::orWhere('pizza_name','like','%'.$serarchKey.'%')
                           ->orWhere('price', $serarchKey)->paginate(5);
        $searchData->appends($request->all());
        if(count($searchData) == 0){
            $emptyStatus = 0;
        }
        else{
            $emptyStatus = 1;
        }
        Session::put('PIZZA', $serarchKey);
        return view('admin.pizza.list')->with(['pizza' => $searchData, 'emptyStatus' =>$emptyStatus]);
    }

    // download pizza wiht file
    public function downloadPizza()
    {
        if(Session::has('PIZZA')){
            $pizza = Session::get('PIZZA');
            $pizza = Pizza::select('pizzas.*',
                            DB::raw('(CASE WHEN pizzas.publish_status = 0 THEN "Unpublish" ELSE "Publish" END) as publish_status'),
                            DB::raw('(CASE WHEN pizzas.buy_one_get_one_status = 1 THEN "Yes" ELSE "No" END) as buy_one_get_one_status'))
                          ->orWhere('pizza_name','like','%'.$pizza.'%')
                          ->orWhere('price', $pizza)
                          ->get();
        }
        else{
            $pizza = Pizza::select('pizzas.*',
                            DB::raw('(CASE WHEN pizzas.publish_status = 0 THEN "Unpublish" ELSE "Publish" END) as publish_status'),
                            DB::raw('(CASE WHEN pizzas.buy_one_get_one_status = 1 THEN "Yes" ELSE "No" END) as buy_one_get_one_status'))
                          ->get();
        }

        $csvExporter = new \Laracsv\Export();

        $csvExporter->build($pizza, [
            'pizza_id' => 'ID',
            'pizza_name' => 'Name',
            'price' => 'Price',
            'publish_status' => 'Publish Status',
            'buy_one_get_one_status' => 'Buy One Get One',
            'created_at' => 'Created Date',
            'updated_at' => 'Updated Date',
        ]);

        $csvReader = $csvExporter->getReader();
        $csvReader->setOutputBOM(\League\Csv\Reader::BOM_UTF8);

        $filename = 'PizzaList.csv';

        return response((string) $csvReader)
            ->header('Content-Type', 'text/csv; charset=UTF-8')
            ->header('Content-Disposition', 'attachment; filename="'.$filename.'"');
    }

    // request pizza update data
    private function getUpdatePizzaData($request)
    {
        $pizzaData = [
            'pizza_name' => $request->pizzaName,
            'price' => $request->price,
            'publish_status' => $request->publishStatus,
            'category_id' => $request->category,
            'discount_price' => $request->discountPrice,
            'buy_one_get_one_status' => $request->buy1get1,
            'waiting_time' => $request->waitingTime,
            'description' => $request->description,
        ];
        if(isset($request->image)){
            $pizzaData['image'] = $request->image;
        }

        return $pizzaData;
    }

    private function getPizzaData($request, $image)
    {
        return $pizzaData = [
            'pizza_name' => $request->pizzaName,
            'image' => $image,
            'price' => $request->price,
            'publish_status' => $request->publishStatus,
            'category_id' => $request->category,
            'discount_price' => $request->discountPrice,
            'buy_one_get_one_status' => $request->buy1get1,
            'waiting_time' => $request->waitingTime,
            'description' => $request->description,
        ];
    }


}
