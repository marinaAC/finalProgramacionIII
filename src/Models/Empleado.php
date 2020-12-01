<?php
    namespace App\Models;

use App\Models\tipos\EstadoPedido;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Date;

class Empleado extends Model{
        
        function prepararPedido($pedido)
        {
            if($pedido != null){
                //$date = new DateTime();
                $pedido->estado = EstadoPedido::PENDIENTE;
                //$pedido->tiempo = $date->getTimestamp();
            }
            return $pedido;
        }

    }




?>