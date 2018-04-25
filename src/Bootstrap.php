<?php

namespace Bundlr;

/**
 * Classe que realiza o bootstrap 
 * dos módulos da aplicação laravel
 * 
 */
abstract class Bootstrap {

    /**
     * Módulos processados no bootstraping
     * 
     */
    static $appModules = [];

    /**
     * Controllers encontrados nos módulos
     * 
     */
    static $appControllers = [];

    /**
     * Módulo raiz da aplicação
     * 
     */
    static $rootModule;

    /**
     * Mappers da aplicação
     * 
     */
    static $appMappers = [];

    /**
     * Obtem os módulos declarados em um módulo
     *
     * @param [type] $module
     * @return void
     */
    static function parseModules( $mainModule ) {

        // Obtem os módulos do módulo
        $modules = $mainModule::imports();

        // Get only the modules that werent loaded yet
        $modulesToLoad = array_filter( $modules, function( $module ) {
            return !in_array( $module, self::$appModules );
        });

        // Add the modules to the application modules
        self::$appModules = array_merge( self::$appModules, $modulesToLoad );

        // Check if there is any module to parse
        if ( count( $modulesToLoad ) > 0 ) {
            foreach( $modulesToLoad as $moduleToLoad ) {
                self::parseModules( $moduleToLoad );
            }
        }
    }

    /**
     * Obtem os controllers 
     * registrados nos módulos
     *
     * @return void
     */
    static function parseControllers() {
        foreach( self::$appModules as $appModule ) {

            // Obtem os controllers
            $controllers = $appModule::controllers();

            // Registra os controllers
            self::$appControllers = array_unique( array_merge( self::$appControllers, $controllers ) );
        }
    }

    /**
     * Checka a integridade do sistema
     *
     * @return void
     */
    static function checkIntegrity() {
        foreach( self::$appModules as $appModule ) {

            // Obtem os controllers
            $required = $appModule::requires();
            foreach( $required as $module ) {
                if ( !in_array( $module, self::$appModules ) ) {
                    throw new \Error( 'O Módulo '.$appModule.' precisa do módulo '.$module.' para funcionar corretamente.' );
                }
            }
        }
    }

    /**
     * Faz o registro dos decorators
     *
     * @return void
     */
    static function parseDecorators() {

        // Percorre todos os controllers
        foreach( self::$appControllers as $controller ) {

            // Obtem o decorator da classe
            $decorator = new Reflectors\Decorator( $controller );

            // Obtem o router mapper da classe
            self::$appMappers[] = new Router\RouterMapper( $decorator );
        }

        // Carrega o parser
        Router\RouterParser::load( self::$appMappers );
        Router\RouterParser::forRoot();
    }

    /**
     * Faz o bootstrap de um módulo
     *
     * @param [type] $module
     * @return void
     */
    static function forRoot( $module ) {

        // Seta o módulo principal
        self::$rootModule   = $module;
        self::$appModules[] = $module;

        // Inicia a obtenção dos módulos
        self::parseModules( $module );

        // Carrega os controllers
        self::parseControllers();

        // Faz o parse dos decorators
        self::parseDecorators();

        // Verifica a integridade dos módulos
        self::checkIntegrity();
    }
}

// End of file
