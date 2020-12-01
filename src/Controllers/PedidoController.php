<?php
    namespace App\Controllers;
    use Psr\Http\Message\ResponseInterface as Response;
    use Psr\Http\Message\ServerRequestInterface as Request;

    use App\Models\Pedido;
    use App\Models\PedidoMenus;
    use App\Models\Menu;
    use App\Models\tipos\EstadoPedido;
  

    class PedidoController {



        //se deberia aplicar una interface para que quede bien
        public function getAll(Request $request, Response $response, $args) {

            $rta = Pedido::get();

            $response->getBody()->write(json_encode($rta));
            return $response;
        }


        public function getOne(Request $request, Response $response, $args) {
            $parseBody= $request->getParsedBody();
            if(isset($parseBody['empleado_id'])&&isset($parseBody['tiempo'])&&isset($parseBody['estado'])&&isset($parseBody['precio'])){
                $pedidoCoincidenteEmail = Pedido::where([['empleado_id','=',$parseBody['empleado_id']],['estado','=',$parseBody['estado']],['precio','=',$parseBody['precio']]])->first();
                if($pedidoCoincidenteEmail != null){
                    $pedidoCoincidenteEmail = $pedidoCoincidenteEmail->attributesToArray();
                    $pedido = new Pedido();
                    $pedido->empleado_id= $pedidoCoincidenteEmail['empleado_id'];
                    $pedido->tiempo= $pedidoCoincidenteEmail['tiempo'];
                    $pedido->estado= $pedidoCoincidenteEmail['estado'];
                    $pedido->precio= $pedidoCoincidenteEmail['precio'];
                    $response->getBody()->write(json_encode($pedido));
                }
            }else{
                $response->getBody()->write("Faltan elementos para realizar la busqueda");
            }
            return $response;
        }

        public function addOne(Request $request,Response  $response, $args) {
            
            $parseBody= $request->getParsedBody();
             if(isset($parseBody['empleado_id'])&&isset($parseBody['listaMenu'])){        
                $pedido = new Pedido();
                $pedido->empleado_id= $parseBody['empleado_id'];
                $pedido->estado= EstadoPedido::PENDIENTE;
                $listaMenus = json_decode($parseBody['listaMenu']); //esto tiene que ser un json con id y su precio; [{"codigo":"1","precio":"250"},{"codigo":"2","precio":"200"}]
                $response->getBody()->write(json_encode($pedido->save()));
                foreach ($listaMenus as $key => $value) {
                    if($key != null){
                        $menu = Menu::where('codigo',$key)->first();
                        if($menu!=null){
                            $pedidos = new PedidoMenus();
                            $pedidos->pedido_id= $pedido->id;
                            $pedidos->menu_id = $menu->id;
                            $pedidos->estado =EstadoPedido::PENDIENTE;
                            $pedidos->save();
                        }
                    }                
                }
             }else{
                $response->getBody()->write("Faltan elementos para dar de alta");
             }
            return $response;
        }

        public function consultarEstado(Request $request,Response  $response, $args) {
                $chequearPedidos = PedidoMenus::where('pedido_id',$args['id']);
                if($chequearPedidos != null ){
                    $finalizado = false;
                    foreach ($chequearPedidos as $key => $value) {
                        $pedido = $value->attributesToArray();
                        if($pedido->estado == 'listo' ){
                            $finalizado = true;
                        }
                    }
                    if($finalizado){
                        $cambiarPedido = Pedido::find($args['id']);
                        $cambiarPedido->estado=EstadoPedido::LISTO;
                        $response->getBody()->write(json_encode($cambiarPedido->save()));
                    }
                }

                              
                
    
            return $response;
        }

        public function updateOne(Request $request,Response  $response, $args) {
            $parseBody= $request->getParsedBody();
            if(isset($parseBody['estado'])&&isset($parseBody['empleado_id'])){
                $findPedido = Pedido::find($args['id']);
                if($findPedido!=null){
                    $findPedido = $findPedido->attributesToArray();
                    $pedido = new Pedido();
                    $pedido->empleado_id= $findPedido['empleado_id'];
                    $pedido->tiempo= $findPedido['tiempo'];
                    $pedido->estado= $findPedido['estado'];
                    $pedido->precio= $findPedido['precio'];
                    $pedido->estado= $parseBody['estado'];
                    $response->getBody()->write(json_encode($pedido->save()));
                }
            }else{
                echo "Faltan elementos para dar de alta";
                $response = "Error";
            }
            return $response;
        }

        public function deleteOne(Request $request, Response $response, $args) {
            $pedido =Pedido::find($args['id']);
            $rta =$pedido->delete();
            $response->getBody()->write(json_encode($rta));
            return $response;
        }


        

        

    }




?>