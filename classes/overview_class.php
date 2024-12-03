<?php
require("../settings/db_class.php");

/**
 * Order class to handle all functions related to orders
 */
class overview_class extends db_connection{
    function getOverview(){
        $today_order_count = "SELECT COUNT(*) as count FROM orders WHERE DATE(order_date) = CURDATE()";
        $today_order_count_result = $this->db_fetch_one($today_order_count);
        $today_order_count = $today_order_count_result['count'];

        $total_earnings = "SELECT SUM(quantity * price) as total FROM order_details";
        $total_earnings_result = $this->db_fetch_one($total_earnings);
        $total_earnings = $total_earnings_result['total'];

        $total_customers = "SELECT COUNT(*) as count FROM customers";
        $total_customers_result = $this->db_fetch_one($total_customers);
        $total_customers = $total_customers_result['count'];

        $total_drivers = "SELECT COUNT(*) as count FROM drivers";
        $total_drivers_result = $this->db_fetch_one($total_drivers);
        $total_drivers = $total_drivers_result['count'];

        $total_providers = "SELECT COUNT(*) as count FROM providers";
        $total_providers_result = $this->db_fetch_one($total_providers);
        $total_providers = $total_providers_result['count'];

        $pending_deliveries = "SELECT COUNT(*) as count FROM deliveries WHERE delivery_status != 'Returned to customer'";
        $pending_deliveries_result = $this->db_fetch_one($pending_deliveries);
        $pending_deliveries = $pending_deliveries_result['count'];

        $total_payments = "SELECT SUM(amt) as total FROM payments";
        $total_payments_result = $this->db_fetch_one($total_payments);
        $total_payments = $total_payments_result['total'];

        $admin_cut = 20/100 * $total_payments;

        $total_users = $total_customers + $total_drivers + $total_providers;

        $info = array(
            'today_order_count' => $today_order_count,
            'total_earnings' => $total_earnings,
            'total_customers' => $total_customers,
            'total_drivers' => $total_drivers,
            'total_providers' => $total_providers,
            'pending_deliveries' => $pending_deliveries,
            'total_payments' => $total_payments,
            'admin_cut' => $admin_cut,
            'total_users' => $total_users
        );

        return $info;

    }
}