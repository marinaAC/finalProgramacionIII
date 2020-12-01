<?php
    namespace App\Controllers;
    use Psr\Http\Message\ResponseInterface as Response;
    use Psr\Http\Message\ServerRequestInterface as Request;

    use App\Models\Empleado;
use App\Models\EmpleadoMenu;
use App\Models\Pedido;
    use App\Models\PedidoMenus;
    use App\Models\Menu;
    use App\Models\tipos\EstadoPedido;
  

    class EmpleadoController {



        //se deberia aplicar una interface para que quede bien
        public function getAll(Request $request, Response $response, $args) {

            $rta = Empleado::get();

            $response->getBody()->write(json_encode($rta));
            return $response;
        }


        public function getOne(Request $request, Response $response, $args) {

            $parseBody= $request->getParsedBody();
            if(isset($parseBody['tipo'])&&isset($parseBody['nombre'])){
                $empleadoEncontrado = Empleado::where([['nombre','=',$parseBody['nombre']],['tipo','=',$parseBody['tipo']]])->first()->attributesToArray();
                if($empleadoEncontrado!=null){
                    $empleado = new Empleado();
                    $empleado->nombre= $empleadoEncontrado['nombre'];
                    $empleado->tipo= $empleadoEncontrado['tipo'];
                    $response->getBody()->write(json_encode($empleado));
                }else{
                    $response->getBody()->write("Empleado no encontrado");
                }
            }else{
                $response->getBody()->write("Faltan elementos para dar de alta");
            }
            return $response;
        }

        public function addOne(Request $request,Response  $response, $args) {
            
            $parseBody= $request->getParsedBody();

             if(isset($parseBody['nombre'])&&isset($parseBody['tipo'])){
                $empleadoCoindicenteName = Empleado::where('nombre',$parseBody['nombre'])->first();
                if( $empleadoCoindicenteName != null){
                    $response->getBody()->write("Empleado repetido");
                    return $response;
                }
                $empleado = new Empleado;
                $empleado->nombre= $parseBody['nombre'];
                $empleado->tipo= $parseBody['tipo'];
                $response->getBody()->write(json_encode($empleado->save()));
             }else{
                $response->getBody()->write("Faltan elementos para dar de alta");
             }
            return $response;
        }

        public function updateOne(Request $request,Response  $response, $args) {

            $parseBody= $request->getParsedBody();
            if(isset($parseBody['nombre'])&&isset($parseBody['tipo'])){
                $empleadoEncontrado= Empleado::where([['nombre','=',$parseBody['nombre']],['tipo','=',$parseBody['tipo']]])->first()->attributesToArray();
                $empleado = new Empleado;
                $empleado->nombre= $empleadoEncontrado['nombre'];
                $empleado->tipo= $empleadoEncontrado['tipo'];
                $response->getBody()->write(json_encode($empleado->save()));
            }else{
                $response->getBody()->write("Faltan elementos para dar de alta");
            }
            return $response;
        }

        

        public function deleteOne(Request $request, Response $response, $args) {
            $empleado =Empleado::find($args['id']);
            $rta =$empleado->delete();
            $response->getBody()->write(json_encode($rta));
            return $response;
        }

        public function atenderPedido(Request $request,Response  $response, $args) {
            echo"estamos";
            $parseBody= $request->getParsedBody();
             if(isset($parseBody['pedido_id'])){ 
                $findPedido = Pedido::find($parseBody['pedido_id']); 
                $findEmpleado = Empleado::find($args['id']);      
                if($findPedido != null && $findEmpleado != null){
                    $menus = PedidoMenus::where('pedido_id',$parseBody['pedido_id'])->get();
                    if($menus != null){
                        foreach ($menus as $key => $value) {
                            $menuArray = $value->attributesToArray();
                            $findMenu = Menu::where('id',$menuArray['menu_id'])->first();
                            if($findMenu != null && $menuArray['estado']=='pendiente'){
                                $pedidoMenu = new PedidoMenus();
                                $pedidoMenu->menu_id = $menuArray['menu_id'];
                                $pedidoMenu->pedido_id =$menuArray['pedido_id'];
                                $pedidoMenu->estado = EstadoPedido::PREPARACION;
                                $pedidoMenu->save();
                                $EmpleadoMenu = new EmpleadoMenu();
                                $EmpleadoMenu->empleado_id = $args['id'];
                                $EmpleadoMenu->menu_id = $menuArray['menu_id'];
                                $EmpleadoMenu->save();
                            }else if($findMenu != null && $menuArray['estado']=='preparacion'){
                                $pedidoMenu = new PedidoMenus();
                                $pedidoMenu->menu_id = $menuArray['menu_id'];
                                $pedidoMenu->pedido_id =$menuArray['pedido_id'];
                                $pedidoMenu->estado = EstadoPedido::LISTO;
                                $pedidoMenu->save();
                                $EmpleadoMenu = new EmpleadoMenu();
                                $EmpleadoMenu->empleado_id = $args['id'];
                                $EmpleadoMenu->menu_id = $menuArray['menu_id'];
                                $EmpleadoMenu->save();
                            }
                        }
                        $response->getBody()->write(json_encode($EmpleadoMenu));
                    }

                }
             }else{
                $response->getBody()->write("Faltan elementos para dar de alta");
             }
            return $response;
        }
        

        

    }




?>