<?php
    namespace App\Controllers;
    use Psr\Http\Message\ResponseInterface as Response;
    use Psr\Http\Message\ServerRequestInterface as Request;

    use App\Models\Menu;
    use App\Models\tipos\SectorPedido;
    use App\Validations\Validaciones;
    use App\Security\Auth;
  

    class MenuController {

        public function getAll(Request $request, Response $response, $args) {
            $rta = Menu::get();
            $response->getBody()->write(json_encode($rta));
            return $response;
        }


        public function getOne(Request $request, Response $response, $args) {
            $parseBody= $request->getParsedBody();
            if(isset($parseBody['codigo'])&&isset($parseBody['descripcion'])&&isset($parseBody['precio'])){
                    $menu = Menu::where('codigo',$parseBody['email'])->first();
                    $menu = new Menu();
                    $menu->codigo= $parseBody['codigo'];
                    $menu->descripcion= $parseBody['descripcion'];
                    $menu->precio= $parseBody['precio'];
                    $response->getBody()->write(json_encode($menu));   
            }else{
                $response->getBody()->write("Faltan elementos para dar de alta");
            }
            return $response;
        }

        public function addOne(Request $request,Response  $response, $args) {
            
            $parseBody= $request->getParsedBody();

            if(isset($parseBody['codigo'])&&isset($parseBody['descripcion'])&&isset($parseBody['precio'])){
                 $menuCoincidenteCodigo = Menu::where('codigo',$parseBody['codigo'])->first();
                 if($menuCoincidenteCodigo !=null){
                     echo "tiene que seleccionar otro codigo ya que estan guardados";
                     return $response = "Error";
                 }
                $menu = new Menu();
                $menu->codigo= $parseBody['codigo'];
                $menu->descripcion= $parseBody['descripcion'];
                $menu->precio= $parseBody['precio'];
                foreach (SectorPedido::SECTORES as $key => $value) {
                    if($key== $parseBody['sector']){
                        $menu->sector=$value;
                    }
                }
                $response->getBody()->write(json_encode($menu));
             }else{
                $response->getBody()->write("Faltan elementos para dar de alta");
             }



            return $response;
        }

        public function updateOne(Request $request,Response  $response, $args) {

            $parseBody= $request->getParsedBody();

            if(isset($parseBody['codigo'])){
                $menuCoincidenteCodigo = Menu::where('codigo',$parseBody['codigo'])->first()->attributesToArray();
                $menu = new Menu();
                $menu->codigo= $menuCoincidenteCodigo['codigo'];
                $menu->descripcion= $menuCoincidenteCodigo['descripcion'];
                $menu->precio= $menuCoincidenteCodigo['precio'];
                $response->getBody()->write(json_encode($menu->save()));
            }else{
                $response->getBody()->write("Faltan elementos para updatear");
            }
            return $response;
        }

        public function deleteOne(Request $request, Response $response, $args) {
            $menu =Menu::find($args['id']);
            $rta =$menu->delete();
            $response->getBody()->write(json_encode($rta));
            return $response;
        }


        

        

    }




?>