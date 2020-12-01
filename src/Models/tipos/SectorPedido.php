<?php
    namespace App\Models\tipos;

    abstract class SectorPedido{
         const BARRA_TRAGOS = 'barra de tragos y bebidas';
         const BARRA_CERVEZA = 'barra de cerveza artesanal';
         const COCINA = 'sector cocina';
         const CANDY_BAR = 'candy_bar';
         const SECTORES = array('BARRA_TRAGOS'=>self::BARRA_TRAGOS,'BARRA_CERVEZA'=>self::BARRA_CERVEZA,'COCINA'=>self::COCINA,'CANDY_BAR'=>self::CANDY_BAR);
    }
?>