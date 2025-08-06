<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            UserAddressSeeder::class,
            UserRoleSeeder::class,
            RoleSeeder::class,
            PermissionSeeder::class,
            PermissionRoleSeeder::class,
            ShippingMethodSeeder::class,
            BrandSeeder::class,
            CategorySeeder::class,
            AttributeSeeder::class,
            AttributeValueSeeder::class,
            ProductSeeder::class,
            CouponSeeder::class,
            OrderSeeder::class,
            OrderItemSeeder::class,
            OrderReturnSeeder::class,
            NewsCategorySeeder::class,
            NewsSeeder::class,
            NewsCommentSeeder::class,
            ContactSeeder::class,
            CouponSeeder::class,
        ]);
    }
}
