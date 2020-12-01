<?php
    namespace App\Controllers;
    use Psr\Http\Message\ResponseInterface as Response;
    use Psr\Http\Message\ServerRequestInterface as Request;

    use App\Models\Usuario;
    use App\Validations\Validaciones;
    use App\Security\Auth;
  

    class UsuarioController {



        //se deberia aplicar una interface para que quede bien
        public function getAll(Request $request, Response $response, $args) {

            $rta = Usuario::get();

            $response->getBody()->write(json_encode($rta));
            return $response;
        }


        public function getOne(Request $request, Response $response, $args) {
            $parseBody= $request->getParsedBody();
            if(isset($parseBody['email'])&&isset($parseBody['clave'])){
                $usuarioCoincidenteEmail = Usuario::where([['email','=',$parseBody['email']],['clave','=',$parseBody['clave']]])->first()->attributesToArray();
                if($usuarioCoincidenteEmail!=null){
                    $usuario = new Usuario();
                    $usuario->email= $usuarioCoincidenteEmail['email'];
                    $usuario->clave= $usuarioCoincidenteEmail['clave'];
                    $usuario->tipo= $usuarioCoincidenteEmail['tipo'];
                    $token= Auth::SignIn($usuario);
                    $_SERVER['HTTP_TOKEN'] =  $token;
                    $response->getBody()->write($token);
                   // $response->withHeader('token', $token);
                }else{
                    $response->getBody()->write("Usuario no encontrado");
                }
                
            }else{
                $response->getBody()->write("Faltan elementos para poder realizar la busqueda");
            }
            return $response;
        }

        public function addOne(Request $request,Response  $response, $args) {
            
            $parseBody= $request->getParsedBody();

             if(Validaciones::validName($parseBody['email'])&&
                Validaciones::validClave($parseBody['clave'])){
                 $usuarioConverEmail = Usuario::where('email',$parseBody['email'])->first();
                 if($usuarioConverEmail !=null){
                     $response->getBody()->write("El usuario ya se encuentra dado de alta");
                     return $response;
                }
                $usuario = new Usuario;
                $usuario->email= $parseBody['email'];
                $usuario->clave= $parseBody['clave'];
                $usuario->tipo= $parseBody['tipo'];
                $response->getBody()->write(json_encode($usuario->save()));
             }else{
                $response->getBody()->write("Faltan elementos para dar de alta");
             }
            return $response;
        }

        public function updateOne(Request $request,Response  $response, $args) {

            $parseBody= $request->getParsedBody();
            if(isset($parseBody['email'])&&isset($parseBody['clave'])){
                $uss=Usuario::where([['email','=',$parseBody['email']],['clave','=',$parseBody['clave']]])->first()->attributesToArray();
                if($uss != null ){
                    $usuario = new Usuario;
                    $usuario->email= $uss['email'];
                    $usuario->clave= $uss['clave'];
                    $usuario->tipo= $uss['tipo'];
                    $response->getBody()->write(json_encode($usuario->save()));
                }else{
                    $response->getBody()->write("Usuario no encontrado");
                }
            }else{
                $response->getBody()->write("Faltan elementos para updatear");
            }
            return $response;
        }

        public function deleteOne(Request $request, Response $response, $args) {
            $usuario =Usuario::find($args['id']);
            $rta =$usuario->delete();
            $response->getBody()->write(json_encode($rta));
            return $response;
        }


        

        

    }




?>