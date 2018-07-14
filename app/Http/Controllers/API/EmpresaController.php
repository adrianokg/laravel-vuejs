<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Empresa;
use Validator;

class EmpresaController extends Controller
{
    public $successStatus = 200;

    public function index()
    {
        $empresas = Empresa::with('segmento')->get();
        return response()->json(['success'=> $empresas], $this->successStatus);
    }

    public function store(Request $request)
    {
        $messages = [
            'required' => 'O campo :attribute é obrigatório.'
        ];
        $validator = Validator::make($request->except(['logo', 'banner']), [
            'nome' => 'required'
        ], $messages);
        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }
        $input = $request->all();
        $model = Empresa::create($input);
        if($model) {
            if ($request->hasFile('logo') && $request->file('logo')->isValid()) {
                $imagem = $request->file('logo');
                $name = uniqid(date('HisYmd'));
                $extension = $imagem->extension();
                $nameFile = "{$name}.{$extension}";
                $imagem->storeAs('empresa/logo', $nameFile);
                $model->logo = $nameFile;
                $model->save();
            }
            if ($request->hasFile('banner') && $request->file('banner')->isValid()) {
                $imagem = $request->file('banner');
                $name = uniqid(date('HisYmd'));
                $extension = $imagem->extension();
                $nameFile = "{$name}.{$extension}";
                $imagem->storeAs('empresa/banner', $nameFile);
                $model->banner = $nameFile;
                $model->save();
            }
        }
        return response()->json(['success'=> $model], $this->successStatus);
    }

    public function show($id)
    {
        $item = Empresa::find($id);

        if(!$item) {
            return response()->json([
                'message'   => 'Not found',
            ], 404);
        }

        return response()->json(['success'=> $item], $this->successStatus);
    }

    public function update(Request $request, $id)
    {
        $item = Empresa::find($id);

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
        $item = Empresa::find($id);

        if(!$item) {
            return response()->json([
                'message'   => 'Not found',
            ], 404);
        }

        $item->delete();

        return response()->json(['success'=> 'Success'], $this->successStatus);
    }
}
