/*
SQLyog Enterprise v13.1.1 (64 bit)
MySQL - 8.0.27 : Database - jrn
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

/*Table structure for table `accounts` */

DROP TABLE IF EXISTS `accounts`;

CREATE TABLE `accounts` (
  `account_id` bigint NOT NULL AUTO_INCREMENT,
  `account_branch_id` int DEFAULT '0',
  `account_parent_id` bigint DEFAULT NULL,
  `account_group` int DEFAULT NULL COMMENT '1=Asset,2=Liability,3=Equity,4=Pendapatan,5=Biaya',
  `account_group_sub` int DEFAULT NULL,
  `account_group_sub_name` varchar(255) DEFAULT NULL,
  `account_code` varchar(255) DEFAULT NULL,
  `account_name` varchar(255) DEFAULT NULL,
  `account_side` int DEFAULT NULL COMMENT '1=Debit, 2=Kredit',
  `account_show` int DEFAULT NULL COMMENT '1=Show, 2=Hide',
  `account_tree` int DEFAULT NULL COMMENT 'Sort Asc',
  `account_saldo` varchar(255) DEFAULT '0',
  `account_info` varchar(255) DEFAULT NULL,
  `account_user_id` bigint DEFAULT NULL,
  `account_date_created` datetime DEFAULT NULL,
  `account_date_updated` datetime DEFAULT NULL,
  `account_flag` int DEFAULT '1' COMMENT '1=Aktif, 0=Delete',
  `account_session` varchar(255) DEFAULT NULL,
  `account_locked` int DEFAULT '0' COMMENT '1=Lock, 0=Free',
  PRIMARY KEY (`account_id`) USING BTREE,
  KEY `PARENT` (`account_parent_id`) USING BTREE,
  KEY `USER` (`account_user_id`) USING BTREE,
  KEY `BRANCH` (`account_branch_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `accounts_groups` */

DROP TABLE IF EXISTS `accounts_groups`;

CREATE TABLE `accounts_groups` (
  `group_id` int NOT NULL AUTO_INCREMENT,
  `group_group_id` int DEFAULT NULL,
  `group_group_sub_id` int DEFAULT NULL,
  `group_name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `accounts_maps` */

DROP TABLE IF EXISTS `accounts_maps`;

CREATE TABLE `accounts_maps` (
  `account_map_id` bigint NOT NULL AUTO_INCREMENT,
  `account_map_branch_id` bigint DEFAULT NULL,
  `account_map_account_id` bigint DEFAULT NULL,
  `account_map_for_transaction` bigint DEFAULT NULL COMMENT '1=Pembelian, 2=Penjualan, 3=Persediaan, 4=Stok Opname, 10=Other',
  `account_map_type` bigint DEFAULT NULL,
  `account_map_flag` int DEFAULT NULL,
  `account_map_note` varchar(255) DEFAULT NULL,
  `account_map_formula` varchar(10) DEFAULT NULL COMMENT 'D=Debit, C=Credit',
  `account_map_date_created` datetime DEFAULT NULL,
  `account_map_date_updated` datetime DEFAULT NULL,
  `account_map_session` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`account_map_id`) USING BTREE,
  KEY `ACCOUNT` (`account_map_account_id`) USING BTREE,
  KEY `BRANCH` (`account_map_branch_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Table structure for table `activities` */

DROP TABLE IF EXISTS `activities`;

CREATE TABLE `activities` (
  `activity_id` bigint NOT NULL AUTO_INCREMENT,
  `activity_branch_id` int DEFAULT '0',
  `activity_user_id` bigint DEFAULT NULL,
  `activity_action` int DEFAULT '0' COMMENT '0=none, 1=login, 2=create, 3=read, 4=update, 5=delete, 6=print, 7=aktifkan, 8=nonaktifkan, 9=pengajuan_persetujuan',
  `activity_table` varchar(255) DEFAULT NULL,
  `activity_table_id` bigint DEFAULT NULL,
  `activity_text_1` varchar(255) DEFAULT NULL,
  `activity_text_2` varchar(255) DEFAULT NULL,
  `activity_text_3` varchar(255) DEFAULT NULL,
  `activity_text_4` varchar(255) DEFAULT NULL COMMENT 'Approve, Tunda, Tolak, Hapus',
  `activity_text_5` varchar(255) DEFAULT NULL COMMENT 'Komentar Approval',
  `activity_date_created` datetime DEFAULT NULL,
  `activity_flag` int DEFAULT '0' COMMENT '1=Show, 0=Hide',
  `activity_transaction` varchar(255) DEFAULT NULL,
  `activity_type` int DEFAULT '1' COMMENT '1=Master, 2=Transaction, 3=Report',
  `activity_remote_addr` varchar(255) DEFAULT NULL,
  `activity_user_agent` varchar(255) DEFAULT NULL,
  `activity_http_referer` varchar(255) DEFAULT NULL,
  `activity_icon` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`activity_id`) USING BTREE,
  KEY `USER` (`activity_user_id`) USING BTREE,
  KEY `BRANCH` (`activity_branch_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `app_branch_billings` */

DROP TABLE IF EXISTS `app_branch_billings`;

CREATE TABLE `app_branch_billings` (
  `app_branch_billing_id` bigint NOT NULL AUTO_INCREMENT,
  `app_branch_billing_app_package_id` bigint DEFAULT NULL,
  `app_branch_billing_branch_id` bigint DEFAULT NULL,
  `app_branch_billing_number` varchar(255) DEFAULT NULL,
  `app_branch_billing_date` datetime DEFAULT NULL,
  `app_branch_billing_uniq_nominal` varchar(255) DEFAULT NULL,
  `app_branch_billing_total` varchar(255) DEFAULT NULL,
  `app_branch_billing_is_paid` int DEFAULT NULL,
  `app_branch_billing_start` datetime DEFAULT NULL,
  `app_branch_billing_end` datetime DEFAULT NULL,
  `app_branch_billing_date_created` datetime DEFAULT NULL,
  `app_branch_billing_flag` int DEFAULT NULL,
  PRIMARY KEY (`app_branch_billing_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Table structure for table `app_packages` */

DROP TABLE IF EXISTS `app_packages`;

CREATE TABLE `app_packages` (
  `app_package_id` bigint NOT NULL AUTO_INCREMENT,
  `app_package_name` varchar(255) DEFAULT NULL,
  `app_package_description` text,
  `app_package_publish_price` varchar(255) DEFAULT NULL,
  `app_package_promo_price` varchar(255) DEFAULT NULL,
  `app_package_flag` int DEFAULT NULL,
  `app_package_date_created` datetime DEFAULT NULL,
  PRIMARY KEY (`app_package_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Table structure for table `app_packages_items` */

DROP TABLE IF EXISTS `app_packages_items`;

CREATE TABLE `app_packages_items` (
  `item_id` int NOT NULL AUTO_INCREMENT,
  `item_app_package_id` int DEFAULT NULL,
  `maximal_user_data` varchar(10) DEFAULT NULL,
  `maximal_location_data` varchar(10) DEFAULT NULL,
  `maximal_product_data` varchar(10) DEFAULT NULL,
  `maximal_contact_data` varchar(10) DEFAULT NULL,
  `transaction_buy_sell` varchar(10) DEFAULT NULL,
  `transaction_operational_cost` varchar(10) DEFAULT NULL,
  `transaction_cash_bank` varchar(10) DEFAULT NULL,
  `transaction_receivable_payable` varchar(10) DEFAULT NULL,
  `transaction_inventory` varchar(10) DEFAULT NULL,
  `transaction_production` varchar(10) DEFAULT NULL,
  `support_fixed_asset` varchar(10) DEFAULT NULL,
  `support_point_of_sale` varchar(10) DEFAULT NULL,
  `support_approval` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`item_id`),
  KEY `PACKAGE` (`item_app_package_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Table structure for table `app_vouchers` */

DROP TABLE IF EXISTS `app_vouchers`;

CREATE TABLE `app_vouchers` (
  `app_voucher_id` bigint NOT NULL AUTO_INCREMENT,
  `app_voucher_type` int DEFAULT NULL,
  `app_voucher_code` varchar(255) DEFAULT NULL,
  `app_voucher_total` varchar(255) DEFAULT NULL,
  `app_voucher_flag` int DEFAULT NULL,
  `app_voucher_date_created` datetime DEFAULT NULL,
  `app_voucher_user_id` bigint DEFAULT NULL,
  PRIMARY KEY (`app_voucher_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Table structure for table `approvals` */

DROP TABLE IF EXISTS `approvals`;

CREATE TABLE `approvals` (
  `approval_id` bigint NOT NULL AUTO_INCREMENT,
  `approval_user_id` bigint DEFAULT NULL COMMENT 'User Approval',
  `approval_user_from` bigint DEFAULT NULL COMMENT 'User Request',
  `approval_level` int DEFAULT '1' COMMENT '1=Tingkat 1, 2=Tingkat 2',
  `approval_from_table` enum('users','contacts','products','orders','trans','journals','others','news') DEFAULT NULL COMMENT '1=Order, 2=Trans, 3=Journals',
  `approval_from_id` bigint DEFAULT NULL COMMENT 'ID From Order, Trans, Journal',
  `approval_date_created` datetime DEFAULT NULL,
  `approval_session` varchar(255) DEFAULT NULL COMMENT 'Session Approval',
  `approval_flag` int DEFAULT '0' COMMENT '0=Request, 1=Approve, 2=Pending, 3=Denied, 4=Delete by User',
  `approval_date_action` datetime DEFAULT NULL,
  `approval_comment` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`approval_id`),
  KEY `APPROVAL_ID` (`approval_id`) USING BTREE,
  KEY `USER` (`approval_user_id`) USING BTREE,
  KEY `FROM_SESSION` (`approval_from_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `balances` */

DROP TABLE IF EXISTS `balances`;

CREATE TABLE `balances` (
  `balance_id` bigint NOT NULL AUTO_INCREMENT,
  `balance_type` int DEFAULT NULL,
  `balance_type_name` varchar(255) DEFAULT NULL,
  `balance_date` datetime DEFAULT NULL,
  `balance_number` varchar(255) DEFAULT NULL,
  `balance_debit` double(18,0) DEFAULT '0',
  `balance_credit` double(18,0) DEFAULT '0',
  `balance_note` varchar(255) DEFAULT NULL,
  `balance_flag` int DEFAULT NULL COMMENT '0=Pending, 1=Valid, 4=Delete',
  `balance_date_created` datetime DEFAULT NULL,
  `balance_date_due` datetime NOT NULL,
  `balance_session` varchar(255) DEFAULT NULL,
  `balance_user_session` varchar(255) DEFAULT NULL COMMENT 'Join User',
  `balance_bank_session` varchar(255) DEFAULT NULL COMMENT 'Join Bank',
  `balance_position` int DEFAULT NULL COMMENT '1=In, 2=Out',
  `balance_user_id` bigint DEFAULT NULL COMMENT 'TRIGGER',
  `balance_bank_id` bigint DEFAULT NULL COMMENT 'TRIGGER',
  `balance_date_delete_by_sp` datetime DEFAULT NULL COMMENT 'CRON n SP',
  `balance_uniq` int DEFAULT '0',
  `balance_sp_session` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`balance_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `banks` */

DROP TABLE IF EXISTS `banks`;

CREATE TABLE `banks` (
  `bank_id` bigint NOT NULL AUTO_INCREMENT,
  `bank_category_id` bigint DEFAULT NULL,
  `bank_session` varchar(255) DEFAULT NULL,
  `bank_account_name` varchar(255) DEFAULT NULL,
  `bank_account_number` varchar(255) DEFAULT NULL,
  `bank_account_username` varchar(255) DEFAULT NULL,
  `bank_account_password` varchar(255) DEFAULT NULL,
  `bank_account_business` varchar(255) DEFAULT NULL,
  `bank_minute_interval` int DEFAULT NULL COMMENT 'Not Used',
  `bank_email_notification` varchar(255) DEFAULT NULL,
  `bank_phone_notification` varchar(255) DEFAULT NULL,
  `bank_date_created` datetime DEFAULT NULL,
  `bank_date_updated` datetime DEFAULT NULL,
  `bank_flag` varchar(255) DEFAULT NULL,
  `bank_user_session` varchar(255) DEFAULT NULL,
  `bank_api_id` int DEFAULT NULL,
  `bank_api_package` varchar(255) DEFAULT NULL,
  `bank_api_last_check` datetime DEFAULT NULL,
  PRIMARY KEY (`bank_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Table structure for table `branchs` */

DROP TABLE IF EXISTS `branchs`;

CREATE TABLE `branchs` (
  `branch_id` bigint NOT NULL AUTO_INCREMENT,
  `branch_user_id` bigint DEFAULT NULL COMMENT 'id_user penanggung jawab',
  `branch_code` varchar(255) DEFAULT NULL,
  `branch_name` varchar(255) DEFAULT NULL,
  `branch_note` varchar(255) DEFAULT NULL,
  `branch_specialist_id` bigint DEFAULT NULL,
  `branch_slogan` varchar(255) DEFAULT NULL,
  `branch_address` varchar(255) DEFAULT NULL,
  `branch_phone_1` varchar(255) DEFAULT NULL,
  `branch_phone_2` varchar(255) DEFAULT NULL,
  `branch_email_1` varchar(255) DEFAULT NULL,
  `branch_email_2` varchar(255) DEFAULT NULL,
  `branch_district` varchar(255) DEFAULT NULL,
  `branch_city` varchar(255) DEFAULT NULL,
  `branch_province` varchar(255) DEFAULT NULL,
  `branch_logo` text,
  `branch_logo_sidebar` text,
  `branch_date_created` datetime DEFAULT NULL,
  `branch_date_updated` datetime DEFAULT NULL,
  `branch_flag` int DEFAULT NULL COMMENT '1=Aktif, 0=Nonaktif',
  `branch_bank_name` varchar(255) DEFAULT NULL,
  `branch_bank_branch` varchar(255) DEFAULT NULL,
  `branch_bank_address` varchar(255) DEFAULT NULL,
  `branch_bank_account_number` varchar(255) DEFAULT NULL,
  `branch_bank_account_name` varchar(255) DEFAULT NULL,
  `branch_province_id` bigint DEFAULT NULL,
  `branch_city_id` bigint DEFAULT NULL,
  `branch_district_id` bigint DEFAULT NULL,
  `branch_village_id` bigint DEFAULT NULL,
  `branch_transaction_with_stock` varchar(255) DEFAULT NULL,
  `branch_transaction_with_journal` varchar(255) DEFAULT NULL,
  `branch_session` varchar(255) DEFAULT NULL,
  `branch_url` varchar(255) DEFAULT NULL COMMENT 'App URL Directory',
  `branch_location_id` int DEFAULT NULL COMMENT 'Location Default',
  PRIMARY KEY (`branch_id`) USING BTREE,
  KEY `USER` (`branch_user_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `branchs_specialists` */

DROP TABLE IF EXISTS `branchs_specialists`;

CREATE TABLE `branchs_specialists` (
  `specialist_id` bigint NOT NULL AUTO_INCREMENT,
  `specialist_name` varchar(255) DEFAULT NULL,
  `specialist_name_translate` varchar(500) DEFAULT NULL,
  `specialist_flag` int DEFAULT NULL,
  `specialist_date_created` datetime DEFAULT NULL,
  PRIMARY KEY (`specialist_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Table structure for table `branchs_specialists_accounts` */

DROP TABLE IF EXISTS `branchs_specialists_accounts`;

CREATE TABLE `branchs_specialists_accounts` (
  `item_id` bigint NOT NULL AUTO_INCREMENT,
  `item_branch_specialist_id` bigint DEFAULT NULL,
  `item_code` varchar(255) DEFAULT NULL,
  `item_name` varchar(255) DEFAULT NULL,
  `item_group` int DEFAULT NULL,
  `item_group_sub` int DEFAULT NULL,
  `item_group_sub_name` varchar(255) DEFAULT NULL,
  `item_parent_id` bigint DEFAULT NULL,
  `item_show` int DEFAULT NULL,
  `item_side` int DEFAULT NULL,
  `item_flag` int DEFAULT NULL,
  `item_date_created` datetime DEFAULT NULL,
  `item_session` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`item_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Table structure for table `branchs_specialists_accounts_maps` */

DROP TABLE IF EXISTS `branchs_specialists_accounts_maps`;

CREATE TABLE `branchs_specialists_accounts_maps` (
  `account_map_id` bigint NOT NULL AUTO_INCREMENT,
  `account_branch_specialist_id` bigint DEFAULT NULL,
  `account_map_for_transaction` bigint DEFAULT NULL COMMENT '1=Pembelian, 2=Penjualan, 3=Persediaan, 4=Stok Opname, 10=Other',
  `account_map_type` bigint DEFAULT NULL,
  `account_map_flag` int DEFAULT NULL,
  `account_map_note` varchar(255) DEFAULT NULL,
  `account_map_formula` varchar(10) DEFAULT NULL COMMENT 'D=Debit, C=Credit',
  `account_map_date_created` datetime DEFAULT NULL,
  `account_map_date_updated` datetime DEFAULT NULL,
  `account_map_session` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`account_map_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Table structure for table `categories` */

DROP TABLE IF EXISTS `categories`;

CREATE TABLE `categories` (
  `category_id` int NOT NULL AUTO_INCREMENT,
  `category_type` int DEFAULT '0' COMMENT '1=Product, 2=News, 3=MessageWhatsapp, 4=Contact Group',
  `category_branch_id` int DEFAULT '0',
  `category_parent_id` int DEFAULT '0',
  `category_code` varchar(255) DEFAULT NULL,
  `category_name` varchar(255) DEFAULT NULL,
  `category_url` varchar(255) DEFAULT NULL,
  `category_icon` varchar(255) DEFAULT NULL,
  `category_date_created` datetime DEFAULT NULL,
  `category_date_updated` datetime DEFAULT NULL,
  `category_user_id` int DEFAULT NULL,
  `category_flag` int DEFAULT '0',
  `category_count_data` int DEFAULT '0' COMMENT 'TR products, contacts',
  `category_color` varchar(255) DEFAULT NULL,
  `category_background` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`category_id`) USING BTREE,
  KEY `BRANCH` (`category_branch_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Table structure for table `cities` */

DROP TABLE IF EXISTS `cities`;

CREATE TABLE `cities` (
  `city_id` int NOT NULL,
  `city_province_id` int NOT NULL,
  `city_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `city_flag` int DEFAULT '1',
  PRIMARY KEY (`city_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8_unicode_ci;

/*Table structure for table `contacts` */

DROP TABLE IF EXISTS `contacts`;

CREATE TABLE `contacts` (
  `contact_id` bigint NOT NULL AUTO_INCREMENT,
  `contact_branch_id` int DEFAULT '0',
  `contact_type` varchar(10) DEFAULT NULL COMMENT '1=Supplier, 2=Customer, 3=Karyawan, 4=Pasien, 5-Asuransi',
  `contact_type_name` varchar(255) DEFAULT NULL,
  `contact_category_id` bigint DEFAULT NULL,
  `contact_code` varchar(255) DEFAULT NULL,
  `contact_name` varchar(255) DEFAULT NULL,
  `contact_address` varchar(255) DEFAULT NULL,
  `contact_phone_1` varchar(255) DEFAULT NULL,
  `contact_phone_2` varchar(255) DEFAULT NULL,
  `contact_email_1` varchar(255) DEFAULT NULL,
  `contact_email_2` varchar(255) DEFAULT NULL,
  `contact_company` varchar(255) DEFAULT NULL,
  `contact_image` tinytext,
  `contact_url` tinytext,
  `contact_gender` int DEFAULT NULL COMMENT '1=Laki, 2=Perempuan',
  `contact_birth_city_id` bigint DEFAULT NULL,
  `contact_birth_city_name` varchar(255) DEFAULT NULL COMMENT 'By Trigger',
  `contact_birth_date` date DEFAULT NULL,
  `contact_user_id` bigint DEFAULT NULL,
  `contact_account_receivable_account_id` bigint DEFAULT NULL,
  `contact_account_payable_account_id` bigint DEFAULT NULL,
  `contact_date_created` datetime DEFAULT NULL,
  `contact_date_updated` datetime DEFAULT NULL,
  `contact_flag` int DEFAULT NULL COMMENT '1=Aktif, 0=Delete',
  `contact_identity_type` varchar(255) DEFAULT '0' COMMENT '0, KTP, SIM, Pasport',
  `contact_identity_number` varchar(255) DEFAULT NULL,
  `contact_fax` varchar(255) DEFAULT NULL,
  `contact_npwp` varchar(255) DEFAULT NULL,
  `contact_handphone` varchar(255) DEFAULT NULL,
  `contact_note` text,
  `contact_visitor` varchar(255) DEFAULT NULL,
  `contact_parent_id` bigint DEFAULT NULL COMMENT 'Contact Asuransi',
  `contact_parent_name` varchar(255) DEFAULT NULL COMMENT 'By Trigger',
  `contact_city_id` bigint DEFAULT NULL,
  `contact_province_id` bigint DEFAULT NULL,
  `contact_session` varchar(255) DEFAULT NULL,
  `contact_ascii` int DEFAULT NULL,
  `contact_termin` varchar(25) DEFAULT '0' COMMENT 'Relasi reference type=8',
  `contact_payable_limit` double(18,2) DEFAULT '0.00',
  `contact_payable_running` double(18,2) DEFAULT '0.00' COMMENT 'Trigger Pembelian Berjalan',
  `contact_payable_paid` double(18,2) DEFAULT '0.00' COMMENT 'Trigger Hutang Beli Terbayar',
  `contact_payable_balance` double(18,2) DEFAULT '0.00' COMMENT 'Trigger Saldo Hutang',
  `contact_receivable_limit` double(18,2) DEFAULT '0.00',
  `contact_receivable_running` double(18,2) DEFAULT '0.00' COMMENT 'Trigger Penjualan Berjalan',
  `contact_receivable_paid` double(18,2) DEFAULT '0.00' COMMENT 'Trigger Penjualan Terbayar',
  `contact_receivable_balance` double(18,2) DEFAULT '0.00' COMMENT 'Trigger Saldo Piutang',
  `contact_use_type` int DEFAULT '0' COMMENT 'Terpakai di POS, 1=Used, 0=Available',
  `contact_group_session` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`contact_id`) USING BTREE,
  KEY `BRANCH` (`contact_branch_id`) USING BTREE,
  KEY `CATEGORY` (`contact_category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `countries` */

DROP TABLE IF EXISTS `countries`;

CREATE TABLE `countries` (
  `country_id` int DEFAULT NULL,
  `country_short` varchar(9) DEFAULT NULL,
  `country_name` varchar(450) DEFAULT NULL,
  `country_phone` int DEFAULT NULL,
  `country_default` int DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Table structure for table `devices` */

DROP TABLE IF EXISTS `devices`;

CREATE TABLE `devices` (
  `device_id` bigint NOT NULL AUTO_INCREMENT,
  `device_branch_id` bigint DEFAULT NULL,
  `device_media` enum('WhatsApp','Telegram','SMS','Email') DEFAULT NULL,
  `device_number` varchar(255) DEFAULT NULL,
  `device_flag` int DEFAULT '0',
  `device_date_created` datetime DEFAULT NULL,
  `device_date_updated` datetime DEFAULT NULL COMMENT 'Diisi Otomatis oleh SQL',
  `device_token` varchar(255) DEFAULT NULL,
  `device_label` varchar(255) DEFAULT NULL,
  `device_mail_host` varchar(255) DEFAULT NULL COMMENT 'noreply@yoursite.com',
  `device_mail_port` int DEFAULT NULL COMMENT '465, 587, 25',
  `device_mail_email` varchar(255) DEFAULT NULL COMMENT 'noreply@yoursite.com',
  `device_mail_password` varchar(255) DEFAULT NULL COMMENT 'noreply@yoursite.com',
  `device_mail_from_alias` varchar(255) DEFAULT NULL COMMENT 'noreply@yoursite.com',
  `device_mail_reply_alias` varchar(255) DEFAULT NULL COMMENT 'info@yoursite.com',
  `device_mail_label_alias` varchar(255) DEFAULT NULL COMMENT 'Notifikasi Your Site',
  PRIMARY KEY (`device_id`),
  KEY `BRANCH` (`device_branch_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `districts` */

DROP TABLE IF EXISTS `districts`;

CREATE TABLE `districts` (
  `district_id` int NOT NULL,
  `district_city_id` int NOT NULL,
  `district_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `district_flag` int DEFAULT '1',
  PRIMARY KEY (`district_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8_unicode_ci;

/*Table structure for table `files` */

DROP TABLE IF EXISTS `files`;

CREATE TABLE `files` (
  `file_id` bigint NOT NULL AUTO_INCREMENT,
  `file_type` int DEFAULT '1' COMMENT '1=Attachment, 2=Link',
  `file_from_table` enum('users','contacts','products','orders','trans','journals','news') DEFAULT NULL COMMENT 'Source Table',
  `file_from_id` bigint DEFAULT NULL COMMENT 'ID Source, Can Null',
  `file_name` varchar(255) DEFAULT NULL,
  `file_url` varchar(255) DEFAULT NULL COMMENT 'upload/files',
  `file_format` varchar(255) DEFAULT NULL COMMENT '.doc.pdf.jpg.xls',
  `file_session` varchar(255) DEFAULT NULL,
  `file_flag` int DEFAULT '1' COMMENT '1=Exists, 4=Deleted',
  `file_date_created` datetime DEFAULT NULL,
  `file_user_id` bigint DEFAULT NULL COMMENT 'User Id',
  `file_size` double(12,0) DEFAULT '0' COMMENT 'Kilobyte',
  PRIMARY KEY (`file_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `flows` */

DROP TABLE IF EXISTS `flows`;

CREATE TABLE `flows` (
  `flow_id` bigint NOT NULL AUTO_INCREMENT,
  `flow_type` int DEFAULT '0' COMMENT '1=Registration, 2=Order',
  `flow_name` varchar(255) DEFAULT NULL,
  `flow_phone` varchar(255) DEFAULT NULL,
  `flow_data` text,
  `flow_flag` int DEFAULT '0' COMMENT '1=Confirmed, 4=Delete',
  `flow_recipient_number` varchar(255) DEFAULT NULL,
  `flow_recipient_name` varchar(255) DEFAULT NULL,
  `flow_sender_number` varchar(255) DEFAULT NULL,
  `flow_date_created` datetime DEFAULT NULL,
  `flow_session` varchar(255) DEFAULT NULL,
  `flow_session_group` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`flow_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `journals` */

DROP TABLE IF EXISTS `journals`;

CREATE TABLE `journals` (
  `journal_id` bigint NOT NULL AUTO_INCREMENT,
  `journal_branch_id` int DEFAULT '0',
  `journal_type` int DEFAULT NULL COMMENT '1=BayarHutang,2=BayarPiutang,3=KasMasuk,4=KasKeluar,5=MutasiKas,6=UangMukaBeli,7=UangMukaJual',
  `journal_number` varchar(255) DEFAULT NULL,
  `journal_date` datetime DEFAULT NULL,
  `journal_account_id` bigint DEFAULT NULL,
  `journal_total` double(18,2) DEFAULT '0.00',
  `journal_contact_id` bigint DEFAULT NULL,
  `journal_paid_type` int DEFAULT NULL COMMENT '1=Cash,2=Bank,3=Credit,4=Debit,5=Digital,6=Cek',
  `journal_note` varchar(255) DEFAULT NULL,
  `journal_date_created` datetime DEFAULT NULL,
  `journal_date_updated` datetime DEFAULT NULL,
  `journal_user_id` bigint DEFAULT NULL,
  `journal_flag` int DEFAULT '0',
  `journal_session` varchar(255) DEFAULT NULL,
  `journal_approval_flag` int DEFAULT '0' COMMENT 'Run From Trigger 1=Approve, 2=Pending, 4=Denied',
  `journal_label` varchar(255) DEFAULT NULL,
  `journal_id_source` bigint DEFAULT NULL COMMENT 'Hanya Asset yg menggunakan',
  `journal_asset_id` bigint DEFAULT NULL COMMENT 'Relasi products = product_type=3',
  `journal_total_2` double(18,2) DEFAULT '0.00',
  `journal_cashback` int DEFAULT '0' COMMENT '1=Cashback, 0=Not Cashback',
  PRIMARY KEY (`journal_id`) USING BTREE,
  KEY `USER` (`journal_user_id`) USING BTREE,
  KEY `CONTACT` (`journal_contact_id`) USING BTREE,
  KEY `BRANCH` (`journal_branch_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Table structure for table `journals_items` */

DROP TABLE IF EXISTS `journals_items`;

CREATE TABLE `journals_items` (
  `journal_item_id` bigint NOT NULL AUTO_INCREMENT,
  `journal_item_journal_id` bigint DEFAULT NULL,
  `journal_item_group_session` varchar(255) DEFAULT NULL,
  `journal_item_branch_id` int DEFAULT '0',
  `journal_item_account_id` bigint DEFAULT NULL,
  `journal_item_trans_id` bigint DEFAULT NULL,
  `journal_item_order_id` bigint DEFAULT NULL,
  `journal_item_date` datetime DEFAULT NULL,
  `journal_item_type` int DEFAULT NULL,
  `journal_item_type_name` varchar(255) DEFAULT NULL,
  `journal_item_debit` double(18,2) DEFAULT '0.00',
  `journal_item_credit` double(18,2) DEFAULT '0.00',
  `journal_item_note` text,
  `journal_item_user_id` bigint DEFAULT NULL,
  `journal_item_date_created` datetime DEFAULT NULL,
  `journal_item_date_updated` datetime DEFAULT NULL,
  `journal_item_flag` int DEFAULT '0',
  `journal_item_position` int DEFAULT '2' COMMENT '1=Header, 2=Detail',
  `journal_item_journal_session` varchar(255) DEFAULT NULL,
  `journal_item_session` varchar(255) DEFAULT NULL,
  `journal_item_ref` varchar(255) DEFAULT NULL COMMENT 'FIFO Down Payment',
  PRIMARY KEY (`journal_item_id`) USING BTREE,
  KEY `JOURNAL` (`journal_item_journal_id`) USING BTREE,
  KEY `BRANCH` (`journal_item_branch_id`) USING BTREE,
  KEY `ACCOUNT` (`journal_item_account_id`) USING BTREE,
  KEY `TRANS` (`journal_item_trans_id`) USING BTREE,
  KEY `USER` (`journal_item_user_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Table structure for table `links` */

DROP TABLE IF EXISTS `links`;

CREATE TABLE `links` (
  `link_id` bigint NOT NULL AUTO_INCREMENT,
  `link_session` varchar(255) DEFAULT NULL,
  `link_url` varchar(255) DEFAULT NULL COMMENT 'Short Link',
  `link_name` varchar(255) DEFAULT NULL COMMENT 'Link To Forward',
  `link_user_session` varchar(255) DEFAULT NULL,
  `link_branch_session` varchar(255) DEFAULT NULL,
  `link_date_created` datetime DEFAULT NULL,
  `link_date_updated` datetime DEFAULT NULL,
  `link_flag` int DEFAULT NULL COMMENT '1=Publish, 0=Unpublish',
  `link_visit` bigint DEFAULT '0' COMMENT 'Trigger',
  `link_label` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`link_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Table structure for table `links_hits` */

DROP TABLE IF EXISTS `links_hits`;

CREATE TABLE `links_hits` (
  `hit_id` bigint NOT NULL AUTO_INCREMENT,
  `hit_remote_addr` varchar(255) DEFAULT NULL,
  `hit_user_agent` varchar(255) DEFAULT NULL,
  `hit_http_referer` varchar(255) DEFAULT NULL,
  `hit_date_created` datetime DEFAULT NULL,
  `hit_session` varchar(255) DEFAULT NULL,
  `hit_link_session` varchar(255) DEFAULT NULL COMMENT 'Link Session',
  PRIMARY KEY (`hit_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `locations` */

DROP TABLE IF EXISTS `locations`;

CREATE TABLE `locations` (
  `location_id` bigint NOT NULL AUTO_INCREMENT,
  `location_branch_id` bigint DEFAULT NULL,
  `location_user_id` bigint DEFAULT NULL COMMENT 'User Penanggung Jawab',
  `location_code` varchar(255) DEFAULT NULL,
  `location_name` varchar(255) DEFAULT NULL,
  `location_address` varchar(255) DEFAULT NULL,
  `location_note` varchar(255) DEFAULT NULL,
  `location_date_created` datetime DEFAULT NULL,
  `location_date_updated` datetime DEFAULT NULL,
  `location_flag` int DEFAULT NULL COMMENT '1=Aktif, 0=Delete',
  `location_session` varchar(255) DEFAULT NULL COMMENT 'Sesi Untuk Otomatis Lokasi ke Branch',
  PRIMARY KEY (`location_id`) USING BTREE,
  KEY `BRANCH` (`location_branch_id`) USING BTREE,
  KEY `USER` (`location_user_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `menus` */

DROP TABLE IF EXISTS `menus`;

CREATE TABLE `menus` (
  `menu_id` int NOT NULL AUTO_INCREMENT,
  `menu_parent_id` int DEFAULT NULL,
  `menu_name` varchar(255) DEFAULT NULL,
  `menu_icon` varchar(255) DEFAULT NULL,
  `menu_link` varchar(255) DEFAULT NULL,
  `menu_sorting` int DEFAULT NULL,
  `menu_date_created` datetime DEFAULT NULL,
  `menu_flag` int DEFAULT NULL,
  `menu_user_id` bigint DEFAULT NULL,
  `menu_session` varchar(255) DEFAULT NULL COMMENT 'Untuk Sub Navigation',
  PRIMARY KEY (`menu_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `messages` */

DROP TABLE IF EXISTS `messages`;

CREATE TABLE `messages` (
  `message_id` bigint NOT NULL AUTO_INCREMENT,
  `message_branch_id` bigint DEFAULT NULL,
  `message_type` int DEFAULT '1' COMMENT 'Not USED 1=text, 2=image, 3=video, 4=document',
  `message_news_id` bigint DEFAULT NULL,
  `message_platform` int DEFAULT '1' COMMENT '1=Whatsapp, 2=Telegram, 3=SMS, 4=Email',
  `message_session` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `message_group_session` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Sent always by this value enqueque',
  `message_date_created` datetime DEFAULT NULL,
  `message_date_sent` datetime DEFAULT NULL,
  `message_flag` int DEFAULT NULL COMMENT '0=Antrian, 1=Terkirim, 2=Proses, 4=Gagal Dikirim',
  `message_text` blob,
  `message_url` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `message_subject` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Wajib diisi jika message_platform=4',
  `message_contact_id` bigint DEFAULT NULL,
  `message_contact_name` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `message_contact_number` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `message_contact_email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Email, Wajib jika message_platform=4',
  `message_device_id` bigint DEFAULT NULL,
  `message_date_schedule` date DEFAULT NULL COMMENT 'Cronjob, Diisi jika dikirim tanggal tersebut',
  PRIMARY KEY (`message_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `mutations` */

DROP TABLE IF EXISTS `mutations`;

CREATE TABLE `mutations` (
  `mutation_id` bigint NOT NULL AUTO_INCREMENT,
  `mutation_session` varchar(255) DEFAULT NULL,
  `mutation_bank_session` varchar(255) DEFAULT NULL,
  `mutation_user_session` varchar(255) DEFAULT NULL,
  `mutation_bank_id` int DEFAULT NULL,
  `mutation_date` datetime DEFAULT NULL,
  `mutation_text` text,
  `mutation_debit` varchar(255) DEFAULT NULL,
  `mutation_credit` varchar(255) DEFAULT NULL,
  `mutation_total` varchar(255) DEFAULT NULL,
  `mutation_type` varchar(255) DEFAULT NULL,
  `mutation_api_id` int DEFAULT NULL,
  `mutation_api_date` datetime DEFAULT NULL,
  `mutation_api_bank_id` int DEFAULT NULL,
  `mutation_api_bank_code` varchar(255) DEFAULT NULL,
  `mutation_api_bank_name` varchar(255) DEFAULT NULL,
  `mutation_api_bank_account_number` varchar(255) DEFAULT NULL,
  `mutation_notif_phone` varchar(255) DEFAULT NULL,
  `mutation_notif_email` varchar(255) DEFAULT NULL,
  `mutation_time` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`mutation_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Table structure for table `news` */

DROP TABLE IF EXISTS `news`;

CREATE TABLE `news` (
  `news_id` bigint NOT NULL AUTO_INCREMENT,
  `news_type` int DEFAULT '1' COMMENT '1=News, 2=Promo',
  `news_branch_id` bigint DEFAULT '0',
  `news_category_id` bigint DEFAULT NULL,
  `news_title` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `news_url` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `news_image` tinytext CHARACTER SET latin1 COLLATE latin1_swedish_ci,
  `news_short` text COLLATE utf8mb4_unicode_ci,
  `news_content` text COLLATE utf8mb4_unicode_ci,
  `news_tags` text CHARACTER SET latin1 COLLATE latin1_swedish_ci,
  `news_keywords` text CHARACTER SET latin1 COLLATE latin1_swedish_ci,
  `news_visitor` int DEFAULT '0',
  `news_user_id` bigint DEFAULT NULL,
  `news_date_created` datetime DEFAULT NULL,
  `news_date_updated` datetime DEFAULT NULL,
  `news_flag` int DEFAULT NULL COMMENT '1=Aktif, 0=Delete',
  `news_position` int DEFAULT '1',
  PRIMARY KEY (`news_id`) USING BTREE,
  KEY `BRANCH` (`news_branch_id`) USING BTREE,
  KEY `USER` (`news_user_id`) USING BTREE,
  KEY `CATEGORIES` (`news_category_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `orders` */

DROP TABLE IF EXISTS `orders`;

CREATE TABLE `orders` (
  `order_id` bigint NOT NULL AUTO_INCREMENT,
  `order_branch_id` int DEFAULT '0',
  `order_type` int DEFAULT NULL COMMENT '1=PurchaseOrder, 2=SalesOrder, 3=PenawaranPembelian, 4=PenawaranPenjualan, 5=CheckUp Medicine, 6=CheckUp Laboratory',
  `order_number` varchar(255) DEFAULT NULL,
  `order_date` datetime DEFAULT NULL,
  `order_date_due` datetime DEFAULT NULL COMMENT 'Tanggal Jatuh Tempo',
  `order_contact_id` bigint DEFAULT NULL,
  `order_contact_id_2` bigint DEFAULT NULL COMMENT 'Waitress, Employee Contact ID',
  `order_ppn` int DEFAULT NULL COMMENT '1=Ppn, 0=NonPpn',
  `order_total_dpp` double(18,2) DEFAULT '0.00',
  `order_discount` double(18,2) DEFAULT '0.00',
  `order_voucher` double(18,2) DEFAULT '0.00',
  `order_with_dp` double(18,2) DEFAULT '0.00' COMMENT 'Not Used',
  `order_total` double(18,2) DEFAULT '0.00' COMMENT 'Total BY TRIGGER',
  `order_with_dp_account` bigint DEFAULT NULL,
  `order_note` text,
  `order_user_id` bigint DEFAULT NULL,
  `order_ref_id` int DEFAULT NULL,
  `order_date_created` datetime DEFAULT NULL,
  `order_date_updated` datetime DEFAULT NULL,
  `order_flag` int DEFAULT NULL COMMENT '0=Order, 1=Is Payment',
  `order_trans_id` bigint DEFAULT NULL COMMENT 'Many Order => One Trans',
  `order_ref_number` varchar(255) DEFAULT NULL COMMENT 'Nomor Ref Customer/Supplier',
  `order_session` varchar(255) DEFAULT NULL,
  `order_approval_flag` int DEFAULT '0' COMMENT 'Run From Trigger 1=Approve, 2=Pending, 4=Denied',
  `order_label` varchar(255) DEFAULT NULL,
  `order_sales_id` bigint DEFAULT NULL,
  `order_sales_name` varchar(255) DEFAULT NULL,
  `order_contact_name` varchar(255) DEFAULT NULL,
  `order_contact_phone` varchar(255) DEFAULT NULL,
  `order_approval_count` int DEFAULT '0' COMMENT 'Trigger approval',
  `order_files_count` int DEFAULT '0' COMMENT 'Trigger files',
  PRIMARY KEY (`order_id`) USING BTREE,
  KEY `USER` (`order_user_id`) USING BTREE,
  KEY `CONTACT` (`order_contact_id`) USING BTREE,
  KEY `BRANCH` (`order_branch_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `orders_items` */

DROP TABLE IF EXISTS `orders_items`;

CREATE TABLE `orders_items` (
  `order_item_id` bigint NOT NULL AUTO_INCREMENT,
  `order_item_branch_id` int DEFAULT '0',
  `order_item_type` int DEFAULT NULL COMMENT '1=PurchaseOrder, 2=SalesOrder, 3=PenawaranPembelian, 4=PenawaranPenjualan, 5=CheckUp Medicine, 6=CheckUp Laboratory',
  `order_item_type_name` varchar(255) DEFAULT NULL,
  `order_item_order_id` bigint DEFAULT NULL,
  `order_item_product_id` bigint DEFAULT NULL,
  `order_item_location_id` bigint DEFAULT NULL,
  `order_item_qty` double(18,2) DEFAULT '0.00',
  `order_item_unit` varchar(255) DEFAULT NULL,
  `order_item_price` double(18,2) DEFAULT '0.00',
  `order_item_discount` double(18,2) DEFAULT '0.00',
  `order_item_total` double(18,2) DEFAULT '0.00',
  `order_item_note` text,
  `order_item_user_id` bigint DEFAULT NULL,
  `order_item_date` datetime DEFAULT NULL,
  `order_item_date_created` datetime DEFAULT NULL,
  `order_item_date_updated` datetime DEFAULT NULL,
  `order_item_flag` int DEFAULT NULL,
  `order_item_recipe_order_item_id` bigint DEFAULT '0',
  `order_item_product_price_id` bigint DEFAULT NULL,
  `order_item_qty_weight` double(18,2) DEFAULT NULL,
  `order_item_ppn` int DEFAULT '0',
  `order_item_order_session` varchar(255) DEFAULT NULL,
  `order_item_session` varchar(255) DEFAULT NULL,
  `order_item_ref_id` int DEFAULT NULL COMMENT 'Room, Meja Ref ID',
  `order_item_contact_id` int DEFAULT NULL COMMENT 'Customer, Supplier Contact ID',
  `order_item_contact_id_2` int DEFAULT NULL,
  PRIMARY KEY (`order_item_id`) USING BTREE,
  KEY `USER` (`order_item_user_id`) USING BTREE,
  KEY `ORDER` (`order_item_order_id`) USING BTREE,
  KEY `PRODUCT` (`order_item_product_id`) USING BTREE,
  KEY `LOCATION` (`order_item_location_id`) USING BTREE,
  KEY `BRANCH` (`order_item_branch_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `print_spoilers` */

DROP TABLE IF EXISTS `print_spoilers`;

CREATE TABLE `print_spoilers` (
  `spoiler_id` bigint NOT NULL AUTO_INCREMENT,
  `spoiler_content` text,
  `spoiler_ip` varchar(255) DEFAULT NULL,
  `spoiler_source_table` varchar(255) DEFAULT NULL,
  `spoiler_source_id` bigint DEFAULT NULL,
  `spoiler_date` datetime DEFAULT NULL,
  `spoiler_flag` int DEFAULT NULL,
  PRIMARY KEY (`spoiler_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Table structure for table `printers` */

DROP TABLE IF EXISTS `printers`;

CREATE TABLE `printers` (
  `printer_id` bigint NOT NULL AUTO_INCREMENT,
  `printer_parent_id` bigint DEFAULT NULL COMMENT 'Relasi ke printer',
  `printer_type` int DEFAULT NULL COMMENT '1=Deksjet, 2=DotMatrik, 3=Labelworks, 4=Thermal',
  `printer_ip` varchar(255) DEFAULT NULL,
  `printer_name` varchar(255) DEFAULT NULL,
  `printer_flag` int DEFAULT NULL,
  `printer_session` varchar(255) DEFAULT NULL,
  `printer_date_created` datetime DEFAULT NULL,
  `printer_paper_design` varchar(255) DEFAULT NULL,
  `printer_paper_width` varchar(10) DEFAULT '0' COMMENT 'milimeter',
  `printer_paper_height` varchar(10) DEFAULT '0' COMMENT 'milimeter',
  PRIMARY KEY (`printer_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Table structure for table `products` */

DROP TABLE IF EXISTS `products`;

CREATE TABLE `products` (
  `product_id` bigint NOT NULL AUTO_INCREMENT,
  `product_branch_id` bigint NOT NULL,
  `product_category_id` bigint DEFAULT NULL,
  `product_ref_id` bigint DEFAULT NULL COMMENT '1=Jual, 2=Sewa',
  `product_type` int DEFAULT NULL COMMENT '1=Barang/Obat,2=Jasa, 3=Inventaris, 4=Tindakan, 5=Laboratorium, 6=Lain',
  `product_barcode` varchar(255) DEFAULT NULL,
  `product_code` varchar(255) DEFAULT NULL,
  `product_name` varchar(255) DEFAULT NULL,
  `product_unit` varchar(255) DEFAULT NULL,
  `product_note` text,
  `product_price_buy` double(18,2) DEFAULT '0.00',
  `product_price_sell` double(18,2) DEFAULT '0.00',
  `product_min_stock_limit` varchar(255) DEFAULT '0',
  `product_max_stock_limit` varchar(255) DEFAULT '0',
  `product_fee_1` varchar(255) DEFAULT '0' COMMENT 'Pelaksana',
  `product_fee_2` varchar(255) DEFAULT '0' COMMENT 'Asisten',
  `product_manufacture` varchar(255) DEFAULT NULL,
  `product_image` tinytext,
  `product_url` text,
  `product_user_id` bigint DEFAULT NULL,
  `product_date_created` datetime DEFAULT NULL,
  `product_date_updated` datetime DEFAULT NULL,
  `product_flag` int DEFAULT NULL COMMENT '1=Aktif, 0=Delete',
  `product_stock` double(18,2) DEFAULT '0.00',
  `product_with_stock` int DEFAULT '0',
  `product_buy_account_id` bigint DEFAULT NULL,
  `product_sell_account_id` bigint DEFAULT NULL,
  `product_inventory_account_id` bigint DEFAULT NULL,
  `product_visitor` varchar(255) DEFAULT NULL,
  `product_city_id` bigint DEFAULT NULL,
  `product_province_id` bigint DEFAULT NULL,
  `product_square_size` double(18,0) DEFAULT NULL,
  `product_building_size` double(18,0) DEFAULT NULL,
  `product_bedroom` varchar(255) DEFAULT NULL,
  `product_bathroom` varchar(255) DEFAULT NULL,
  `product_garage` varchar(255) DEFAULT NULL,
  `product_contact_id` bigint DEFAULT NULL,
  `product_price_promo` double(18,0) DEFAULT '0',
  `product_address` varchar(255) DEFAULT NULL,
  `product_dimension_size` varchar(255) DEFAULT NULL,
  `product_latitude` varchar(255) DEFAULT NULL,
  `product_longitude` varchar(255) DEFAULT NULL,
  `product_reminder` varchar(255) DEFAULT NULL,
  `product_reminder_date` datetime DEFAULT NULL,
  `product_asset_name` varchar(255) DEFAULT NULL COMMENT 'Nama Asset',
  `product_asset_code` varchar(255) DEFAULT NULL COMMENT 'Nomor Asset',
  `product_asset_note` text COMMENT 'Deskripsi Asset',
  `product_asset_acquisition_date` date DEFAULT NULL COMMENT 'Tgl Akusisi',
  `product_asset_acquisition_value` double(18,2) DEFAULT NULL COMMENT 'Biaya Akusisi',
  `product_asset_dep_flag` int DEFAULT NULL COMMENT '0=NonDepresiasi, 1=Depresiasi',
  `product_asset_dep_method` int DEFAULT NULL COMMENT '1=Straight Line, 2=Reducing Balance',
  `product_asset_dep_period` int DEFAULT NULL COMMENT 'Masa Manfaat / Tahun',
  `product_asset_dep_percentage` double(18,2) DEFAULT NULL COMMENT 'Nilai / Tahun Persen',
  `product_asset_fixed_account_id` bigint DEFAULT NULL COMMENT 'Account Asset Tetap',
  `product_asset_cost_account_id` bigint DEFAULT NULL COMMENT 'Account Biaya Akusisi',
  `product_asset_depreciation_account_id` bigint DEFAULT NULL COMMENT 'Account Penyusutan',
  `product_asset_accumulated_depreciation_account_id` bigint DEFAULT NULL COMMENT 'Account Akumulasi Penyusutan',
  `product_asset_accumulated_depreciation_value` double(18,2) DEFAULT NULL COMMENT 'Akumulasi Penyusutan',
  `product_asset_accumulated_depreciation_date` date DEFAULT NULL COMMENT 'Tgl Mulai Awal',
  PRIMARY KEY (`product_id`) USING BTREE,
  KEY `CATEGORIES` (`product_category_id`) USING BTREE,
  KEY `BRANCH` (`product_branch_id`) USING BTREE,
  KEY `REFERENCE` (`product_ref_id`) USING BTREE,
  KEY `UNIT` (`product_unit`) USING BTREE,
  KEY `USER` (`product_user_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `products_items` */

DROP TABLE IF EXISTS `products_items`;

CREATE TABLE `products_items` (
  `product_item_id` bigint NOT NULL AUTO_INCREMENT,
  `product_item_product_id` bigint DEFAULT NULL,
  `product_item_type` int DEFAULT NULL COMMENT '1=Other Image, 2=Other Price',
  `product_item_name` varchar(255) DEFAULT NULL,
  `product_item_image` tinytext,
  `product_item_price` double(18,2) DEFAULT '0.00',
  `product_item_date_created` datetime DEFAULT NULL,
  `product_item_date_updated` datetime DEFAULT NULL,
  `product_item_flag` int DEFAULT '1',
  PRIMARY KEY (`product_item_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Table structure for table `products_prices` */

DROP TABLE IF EXISTS `products_prices`;

CREATE TABLE `products_prices` (
  `product_price_id` bigint NOT NULL AUTO_INCREMENT,
  `product_price_product_id` bigint NOT NULL,
  `product_price_name` varchar(255) DEFAULT NULL,
  `product_price_price` decimal(10,2) DEFAULT NULL,
  `product_price_date_created` datetime DEFAULT NULL,
  `product_price_date_updated` datetime DEFAULT NULL,
  `product_price_flag` int DEFAULT '0' COMMENT '1=Publish, 0=Deleted',
  `product_price_user_id` bigint DEFAULT NULL,
  PRIMARY KEY (`product_price_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Table structure for table `products_recipes` */

DROP TABLE IF EXISTS `products_recipes`;

CREATE TABLE `products_recipes` (
  `recipe_id` bigint NOT NULL AUTO_INCREMENT,
  `recipe_product_id` bigint DEFAULT NULL COMMENT '>0=Product ID, 0=Is Raw Material',
  `recipe_product_id_child` bigint DEFAULT '0',
  `recipe_unit` varchar(255) DEFAULT NULL,
  `recipe_qty` varchar(255) DEFAULT NULL,
  `recipe_note` varchar(255) DEFAULT NULL,
  `recipe_date_created` datetime DEFAULT NULL,
  `recipe_date_updated` datetime DEFAULT NULL,
  `recipe_flag` int DEFAULT '0',
  `recipe_user_id` bigint DEFAULT NULL,
  PRIMARY KEY (`recipe_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Table structure for table `provinces` */

DROP TABLE IF EXISTS `provinces`;

CREATE TABLE `provinces` (
  `province_id` int NOT NULL,
  `province_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `province_latitude` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `province_longitude` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `province_flag` int DEFAULT NULL,
  PRIMARY KEY (`province_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8_unicode_ci;

/*Table structure for table `recipients` */

DROP TABLE IF EXISTS `recipients`;

CREATE TABLE `recipients` (
  `recipient_id` bigint NOT NULL AUTO_INCREMENT,
  `recipient_branch_id` bigint DEFAULT NULL,
  `recipient_group_id` bigint DEFAULT NULL,
  `recipient_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `recipient_phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `recipient_email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `recipient_birth` date DEFAULT NULL,
  `recipient_session` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `recipient_date_created` datetime DEFAULT NULL,
  `recipient_flag` int DEFAULT '1',
  PRIMARY KEY (`recipient_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `recipients_groups` */

DROP TABLE IF EXISTS `recipients_groups`;

CREATE TABLE `recipients_groups` (
  `group_id` int NOT NULL AUTO_INCREMENT,
  `group_branch_id` int DEFAULT NULL,
  `group_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `group_date_created` datetime DEFAULT NULL,
  `group_flag` int DEFAULT NULL,
  `group_count` bigint DEFAULT '0',
  PRIMARY KEY (`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `references` */

DROP TABLE IF EXISTS `references`;

CREATE TABLE `references` (
  `ref_id` bigint NOT NULL AUTO_INCREMENT,
  `ref_branch_id` int DEFAULT NULL,
  `ref_parent_id` int DEFAULT NULL,
  `ref_type` int DEFAULT NULL COMMENT '1=Diagnosa,2=JenisPraktek,3=GolonganBarang,4=JenisPenyakit, 5=JenisLab, 6=JenisInventaris, 7=Table, 8=PayTemin',
  `ref_code` varchar(255) DEFAULT NULL,
  `ref_name` varchar(255) DEFAULT NULL,
  `ref_note` varchar(255) DEFAULT NULL,
  `ref_user_id` bigint DEFAULT NULL,
  `ref_date_created` datetime DEFAULT NULL,
  `ref_date_updated` datetime DEFAULT NULL,
  `ref_flag` int DEFAULT NULL COMMENT '1=Aktif, 0=Delete',
  `ref_color` varchar(255) DEFAULT NULL,
  `ref_background` varchar(255) DEFAULT NULL,
  `ref_icon` varchar(255) DEFAULT NULL,
  `ref_use_type` int DEFAULT NULL COMMENT '0=Available, 1=Used, 2=Booking, 3=, 4=Maintenance',
  PRIMARY KEY (`ref_id`) USING BTREE,
  KEY `USER` (`ref_user_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `surveys` */

DROP TABLE IF EXISTS `surveys`;

CREATE TABLE `surveys` (
  `survey_id` int NOT NULL AUTO_INCREMENT,
  `survey_session` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `survey_date_created` datetime DEFAULT NULL,
  `survey_flag` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `survey_note` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `survey_rating` int DEFAULT NULL,
  `survey_contact_id` bigint DEFAULT NULL,
  `survey_contact_name` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `survey_contact_number` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `survey_date_sent` datetime DEFAULT NULL,
  `survey_text` text COLLATE utf8mb4_unicode_ci,
  `survey_date_updated` datetime DEFAULT NULL,
  `survey_link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`survey_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `taxs` */

DROP TABLE IF EXISTS `taxs`;

CREATE TABLE `taxs` (
  `tax_id` int NOT NULL AUTO_INCREMENT,
  `tax_name` varchar(255) DEFAULT NULL,
  `tax_percent` double(18,2) DEFAULT '0.00',
  `tax_decimal_0` float DEFAULT '0' COMMENT 'Trigger tax_percent / 100',
  `tax_decimal_1` float DEFAULT '0' COMMENT 'Trigger',
  `tax_flag` int DEFAULT '0' COMMENT '1=Aktif, 0=Nonaktif, 2=Tidak (Default)',
  `tax_date_created` datetime DEFAULT NULL COMMENT 'Trigger',
  `tax_date_updated` datetime DEFAULT NULL COMMENT 'Trigger',
  `tax_session` varchar(255) DEFAULT NULL COMMENT 'Trigger',
  PRIMARY KEY (`tax_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `tests` */

DROP TABLE IF EXISTS `tests`;

CREATE TABLE `tests` (
  `test_id` int NOT NULL AUTO_INCREMENT,
  `test_name` varchar(255) DEFAULT NULL,
  `test_date` datetime DEFAULT NULL,
  `test_text` text,
  `test_value` double(18,2) DEFAULT NULL,
  `test_flag` int DEFAULT '0',
  PRIMARY KEY (`test_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `trans` */

DROP TABLE IF EXISTS `trans`;

CREATE TABLE `trans` (
  `trans_id` bigint NOT NULL AUTO_INCREMENT,
  `trans_branch_id` int DEFAULT '0',
  `trans_type` int DEFAULT NULL COMMENT '1=Pembelian,2=Penjualan,3=ReturBeli,4=ReturJual,5=MutasiGudang,6=StokOpnamePlus,7=StokOpnameMinus,8=Pemeriksaan',
  `trans_number` varchar(255) DEFAULT NULL,
  `trans_date` datetime DEFAULT NULL,
  `trans_date_due` datetime DEFAULT NULL,
  `trans_ppn` int DEFAULT NULL COMMENT '1=Ppn, 0=NonPpn',
  `trans_note` text,
  `trans_contact_id` bigint DEFAULT NULL,
  `trans_user_id` bigint DEFAULT NULL,
  `trans_order_id` bigint DEFAULT NULL,
  `trans_location_id` bigint DEFAULT NULL,
  `trans_location_to_id` bigint DEFAULT NULL,
  `trans_date_created` datetime DEFAULT NULL,
  `trans_date_updated` datetime DEFAULT NULL,
  `trans_flag` int DEFAULT '0' COMMENT '1=Aktif, 0=Delete',
  `trans_total_dpp` double(18,2) DEFAULT '0.00',
  `trans_total_ppn` double(10,2) DEFAULT '0.00' COMMENT 'Trigger Insert',
  `trans_down_payment` double(18,2) DEFAULT '0.00',
  `trans_discount` double(18,2) DEFAULT '0.00',
  `trans_voucher` double(18,2) DEFAULT '0.00',
  `trans_return` double(18,2) DEFAULT '0.00' COMMENT 'Return Insert By SP/TR',
  `trans_total` double(18,2) DEFAULT '0.00' COMMENT 'Total Faktur',
  `trans_total_paid` double(18,2) DEFAULT '0.00' COMMENT 'Total Terbayar BY TRIGGER',
  `trans_change` double(18,2) DEFAULT '0.00',
  `trans_received` double(18,2) DEFAULT '0.00',
  `trans_fee` double(18,2) DEFAULT '0.00',
  `trans_paid` int DEFAULT '0' COMMENT '1=Paid, 0=Unpaid BY TRIGGER',
  `trans_paid_type` int DEFAULT NULL COMMENT '1=Cash,2=Bank,3=Credit,4=Debit,5=Digital',
  `trans_bank_name` varchar(255) DEFAULT NULL,
  `trans_bank_number` varchar(255) DEFAULT NULL,
  `trans_bank_account_name` varchar(255) DEFAULT NULL,
  `trans_bank_ref` varchar(255) DEFAULT NULL,
  `trans_card_number` varchar(255) DEFAULT NULL,
  `trans_card_bank_name` varchar(255) DEFAULT NULL,
  `trans_card_bank_number` varchar(255) DEFAULT NULL,
  `trans_card_account_name` varchar(255) DEFAULT NULL,
  `trans_card_expired` varchar(255) DEFAULT NULL,
  `trans_card_type` int DEFAULT '0' COMMENT '1=Visa, 2=MasterCard, 3=AmericanExpress',
  `trans_digital_provider` varchar(255) DEFAULT NULL,
  `trans_ref_id` bigint DEFAULT NULL,
  `trans_ref_number` varchar(255) DEFAULT NULL,
  `trans_contact_name` varchar(255) DEFAULT NULL,
  `trans_contact_address` varchar(255) DEFAULT NULL,
  `trans_contact_phone` varchar(255) DEFAULT NULL,
  `trans_contact_email` varchar(255) DEFAULT NULL,
  `trans_session` varchar(255) DEFAULT NULL,
  `trans_approval_flag` int DEFAULT '0' COMMENT 'Run From Trigger 1=Approve, 2=Pending, 4=Denied',
  `trans_vehicle_brand` varchar(255) DEFAULT NULL,
  `trans_vehicle_brand_type_name` varchar(255) DEFAULT NULL,
  `trans_vehicle_plate_number` varchar(255) DEFAULT NULL,
  `trans_vehicle_distance` int DEFAULT NULL,
  `trans_vehicle_person` bigint DEFAULT NULL,
  `trans_id_source` bigint DEFAULT NULL COMMENT 'Only For Return Transaction',
  `trans_label` varchar(255) DEFAULT NULL,
  `trans_sales_id` bigint DEFAULT NULL,
  `trans_sales_name` varchar(255) DEFAULT NULL,
  `trans_voucher_id` bigint DEFAULT NULL,
  `trans_approval_count` int DEFAULT '0' COMMENT 'Trigger approval',
  `trans_files_count` int DEFAULT '0' COMMENT 'Trigger files',
  `trans_branch_id_2` bigint DEFAULT NULL,
  PRIMARY KEY (`trans_id`) USING BTREE,
  KEY `USER` (`trans_user_id`) USING BTREE,
  KEY `CONTACT` (`trans_contact_id`) USING BTREE,
  KEY `BRANCH` (`trans_branch_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Table structure for table `trans_items` */

DROP TABLE IF EXISTS `trans_items`;

CREATE TABLE `trans_items` (
  `trans_item_id` bigint NOT NULL AUTO_INCREMENT,
  `trans_item_branch_id` int DEFAULT '0',
  `trans_item_type` int DEFAULT NULL COMMENT '1=Pembelian,2=Penjualan,3=ReturBeli,4=ReturJual,5=MutasiGudang,6=StokOpnamePlus,7=StokOpnameMinus,8=Pemeriksaan',
  `trans_item_type_name` varchar(255) DEFAULT NULL,
  `trans_item_trans_id` bigint DEFAULT NULL,
  `trans_item_order_id` bigint DEFAULT NULL,
  `trans_item_order_item_id` bigint DEFAULT NULL,
  `trans_item_product_id` bigint DEFAULT NULL,
  `trans_item_location_id` bigint DEFAULT NULL,
  `trans_item_product_type` int DEFAULT NULL,
  `trans_item_date` datetime DEFAULT NULL,
  `trans_item_unit` varchar(255) DEFAULT NULL,
  `trans_item_in_qty` double(18,2) DEFAULT '0.00',
  `trans_item_in_price` double(18,2) DEFAULT '0.00',
  `trans_item_out_qty` double(18,2) DEFAULT '0.00',
  `trans_item_out_price` double(18,2) DEFAULT '0.00',
  `trans_item_sell_price` double(18,2) DEFAULT '0.00',
  `trans_item_discount` double(18,2) DEFAULT '0.00',
  `trans_item_ppn` int DEFAULT '0' COMMENT '0=Non, 1=Ppn',
  `trans_item_ppn_value` double(18,2) DEFAULT '0.00',
  `trans_item_total` double(18,2) DEFAULT '0.00',
  `trans_item_sell_total` double(18,2) DEFAULT '0.00' COMMENT 'By Trigger',
  `trans_item_note` varchar(255) DEFAULT NULL,
  `trans_item_date_created` datetime DEFAULT NULL,
  `trans_item_date_updated` datetime DEFAULT NULL,
  `trans_item_user_id` bigint DEFAULT NULL,
  `trans_item_flag` int DEFAULT NULL COMMENT '1=Has Trans_ID, 0=Temporary',
  `trans_item_ref` varchar(255) DEFAULT NULL,
  `trans_item_position` int DEFAULT '0' COMMENT '1=IN, 2=OUT',
  `trans_item_trans_session` varchar(255) DEFAULT NULL,
  `trans_item_session` varchar(255) DEFAULT NULL,
  `trans_item_pack` varchar(255) DEFAULT NULL COMMENT 'Satuan Coli/Pack/Dus',
  `trans_item_pack_unit` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`trans_item_id`) USING BTREE,
  KEY `TRANS` (`trans_item_trans_id`) USING BTREE,
  KEY `ORDER` (`trans_item_order_id`) USING BTREE,
  KEY `USER` (`trans_item_user_id`) USING BTREE,
  KEY `ORDER_ITEM` (`trans_item_order_item_id`) USING BTREE,
  KEY `PRODUCT` (`trans_item_product_id`) USING BTREE,
  KEY `LOCATION` (`trans_item_location_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Table structure for table `types` */

DROP TABLE IF EXISTS `types`;

CREATE TABLE `types` (
  `type_id` bigint NOT NULL AUTO_INCREMENT,
  `type_for` int DEFAULT NULL,
  `type_type` int DEFAULT NULL,
  `type_name` varchar(255) DEFAULT NULL,
  `type_doc` varchar(255) DEFAULT NULL,
  `type_flag` int DEFAULT '1',
  `type_date_created` datetime DEFAULT NULL,
  PRIMARY KEY (`type_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Table structure for table `types_paids` */

DROP TABLE IF EXISTS `types_paids`;

CREATE TABLE `types_paids` (
  `paid_id` bigint NOT NULL AUTO_INCREMENT COMMENT 'Petunjuk trans=trans_paid_type',
  `paid_name` varchar(255) DEFAULT NULL,
  `paid_note` varchar(255) DEFAULT NULL,
  `paid_image` varchar(255) DEFAULT NULL,
  `paid_flag` int DEFAULT NULL,
  `paid_date_created` datetime DEFAULT NULL,
  PRIMARY KEY (`paid_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `units` */

DROP TABLE IF EXISTS `units`;

CREATE TABLE `units` (
  `unit_id` bigint NOT NULL AUTO_INCREMENT,
  `unit_user_id` bigint DEFAULT NULL,
  `unit_branch_id` bigint DEFAULT NULL,
  `unit_name` varchar(255) DEFAULT NULL,
  `unit_note` varchar(255) DEFAULT NULL,
  `unit_qty` varchar(255) DEFAULT NULL,
  `unit_date_created` datetime DEFAULT NULL,
  `unit_date_updated` datetime DEFAULT NULL,
  `unit_flag` int DEFAULT NULL,
  PRIMARY KEY (`unit_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `user_id` bigint NOT NULL AUTO_INCREMENT,
  `user_branch_id` bigint DEFAULT NULL,
  `user_user_group_id` bigint DEFAULT NULL,
  `user_type` int DEFAULT NULL COMMENT '0=Owner, 1=Karyawan, 2=Dokter',
  `user_code` varchar(255) DEFAULT NULL COMMENT 'nik',
  `user_username` varchar(255) DEFAULT NULL,
  `user_fullname` varchar(255) DEFAULT NULL,
  `user_place_birth` varchar(255) DEFAULT NULL,
  `user_birth_of_date` date DEFAULT NULL,
  `user_gender` varchar(255) DEFAULT NULL,
  `user_address` varchar(255) DEFAULT NULL,
  `user_phone_1` varchar(255) DEFAULT NULL,
  `user_phone_2` varchar(255) DEFAULT NULL,
  `user_email_1` varchar(255) DEFAULT NULL,
  `user_email_2` varchar(255) DEFAULT NULL,
  `user_password` varchar(255) DEFAULT NULL,
  `user_theme` varchar(255) DEFAULT NULL,
  `user_date_created` datetime DEFAULT NULL,
  `user_date_updated` datetime DEFAULT NULL,
  `user_flag` int DEFAULT '0' COMMENT '1=Aktif, 0=NonActive',
  `user_activation` int DEFAULT '0' COMMENT '1=Confirmed Email, 0=NotCofirmed',
  `user_activation_code` varchar(255) DEFAULT NULL,
  `user_date_activation` datetime DEFAULT NULL,
  `user_referal_code` varchar(255) DEFAULT NULL,
  `user_registration_from_referal` varchar(255) DEFAULT NULL,
  `user_date_last_login` datetime DEFAULT NULL,
  `user_session` varchar(255) DEFAULT NULL,
  `user_otp` int DEFAULT NULL COMMENT 'One Time Password',
  `user_menu_style` int DEFAULT NULL COMMENT '0=Vertical Menu, 1=Horizontal Menu',
  `user_balance` double(18,2) DEFAULT '0.00' COMMENT 'TR Balance',
  `user_total_link` bigint DEFAULT NULL,
  `user_check_price_buy` int DEFAULT '0' COMMENT 'Riwayat Harga Beli',
  `user_check_price_sell` int DEFAULT '0' COMMENT 'Riwayat Harga Jual',
  PRIMARY KEY (`user_id`) USING BTREE,
  KEY `USER_GROUP` (`user_user_group_id`) USING BTREE,
  KEY `BRANCH` (`user_branch_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `users_balances` */

DROP TABLE IF EXISTS `users_balances`;

CREATE TABLE `users_balances` (
  `user_balance_id` bigint NOT NULL AUTO_INCREMENT,
  `user_balance_branch_id` bigint DEFAULT NULL,
  `user_balance_user_id` bigint DEFAULT NULL,
  `user_balance_app_branch_billing_id` bigint DEFAULT NULL,
  `user_balance_code` varchar(255) DEFAULT NULL COMMENT 'Transaction Code',
  `user_balance_number` bigint DEFAULT NULL,
  `user_balance_ref` varchar(255) DEFAULT NULL COMMENT 'FIFO Method',
  `user_balance_date` datetime DEFAULT NULL,
  `user_balance_type` int DEFAULT NULL COMMENT '1=Register, 2=Buy First Package, 3=Renewal Package',
  `user_balance_note` text,
  `user_balance_debit` double(18,2) DEFAULT NULL,
  `user_balance_credit` double(18,2) DEFAULT NULL,
  `user_balance_flag` int DEFAULT NULL COMMENT '1=Active, 2=Nonaktive',
  `user_balance_date_created` datetime DEFAULT NULL,
  `user_balance_date_updated` datetime DEFAULT NULL,
  PRIMARY KEY (`user_balance_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Table structure for table `users_groups` */

DROP TABLE IF EXISTS `users_groups`;

CREATE TABLE `users_groups` (
  `user_group_id` bigint NOT NULL AUTO_INCREMENT,
  `user_group_name` varchar(255) DEFAULT NULL,
  `user_group_date_created` datetime DEFAULT NULL,
  `user_group_date_updated` datetime DEFAULT NULL,
  PRIMARY KEY (`user_group_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `users_menus` */

DROP TABLE IF EXISTS `users_menus`;

CREATE TABLE `users_menus` (
  `user_menu_id` bigint NOT NULL AUTO_INCREMENT,
  `user_menu_user_id` bigint DEFAULT NULL,
  `user_menu_menu_parent_id` bigint DEFAULT NULL,
  `user_menu_menu_id` bigint DEFAULT NULL,
  `user_menu_action` int DEFAULT NULL COMMENT '1=view, 2=create, 3=read, 4=update, 5=delete, 6=approve',
  `user_menu_date_created` datetime DEFAULT NULL,
  `user_menu_date_updated` datetime DEFAULT NULL,
  `user_menu_flag` int DEFAULT NULL,
  PRIMARY KEY (`user_menu_id`) USING BTREE,
  KEY `MENU` (`user_menu_menu_id`) USING BTREE,
  KEY `USER` (`user_menu_user_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `vendors` */

DROP TABLE IF EXISTS `vendors`;

CREATE TABLE `vendors` (
  `vendor_id` int NOT NULL AUTO_INCREMENT,
  `vendor_name` varchar(255) DEFAULT NULL,
  `vendor_api_key` varchar(255) DEFAULT NULL,
  `vendor_public_key` varchar(255) DEFAULT NULL,
  `vendor_api_signature` varchar(255) DEFAULT NULL,
  `vendor_token` blob,
  `vendor_url` varchar(255) DEFAULT NULL,
  `vendor_flag` int DEFAULT NULL,
  `vendor_note` varchar(255) DEFAULT NULL,
  `vendor_date_created` datetime DEFAULT NULL,
  `vendor_date_updated` datetime DEFAULT NULL,
  PRIMARY KEY (`vendor_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `vendors_logs` */

DROP TABLE IF EXISTS `vendors_logs`;

CREATE TABLE `vendors_logs` (
  `log_id` bigint NOT NULL AUTO_INCREMENT,
  `log_vendor_id` int DEFAULT NULL COMMENT 'Vendor ID',
  `log_vendor_name` varchar(255) DEFAULT NULL COMMENT 'Trigger Vendor Name',
  `log_message` blob COMMENT 'Callback POST Decode',
  `log_flag` int DEFAULT NULL,
  `log_date_created` datetime DEFAULT NULL,
  PRIMARY KEY (`log_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `villages` */

DROP TABLE IF EXISTS `villages`;

CREATE TABLE `villages` (
  `village_id` int NOT NULL,
  `village_district_id` int NOT NULL,
  `village_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `village_flag` int DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8_unicode_ci;

/*Table structure for table `vouchers` */

DROP TABLE IF EXISTS `vouchers`;

CREATE TABLE `vouchers` (
  `voucher_id` bigint NOT NULL AUTO_INCREMENT,
  `voucher_type` int DEFAULT NULL COMMENT '1=Voucher, 2=Promo',
  `voucher_title` varchar(255) DEFAULT NULL COMMENT 'Judul Voucher',
  `voucher_code` varchar(255) DEFAULT NULL COMMENT 'Kode Voucer exm #PROMO_2023#, Harus Uniq',
  `voucher_minimum_transaction` double(18,0) DEFAULT '0' COMMENT 'Minimal Transaksi POS, 0 kan jika tidak ada',
  `voucher_price` double(18,0) DEFAULT '0' COMMENT 'Nominal Voucher untuk klaim, 0 kan jika tidak ada',
  `voucher_discount_percentage` double(3,0) DEFAULT NULL,
  `voucher_date_start` datetime DEFAULT NULL COMMENT 'Tgl Berlaku Voucher',
  `voucher_date_end` datetime DEFAULT NULL COMMENT 'Tgl Berakhir Voucher',
  `voucher_url` blob COMMENT 'Voucher Gambar Alamat',
  `voucher_user_id` bigint DEFAULT NULL,
  `voucher_flag` int DEFAULT NULL COMMENT '1=Aktif, 0=Nonaktif, 4=Hapus',
  `voucher_date_created` datetime DEFAULT NULL,
  `voucher_date_updated` datetime DEFAULT NULL,
  `voucher_session` varchar(255) DEFAULT NULL,
  `voucher_trans_count` int DEFAULT '0' COMMENT 'Trigger yg mengisi',
  `voucher_date_updated_use` datetime DEFAULT NULL COMMENT 'Untuk Menjalankan Trigger',
  PRIMARY KEY (`voucher_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
