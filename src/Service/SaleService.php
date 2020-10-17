<?php

   namespace Grayl\Store\Sale\Service;

   use Grayl\Store\Product\Entity\ProductDiscount;
   use Grayl\Store\Sale\Entity\SaleData;

   /**
    * Class SaleService
    * The service for working with SaleData and ProductDiscount entities
    *
    * @package Grayl\Store\Sale
    */
   class SaleService
   {

      /**
       * Searches a SaleData object to find ProductDiscount from an array of multiple tags
       *
       * @param SaleData $data The SaleData entity to search
       * @param string[] $tags An array of tags to use for finding discounts
       *
       * @return ProductDiscount
       */
      public function findProductDiscountFromTags ( SaleData $data,
                                                    array $tags ): ?ProductDiscount
      {

         // Loop through each product tag
         foreach ( $tags as $tag ) {
            // See if the SaleData has a ProductDiscount set for this product tag
            $discount = $data->getProductDiscount( $tag );

            // If a discount was found for this tag
            if ( ! empty( $discount ) ) {
               // Return it
               return $discount;
            }
         }

         // No discounts found
         return null;
      }

   }