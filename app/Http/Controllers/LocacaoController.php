<?php

namespace App\Http\Controllers;

use App\Models\Locacao;
use App\Repositories\LocacaoRepository;
use Illuminate\Http\Request;

class LocacaoController extends Controller
{
    public function __construct(Locacao $locacao)
    {
        $this->locacao = $locacao;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $locacaoRepository = new LocacaoRepository($this->locacao);

        if ($request->has('filtro')) {
            $locacaoRepository->filtro($request->input('filtro'));
        }

        if ($request->has('atributos')) {
            $locacaoRepository->selectAtributos($request->input('atributos'));
        }

        return response()->json($locacaoRepository->getResultado(), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate($this->locacao->rules());

        $locacao = $this->locacao->create([
            'cliente_id' => $request->input('cliente_id'),
            'carro_id' => $request->input('carro_id'),
            'data_inicio_periodo' => $request->input('data_inicio_periodo'),
            'data_final_previsto_periodo' => $request->input('data_final_previsto_periodo'),
            'data_final_realizado_periodo' => $request->input('data_final_realizado_periodo'),
            'valor_diaria' => $request->input('valor_diaria'),
            'km_inicial' => $request->input('km_inicial'),
            'km_final' => $request->input('km_final'),
        ]);

        return response()->json($locacao, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param Integer
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $locacao = $this->locacao->find($id);

        if ($locacao === null) {
            return response()->json(['erro' => 'Nenhum registro encontrado.'], 404);
        }

        return response()->json($locacao, 200);
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
        $locacao = $this->locacao->find($id);

        if ($locacao === null) {
            return response()->json(['erro' => 'Nenhum registro encontrado.'], 404);
        }

        if ($request->method() === 'PATCH') {
            $regrasDinamicas = array();

            foreach ($locacao->rules() as $input => $rule) {
                if (array_key_exists($input, $request->all())) {
                    $regrasDinamicas[$input] = $rule;
                }
            }
            $request->validate($regrasDinamicas);
        } else {
            $request->validate($locacao->rules());
        }

        $locacao->fill($request->all());
        $locacao->save();

        return response()->json($locacao, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Integer
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $locacao = $this->locacao->find($id);

        if ($locacao === null) {
            return response()->json(['erro' => 'Nenhum registro encontrado.'], 404);
        }

        $locacao->delete();
        return response()->json('Registro removido com sucesso.', 204);
    }
}
