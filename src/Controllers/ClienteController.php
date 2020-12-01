<?php
    namespace App\Controllers;
    use Psr\Http\Message\ResponseInterface as Response;
    use Psr\Http\Message\ServerRequestInterface as Request;

    use App\Models\Cliente;
    use App\Validations\Validaciones;
    use App\Security\Auth;
  

    class ClienteController {



        //se deberia aplicar una interface para que quede bien
        public function getAll(Request $request, Response $response, $args) {
            $rta = Cliente::get();
            $response->getBody()->write(json_encode($rta));
            return $response;
        }


        public function getOne(Request $request, Response $response, $args) {
            $parseBody= $request->getParsedBody();
            if(isset($parseBody['nombre'])&&isset($parseBody['clave'])){
                $clienteCoincidenteNombre = Cliente::where('nombre',$parseBody['nombre'])->first();
                if($clienteCoincidenteNombre!=null){
                    $cliente = new Cliente();
                    $cliente->nombre= $clienteCoincidenteNombre['nombre'];
                    $response->getBody()->write(json_encode($cliente));
                }else{
                    $response->getBody()->write("Cliente no encontrado");
                }
                
            }else{
                $response->getBody()->write("Faltan elementos para poder realizar la busqueda");
            }
            return $response;
        }

        public function addOne(Request $request,Response  $response, $args) {
            
            $parseBody= $request->getParsedBody();

             if(Validaciones::validName($parseBody['nombre'])){
                 $clienteConverEmail = Cliente::where('nombre',$parseBody['nombre'])->first();
                 if($clienteConverEmail !=null){
                     $response->getBody()->write("El cliente ya se encuentra dado de alta");
                     return $response;
                }
                $cliente = new Cliente;
                $cliente->nombre= $parseBody['nombre'];
                $response->getBody()->write(json_encode($cliente->save()));
             }else{
                $response->getBody()->write("Faltan elementos para dar de alta");
             }
            return $response;
        }

        public function updateOne(Request $request,Response  $response, $args) {

            $parseBody= $request->getParsedBody();
            if(isset($parseBody['nombre'])){
                $clienteEncontrado=Cliente::find($args['id']);
                if($clienteEncontrado != null ){
                    
                    $clienteEncontrado->nombre= $parseBody['nombre'];
                    $response->getBody()->write(json_encode($clienteEncontrado->save()));
                }else{
                    $response->getBody()->write("Cliente no encontrado");
                }
            }else{
                $response->getBody()->write("Faltan elementos para updatear");
            }
            return $response;
        }

        public function deleteOne(Request $request, Response $response, $args) {
            $cliente =Cliente::find($args['id']);
            $rta =$cliente->delete();
            $response->getBody()->write(json_encode($rta));
            return $response;
        }


        

        

    }




?>