<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ManageItems;
use App\Models\ManageEnquiry;
use Exception;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class ManageItemsController extends Controller
{
    public function EnquiryCounter(){
        $counter = ManageEnquiry::count();
        return response()->json([
            'totalEnquiry' => $counter
        ]);
    }
    public function fetchEnquiry()
    {
        $enquiry = ManageEnquiry::all();
        $counter = ManageEnquiry::count();
        return response()->json([
            'enquiry' => $enquiry,
            'counter' => $counter,
        ]);
    }

    
    public function fetchItems()
    {
        $items = ManageItems::all();
        $itemcounter = ManageItems::count();
        return response()->json([
            'items' => $items,
            'itemcount'=>$itemcounter
        ]);
    }

    public function modifyItem($itemID){
        $iid = ManageItems::find($itemID);
        if($iid)
        {
            return response()->json([
                'status'=>200,
                'iid'=>$iid,
            ]);
        }
        else
        {
            return response()->json([
                'status' => 404,
                'message' => 'Item not found'
            ]);
        }
    }
    public function saveModifiedItem(Request $request, $id){
        $validator = Validator::make($request->all(),[
            'modifiedInputName' => 'required|max:100',
            'modifiedInputCategory' => 'required|max:20',
            'modifiedInputType' => 'required|max:20',
            'modifiedInputDetails' => 'required|max:300',
            'modifiedInputStatus' => 'required|max:20',
            'modifiedInputContact' => 'required|max:20',
            'modifiedItemImage' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);
        if($validator->fails()){
            return response()->json([
                'status' => 400,
                'errors' => 'Please enter valid data'
            ]);
        }else{
            try{
                if($request->hasFile('modifiedItemImage'))
                {
                    $file = $request->file('modifiedItemImage');
                    $filename = $file->getClientOriginalName();
                    //Storage::disk('task_img')->put($filename, file_get_contents($file));
                    $file->move('img', $filename);
                    
                }
            }catch(Exception $exception){
                return response()->json([
                    'status' => 404,
                    'exist_error' => $exception->getMessage()
                ]);
            }
            $manageitems = ManageItems::whereId($id)->update([
                'name' => $request->input('modifiedInputName'),
                'category' => $request->input('modifiedInputCategory'),
                'type' => $request->input('modifiedInputType'),
                'details' => $request->input('modifiedInputDetails'),
                'status' => $request->input('modifiedInputStatus'),
                'contact' => $request->input('modifiedInputContact'),
                'image' => $request->file('modifiedItemImage')->getClientOriginalName(),
            ]);

            return response()->json([
                'status' => 200,
                'message'=> 'Item updated successfully'
            ]);
        }
    }

    public function addItem(Request $request){
        $validator = Validator::make($request->all(),[
            'itemInputName' => 'required|max:100',
            'itemInputCategory' => 'required|max:20',
            'itemInputType' => 'required|max:20',
            'itemInputDetails' => 'required|max:300',
            'itemInputStatus' => 'required|max:20',
            'itemInputContact' => 'required|max:20',
            'itemImage' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);
        if($validator->fails()){
            return response()->json([
                'status' => 400,
                'errors' => '$validator->messages()'
            ]);
        }else{
            $item = new ManageItems;
            $item->name = $request->input('itemInputName');
            $item->category = $request->input('itemInputCategory');
            $item->type = $request->input('itemInputType');
            $item->details = $request->input('itemInputDetails');
            $item->status = $request->input('itemInputStatus');
            $item->contact = $request->input('itemInputContact');
            
            if($request->hasFile('itemImage'))
            {
                $file = $request->file('itemImage');
                $filename = $file->getClientOriginalName();
                $file->move('img/', $filename);
                $item->image = $request->file('itemImage')->getClientOriginalName();
            }
            $item->save();

            return response()->json([
                'status' => 200,
                'message'=> 'item added'
            ]);
        }
    }
    
    //delete item
    public function deleteItem($id){
        try{
            $delItem = ManageItems::findOrFail($id);
            if(file_exists(public_path('img/'.$delItem->image))){
                unlink(public_path('img/'.$delItem->image));
            }
            $delItem->delete();
            return response()->json([
                'status' => 200,
                'message' => 'Item deleted'
            ]);
        } catch (Exception $e){
            return response()->json([
                'status' => 400,
                'errors' => $e->getMessage()
            ]);
        }
    }


    public function itemCounter(){
        $counter = ManageItems::count();
        return response()->json([
            'totalItem' => 10
        ]);
    }

}

