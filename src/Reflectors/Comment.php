<?php

namespace Bundlr\Reflectors;

class Comment {

    /**
     * Titulo do decorator
     * 
     */
    public $title;

    /**
     * Parametros do decorator
     */
    public $params;

    /**
     * Nome da classe
     * 
     */
    public $className;

    /**
     * Nome do método
     * 
     */
    public $methodName;

    /** 
     * Nome da propriedade 
     * 
     */
    public $propertyName;

    /**
     * Método constructor
     *
     * @param [type] $annotations
     */
    function __construct( $annotation, $title ) {

        // Seta o titulo da anotacao
        $this->title = $title;

        // Seta os parametros
        $this->params = $annotation;
    }

    /** 
     * Seta o nome da classe
     * 
     */
    function setClassName( $className ) {

        // Seta o nome da classe
        $this->className = $className;
    }

    /**
     * Seta o nome do método
     *
     * @param [type] $methodName
     * @return void
     */
    function setMethodName( $methodName ) {

        // Seta o nome do método
        $this->methodName = $methodName;
    }

    /** 
     * Seta o nome da propriedade
     * 
     */
    function setPropertyName( $propertyName ) {

        // Seta o nome da propriedade
        $this->propertyName = $propertyName;
    }
}

// End of file
