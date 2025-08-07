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
            RoleSeeder::class,
            PermissionSeeder::class,
            PermissionRoleSeeder::class,
            UserRoleSeeder::class,
            ShippingMethodSeeder::class,
            BrandSeeder::class,
            CategorySeeder::class,
            AttributeSeeder::class,
            AttributeValueSeeder::class,
            ProductSeeder::class,
            ProductCommentSeeder::class,
            CouponSeeder::class,
            BannerSeeder::class,
            OrderSeeder::class,
            OrderItemSeeder::class,
            OrderReturnSeeder::class,
            NewsCategorySeeder::class,
            NewsSeeder::class,
            NewsCommentSeeder::class,
            ContactSeeder::class,
        ]);
    }
}
