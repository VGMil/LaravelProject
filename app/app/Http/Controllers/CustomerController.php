<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    public function index(){
        $customer = DB::table('customers')->get();
        return response()->json($customer,200);
    }

    public function show($id){
        $customer = DB::table('customers')->where('id',$id)->first();
        if($customer){
            return response()->json($customer,200);
        }else{
            return response()->json([
                'message' => 'Cliente no encontrado'
            ],404);
        }
    }

    public function store(Request $req){
        $validator = Validator::make($req->all(),[
            'name' => 'required',
            'lastname' => 'required',
            'uuid' => 'required',
            'email' =>'required|email'
        ]);
        if($validator->fails()){
            return response()->json($validator->errors(),400);
        }
        $customerId = DB::table('customers')->insertGetId([
            'name' => $req->name,
            'last_name' => $req->lastname,
            'uuid' => $req->uuid,
            'email' => $req->email,
            'created_at' => now(),
            'updated_at' => now()
        ]);


        return response()->json([
            'message'=>
                'Cliente creado correctamente'
        ],201);
    }

    public function update($id,Request $req){
        $validator = Validator::make($req->all(),[

            'name' => 'required',
            'lastname' => 'required',
            'uuid' => 'required',
            'email' => 'required|email'
        ]);
        if($validator->fails()){
            return response()->json($validator->errors(),400);
        }
        $updateData = [
            'name' => $req->input('name'),
            'last_name' => $req->input('lastname'),
            'uuid' => $req->input('uuid'),
            'email' => $req->input('email'),
            'updated_at' => now()
        ];

        DB::table('customers')->where('id', $id)->update($updateData);

        return response()->json([
            'message' => 'Cliente actualizado correctamente',
               ], 200);
    }


    public function updatePartial($id,Request $req){

        $validator = Validator::make($req->all(),[
            'name' => '',
            'lastname' => '',
            'uuid' => '',
            'email' => 'email'
        ]);
        if($validator->fails()){
            return response()->json($validator->errors(),400);
        }

        $customer = DB::table('customers')->where('id',$id)->first();

        if (!$customer) {
            return response()->json([
                'message' => 'Cliente no encontrado'
            ],404);
        }

        $updateData = [];

        $keys = ['name', 'lastname', 'uuid', 'email'];
        foreach ($keys as $key) {
            if ($req->has($key)) {
                if ($req->input($key) != $customer->$key) {
                    $updateData[$key] = $req->input($key);
                }
            }
        }

        if (empty($updateData)) {
            return response()->json([
                'message' => 'No se realizaron cambios'
            ], 200);
        }

        $updateData['updated_at'] = now();

        DB::table('customers')->where('id', $id)->update($updateData);

        return response()->json([
            'message' => 'Cliente actualizado correctamente'
        ],200);
    }


    public function destroy($id){
        $customer = DB::table('customers')->where('id',$id)->first();
        if($customer){
            DB::table('customers')->where('id',$id)->delete();
            return response()->json([
                'message' => 'Cliente eliminado correctamente'
            ],200);
        }else{
            return response()->json([
                'message' => 'Cliente no encontrado'
            ],404);
        }
    }
}
