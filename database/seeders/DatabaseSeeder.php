<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\CouponUserSeeder;
use Database\Seeders\CouponProductSeeder;
use Database\Seeders\CouponCategorySeeder;

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
            CouponSeeder::class,
            ProductCommentSeeder::class,
            CouponUserSeeder::class,
            CouponProductSeeder::class,
            CouponCategorySeeder::class,
            BannerSeeder::class,
            OrderSeeder::class,
            OrderItemSeeder::class,
            OrderReturnSeeder::class,
            NewsCategorySeeder::class,
            NewsSeeder::class,
            NewsCommentSeeder::class,
            ContactSeeder::class,
            PromotionSeeder::class,
            PromotionProductSeeder::class,
            CouponPromotionSeeder::class,
            PromotionCategorySeeder::class,
            MailTemplateSeeder::class,
            LogoSeeder::class,
        ]);
    }
}