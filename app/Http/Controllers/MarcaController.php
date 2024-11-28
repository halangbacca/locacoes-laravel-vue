<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Ramsey\Uuid\Type\Integer;

class MarcaController extends Controller
{
    public function __construct(Marca $marca)
    {
        $this->marca = $marca;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return response()->json($this->marca::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate($this->marca->rules(), $this->marca->feedback());

        $imagem = $request->file('imagem');
        $imagem_urn = $imagem->store('marcas', 'public');

        $marca = $this->marca->create([
            'nome' => $request->input('nome'),
            'imagem' => $imagem_urn,
        ]);
        return response()->json($marca, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param Integer
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $marca = $this->marca->with('modelos')->find($id);

        if ($marca === null) {
            return response()->json(['erro' => 'Nenhum registro encontrado.'], 404);
        }

        return response()->json($marca, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param Integer
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $marca = $this->marca->find($id);

        if ($marca === null) {
            return response()->json(['erro' => 'Nenhum registro encontrado.'], 404);
        }

        if ($request->method() === 'PATCH') {
            $regrasDinamicas = array();

            foreach ($marca->rules() as $input => $rule) {
                if (array_key_exists($input, $request->all())) {
                    $regrasDinamicas[$input] = $rule;
                }
            }
            $request->validate($regrasDinamicas, $marca->feedback());
        } else {
            $request->validate($marca->rules(), $marca->feedback());
        }

        if ($request->file('imagem') != null) {
            Storage::disk('public')->delete($marca->imagem);
        }

        $imagem = $request->file('imagem');
        $imagem_urn = $imagem->store('marcas', 'public');

        $marca->update([
            'nome' => $request->input('nome'),
            'imagem' => $imagem_urn
        ]);

        return response()->json($marca, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Integer
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $marca = $this->marca->find($id);

        if ($marca === null) {
            return response()->json(['erro' => 'Nenhum registro encontrado.'], 404);
        }

        Storage::disk('public')->delete($marca->imagem);

        $marca->delete();
        return response()->json('Registro removido com sucesso.', 204);
    }
}
