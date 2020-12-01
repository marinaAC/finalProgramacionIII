<?php
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Factory\AppFactory;
use FastRoute\RouteCollector;
use Slim\Routing\RouteCollectorProxy;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;


use Config\Database;


use FastRoute\DataGenerator\GroupPosBased;

use App\Controllers\UsuarioController;
use App\Controllers\MenuController;
use App\Controllers\EmpleadoController;
use App\Controllers\PedidoController;
use App\Controllers\ComandaController;
use App\Middlewares\AuthMiddleware;
use App\Middlewares\AuthEmployee;
use App\Middlewares\JsonMiddleware;

//use Slim\Handlers\Strategies\RequestHandler;

require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();
$app->setBasePath('/FinalComanda/public');



new Database;

$app->group('/cliente',function(RouteCollectorProxy $group){
    $group->get('[/]',PedidoController::class.":getAll");
    $group->post('[/]',PedidoController::class.":addOne");
    $group->get('/{id}',PedidoController::class.":getOne");
    $group->put('/{id}',PedidoController::class.":updateOne");
    $group->delete('/{id}',PedidoController::class.":deleteOne");
});


$app->group('/pedido',function(RouteCollectorProxy $group){
    $group->get('[/]',PedidoController::class.":getAll");
    $group->post('[/]',PedidoController::class.":addOne");
    $group->get('/{id}',PedidoController::class.":getOne");
    $group->put('/{id}',PedidoController::class.":updateOne");
    $group->delete('/{id}',PedidoController::class.":deleteOne");
});

$app->group('/empleado',function(RouteCollectorProxy $group){
    $group->get('[/]',EmpleadoController::class.":getAll");
    $group->post('[/]',EmpleadoController::class.":addOne");
    $group->get('/{id}',EmpleadoController::class.":getOne");
    $group->put('/{id}',EmpleadoController::class.":updateOne");
    $group->delete('/{id}',EmpleadoController::class.":deleteOne");
});

$app->group('/comanda',function(RouteCollectorProxy $group){
    $group->get('[/]',ComandaController::class.":getAll");
    $group->post('[/]',ComandaController::class.":addOne");
    $group->get('/{id}',ComandaController::class.":getOne");
    $group->put('/{id}',ComandaController::class.":updateOne");
    $group->delete('/{id}',ComandaController::class.":deleteOne");
});

$app->group('/users',function(RouteCollectorProxy $group){
    $group->get('[/]',UsuarioController::class.":getAll");
    $group->post('[/]',UsuarioController::class.":addOne");
    $group->get('/{id}',UsuarioController::class.":getOne");
    $group->put('/{id}',UsuarioController::class.":updateOne");
    $group->delete('/{id}',UsuarioController::class.":deleteOne");
});

$app->group('/cargarDatos',function(RouteCollectorProxy $group){
    $group->post('/menu',MenuController::class.":addOne");
    $group->put('/menu',MenuController::class.":updateOne");
    $group->post('/empleado',EmpleadoController::class.":addOne");
    $group->put('/empleado',EmpleadoController::class.":updateOne");
})->add(new AuthMiddleware);

$app->group('/preparacion',function(RouteCollectorProxy $group){
    $group->post('/atenderPedido/{id}',EmpleadoController::class.":atenderPedido");
    $group->POST('/consultarPedido/{id}',PedidoController::class.":consultarEstado");
})->add(new AuthEmployee);

$app->group('/login',function(RouteCollectorProxy $group){
    $group->post('[/]',UsuarioController::class.":getOne");
});




$app->run();


?>