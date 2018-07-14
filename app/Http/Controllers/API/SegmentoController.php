<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Segmento;
use Validator;

class SegmentoController extends Controller
{
    public $successStatus = 200;

    public function index()
    {
        $segmentos = Segmento::all();
        return response()->json(['success'=> $segmentos], $this->successStatus);
    }

    public function store(Request $request)
    {
        $messages = [
            'required' => 'O campo :attribute é obrigatório.'
        ];
        $validator = Validator::make($request->all(), [
            'nome' => 'required'
        ], $messages);
        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }
        $input = $request->all();
        $model = Segmento::create($input);
        return response()->json(['success'=> $model], $this->successStatus);
    }

    public function show($id)
    {
        $item = Segmento::find($id);

        if(!$item) {
            return response()->json([
                'message'   => 'Not found',
            ], 404);
        }

        return response()->json(['success'=> $item], $this->successStatus);
    }

    public function update(Request $request, $id)
    {
        $item = Segmento::find($id);

        if(!$item) {
            return response()->json([
                'message'   => 'Not found',
            ], 404);
        }

        $item->fill($request->all());
        $item->save();

        return response()->json(['success'=> $item], $this->successStatus);
    }

    public function destroy($id)
    {
        $item = Segmento::find($id);

        if(!$item) {
            return response()->json([
                'message'   => 'Not found',
            ], 404);
        }

        $item->delete();

        return response()->json(['success'=> 'Success'], $this->successStatus);
    }
}
