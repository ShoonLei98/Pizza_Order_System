<?php

namespace App\Http\Controllers\Admin;

use App\Models\Contact;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    public function createContact(Request $request)
    {
        $contactData = $this->requestContactData($request);

        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required',
            'message' => 'required',
        ]);

        if($validator->fails()){
            return back()->withErrors($validator)
                         ->withInput();
        }

        Contact::create($contactData);
        return back()->with(['createSuccess' => 'Contact Created Successfully...']);
    }

    //contact list
    public function contactList()
    {
        $data = Contact::orderBy('contact_id', 'desc')
                       ->paginate(5);
        return view('admin.contact.contact_list')->with(['contact' => $data]);
    }

    // contact search
    public function searchContact(Request $request)
    {
        Session::put('SEARCH_CONTACT',$request->searchContact);
        $searchData = Contact::orderBy('contact_id', 'desc')
                             ->orWhere('name', 'like', '%'.$request->searchContact.'%')
                             ->orWhere('email', 'like', '%'.$request->searchContact.'%')
                             ->orWhere('message', 'like', '%'.$request->searchContact.'%')
                             ->paginate(5);
        $searchData->appends($request->all());
        // dd(count($searchData));

        if(count($searchData) == 0)
        {
            $emptyStatus = 0;
        }
        else
        {
            $emptyStatus = 1;
        }
        // dd($emptyStatus);

        return view('admin.contact.contact_list')->with(['contact' => $searchData, 'emptyStatus' => $emptyStatus]);
    }

    // get request data
    private function requestContactData($request)
    {
        return [
            'user_id' => auth()->user()->id,
            'name' => $request->name,
            'email' => $request->email,
            'message' => $request->message
        ];
    }
}
