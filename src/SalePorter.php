<?php

   namespace Grayl\Store\Sale;

   use Grayl\Config\ConfigPorter;
   use Grayl\Config\Controller\ConfigController;
   use Grayl\Mixin\Common\Entity\KeyedDataBag;
   use Grayl\Mixin\Common\Traits\StaticTrait;
   use Grayl\Store\Product\ProductPorter;
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
       * The name of the config file for the Sale package
       *
       * @var string
       */
      private string $config_file = 'store.sale.php';

      /**
       * The config instance for the Sale package
       *
       * @var ConfigController
       */
      private ConfigController $config;

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

         // Create the config instance from the config file
         $this->config = ConfigPorter::getInstance()
                                     ->newConfigControllerFromFile( $this->config_file );

         // Create a KeyedDataBag for storing sales
         $this->saved_sales = new KeyedDataBag();
      }


      /**
       * Changes the default config file being used
       *
       * @param string $config_file The new config file to use
       *
       * @throws \Exception
       */
      public function setConfigFile ( string $config_file ): void
      {

         // Set the new config file value
         $this->config_file = $config_file;

         // Create the config instance from the config file
         $this->config = ConfigPorter::getInstance()
                                     ->newConfigControllerFromFile( $config_file );
      }


      /**
       * Creates a new SaleController using data from the sale ConfigController
       *
       * @param string $id The unique ID of the sale to load from the config file
       *
       * @return SaleController
       * @throws \Exception
       */
      private function newSaleControllerFromConfig ( string $id ): SaleController
      {

         // Make sure the sale ID given has a config
         if ( empty( $this->config->getConfig( $id ) ) ) {
            // Throw an error and exit
            throw new \Exception( 'Sale data could not be found in the config.' );
         }

         // Request a new SaleData entity
         $sale_data = new SaleData( $this->config->getConfig( $id )[ 'id' ] );

         // Loop through each ProductDiscount in the config and add it into this new SaleController
         foreach ( $this->config->getConfig( $id )[ 'discounts' ] as $discount_config ) {
            // Request a new ProductDiscount from the factory
            $product_discount = ProductPorter::getInstance()
                                             ->newProductDiscount( $discount_config[ 'discount' ],
                                                                   $discount_config[ 'round_down' ],
                                                                   $discount_config[ 'settings' ] );

            // Set the ProductDiscount to each tag
            foreach ( $discount_config[ 'tags' ] as $tag ) {
               // Set the tag
               $sale_data->setProductDiscount( $tag,
                                               $product_discount );
            }
         }

         // Return a SaleController
         return new SaleController( $sale_data,
                                    new SaleService() );
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
            $controller = $this->newSaleControllerFromConfig( $id );

            // Save it for re-use
            $this->saved_sales->setVariable( $id,
                                             $controller );
         }

         // Return the controller
         return $controller;
      }

   }