<?php

   namespace Grayl\Store\Sale;

   use Grayl\Config\ConfigPorter;
   use Grayl\Mixin\Common\Entity\KeyedDataBag;
   use Grayl\Mixin\Common\Traits\StaticTrait;
   use Grayl\Store\Sale\Controller\SaleController;
   use Grayl\Store\Sale\Entity\SaleData;
   use Grayl\Store\Sale\Service\SaleService;

   /**
    * Front-end for the Product package
    *
    * @package Grayl\Store\Sale
    */
   class SalePorter
   {

      // Use the static instance trait
      use StaticTrait;

      /**
       * The name of the config folder for the Sale package
       *
       * @var string
       */
      private string $config_folder = 'store-sale';

      /**
       * A KeyedDataBag that holds previously created SaleControllers
       *
       * @var KeyedDataBag
       */
      private KeyedDataBag $saved_sales;


      /**
       * The class constructor
       *
       * @throws \Exception
       */
      public function __construct ()
      {

         // Create a KeyedDataBag for storing sales
         $this->saved_sales = new KeyedDataBag();

      }


      /**
       * Loads a SaleController from a config file
       *
       * @param string $id The unique ID of the sale to load from the config folder
       *
       * @return SaleController
       * @throws \Exception
       */
      private function loadSaleControllerFromConfigFile ( string $id ): SaleController
      {

         // Grab the product's config file
         /** @var  $sale_controller SaleController */
         $sale_controller = ConfigPorter::getInstance()
                                        ->includeConfigFile( $this->config_folder . '/' . $id . '.php' );

         // Return the SaleController
         return $sale_controller;
      }


      /**
       * Retrieves a previously created SaleController entity if it exists, otherwise a new one is created
       *
       * @param string $id The sale ID to load from the config
       *
       * @return SaleController
       * @throws \Exception
       */
      public function getSavedSaleController ( string $id ): SaleController
      {

         // Check to see if there is already a saved SaleController for this ID
         $controller = $this->saved_sales->getVariable( $id );

         // If we don't have a saved SaleController for this ID, create one from the Sale ConfigController
         if ( empty ( $controller ) ) {
            // Request the SaleController
            $controller = $this->loadSaleControllerFromConfigFile( $id );

            // Save it for re-use
            $this->saved_sales->setVariable( $id,
                                             $controller );
         }

         // Return the controller
         return $controller;
      }


      /**
       * Creates a new SaleController
       *
       * @param string $id The unique ID of the sale
       *
       * @return SaleController
       */
      public function newSaleController ( string $id ): SaleController
      {

         // Create the SaleData object
         $sale_data = new SaleData( $id );

         // Return a new SaleController
         return new SaleController( $sale_data,
                                    new SaleService() );

      }

   }