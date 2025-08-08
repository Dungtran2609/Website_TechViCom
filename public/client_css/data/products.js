// Product data for Techvicom
window.PRODUCT_DATA = {
    // Sample product data
    products: [
        {
            id: 1,
            name: "iPhone 15 Pro Max 256GB",
            category: "phone",
            price: 34990000,
            originalPrice: 37990000,
            image: "assets/images/iphone-15-pro-max.jpg",
            rating: 4.8,
            reviews: 156,
            discount: 8,
            description: "iPhone 15 Pro Max với chip A17 Pro mạnh mẽ, camera 48MP",
            specifications: {
                screen: "6.7 inch, Super Retina XDR",
                chip: "Apple A17 Pro",
                ram: "8GB",
                storage: "256GB",
                camera: "Camera chính 48MP"
            },
            colors: ["Titan Tự Nhiên", "Titan Xanh", "Titan Trắng", "Titan Đen"],
            storageOptions: ["256GB", "512GB", "1TB"],
            inStock: true,
            brand: "Apple"
        },
        {
            id: 2,
            name: "Samsung Galaxy S24 Ultra 512GB",
            category: "phone",
            price: 33990000,
            originalPrice: 36990000,
            image: "assets/images/samsung-s24-ultra.jpg",
            rating: 4.7,
            reviews: 89,
            discount: 8,
            description: "Galaxy S24 Ultra với S Pen tích hợp, camera 200MP",
            specifications: {
                screen: "6.8 inch, Dynamic AMOLED 2X",
                chip: "Snapdragon 8 Gen 3",
                ram: "12GB",
                storage: "512GB",
                camera: "Camera chính 200MP"
            },
            colors: ["Phantom Black", "Phantom Silver", "Phantom Violet"],
            storageOptions: ["256GB", "512GB", "1TB"],
            inStock: true,
            brand: "Samsung"
        },
        {
            id: 3,
            name: "MacBook Air M3 13 inch 256GB",
            category: "laptop",
            price: 32990000,
            originalPrice: 34990000,
            image: "assets/images/macbook-air-m3.jpg",
            rating: 4.9,
            reviews: 234,
            discount: 6,
            description: "MacBook Air M3 siêu mỏng nhẹ, hiệu năng vượt trội",
            specifications: {
                screen: "13.6 inch, Liquid Retina",
                chip: "Apple M3",
                ram: "8GB",
                storage: "256GB SSD",
                battery: "Lên đến 18 giờ"
            },
            colors: ["Space Gray", "Silver", "Gold"],
            storageOptions: ["256GB", "512GB", "1TB"],
            inStock: true,
            brand: "Apple"
        },
        {
            id: 4,
            name: "iPad Pro M4 11 inch WiFi 256GB",
            category: "tablet",
            price: 28990000,
            originalPrice: 31990000,
            image: "assets/images/ipad-pro-m4.jpg",
            rating: 4.8,
            reviews: 67,
            discount: 9,
            description: "iPad Pro M4 với màn hình OLED tandem cực nét",
            specifications: {
                screen: "11 inch, OLED tandem",
                chip: "Apple M4",
                ram: "8GB",
                storage: "256GB",
                camera: "Camera sau 12MP"
            },
            colors: ["Space Gray", "Silver"],
            storageOptions: ["256GB", "512GB", "1TB", "2TB"],
            inStock: true,
            brand: "Apple"
        },
        {
            id: 5,
            name: "AirPods Pro 2 USB-C",
            category: "audio",
            price: 6190000,
            originalPrice: 6990000,
            image: "assets/images/airpods-pro-2.jpg",
            rating: 4.6,
            reviews: 445,
            discount: 11,
            description: "AirPods Pro 2 với tính năng khử tiếng ồn chủ động",
            specifications: {
                connectivity: "Bluetooth 5.3",
                battery: "Lên đến 6 giờ",
                features: "Khử tiếng ồn chủ động",
                charging: "USB-C",
                water: "IPX4"
            },
            colors: ["White"],
            inStock: true,
            brand: "Apple"
        },
        {
            id: 6,
            name: "Apple Watch Series 9 GPS 45mm",
            category: "watch",
            price: 10990000,
            originalPrice: 12990000,
            image: "assets/images/apple-watch-s9.jpg",
            rating: 4.7,
            reviews: 178,
            discount: 15,
            description: "Apple Watch Series 9 với chip S9 và màn hình sáng hơn",
            specifications: {
                screen: "45mm, Always-On Retina",
                chip: "Apple S9",
                battery: "Lên đến 18 giờ",
                features: "ECG, SpO2, GPS",
                water: "Chống nước 50m"
            },
            colors: ["Midnight", "Starlight", "Red"],
            inStock: true,
            brand: "Apple"
        },
        {
            id: 7,
            name: "Dell XPS 13 Plus",
            category: "laptop",
            price: 28990000,
            originalPrice: 31990000,
            image: "assets/images/dell-xps-13.jpg",
            rating: 4.5,
            reviews: 123,
            discount: 9,
            description: "Dell XPS 13 Plus với thiết kế premium và hiệu năng mạnh mẽ",
            specifications: {
                screen: "13.4 inch, InfinityEdge",
                chip: "Intel Core i7-1260P",
                ram: "16GB",
                storage: "512GB SSD",
                battery: "Lên đến 12 giờ"
            },
            colors: ["Platinum Silver", "Graphite"],
            storageOptions: ["256GB", "512GB", "1TB"],
            inStock: true,
            brand: "Dell"
        },
        {
            id: 8,
            name: "Sony WH-1000XM5",
            category: "audio",
            price: 8990000,
            originalPrice: 9990000,
            image: "assets/images/sony-wh1000xm5.jpg",
            rating: 4.8,
            reviews: 267,
            discount: 10,
            description: "Tai nghe chống ồn hàng đầu từ Sony với chất lượng âm thanh tuyệt vời",
            specifications: {
                connectivity: "Bluetooth 5.2",
                battery: "Lên đến 30 giờ",
                features: "Khử tiếng ồn V1 Processor",
                charging: "USB-C",
                weight: "250g"
            },
            colors: ["Black", "Silver"],
            inStock: true,
            brand: "Sony"
        }
    ],

    // Flash sale products
    flashSaleProducts: [
        { productId: 1, salePrice: 31990000, timeLeft: 43200 },
        { productId: 2, salePrice: 29990000, timeLeft: 43200 },
        { productId: 5, salePrice: 5490000, timeLeft: 43200 },
        { productId: 6, salePrice: 9990000, timeLeft: 43200 }
    ],

    // Categories
    categories: [
        { id: 'phone', name: 'Điện thoại', icon: 'fas fa-mobile-alt' },
        { id: 'laptop', name: 'Laptop', icon: 'fas fa-laptop' },
        { id: 'tablet', name: 'Tablet', icon: 'fas fa-tablet-alt' },
        { id: 'audio', name: 'Âm thanh', icon: 'fas fa-headphones' },
        { id: 'watch', name: 'Đồng hồ', icon: 'fas fa-clock' },
        { id: 'gaming', name: 'Gaming', icon: 'fas fa-gamepad' }
    ],

    // Reviews data
    reviews: [
        {
            id: 1,
            productId: 1,
            userId: 101,
            userName: "Nguyễn Văn A",
            rating: 5,
            title: "Sản phẩm tuyệt vời!",
            content: "iPhone 15 Pro Max thực sự xuất sắc. Camera rất sắc nét, hiệu năng mạnh mẽ, pin trâu. Rất đáng đồng tiền bát gạo!",
            date: "2025-01-15",
            verified: true,
            helpful: 24,
            images: ["review1.jpg", "review2.jpg"]
        },
        {
            id: 2,
            productId: 1,
            userId: 102,
            userName: "Trần Thị B",
            rating: 4,
            title: "Tốt nhưng giá hơi cao",
            content: "Chất lượng rất tốt, thiết kế đẹp. Tuy nhiên giá hơi cao so với mặt bằng chung.",
            date: "2025-01-10",
            verified: true,
            helpful: 12
        },
        {
            id: 3,
            productId: 2,
            userId: 103,
            userName: "Lê Văn C",
            rating: 5,
            title: "Samsung Galaxy S24 Ultra quá đỉnh",
            content: "S Pen rất tiện lợi, camera zoom 100x ấn tượng. Màn hình đẹp, hiệu năng mượt mà.",
            date: "2025-01-12",
            verified: true,
            helpful: 18
        }
    ],

    // User data (demo)
    users: [
        {
            id: 101,
            name: "Nguyễn Văn A",
            email: "nguyenvana@email.com",
            avatar: "assets/images/avatar1.jpg"
        },
        {
            id: 102,
            name: "Trần Thị B",
            email: "tranthib@email.com",
            avatar: "assets/images/avatar2.jpg"
        }
    ]
};

// Helper functions
window.PRODUCT_UTILS = {
    // Get product by ID
    getProductById: function(id) {
        return window.PRODUCT_DATA.products.find(p => p.id === parseInt(id));
    },

    // Get products by category
    getProductsByCategory: function(category) {
        return window.PRODUCT_DATA.products.filter(p => p.category === category);
    },

    // Get flash sale products
    getFlashSaleProducts: function() {
        return window.PRODUCT_DATA.flashSaleProducts.map(flash => {
            const product = this.getProductById(flash.productId);
            return {
                ...product,
                salePrice: flash.salePrice,
                timeLeft: flash.timeLeft
            };
        });
    },

    // Get reviews for product
    getReviewsForProduct: function(productId) {
        return window.PRODUCT_DATA.reviews.filter(r => r.productId === parseInt(productId));
    },

    // Format currency
    formatCurrency: function(amount) {
        try {
            if (!amount || isNaN(amount)) {
                return '0đ';
            }
            return new Intl.NumberFormat('vi-VN', {
                style: 'currency',
                currency: 'VND'
            }).format(amount);
        } catch (error) {
            return `${(amount || 0).toLocaleString()}đ`;
        }
    },

    // Create star rating HTML
    createStarRating: function(rating) {
        const fullStars = Math.floor(rating);
        const hasHalfStar = rating % 1 !== 0;
        let starsHtml = '';
        
        for (let i = 0; i < fullStars; i++) {
            starsHtml += '<i class="fas fa-star"></i>';
        }
        
        if (hasHalfStar) {
            starsHtml += '<i class="fas fa-star-half-alt"></i>';
        }
        
        const emptyStars = 5 - Math.ceil(rating);
        for (let i = 0; i < emptyStars; i++) {
            starsHtml += '<i class="far fa-star"></i>';
        }
        
        return starsHtml;
    }
};
