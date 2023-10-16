ALTER TABLE `products` ADD COLUMN `product_asset_name` VARCHAR(255) DEFAULT NULL COMMENT 'Nama Asset' AFTER `product_reminder_date`;
ALTER TABLE `products` ADD COLUMN `product_asset_code` VARCHAR(255) DEFAULT NULL COMMENT 'Nomor Asset' AFTER `product_asset_name`;
ALTER TABLE `products` ADD COLUMN `product_asset_note` TEXT DEFAULT NULL COMMENT 'Deskripsi Asset' AFTER `product_asset_code`;

ALTER TABLE `products` ADD COLUMN `product_asset_acquisition_date` DATE DEFAULT NULL COMMENT 'Tgl Akusisi' AFTER `product_asset_note`;
ALTER TABLE `products` ADD COLUMN `product_asset_acquisition_value` DOUBLE(18,2) DEFAULT NULL COMMENT 'Biaya Akusisi' AFTER `product_asset_acquisition_date`;

ALTER TABLE `products` ADD COLUMN `product_asset_dep_flag` INT(5) DEFAULT NULL COMMENT '0=NonDepresiasi, 1=Depresiasi' AFTER `product_asset_acquisition_value`;
ALTER TABLE `products` ADD COLUMN `product_asset_dep_method` INT(5) DEFAULT NULL COMMENT '1=Straight Line, 2=Reducing Balance' AFTER `product_asset_dep_flag`;

ALTER TABLE `products` ADD COLUMN `product_asset_dep_period` INT(255) DEFAULT NULL COMMENT 'Masa Manfaat / Tahun' AFTER `product_asset_dep_method`;
ALTER TABLE `products` ADD COLUMN `product_asset_dep_percentage` DOUBLE(18,2) DEFAULT NULL COMMENT 'Nilai / Tahun Persen' AFTER `product_asset_dep_period`;

ALTER TABLE `products` ADD COLUMN `product_asset_fixed_account_id` BIGINT(255) DEFAULT NULL COMMENT 'Account Asset Tetap' AFTER `product_asset_dep_percentage`;
ALTER TABLE `products` ADD COLUMN `product_asset_cost_account_id` BIGINT(255) DEFAULT NULL COMMENT 'Account Biaya Akusisi' AFTER `product_asset_fixed_account_id`;
ALTER TABLE `products` ADD COLUMN `product_asset_depreciation_account_id` BIGINT(255) DEFAULT NULL COMMENT 'Account Penyusutan' AFTER `product_asset_cost_account_id`;
ALTER TABLE `products` ADD COLUMN `product_asset_accumulated_depreciation_account_id` BIGINT(255) DEFAULT NULL COMMENT 'Account Akumulasi Penyusutan' AFTER `product_asset_depreciation_account_id`;

ALTER TABLE `products` ADD COLUMN `product_asset_accumulated_depreciation_value` DOUBLE(18,2) DEFAULT NULL COMMENT 'Akumulasi Penyusutan' AFTER `product_asset_accumulated_depreciation_account_id`;
ALTER TABLE `products` ADD COLUMN `product_asset_accumulated_depreciation_date` DATE DEFAULT NULL COMMENT 'Tgl Mulai Awal' AFTER `product_asset_accumulated_depreciation_value`;


DELIMITER $$
/* USE `database_name`$$ */
DROP PROCEDURE IF EXISTS `sp_journal_from_asset`$$
CREATE PROCEDURE `sp_journal_from_asset`(
  IN vACTION VARCHAR(255),
  IN vPRODUCT_ID BIGINT(255)
)
BEGIN
    /*
        TYPE
        16 Asset Beli 
        17 Asset Susut
        18 Asset Jual
    */
    -- Block A : Get Product
    -- Block B : Create Debit Credit 

    BLOCK_A:BEGIN
        DECLARE mBRANCH_ID INT(5) DEFAULT 0;
        DECLARE mBRANCH_WITH_JOURNAL VARCHAR(255) DEFAULT 0;
        DECLARE mUSER_ID BIGINT(255);

        DECLARE mACCOUNT_FIXED BIGINT(255);
        DECLARE mACCOUNT_COST BIGINT(255);
        DECLARE mACCOUNT_DEPRECIATION BIGINT(255);
        DECLARE mACCOUNT_ACCUMULATED BIGINT(255);
        DECLARE mACCOUNT_EQUITY BIGINT(255);

        DECLARE mASSET_CODE VARCHAR(255);
        DECLARE mASSET_NAME VARCHAR(255);        

        DECLARE mASSET_ACQUISITION_DATE VARCHAR(255);
        DECLARE mASSET_ACQUISITION_VALUE DOUBLE(18,2) DEFAULT '0.00';
        DECLARE mASSET_DEP_FLAG INT(5); -- 0 Non Dep, 1 Dep
        DECLARE mASSET_DEP_METHOD INT(5); -- 1 Straigt, 2 Reducing
        DECLARE mASSET_DEP_PERIOD INT(5); -- Year
        DECLARE mASSET_DEP_PERCENTAGE DOUBLE(18,2) DEFAULT '0.00'; -- Percen

        DECLARE mASSET_ACCUMULATED_DATE VARCHAR(255); 
        DECLARE mASSET_ACCUMULATED_VALUE DOUBLE(18,2);

        DECLARE mJOURNAL_ID BIGINT(255);
        DECLARE mJOURNAL_ID_2 BIGINT(255);

        DECLARE mFINISHED INTEGER;
        
        -- DECLARE mACTION_CURSOR CURSOR FOR
        --     SELECT column_one, column_two FROM table_name ORDER BY id ASC;
        -- DECLARE CONTINUE HANDLER FOR NOT FOUND SET mFINISHED = 1;
        -- OPEN mACTION_CURSOR;

        -- LOOP_1: LOOP
        --     FETCH mACTION_CURSOR INTO mCOLUMN_ONE, mCOLUMN_TWO;
        --     IF mFINISHED = 1 THEN LEAVE LOOP_1; END IF;

        --     INSERT INTO temps(`COLUMN_TWO`,`COLUMN_THREE`)VALUES(mCOLUMN_TWO,mCOLUMN_THREE);
        --     /* Code the logic here */
        
        -- END LOOP LOOP_1;
        
    
        /* Get Product Info*/
        SELECT product_branch_id, product_user_id, 
            product_asset_fixed_account_id, product_asset_cost_account_id,
            product_asset_depreciation_account_id, product_asset_accumulated_depreciation_account_id,
            product_asset_acquisition_value, product_asset_acquisition_date, product_asset_dep_method, product_asset_dep_period, product_asset_dep_percentage,
            product_asset_dep_flag, product_asset_accumulated_depreciation_date, product_asset_accumulated_depreciation_value,
            product_asset_code, product_asset_name
        INTO mBRANCH_ID, mUSER_ID, mACCOUNT_FIXED, mACCOUNT_COST, mACCOUNT_DEPRECIATION, mACCOUNT_ACCUMULATED,
        mASSET_ACQUISITION_VALUE, mASSET_ACQUISITION_DATE, mASSET_DEP_METHOD, mASSET_DEP_PERIOD, mASSET_DEP_PERCENTAGE, 
        mASSET_DEP_FLAG, mASSET_ACCUMULATED_DATE, mASSET_ACCUMULATED_VALUE,
        mASSET_CODE, mASSET_NAME
        FROM products 
        WHERE product_id=vPRODUCT_ID;

        /* Get Branch if Have Journal Config */
        SELECT branch_transaction_with_journal INTO mBRANCH_WITH_JOURNAL FROM branchs WHERE branch_id=mBRANCH_ID;

        IF mBRANCH_WITH_JOURNAL = 'Yes' THEN
            IF vACTION = 'CREATE' THEN
                IF mASSET_DEP_FLAG = 0 THEN -- NonDepresiasi
                    
                    -- Insert Journal
                        SET @journal_type := 16;
                        SET @journal_number := 'Pembelian-Asset-0001';
                        SET @journal_session := fn_create_session();
                        INSERT INTO `journals` (`journal_number`,`journal_type`,`journal_date`,`journal_total`,`journal_date_created`,
                        `journal_user_id`,`journal_flag`,`journal_session`,`journal_asset_id`,`journal_branch_id`) 
                        VALUES (@journal_number,@journal_type,CONCAT(mASSET_ACQUISITION_DATE,' 07:00:00'),mASSET_ACQUISITION_VALUE,NOW(),
                        mUSER_ID,1,@journal_session,vPRODUCT_ID,mBRANCH_ID);

                        SELECT LAST_INSERT_ID() INTO mJOURNAL_ID;

                    -- Insert Journal Item (Debit & Credit)
                        -- Fixed Account ID
                        INSERT INTO `journals_items` (
                            `journal_item_journal_id`,`journal_item_group_session`,`journal_item_branch_id`,
                            `journal_item_account_id`,`journal_item_date`,
                            `journal_item_type`,`journal_item_type_name`,`journal_item_debit`,`journal_item_credit`,
                            `journal_item_user_id`, `journal_item_note`,
                            `journal_item_date_created`,`journal_item_flag`,`journal_item_position`,`journal_item_journal_session`,`journal_item_session`
                        ) VALUES (
                            mJOURNAL_ID, @journal_session, mBRANCH_ID,
                            mACCOUNT_FIXED, CONCAT(mASSET_ACQUISITION_DATE,' 07:00:00'),
                            @journal_type, 'Beli Asset', mASSET_ACQUISITION_VALUE,'0.00',
                            mUSER_ID, CONCAT('(',mASSET_CODE,') ',mASSET_NAME),
                            NOW(),1,1,@journal_session,fn_create_session()
                        );

                        -- Cost Account ID
                        INSERT INTO `journals_items` (
                            `journal_item_journal_id`,`journal_item_group_session`,`journal_item_branch_id`,
                            `journal_item_account_id`,`journal_item_date`,
                            `journal_item_type`,`journal_item_type_name`,`journal_item_debit`,`journal_item_credit`,
                            `journal_item_user_id`, `journal_item_note`,
                            `journal_item_date_created`,`journal_item_flag`,`journal_item_position`,`journal_item_journal_session`,`journal_item_session`
                        ) VALUES (
                            mJOURNAL_ID, @journal_session, mBRANCH_ID,
                            mACCOUNT_COST, CONCAT(mASSET_ACQUISITION_DATE,' 07:00:00'),
                            @journal_type, 'Beli Asset', '0.00', mASSET_ACQUISITION_VALUE,
                            mUSER_ID, CONCAT('(',mASSET_CODE,') ',mASSET_NAME),
                            NOW(), 1, 2, @journal_session, fn_create_session()
                        );

                    SET @SQL_Text := CONCAT('SELECT CONCAT(1) AS status, CONCAT("-") AS message, CONCAT("Operator CREATE NONDEPRESIASI Asset to Journal") AS operator, CONCAT("',vPRODUCT_ID,'") AS product_id, CONCAT("0") AS result');
                ELSEIF mASSET_DEP_FLAG = 1 THEN -- Depresiasi

                    /* Ekuitas Saldo Awal */ 
                    SELECT account_map_account_id INTO mACCOUNT_EQUITY 
                    FROM accounts_maps WHERE account_map_branch_id=mBRANCH_ID AND account_map_for_transaction=10 AND account_map_type=1;

                    -- Insert Journal
                        SET @journal_type := 16;
                        SET @journal_number := 'Pembelian-Asset-0002';
                        SET @journal_session := fn_create_session();
                        INSERT INTO `journals` (`journal_number`,`journal_type`,`journal_date`,`journal_total`,`journal_date_created`,
                        `journal_user_id`,`journal_flag`,`journal_session`,`journal_asset_id`,`journal_branch_id`) 
                        VALUES (@journal_number,@journal_type,CONCAT(mASSET_ACQUISITION_DATE,' 07:00:00'),mASSET_ACQUISITION_VALUE,NOW(),
                        mUSER_ID,1,@journal_session,vPRODUCT_ID,mBRANCH_ID);

                        SELECT LAST_INSERT_ID() INTO mJOURNAL_ID;

                    -- Standard Insert Journal Item (Debit & Credit)
                        -- Fixed Account ID
                        INSERT INTO `journals_items` (
                            `journal_item_journal_id`,`journal_item_group_session`,`journal_item_branch_id`,
                            `journal_item_account_id`,`journal_item_date`,
                            `journal_item_type`,`journal_item_type_name`,`journal_item_debit`,`journal_item_credit`,
                            `journal_item_user_id`,`journal_item_note`,
                            `journal_item_date_created`,`journal_item_flag`,`journal_item_position`,`journal_item_journal_session`,`journal_item_session`
                        ) VALUES (
                            mJOURNAL_ID, @journal_session, mBRANCH_ID,
                            mACCOUNT_FIXED, CONCAT(mASSET_ACQUISITION_DATE,' 07:00:00'),
                            @journal_type, 'Beli Asset', mASSET_ACQUISITION_VALUE,'0.00',
                            mUSER_ID, CONCAT('(',mASSET_CODE,') ',mASSET_NAME),
                            NOW(),1,1,@journal_session,fn_create_session()
                        );

                        -- Cost Account ID
                        INSERT INTO `journals_items` (
                            `journal_item_journal_id`,`journal_item_group_session`,`journal_item_branch_id`,
                            `journal_item_account_id`,`journal_item_date`,
                            `journal_item_type`,`journal_item_type_name`,`journal_item_debit`,`journal_item_credit`,
                            `journal_item_user_id`,`journal_item_note`,
                            `journal_item_date_created`,`journal_item_flag`,`journal_item_position`,`journal_item_journal_session`,`journal_item_session`
                        ) VALUES (
                            mJOURNAL_ID, @journal_session, mBRANCH_ID,
                            mACCOUNT_COST, CONCAT(mASSET_ACQUISITION_DATE,' 07:00:00'),
                            @journal_type, 'Beli Asset', '0.00', mASSET_ACQUISITION_VALUE,
                            mUSER_ID, CONCAT('(',mASSET_CODE,') ',mASSET_NAME),
                            NOW(), 1, 2, @journal_session, fn_create_session()
                        );

                    -- Insert Journal For Depreciation
                        SET @journal_type := 17;
                        SET @journal_number := 'Penyusutan-Asset-0001';
                        SET @journal_session := fn_create_session();
                        INSERT INTO `journals` (`journal_number`,`journal_type`,`journal_date`,`journal_total`,`journal_date_created`,
                        `journal_user_id`,`journal_flag`,`journal_session`,`journal_asset_id`,`journal_branch_id`,`journal_id_source`) 
                        VALUES (@journal_number,@journal_type,CONCAT(mASSET_ACCUMULATED_DATE,' 07:00:00'),mASSET_ACCUMULATED_VALUE,NOW(),
                        mUSER_ID,1,@journal_session,vPRODUCT_ID,mBRANCH_ID,mJOURNAL_ID);

                        SELECT LAST_INSERT_ID() INTO mJOURNAL_ID_2;

                    -- Depresiasi Insert Journal Item (Debit & Credit)
                        -- Ekuitas, Depretiation Account ID
                        INSERT INTO `journals_items` (
                            `journal_item_journal_id`,`journal_item_group_session`,`journal_item_branch_id`,
                            `journal_item_account_id`,`journal_item_date`,
                            `journal_item_type`,`journal_item_type_name`,`journal_item_debit`,`journal_item_credit`,
                            `journal_item_user_id`,`journal_item_note`,
                            `journal_item_date_created`,`journal_item_flag`,`journal_item_position`,`journal_item_journal_session`,`journal_item_session`
                        ) VALUES (
                            mJOURNAL_ID_2, @journal_session, mBRANCH_ID,
                            mACCOUNT_EQUITY, CONCAT(mASSET_ACCUMULATED_DATE,' 07:00:00'),
                            @journal_type, 'Penyusutan Asset', mASSET_ACCUMULATED_VALUE,'0.00',
                            mUSER_ID, CONCAT('(',mASSET_CODE,') ',mASSET_NAME),
                            NOW(),1,1,@journal_session,fn_create_session()
                        );

                        -- Accumulated Account ID
                        INSERT INTO `journals_items` (
                            `journal_item_journal_id`,`journal_item_group_session`,`journal_item_branch_id`,
                            `journal_item_account_id`,`journal_item_date`,
                            `journal_item_type`,`journal_item_type_name`,`journal_item_debit`,`journal_item_credit`,
                            `journal_item_user_id`,`journal_item_note`,
                            `journal_item_date_created`,`journal_item_flag`,`journal_item_position`,`journal_item_journal_session`,`journal_item_session`
                        ) VALUES (
                            mJOURNAL_ID_2, @journal_session, mBRANCH_ID,
                            mACCOUNT_ACCUMULATED, CONCAT(mASSET_ACCUMULATED_DATE,' 07:00:00'),
                            @journal_type, 'Penyusutan Asset', '0.00', mASSET_ACCUMULATED_VALUE,
                            mUSER_ID, CONCAT('(',mASSET_CODE,') ',mASSET_NAME),
                            NOW(), 1, 2, @journal_session, fn_create_session()
                        );

                    SET @SQL_Text := CONCAT('SELECT CONCAT(1) AS status, CONCAT("-") AS message, CONCAT("Operator CREATE DEPRESIASI Asset to Journal") AS operator, CONCAT("',vPRODUCT_ID,'") AS product_id, CONCAT("0") AS result');
                END IF;
            ELSEIF vACTION = 'UPDATE' THEN
                SET @SQL_Text := CONCAT('SELECT CONCAT(1) AS status, CONCAT("-") AS message, CONCAT("Operator UPDATE Asset to Journal") AS operator, CONCAT("',vPRODUCT_ID,'") AS product_id, CONCAT("0") AS result');     
            ELSEIF vACTION = 'SELL' THEN
                SET @SQL_Text := CONCAT('SELECT CONCAT(1) AS status, CONCAT("-") AS message, CONCAT("Operator SELL Asset to Journal") AS operator, CONCAT("',vPRODUCT_ID,'") AS product_id, CONCAT("0") AS result');
            ELSEIF vACTION = 'DELETE' THEN
                SET @SQL_Text := CONCAT('SELECT CONCAT(1) AS status, CONCAT("-") AS message, CONCAT("Operator DELETE Asset to Journal") AS operator, CONCAT("',vPRODUCT_ID,'") AS product_id, CONCAT("0") AS result');
            END IF; 
        END IF;

        /*
        SET mFOUND_ROW = FOUND_ROWS();
        IF mFOUND_ROW > 0 THEN
        END IF;
        */

    END BLOCK_A;

    /* Prepare Query Statement */
    PREPARE stmt FROM @SQL_Text;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;  

END$$
DELIMITER ;

-- ALTER TABLE `journals` ADD COLUMN journal_id_source BIGINT(255) COMMENT 'Hanya Asset yg menggunakan' AFTER journal_label;
-- ALTER TABLE `journals` ADD COLUMN journal_asset_id BIGINT(255) COMMENT 'Relasi products = product_type=3' AFTER journal_id_source;
