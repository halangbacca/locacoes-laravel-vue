<?php

namespace App\Http\Controllers;

use App\Models\Modelo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ModeloController extends Controller
{

    public function __construct(Modelo $modelo)
    {
        $this->modelo = $modelo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return response()->json($this->modelo::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate($this->modelo->rules(), $this->modelo->feedback());

        $imagem = $request->file('imagem');
        $imagem_urn = $imagem->store('modelos', 'public');

        $modelo = $this->modelo->create([
            'marca_id' => $request->input('marca_id'),
            'nome' => $request->input('nome'),
            'imagem' => $imagem_urn,
            'numero_portas' => $request->input('numero_portas'),
            'lugares' => $request->input('lugares'),
            'air_bag' => $request->input('air_bag'),
            'abs' => $request->input('abs'),
        ]);
        return response()->json($modelo, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Modelo $modelo
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $modelo = $this->modelo->with('marca')->find($id);

        if ($modelo === null) {
            return response()->json(['erro' => 'Nenhum registro encontrado.'], 404);
        }

        return response()->json($modelo, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Modelo $modelo
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $modelo = $this->modelo->find($id);

        if ($modelo === null) {
            return response()->json(['erro' => 'Nenhum registro encontrado.'], 404);
        }

        if ($request->method() === 'PATCH') {
            $regrasDinamicas = array();

            foreach ($modelo->rules() as $input => $rule) {
                if (array_key_exists($input, $request->all())) {
                    $regrasDinamicas[$input] = $rule;
                }
            }
            $request->validate($regrasDinamicas, $modelo->feedback());
        } else {
            $request->validate($modelo->rules(), $modelo->feedback());
        }

        if ($request->file('imagem') != null) {
            Storage::disk('public')->delete($modelo->imagem);
        }

        $imagem = $request->file('imagem');
        $imagem_urn = $imagem->store('marcas', 'public');

        $modelo->update([
            'marca_id' => $request->input('marca_id'),
            'nome' => $request->input('nome'),
            'imagem' => $imagem_urn,
            'numero_portas' => $request->input('numero_portas'),
            'lugares' => $request->input('lugares'),
            'air_bag' => $request->input('air_bag'),
            'abs' => $request->input('abs'),
        ]);

        return response()->json($modelo, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Modelo $modelo
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $modelo = $this->modelo->find($id);

        if ($modelo === null) {
            return response()->json(['erro' => 'Nenhum registro encontrado.'], 404);
        }

        Storage::disk('public')->delete($modelo->imagem);

        $modelo->delete();
        return response()->json('Registro removido com sucesso.', 204);
    }
}
