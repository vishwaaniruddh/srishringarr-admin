<?php

namespace Api\V1\Models;

use Api\V1\Core\Database;

class DashboardModel extends BaseModel {
    private $posDb;

    public function __construct() {
        parent::__construct(); // Default connection (u464193275_srishrinjewels)
        $this->posDb = Database::getInstance()->getConnection('pos'); // POS connection (u464193275_srishringarr)
    }

    public function getStats() {
        return [
            'summary' => $this->getSummaryStats(),
            'revenue_trend' => $this->getRevenueTrend(),
            'category_distribution' => $this->getCategoryDistribution(),
            'recent_orders' => $this->getRecentOrders(),
            'popular_products' => $this->getPopularProducts(),
            'payment_methods' => $this->getPaymentMethods(),
            'revenue_comparison' => $this->getRevenueComparison()
        ];
    }

    private function getSummaryStats() {
        // Total Orders & Trend (from POS DB)
        $currMonthOrders = $this->posDb->query("SELECT COUNT(*) FROM phppos_rent WHERE MONTH(bill_date) = MONTH(CURRENT_DATE) AND YEAR(bill_date) = YEAR(CURRENT_DATE)")->fetchColumn();
        $lastMonthOrders = $this->posDb->query("SELECT COUNT(*) FROM phppos_rent WHERE MONTH(bill_date) = MONTH(DATE_SUB(CURRENT_DATE, INTERVAL 1 MONTH)) AND YEAR(bill_date) = YEAR(DATE_SUB(CURRENT_DATE, INTERVAL 1 MONTH))")->fetchColumn();
        $totalOrders = $this->posDb->query("SELECT COUNT(*) FROM phppos_rent")->fetchColumn();
        
        // Revenue & Trend (from POS DB)
        $currMonthRev = $this->posDb->query("SELECT SUM(rent_amount) FROM phppos_rent WHERE MONTH(bill_date) = MONTH(CURRENT_DATE) AND YEAR(bill_date) = YEAR(CURRENT_DATE)")->fetchColumn() ?: 0;
        $lastMonthRev = $this->posDb->query("SELECT SUM(rent_amount) FROM phppos_rent WHERE MONTH(bill_date) = MONTH(DATE_SUB(CURRENT_DATE, INTERVAL 1 MONTH)) AND YEAR(bill_date) = YEAR(DATE_SUB(CURRENT_DATE, INTERVAL 1 MONTH))")->fetchColumn() ?: 0;
        
        // Active Products (from Primary DB)
        $jewelCount = $this->db->query("SELECT COUNT(*) FROM product")->fetchColumn();
        $garmentCount = $this->db->query("SELECT COUNT(*) FROM garment_product")->fetchColumn();
        
        // Active Rentals (from POS DB)
        $activeRentals = $this->posDb->query("SELECT COUNT(*) FROM phppos_rent WHERE pick_date <= CURRENT_DATE AND delivery_date >= CURRENT_DATE")->fetchColumn();
        $yesterdayRentals = $this->posDb->query("SELECT COUNT(*) FROM phppos_rent WHERE pick_date <= DATE_SUB(CURRENT_DATE, INTERVAL 1 DAY) AND delivery_date >= DATE_SUB(CURRENT_DATE, INTERVAL 1 DAY)")->fetchColumn();

        return [
            'orders' => [
                'total' => (int)$totalOrders,
                'current_month' => (int)$currMonthOrders,
                'trend' => $lastMonthOrders > 0 ? (($currMonthOrders - $lastMonthOrders) / $lastMonthOrders) * 100 : 0
            ],
            'revenue' => [
                'current_month' => (float)$currMonthRev,
                'trend' => $lastMonthRev > 0 ? (($currMonthRev - $lastMonthRev) / $lastMonthRev) * 100 : 0
            ],
            'products' => [
                'total' => $jewelCount + $garmentCount,
                'jewellery' => (int)$jewelCount,
                'garments' => (int)$garmentCount
            ],
            'rentals' => [
                'active' => (int)$activeRentals,
                'trend' => $yesterdayRentals > 0 ? (($activeRentals - $yesterdayRentals) / $yesterdayRentals) * 100 : 0
            ]
        ];
    }

    private function getRevenueTrend() {
        $sql = "SELECT DATE_FORMAT(bill_date, '%Y-%m') as month, SUM(rent_amount) as revenue 
                FROM phppos_rent 
                WHERE bill_date >= DATE_SUB(CURRENT_DATE, INTERVAL 12 MONTH)
                GROUP BY month ORDER BY month";
        $stmt = $this->posDb->query($sql);
        $data = $stmt->fetchAll();
        
        return [
            'labels' => array_map(function($r) { return date('M Y', strtotime($r['month'])); }, $data),
            'values' => array_map(function($r) { return (float)$r['revenue']; }, $data)
        ];
    }

    private function getCategoryDistribution() {
        $jewel = $this->db->query("SELECT COUNT(*) FROM product")->fetchColumn();
        $garment = $this->db->query("SELECT COUNT(*) FROM garment_product")->fetchColumn();
        return [
            'labels' => ['Jewellery', 'Garments'],
            'values' => [(int)$jewel, (int)$garment]
        ];
    }

    private function getRecentOrders() {
        $sql = "SELECT bill_id, cust_name, rent_amount, booking_status, bill_date, payment_mode_name 
                FROM phppos_rent ORDER BY bill_date DESC LIMIT 5";
        return $this->posDb->query($sql)->fetchAll();
    }

    private function getPopularProducts() {
        $sql = "SELECT item_id, COUNT(*) as count FROM order_detail GROUP BY item_id ORDER BY count DESC LIMIT 5";
        $popular = $this->posDb->query($sql)->fetchAll();
        
        foreach ($popular as &$p) {
            $stmt = $this->posDb->prepare("SELECT unit_price FROM phppos_items WHERE name = ?");
            $stmt->execute([$p['item_id']]);
            $p['price'] = $stmt->fetchColumn() ?: 0;
        }
        return $popular;
    }

    private function getPaymentMethods() {
        $sql = "SELECT payment_mode_name as label, COUNT(*) as value FROM phppos_rent GROUP BY label";
        return $this->posDb->query($sql)->fetchAll();
    }

    private function getRevenueComparison() {
        $currYear = date('Y');
        $prevYear = $currYear - 1;
        
        $sql = "SELECT YEAR(bill_date) as year, MONTH(bill_date) as month, SUM(rent_amount) as revenue 
                FROM phppos_rent WHERE YEAR(bill_date) IN (?, ?) 
                GROUP BY year, month ORDER BY year, month";
        $stmt = $this->posDb->prepare($sql);
        $stmt->execute([$currYear, $prevYear]);
        $data = $stmt->fetchAll();
        
        $currData = array_fill(0, 12, 0);
        $prevData = array_fill(0, 12, 0);
        
        foreach ($data as $r) {
            if ($r['year'] == $currYear) $currData[$r['month']-1] = (float)$r['revenue'];
            else $prevData[$r['month']-1] = (float)$r['revenue'];
        }
        
        return [
            'current' => $currData,
            'previous' => $prevData
        ];
    }
}
