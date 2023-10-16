/*
SQLyog Enterprise v13.1.1 (64 bit)
MySQL - 10.3.32-MariaDB-cll-lve : Database - u5219906_jrn
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`u5219906_jrn` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `u5219906_jrn`;

/*Table structure for table `_mutasi` */

DROP TABLE IF EXISTS `_mutasi`;

CREATE TABLE `_mutasi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tgl` varchar(255) DEFAULT NULL,
  `berita` varchar(255) DEFAULT NULL,
  `nilai` varchar(255) DEFAULT NULL,
  `operator` varchar(255) DEFAULT NULL,
  `debit` double(18,2) DEFAULT NULL,
  `kredit` double(18,2) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=477 DEFAULT CHARSET=latin1;

/*Table structure for table `accounts` */

DROP TABLE IF EXISTS `accounts`;

CREATE TABLE `accounts` (
  `account_id` bigint(255) NOT NULL AUTO_INCREMENT,
  `account_branch_id` int(11) DEFAULT 0,
  `account_parent_id` bigint(255) DEFAULT NULL,
  `account_group` int(11) DEFAULT NULL COMMENT '1=Asset,2=Liability,3=Equity,4=Pendapatan,5=Biaya',
  `account_group_sub` int(11) DEFAULT NULL,
  `account_group_sub_name` varchar(255) DEFAULT NULL,
  `account_code` varchar(255) DEFAULT NULL,
  `account_name` varchar(255) DEFAULT NULL,
  `account_side` int(11) DEFAULT NULL COMMENT '1=Debit, 2=Kredit',
  `account_show` int(11) DEFAULT NULL COMMENT '1=Show, 2=Hide',
  `account_tree` int(11) DEFAULT NULL COMMENT 'Sort Asc',
  `account_saldo` varchar(255) DEFAULT '0',
  `account_info` varchar(255) DEFAULT NULL,
  `account_user_id` bigint(255) DEFAULT NULL,
  `account_date_created` datetime DEFAULT NULL,
  `account_date_updated` datetime DEFAULT NULL,
  `account_flag` int(11) DEFAULT 1 COMMENT '1=Aktif, 0=Delete',
  `account_session` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`account_id`) USING BTREE,
  KEY `PARENT` (`account_parent_id`) USING BTREE,
  KEY `USER` (`account_user_id`) USING BTREE,
  KEY `BRANCH` (`account_branch_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=695 DEFAULT CHARSET=latin1;

/*Table structure for table `accounts_groups` */

DROP TABLE IF EXISTS `accounts_groups`;

CREATE TABLE `accounts_groups` (
  `group_id` int(5) NOT NULL AUTO_INCREMENT,
  `group_group_id` int(5) DEFAULT NULL,
  `group_group_sub_id` int(5) DEFAULT NULL,
  `group_name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`group_id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=latin1;

/*Table structure for table `accounts_maps` */

DROP TABLE IF EXISTS `accounts_maps`;

CREATE TABLE `accounts_maps` (
  `account_map_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `account_map_branch_id` bigint(20) DEFAULT NULL,
  `account_map_account_id` bigint(20) DEFAULT NULL,
  `account_map_for_transaction` bigint(20) DEFAULT NULL COMMENT '1=Pembelian, 2=Penjualan, 3=Persediaan, 4=Stok Opname, 10=Other',
  `account_map_type` bigint(20) DEFAULT NULL,
  `account_map_flag` int(11) DEFAULT NULL,
  `account_map_note` varchar(255) DEFAULT NULL,
  `account_map_formula` varchar(10) DEFAULT NULL COMMENT 'D=Debit, C=Credit',
  `account_map_date_created` datetime DEFAULT NULL,
  `account_map_date_updated` datetime DEFAULT NULL,
  `account_map_session` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`account_map_id`) USING BTREE,
  KEY `ACCOUNT` (`account_map_account_id`) USING BTREE,
  KEY `BRANCH` (`account_map_branch_id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=91 DEFAULT CHARSET=latin1;

/*Table structure for table `activities` */

DROP TABLE IF EXISTS `activities`;

CREATE TABLE `activities` (
  `activity_id` bigint(50) NOT NULL AUTO_INCREMENT,
  `activity_branch_id` int(5) DEFAULT 0,
  `activity_user_id` bigint(50) DEFAULT NULL,
  `activity_action` int(11) DEFAULT 0 COMMENT '0=none, 1=login, 2=create, 3=read, 4=update, 5=delete, 6=print, 7=aktifkan, 8=nonaktifkan, 9=pengajuan_persetujuan',
  `activity_table` varchar(255) DEFAULT NULL,
  `activity_table_id` bigint(50) DEFAULT NULL,
  `activity_text_1` varchar(255) DEFAULT NULL,
  `activity_text_2` varchar(255) DEFAULT NULL,
  `activity_text_3` varchar(255) DEFAULT NULL,
  `activity_date_created` datetime DEFAULT NULL,
  `activity_flag` int(5) DEFAULT 0 COMMENT '1=Show, 0=Hide',
  `activity_transaction` varchar(255) DEFAULT NULL,
  `activity_type` int(5) DEFAULT 1 COMMENT '1=Master, 2=Transaction',
  PRIMARY KEY (`activity_id`) USING BTREE,
  KEY `USER` (`activity_user_id`) USING BTREE,
  KEY `BRANCH` (`activity_branch_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=9052 DEFAULT CHARSET=latin1;

/*Table structure for table `app_branch_billings` */

DROP TABLE IF EXISTS `app_branch_billings`;

CREATE TABLE `app_branch_billings` (
  `app_branch_billing_id` bigint(50) NOT NULL AUTO_INCREMENT,
  `app_branch_billing_app_package_id` bigint(50) DEFAULT NULL,
  `app_branch_billing_branch_id` bigint(50) DEFAULT NULL,
  `app_branch_billing_number` varchar(255) DEFAULT NULL,
  `app_branch_billing_date` datetime DEFAULT NULL,
  `app_branch_billing_uniq_nominal` varchar(255) DEFAULT NULL,
  `app_branch_billing_total` varchar(255) DEFAULT NULL,
  `app_branch_billing_is_paid` int(5) DEFAULT NULL,
  `app_branch_billing_start` datetime DEFAULT NULL,
  `app_branch_billing_end` datetime DEFAULT NULL,
  `app_branch_billing_date_created` datetime DEFAULT NULL,
  `app_branch_billing_flag` int(5) DEFAULT NULL,
  PRIMARY KEY (`app_branch_billing_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Table structure for table `app_packages` */

DROP TABLE IF EXISTS `app_packages`;

CREATE TABLE `app_packages` (
  `app_package_id` bigint(50) NOT NULL AUTO_INCREMENT,
  `app_package_name` varchar(255) DEFAULT NULL,
  `app_package_description` text DEFAULT NULL,
  `app_package_publish_price` varchar(255) DEFAULT NULL,
  `app_package_promo_price` varchar(255) DEFAULT NULL,
  `app_package_flag` int(5) DEFAULT NULL,
  `app_package_date_created` datetime DEFAULT NULL,
  PRIMARY KEY (`app_package_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

/*Table structure for table `app_vouchers` */

DROP TABLE IF EXISTS `app_vouchers`;

CREATE TABLE `app_vouchers` (
  `app_voucher_id` bigint(50) NOT NULL AUTO_INCREMENT,
  `app_voucher_type` int(5) DEFAULT NULL,
  `app_voucher_code` varchar(255) DEFAULT NULL,
  `app_voucher_total` varchar(255) DEFAULT NULL,
  `app_voucher_flag` int(5) DEFAULT NULL,
  `app_voucher_date_created` datetime DEFAULT NULL,
  `app_voucher_user_id` bigint(50) DEFAULT NULL,
  PRIMARY KEY (`app_voucher_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

/*Table structure for table `approvals` */

DROP TABLE IF EXISTS `approvals`;

CREATE TABLE `approvals` (
  `approval_id` bigint(255) NOT NULL AUTO_INCREMENT,
  `approval_user_id` bigint(255) DEFAULT NULL COMMENT 'User Approval',
  `approval_user_from` bigint(20) DEFAULT NULL COMMENT 'User Request',
  `approval_level` int(11) DEFAULT 1 COMMENT '1=Tingkat 1, 2=Tingkat 2',
  `approval_session` varchar(255) DEFAULT NULL COMMENT 'Session Approval',
  `approval_from_table` int(5) DEFAULT NULL COMMENT '1=Order, 2=Trans, 3=Journals',
  `approval_from_session` varchar(255) DEFAULT NULL COMMENT 'Session From Order, Trans, Journal',
  `approval_date_created` datetime DEFAULT NULL,
  `approval_flag` int(5) DEFAULT 0 COMMENT '0=Request, 1=Approve, 2=Pending, 3=Denied, 4=Delete by User',
  `approval_date_action` datetime DEFAULT NULL,
  PRIMARY KEY (`approval_id`),
  KEY `APPROVAL_ID` (`approval_id`) USING BTREE,
  KEY `USER` (`approval_user_id`) USING BTREE,
  KEY `FROM_SESSION` (`approval_from_session`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

/*Table structure for table `banks` */

DROP TABLE IF EXISTS `banks`;

CREATE TABLE `banks` (
  `bank_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `bank_category_id` bigint(20) DEFAULT NULL,
  `bank_session` varchar(255) DEFAULT NULL,
  `bank_account_name` varchar(255) DEFAULT NULL,
  `bank_account_number` varchar(255) DEFAULT NULL,
  `bank_account_username` varchar(255) DEFAULT NULL,
  `bank_account_password` varchar(255) DEFAULT NULL,
  `bank_account_business` varchar(255) DEFAULT NULL,
  `bank_minute_interval` int(11) DEFAULT NULL,
  `bank_email_notification` varchar(255) DEFAULT NULL,
  `bank_phone_notification` varchar(255) DEFAULT NULL,
  `bank_date_created` datetime DEFAULT NULL,
  `bank_date_updated` datetime DEFAULT NULL,
  `bank_flag` varchar(255) DEFAULT NULL,
  `bank_user_session` varchar(255) DEFAULT NULL,
  `bank_api_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`bank_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

/*Table structure for table `branchs` */

DROP TABLE IF EXISTS `branchs`;

CREATE TABLE `branchs` (
  `branch_id` bigint(50) NOT NULL AUTO_INCREMENT,
  `branch_user_id` bigint(50) DEFAULT NULL COMMENT 'id_user penanggung jawab',
  `branch_code` varchar(255) DEFAULT NULL,
  `branch_name` varchar(255) DEFAULT NULL,
  `branch_note` varchar(255) DEFAULT NULL,
  `branch_specialist_id` bigint(50) DEFAULT NULL,
  `branch_slogan` varchar(255) DEFAULT NULL,
  `branch_address` varchar(255) DEFAULT NULL,
  `branch_phone_1` varchar(255) DEFAULT NULL,
  `branch_phone_2` varchar(255) DEFAULT NULL,
  `branch_email_1` varchar(255) DEFAULT NULL,
  `branch_email_2` varchar(255) DEFAULT NULL,
  `branch_district` varchar(255) DEFAULT NULL,
  `branch_city` varchar(255) DEFAULT NULL,
  `branch_province` varchar(255) DEFAULT NULL,
  `branch_logo` text DEFAULT NULL,
  `branch_logo_sidebar` text DEFAULT NULL,
  `branch_date_created` datetime DEFAULT NULL,
  `branch_date_updated` datetime DEFAULT NULL,
  `branch_flag` int(5) DEFAULT NULL COMMENT '1=Aktif, 0=Nonaktif',
  `branch_bank_name` varchar(255) DEFAULT NULL,
  `branch_bank_branch` varchar(255) DEFAULT NULL,
  `branch_bank_address` varchar(255) DEFAULT NULL,
  `branch_bank_account_number` varchar(255) DEFAULT NULL,
  `branch_bank_account_name` varchar(255) DEFAULT NULL,
  `branch_province_id` bigint(50) DEFAULT NULL,
  `branch_city_id` bigint(50) DEFAULT NULL,
  `branch_district_id` bigint(50) DEFAULT NULL,
  `branch_village_id` bigint(50) DEFAULT NULL,
  `branch_transaction_with_stock` varchar(255) DEFAULT NULL,
  `branch_transaction_with_journal` varchar(255) DEFAULT NULL,
  `branch_session` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`branch_id`) USING BTREE,
  KEY `USER` (`branch_user_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

/*Table structure for table `branchs_specialists` */

DROP TABLE IF EXISTS `branchs_specialists`;

CREATE TABLE `branchs_specialists` (
  `specialist_id` bigint(50) NOT NULL AUTO_INCREMENT,
  `specialist_name` varchar(255) DEFAULT NULL,
  `specialist_name_translate` varchar(500) DEFAULT NULL,
  `specialist_flag` int(5) DEFAULT NULL,
  `specialist_date_created` datetime DEFAULT NULL,
  PRIMARY KEY (`specialist_id`)
) ENGINE=MyISAM AUTO_INCREMENT=39 DEFAULT CHARSET=latin1;

/*Table structure for table `branchs_specialists_accounts` */

DROP TABLE IF EXISTS `branchs_specialists_accounts`;

CREATE TABLE `branchs_specialists_accounts` (
  `item_id` bigint(50) NOT NULL AUTO_INCREMENT,
  `item_branch_specialist_id` bigint(50) DEFAULT NULL,
  `item_code` varchar(255) DEFAULT NULL,
  `item_name` varchar(255) DEFAULT NULL,
  `item_group` int(5) DEFAULT NULL,
  `item_group_sub` int(5) DEFAULT NULL,
  `item_group_sub_name` varchar(255) DEFAULT NULL,
  `item_parent_id` bigint(50) DEFAULT NULL,
  `item_show` int(5) DEFAULT NULL,
  `item_side` int(5) DEFAULT NULL,
  `item_flag` int(5) DEFAULT NULL,
  `item_date_created` datetime DEFAULT NULL,
  `item_session` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`item_id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=146 DEFAULT CHARSET=latin1;

/*Table structure for table `branchs_specialists_accounts_maps` */

DROP TABLE IF EXISTS `branchs_specialists_accounts_maps`;

CREATE TABLE `branchs_specialists_accounts_maps` (
  `account_map_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `account_branch_specialist_id` bigint(50) DEFAULT NULL,
  `account_map_for_transaction` bigint(20) DEFAULT NULL COMMENT '1=Pembelian, 2=Penjualan, 3=Persediaan, 4=Stok Opname, 10=Other',
  `account_map_type` bigint(20) DEFAULT NULL,
  `account_map_flag` int(11) DEFAULT NULL,
  `account_map_note` varchar(255) DEFAULT NULL,
  `account_map_formula` varchar(10) DEFAULT NULL COMMENT 'D=Debit, C=Credit',
  `account_map_date_created` datetime DEFAULT NULL,
  `account_map_date_updated` datetime DEFAULT NULL,
  `account_map_session` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`account_map_id`)
) ENGINE=MyISAM AUTO_INCREMENT=25 DEFAULT CHARSET=latin1;

/*Table structure for table `categories` */

DROP TABLE IF EXISTS `categories`;

CREATE TABLE `categories` (
  `category_id` int(255) NOT NULL AUTO_INCREMENT,
  `category_type` int(11) DEFAULT 0 COMMENT '1=Product, 2=News, 3=MessageWhatsapp, 4=Contact Group',
  `category_branch_id` int(5) DEFAULT 0,
  `category_parent_id` int(255) DEFAULT 0,
  `category_code` varchar(255) DEFAULT NULL,
  `category_name` varchar(255) DEFAULT NULL,
  `category_url` varchar(255) DEFAULT NULL,
  `category_icon` varchar(255) DEFAULT NULL,
  `category_date_created` datetime DEFAULT NULL,
  `category_date_updated` datetime DEFAULT NULL,
  `category_user_id` int(255) DEFAULT NULL,
  `category_flag` int(5) DEFAULT 0,
  PRIMARY KEY (`category_id`) USING BTREE,
  KEY `BRANCH` (`category_branch_id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=39 DEFAULT CHARSET=latin1;

/*Table structure for table `cities` */

DROP TABLE IF EXISTS `cities`;

CREATE TABLE `cities` (
  `city_id` int(50) NOT NULL,
  `city_province_id` int(50) NOT NULL,
  `city_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `city_flag` int(5) DEFAULT 1,
  PRIMARY KEY (`city_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `contacts` */

DROP TABLE IF EXISTS `contacts`;

CREATE TABLE `contacts` (
  `contact_id` bigint(50) NOT NULL AUTO_INCREMENT,
  `contact_branch_id` int(5) DEFAULT 0,
  `contact_type` varchar(10) DEFAULT NULL COMMENT '1=Supplier, 2=Customer, 3=Karyawan, 4=Pasien, 5-Asuransi',
  `contact_category_id` bigint(255) DEFAULT NULL,
  `contact_code` varchar(255) DEFAULT NULL,
  `contact_name` varchar(255) DEFAULT NULL,
  `contact_address` varchar(255) DEFAULT NULL,
  `contact_phone_1` varchar(255) DEFAULT NULL,
  `contact_phone_2` varchar(255) DEFAULT NULL,
  `contact_email_1` varchar(255) DEFAULT NULL,
  `contact_email_2` varchar(255) DEFAULT NULL,
  `contact_company` varchar(255) DEFAULT NULL,
  `contact_image` tinytext DEFAULT NULL,
  `contact_url` tinytext DEFAULT NULL,
  `contact_gender` int(5) DEFAULT NULL COMMENT '1=Laki, 2=Perempuan',
  `contact_birth_city_id` bigint(50) DEFAULT NULL,
  `contact_birth_city_name` varchar(255) DEFAULT NULL COMMENT 'By Trigger',
  `contact_birth_date` date DEFAULT NULL,
  `contact_user_id` bigint(50) DEFAULT NULL,
  `contact_account_receivable_account_id` bigint(50) DEFAULT NULL,
  `contact_account_payable_account_id` bigint(50) DEFAULT NULL,
  `contact_date_created` datetime DEFAULT NULL,
  `contact_date_updated` datetime DEFAULT NULL,
  `contact_flag` int(5) DEFAULT NULL COMMENT '1=Aktif, 0=Delete',
  `contact_identity_type` varchar(255) DEFAULT '0' COMMENT '0, KTP, SIM, Pasport',
  `contact_identity_number` varchar(255) DEFAULT NULL,
  `contact_fax` varchar(255) DEFAULT NULL,
  `contact_npwp` varchar(255) DEFAULT NULL,
  `contact_handphone` varchar(255) DEFAULT NULL,
  `contact_note` text DEFAULT NULL,
  `contact_visitor` varchar(255) DEFAULT NULL,
  `contact_parent_id` bigint(50) DEFAULT NULL COMMENT 'Contact Asuransi',
  `contact_parent_name` varchar(255) DEFAULT NULL COMMENT 'By Trigger',
  `contact_city_id` bigint(50) DEFAULT NULL,
  `contact_province_id` bigint(50) DEFAULT NULL,
  `contact_session` varchar(255) DEFAULT NULL,
  `contact_ascii` int(255) DEFAULT NULL,
  PRIMARY KEY (`contact_id`) USING BTREE,
  KEY `BRANCH` (`contact_branch_id`) USING BTREE,
  KEY `CATEGORY` (`contact_category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

/*Table structure for table `devices` */

DROP TABLE IF EXISTS `devices`;

CREATE TABLE `devices` (
  `device_id` bigint(255) NOT NULL AUTO_INCREMENT,
  `device_branch_id` bigint(255) DEFAULT NULL,
  `device_number` varchar(255) DEFAULT NULL,
  `device_flag` int(1) DEFAULT 0,
  `device_ignore` tinyint(1) DEFAULT 0 COMMENT '1=Device tidak akan dipakai',
  `device_session` mediumtext DEFAULT NULL,
  `device_date_created` datetime DEFAULT NULL,
  `device_date_updated` datetime DEFAULT NULL COMMENT 'Diisi Otomatis oleh SQL',
  `device_token` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`device_id`),
  KEY `BRANCH` (`device_branch_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=latin1;

/*Table structure for table `districts` */

DROP TABLE IF EXISTS `districts`;

CREATE TABLE `districts` (
  `district_id` int(50) NOT NULL,
  `district_city_id` int(50) NOT NULL,
  `district_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `district_flag` int(5) DEFAULT 1,
  PRIMARY KEY (`district_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `flows` */

DROP TABLE IF EXISTS `flows`;

CREATE TABLE `flows` (
  `flow_id` bigint(255) NOT NULL AUTO_INCREMENT,
  `flow_type` int(50) DEFAULT 0 COMMENT '1=Registration, 2=Order',
  `flow_name` varchar(255) DEFAULT NULL,
  `flow_phone` varchar(255) DEFAULT NULL,
  `flow_data` text DEFAULT NULL,
  `flow_flag` int(5) DEFAULT 0 COMMENT '1=Confirmed, 4=Delete',
  `flow_recipient_number` varchar(255) DEFAULT NULL,
  `flow_recipient_name` varchar(255) DEFAULT NULL,
  `flow_sender_number` varchar(255) DEFAULT NULL,
  `flow_date_created` datetime DEFAULT NULL,
  `flow_session` varchar(255) DEFAULT NULL,
  `flow_session_group` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`flow_id`)
) ENGINE=InnoDB AUTO_INCREMENT=679 DEFAULT CHARSET=latin1;

/*Table structure for table `journals` */

DROP TABLE IF EXISTS `journals`;

CREATE TABLE `journals` (
  `journal_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `journal_branch_id` int(11) DEFAULT 0,
  `journal_type` int(11) DEFAULT NULL COMMENT '1=BayarHutang,2=BayarPiutang,3=KasMasuk,4=KasKeluar,5=MutasiKas,6=UangMukaBeli,7=UangMukaJual',
  `journal_number` varchar(255) DEFAULT NULL,
  `journal_date` datetime DEFAULT NULL,
  `journal_account_id` bigint(20) DEFAULT NULL,
  `journal_total` double(18,2) DEFAULT NULL,
  `journal_contact_id` bigint(20) DEFAULT NULL,
  `journal_paid_type` int(11) DEFAULT NULL COMMENT '1=Cash,2=Bank,3=Credit,4=Debit,5=Digital,6=Cek',
  `journal_note` varchar(255) DEFAULT NULL,
  `journal_date_created` datetime DEFAULT NULL,
  `journal_date_updated` datetime DEFAULT NULL,
  `journal_user_id` bigint(20) DEFAULT NULL,
  `journal_flag` int(11) DEFAULT 0,
  `journal_session` varchar(255) DEFAULT NULL,
  `journal_approval_flag` int(5) DEFAULT 0 COMMENT 'Run From Trigger 1=Approve, 2=Pending, 4=Denied',
  PRIMARY KEY (`journal_id`) USING BTREE,
  KEY `USER` (`journal_user_id`) USING BTREE,
  KEY `CONTACT` (`journal_contact_id`) USING BTREE,
  KEY `BRANCH` (`journal_branch_id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=89 DEFAULT CHARSET=latin1;

/*Table structure for table `journals_items` */

DROP TABLE IF EXISTS `journals_items`;

CREATE TABLE `journals_items` (
  `journal_item_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `journal_item_journal_id` bigint(20) DEFAULT NULL,
  `journal_item_group_session` varchar(255) DEFAULT NULL,
  `journal_item_branch_id` int(11) DEFAULT 0,
  `journal_item_account_id` bigint(20) DEFAULT NULL,
  `journal_item_trans_id` bigint(20) DEFAULT NULL,
  `journal_item_date` datetime DEFAULT NULL,
  `journal_item_type` int(11) DEFAULT NULL,
  `journal_item_type_name` varchar(255) DEFAULT NULL,
  `journal_item_debit` double(18,2) DEFAULT 0.00,
  `journal_item_credit` double(18,2) DEFAULT 0.00,
  `journal_item_note` text DEFAULT NULL,
  `journal_item_user_id` bigint(20) DEFAULT NULL,
  `journal_item_date_created` datetime DEFAULT NULL,
  `journal_item_date_updated` datetime DEFAULT NULL,
  `journal_item_flag` int(11) DEFAULT 0,
  `journal_item_position` int(11) DEFAULT 2 COMMENT '1=Header, 2=Detail',
  `journal_item_journal_session` varchar(255) DEFAULT NULL,
  `journal_item_session` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`journal_item_id`) USING BTREE,
  KEY `JOURNAL` (`journal_item_journal_id`) USING BTREE,
  KEY `BRANCH` (`journal_item_branch_id`) USING BTREE,
  KEY `ACCOUNT` (`journal_item_account_id`) USING BTREE,
  KEY `TRANS` (`journal_item_trans_id`) USING BTREE,
  KEY `USER` (`journal_item_user_id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=54138 DEFAULT CHARSET=latin1;

/*Table structure for table `locations` */

DROP TABLE IF EXISTS `locations`;

CREATE TABLE `locations` (
  `location_id` bigint(50) NOT NULL AUTO_INCREMENT,
  `location_branch_id` bigint(50) DEFAULT NULL,
  `location_user_id` bigint(50) DEFAULT NULL COMMENT 'User Penanggung Jawab',
  `location_code` varchar(255) DEFAULT NULL,
  `location_name` varchar(255) DEFAULT NULL,
  `location_address` varchar(255) DEFAULT NULL,
  `location_note` varchar(255) DEFAULT NULL,
  `location_date_created` datetime DEFAULT NULL,
  `location_date_updated` datetime DEFAULT NULL,
  `location_flag` int(5) DEFAULT NULL COMMENT '1=Aktif, 0=Delete',
  PRIMARY KEY (`location_id`) USING BTREE,
  KEY `BRANCH` (`location_branch_id`) USING BTREE,
  KEY `USER` (`location_user_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

/*Table structure for table `menus` */

DROP TABLE IF EXISTS `menus`;

CREATE TABLE `menus` (
  `menu_id` int(20) NOT NULL AUTO_INCREMENT,
  `menu_parent_id` int(5) DEFAULT NULL,
  `menu_name` varchar(255) DEFAULT NULL,
  `menu_icon` varchar(255) DEFAULT NULL,
  `menu_link` varchar(255) DEFAULT NULL,
  `menu_sorting` int(5) DEFAULT NULL,
  `menu_date_created` datetime DEFAULT NULL,
  `menu_flag` int(5) DEFAULT NULL,
  `menu_user_id` bigint(50) DEFAULT NULL,
  PRIMARY KEY (`menu_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=78 DEFAULT CHARSET=latin1;

/*Table structure for table `messages` */

DROP TABLE IF EXISTS `messages`;

CREATE TABLE `messages` (
  `message_id` bigint(255) NOT NULL AUTO_INCREMENT,
  `message_type` int(11) DEFAULT 1 COMMENT '1=text, 2=image, 3=video, 4=document',
  `message_news_id` bigint(255) DEFAULT NULL,
  `message_platform` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `message_category_id` bigint(255) DEFAULT NULL,
  `message_session` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `message_date_created` datetime DEFAULT NULL,
  `message_date_sent` datetime DEFAULT NULL,
  `message_flag` int(11) DEFAULT NULL COMMENT '0=Antrian, 1=Terkirim, 2=Proses, 4=Gagal Dikirim',
  `message_text` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `message_url` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `message_contact_id` bigint(255) DEFAULT NULL,
  `message_contact_name` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `message_contact_number` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `message_api_id` bigint(255) DEFAULT NULL,
  `message_api_flag` int(11) DEFAULT NULL COMMENT '1=Success, 0=Failed',
  `message_device_id` bigint(255) DEFAULT NULL,
  PRIMARY KEY (`message_id`)
) ENGINE=InnoDB AUTO_INCREMENT=114 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `mutations` */

DROP TABLE IF EXISTS `mutations`;

CREATE TABLE `mutations` (
  `mutation_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `mutation_session` varchar(255) DEFAULT NULL,
  `mutation_bank_session` varchar(255) DEFAULT NULL,
  `mutation_bank_id` int(11) DEFAULT NULL,
  `mutation_date` datetime DEFAULT NULL,
  `mutation_text` text DEFAULT NULL,
  `mutation_debit` varchar(255) DEFAULT NULL,
  `mutation_credit` varchar(255) DEFAULT NULL,
  `mutation_total` varchar(255) DEFAULT NULL,
  `mutation_type` varchar(255) DEFAULT NULL,
  `mutation_api_id` int(11) DEFAULT NULL,
  `mutation_api_date` datetime DEFAULT NULL,
  `mutation_api_bank_id` int(11) DEFAULT NULL,
  `mutation_api_bank_code` varchar(255) DEFAULT NULL,
  `mutation_api_bank_name` varchar(255) DEFAULT NULL,
  `mutation_api_bank_account_number` varchar(255) DEFAULT NULL,
  `mutation_notif_phone` varchar(255) DEFAULT NULL,
  `mutation_notif_email` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`mutation_id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;

/*Table structure for table `news` */

DROP TABLE IF EXISTS `news`;

CREATE TABLE `news` (
  `news_id` bigint(50) NOT NULL AUTO_INCREMENT,
  `news_type` int(11) DEFAULT 1 COMMENT '1=News, 2=Promo',
  `news_branch_id` bigint(50) DEFAULT 0,
  `news_category_id` bigint(50) DEFAULT NULL,
  `news_title` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `news_url` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `news_image` tinytext CHARACTER SET latin1 DEFAULT NULL,
  `news_short` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `news_content` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `news_tags` text CHARACTER SET latin1 DEFAULT NULL,
  `news_keywords` text CHARACTER SET latin1 DEFAULT NULL,
  `news_visitor` int(5) DEFAULT 0,
  `news_user_id` bigint(50) DEFAULT NULL,
  `news_date_created` datetime DEFAULT NULL,
  `news_date_updated` datetime DEFAULT NULL,
  `news_flag` int(5) DEFAULT NULL COMMENT '1=Aktif, 0=Delete',
  `news_position` int(5) DEFAULT 1,
  PRIMARY KEY (`news_id`) USING BTREE,
  KEY `BRANCH` (`news_branch_id`) USING BTREE,
  KEY `USER` (`news_user_id`) USING BTREE,
  KEY `CATEGORIES` (`news_category_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `orders` */

DROP TABLE IF EXISTS `orders`;

CREATE TABLE `orders` (
  `order_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `order_branch_id` int(11) DEFAULT 0,
  `order_type` int(11) DEFAULT NULL COMMENT '1=PurchaseOrder, 2=SalesOrder, 3=PenawaranPembelian, 4=PenawaranPenjualan, 5=CheckUp Medicine, 6=CheckUp Laboratory',
  `order_number` varchar(255) DEFAULT NULL,
  `order_date` datetime DEFAULT NULL,
  `order_date_due` datetime DEFAULT NULL COMMENT 'Tanggal Jatuh Tempo',
  `order_contact_id` bigint(20) DEFAULT NULL,
  `order_ppn` int(11) DEFAULT NULL COMMENT '1=Ppn, 0=NonPpn',
  `order_discount` double(18,2) DEFAULT 0.00,
  `order_total` double(18,2) DEFAULT 0.00 COMMENT 'Total BY TRIGGER',
  `order_note` text DEFAULT NULL,
  `order_user_id` bigint(20) DEFAULT NULL,
  `order_ref_id` int(11) DEFAULT NULL,
  `order_date_created` datetime DEFAULT NULL,
  `order_date_updated` datetime DEFAULT NULL,
  `order_flag` int(11) DEFAULT NULL COMMENT '0=Order, 1=Is Payment',
  `order_trans_id` bigint(20) DEFAULT NULL COMMENT 'Many Order => One Trans',
  `order_with_dp` double(18,2) DEFAULT 0.00 COMMENT 'Not Used',
  `order_ref_number` varchar(255) DEFAULT NULL COMMENT 'Nomor Ref Customer/Supplier',
  `order_session` varchar(255) DEFAULT NULL,
  `order_approval_flag` int(5) DEFAULT 0 COMMENT 'Run From Trigger 1=Approve, 2=Pending, 4=Denied',
  PRIMARY KEY (`order_id`) USING BTREE,
  KEY `USER` (`order_user_id`) USING BTREE,
  KEY `CONTACT` (`order_contact_id`) USING BTREE,
  KEY `BRANCH` (`order_branch_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;

/*Table structure for table `orders_items` */

DROP TABLE IF EXISTS `orders_items`;

CREATE TABLE `orders_items` (
  `order_item_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `order_item_branch_id` int(11) DEFAULT 0,
  `order_item_type` int(11) DEFAULT NULL COMMENT '1=PurchaseOrder, 2=SalesOrder, 3=PenawaranPembelian, 4=PenawaranPenjualan, 5=CheckUp Medicine, 6=CheckUp Laboratory',
  `order_item_type_name` varchar(255) DEFAULT NULL,
  `order_item_order_id` bigint(20) DEFAULT NULL,
  `order_item_product_id` bigint(20) DEFAULT NULL,
  `order_item_location_id` bigint(20) DEFAULT NULL,
  `order_item_qty` double(18,2) DEFAULT 0.00,
  `order_item_unit` varchar(255) DEFAULT NULL,
  `order_item_price` double(18,2) DEFAULT 0.00,
  `order_item_discount` double(18,2) DEFAULT 0.00,
  `order_item_total` double(18,2) DEFAULT 0.00,
  `order_item_note` text DEFAULT NULL,
  `order_item_user_id` bigint(20) DEFAULT NULL,
  `order_item_date` datetime DEFAULT NULL,
  `order_item_date_created` datetime DEFAULT NULL,
  `order_item_date_updated` datetime DEFAULT NULL,
  `order_item_flag` int(11) DEFAULT NULL,
  `order_item_recipe_order_item_id` bigint(20) DEFAULT 0,
  `order_item_product_price_id` bigint(20) DEFAULT NULL,
  `order_item_qty_weight` double(18,2) DEFAULT NULL,
  `order_item_ppn` int(11) DEFAULT 0,
  `order_item_order_session` varchar(255) DEFAULT NULL,
  `order_item_session` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`order_item_id`) USING BTREE,
  KEY `USER` (`order_item_user_id`) USING BTREE,
  KEY `ORDER` (`order_item_order_id`) USING BTREE,
  KEY `PRODUCT` (`order_item_product_id`) USING BTREE,
  KEY `LOCATION` (`order_item_location_id`) USING BTREE,
  KEY `BRANCH` (`order_item_branch_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;

/*Table structure for table `print_spoilers` */

DROP TABLE IF EXISTS `print_spoilers`;

CREATE TABLE `print_spoilers` (
  `spoiler_id` bigint(50) NOT NULL AUTO_INCREMENT,
  `spoiler_content` text DEFAULT NULL,
  `spoiler_ip` varchar(255) DEFAULT NULL,
  `spoiler_source_table` varchar(255) DEFAULT NULL,
  `spoiler_source_id` bigint(50) DEFAULT NULL,
  `spoiler_date` datetime DEFAULT NULL,
  `spoiler_flag` int(5) DEFAULT NULL,
  PRIMARY KEY (`spoiler_id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;

/*Table structure for table `printers` */

DROP TABLE IF EXISTS `printers`;

CREATE TABLE `printers` (
  `printer_id` int(5) NOT NULL AUTO_INCREMENT,
  `printer_ip` varchar(255) DEFAULT NULL,
  `printer_name` varchar(255) DEFAULT NULL,
  `printer_note` varchar(255) DEFAULT NULL,
  `printer_flag` int(5) DEFAULT NULL,
  PRIMARY KEY (`printer_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Table structure for table `products` */

DROP TABLE IF EXISTS `products`;

CREATE TABLE `products` (
  `product_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `product_branch_id` bigint(20) NOT NULL,
  `product_category_id` bigint(20) DEFAULT NULL,
  `product_ref_id` bigint(20) DEFAULT NULL COMMENT '1=Jual, 2=Sewa',
  `product_type` int(11) DEFAULT NULL COMMENT '1=Barang/Obat,2=Jasa, 3=Inventaris, 4=Tindakan, 5=Laboratorium, 6=Lain',
  `product_barcode` varchar(255) DEFAULT NULL,
  `product_code` varchar(255) DEFAULT NULL,
  `product_name` varchar(255) DEFAULT NULL,
  `product_unit` varchar(255) DEFAULT NULL,
  `product_note` text DEFAULT NULL,
  `product_price_buy` double(18,2) DEFAULT 0.00,
  `product_price_sell` double(18,2) DEFAULT 0.00,
  `product_min_stock_limit` varchar(255) DEFAULT '0',
  `product_max_stock_limit` varchar(255) DEFAULT '0',
  `product_fee_1` varchar(255) DEFAULT '0' COMMENT 'Pelaksana',
  `product_fee_2` varchar(255) DEFAULT '0' COMMENT 'Asisten',
  `product_manufacture` varchar(255) DEFAULT NULL,
  `product_image` tinytext DEFAULT NULL,
  `product_url` text DEFAULT NULL,
  `product_user_id` bigint(20) DEFAULT NULL,
  `product_date_created` datetime DEFAULT NULL,
  `product_date_updated` datetime DEFAULT NULL,
  `product_flag` int(11) DEFAULT NULL COMMENT '1=Aktif, 0=Delete',
  `product_stock` double(18,2) DEFAULT 0.00,
  `product_with_stock` int(11) DEFAULT 0,
  `product_buy_account_id` bigint(20) DEFAULT NULL,
  `product_sell_account_id` bigint(20) DEFAULT NULL,
  `product_inventory_account_id` bigint(20) DEFAULT NULL,
  `product_visitor` varchar(255) DEFAULT NULL,
  `product_city_id` bigint(20) DEFAULT NULL,
  `product_province_id` bigint(20) DEFAULT NULL,
  `product_square_size` double(18,0) DEFAULT NULL,
  `product_building_size` double(18,0) DEFAULT NULL,
  `product_bedroom` varchar(255) DEFAULT NULL,
  `product_bathroom` varchar(255) DEFAULT NULL,
  `product_garage` varchar(255) DEFAULT NULL,
  `product_contact_id` bigint(20) DEFAULT NULL,
  `product_price_promo` double(18,0) DEFAULT 0,
  `product_address` varchar(255) DEFAULT NULL,
  `product_dimension_size` varchar(255) DEFAULT NULL,
  `product_latitude` varchar(255) DEFAULT NULL,
  `product_longitude` varchar(255) DEFAULT NULL,
  `product_reminder` varchar(255) DEFAULT NULL,
  `product_reminder_date` datetime DEFAULT NULL,
  PRIMARY KEY (`product_id`) USING BTREE,
  KEY `CATEGORIES` (`product_category_id`) USING BTREE,
  KEY `BRANCH` (`product_branch_id`) USING BTREE,
  KEY `REFERENCE` (`product_ref_id`) USING BTREE,
  KEY `UNIT` (`product_unit`) USING BTREE,
  KEY `USER` (`product_user_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=latin1;

/*Table structure for table `products_items` */

DROP TABLE IF EXISTS `products_items`;

CREATE TABLE `products_items` (
  `product_item_id` bigint(50) NOT NULL AUTO_INCREMENT,
  `product_item_product_id` bigint(50) DEFAULT NULL,
  `product_item_type` int(5) DEFAULT NULL COMMENT '1=Other Image, 2=Other Price',
  `product_item_name` varchar(255) DEFAULT NULL,
  `product_item_image` tinytext DEFAULT NULL,
  `product_item_price` double(18,2) DEFAULT 0.00,
  `product_item_date_created` datetime DEFAULT NULL,
  `product_item_date_updated` datetime DEFAULT NULL,
  `product_item_flag` int(5) DEFAULT 1,
  PRIMARY KEY (`product_item_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

/*Table structure for table `products_prices` */

DROP TABLE IF EXISTS `products_prices`;

CREATE TABLE `products_prices` (
  `product_price_id` bigint(50) NOT NULL AUTO_INCREMENT,
  `product_price_product_id` bigint(50) NOT NULL,
  `product_price_name` varchar(255) DEFAULT NULL,
  `product_price_price` decimal(10,2) DEFAULT NULL,
  `product_price_date_created` datetime DEFAULT NULL,
  `product_price_date_updated` datetime DEFAULT NULL,
  `product_price_flag` int(5) DEFAULT 0 COMMENT '1=Publish, 0=Deleted',
  `product_price_user_id` bigint(50) DEFAULT NULL,
  PRIMARY KEY (`product_price_id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

/*Table structure for table `products_recipes` */

DROP TABLE IF EXISTS `products_recipes`;

CREATE TABLE `products_recipes` (
  `recipe_id` bigint(50) NOT NULL AUTO_INCREMENT,
  `recipe_product_id` bigint(50) DEFAULT NULL COMMENT '>0=Product ID, 0=Is Raw Material',
  `recipe_product_id_child` bigint(50) DEFAULT 0,
  `recipe_unit` varchar(255) DEFAULT NULL,
  `recipe_qty` varchar(255) DEFAULT NULL,
  `recipe_note` varchar(255) DEFAULT NULL,
  `recipe_date_created` datetime DEFAULT NULL,
  `recipe_date_updated` datetime DEFAULT NULL,
  `recipe_flag` int(5) DEFAULT 0,
  `recipe_user_id` bigint(50) DEFAULT NULL,
  PRIMARY KEY (`recipe_id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;

/*Table structure for table `provinces` */

DROP TABLE IF EXISTS `provinces`;

CREATE TABLE `provinces` (
  `province_id` int(50) NOT NULL,
  `province_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `province_latitude` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `province_longitude` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `province_flag` int(5) DEFAULT NULL,
  PRIMARY KEY (`province_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `references` */

DROP TABLE IF EXISTS `references`;

CREATE TABLE `references` (
  `ref_id` bigint(50) NOT NULL AUTO_INCREMENT,
  `ref_type` int(5) DEFAULT NULL COMMENT '1=Diagnosa,2=JenisPraktek,3=GolonganBarang,4=JenisPenyakit, 5=JenisLab, 6=JenisInventaris, 7=Table',
  `ref_code` varchar(255) DEFAULT NULL,
  `ref_name` varchar(255) DEFAULT NULL,
  `ref_note` varchar(255) DEFAULT NULL,
  `ref_user_id` bigint(50) DEFAULT NULL,
  `ref_date_created` datetime DEFAULT NULL,
  `ref_date_updated` datetime DEFAULT NULL,
  `ref_flag` int(5) DEFAULT NULL COMMENT '1=Aktif, 0=Delete',
  PRIMARY KEY (`ref_id`) USING BTREE,
  KEY `USER` (`ref_user_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=462 DEFAULT CHARSET=latin1;

/*Table structure for table `surveys` */

DROP TABLE IF EXISTS `surveys`;

CREATE TABLE `surveys` (
  `survey_id` int(11) NOT NULL AUTO_INCREMENT,
  `survey_session` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `survey_date_created` datetime DEFAULT NULL,
  `survey_flag` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `survey_note` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `survey_rating` int(11) DEFAULT NULL,
  `survey_contact_id` bigint(20) DEFAULT NULL,
  `survey_contact_name` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `survey_contact_number` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `survey_date_sent` datetime DEFAULT NULL,
  `survey_text` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `survey_date_updated` datetime DEFAULT NULL,
  `survey_link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`survey_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `tests` */

DROP TABLE IF EXISTS `tests`;

CREATE TABLE `tests` (
  `test_id` int(11) NOT NULL AUTO_INCREMENT,
  `test_name` varchar(255) DEFAULT NULL,
  `test_date` datetime DEFAULT NULL,
  `test_text` text DEFAULT NULL,
  `test_value` double(18,2) DEFAULT NULL,
  `test_flag` int(11) DEFAULT 0,
  PRIMARY KEY (`test_id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=latin1;

/*Table structure for table `trans` */

DROP TABLE IF EXISTS `trans`;

CREATE TABLE `trans` (
  `trans_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `trans_branch_id` int(11) DEFAULT 0,
  `trans_type` int(11) DEFAULT NULL COMMENT '1=Pembelian,2=Penjualan,3=ReturBeli,4=ReturJual,5=MutasiGudang,6=StokOpnamePlus,7=StokOpnameMinus,8=Pemeriksaan',
  `trans_number` varchar(255) DEFAULT NULL,
  `trans_date` datetime DEFAULT NULL,
  `trans_date_due` datetime DEFAULT NULL,
  `trans_ppn` int(11) DEFAULT NULL COMMENT '1=Ppn, 0=NonPpn',
  `trans_note` text DEFAULT NULL,
  `trans_contact_id` bigint(20) DEFAULT NULL,
  `trans_user_id` bigint(20) DEFAULT NULL,
  `trans_order_id` bigint(20) DEFAULT NULL,
  `trans_location_id` bigint(20) DEFAULT NULL,
  `trans_location_to_id` bigint(50) DEFAULT NULL,
  `trans_date_created` datetime DEFAULT NULL,
  `trans_date_updated` datetime DEFAULT NULL,
  `trans_flag` int(11) DEFAULT 0 COMMENT '1=Aktif, 0=Delete',
  `trans_total_dpp` double(18,2) DEFAULT 0.00,
  `trans_discount` double(18,2) DEFAULT 0.00,
  `trans_return` double(18,2) DEFAULT 0.00 COMMENT 'Return Insert By SP/TR',
  `trans_total` double(18,2) DEFAULT 0.00 COMMENT 'Total Faktur',
  `trans_total_paid` double(18,2) DEFAULT 0.00 COMMENT 'Total Terbayar BY TRIGGER',
  `trans_change` double(18,2) DEFAULT 0.00,
  `trans_received` double(18,2) DEFAULT 0.00,
  `trans_fee` double(18,2) DEFAULT 0.00,
  `trans_paid` int(11) DEFAULT 0 COMMENT '1=Paid, 0=Unpaid BY TRIGGER',
  `trans_paid_type` int(11) DEFAULT NULL COMMENT '1=Cash,2=Bank,3=Credit,4=Debit,5=Digital',
  `trans_bank_name` varchar(255) DEFAULT NULL,
  `trans_bank_number` varchar(255) DEFAULT NULL,
  `trans_bank_account_name` varchar(255) DEFAULT NULL,
  `trans_bank_ref` varchar(255) DEFAULT NULL,
  `trans_card_number` varchar(255) DEFAULT NULL,
  `trans_card_bank_name` varchar(255) DEFAULT NULL,
  `trans_card_bank_number` varchar(255) DEFAULT NULL,
  `trans_card_account_name` varchar(255) DEFAULT NULL,
  `trans_card_expired` varchar(255) DEFAULT NULL,
  `trans_card_type` int(11) DEFAULT 0 COMMENT '1=Visa, 2=MasterCard, 3=AmericanExpress',
  `trans_digital_provider` varchar(255) DEFAULT NULL,
  `trans_ref_id` bigint(50) DEFAULT NULL,
  `trans_ref_number` varchar(255) DEFAULT NULL,
  `trans_contact_name` varchar(255) DEFAULT NULL,
  `trans_contact_address` varchar(255) DEFAULT NULL,
  `trans_contact_phone` varchar(255) DEFAULT NULL,
  `trans_contact_email` varchar(255) DEFAULT NULL,
  `trans_session` varchar(255) DEFAULT NULL,
  `trans_approval_flag` int(5) DEFAULT 0 COMMENT 'Run From Trigger 1=Approve, 2=Pending, 4=Denied',
  `trans_vehicle_brand` varchar(255) DEFAULT NULL,
  `trans_vehicle_brand_type_name` varchar(255) DEFAULT NULL,
  `trans_vehicle_plate_number` varchar(255) DEFAULT NULL,
  `trans_vehicle_distance` int(255) DEFAULT NULL,
  `trans_vehicle_person` bigint(20) DEFAULT NULL,
  `trans_id_source` bigint(255) DEFAULT NULL COMMENT 'Only For Return Transaction',
  PRIMARY KEY (`trans_id`) USING BTREE,
  KEY `USER` (`trans_user_id`) USING BTREE,
  KEY `CONTACT` (`trans_contact_id`) USING BTREE,
  KEY `BRANCH` (`trans_branch_id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=282 DEFAULT CHARSET=latin1;

/*Table structure for table `trans_items` */

DROP TABLE IF EXISTS `trans_items`;

CREATE TABLE `trans_items` (
  `trans_item_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `trans_item_branch_id` int(11) DEFAULT 0,
  `trans_item_type` int(11) DEFAULT NULL COMMENT '1=Pembelian,2=Penjualan,3=ReturBeli,4=ReturJual,5=MutasiGudang,6=StokOpnamePlus,7=StokOpnameMinus,8=Pemeriksaan',
  `trans_item_type_name` varchar(255) DEFAULT NULL,
  `trans_item_trans_id` bigint(20) DEFAULT NULL,
  `trans_item_order_id` bigint(20) DEFAULT NULL,
  `trans_item_order_item_id` bigint(20) DEFAULT NULL,
  `trans_item_product_id` bigint(20) DEFAULT NULL,
  `trans_item_location_id` bigint(20) DEFAULT NULL,
  `trans_item_product_type` int(50) DEFAULT NULL,
  `trans_item_date` datetime DEFAULT NULL,
  `trans_item_unit` varchar(255) DEFAULT NULL,
  `trans_item_in_qty` varchar(255) DEFAULT '0',
  `trans_item_in_price` varchar(255) DEFAULT '0',
  `trans_item_out_qty` varchar(255) DEFAULT '0',
  `trans_item_out_price` varchar(255) DEFAULT '0',
  `trans_item_sell_price` varchar(255) DEFAULT '0',
  `trans_item_discount` varchar(255) DEFAULT '0',
  `trans_item_ppn` int(11) DEFAULT 0 COMMENT '0=Non, 1=Ppn',
  `trans_item_total` varchar(255) DEFAULT '0',
  `trans_item_sell_total` varchar(255) DEFAULT '0' COMMENT 'By Trigger',
  `trans_item_note` varchar(255) DEFAULT NULL,
  `trans_item_date_created` datetime DEFAULT NULL,
  `trans_item_date_updated` datetime DEFAULT NULL,
  `trans_item_user_id` bigint(20) DEFAULT NULL,
  `trans_item_flag` int(11) DEFAULT NULL COMMENT '1=Has Trans_ID, 0=Temporary',
  `trans_item_ref` varchar(255) DEFAULT NULL,
  `trans_item_position` int(5) DEFAULT 0 COMMENT '1=IN, 2=OUT',
  `trans_item_trans_session` varchar(255) DEFAULT NULL,
  `trans_item_session` varchar(255) DEFAULT NULL,
  `trans_item_pack` varchar(255) DEFAULT NULL COMMENT 'Satuan Coli/Pack/Dus',
  PRIMARY KEY (`trans_item_id`) USING BTREE,
  KEY `TRANS` (`trans_item_trans_id`) USING BTREE,
  KEY `ORDER` (`trans_item_order_id`) USING BTREE,
  KEY `USER` (`trans_item_user_id`) USING BTREE,
  KEY `ORDER_ITEM` (`trans_item_order_item_id`) USING BTREE,
  KEY `PRODUCT` (`trans_item_product_id`) USING BTREE,
  KEY `LOCATION` (`trans_item_location_id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=246 DEFAULT CHARSET=latin1;

/*Table structure for table `types` */

DROP TABLE IF EXISTS `types`;

CREATE TABLE `types` (
  `type_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `type_for` int(11) DEFAULT NULL,
  `type_type` int(11) DEFAULT NULL,
  `type_name` varchar(255) DEFAULT NULL,
  `type_flag` int(11) DEFAULT 1,
  `type_date_created` datetime DEFAULT NULL,
  PRIMARY KEY (`type_id`)
) ENGINE=MyISAM AUTO_INCREMENT=37 DEFAULT CHARSET=latin1;

/*Table structure for table `units` */

DROP TABLE IF EXISTS `units`;

CREATE TABLE `units` (
  `unit_id` bigint(50) NOT NULL AUTO_INCREMENT,
  `unit_user_id` bigint(20) DEFAULT NULL,
  `unit_branch_id` bigint(20) DEFAULT NULL,
  `unit_name` varchar(255) DEFAULT NULL,
  `unit_note` varchar(255) DEFAULT NULL,
  `unit_qty` varchar(255) DEFAULT NULL,
  `unit_date_created` datetime DEFAULT NULL,
  `unit_date_updated` datetime DEFAULT NULL,
  `unit_flag` int(5) DEFAULT NULL,
  PRIMARY KEY (`unit_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `user_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_branch_id` bigint(20) DEFAULT NULL,
  `user_user_group_id` bigint(20) DEFAULT NULL,
  `user_type` int(11) DEFAULT NULL COMMENT '0=Owner, 1=Karyawan, 2=Dokter',
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
  `user_flag` int(11) DEFAULT 0 COMMENT '1=Aktif, 0=NonActive',
  `user_activation` int(11) DEFAULT 0 COMMENT '1=Confirmed Email, 0=NotCofirmed',
  `user_activation_code` varchar(255) DEFAULT NULL,
  `user_date_activation` datetime DEFAULT NULL,
  `user_referal_code` varchar(255) DEFAULT NULL,
  `user_registration_from_referal` varchar(255) DEFAULT NULL,
  `user_date_last_login` datetime DEFAULT NULL,
  `user_session` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`user_id`) USING BTREE,
  KEY `USER_GROUP` (`user_user_group_id`) USING BTREE,
  KEY `BRANCH` (`user_branch_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

/*Table structure for table `users_balances` */

DROP TABLE IF EXISTS `users_balances`;

CREATE TABLE `users_balances` (
  `user_balance_id` bigint(50) NOT NULL AUTO_INCREMENT,
  `user_balance_branch_id` bigint(50) DEFAULT NULL,
  `user_balance_user_id` bigint(50) DEFAULT NULL,
  `user_balance_app_branch_billing_id` bigint(50) DEFAULT NULL,
  `user_balance_code` varchar(255) DEFAULT NULL COMMENT 'Transaction Code',
  `user_balance_number` bigint(50) DEFAULT NULL,
  `user_balance_ref` varchar(255) DEFAULT NULL COMMENT 'FIFO Method',
  `user_balance_date` datetime DEFAULT NULL,
  `user_balance_type` int(5) DEFAULT NULL COMMENT '1=Register, 2=Buy First Package, 3=Renewal Package',
  `user_balance_note` text DEFAULT NULL,
  `user_balance_debit` double(18,2) DEFAULT NULL,
  `user_balance_credit` double(18,2) DEFAULT NULL,
  `user_balance_flag` int(5) DEFAULT NULL COMMENT '1=Active, 2=Nonaktive',
  `user_balance_date_created` datetime DEFAULT NULL,
  `user_balance_date_updated` datetime DEFAULT NULL,
  PRIMARY KEY (`user_balance_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Table structure for table `users_groups` */

DROP TABLE IF EXISTS `users_groups`;

CREATE TABLE `users_groups` (
  `user_group_id` bigint(50) NOT NULL AUTO_INCREMENT,
  `user_group_name` varchar(255) DEFAULT NULL,
  `user_group_date_created` datetime DEFAULT NULL,
  `user_group_date_updated` datetime DEFAULT NULL,
  PRIMARY KEY (`user_group_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

/*Table structure for table `users_menus` */

DROP TABLE IF EXISTS `users_menus`;

CREATE TABLE `users_menus` (
  `user_menu_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_menu_user_id` bigint(20) DEFAULT NULL,
  `user_menu_menu_parent_id` bigint(20) DEFAULT NULL,
  `user_menu_menu_id` bigint(20) DEFAULT NULL,
  `user_menu_action` int(11) DEFAULT NULL COMMENT '1=view, 2=create, 3=read, 4=update, 5=delete, 6=approve',
  `user_menu_date_created` datetime DEFAULT NULL,
  `user_menu_date_updated` datetime DEFAULT NULL,
  `user_menu_flag` int(11) DEFAULT NULL,
  PRIMARY KEY (`user_menu_id`) USING BTREE,
  KEY `MENU` (`user_menu_menu_id`) USING BTREE,
  KEY `USER` (`user_menu_user_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=199 DEFAULT CHARSET=latin1;

/*Table structure for table `villages` */

DROP TABLE IF EXISTS `villages`;

CREATE TABLE `villages` (
  `village_id` int(50) NOT NULL,
  `village_district_id` int(50) NOT NULL,
  `village_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `village_flag` int(5) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
