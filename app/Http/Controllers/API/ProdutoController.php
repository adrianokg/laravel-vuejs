<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Produto;
use Validator;

class ProdutoController extends Controller
{
    public $successStatus = 200;

    public function index()
    {
        $produtos = Produto::with('categoria')->get();
        return response()->json(['success'=> $produtos], $this->successStatus);
    }

    public function store(Request $request)
    {
        $messages = [
            'required' => 'O campo :attribute é obrigatório.'
        ];
        $validator = Validator::make($request->except('foto'), [
            'nome' => 'required'
        ], $messages);
        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }
        $input = $request->all();
        $model = Produto::create($input);
        if($model) {
            if ($request->hasFile('foto') && $request->file('foto')->isValid()) {
                $imagem = $request->file('foto');
                $name = uniqid(date('HisYmd'));
                $extension = $imagem->extension();
                $nameFile = "{$name}.{$extension}";
                $imagem->storeAs('produto', $nameFile);
                $model->foto = $nameFile;
                $model->save();
            }
        }
        return response()->json(['success'=> $model], $this->successStatus);
    }

    public function show($id)
    {
        $item = Produto::find($id);

        if(!$item) {
            return response()->json([
                'message'   => 'Not found',
            ], 404);
        }

        return response()->json(['success'=> $item], $this->successStatus);
    }

    public function update(Request $request, $id)
    {
        $item = Produto::find($id);

        if(!$item) {
            return response()->json([
                'message'   => 'Not found',
            ], 404);
        }

        if ($request->hasFile('foto') && $request->file('foto')->isValid()) {
            $imagem = $request->file('foto');
            $name = uniqid(date('HisYmd'));
            $extension = $imagem->extension();
            $nameFile = "{$name}.{$extension}";
            $upload = $imagem->storeAs('produto', $nameFile);
            $item->foto = $nameFile;
            $item->save();
        } else {
            $item->fill($request->all());
            $item->save();
        }


        return response()->json(['success'=> $item], $this->successStatus);
    }

    public function destroy($id)
    {
        $item = Produto::find($id);

        if(!$item) {
            return response()->json([
                'message'   => 'Not found',
            ], 404);
        }

        $item->delete();

        return response()->json(['success'=> 'Success'], $this->successStatus);
    }
}
