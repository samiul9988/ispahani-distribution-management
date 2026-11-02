<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['key', 'label', 'group'])]
class Permission extends Model
{
    /**
     * The canonical list of permissions available in the application, grouped
     * the same way as the sidebar navigation. This is the single source of
     * truth used by the seeder, the roles & permissions screen, and the
     * `permission` route middleware.
     *
     * @return array<string, array<string, string>>
     */
    public static function registry(): array
    {
        return [
            'Masters' => [
                'products' => 'Products',
                'pack-types' => 'Pack & Quantity Types',
                'categories' => 'Categories',
                'suppliers' => 'Suppliers',
                'retailers' => 'Customers',
                'sales-reps' => 'Sales Reps (DSR)',
                'markets' => 'Markets',
            ],
            'Operations' => [
                'purchase-orders' => 'Purchase Orders',
                'daily-sales' => 'Sales',
                'liftings' => 'Lifting (Quick Entry)',
                'stock' => 'Stock Ledger',
                'free-offers' => 'Free Offers',
                'damaged-stock' => 'Damaged Stock',
                'damage-sendings' => 'Damage Sendings',
            ],
            'Accounting' => [
                'transactions' => 'Transactions',
                'cash-credit' => 'Cash & Credit',
                'cash-box' => 'Cash Box',
                'supplier-payments' => 'Supplier Payment',
                'incentives' => 'Incentive',
                'payments' => 'Payments',
                'expenses' => 'Expenses',
            ],
            'Reports' => [
                'reports' => 'Reports',
            ],
            'HRM' => [
                'staff-attendance' => 'Attendance',
                'staff-leave' => 'Leave',
                'staff-salary' => 'Staff Salary',
                'staff' => 'Staff List',
            ],
            'System' => [
                'audit-logs' => 'Audit Log',
                'company-profile' => 'Company Profile',
                'settings' => 'Settings',
                'support' => 'Support',
            ],
        ];
    }

    /**
     * All permission keys defined in the registry.
     *
     * @return array<int, string>
     */
    public static function keys(): array
    {
        return collect(self::registry())
            ->flatMap(fn (array $permissions) => array_keys($permissions))
            ->all();
    }
}
