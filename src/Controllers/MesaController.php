<?php
    namespace App\Controllers;
    use Psr\Http\Message\ResponseInterface as Response;
    use Psr\Http\Message\ServerRequestInterface as Request;

    use App\Models\Mesa;
    use App\Validations\Validaciones;
    use App\Security\Auth;
  

    class MesaController {



        //se deberia aplicar una interface para que quede bien
        public function getAll(Request $request, Response $response, $args) {

            $rta = Mesa::get();

            $response->getBody()->write(json_encode($rta));
            return $response;
        }


        public function getOne(Request $request, Response $response, $args) {

            $parseBody= $request->getParsedBody();
            if(isset($parseBody['email'])&&(isset($parseBody['clave'])||isset($parseBody['nombre']))){
                $mesaCoincidenteEmail = Mesa::where('email',$parseBody['email'])->first()->attributesToArray();
               // $mesaCoindicenteName = Mesa::where('nombre',$parseBody['nombre'])->first()->attributesToArray();
                $mesaCoindicentePass = Mesa::where('clave',$parseBody['clave'])->first()->attributesToArray();
                if($mesaCoincidenteEmail['id']==$mesaCoindicentePass['id']){
                    $mesa = new Mesa();
                    $mesa->email= $mesaCoincidenteEmail['email'];
                    $mesa->nombre= $mesaCoincidenteEmail['nombre'];
                    $mesa->clave= $mesaCoincidenteEmail['clave'];
                    $mesa->tipo= $mesaCoincidenteEmail['tipo'];
                    $token= Auth::SignIn($mesa);
                    $_SERVER['HTTP_TOKEN'] =  $token;
                    echo "$token";
                }
                
            }else{
                echo "Faltan elementos para dar de alta";
                $response = "Error";
            }
            return $response;
        }

        public function addOne(Request $request,Response  $response, $args) {
            
            $parseBody= $request->getParsedBody();

             if(Validaciones::validName($parseBody['email'])&&
                Validaciones::validName($parseBody['nombre'])&&
                Validaciones::validClave($parseBody['clave'])){
                $mesaCoincidenteEmail = Mesa::where('email',$parseBody['email'])->first();
                $mesaCoindicenteName = Mesa::where('nombre',$parseBody['nombre'])->first();
                $mesaConverEmail = $mesaCoincidenteEmail->attributesToArray();
                $mesaCoindicenteName = $mesaCoindicenteName->attributesToArray();
                if(($mesaConverEmail !=null || $mesaCoindicenteName != null) &&
                    (strcmp($mesaConverEmail['email'],$parseBody['email'])==0 ||
                     strcmp($mesaCoindicenteName['nombre'],$parseBody['nombre'])==0) ){
                    
                    echo "tiene que seleccionar otro email o nombre, ya que estan guardados";
                    return $response = "Error";
                }
                
                $mesa = new Mesa;
                $mesa->email= $parseBody['email'];
                $mesa->nombre= $parseBody['nombre'];
                $mesa->clave= $parseBody['clave'];
                $mesa->tipo= $parseBody['tipo'];
                $response->getBody()->write(json_encode($mesa->save()));
             }else{
                 echo "Faltan elementos para dar de alta";
                 $response = "Error";
             }



            return $response;
        }

        public function updateOne(Request $request,Response  $response, $args) {

            $parseBody= $request->getParsedBody();

            if(isset($parseBody['email'])&&isset($parseBody['pass'])){
                $mesa = new Mesa;
                $mesa->email= $parseBody['email'];
                $mesa->pass= $parseBody['pass'];
                $response->getBody()->write(json_encode($mesa->save()));
            }else{
                echo "Faltan elementos para dar de alta";
                $response = "Error";
            }
            return $response;
        }

        public function deleteOne(Request $request, Response $response, $args) {
            $mesa =Mesa::find($args['id']);
            $rta =$mesa->delete();
            $response->getBody()->write(json_encode($rta));
            return $response;
        }


        

        

    }




?>