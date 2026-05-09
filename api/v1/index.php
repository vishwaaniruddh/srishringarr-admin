<?php

// Include central bootstrap
require_once __DIR__ . '/../../bootstrap.php';

use Api\V1\Core\Router;
use Api\V1\Core\Response;

$router = new Router();

// Auth Routes
$router->add('POST', '/auth/login', 'AuthController@login');
$router->add('GET', '/auth/me', 'AuthController@me');

// Dashboard Routes
$router->add('GET', '/dashboard-stats', 'DashboardController@index');

// Product Routes
$router->add('GET', '/products', 'ProductController@index');
$router->add('GET', '/products/show', 'ProductController@show');
$router->add('POST', '/products/update', 'ProductController@update');
$router->add('POST', '/products/delete', 'ProductController@destroy');
$router->add('GET', '/inventory-stats', 'ProductController@stats');
$router->add('POST', '/products', 'ProductController@store');

$router->add('POST', '/media/upload', 'MediaController@upload');
$router->add('POST', '/media/delete', 'MediaController@delete');
$router->add('POST', '/media/set-primary', 'MediaController@setPrimary');

// Customer Routes
$router->add('GET', '/customers', 'CustomerController@index');
$router->add('GET', '/customers/show', 'CustomerController@show');

// Order Routes
$router->add('GET', '/orders', 'OrderController@index');
$router->add('GET', '/orders/show', 'OrderController@show');

// Category Routes
$router->add('GET', '/categories', 'CategoryController@index');

// Legacy Replica Routes
$router->add('GET', '/legacy/products', 'LegacyApiController@products');

// WooProduct Routes
$router->add('GET', '/wooproducts', 'WooProductController@index');

// Coupon Routes
$router->add('GET', '/coupons', 'CouponController@index');
$router->add('GET', '/coupons/(\d+)', 'CouponController@show');
$router->add('POST', '/coupons', 'CouponController@create');
$router->add('PUT', '/coupons/(\d+)', 'CouponController@update');
$router->add('DELETE', '/coupons/(\d+)', 'CouponController@delete');

// Discount Routes
$router->add('GET', '/discounts', 'DiscountController@index');
$router->add('GET', '/discounts/search-targets', 'DiscountController@searchTargets');
$router->add('POST', '/discounts', 'DiscountController@create');
$router->add('GET', '/discounts/(\d+)', 'DiscountController@show');
$router->add('PUT', '/discounts/(\d+)', 'DiscountController@update');
$router->add('DELETE', '/discounts/(\d+)', 'DiscountController@delete');

// Audit Routes
$router->add('GET', '/audit/logs', 'AuditController@logs');
$router->add('POST', '/audit/clear', 'AuditController@clear');

$router->run();
