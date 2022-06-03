<?php

namespace App\Http\Controllers\API;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;

class ApiController extends Controller
{
    public function categoryList()
    {
        $category = Category::get();
        $response = [
            'status' => "success",
            'data' => $category
        ];
       return Response::json($response);
    }

    public function createCategory(Request $request)
    {
        //  dd($request->header('Key'));
        // dd($request->all());

        $category = [
            'category_name' => $request->name,
            'created_at' => $request->created_at,
            'updated_at' => $request->updated_at,
        ];

        Category::create($category);

        $response = [
            'status' => 200,
            'message' => 'success',
        ];

        return Response::json($response);
    }

    public function details(Request $request)
    {

    }
}
