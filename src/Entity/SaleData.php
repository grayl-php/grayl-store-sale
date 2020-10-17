<?php

   namespace Grayl\Store\Sale\Entity;

   use Grayl\Mixin\Common\Entity\KeyedDataBag;
   use Grayl\Store\Product\Entity\ProductDiscount;

   /**
    * Class SaleData
    * The entity for a product sale
    * TODO: Look at splitting this into two classes, making one a ProductDiscountBag
    *
    * @package Grayl\Store\Sale
    */
   class SaleData
   {

      /** The unique ID of this sale
       *
       * @var string
       */
      private string $id;

      /**
       * A bag of ProductDiscount entities for this sale - indexed by tag
       *
       * @var KeyedDataBag
       */
      private KeyedDataBag $product_discounts;


      /**
       * The class constructor
       *
       * @param string $id The unique ID of the sale
       */
      public function __construct ( string $id )
      {

         // Set the class data
         $this->setID( $id );

         // Create the bag
         $this->product_discounts = new KeyedDataBag();
      }


      /**
       * Gets the ID
       *
       * @return string
       */
      public function getID (): string
      {

         // Return the ID
         return $this->id;
      }


      /**
       * Sets the ID
       *
       * @param string $id The unique ID of the sale
       */
      public function setID ( string $id ): void
      {

         // Set the ID
         $this->id = $id;
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
         $this->product_discounts->setVariable( $product_tag,
                                                $product_discount );
      }


      /**
       * Retrieves the ProductDiscount object for a specific product tag
       *
       * @param string $product_tag The product tag the discount applies to
       *
       * @return ProductDiscount
       */
      public function getProductDiscount ( string $product_tag ): ?ProductDiscount
      {

         // Return the discount
         return $this->product_discounts->getVariable( $product_tag );
      }

   }