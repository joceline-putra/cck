-- 11 Agustus 2023
ALTER TABLE approvals ADD approval_comment VARCHAR(255) AFTER approval_date_action;
ALTER TABLE orders ADD order_approval_count INT(50) DEFAULT 0 COMMENT 'Trigger approval' AFTER order_contact_phone;
ALTER TABLE orders ADD order_files_count INT(50) DEFAULT 0 COMMENT 'Trigger files' AFTER order_approval_count;

ALTER TABLE trans ADD trans_approval_count INT(50) DEFAULT 0 COMMENT 'Trigger approval' AFTER trans_voucher_id;
ALTER TABLE trans ADD trans_files_count INT(50) DEFAULT 0 COMMENT 'Trigger files' AFTER trans_approval_count;

