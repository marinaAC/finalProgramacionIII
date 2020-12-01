<?php
    namespace App\Controllers;
    use Psr\Http\Message\ResponseInterface as Response;
    use Psr\Http\Message\ServerRequestInterface as Request;

    use App\Models\Comanda;
    use App\Models\Pedido;
    use App\Models\tipos\EstadoCobrado;
    use App\Validations\Validaciones;
    use App\Security\Auth;
  

    class ComandaController {



        //se deberia aplicar una interface para que quede bien
        public function getAll(Request $request, Response $response, $args) {

            $rta = Comanda::get();

            $response->getBody()->write(json_encode($rta));
            return $response;
        }


        public function getOne(Request $request, Response $response, $args) {

            $parseBody= $request->getParsedBody();
            if(isset($parseBody['cliente_id'])&&(isset($parseBody['pedido_id'])||isset($parseBody['nombre']))){
                $comandaCoincidenteEmail = Comanda::where('email',$parseBody['email'])->first()->attributesToArray();
                if($comandaCoincidenteEmail!=null){
                    $comanda = new Comanda();
                    $comanda->email= $comandaCoincidenteEmail['email'];
                    $comanda->nombre= $comandaCoincidenteEmail['nombre'];
                    $comanda->clave= $comandaCoincidenteEmail['clave'];
                    $comanda->tipo= $comandaCoincidenteEmail['tipo'];
                   
                }
                
            }else{
                echo "Faltan elementos para dar de alta";
                $response = "Error";
            }
            return $response;
        }

        public function addOne(Request $request,Response  $response, $args) {
            $parseBody= $request->getParsedBody();
            if(isset($parseBody['cliente_id'])&&isset($parseBody['pedido_id'])){
                $comanda = new Comanda;
                $comanda->cliente_id= $parseBody['cliente_id'];
                $comanda->pedido_id= $parseBody['pedido_id'];
                $comanda->estado= EstadoCobrado::SIN_COBRAR;
                $pedido = Pedido::find($parseBody['pedido_id']);
                if($pedido != null){
                    $pedido = $pedido->attributesToArray();
                    $totalPrecio = $pedido->precio+120;
                }
                $comanda->total = $totalPrecio;
                $response->getBody()->write(json_encode($comanda->save()));
            }else{
                $response->getBody()->write("Faltan elementos para dar de alta");
            }
            return $response;
        }

        public function updateOne(Request $request,Response  $response, $args) {
            $parseBody= $request->getParsedBody();
            if(isset($parseBody['cliente_id'])&&isset($parseBody['estado'])&&isset($parseBody['pedido_id'])){
                $comanda = Comanda::where([['cliente_id','=',$parseBody['cliente_id']],['pedido_id','=',$parseBody['pedido_id']]])->first();
                if($comanda != null){
                    $comanda = $comanda->attributesToArray();
                    $comanda->estado= $parseBody['estado'];
                    $response->getBody()->write(json_encode($comanda->save()));
                }
                
            }else{
                $response->getBody()->write("Faltan elementos para updatear el envio");
            }
            return $response;
        }

        public function deleteOne(Request $request, Response $response, $args) {
            $comanda =Comanda::find($args['id']);
            $rta =$comanda->delete();
            $response->getBody()->write(json_encode($rta));
            return $response;
        }


        

        

    }




?>