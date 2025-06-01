INSERT INTO `adminusers` (
    `admin_id`, 
    `username`, 
    `password`, 
    `email`, 
    `remember_token`, 
    `otp_code`, 
    `otp_expires_at`, 
    `is_verified`, 
    `reset_token`, 
    `reset_token_expires_at`, 
    `status`, 
    `role`, 
    `created_at`, 
    `updated_at`
) VALUES (
    1, 
    'apeli', 
    '$2y$10$bS1g5pBmcHF6CSPoB0Q/nuklo7xZCGiprBN5ZjsZK3BGSTIhuvaTS', -- hashed password
    'info@apelisolutions.com', 
    NULL, 
    NULL, 
    NULL, 
    1, 
    NULL, 
    NULL, 
    'active', 
    'super_admin', 
    NOW(), 
    NOW()
);
