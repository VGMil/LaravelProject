<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
class BookingsController extends Controller
{
    private const VALIDATION_RULES =[
        'id' => ['exists:bookings,id'],
        'customer_id' => ['exists:customers,id'],
        'room_id' => ['exists:rooms,id','unique:bookings,room_id'],
        'check_in_date' => ['date','after_or_equal:today'],
        'check_out_date' => ['date','after:check_in_date'],
        'status' => ['in:pending,confirmed,canceled']
    ];

    public function index(){
        $sql = "SELECT * FROM bookings";
        $bookings = DB::select($sql);
        return response()->json([
            'data' => $bookings,
            "message"=> count($bookings) > 0?"Exito":"No se ha registrado reservas por el momento"
        ],200);
    }

    public function show($id){
        $sql = "SELECT * FROM bookings WHERE id = ? LIMIT 1";
        $booking = DB::selectOne($sql,[$id]);
        $exists = $booking ? true : false;
        return response()->json([
            'data' => $booking,
            'message' => $exists ? "Exito" : "Reserva no encontrada"
        ],$exists ? 200 : 404);
    }

    public function store(Request $req){
        $validator = Validator::make($req->all(),[
            'customer_id' => array_merge(self::VALIDATION_RULES['customer_id'],['required']),
            'room_id' => array_merge(self::VALIDATION_RULES['room_id'],['required']),
            'check_in_date' => array_merge(self::VALIDATION_RULES['check_in_date'],['required']),
            'check_out_date' => array_merge(self::VALIDATION_RULES['check_out_date'],['required']),
            'status' => array_merge(self::VALIDATION_RULES['status'],['required']),
        ]);

        if($validator->fails()){
            return response()->json([
                'message' => $validator->errors()
            ],400);
        }

        $sql = "
            INSERT INTO bookings
                (customer_id, room_id, check_in_date, check_out_date, status, created_at, updated_at)
        VALUES (?, ?, ?, ?, ?, ?, ?)
        ";

        $bookgingData = [
            'customer_id' => $req->input('customer_id'),
            'room_id' => $req->input('room_id'),
            'check_in_date' => $req->input('check_in_date'),
            'check_out_date' => $req->input('check_out_date'),
            'status' => $req->input('status'),
            'created_at' => now(),
            'updated_at' => now()
        ];

        DB::insert($sql,array_values($bookgingData));
        return response()->json([
            'data' => $bookgingData,
            'message' => "Reserva creada correctamente"
        ],200);
    }

    public function update($id, Request $req){
        $req->merge(['id' => $id]);

        $validator = Validator::make($req->all(),[
            'id' => self::VALIDATION_RULES['id'],
            'customer_id' => array_merge(self::VALIDATION_RULES['customer_id'],['sometimes']),
            'room_id' => array_merge(self::VALIDATION_RULES['room_id'],['sometimes']),
            'check_in_date' => array_merge(self::VALIDATION_RULES['customer_id'],['sometimes']),
            'check_out_date' => array_merge(self::VALIDATION_RULES['customer_id'],['sometimes']),
            'status' => array_merge(self::VALIDATION_RULES['customer_id'],['sometimes']),
        ]);

        if($validator->fails()){
            return response()->json([
                'message' => $validator->errors()
            ],400);
        }

        $fieldsReq = $req->only(['customer_id','room_id','check_in_date','check_out_date','status']);

        if(empty($fieldsReq)){
            return response()->json([
                'message' => "No existen campos para actualizar"
            ],400);
        }

        $setClauses = [];
        $values = [];

        foreach($fieldsReq as $field => $value){
            $setClauses[] = "$field = ?";
            $values[] = $value;
        }
        $setClauses[] = "updated_at = ?";
        $values[] = now();
        $values[] = $id;

        $sql = 'UPDATE bookings SET' .implode(',',$setClauses). 'WHERE id = ?';
        DB::update($sql,$values);

        return response()->json([
            'message' => "Reserva actualizada correctamente",
        ],200);
    }

    public function destroy($id){
        $sql = "DELETE FROM bookings WHERE id = ?";

        $deleted = DB::delete($sql,[$id]);
        $exists = $deleted > 0;
        return response()->json([
            'message' => $exists ?"Reserva eliminada correctamente":"No hay reservas con ese id"
        ],$exists > 0 ?200:404);
    }
}
