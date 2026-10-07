-- --------------------------------------------------------
-- Flat-Kharch: Permission Master SQL Data
-- --------------------------------------------------------

INSERT INTO `permissions` (`name`, `guard_name`, `created_at`, `updated_at`) VALUES
-- Dashboard
('dashboard view', 'web', NOW(), NOW()),

-- User / Flat Member Management
('user list', 'web', NOW(), NOW()),
('user add', 'web', NOW(), NOW()),
('user edit', 'web', NOW(), NOW()),
('user delete', 'web', NOW(), NOW()),
('user status', 'web', NOW(), NOW()),

-- Monthly Pool / Fund
('monthly-pool list', 'web', NOW(), NOW()),
('monthly-pool add', 'web', NOW(), NOW()),
('monthly-pool edit', 'web', NOW(), NOW()),
('monthly-pool delete', 'web', NOW(), NOW()),

-- Cash Advance / Emergency Buffer
('cash-advance list', 'web', NOW(), NOW()),
('cash-advance add', 'web', NOW(), NOW()),
('cash-advance edit', 'web', NOW(), NOW()),
('cash-advance delete', 'web', NOW(), NOW()),

-- Expenses / Daily Kharch
('expense list', 'web', NOW(), NOW()),
('expense add', 'web', NOW(), NOW()),
('expense edit', 'web', NOW(), NOW()),
('expense delete', 'web', NOW(), NOW()),

-- Grocery & Supplies
('grocery list', 'web', NOW(), NOW()),
('grocery add', 'web', NOW(), NOW()),
('grocery edit', 'web', NOW(), NOW()),
('grocery delete', 'web', NOW(), NOW()),

-- Leave / Absence
('leave list', 'web', NOW(), NOW()),
('leave add', 'web', NOW(), NOW()),
('leave edit', 'web', NOW(), NOW()),
('leave delete', 'web', NOW(), NOW()),

-- Settlement & Reconciliation
('settlement list', 'web', NOW(), NOW()),
('settlement calculate', 'web', NOW(), NOW()),
('settlement close', 'web', NOW(), NOW()),

-- Reports & Exports
('report list', 'web', NOW(), NOW()),
('report export', 'web', NOW(), NOW()),

-- Role & System Permissions
('role list', 'web', NOW(), NOW()),
('role add', 'web', NOW(), NOW()),
('role edit', 'web', NOW(), NOW()),
('role delete', 'web', NOW(), NOW()),
('permission list', 'web', NOW(), NOW())
ON DUPLICATE KEY UPDATE `updated_at` = NOW();
