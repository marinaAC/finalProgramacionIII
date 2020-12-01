<?php

    namespace App\Middlewares;

    use Psr\Http\Message\ResponseInterface as Response;
    use Psr\Http\Message\ServerRequestInterface as Request;
    use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
    use App\Security\Auth;
    use App\Models\tipos\TipoEmpleado;

    class AuthEmployee{
        public function __invoke(Request $request, RequestHandler $handler):Response
        {
            
            $valido = !true;
            $valido=AuthEmployee::validToken();
            if(!$valido){
                $response = new Response();
                $response->getBody()->write("Prohibido pasar");
                return $response->withStatus(403);
            }else{
                $response = $handler->handle($request);
                return $response;
                
            }

        }

        private static function validToken(){
            $token= $_SERVER['HTTP_TOKEN'];
            $valid=false;
            if(isset($token)){
                $data = Auth::GetData($token);
                $valid = $data->tipo!=TipoEmpleado::MOZOS;
            }
            return $valid;
        }
    }

?>