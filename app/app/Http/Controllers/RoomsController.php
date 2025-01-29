<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class RoomsController extends Controller
{

    private const VALIDATION_RULES = [
        'id' =>['required','exists:rooms,id'],
        'name' => ['regex:/^[A-Za-z]\d{3}$/','string','unique:rooms,name'],
        'type' => ['string'],
        'price' => ['numeric','min:0'],
    ];


    public function index(){
        $sql = 'SELECT * FROM rooms';
        $rooms = DB::select($sql);
        return response()->json([
            'data' => $rooms,
            'message' => count($rooms) > 0 ? "Exito":"No existen habitaciones de momeno"
        ], 200);
    }

    public function show($id){
        $sql = 'SELECT * FROM rooms WHERE id = ? LIMIT 1';
        $room = DB::selectOne($sql,[$id]);
        $exists = $room ? true : false;
        return response()->json([
            'data' => $room,
            'message' => $exists ? "Exito" : "Habitacion no encontrada"
        ],$exists ? 200 : 404);
    }

    public function store(Request $req){
        $validator = Validator::make($req->all(),[
            'name' => array_merge(self::VALIDATION_RULES['name'],['required']),
            'type' => array_merge(self::VALIDATION_RULES['type'],['required']),
            'price' => array_merge(self::VALIDATION_RULES['price'],['required']),
        ]);

        if($validator->fails()){
            return response()->json([
                'message' => $validator->errors()
            ],400);
        }

        $sql = "
            INSERT INTO rooms
                (name, type, price, available, created_at, updated_at)
                VALUES (?, ?, ?, ?, ?, ?)
        ";
        $roomData = [
        'name' => $req->input('name'),
        'type' => $req->input('type'),
        'price' => $req->input('price'),
        'available' => true, // Marcar como disponible
        'created_at' => now(), // Fecha de creación
        'updated_at' => now(), // Fecha de actualización
        ];
        DB::insert($sql,array_values($roomData));

        return response()-> json([
            'data' => $roomData,
            'message' =>"Habitacion creada correctamente",
        ],200);

    }
    public function update($id, Request $req){

        $req->merge(['id' => $id]);
        $validator = Validator::make($req->all(),[
            'id' => self::VALIDATION_RULES['id'],
            'name' => array_merge(self::VALIDATION_RULES['name'], ['sometimes', 'unique:rooms,name,' .$id]),
            'type' => array_merge(self::VALIDATION_RULES['type'], ['sometimes']),
            'price' => array_merge(self::VALIDATION_RULES['price'], ['sometimes']),
        ]);

        if($validator->fails()){
            return response()->json([
                'message' => $validator->errors()
            ], 400);
        }

        $fieldsReq = $req->only(['name','type','price']);
        if(empty($fieldsReq)){
            return response()->json([
                'message' => 'No se proporcionaron datos para actualizar'
            ], 400);
        }

        $setClauses = [];
        $values = [];
        foreach ($fieldsReq as $field => $value) {
                $setClauses[] = "$field = ?";
                $values[] = $value;
        }
        $setClauses[] = "updated_at = ?";
        $values[] = now();
        $values[] = $id;

        $sql = "UPDATE rooms SET ".implode(',',$setClauses)." WHERE id = ?";

        DB::update($sql,$values);

        $room = DB::selectOne('SELECT * FROM rooms WHERE id = ? LIMIT 1',[$id]);

        return response()->json([
            'message' => 'Habitacion actualizada correctamente',
            'data' => $room
        ], 200);

    }

    public function destroy($id){
        $sql = 'DELETE FROM rooms WHERE id = ?';
        $deleted = DB::delete($sql,[$id]);
        return response()->json([
            'message' => $deleted > 0 ? 'Habitacion eliminada correctamente' : 'Habitacion no encontrada'
        ], $deleted > 0 ? 200 : 404);
    }
}
