<?php

namespace Api\V1\Services;

class ProductService {
    // This layer would handle complex business logic, third-party integrations, etc.
    public function calculateDiscount($price, $discountPercent) {
        return $price - ($price * ($discountPercent / 100));
    }

    public function formatProduct($product) {
        return [
            'id' => $product['id'],
            'title' => strtoupper($product['name']),
            'formatted_price' => '₹' . number_format($product['price']),
            'availability' => $product['stock'] > 0 ? 'In Stock' : 'Out of Stock'
        ];
    }
}
