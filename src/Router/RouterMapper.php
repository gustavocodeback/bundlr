<?php

namespace Bundlr\Router;

/**
 * Classe resposavel por mapear
 * as rotas declaradas em uma determinada
 * classe
 * 
 */
class RouterMapper {

    /**
     * Refletores do grupo
     *
     * @var [type]
     */
    public $groupReflection;

    /**
     * Refletores das rotas
     *
     * @var array
     */
    public $routesReflection = [];

    /**
     * Método constructor
     *
     * @param [type] $classDecorator
     */
    function __construct( $classDecorator ) {

        // Seta o decorator da classe
        $this->classDecorator = $classDecorator;

        // Inicia o refletor do grupo
        $this->__setGroupReflections();

        // Obtem os métodos da classe
        $methods = $this->classDecorator->classReflector->getMethods();
        foreach( $methods as $methodDecorator ) $this->__addRouteReflection( $methodDecorator );
    }

    /**
     * Seta os refletores do grupo
     *
     * @return void
     */
    private function __setGroupReflections( $reflection = null ) {

        // Verifica se foi informado um refletor
        if ( $reflection ) {
            $this->groupReflection = $reflection;
            return;
        }

        // Cria uma reflexao
        $reflection = new RouteReflection( $this->classDecorator->className);

        // Decorators que iremos procurar na classe
        $decorators = [ 'Prefix', 'Domain', 'Middleware', 'Name', 'Resource' ];
        $this->classDecorator->onDecorators( $decorators, function( $decorator ) use ( $reflection ) {
            
            // Seta o método
            $methodName = 'set'.$decorator->title;
            
            // Chama o método
            $reflection->{$methodName}( $decorator->params );
        });

        // Seta o refletor do grupo
        $this->groupReflection = $reflection;
    }

    /**
     * Seta as reflexoes dos métodos
     *
     * @param [type] $decorator
     * @return void
     */
    private function __addRouteReflection( $decorator ) {

        // Cria uma reflexao
        $reflection = new RouteReflection( $decorator->class, $decorator->name );

        // Ações possiveis
        $possibleActions = [ 'Get', 'Put', 'Patch', 'Post', 'Delete' ];
        $possibleParams = [ 'Middleware', 'Name', 'Prefix', 'Domain' ];
        $this->classDecorator
        ->onDecorators( $possibleActions, function( $decorator ) use ( $reflection ) {
            $reflection->setAction( strtolower( $decorator->title ));
            $reflection->setPath( $decorator->params );
        }, $decorator )
        ->onDecorators( $possibleParams, function( $decorator ) use ( $reflection ) {
            $methodName = 'set'.$decorator->title;
            $reflection->{$methodName}( $decorator->params );
        }, $decorator );

        // Adiciona o refletor na rota
        if ( $reflection->action && $reflection->path ) $this->routesReflection[] = $reflection;
    }

    /**
     * Get refletores do grupo
     *
     * @return  [type]
     */ 
    public function getGroupReflection(){
        return $this->groupReflection;
    }

    /**
     * Get refletores das rotas
     *
     * @return  array
     */ 
    public function getRoutesReflection(){
        return $this->routesReflection;
    }
}

// End of file