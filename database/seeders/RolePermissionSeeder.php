<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds_
     */
    public function run(): void
    {
        $roles = [
            'Admin',
            'cashier',
        ];
        for ($i = 0; $i < count($roles); $i++) {
            $result = Role::firstOrCreate(['name' => $roles[$i]]);
        }
        //creates permission
        $permissions = [
            //dashboard
            'dashboard_view',
            //customer
            // 'customer_create',
            // 'customer_view',
            // 'customer_update',
            // 'customer_delete',
            // 'customer_sales',
            //supplier

            // 'supplier_view',
            // 'supplier_create',
            // 'supplier_update',
            // 'supplier_delete',
            //product
            'product_create',
            'product_view',
            'product_update',
            'product_delete',
            'product_import',
            //brand
            // 'brand_create',
            // 'brand_view',
            // 'brand_update',
            // 'brand_delete',
            //category
            'category_create',
            'category_view',
            'category_update',
            'category_delete',
            //sub category
            'sub_category_create',
            'sub_category_view',
            'sub_category_update',
            'sub_category_delete',
            //unit
            // 'unit_create',
            // 'unit_view',
            // 'unit_update',
            // 'unit_delete',
            //sale
            'sale_create',
            'sale_view',
            'sale_update',
            'sale_delete',
            //purchase
            // 'purchase_create',
            // 'purchase_view',
            // 'purchase_update',
            // 'purchase_delete',
            //reports
            'reports_summary',
            'reports_sales',
            'reports_inventory',
            //currency
            // 'currency_create',
            // 'currency_view',
            // 'currency_update',
            // 'currency_delete',
            // 'currency_set_default',
            //role
            'role_create',
            'role_view',
            'role_update',
            'role_delete',
            'permission_view',
            //user
            'user_create',
            'user_view',
            'user_update',
            'user_delete',
            'user_suspend',

            //setting
            'website_settings',
            'contact_settings',
            // 'socials_settings',
            // 'style_settings',
            // 'custom_settings',
            // 'notification_settings',
            // 'website_status_settings',
            'invoice_settings',

        ];
        $admin = Role::where('name', 'Admin')->first();
        for ($i = 0; $i < count($permissions); $i++) {
            $permission = Permission::firstOrCreate(['name' => $permissions[$i]]);
            $admin->givePermissionTo($permission);
            $permission->assignRole($admin);
        }

        // Create users and assign roles
        $cashierUser = User::create([
            'name' => 'Cashier',
            'email' => 'cashier@dar.net',
            'password' => bcrypt(12345678),
            'username' => uniqid(),
        ]);

        // Assign roles to users
        $cashierRole = Role::where('name', 'cashier')->first();
        $salesRole = Role::where('name', 'sales_associate')->first();

        $cashierUser->assignRole($cashierRole);

        // Optionally, assign permissions to the cashier and sales_associate roles
        // You can customize these permissions as needed
        $cashierPermissions = [
            'sale_create',
            'sale_view',
            'customer_view',
            'product_create',
            'product_view',
            'product_update',
            'product_delete',
            'product_import',
            'product_purchase',
        ];


        foreach ($cashierPermissions as $permissionName) {
            $permission = Permission::firstOrCreate(['name' => $permissionName]);
            $cashierRole->givePermissionTo($permission);
        }


    }
}
