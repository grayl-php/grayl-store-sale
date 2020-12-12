<?php

   namespace Grayl\Store\Sale\Controller;

   use Grayl\Store\Product\Entity\ProductDiscount;
   use Grayl\Store\Sale\Entity\SaleData;
   use Grayl\Store\Sale\Service\SaleService;

   /**
    * Class SaleController
    * The controller for working with saleData entities
    *
    * @package Grayl\Store\Sale
    */
   class SaleController
   {

      /**
       * The SaleData instance to interact with
       *
       * @var SaleData
       */
      private SaleData $sale_data;

      /**
       * The SaleService instance to interact with
       *
       * @var SaleService
       */
      private SaleService $sale_service;


      /**
       * The class constructor
       *
       * @param SaleData    $sale_data    The SaleData instance to interact with
       * @param SaleService $sale_service The SaleService instance to use
       */
      public function __construct ( SaleData $sale_data,
                                    SaleService $sale_service )
      {

         // Setup the class
         $this->sale_data = $sale_data;

         // Set the sale service
         $this->sale_service = $sale_service;
      }


      /**
       * Get the sale ID
       *
       * @return string
       */
      public function getID (): string
      {

         // Return the ID
         return $this->sale_data->getID();
      }


      /**
       * Sets a single ProductDiscount
       *
       * @param string          $product_tag      The product tag the discount applies to
       * @param ProductDiscount $product_discount The ProductDiscount entity
       */
      public function setProductDiscount ( string $product_tag,
                                           ProductDiscount $product_discount ): void
      {

         // Set the discount
         $this->sale_data->setProductDiscount( $product_tag,
                                               $product_discount );
      }


      /**
       * Searches a SaleData object to find ProductDiscount from a single tag
       *
       * @param string $tag A single tag to find a ProductDiscount for
       *
       * @return ProductDiscount
       */
      public function findProductDiscountFromTag ( string $tag ): ?ProductDiscount
      {

         // Use the service to search for a discount
         return $this->sale_data->getProductDiscount( $tag );
      }


      /**
       * Searches a SaleData object to find ProductDiscount from an array of multiple tags
       *
       * @param string[] $tags An array of tags to use for finding discounts
       *
       * @return ?ProductDiscount
       */
      public function findProductDiscountFromTags ( array $tags ): ?ProductDiscount
      {

         // Request the discount from the service
         return $this->sale_service->findProductDiscountFromTags( $this->sale_data,
                                                                  $tags );
      }

   }