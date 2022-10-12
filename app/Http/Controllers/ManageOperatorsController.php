<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ManageOperators;
use Exception;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Hash;

class ManageOperatorsController extends Controller
{
    public function operatorCounter(){
        $counter = ManageOperators::count();
        return response()->json([
            'totalOperators' => $counter
        ]);
    }
    public function fetchOperators()
    {
        $operators = ManageOperators::all();
        return response()->json([
            'operators' => $operators
        ]);
    }

    public function addOperator(Request $request){
        try{
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'age' => ['required', 'string', 'max:255'],
                'mobile' => ['required', 'string', 'max:50'],
                'password' => ['required', Rules\Password::defaults()]
            ]);
            $user = new ManageOperators();
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $user->age = $request->input('age');
            $user->mobile = $request->input('mobile');
            $user->password = Hash::make($request->input('password'));
            $user->save();
            return response()->json([
                'status' => 200,
                'message' => 'Operator added'
            ]);
        }catch(Exception $e){
            return response()->json([
                'status' => 400,
                'error' => $e->getMessage()
            ]);
        }
    }

    public function modifyOperator($id){
        $operatorID = ManageOperators::find($id);
        if($operatorID){
            return response()->json([
                'status' => 200,
                'operator' => $operatorID
            ]);
        }else{
            return response()->json([
                'status' => 400,
                'error' => 'Operator not found'
            ]);
        }
    }

    public function saveModifiedOperator(Request $request, $id){
        try{
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'age' => ['required', 'string', 'max:255'],
                'mobile' => ['required', 'string', 'max:50'],
                'password' => ['required', Rules\Password::defaults()]
            ]);
            $user = ManageOperators::find($id);
            $user->name = $request->input('name');
            $user->age = $request->input('age');
            $user->mobile = $request->input('mobile');
            $user->password = Hash::make($request->input('password'));
            $user->update();
            return response()->json([
                'status' => 200,
                'message' => 'Operator updated'
            ]);
        }catch(Exception $e){
            return response()->json([
                'status' => 400,
                'error' => $e->getMessage()
            ]);
        }
    }

    public function deleteOperator($id){
        $user = ManageOperators::findOrFail($id);
        if($user->delete()){
            return response()->json([
                'status' => 200,
                'message' => 'Operator deleted'
            ]);
        }else{
            return response()->json([
                'status' => 400,
                'error' => 'Something wrong'
            ]);
        }
    }
}


