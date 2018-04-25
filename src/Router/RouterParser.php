<?php

namespace Bundlr\Router;

use Illuminate\Support\Facades\Route;

/**
 * Gera as rotas mapeadas no
 * formato do laravel
 * 
 */
class RouterParser {

    /**
     * Mappers carregados
     * 
     */
    static $mappers = [];

    /**
     * Carrega os mappers
     *
     * @param [type] $mappers
     * @return void
     */
    static function load( $mappers ) {
        self::$mappers = $mappers;
    }

    /**
     * Faz o parse de uma única rota
     *
     * @param [type] $mapper
     * @return void
     */
    static function parseMapperForRoot( $mapper ) {

        // Closure
        $closure = function() use ( $mapper ) {

            // Obtem as rotas
            $routes = $mapper->getRoutesReflection();
            foreach( $routes as $route ) {
                $callable = [ Route::class, $route->action ];
                $callable( $route->path, $route->getControllerAndMethod() );
            }

            // Group
            $group = $mapper->getGroupReflection();
            if ( $group->resource ) {
                $chain = Route::resource( $group->resource->path, $group->getController() );
                if ( isset( $group->resource->only ) ) $chain->only( $group->resource->only );
                if ( isset( $group->resource->except ) ) $chain->only( $group->resource->except );
            }
        };

        // Obtem o primeiro método que será chamado para formar o group
        $priorities = [ 'Middleware', 'Prefix', 'Domain', 'Name' ];
        $methods    = $mapper->getGroupReflection()->getAllWith( $priorities );
        $chain      = Route::class;
        if ( $methods ) {
            foreach( $methods as $func => $params ) {
                $callable = [ $chain, $func ];
                $chain    = $callable($params);
            }
        }

        // Finaliza a cadeia
        $chain->group( $closure );
    }

    /**
     * Faz o parse das rotas
     *
     * @return void
     */
    static function forRoot() {
        foreach( self::$mappers as $mapper ) self::parseMapperForRoot( $mapper );
    }
}

// End of file
