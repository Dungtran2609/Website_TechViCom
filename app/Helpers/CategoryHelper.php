<?php

if (!function_exists('getCategoryIcon')) {
    function getCategoryIcon($categoryName)
    {
        $categoryName = strtolower($categoryName);
        
        $iconMap = [
            'điện thoại' => 'fas fa-mobile-alt',
            'phone' => 'fas fa-mobile-alt',
            'smartphone' => 'fas fa-mobile-alt',
            'laptop' => 'fas fa-laptop',
            'máy tính' => 'fas fa-laptop',
            'computer' => 'fas fa-desktop',
            'tablet' => 'fas fa-tablet-alt',
            'ipad' => 'fas fa-tablet-alt',
            'máy tính bảng' => 'fas fa-tablet-alt',
            'âm thanh' => 'fas fa-headphones',
            'audio' => 'fas fa-headphones',
            'tai nghe' => 'fas fa-headphones',
            'loa' => 'fas fa-volume-up',
            'speaker' => 'fas fa-volume-up',
            'đồng hồ' => 'fas fa-clock',
            'watch' => 'fas fa-clock',
            'smart watch' => 'fas fa-clock',
            'gaming' => 'fas fa-gamepad',
            'game' => 'fas fa-gamepad',
            'phụ kiện' => 'fas fa-plug',
            'accessory' => 'fas fa-plug',
            'cáp' => 'fas fa-plug',
            'cable' => 'fas fa-plug',
            'sạc' => 'fas fa-battery-three-quarters',
            'charger' => 'fas fa-battery-three-quarters',
            'ốp lưng' => 'fas fa-shield-alt',
            'case' => 'fas fa-shield-alt',
            'tv' => 'fas fa-tv',
            'tivi' => 'fas fa-tv',
            'television' => 'fas fa-tv',
            'camera' => 'fas fa-camera',
            'máy ảnh' => 'fas fa-camera',
            'tủ lạnh' => 'fas fa-snowflake',
            'refrigerator' => 'fas fa-snowflake',
            'máy giặt' => 'fas fa-tshirt',
            'washing machine' => 'fas fa-tshirt',
            'điều hòa' => 'fas fa-fan',
            'air conditioner' => 'fas fa-fan',
            'ac' => 'fas fa-fan',
        ];

        // Tìm icon phù hợp
        foreach ($iconMap as $keyword => $icon) {
            if (strpos($categoryName, $keyword) !== false) {
                return $icon;
            }
        }

        // Default icon nếu không tìm thấy
        return 'fas fa-cube';
    }
}
