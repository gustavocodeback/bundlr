<?php

namespace Bundlr\Reflectors;

class Decorator {

    /**
     * Nome da classe onde o decorator será aplicada
     *
     * @var [type]
     */
    public $className;

    /**
     * Refletor da classe
     *
     * @var [type]
     */
    public $classReflector;

    /**
     * Método constructor
     *
     * @param [type] $className
     */
    function __construct( $className ) {

        // Seta o nome da classe
        $this->className = $className;

        // Chama o boot
        $this->boot();
    }

    /**
     * Inicializa a análise da classe
     *
     * @return void
     */
    function boot() {

        // Instancia o reflector
        $this->classReflector = new \ReflectionClass( $this->className );
    }

    /**
     * Obtem as anotacoes das classes
     *
     * @return void
     */
    function getDecorators( $reflector = null ) {

        // Verifica se existe um reflector
        $reflector = $reflector ? $reflector : $this->classReflector;

        // Get the annotations
        $comments = Parser::getAnnotations( $reflector->getDocComment() );

        // Volta o array mapeado
        return array_map( function( $value ) use ( $reflector ) {

            // Seta o nome da classe
            $value->setClassName( $this->className );

            // Verifica se é um método
            if ( get_class( $reflector ) == 'ReflectionMethod' ) {
                $value->setMethodName( $reflector->name );
            }

            // Verifica se é um atributo
            if ( get_class( $reflector ) == 'ReflectionProperty' ) {
                $value->setPropertyName( $reflector->name );
            }

            // Retorna o item formatado
            return $value;
        }, $comments );
    }

    /**
     * Verifica se possui um decorator em especifico
     *
     * @param [type] $name
     * @return boolean
     */
    function hasDecorator( $name, $reflector = null ) {

        // Obtem todos os decorators
        $decorators = $this->getDecorators( $reflector );

        // Obtem somente os nomes
        $names = array_map( function( $value ) {
            return $value->title;
        }, $decorators );

        // Verifica se existe o decorator
        return in_array( $name, $names );
    }

    /**
     * Obtem os decorators
     *
     * @return void
     */
    function getDecorator( $name, $reflector = null ) {

        // verifica se tem o decorator
        if ( !$this->hasDecorator( $name, $reflector ) ) return;

        // Obtem todos os decorators
        $decorators = $this->getDecorators( $reflector );

        // Verifica os decorators
        $retorno = [];
        foreach( $decorators as $decorator ) {
            if ( $decorator->title == $name ) $retorno[] = $decorator;
        }

        // Volta o decorator encontrado
        return count( $retorno ) > 1 ? $retorno : $retorno[0];
    }

    /**
     * Aplica um callback no decorator especificado
     * 
     */
    function onDecorator( $name, $closure, $reflector = null ) {

        // Obtem o decorator
        $decorator = $this->getDecorator( $name, $reflector );

        // Verifica se existe o decorator
        if ( !$decorator ) return;

        // Aplica a funcion
        $closure( $decorator );
        return $this;
    }

    /**
     * Aplica um callback em uma ou mais decorators
     *
     * @param [type] $names
     * @param [type] $closure
     * @return void
     */
    function onDecorators( $names, $closure, $reflector = null ) {
        foreach( $names as $name ) $this->onDecorator( $name, $closure, $reflector );
        return $this;
    }
}

// End of file