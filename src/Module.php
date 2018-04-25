<?php

namespace Bundlr;

class Module {

    /**
     * Controllers registrados no módulo
     * 
     */
    static function controllers() {
        return [];
    }

    /**
     * Rotas registrados no módulo
     *
     * @return void
     */
    static function routes(){}

    /**
     * Importações registrados no módulo
     *
     * @return void
     */
    static function imports(){
        return [];
    }

    /**
     * Módulos requeridos por esse módulo
     *
     * @return void
     */
    static function requires() {
        return [];
    }
}

// End of file