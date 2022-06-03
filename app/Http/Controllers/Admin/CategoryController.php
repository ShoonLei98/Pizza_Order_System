<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Pizza;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{

    // category list page
    public function category()
    {
        // $categoryData = Category::paginate(5);
        // dd($categoryData->toArray());

        // $categoryCount = $categoryData->total();
        // dd($categoryCount->toArray());

        if(Session::has('CATEGORY'))
        {
            Session::forget('CATEGORY');
        }
        $categoryData = Category::select('categories.*',DB::raw('COUNT(pizzas.category_id) as count'))
                                ->leftJoin('pizzas','pizzas.category_id', 'categories.category_id')
                                ->groupBy('categories.category_id')
                                ->paginate(5);
        // dd($categoryData->toArray());
        return view('admin.category.list')->with(['category' => $categoryData]);
    }

    // when click 'add category' in list page
    public function addCategory()
    {
        return view('admin.category.addCategory');
    }

    // create category
    public function createCategory(Request $request)
    {
        $data = [
            'category_name' => $request->category_name
        ];

        $validator = Validator::make($request->all(), [
            'category_name' => 'required',
        ]);

        if ($validator->fails()) {
            return back()
                    ->withErrors($validator)
                    ->withInput();
        }

        Category::create($data);
        return redirect()->route('admin#category')->with(['createSuccess' => 'Created successfully...']);

    }

    // delete category
    public function deleteCategory($id)
    {
        // dd($id);
        $data = Category::where('category_id',$id)->delete();
        return back()->with(['deleteSuccess' => 'Deleted Successfully...']);
        // dd($data->toArray());
    }

    // when click edit in category list
    public function editCategory($id)
    {
        $data = Category::where('category_id', $id)->first();
        // dd($data->toArray());
        return view('admin.category.updateCategory')->with(['category' => $data]);
    }

    // update category
    public function updateCategory(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_name' => 'required',
        ]);

        if ($validator->fails()) {
            return back()
                    ->withErrors($validator)
                    ->withInput();
        }

        $data = [
            'category_name' => $request->category_name
        ];
        Category::where('category_id', $request->id)->update($data);
        return redirect()->route('admin#category')->with(['updateSuccess' => 'Updated Successfully...']);
    }

    // to see details of category items
    public function categoryItem($id)
    {
        $data = Pizza::select('pizzas.*','c.category_name')
                     ->leftJoin('categories as c', 'pizzas.category_id', 'c.category_id')
                     ->where('pizzas.category_id', $id)->paginate(5);
        return view('admin.category.item')->with(['pizza' => $data]);
    }

    // search category
    public function searchCategory(Request $request)
    {
        // Session::put('SEARCH_CATEGORY',$request->searchCategory);
        // dd(Session::GET('SEARCH_CATEGORY'));
        $data = Category::select('categories.*',DB::raw('COUNT(pizzas.category_id) as count'))
                        ->leftJoin('pizzas','pizzas.category_id', 'categories.category_id')
                        ->where('category_name', 'like' , '%'.$request->searchCategory.'%')
                        ->groupBy('categories.category_id')
                        ->paginate(5);

        Session::put('CATEGORY', $request->searchCategory);
        $data->appends($request->all());
        return view('admin.category.list')->with(['category' => $data]);
    }

    // category download
    public function categoryDownload()
    {
        if(Session::has('CATEGORY')){
            $category = Session::get('CATEGORY');
            $category = Category::select('categories.*',DB::raw('COUNT(pizzas.category_id) as count'))
            ->leftJoin('pizzas','pizzas.category_id', 'categories.category_id')
            ->where('category_name', 'like' , '%'.$category.'%')
            ->groupBy('categories.category_id')
            ->get();
        }
        else{

            $category = Category::select('categories.*',DB::raw('COUNT(pizzas.category_id) as count'))
            ->leftJoin('pizzas','pizzas.category_id', 'categories.category_id')
            ->groupBy('categories.category_id')
            ->get();
        }

        $csvExporter = new \Laracsv\Export();

        $csvExporter->build($category, [
            'category_id' => 'ID',
            'category_name' => 'Name',
            'count' => 'Prodcut Count',
            'created_at' => 'Created Date',
            'updated_at' => 'Updated Date',
        ]);

        $csvReader = $csvExporter->getReader();
        $csvReader->setOutputBOM(\League\Csv\Reader::BOM_UTF8);

        $filename = 'CateogryList.csv';

        return response((string) $csvReader)
            ->header('Content-Type', 'text/csv; charset=UTF-8')
            ->header('Content-Disposition', 'attachment; filename="'.$filename.'"');
    }
}
