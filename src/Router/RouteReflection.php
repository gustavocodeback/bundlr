<?php

namespace Bundlr\Router;

use Illuminate\Support\Facades\Route;

class RouteReflection {

    /**
     * Middlewares que serão aplicados na rota
     *
     * @var array
     */
    public $middleware;

    /**
     * Prefixo da rota
     *
     * @var [type]
     */
    public $prefix;

    /**
     * Acao da rota
     *
     * @var string
     */
    public $action;

    /**
     * Nome da rota
     *
     * @var [type]
     */
    public $name;

    /**
     * Controller da rota
     *
     * @var [type]
     */
    public $controller;

    /**
     * Método da rota
     *
     * @var [type]
     */
    public $method;

    /**
     * Dominio da rota
     *
     * @var [type]
     */
    public $domain;

    /**
     * Store da rota
     *
     * @var [type]
     */
    public $resource;

    /**
     * Caminho da rota
     *
     */
    public $path;

    /**
     * Método constructor
     *
     * @param [type] $controller
     * @param [type] $method
     * @param [type] $action
     */
    function __construct( $controller = null, $method = null, $action = null ) {
        $this->setController( $controller );
        $this->setMethod( $method );
        $this->setAction( $action );
    }

    /**
     * Get middlewares que serão aplicados na rota
     *
     * @return  array
     */ 
    public function getMiddleware() {
        return $this->middleware;
    }

    /**
     * Set middlewares que serão aplicados na rota
     *
     * @param  array  $middlewares  Middlewares que serão aplicados na rota
     *
     * @return  self
     */ 
    public function setMiddleware($middleware) {
        $this->middleware = $middleware;
        return $this;
    }

    /**
     * Get prefixo da rota
     *
     * @return  [type]
     */ 
    public function getPrefix(){
        return $this->prefix;
    }

    /**
     * Set prefixo da rota
     *
     * @param  [type]  $prefix  Prefixo da rota
     *
     * @return  self
     */ 
    public function setPrefix($prefix){
        $this->prefix = $prefix;
        return $this;
    }

    /**
     * Get acao da rota
     *
     * @return  string
     */ 
    public function getAction(){
        return $this->action;
    }

    /**
     * Set acao da rota
     *
     * @param  string  $action  Acao da rota
     *
     * @return  self
     */ 
    public function setAction($action){
        $this->action = $action;
        return $this;
    }

    /**
     * Get nome da rota
     *
     * @return  [type]
     */ 
    public function getName(){
        return $this->name;
    }

    /**
     * Set nome da rota
     *
     * @param  [type]  $name  Nome da rota
     *
     * @return  self
     */ 
    public function setName($name){
        $this->name = $name;
        return $this;
    }

    /**
     * Get controller da rota
     *
     * @return  [type]
     */ 
    public function getController(){
        return $this->controller;
    }

    /**
     * Set controller da rota
     *
     * @param  [type]  $controller  Controller da rota
     *
     * @return  self
     */ 
    public function setController($controller){
        $this->controller = $controller;
        return $this;
    }

    /**
     * Get método da rota
     *
     * @return  [type]
     */ 
    public function getMethod(){
        return $this->method;
    }

    /**
     * Set método da rota
     *
     * @param  [type]  $method  Método da rota
     *
     * @return  self
     */ 
    public function setMethod($method){
        $this->method = $method;
        return $this;
    }

    /**
     * Get dominio da rota
     *
     * @return  [type]
     */ 
    public function getDomain(){
        return $this->domain;
    }

    /**
     * Set dominio da rota
     *
     * @param  [type]  $domain  Dominio da rota
     *
     * @return  self
     */ 
    public function setDomain($domain){
        $this->domain = $domain;
        return $this;
    }

    /**
     * Get store da rota
     *
     * @return  [type]
     */ 
    public function getResource(){
        return $this->resource;
    }

    /**
     * Set store da rota
     *
     * @param  [type]  $store  Store da rota
     *
     * @return  self
     */ 
    public function setResource($resource){
        $this->resource = $resource;
        return $this;
    }

    /**
     * Obtem o controller e o método formatados
     *
     * @return void
     */
    function getControllerAndMethod() {
        return $this->controller.'@'.$this->method;
    }

    /**
     * Obtem o primeiro parametro povoado
     *
     * @param [type] $attrs
     * @return void
     */
    function getFirstWith( $attrs ) {
        $attrs = is_array( $attrs ) ? $attrs : [];
        foreach( $attrs as $attr ) {
            $methodName = 'get'.$attr;
            if ( $this->{$methodName}() ) return [ strtolower( $attr ) => $this->{$methodName}() ];
        }
        return null;
    }

    /**
     * Obtem os parametros não nulo
     *
     * @param [type] $attrs
     * @return void
     */
    function getAllWith( $attrs ) {
        $attrs   = is_array( $attrs ) ? $attrs : [];
        $retorno = [];
        foreach( $attrs as $attr ) {
            $methodName = 'get'.$attr;
            if ( $this->{$methodName}() ) $retorno[strtolower( $attr )] = $this->{$methodName}();
        }
        return count( $retorno ) > 0 ? $retorno : null;
    }

    /**
     * Get caminho da rota
     */ 
    public function getPath() {
        return $this->path;
    }

    /**
     * Set caminho da rota
     *
     * @return  self
     */ 
    public function setPath($path) {
        $this->path = $path;
        return $this;
    }
}

// End of file
