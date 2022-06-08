<?php

namespace App\Http\Controllers\API;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\Response;
use LDAP\Result;
use PhpParser\Node\Stmt\Catch_;

use function PHPUnit\Framework\isEmpty;

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

    public function details($id)
    {
        // $id = $request->id;
        $data = Category::where('category_id', $id)->first();
        if(!empty($data)){
            return Response::json([
                'status' => 200,
                'message' => 'success',
                'data' => $data
            ]);
        }
        else{
            return Response::json([
                'status' => 200,
                'message' => 'fail',
                'data' => $data
            ]);
        }
    }

    public function deleteCategory($id)
    {
        $data = Category::where('category_id', $id)->first();
        if(empty($data)){
            return Response::json([
                'result' => 200,
                'message' => 'There is no data'
            ]);
        }
         Category::where('category_id', $id)->delete($data);
         return Response::json([
             'result' => 200,
             'message' => 'success'
         ]);
    }

    public function updateCategory(Request $request)
    {
        $category = [
            'category_id' => $request->id,
            'category_name' => $request->name,
            'updated_at' => Carbon::now(),
        ];

        $check = Category::where('category_id', $request->id)->first();

        if(!empty($check)){
            Category::where('category_id', $request->id)->update($category);
            return Response::json([
                'result' => 200,
                'message' => 'success'
            ]);
        }
        else{
            return Response::json([
                'result' => 200,
                'message' => 'There is no data to update.'
            ]);
        }
    }
}
