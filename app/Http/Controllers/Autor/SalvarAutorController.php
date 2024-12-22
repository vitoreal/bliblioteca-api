<?php

namespace App\Http\Controllers\Autor;

use App\Http\Controllers\Controller;
use App\Services\AutorService;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;
use PDOException;

class SalvarAutorController extends Controller
{

    public function __construct(AutorService $service){
        $this->service = $service;
    }
    public function __invoke(Request $request): JsonResponse {

        try {

            $rules = [
                'nome' => 'required|string|max:40',
            ];

            $messages = [
                'nome.required' => 'Campo nome é obrigatório',
                'nome.max' => 'Campo nome não pode ultrapassar de 20 caracteres',
            ];

            $validator = Validator::make($request->all(), $rules, $messages);

            if ($validator->fails()) {

                $errors = $validator->errors();

                $retorno = [
                            'type' => 'ERROR',
                            'mensagem' => $errors->all()[0],
                            ];
                return response()->json($retorno, Response::HTTP_BAD_REQUEST);
            }

            $total = $this->service->verificarNomeExiste($request);

            if($total > 0){
                $retorno = ['type' => 'WARNING', 'mensagem' => 'Este registro já existe!'];
                return response()->json($retorno, Response::HTTP_OK);
            }

            $acao = ['cadastrado', 'cadastrar'];
            if($request->id){
                $acao = ['alterado', 'alterar'];
            }

            $resultado = $this->service->salvar($request);

            if($resultado === null){
                $retorno = ['type' => 'ERROR', 'mensagem' => 'Não foi possível '.$acao[1].' o dado!'];
                return response()->json($retorno, Response::HTTP_BAD_REQUEST);
            } else {
                $retorno = ['type' => 'SUCESSO', 'mensagem' => 'Registro '.$acao[0].' com sucesso!'];
                return response()->json($retorno, Response::HTTP_OK);
            }

        } catch (QueryException $e ) {
            $retorno = [ 'type' => 'ERROR', 'mensagem' => 'Não foi possível realizar a sua solicitação!', 'ERRO' => $e->getMessage() ];
            return response()->json($retorno, Response::HTTP_BAD_REQUEST);

        } catch (PDOException $e ) {
            $retorno = [ 'type' => 'ERROR', 'mensagem' => 'Não foi possível realizar a sua solicitação!', 'ERRO' => $e->getMessage(), 'get_class' => get_class($e) ];
            return response()->json($retorno, Response::HTTP_BAD_REQUEST);

        }  catch (Exception $e ) {
            $retorno = [ 'type' => 'ERROR', 'mensagem' => 'Não foi possível realizar a sua solicitação!', 'ERRO' => get_class($e) ];
            return response()->json($retorno, Response::HTTP_BAD_REQUEST);

        } 
    }
}
