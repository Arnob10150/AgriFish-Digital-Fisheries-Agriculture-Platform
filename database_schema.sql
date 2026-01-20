CREATE TABLE IF NOT EXISTS users (
    user_id INT PRIMARY KEY AUTO_INCREMENT,
    email VARCHAR(255) UNIQUE,
    mobile_number VARCHAR(15) UNIQUE,
    password VARCHAR(255),
    full_name VARCHAR(100),
    role ENUM('customer', 'fisherman', 'farmer', 'admin') NOT NULL,
    location VARCHAR(255),
    language_preference ENUM('bengali', 'english') DEFAULT 'bengali',
    profile_picture VARCHAR(255),
    is_verified BOOLEAN DEFAULT FALSE,
    account_status ENUM('active', 'suspended', 'locked', 'pending') DEFAULT 'active',
    failed_login_attempts INT DEFAULT 0,
    locked_until DATETIME NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_mobile (mobile_number),
    INDEX idx_role (role)
);

CREATE TABLE IF NOT EXISTS user_sessions (
    session_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    session_token VARCHAR(255) UNIQUE NOT NULL,
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    expires_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    INDEX idx_token (session_token)
);

CREATE TABLE IF NOT EXISTS system_logs (
    log_id INT PRIMARY KEY AUTO_INCREMENT,
    level ENUM('info', 'warning', 'error') DEFAULT 'info',
    message TEXT,
    context JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_level (level),
    INDEX idx_created (created_at)
);

INSERT IGNORE INTO users (email, password, mobile_number, full_name, role, is_verified, account_status) VALUES
('admin@dfap.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '01512345678', 'System Administrator', 'admin', TRUE, 'active');

INSERT IGNORE INTO users (email, password, mobile_number, full_name, role, location, is_verified, account_status) VALUES
('customer@dfap.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '01712345678', 'Rahim Customer', 'customer', 'Dhaka', TRUE, 'active'),
('fisherman@dfap.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '01812345678', 'Karim Fisherman', 'fisherman', 'Cox\'s Bazar', TRUE, 'active'),
('farmer@dfap.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '01912345678', 'Farmer Hassan', 'farmer', 'Khulna', TRUE, 'active');

CREATE INDEX IF NOT EXISTS idx_users_mobile_role ON users(mobile_number, role);
CREATE INDEX IF NOT EXISTS idx_users_status ON users(account_status);
CREATE INDEX IF NOT EXISTS idx_sessions_user_expires ON user_sessions(user_id, expires_at);

DELIMITER $$
CREATE EVENT IF NOT EXISTS cleanup_expired_sessions
ON SCHEDULE EVERY 1 DAY
DO
BEGIN
    DELETE FROM user_sessions WHERE expires_at < NOW();
END$$
DELIMITER ;

ALTER TABLE products ADD COLUMN IF NOT EXISTS approval_status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending' AFTER is_active;

ALTER TABLE products ALTER COLUMN approval_status SET DEFAULT 'pending';

CREATE INDEX IF NOT EXISTS idx_approval ON products(approval_status);

UPDATE products SET approval_status = 'approved' WHERE approval_status = 'pending' OR approval_status IS NULL;

CREATE TABLE IF NOT EXISTS products (
    product_id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    category ENUM('Freshwater', 'Sea Fish', 'Shellfish', 'Dried Fish', 'Frozen') NOT NULL,
    image VARCHAR(255) DEFAULT '🐟',
    stock_quantity INT DEFAULT 0,
    unit VARCHAR(50) DEFAULT 'kg',
    seller_id INT,
    is_active BOOLEAN DEFAULT TRUE,
    approval_status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (seller_id) REFERENCES users(user_id) ON DELETE SET NULL,
    INDEX idx_category (category),
    INDEX idx_active (is_active),
    INDEX idx_seller (seller_id),
    INDEX idx_approval (approval_status)
);

INSERT IGNORE INTO products (name, description, price, category, stock_quantity, unit, seller_id, is_active, approval_status) VALUES
('Ilish (Hilsa)', 'Premium Hilsa fish from the Padma river', 2400.00, 'Freshwater', 50, 'kg', 2, 1, 'approved'),
('Rui (River)', 'Fresh Rui fish from local farms', 750.00, 'Freshwater', 100, 'kg', 3, 1, 'approved'),
('Katla (River)', 'Large Katla fish', 750.00, 'Freshwater', 80, 'kg', 3, 1, 'approved'),
('Ayre (Giant Catfish)', 'Giant catfish from river systems', 1500.00, 'Freshwater', 30, 'kg', 4, 1, 'approved'),
('Chitol (Featherback)', 'Unique featherback fish', 1250.00, 'Freshwater', 40, 'kg', 4, 1, 'approved'),
('Boal (Wallago)', 'Wallago catfish', 800.00, 'Freshwater', 60, 'kg', 2, 1, 'approved'),
('Shing (Stinging Catfish)', 'Freshwater catfish', 570.00, 'Freshwater', 70, 'kg', 3, 1, 'approved'),
('Pabda (Pabo Catfish)', 'Small catfish variety', 450.00, 'Freshwater', 90, 'kg', 4, 1, 'approved'),
('Rupchanda (Pomfret)', 'Silver pomfret from sea', 1200.00, 'Sea Fish', 35, 'kg', 2, 1, 'approved'),
('Koral (Seabass)', 'Fresh seabass', 800.00, 'Sea Fish', 45, 'kg', 3, 1, 'approved'),

('Fresh Tilapia', 'Farm-raised tilapia fish', 350.00, 'Freshwater', 25, 'kg', 2, 1, 'pending'),
('Organic Vegetables', 'Fresh organic vegetables from local farm', 150.00, 'Vegetables', 50, 'kg', 4, 1, 'pending'),
('Shrimp (Large)', 'Premium large shrimp', 1800.00, 'Sea Fish', 15, 'kg', 3, 1, 'pending'),
('Tuna', 'Fresh tuna fish', 500.00, 'Sea Fish', 25, 'kg', 2, 1, 'approved'),
('Loitta (Bombay Duck)', 'Dried bombay duck', 350.00, 'Sea Fish', 55, 'kg', 3, 1, 'approved'),
('Surma (King Fish)', 'King fish from deep sea', 600.00, 'Sea Fish', 20, 'kg', 2, 1, 'approved'),
('Poa (Yellow Croaker)', 'Yellow croaker fish', 550.00, 'Sea Fish', 40, 'kg', 3, 1, 'approved'),
('Golda Chingri (Prawn)', 'Large tiger prawns', 1350.00, 'Shellfish', 30, 'kg', 4, 1, 'approved'),
('Bagda/Tiger Shrimp', 'Fresh tiger shrimp', 1000.00, 'Shellfish', 50, 'kg', 2, 1, 'approved'),
('Lobster', 'Fresh lobster', 2000.00, 'Shellfish', 15, 'kg', 3, 1, 'approved'),
('Crab (Mud/Blue)', 'Fresh crabs', 700.00, 'Shellfish', 25, 'kg', 4, 1, 'approved'),
('Churi Shutki (Dried)', 'Dried fish product', 1200.00, 'Dried Fish', 60, 'kg', 2, 1, 'approved'),
('Basa/Dory Fillet', 'Frozen fish fillet', 580.00, 'Frozen', 75, 'kg', 3, 1, 'approved');

CREATE TABLE IF NOT EXISTS notices (
    notice_id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    category ENUM('all', 'customer', 'fisherman', 'farmer', 'admin', 'government_ngo') NOT NULL DEFAULT 'all',
    created_by INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_created_by (created_by),
    INDEX idx_created_at (created_at),
    INDEX idx_category (category)
);
