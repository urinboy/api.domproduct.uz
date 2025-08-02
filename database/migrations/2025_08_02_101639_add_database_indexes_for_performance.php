<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDatabaseIndexesForPerformance extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Raw SQL orqali indexlarni qo'shamiz
        $indexes = [
            // Categories
            "CREATE INDEX IF NOT EXISTS idx_categories_is_active ON categories(is_active)",
            "CREATE INDEX IF NOT EXISTS idx_categories_parent_active ON categories(parent_id, is_active)",
            "CREATE INDEX IF NOT EXISTS idx_categories_sort_order ON categories(sort_order)",

            // Products
            "CREATE INDEX IF NOT EXISTS idx_products_category_id ON products(category_id)",
            "CREATE INDEX IF NOT EXISTS idx_products_is_active ON products(is_active)",
            "CREATE INDEX IF NOT EXISTS idx_products_is_featured ON products(is_featured)",
            "CREATE INDEX IF NOT EXISTS idx_products_stock_status ON products(stock_status)",
            "CREATE INDEX IF NOT EXISTS idx_products_sku ON products(sku)",
            "CREATE INDEX IF NOT EXISTS idx_products_category_active ON products(category_id, is_active)",
            "CREATE INDEX IF NOT EXISTS idx_products_active_featured ON products(is_active, is_featured)",
            "CREATE INDEX IF NOT EXISTS idx_products_published_at ON products(published_at)",

            // Product translations
            "CREATE INDEX IF NOT EXISTS idx_product_trans_product_id ON product_translations(product_id)",
            "CREATE INDEX IF NOT EXISTS idx_product_trans_language ON product_translations(language)",
            "CREATE INDEX IF NOT EXISTS idx_product_trans_product_lang ON product_translations(product_id, language)",
            "CREATE INDEX IF NOT EXISTS idx_product_trans_slug ON product_translations(slug)",

            // Category translations
            "CREATE INDEX IF NOT EXISTS idx_category_trans_category_id ON category_translations(category_id)",
            "CREATE INDEX IF NOT EXISTS idx_category_trans_language_id ON category_translations(language_id)",
            "CREATE INDEX IF NOT EXISTS idx_category_trans_cat_lang ON category_translations(category_id, language_id)",
            "CREATE INDEX IF NOT EXISTS idx_category_trans_slug ON category_translations(slug)",

            // Orders
            "CREATE INDEX IF NOT EXISTS idx_orders_user_id ON orders(user_id)",
            "CREATE INDEX IF NOT EXISTS idx_orders_status ON orders(status)",
            "CREATE INDEX IF NOT EXISTS idx_orders_payment_status ON orders(payment_status)",
            "CREATE INDEX IF NOT EXISTS idx_orders_created_at ON orders(created_at)",
            "CREATE INDEX IF NOT EXISTS idx_orders_user_status ON orders(user_id, status)",
            "CREATE INDEX IF NOT EXISTS idx_orders_user_created ON orders(user_id, created_at)",
            "CREATE INDEX IF NOT EXISTS idx_orders_order_number ON orders(order_number)",

            // Order items
            "CREATE INDEX IF NOT EXISTS idx_order_items_order_id ON order_items(order_id)",
            "CREATE INDEX IF NOT EXISTS idx_order_items_product_id ON order_items(product_id)",
            "CREATE INDEX IF NOT EXISTS idx_order_items_order_product ON order_items(order_id, product_id)",

            // Cart items
            "CREATE INDEX IF NOT EXISTS idx_cart_items_cart_id ON cart_items(cart_id)",
            "CREATE INDEX IF NOT EXISTS idx_cart_items_product_id ON cart_items(product_id)",
            "CREATE INDEX IF NOT EXISTS idx_cart_items_cart_product ON cart_items(cart_id, product_id)",

            // Shopping carts
            "CREATE INDEX IF NOT EXISTS idx_shopping_carts_user_id ON shopping_carts(user_id)",
            "CREATE INDEX IF NOT EXISTS idx_shopping_carts_session_id ON shopping_carts(session_id)",
            "CREATE INDEX IF NOT EXISTS idx_shopping_carts_expires_at ON shopping_carts(expires_at)",

            // Users
            "CREATE INDEX IF NOT EXISTS idx_users_phone ON users(phone)",
            "CREATE INDEX IF NOT EXISTS idx_users_is_active ON users(is_active)",
            "CREATE INDEX IF NOT EXISTS idx_users_role ON users(role)",
            "CREATE INDEX IF NOT EXISTS idx_users_created_at ON users(created_at)",

            // Addresses
            "CREATE INDEX IF NOT EXISTS idx_addresses_user_id ON addresses(user_id)",
            "CREATE INDEX IF NOT EXISTS idx_addresses_type ON addresses(type)",
            "CREATE INDEX IF NOT EXISTS idx_addresses_is_default ON addresses(is_default)",
            "CREATE INDEX IF NOT EXISTS idx_addresses_is_active ON addresses(is_active)",
            "CREATE INDEX IF NOT EXISTS idx_addresses_user_type ON addresses(user_id, type)",
            "CREATE INDEX IF NOT EXISTS idx_addresses_user_default ON addresses(user_id, is_default)",

            // Notifications
            "CREATE INDEX IF NOT EXISTS idx_notifications_status ON notifications(status)",
            "CREATE INDEX IF NOT EXISTS idx_notifications_created_at ON notifications(created_at)",
            "CREATE INDEX IF NOT EXISTS idx_notifications_user_status ON notifications(user_id, status)",
            "CREATE INDEX IF NOT EXISTS idx_notifications_read_at ON notifications(read_at)",
        ];

        foreach ($indexes as $sql) {
            try {
                DB::statement($sql);
            } catch (\Exception $e) {
                // Index allaqachon mavjud bo'lsa, davom etamiz
                continue;
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Raw SQL orqali indexlarni o'chiramiz
        $indexes = [
            // Categories
            "DROP INDEX IF EXISTS idx_categories_is_active",
            "DROP INDEX IF EXISTS idx_categories_parent_active",
            "DROP INDEX IF EXISTS idx_categories_sort_order",

            // Products
            "DROP INDEX IF EXISTS idx_products_category_id",
            "DROP INDEX IF EXISTS idx_products_is_active",
            "DROP INDEX IF EXISTS idx_products_is_featured",
            "DROP INDEX IF EXISTS idx_products_stock_status",
            "DROP INDEX IF EXISTS idx_products_sku",
            "DROP INDEX IF EXISTS idx_products_category_active",
            "DROP INDEX IF EXISTS idx_products_active_featured",
            "DROP INDEX IF EXISTS idx_products_published_at",

            // Product translations
            "DROP INDEX IF EXISTS idx_product_trans_product_id",
            "DROP INDEX IF EXISTS idx_product_trans_language",
            "DROP INDEX IF EXISTS idx_product_trans_product_lang",
            "DROP INDEX IF EXISTS idx_product_trans_slug",

            // Category translations
            "DROP INDEX IF EXISTS idx_category_trans_category_id",
            "DROP INDEX IF EXISTS idx_category_trans_language_id",
            "DROP INDEX IF EXISTS idx_category_trans_cat_lang",
            "DROP INDEX IF EXISTS idx_category_trans_slug",

            // Orders
            "DROP INDEX IF EXISTS idx_orders_user_id",
            "DROP INDEX IF EXISTS idx_orders_status",
            "DROP INDEX IF EXISTS idx_orders_payment_status",
            "DROP INDEX IF EXISTS idx_orders_created_at",
            "DROP INDEX IF EXISTS idx_orders_user_status",
            "DROP INDEX IF EXISTS idx_orders_user_created",
            "DROP INDEX IF EXISTS idx_orders_order_number",

            // Order items
            "DROP INDEX IF EXISTS idx_order_items_order_id",
            "DROP INDEX IF EXISTS idx_order_items_product_id",
            "DROP INDEX IF EXISTS idx_order_items_order_product",

            // Cart items
            "DROP INDEX IF EXISTS idx_cart_items_cart_id",
            "DROP INDEX IF EXISTS idx_cart_items_product_id",
            "DROP INDEX IF EXISTS idx_cart_items_cart_product",

            // Shopping carts
            "DROP INDEX IF EXISTS idx_shopping_carts_user_id",
            "DROP INDEX IF EXISTS idx_shopping_carts_session_id",
            "DROP INDEX IF EXISTS idx_shopping_carts_expires_at",

            // Users
            "DROP INDEX IF EXISTS idx_users_phone",
            "DROP INDEX IF EXISTS idx_users_is_active",
            "DROP INDEX IF EXISTS idx_users_role",
            "DROP INDEX IF EXISTS idx_users_created_at",

            // Addresses
            "DROP INDEX IF EXISTS idx_addresses_user_id",
            "DROP INDEX IF EXISTS idx_addresses_type",
            "DROP INDEX IF EXISTS idx_addresses_is_default",
            "DROP INDEX IF EXISTS idx_addresses_is_active",
            "DROP INDEX IF EXISTS idx_addresses_user_type",
            "DROP INDEX IF EXISTS idx_addresses_user_default",

            // Notifications
            "DROP INDEX IF EXISTS idx_notifications_status",
            "DROP INDEX IF EXISTS idx_notifications_created_at",
            "DROP INDEX IF EXISTS idx_notifications_user_status",
            "DROP INDEX IF EXISTS idx_notifications_read_at",
        ];

        foreach ($indexes as $sql) {
            try {
                DB::statement($sql);
            } catch (\Exception $e) {
                // Index mavjud bo'lmasa ham davom etamiz
                continue;
            }
        }
    }
}
