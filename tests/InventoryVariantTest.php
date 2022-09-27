<?php

namespace Stevebauman\Inventory\Tests;

use Stevebauman\Inventory\Models\Inventory;

/**
 * Inventory Variant Test
 * 
 * @coversDefaultClass \Stevebauman\Inventory\Traits\InventoryVariantTrait
 */
class InventoryVariantTest extends FunctionalTestCase
{
    /**
     * Test new variant
     *  
     * @covers ::newVariant
     * 
     * @return void
     */
    public function testNewVariant()
    {
        $item = $this->newInventory();

        $milk = Inventory::find($item->id);

        $sku = $this->generateSku().'CHOCOLATE-MILK';

        $chocolateMilk = $milk->newVariant($sku);

        $name = 'Chocolate Milk';

        $chocolateMilk->name = $name;

        $chocolateMilk->save();

        $this->assertEquals($chocolateMilk->sku, $sku);
        $this->assertEquals($chocolateMilk->name, $name);
        $this->assertEquals($chocolateMilk->parent_id, $milk->id);
        $this->assertEquals($chocolateMilk->category_id, $milk->category_id);
        $this->assertEquals($chocolateMilk->metric_id, $milk->metric_id);
    }

    /**
     * Test create variant
     * 
     * @covers ::createVariant
     * @covers ::isVariant
     *
     * @return void
     */
    public function testCreateVariant()
    {
        $category = $this->newCategory();
        $metric = $this->newMetric();

        $coke = Inventory::create([
            'name' => 'Coke',
            'sku' => $this->generateSku(),
            'description' => 'Delicious Pop',
            'metric_id' => $metric->id,
            'category_id' => $category->id,
        ]);

        $sku = $this->generateSku().'CHERRY-COKE';
        $name = 'Cherry Coke';
        $description = 'Delicious Cherry Coke';

        $cherryCoke = $coke->createVariant($sku, $name, $description);

        $this->assertTrue($cherryCoke->isVariant());
        $this->assertEquals($coke->id, $cherryCoke->parent_id);
        $this->assertEquals($cherryCoke->sku, $sku);
        $this->assertEquals($name, $cherryCoke->name);
        $this->assertEquals($description, $cherryCoke->description);
        $this->assertEquals($category->id, $cherryCoke->category_id);
        $this->assertEquals($metric->id, $cherryCoke->metric_id);
    }

    /**
     * Test make variant
     * 
     * @covers ::makeVariantOf
     *
     * @return void
     */
    public function testMakeVariant()
    {
        $category = $this->newCategory();
        $metric = $this->newMetric();

        $coke = Inventory::create([
            'name' => 'Coke',
            'sku' => $this->generateSku(),
            'description' => 'Delicious Pop',
            'metric_id' => $metric->id,
            'category_id' => $category->id,
        ]);

        $cherryCoke = Inventory::create([
            'name' => 'Cherry Coke',
            'sku' => $this->generateSku(),
            'description' => 'Delicious Cherry Coke',
            'metric_id' => $metric->id,
            'category_id' => $category->id,
        ]);

        $cherryCoke->makeVariantOf($coke);

        $this->assertEquals($cherryCoke->parent_id, $coke->id);
    }

    /**
     * Test is variant
     *
     * @covers ::isVariant
     * 
     * @return void
     */
    public function testIsVariant()
    {
        $category = $this->newCategory();
        $metric = $this->newMetric();

        $coke = Inventory::create([
            'name' => 'Coke',
            'sku' => $this->generateSku(),
            'description' => 'Delicious Pop',
            'metric_id' => $metric->id,
            'category_id' => $category->id,
        ]);

        $sku = $this->generateSku().'CHERRY-COKE';

        $cherryCoke = $coke->createVariant($sku, 'Cherry Coke');

        $cherryCoke->makeVariantOf($coke);

        $isCokeVariant = $coke->isVariant();
        $isCherryVariant = $cherryCoke->isVariant();

        $this->assertFalse($isCokeVariant);
        $this->assertTrue($isCherryVariant);
    }

    /**
     * Test get variants
     * 
     * @covers ::getVariants
     *
     * @return void
     */
    public function testGetVariants()
    {
        $category = $this->newCategory();
        $metric = $this->newMetric();

        $coke = Inventory::create([
            'name' => 'Coke',
            'sku' => $this->generateSku(),
            'description' => 'Delicious Pop',
            'metric_id' => $metric->id,
            'category_id' => $category->id,
        ]);

        $sku = $this->generateSku().'CHERRY-COKE';

        $cherryCoke = $coke->createVariant($sku, 'Cherry Coke');

        $cherryCoke->makeVariantOf($coke);

        $variants = $coke->getVariants();

        $this->assertInstanceOf('Illuminate\Support\Collection', $variants);
        $this->assertEquals(1, $variants->count());
    }

    /**
     * Test get parent
     * 
     * @covers ::getParent
     *
     * @return void
     */
    public function testGetParent()
    {
        $category = $this->newCategory();
        $metric = $this->newMetric();

        $coke = Inventory::create([
            'name' => 'Coke',
            'sku' => $this->generateSku(),
            'description' => 'Delicious Pop',
            'metric_id' => $metric->id,
            'category_id' => $category->id,
        ]);

        $cherryCoke = Inventory::create([
            'name' => 'Cherry Coke',
            'sku' => $this->generateSku(),
            'description' => 'Delicious Cherry Coke',
            'metric_id' => $metric->id,
            'category_id' => $category->id,
        ]);

        $cherryCoke->makeVariantOf($coke);

        $parent = $cherryCoke->getParent();

        $this->assertEquals('Coke', $parent->name);
        $this->assertEquals(null, $parent->parent_id);
    }

    /**
     * Test get total variant stock
     * 
     * @covers ::getTotalVariantStock
     *
     * @return void
     */
    public function testGetTotalVariantStock()
    {
        $category = $this->newCategory();
        $metric = $this->newMetric();

        $coke = Inventory::create([
            'name' => 'Coke',
            'sku' => $this->generateSku(),
            'description' => 'Delicious Pop',
            'metric_id' => $metric->id,
            'category_id' => $category->id,
        ]);

        $sku1 = $this->generateSku().'CHERRY-COKE';

        $cherryCoke = $coke->createVariant($sku1, 'Cherry Coke');

        $cherryCoke->makeVariantOf($coke);

        $sku2 = $this->generateSku().'VANILLA-CHERRY-COKE';

        $vanillaCherryCoke = $coke->createVariant($sku2, 'Vanilla Cherry Coke');

        $vanillaCherryCoke->makeVariantOf($coke);

        $location = $this->newLocation();

        $vanillaCherryCoke->createStockOnLocation(40, $location);

        $this->assertEquals(40, $coke->getTotalVariantStock());
        $this->assertEquals(0, $coke->getTotalStock());
    }

    /**
     * Test parents cannot be variants
     * 
     * @covers ::makeVariantOf
     * @covers \Stevebauman\Inventory\Exceptions\InvalidVariantException
     *
     * @return void
     */
    public function testParentsCannotBeVariants()
    {
        $category = $this->newCategory();
        $metric = $this->newMetric();

        $coke = Inventory::create([
            'name' => 'Coke',
            'sku' => $this->generateSku(),
            'description' => 'Delicious Pop',
            'metric_id' => $metric->id,
            'category_id' => $category->id,
        ]);

        $sku1 = $this->generateSku().'CHERRY-COKE';

        $cherryCoke = $coke->createVariant($sku1, 'Cherry Coke');

        $cherryCoke->makeVariantOf($coke);

        $this->expectException('Stevebauman\Inventory\Exceptions\InvalidVariantException');

        $sku2 = $this->generateSku().'VANILLA-CHERRY-COKE';

        $vanillaCherryCoke = $cherryCoke->createVariant($sku2, 'Vanilla Cherry Coke');

        $vanillaCherryCoke->makeVariantOf($cherryCoke);
    }

    /**
     * Test parents cannot have location
     * 
     * @covers \Stevebauman\Inventory\Traits\InventoryTrait::createStockOnLocation
     *
     * @return void
     */
    public function testParentsCannotHaveLocation() 
    {
        $location = $this->newLocation();

        $metric = $this->newMetric();

        $category = $this->newCategory();

        $coke = Inventory::create([
            'name' => 'Coke',
            'sku' => $this->generateSku(),
            'description' => 'Delicious Pop',
            'metric_id' => $metric->id,
            'category_id' => $category->id,
        ]);

        $sku = $this->generateSku().'CHERRY-COKE';

        $cherryCoke = $coke->createVariant($sku, 'Cherry Coke');

        $cherryCoke->makeVariantOf($coke);

        $this->expectException('Stevebauman\Inventory\Exceptions\IsParentException');

        $coke->createStockOnLocation(10, $location);
    }

    /**
     * Test cannot add supplier to parent
     * 
     * @covers \Stevebauman\Inventory\Traits\InventoryTrait::addSupplier
     *
     * @return void
     */
    public function testCannotAddSupplierToParent() 
    {
        $metric = $this->newMetric();

        $category = $this->newCategory();

        $supplier = $this->newSupplier();

        $coke = Inventory::create([
            'name' => 'Coke',
            'sku' => $this->generateSku(),
            'description' => 'Actually coke is kinda gross',
            'metric_id' => $metric->id,
            'category_id' => $category->id,
        ]);

        $sku = $this->generateSku().'CHERRY-COKE';

        $cherryCoke = $coke->createVariant($sku, 'Cherry Coke');

        $cherryCoke->makeVariantOf($coke);

        $this->expectException('Stevebauman\Inventory\Exceptions\IsParentException');

        $coke->addSupplier($supplier);
    }

    /**
     * Test cannot add suppliers to parent
     * 
     * @covers \Stevebauman\Inventory\Traits\InventoryTrait::addSuppliers
     *
     * @return void
     */
    public function testCannotAddSuppliersToParent() 
    {
        $metric = $this->newMetric();

        $category = $this->newCategory();

        $supplier1 = $this->newSupplier();

        $supplier2 = $this->newSupplier();

        $coke = Inventory::create([
            'name' => 'Coke',
            'sku' => $this->generateSku(),
            'description' => 'Actually coke is kinda gross',
            'metric_id' => $metric->id,
            'category_id' => $category->id,
        ]);

        $sku = $this->generateSku().'CHERRY-COKE';

        $cherryCoke = $coke->createVariant($sku, 'Cherry Coke');

        $cherryCoke->makeVariantOf($coke);

        $this->expectException('Stevebauman\Inventory\Exceptions\IsParentException');

        $coke->addSuppliers([$supplier1, $supplier2]);
    }

    /**
     * Test parents cannot create stock on location
     * 
     * @covers \Stevebauman\Inventory\Traits\InventoryTrait::createStockOnLocation
     *
     * @return void
     */
    public function testParentsCannotCreateStockOnLocation()
    {
        $metric = $this->newMetric();

        $category = $this->newCategory();

        $coke = Inventory::create([
            'name' => 'Coke',
            'sku' => $this->generateSku(),
            'description' => 'No really, I\'m getting sick of it',
            'metric_id' => $metric->id,
            'category_id' => $category->id,
        ]);

        $sku = $this->generateSku().'CHERRY-COKE';

        $cherryCoke = $coke->createVariant($sku, 'Cherry Coke');

        $cherryCoke->makeVariantOf($coke);

        $location = $this->newLocation();

        $this->expectException('Stevebauman\Inventory\Exceptions\IsParentException');

        $coke->createStockOnLocation(10, $location);
    }

    /**
     * Test parents cannot have new stock on location
     * 
     * @covers \Stevebauman\Inventory\Traits\InventoryTrait::newStockOnLocation
     *
     * @return void
     */
    public function testParentsCannotHaveNewStockOnLocation()
    {
        $metric = $this->newMetric();

        $category = $this->newCategory();

        $coke = Inventory::create([
            'name' => 'Coke',
            'sku' => $this->generateSku(),
            'description' => 'No really, I\'m getting sick of it',
            'metric_id' => $metric->id,
            'category_id' => $category->id,
        ]);

        $sku = $this->generateSku().'CHERRY-COKE';

        $cherryCoke = $coke->createVariant($sku, 'Cherry Coke');

        $cherryCoke->makeVariantOf($coke);

        $location = $this->newLocation();

        $this->expectException('Stevebauman\Inventory\Exceptions\IsParentException');

        $coke->newStockOnLocation($location);
    }

    /**
     * Test inventory becomes parent when variant added
     * 
     * @covers ::makeVariantOf
     *
     * @return void
     */
    public function testInventoryBecomesParentWhenVariantAdded() {
        $metric = $this->newMetric();
        
        $category = $this->newCategory();

        $coke = Inventory::create([
            'name' => 'Coke',
            'sku' => $this->generateSku(),
            'description' => 'Honestly why drink brown fizzy garbage water',
            'metric_id' => $metric->id,
            'category_id' => $category->id,
        ]);

        $sku = $this->generateSku().'CHERRY-COKE';

        $cherryCoke = $coke->createVariant($sku, 'Cherry Coke');

        $cherryCoke->makeVariantOf($coke);

        $this->assertTrue($coke->is_parent);
    }
}
