<?php

   namespace Grayl\Test\Sale;

   use Grayl\Store\Product\Entity\ProductDiscount;
   use Grayl\Store\Sale\Controller\SaleController;
   use Grayl\Store\Sale\SalePorter;
   use PHPUnit\Framework\TestCase;

   /**
    * Test class for the Sale package
    *
    * @package Grayl\Store\Sale
    */
   class SaleControllerTest extends TestCase
   {

      /**
       * Tests the creation of SaleController object
       *
       * @return SaleController
       * @throws \Exception
       */
      public function testCreateSaleController (): SaleController
      {

         // Create the test object
         $sale = SalePorter::getInstance()
                           ->getSavedSaleController( 'test' );

         // Check the type of object created
         $this->assertInstanceOf( SaleController::class,
                                  $sale );

         // Return it
         return $sale;
      }


      /**
       * Tests a SaleData entity
       *
       * @param SaleController $sale A SaleController entity to use for testing
       *
       * @depends testCreateSaleController
       */
      public function testSaleControllerData ( SaleController $sale ): void
      {

         // Perform some checks on the data
         $this->assertEquals( 'test',
                              $sale->getID() );
      }


      /**
       * Tests a tag search for a ProductDiscount in a SaleController
       *
       * @param SaleController $sale A SaleController entity to use for testing
       *
       * @return ProductDiscount
       * @depends testCreateSaleController
       */
      public function testSaleControllerTagSearch ( SaleController $sale ): ProductDiscount
      {

         // Search the SaleController for a ProductDiscount using a tag
         $product_discount = $sale->findProductDiscountFromTags( [ 'test-tag' ] );

         // Check the type of object returned
         $this->assertInstanceOf( ProductDiscount::class,
                                  $product_discount );

         // Check the type of object returned
         $this->assertNotNull( $product_discount );

         // Return it
         return $product_discount;
      }


      /**
       * Tests a ProductDiscount entity returned by a SearchController
       *
       * @param ProductDiscount $product_discount A ProductDiscount entity to use for testing
       *
       * @depends testSaleControllerTagSearch
       */
      public function testProductDiscountData ( ProductDiscount $product_discount ): void
      {

         // Perform some checks on the data
         $this->assertEquals( 10,
                              $product_discount->getDiscount() );
         $this->assertTrue( $product_discount->doRoundDown() );

         // Test discount settings
         $this->assertEquals( 'overridden',
                              $product_discount->getOverrideSetting( 'overridden_setting' ) );
      }

   }