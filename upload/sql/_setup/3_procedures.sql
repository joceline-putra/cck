DELIMITER $$
DROP PROCEDURE IF EXISTS `sp_chart` $$
CREATE PROCEDURE `sp_chart`(IN vACTION VARCHAR(255),IN vBRANCH BIGINT(255),IN vDIFF INT(5))
    BEGIN
        DROP TEMPORARY TABLE IF EXISTS `temp`;
        CREATE TEMPORARY TABLE `temp`(
            `temp_id` BIGINT(50) NOT NULL AUTO_INCREMENT PRIMARY KEY,
            `temp_year_month` VARCHAR(255),
            `temp_label` VARCHAR(255),
            `temp_name` VARCHAR(255),
            -- `temp_total_buy` DOUBLE(18,2) DEFAULT 0,
            -- `temp_total_sell` DOUBLE(18,2) DEFAULT 0,
            `temp_flag` INT(5) DEFAULT 0,
            `temp_status` INT(5) DEFAULT 0,
            `temp_total_data` INT(255) DEFAULT 0,
            `temp_total_buy` DOUBLE(18,2) DEFAULT '0.00',
            `temp_total_sell` DOUBLE(18,2) DEFAULT '0.00',
            `temp_total_debit` DOUBLE(18,2) DEFAULT '0.00',
            `temp_total_credit` DOUBLE(18,2) DEFAULT '0.00',
            `temp_total_balance`   DOUBLE(18,2) DEFAULT '0.00',      
            INDEX `ID`(`temp_id`) USING BTREE
        ) ENGINE=MEMORY;
            
        IF vACTION = 'buy-sell' THEN
            BLOCK_A:BEGIN
                DECLARE mLABEL VARCHAR(255);
                DECLARE mNAME VARCHAR(255);
                DECLARE mCOUNT INT(5);
                SET mCOUNT = 0;
                WHILE mCOUNT < 5 DO
                    SELECT DATE_FORMAT(DATE_ADD(NOW(), INTERVAL -mCOUNT MONTH), '%Y-%m'), DATE_FORMAT(DATE_ADD(NOW(), INTERVAL -mCOUNT MONTH), '%M %Y') INTO mLABEL, mNAME;
                    INSERT INTO temp(`temp_year_month`,`temp_label`)VALUES(mLABEL,mNAME);
                    SET mCOUNT = mCOUNT + 1;
                END WHILE;
            END BLOCK_A;
        
            BLOCK_B:BEGIN
                
                UPDATE temp JOIN (
                    SELECT DATE_FORMAT(trans_date,'%Y-%m') AS years_month, 
                    IFNULL(SUM(trans_total),0) AS total
                    FROM trans 
                    WHERE trans_type=1
                    GROUP BY EXTRACT(YEAR_MONTH FROM `trans_date`)
                ) AS trans ON temp_label=trans.years_month
                SET temp_total_buy=trans.total
                WHERE temp.temp_year_month=trans.years_month;
            END BLOCK_B;

            BLOCK_C:BEGIN
                UPDATE temp JOIN (
                    SELECT DATE_FORMAT(trans_date,'%Y-%m') AS years_month, 
                    IFNULL(SUM(trans_total),0) AS total
                    FROM trans 
                    WHERE trans_type=2
                    GROUP BY EXTRACT(YEAR_MONTH FROM `trans_date`)
                ) AS trans ON temp_label=trans.years_month
                SET temp_total_sell=trans.total
                WHERE temp.temp_year_month=trans.years_month;

            END BLOCK_C;        
        END IF;
        
        SELECT * FROM temp;
    END$$
DELIMITER ;

DELIMITER $$
DROP PROCEDURE IF EXISTS `sp_chart_buy_sell` $$
CREATE PROCEDURE `sp_chart_buy_sell`(IN vBRANCH BIGINT(255))
    BEGIN
        DROP TEMPORARY TABLE IF EXISTS `temp`;
        CREATE TEMPORARY TABLE `temp`(
            `temp_id` BIGINT(50) NOT NULL AUTO_INCREMENT PRIMARY KEY,
            `temp_label` VARCHAR(255),
            `temp_name` VARCHAR(255),
            `temp_total_buy` DOUBLE(18,2) DEFAULT 0,
            `temp_total_sell` DOUBLE(18,2) DEFAULT 0,
            `temp_total_income` DOUBLE(18,2) DEFAULT 0,
            `temp_total_expense` DOUBLE(18,2) DEFAULT 0,            
            `temp_flag` INT(5) DEFAULT 0,
            `temp_status` INT(5) DEFAULT 0,
            `temp_message` VARCHAR(255) DEFAULT 'No Data',
            `temp_total_data` INT(255) DEFAULT 0,
            INDEX `ID`(`temp_id`) USING BTREE
        ) ENGINE=MEMORY;

        /* Make Label Month-Year */    
        BLOCK_A:BEGIN

            DECLARE mLABEL VARCHAR(255);
            DECLARE mNAME VARCHAR(255);
            DECLARE mFINISHED INTEGER;
            DECLARE mACTION_CURSOR CURSOR FOR
                SELECT DATE_FORMAT(order_date,'%Y-%m'), DATE_FORMAT(order_date,'%b') 
                FROM orders 
                WHERE order_date > DATE_SUB(NOW(), INTERVAL 5 MONTH)
                GROUP BY MONTH(order_date) ORDER BY order_date ASC;
            DECLARE CONTINUE HANDLER FOR NOT FOUND SET mFINISHED=1;
            OPEN mACTION_CURSOR;

            LOOP_1: LOOP
                FETCH mACTION_CURSOR INTO mLABEL, mNAME;
                IF mFINISHED = 1 THEN
                    LEAVE LOOP_1;
                END IF;       
                INSERT INTO temp(`temp_label`,`temp_name`)VALUES(mLABEL,mNAME);
            END LOOP LOOP_1;        
        END BLOCK_A;
        
        BLOCK_A1:BEGIN
            DECLARE mFINISHED INTEGER;
            DECLARE mMOUNTMAX INTEGER;
            DECLARE mLABEL VARCHAR(255);
            DECLARE mNAME VARCHAR(255);            
            SET mMOUNTMAX = 4;
            
            WHILE mMOUNTMAX >= 1 DO
                SELECT DATE_FORMAT(DATE_SUB(NOW(),INTERVAL mMOUNTMAX MONTH),'%Y-%m'), 
                DATE_FORMAT(DATE_SUB(NOW(),INTERVAL mMOUNTMAX MONTH),'%b') INTO mLABEL, mNAME;
                INSERT INTO temp(`temp_label`,`temp_name`)VALUES(mLABEL,mNAME);
                SET mMOUNTMAX = mMOUNTMAX - 1;
            END WHILE;
        END BLOCK_A1;

        /* Trans Type 1 = Pembelian */
        BLOCK_B:BEGIN
            
            UPDATE temp JOIN (
                SELECT DATE_FORMAT(trans_date,'%Y-%m') AS years_month, 
                IFNULL(SUM(trans_total),0) AS total
                FROM trans 
                WHERE trans_type=1 AND CASE WHEN vBRANCH > 0 THEN trans_branch_id=vBRANCH ELSE trans_branch_id > 0 END
                GROUP BY EXTRACT(YEAR_MONTH FROM `trans_date`)
            ) AS trans ON temp_label=trans.years_month
            SET temp_total_buy=trans.total
            WHERE temp.temp_label=trans.years_month;
        END BLOCK_B;

        /* Trans Type 2 = Penjualan */
        BLOCK_C:BEGIN
            -- UPDATE temp JOIN (
            --     SELECT DATE_FORMAT(trans_date,'%Y-%m') AS years_month, 
            --     IFNULL(SUM(trans_total),0) AS total
            --     FROM trans 
            --     WHERE trans_type=2
            --     GROUP BY EXTRACT(YEAR_MONTH FROM `trans_date`)
            -- ) AS trans ON temp_label=trans.years_month
            -- SET temp_total_sell=trans.total
            -- WHERE temp.temp_label=trans.years_month;

            UPDATE temp JOIN (
                SELECT DATE_FORMAT(order_date,'%Y-%m') AS years_month, 
                IFNULL(SUM(order_total),0) AS total
                FROM orders 
                WHERE order_type=222 AND order_paid = 1 AND CASE WHEN vBRANCH > 0 THEN order_branch_id=vBRANCH ELSE order_branch_id>0 END
                GROUP BY EXTRACT(YEAR_MONTH FROM `order_date`)
            ) AS orders ON temp_label=orders.years_month
            SET temp_total_sell=orders.total
            WHERE temp.temp_label=orders.years_month;            

        END BLOCK_C;
        
        /* Journal 2,3 = Income*/
        BLOCK_C:BEGIN
            UPDATE temp JOIN (
                SELECT DATE_FORMAT(journal_item_date,'%Y-%m') AS years_month, 
                IFNULL(SUM(journal_item_debit),0) AS total
                FROM journals_items 
                WHERE journal_item_type IN (2,3) AND CASE WHEN vBRANCH > 0 THEN journal_item_branch_id=vBRANCH ELSE journal_item_branch_id>0 END 
                GROUP BY EXTRACT(YEAR_MONTH FROM `journal_item_date`)
            ) AS journals ON temp_label=journals.years_month
            SET temp_total_income=journals.total
            WHERE temp.temp_label=journals.years_month;
        END BLOCK_C;

        /* Journal 1,4 = Expense*/
        BLOCK_D:BEGIN
            UPDATE temp JOIN (
                SELECT DATE_FORMAT(journal_item_date,'%Y-%m') AS years_month, 
                IFNULL(SUM(journal_item_debit),0) AS total
                FROM journals_items 
                WHERE journal_item_type IN (4) AND CASE WHEN vBRANCH > 0 THEN journal_item_branch_id=vBRANCH ELSE journal_item_branch_id>0 END 
                GROUP BY EXTRACT(YEAR_MONTH FROM `journal_item_date`)
            ) AS journals ON temp_label=journals.years_month
            SET temp_total_expense=journals.total
            WHERE temp.temp_label=journals.years_month;
        END BLOCK_D;

        BLOCK_F:BEGIN
            SELECT COUNT(*) INTO @mTOTAL FROM temp;
            IF @mTOTAL > 0 THEN SET @mSTATUS=1; SET @mMESSAGE='Data found';
            ELSE SET @mSTATUS=0; SET @mMESSAGE='No Data';
            END IF;

            UPDATE temp 
            SET temp_status=@mSTATUS, temp_message=@mMESSAGE, temp_total_data=@mTOTAL;
        END BLOCK_F;
        
        SELECT * FROM temp GROUP BY temp_label ORDER BY temp_label ASC;
    END$$
DELIMITER ;

DELIMITER $$
DROP PROCEDURE IF EXISTS `sp_journal_from_order` $$
CREATE PROCEDURE `sp_journal_from_order` (IN vOPERATION VARCHAR(255), IN vORDER_ID BIGINT(50)) 
BEGIN
    DECLARE mORDER_TYPE INT(5);
    DECLARE mORDER_DATE VARCHAR(255);
    DECLARE mACCOUNT_ORDER INT(50);
    DECLARE mACCOUNT_DOWN_PAYMENT INT(50);
    DECLARE mORDER_BRANCH BIGINT(255);
    DECLARE mORDER_USER BIGINT(255);
    DECLARE mORDER_TOTAL_DPP DOUBLE(18,2) DEFAULT '0.00';
    DECLARE mORDER_TOTAL_DISCOUNT DOUBLE(18,2) DEFAULT '0.00';
    DECLARE mORDER_TOTAL_DP DOUBLE(18,2) DEFAULT '0.00';
    DECLARE mORDER_TOTAL DOUBLE(18,2) DEFAULT '0.00';        
    -- DECLARE mGROUP_SESSION VARCHAR(50);

    SELECT order_branch_id, order_user_id, order_date, order_type, order_with_dp_account, order_total_dpp, order_discount, order_with_dp, order_total 
    INTO mORDER_BRANCH, mORDER_USER, mORDER_DATE, mORDER_TYPE, mACCOUNT_ORDER, mORDER_TOTAL_DPP, mORDER_TOTAL_DISCOUNT, mORDER_TOTAL_DP, mORDER_TOTAL
    FROM orders WHERE order_id=vORDER_ID;

    -- SET mGROUP_SESSION = MID(fn_create_session(),5,10);
    
    IF mORDER_TOTAL_DP > 0 THEN
        IF vOPERATION = "create" THEN
            IF mORDER_TYPE = 1 THEN /* From Purchase Order */
                SET mORDER_TYPE = 6;
                SELECT account_map_account_id INTO mACCOUNT_DOWN_PAYMENT
                FROM accounts_maps 
                WHERE account_map_branch_id=mORDER_BRANCH 
                AND account_map_for_transaction=1 
                AND account_map_type=3; /* Biaya Dibayar Dimuka*/

                /*Debit = Biaya Dibayar Dimuka */
                INSERT INTO `journals_items` (`journal_item_branch_id`,`journal_item_account_id`,`journal_item_order_id`,`journal_item_date`,`journal_item_type`,`journal_item_debit`,`journal_item_credit`,`journal_item_user_id`,`journal_item_date_created`,`journal_item_flag`,`journal_item_position`)
                VALUES(mORDER_BRANCH, mACCOUNT_DOWN_PAYMENT, vORDER_ID, mORDER_DATE, mORDER_TYPE, mORDER_TOTAL_DP, '0.00', mORDER_USER,NOW(),1,2);
                
                /*Credit = Pendapatan diterima Dimuka */
                INSERT INTO `journals_items` (`journal_item_branch_id`,`journal_item_account_id`,`journal_item_order_id`,`journal_item_date`,`journal_item_type`,`journal_item_debit`,`journal_item_credit`,`journal_item_user_id`,`journal_item_date_created`,`journal_item_flag`,`journal_item_position`)
                VALUES(mORDER_BRANCH, mACCOUNT_ORDER, vORDER_ID, mORDER_DATE, mORDER_TYPE, '0.00', mORDER_TOTAL_DP, mORDER_USER,NOW(),1,2);
            ELSEIF mORDER_TYPE = 2 THEN /* From Sales Order */
                SET mORDER_TYPE = 7;
                SELECT account_map_account_id INTO mACCOUNT_DOWN_PAYMENT
                FROM accounts_maps 
                WHERE account_map_branch_id=mORDER_BRANCH 
                AND account_map_for_transaction=2 
                AND account_map_type=3; /* Pendapatan Diterima Dimuka*/   

                /*Debit = Kas / Bank */
                INSERT INTO `journals_items` (`journal_item_branch_id`,`journal_item_account_id`,`journal_item_order_id`,`journal_item_date`,`journal_item_type`,`journal_item_debit`,`journal_item_credit`,`journal_item_user_id`,`journal_item_date_created`,`journal_item_flag`,`journal_item_position`)
                VALUES(mORDER_BRANCH, mACCOUNT_ORDER, vORDER_ID, mORDER_DATE, mORDER_TYPE, mORDER_TOTAL_DP, '0.00', mORDER_USER,NOW(),1,2);
                
                /*Credit = Pendapatan Diterima Dimuka */
                INSERT INTO `journals_items` (`journal_item_branch_id`,`journal_item_account_id`,`journal_item_order_id`,`journal_item_date`,`journal_item_type`,`journal_item_debit`,`journal_item_credit`,`journal_item_user_id`,`journal_item_date_created`,`journal_item_flag`,`journal_item_position`)
                VALUES(mORDER_BRANCH, mACCOUNT_DOWN_PAYMENT, vORDER_ID, mORDER_DATE, mORDER_TYPE, '0.00', mORDER_TOTAL_DP, mORDER_USER,NOW(),1,2);

            END IF;        
            SET @mMESSAGE = CONCAT('Create For Order ',vORDER_ID,' And Type ',mORDER_TYPE);
        ELSEIF vOPERATION = "update" THEN 

            DELETE FROM journals_items 
            WHERE journal_item_order_id=vORDER_ID;

            IF mORDER_TYPE = 1 THEN /* From Purchase Order */
                SET mORDER_TYPE = 6;
                SELECT account_map_account_id INTO mACCOUNT_DOWN_PAYMENT
                FROM accounts_maps 
                WHERE account_map_branch_id=mORDER_BRANCH 
                AND account_map_for_transaction=1 
                AND account_map_type=3; /* Biaya Dibayar Dimuka*/
                
                /*Debit = Biaya Dibayar Dimuka */
                INSERT INTO `journals_items` (`journal_item_branch_id`,`journal_item_account_id`,`journal_item_order_id`,`journal_item_date`,`journal_item_type`,`journal_item_debit`,`journal_item_credit`,`journal_item_user_id`,`journal_item_date_created`,`journal_item_flag`,`journal_item_position`)
                VALUES(mORDER_BRANCH, mACCOUNT_DOWN_PAYMENT, vORDER_ID, mORDER_DATE, mORDER_TYPE, mORDER_TOTAL_DP, '0.00', mORDER_USER,NOW(),1,2);
                
                /*Credit = Pendapatan diterima Dimuka */
                INSERT INTO `journals_items` (`journal_item_branch_id`,`journal_item_account_id`,`journal_item_order_id`,`journal_item_date`,`journal_item_type`,`journal_item_debit`,`journal_item_credit`,`journal_item_user_id`,`journal_item_date_created`,`journal_item_flag`,`journal_item_position`)
                VALUES(mORDER_BRANCH, mACCOUNT_ORDER, vORDER_ID, mORDER_DATE, mORDER_TYPE, '0.00', mORDER_TOTAL_DP, mORDER_USER,NOW(),1,2);

            ELSEIF mORDER_TYPE = 2 THEN /* From Sales Order */
                SET mORDER_TYPE = 7;
                SELECT account_map_account_id INTO mACCOUNT_DOWN_PAYMENT
                FROM accounts_maps 
                WHERE account_map_branch_id=mORDER_BRANCH 
                AND account_map_for_transaction=2 
                AND account_map_type=3; /* Pendapatan Diterima Dimuka*/

                /*Debit = Kas / Bank */
                INSERT INTO `journals_items` (`journal_item_branch_id`,`journal_item_account_id`,`journal_item_order_id`,`journal_item_date`,`journal_item_type`,`journal_item_debit`,`journal_item_credit`,`journal_item_user_id`,`journal_item_date_created`,`journal_item_flag`,`journal_item_position`)
                VALUES(mORDER_BRANCH, mACCOUNT_ORDER, vORDER_ID, mORDER_DATE, mORDER_TYPE, mORDER_TOTAL_DP, '0.00', mORDER_USER,NOW(),1,2);
                
                /*Credit = Pendapatan Diterima Dimuka */
                INSERT INTO `journals_items` (`journal_item_branch_id`,`journal_item_account_id`,`journal_item_order_id`,`journal_item_date`,`journal_item_type`,`journal_item_debit`,`journal_item_credit`,`journal_item_user_id`,`journal_item_date_created`,`journal_item_flag`,`journal_item_position`)
                VALUES(mORDER_BRANCH, mACCOUNT_DOWN_PAYMENT, vORDER_ID, mORDER_DATE, mORDER_TYPE, '0.00', mORDER_TOTAL_DP, mORDER_USER,NOW(),1,2);

            END IF;

            SET @mMESSAGE = CONCAT('Update For Order ',vORDER_ID,' And Type ',mORDER_TYPE);  
        ELSEIF vOPERATION = "delete" THEN

            IF mORDER_TYPE = 1 THEN /* From Purchase Order */
                SET mORDER_TYPE = 6; 
            ELSEIF mORDER_TYPE = 2 THEN /* From Sales Order */
                SET mORDER_TYPE = 7;
            END IF;

            DELETE FROM journals_items 
            WHERE journal_item_order_id=vORDER_ID
            AND journal_item_type=mORDER_TYPE;
            SET @mMESSAGE = CONCAT('Delete For Order ',vORDER_ID,' And Type ',mORDER_TYPE);                
        END IF;
    END IF;

    SET @SQL_Text := CONCAT('SELECT CONCAT(1) AS status, 
        CONCAT("',@mMESSAGE,'") AS message, 
        CONCAT("',vOPERATION,'") AS operator, 
        CONCAT("',vORDER_ID,'") AS order_id, 
        CONCAT("0") AS result');       
            
    /* Prepare Query Statement */
    PREPARE stmt FROM @SQL_Text;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
END$$
DELIMITER ;

DELIMITER $$
DROP PROCEDURE IF EXISTS `sp_journal_from_transaction`$$
CREATE PROCEDURE `sp_journal_from_transaction`(IN vACTION VARCHAR(50), IN vTRANS_ID BIGINT(50))
    BEGIN
        SET max_sp_recursion_depth=255;
        BLOCK_A:BEGIN
            DECLARE mCONTACT_ID BIGINT(50); 
                DECLARE mBRANCH_ID BIGINT(50);
                DECLARE mBRANCH_WITH_JOURNAL VARCHAR(255);
                DECLARE mUSER_ID BIGINT(50);
                DECLARE mTRANS_TYPE INT(5);
                DECLARE mTRANS_PPN INT(5);
                DECLARE mTRANS_TOTAL DOUBLE(20,2) DEFAULT 0;
                DECLARE mTRANS_TOTAL_INCOME DOUBLE(20,2) DEFAULT 0;
                DECLARE mTRANS_TOTAL_OUTCOME DOUBLE(20,2) DEFAULT 0;
                DECLARE mFOUND_PPN INT(5);
                DECLARE mFOUND_DISCOUNT DOUBLE(18,2);
                
                DECLARE mTRANS_ITEM_SELL_TOTAL DOUBLE(20,2) DEFAULT 0;
                DECLARE mTRANS_ITEM_SELL_VALUE_BARANG DOUBLE(20,2) DEFAULT 0;
                DECLARE mTRANS_ITEM_SELL_VALUE_JASA DOUBLE(20,2) DEFAULT 0;

                DECLARE mTRANS_ITEM_TOTAL DOUBLE(20,2) DEFAULT 0;
                DECLARE mTRANS_ITEM_SUBTOTAL DOUBLE(20,2) DEFAULT 0;

                DECLARE mTRANS_ITEM_TOTAL_BARANG DOUBLE(20,2) DEFAULT 0;
                DECLARE mTRANS_ITEM_SUBTOTAL_BARANG DOUBLE(20,2) DEFAULT 0;

                DECLARE mTRANS_ITEM_TOTAL_JASA DOUBLE(20,2) DEFAULT 0;
                DECLARE mTRANS_ITEM_SUBTOTAL_JASA DOUBLE(20,2) DEFAULT 0;

                DECLARE mTRANS_ITEM_DISCOUNT DOUBLE(18,2) DEFAULT 0;

                DECLARE mJOURNAL_ITEM_TYPE INT(5);
                DECLARE mJOURNAL_ITEM_DATE DATETIME;
                
                DECLARE mACCOUNT_INVENTORY BIGINT(50);
                DECLARE mACCOUNT_INVENTORY_ADJUSMENT BIGINT(50);
                DECLARE mACCOUNT_SERVICE BIGINT(50);

                DECLARE mACCOUNT_PAYABLE BIGINT(50);
                DECLARE mACCOUNT_RECEIVABLE BIGINT(50);     
                DECLARE mACCOUNT_PPN_IN INT(5);
                DECLARE mACCOUNT_PPN_OUT INT(5);
                DECLARE mACCOUNT_SALE_INCOME BIGINT(50);
                DECLARE mACCOUNT_SALE_COGS BIGINT(50);
                DECLARE mACCOUNT_RETURN INT(5);
                DECLARE mACCOUNT_DISCOUNT INT(255);

                DECLARE mFINISHED INTEGER;  
                DECLARE mFINISHED_PPN INTEGER;  
                DECLARE mFINISHED_ACCOUNT_PAYABLE INTEGER;
                DECLARE mMESSAGE VARCHAR(255) DEFAULT 'No Message';
                DECLARE mFOUND_ROWS INTEGER;
            DECLARE mTRANS_SOURCE_ID BIGINT(255);

            /* Transaksi melibatkan Barang */
            DECLARE mACTION_CURSOR_INVENTORY CURSOR FOR /* Total Barang Bersifat Masuk ke program */
                SELECT trans_item_total, product_inventory_account_id 
                FROM trans_items 
                LEFT JOIN products ON trans_item_product_id=product_id
                WHERE trans_item_trans_id=vTRANS_ID AND trans_item_product_type=1;  

            DECLARE mACTION_CURSOR_INVENTORY_IN CURSOR FOR /* Total Barang Bersifat Masuk ke program */
                SELECT IFNULL(trans_item_in_price*trans_item_in_qty,0), product_inventory_account_id, product_buy_account_id 
                FROM trans_items 
                LEFT JOIN products ON trans_item_product_id=product_id
                WHERE trans_item_trans_id=vTRANS_ID AND trans_item_product_type=1;                  

            DECLARE mACTION_CURSOR_INVENTORY_OUT CURSOR FOR /* Total Barang Bersifat Keluar ke program*/
                SELECT trans_item_out_price*trans_item_out_qty, trans_item_sell_price*trans_item_out_qty, product_inventory_account_id, product_buy_account_id, product_sell_account_id 
                FROM trans_items 
                LEFT JOIN products ON trans_item_product_id=product_id 
                WHERE trans_item_trans_id=vTRANS_ID AND trans_item_product_type=1;

            DECLARE mACTION_CURSOR_INVENTORY_PRODUCTION_OUT CURSOR FOR /* Total Barang Produksi Bersifat Keluar ke program*/
                SELECT trans_item_out_price*trans_item_out_qty, product_inventory_account_id, product_buy_account_id, product_sell_account_id
                FROM trans_items 
                LEFT JOIN products ON trans_item_product_id=product_id 
                WHERE trans_item_trans_id=vTRANS_ID AND trans_item_position=2;

            DECLARE mACTION_CURSOR_INVENTORY_PRODUCTION_IN CURSOR FOR /* Total Barang Produksi Bersifat Masuk ke program*/
                SELECT trans_item_in_price*trans_item_in_qty, product_inventory_account_id, product_buy_account_id, product_sell_account_id
                FROM trans_items 
                LEFT JOIN products ON trans_item_product_id=product_id 
                WHERE trans_item_trans_id=vTRANS_ID AND trans_item_position=1;

            /* Transaksi melibatkan Jasa */
            DECLARE mACTION_CURSOR_JASA_OUT CURSOR FOR /* Total Jasa Bersifat Keluar ke program */
                SELECT trans_item_sell_price*trans_item_out_qty, product_sell_account_id
                FROM trans_items LEFT JOIN products ON trans_item_product_id=product_id
                WHERE trans_item_trans_id=vTRANS_ID AND trans_item_product_type=2;     

            -- DECLARE mACTION_CURSOR_JASA_OUT CURSOR FOR /* Total Barang Bersifat Keluar ke program*/
            --  SELECT trans_item_out_price*trans_item_out_qty, product_sell_account_id  
            --  FROM trans_items 
            --  LEFT JOIN products ON trans_item_product_id=product_id 
            --  WHERE trans_item_trans_id=vTRANS_ID AND trans_item_product_type=2;

            /*
                DECLARE mACTION_CURSOR_PPN CURSOR FOR 
                SELECT trans_item_total FROM trans_items WHERE trans_item_trans_id=vTRANS_ID AND trans_item_ppn=1;
            */
            DECLARE CONTINUE HANDLER FOR NOT FOUND SET mFINISHED=1;                     

            /* Get Transaction Info*/
            SELECT trans_date, trans_branch_id, trans_user_id, trans_type, trans_contact_id, trans_id_source, trans_discount 
            INTO mJOURNAL_ITEM_DATE, mBRANCH_ID, mUSER_ID, mTRANS_TYPE, mCONTACT_ID, mTRANS_SOURCE_ID, mFOUND_DISCOUNT
            FROM trans WHERE trans_id=vTRANS_ID;
                            
            /* Get Branch if Have Journal Config */
            SELECT branch_transaction_with_journal INTO mBRANCH_WITH_JOURNAL FROM branchs WHERE branch_id=mBRANCH_ID;

            IF mBRANCH_WITH_JOURNAL = 'Yes' THEN
                /*
                    1= Account Payable
                    2= Account Receivable
                    //TRANS
                    1=Pembelian
                    2=Penjualan
                    3=ReturPembelian
                    4=ReturPenjualan
                    5=MutasiStock
                        6=StokOpnamePlus
                        7=StokOpnameMinus
                    8=Production            
                */
                IF mTRANS_TYPE = 1 THEN SET mTRANS_TYPE=10;  /* Pembelian */
                ELSEIF mTRANS_TYPE = 2 THEN SET mTRANS_TYPE=11; /* Penjualan */
                ELSEIF mTRANS_TYPE = 3 THEN SET mTRANS_TYPE=22; /* Retur Pembelian */
                ELSEIF mTRANS_TYPE = 4 THEN SET mTRANS_TYPE=23; /* Retur Penjualan */           
                ELSEIF mTRANS_TYPE = 6 THEN SET mTRANS_TYPE=14; /* Stock Opname */
                ELSEIF mTRANS_TYPE = 8 THEN SET mTRANS_TYPE=19; /* Produksi*/
                ELSEIF mTRANS_TYPE = 9 THEN SET mTRANS_TYPE=20; /* Pemakaian Barang */
                ELSEIF mTRANS_TYPE = 10 THEN SET mTRANS_TYPE=21; /* Pemasukan Barang */
                ELSE SET mTRANS_TYPE = 0;
                END IF;

                /*Check Count Trans With Ppn and Trans Total */
                SELECT COUNT(trans_item_id) INTO mFOUND_PPN FROM trans_items WHERE trans_item_trans_id=vTRANS_ID AND trans_item_ppn=1;
                SELECT IFNULL(trans_total,0) INTO mTRANS_TOTAL FROM trans WHERE trans_id=vTRANS_ID;
                
                IF vACTION = 'CREATE' OR vACTION = 'UPDATE' THEN
                    IF mTRANS_TYPE = 10 THEN /* Pembelian */
                        /* Delete Current Data From Journal Trans ID */
                        DELETE FROM journals_items WHERE journal_item_trans_id=vTRANS_ID AND journal_item_type=mTRANS_TYPE;                 
                        /* Get Account Map */
                        /* Hutang Usaha From Kontak */
                        SELECT contact_account_payable_account_id INTO mACCOUNT_PAYABLE FROM contacts WHERE contact_id=mCONTACT_ID;
                        /* PPN Masukan */ 
                        SELECT account_map_account_id INTO mACCOUNT_PPN_IN FROM accounts_maps WHERE account_map_branch_id=mBRANCH_ID AND account_map_for_transaction=1 AND account_map_type=2;              
                        
                        /* Persediaan */
                        OPEN mACTION_CURSOR_INVENTORY;
                        LOOP_INVENTORY:LOOP
                            FETCH mACTION_CURSOR_INVENTORY INTO mTRANS_ITEM_TOTAL_BARANG, mACCOUNT_INVENTORY;
                            IF mFINISHED = 1 THEN
                                LEAVE LOOP_INVENTORY;
                            END IF;
                            SET mTRANS_ITEM_SUBTOTAL_BARANG = mTRANS_ITEM_SUBTOTAL_BARANG + mTRANS_ITEM_TOTAL_BARANG;
                            /* Persedian Barang Bertambah */
                            INSERT INTO journals_items (`journal_item_type`,`journal_item_trans_id`,`journal_item_date`,`journal_item_account_id`,
                            `journal_item_debit`,`journal_item_branch_id`,`journal_item_user_id`,`journal_item_date_created`,`journal_item_date_updated`,
                            `journal_item_flag`)
                            VALUES(mTRANS_TYPE, vTRANS_ID, mJOURNAL_ITEM_DATE, mACCOUNT_INVENTORY,
                            mTRANS_ITEM_TOTAL_BARANG, mBRANCH_ID, mUSER_ID, CURDATE(), CURDATE(),1);
                        END LOOP LOOP_INVENTORY;

                        /* Jasa */
                        SET mFINISHED = 0;
                        SELECT trans_item_total, product_buy_account_id INTO mTRANS_ITEM_TOTAL_JASA, mACCOUNT_SERVICE
                        FROM trans_items 
                        LEFT JOIN products ON trans_item_product_id=product_id
                        WHERE trans_item_trans_id=vTRANS_ID AND trans_item_product_type=2;                  

                        WHILE mFINISHED < FOUND_ROWS() DO
                            SET mTRANS_ITEM_SUBTOTAL_JASA = mTRANS_ITEM_SUBTOTAL_JASA + mTRANS_ITEM_TOTAL_JASA;
                            /* Jasa Bertambah */
                            INSERT INTO journals_items (`journal_item_type`,`journal_item_trans_id`,`journal_item_date`,`journal_item_account_id`,
                            `journal_item_debit`,`journal_item_branch_id`,`journal_item_user_id`,`journal_item_date_created`,`journal_item_date_updated`,
                            `journal_item_flag`)
                            VALUES(mTRANS_TYPE, vTRANS_ID, mJOURNAL_ITEM_DATE, mACCOUNT_SERVICE,
                            mTRANS_ITEM_TOTAL_JASA, mBRANCH_ID, mUSER_ID, CURDATE(), CURDATE(),1);
                            SET mFINISHED = mFINISHED + 1;
                        END WHILE;
                
                    
                        SET mTRANS_ITEM_SUBTOTAL = mTRANS_ITEM_SUBTOTAL_BARANG + mTRANS_ITEM_SUBTOTAL_JASA;
                    
                        /* Ppn Masukan & Hutang Usaha */            
                        IF mFOUND_PPN > 0 THEN
                            /* If Has PPN */
                            SELECT SUM(trans_item_in_price * trans_item_in_qty * (trans_item_ppn_value / 100)) INTO @total_ppn FROM trans_items 
                            WHERE trans_item_trans_id=vTRANS_ID AND trans_item_ppn=1;
                            -- SET @total_ppn := @total_ppn * 0.1;
                            SET mTRANS_ITEM_SUBTOTAL := mTRANS_ITEM_SUBTOTAL + @total_ppn;
                            
                            /* Ppn Masukan (DEBIT) */
                            INSERT INTO journals_items (`journal_item_type`,`journal_item_trans_id`,`journal_item_date`,`journal_item_account_id`,
                            `journal_item_debit`,`journal_item_branch_id`,`journal_item_user_id`,`journal_item_date_created`,`journal_item_date_updated`,
                            `journal_item_flag`)
                            VALUES(mTRANS_TYPE, vTRANS_ID, mJOURNAL_ITEM_DATE, mACCOUNT_PPN_IN,
                            @total_ppn, mBRANCH_ID, mUSER_ID, CURDATE(), CURDATE(),1);
                            
                            /* Hutang Usaha (CREDIT) */
                            INSERT INTO journals_items (`journal_item_type`,`journal_item_trans_id`,`journal_item_date`,`journal_item_account_id`,
                            `journal_item_credit`,`journal_item_branch_id`,`journal_item_user_id`,`journal_item_date_created`,`journal_item_date_updated`,
                            `journal_item_flag`)
                            VALUES(mTRANS_TYPE, vTRANS_ID, mJOURNAL_ITEM_DATE, mACCOUNT_PAYABLE,
                            mTRANS_ITEM_SUBTOTAL, mBRANCH_ID, mUSER_ID, CURDATE(), CURDATE(),1);
                        ELSE
                            /* Hutang Usaha (CREDIT) */
                            INSERT INTO journals_items (`journal_item_type`,`journal_item_trans_id`,`journal_item_date`,`journal_item_account_id`,
                            `journal_item_credit`,`journal_item_branch_id`,`journal_item_user_id`,`journal_item_date_created`,`journal_item_date_updated`,
                            `journal_item_flag`)
                            VALUES(mTRANS_TYPE, vTRANS_ID, mJOURNAL_ITEM_DATE, mACCOUNT_PAYABLE,
                            mTRANS_ITEM_SUBTOTAL, mBRANCH_ID, mUSER_ID, CURDATE(), CURDATE(),1);
                        END IF;
                        /* Hutang */
                    ELSEIF mTRANS_TYPE = 11 THEN /* Penjualan */
                        /* Delete Current Data From Journal Trans ID */
                        DELETE FROM journals_items WHERE journal_item_trans_id=vTRANS_ID AND journal_item_type=mTRANS_TYPE;             
                        /* Get Account Map */
                        /* Piutang Usaha From Kontak */
                        SELECT contact_account_receivable_account_id INTO mACCOUNT_RECEIVABLE FROM contacts WHERE contact_id=mCONTACT_ID;
                        /* PPN Masukan */ 
                        SELECT account_map_account_id INTO mACCOUNT_PPN_OUT FROM accounts_maps WHERE account_map_branch_id=mBRANCH_ID AND account_map_for_transaction=2 AND account_map_type=2;             
                        /* Pendapatan */ 
                        -- SELECT account_map_account_id INTO mACCOUNT_SALE_INCOME FROM accounts_maps WHERE account_map_branch_id=mBRANCH_ID AND account_map_for_transaction=2 AND account_map_type=1;
                        /* HPP / COGS */ 
                        -- SELECT account_map_account_id INTO mACCOUNT_SALE_COGS FROM accounts_maps WHERE account_map_branch_id=mBRANCH_ID AND account_map_for_transaction=1 AND account_map_type=1;
                        
                        /* Diskon */ 
                        SELECT account_map_account_id INTO mACCOUNT_DISCOUNT FROM accounts_maps WHERE account_map_branch_id=mBRANCH_ID AND account_map_for_transaction=2 AND account_map_type=5;             
                        
                        /* Persediaan (KREDIT) dan HPP / Beban Pokok Pendapatan (DEBIT) */
                        OPEN mACTION_CURSOR_INVENTORY_OUT;
                        LOOP_INVENTORY:LOOP
                            FETCH mACTION_CURSOR_INVENTORY_OUT INTO mTRANS_ITEM_TOTAL_BARANG, mTRANS_ITEM_SELL_TOTAL, mACCOUNT_INVENTORY, mACCOUNT_SALE_COGS, mACCOUNT_SALE_INCOME;
                            IF mFINISHED = 1 THEN LEAVE LOOP_INVENTORY; END IF;

                            IF mTRANS_ITEM_TOTAL_BARANG > 0 THEN

                                /* HPP / Beban Pokok Pendapatan (DEBIT) */
                                INSERT INTO journals_items (`journal_item_type`,`journal_item_trans_id`,`journal_item_date`,`journal_item_account_id`,
                                `journal_item_debit`,`journal_item_branch_id`,`journal_item_user_id`,`journal_item_date_created`,`journal_item_date_updated`,`journal_item_flag`)
                                VALUES(mTRANS_TYPE, vTRANS_ID, mJOURNAL_ITEM_DATE, mACCOUNT_SALE_COGS,
                                mTRANS_ITEM_TOTAL_BARANG, mBRANCH_ID, mUSER_ID, CURDATE(), CURDATE(),1);

                                /* Persediaan Berkurang (CREDIT) */
                                INSERT INTO journals_items (`journal_item_type`,`journal_item_trans_id`,`journal_item_date`,`journal_item_account_id`,
                                `journal_item_credit`,`journal_item_branch_id`,`journal_item_user_id`,`journal_item_date_created`,`journal_item_date_updated`,`journal_item_flag`)
                                VALUES(mTRANS_TYPE, vTRANS_ID, mJOURNAL_ITEM_DATE, mACCOUNT_INVENTORY,
                                mTRANS_ITEM_TOTAL_BARANG, mBRANCH_ID, mUSER_ID, CURDATE(), CURDATE(),1);

                                /* Pendapatan Barang (CREDIT) */
                                INSERT INTO journals_items (`journal_item_type`,`journal_item_trans_id`,`journal_item_date`,`journal_item_account_id`,
                                `journal_item_credit`,`journal_item_branch_id`,`journal_item_user_id`,`journal_item_date_created`,`journal_item_date_updated`,`journal_item_flag`)
                                VALUES(mTRANS_TYPE, vTRANS_ID, mJOURNAL_ITEM_DATE, mACCOUNT_SALE_INCOME,
                                mTRANS_ITEM_SELL_TOTAL, mBRANCH_ID, mUSER_ID, CURDATE(), CURDATE(),1);

                                SET mTRANS_ITEM_SUBTOTAL_BARANG = mTRANS_ITEM_SUBTOTAL_BARANG + mTRANS_ITEM_TOTAL_BARANG; -- trans_item_out_price / qty
                                SET mTRANS_ITEM_SELL_VALUE_BARANG = mTRANS_ITEM_SELL_VALUE_BARANG + mTRANS_ITEM_SELL_TOTAL; -- trans_item_sell_price / qty                                
                            END IF;
                        END LOOP LOOP_INVENTORY;

                        /* Jasa */
                        SET mFINISHED = 0;
                        OPEN mACTION_CURSOR_JASA_OUT;
                        LOOP_JASA:LOOP
                            FETCH mACTION_CURSOR_JASA_OUT INTO mTRANS_ITEM_TOTAL_JASA, mACCOUNT_SALE_INCOME;
                            IF mFINISHED = 1 THEN LEAVE LOOP_JASA; END IF;

                            /* Pendapatan Jasa (CREDIT) */
                            INSERT INTO journals_items (`journal_item_type`,`journal_item_trans_id`,`journal_item_date`,`journal_item_account_id`,
                            `journal_item_credit`,`journal_item_branch_id`,`journal_item_user_id`,`journal_item_date_created`,`journal_item_date_updated`,`journal_item_flag`)
                            VALUES(mTRANS_TYPE, vTRANS_ID, mJOURNAL_ITEM_DATE, mACCOUNT_SALE_INCOME,
                            mTRANS_ITEM_TOTAL_JASA, mBRANCH_ID, mUSER_ID, CURDATE(), CURDATE(),1);

                            /* Tampung nilai jasa dari trans_items */
                            SET mTRANS_ITEM_SUBTOTAL_JASA = mTRANS_ITEM_SUBTOTAL_JASA + mTRANS_ITEM_TOTAL_JASA;
                        END LOOP LOOP_JASA;

                        -- SET mFINISHED = 0;
                        -- SELECT trans_item_sell_price*trans_item_out_qty, product_sell_account_id INTO mTRANS_ITEM_TOTAL_JASA, mACCOUNT_SALE_INCOME
                        -- FROM trans_items LEFT JOIN products ON trans_item_product_id=product_id
                        -- WHERE trans_item_trans_id=vTRANS_ID AND trans_item_product_type=2;                  

                        -- WHILE mFINISHED < FOUND_ROWS() DO
                            -- SET mTRANS_ITEM_SUBTOTAL_JASA = mTRANS_ITEM_SUBTOTAL_JASA + mTRANS_ITEM_TOTAL_JASA;
                            /* Jasa = HPP / Beban Pokok Pendapatan (DEBIT) */
                            -- INSERT INTO journals_items (`journal_item_type`,`journal_item_trans_id`,`journal_item_date`,`journal_item_account_id`,
                            -- `journal_item_credit`,`journal_item_branch_id`,`journal_item_user_id`,`journal_item_date_created`,`journal_item_date_updated`,`journal_item_flag`)
                            -- VALUES(mTRANS_TYPE, vTRANS_ID, mJOURNAL_ITEM_DATE, mACCOUNT_SERVICE,
                            -- mTRANS_ITEM_TOTAL_JASA, mBRANCH_ID, mUSER_ID, CURDATE(), CURDATE(),1);
                            -- SET mFINISHED = mFINISHED + 1;
                        -- END WHILE;
                    
                        -- SET mTRANS_ITEM_SUBTOTAL = mTRANS_ITEM_SUBTOTAL_BARANG + mTRANS_ITEM_SUBTOTAL_JASA; -- 2
                        SET mTRANS_ITEM_SUBTOTAL = mTRANS_ITEM_SELL_VALUE_BARANG + mTRANS_ITEM_SUBTOTAL_JASA; -- 12000                       
                        SET mTRANS_TOTAL = mTRANS_ITEM_SUBTOTAL; -- 12000
                        -- SET mTRANS_TOTAL_INCOME = mTRANS_TOTAL + mTRANS_ITEM_SUBTOTAL; 

                        IF mFOUND_DISCOUNT > 0 THEN
                            /* Diskon Nota dan Diskon Baris di totalkan*/
                            SELECT IFNULL(SUM(trans_item_discount),0) INTO mTRANS_ITEM_DISCOUNT FROM trans_items WHERE trans_item_trans_id=vTRANS_ID;
                            SET @discount_header = mFOUND_DISCOUNT - mTRANS_ITEM_DISCOUNT;
                            /* Diskon Penjualan Nota */
                            INSERT INTO journals_items (`journal_item_type`,`journal_item_trans_id`,`journal_item_date`,`journal_item_account_id`,`journal_item_debit`,`journal_item_branch_id`,`journal_item_user_id`,`journal_item_date_created`,`journal_item_date_updated`,`journal_item_flag`)
                            VALUES(mTRANS_TYPE, vTRANS_ID, mJOURNAL_ITEM_DATE, mACCOUNT_DISCOUNT,@discount_header, mBRANCH_ID, mUSER_ID, CURDATE(), CURDATE(),1);

                            IF mTRANS_ITEM_DISCOUNT > 0 THEN
                                SET @discount_item = mTRANS_ITEM_DISCOUNT;
                                /* Diskon Penjualan Baris Produk */
                                INSERT INTO journals_items (`journal_item_type`,`journal_item_trans_id`,`journal_item_date`,`journal_item_account_id`,`journal_item_debit`,`journal_item_branch_id`,`journal_item_user_id`,`journal_item_date_created`,`journal_item_date_updated`,`journal_item_flag`)
                                VALUES(mTRANS_TYPE, vTRANS_ID, mJOURNAL_ITEM_DATE, mACCOUNT_DISCOUNT,@discount_item, mBRANCH_ID, mUSER_ID, CURDATE(), CURDATE(),1);                            
                            END IF;
                            SET @total_discount = mFOUND_DISCOUNT;
                        ELSE 
                            SET @total_discount = 0;    
                        END IF;

                        /* Ppn Keluaran & Piutang Usaha */          
                        IF mFOUND_PPN > 0 THEN
                            
                            /* If Has PPN */
                            SELECT SUM(trans_item_sell_total * (trans_item_ppn_value / 100)) INTO @total_ppn FROM trans_items 
                            WHERE trans_item_trans_id=vTRANS_ID AND trans_item_ppn=1;

                            SET mTRANS_TOTAL = mTRANS_TOTAL - @total_discount;
                            SET mTRANS_TOTAL_INCOME = mTRANS_TOTAL + @total_discount; -- 2400
                            SET mTRANS_TOTAL = mTRANS_TOTAL + @total_ppn;

                            /* Ppn Keluaran (CREDIT) */
                            INSERT INTO journals_items (`journal_item_type`,`journal_item_trans_id`,`journal_item_date`,`journal_item_account_id`,
                            `journal_item_credit`,`journal_item_branch_id`,`journal_item_user_id`,`journal_item_date_created`,`journal_item_date_updated`,`journal_item_flag`)
                            VALUES(mTRANS_TYPE, vTRANS_ID, mJOURNAL_ITEM_DATE, mACCOUNT_PPN_OUT,
                            @total_ppn, mBRANCH_ID, mUSER_ID, CURDATE(), CURDATE(),1);

                            /* Penjualan / Pendapatan (CREDIT)*/    
                            -- INSERT INTO journals_items (`journal_item_type`,`journal_item_trans_id`,`journal_item_date`,`journal_item_account_id`,
                            -- `journal_item_credit`,`journal_item_branch_id`,`journal_item_user_id`,`journal_item_date_created`,`journal_item_date_updated`,`journal_item_flag`)
                            -- VALUES(mTRANS_TYPE, vTRANS_ID, mJOURNAL_ITEM_DATE, mACCOUNT_SALE_INCOME,
                            -- mTRANS_TOTAL_INCOME, mBRANCH_ID, mUSER_ID, CURDATE(), CURDATE(),1);

                            /* Piutang Usaha (DEBIT) */
                            INSERT INTO journals_items (`journal_item_type`,`journal_item_trans_id`,`journal_item_date`,`journal_item_account_id`,
                            `journal_item_debit`,`journal_item_branch_id`,`journal_item_user_id`,`journal_item_date_created`,`journal_item_date_updated`,`journal_item_flag`)
                            VALUES(mTRANS_TYPE, vTRANS_ID, mJOURNAL_ITEM_DATE, mACCOUNT_RECEIVABLE,
                            mTRANS_TOTAL, mBRANCH_ID, mUSER_ID, CURDATE(), CURDATE(),1);
                        ELSE
                            SET mTRANS_TOTAL = mTRANS_TOTAL - @total_discount;
                            SET mTRANS_TOTAL_INCOME = mTRANS_TOTAL + @total_discount; -- 2400
                            SET mTRANS_TOTAL = mTRANS_TOTAL; -- 3600

                            /* Penjualan / Pendapatan (CREDIT)*/    
                            -- INSERT INTO journals_items (`journal_item_type`,`journal_item_trans_id`,`journal_item_date`,`journal_item_account_id`,
                            -- `journal_item_credit`,`journal_item_branch_id`,`journal_item_user_id`,`journal_item_date_created`,`journal_item_date_updated`,`journal_item_flag`)
                            -- VALUES(mTRANS_TYPE, vTRANS_ID, mJOURNAL_ITEM_DATE, mACCOUNT_SALE_INCOME,
                            -- mTRANS_TOTAL_INCOME, mBRANCH_ID, mUSER_ID, CURDATE(), CURDATE(),1);

                            /* Piutang Usaha (DEBIT) */
                            INSERT INTO journals_items (`journal_item_type`,`journal_item_trans_id`,`journal_item_date`,`journal_item_account_id`,
                            `journal_item_debit`,`journal_item_branch_id`,`journal_item_user_id`,`journal_item_date_created`,`journal_item_date_updated`,`journal_item_flag`)
                            VALUES(mTRANS_TYPE, vTRANS_ID, mJOURNAL_ITEM_DATE, mACCOUNT_RECEIVABLE,
                            mTRANS_TOTAL, mBRANCH_ID, mUSER_ID, CURDATE(), CURDATE(),1);
                        END IF;          
                    ELSEIF mTRANS_TYPE = 14 THEN /* Stok Opname */
                        /* Delete Current Data From Journal Trans ID */
                        DELETE FROM journals_items WHERE journal_item_trans_id=vTRANS_ID AND journal_item_type=mTRANS_TYPE;             
                        /* Persediaan Barang (Debit) */
                        -- SELECT account_map_account_id INTO mACCOUNT_INVENTORY FROM accounts_maps WHERE account_map_branch_id=mBRANCH_ID AND account_map_for_transaction=3 AND account_map_type=1;    
                        /* Persediaan Umum / Penyesuaian (Credit) */ 
                        SELECT account_map_account_id INTO mACCOUNT_INVENTORY_ADJUSMENT FROM accounts_maps WHERE account_map_branch_id=mBRANCH_ID AND account_map_for_transaction=3 AND account_map_type=2;             

                        /* Persediaan */
                        OPEN mACTION_CURSOR_INVENTORY_IN;
                        LOOP_INVENTORY:LOOP
                            FETCH mACTION_CURSOR_INVENTORY_IN INTO mTRANS_ITEM_TOTAL, mACCOUNT_INVENTORY, mACCOUNT_SERVICE;
                            IF mFINISHED = 1 THEN
                                LEAVE LOOP_INVENTORY;
                            END IF;
                            SET mTRANS_ITEM_SUBTOTAL_BARANG = mTRANS_ITEM_SUBTOTAL_BARANG + mTRANS_ITEM_TOTAL;

                            /* Persedian Barang Bertambah */
                            INSERT INTO journals_items (`journal_item_type`,`journal_item_trans_id`,`journal_item_date`,`journal_item_account_id`,
                            `journal_item_debit`,`journal_item_branch_id`,`journal_item_user_id`,`journal_item_date_created`,`journal_item_date_updated`,
                            `journal_item_flag`)
                            VALUES(mTRANS_TYPE, vTRANS_ID, mJOURNAL_ITEM_DATE, mACCOUNT_INVENTORY,
                            mTRANS_ITEM_TOTAL, mBRANCH_ID, mUSER_ID, CURDATE(), CURDATE(),1);
                        END LOOP LOOP_INVENTORY;

                        /* Penyesuaian (CREDIT) */
                        INSERT INTO journals_items (`journal_item_type`,`journal_item_trans_id`,`journal_item_date`,`journal_item_account_id`,
                        `journal_item_credit`,`journal_item_branch_id`,`journal_item_user_id`,`journal_item_date_created`,`journal_item_date_updated`,
                        `journal_item_flag`)
                        VALUES(mTRANS_TYPE, vTRANS_ID, mJOURNAL_ITEM_DATE, mACCOUNT_INVENTORY_ADJUSMENT,
                        mTRANS_ITEM_SUBTOTAL_BARANG, mBRANCH_ID, mUSER_ID, CURDATE(), CURDATE(),1);
                    ELSEIF mTRANS_TYPE = 19 THEN /* Produksi */
                        /* Delete Current Data From Journal Trans ID */
                        DELETE FROM journals_items 
                        WHERE journal_item_trans_id=vTRANS_ID 
                        AND journal_item_type=mTRANS_TYPE
                        AND journal_item_account_id NOT IN (
                            SELECT account_id FROM accounts WHERE account_branch_id=mBRANCH_ID AND account_group=5 AND account_group_sub=16
                        );
                        /* Bahan Terpakai Keluar */
                        OPEN mACTION_CURSOR_INVENTORY_PRODUCTION_OUT;
                        LOOP_INVENTORY_OUT:LOOP
                            FETCH mACTION_CURSOR_INVENTORY_PRODUCTION_OUT INTO mTRANS_ITEM_TOTAL_BARANG, mACCOUNT_INVENTORY, mACCOUNT_SALE_COGS, mACCOUNT_SALE_INCOME;
                            IF mFINISHED = 1 THEN LEAVE LOOP_INVENTORY_OUT; END IF;
                            /* Persedian Barang Bertambah */
                            INSERT INTO journals_items (`journal_item_type`,`journal_item_trans_id`,`journal_item_date`,`journal_item_account_id`,
                            `journal_item_credit`,`journal_item_branch_id`,`journal_item_user_id`,`journal_item_date_created`,`journal_item_date_updated`,
                            `journal_item_flag`)
                            VALUES(mTRANS_TYPE, vTRANS_ID, mJOURNAL_ITEM_DATE, mACCOUNT_INVENTORY,
                            mTRANS_ITEM_TOTAL_BARANG, mBRANCH_ID, mUSER_ID, CURDATE(), CURDATE(),1);
                        END LOOP LOOP_INVENTORY_OUT;

                        SET mFINISHED = 0;
                        /* Produk Jadi Masuk */
                        OPEN mACTION_CURSOR_INVENTORY_PRODUCTION_IN;
                        LOOP_INVENTORY_IN:LOOP
                            FETCH mACTION_CURSOR_INVENTORY_PRODUCTION_IN INTO mTRANS_ITEM_TOTAL_BARANG, mACCOUNT_INVENTORY, mACCOUNT_SALE_COGS, mACCOUNT_SALE_INCOME;
                            IF mFINISHED = 1 THEN LEAVE LOOP_INVENTORY_IN; END IF;
                            /* Persedian Barang Bertambah */
                            INSERT INTO journals_items (`journal_item_type`,`journal_item_trans_id`,`journal_item_date`,`journal_item_account_id`,
                            `journal_item_debit`,`journal_item_branch_id`,`journal_item_user_id`,`journal_item_date_created`,`journal_item_date_updated`,
                            `journal_item_flag`)
                            VALUES(mTRANS_TYPE, vTRANS_ID, mJOURNAL_ITEM_DATE, mACCOUNT_INVENTORY,
                            mTRANS_ITEM_TOTAL_BARANG, mBRANCH_ID, mUSER_ID, CURDATE(), CURDATE(),1);
                        END LOOP LOOP_INVENTORY_IN;
                    ELSEIF mTRANS_TYPE = 20 THEN /* Pemakaian Barang */
                        /* Delete Current Data From Journal Trans ID */
                        DELETE FROM journals_items WHERE journal_item_trans_id=vTRANS_ID AND journal_item_type=mTRANS_TYPE;
                        
                        OPEN mACTION_CURSOR_INVENTORY_OUT;
                        LOOP_INVENTORY:LOOP
                            FETCH mACTION_CURSOR_INVENTORY_OUT INTO mTRANS_ITEM_TOTAL, mTRANS_ITEM_SELL_TOTAL, mACCOUNT_INVENTORY, mACCOUNT_SALE_INCOME, mACCOUNT_INVENTORY_ADJUSMENT;
                            IF mFINISHED = 1 THEN
                                LEAVE LOOP_INVENTORY;
                            END IF;
                            SET mTRANS_ITEM_SUBTOTAL = mTRANS_ITEM_SUBTOTAL + mTRANS_ITEM_TOTAL;
                            
                            /* Persedian Barang CREDIT */
                            INSERT INTO journals_items (`journal_item_type`,`journal_item_trans_id`,`journal_item_date`,`journal_item_account_id`,
                            `journal_item_credit`,`journal_item_branch_id`,`journal_item_user_id`,`journal_item_date_created`,`journal_item_date_updated`,
                            `journal_item_flag`)
                            VALUES(mTRANS_TYPE, vTRANS_ID, mJOURNAL_ITEM_DATE, mACCOUNT_INVENTORY,
                            mTRANS_ITEM_TOTAL, mBRANCH_ID, mUSER_ID, CURDATE(), CURDATE(),1);

                            /* HPP DEBIT */        
                            INSERT INTO journals_items (`journal_item_type`,`journal_item_trans_id`,`journal_item_date`,`journal_item_account_id`,
                            `journal_item_debit`,`journal_item_branch_id`,`journal_item_user_id`,`journal_item_date_created`,`journal_item_date_updated`,
                            `journal_item_flag`)
                            VALUES(mTRANS_TYPE, vTRANS_ID, mJOURNAL_ITEM_DATE, mACCOUNT_INVENTORY_ADJUSMENT,
                            mTRANS_ITEM_TOTAL, mBRANCH_ID, mUSER_ID, CURDATE(), CURDATE(),1);        
                        END LOOP LOOP_INVENTORY;     
                    ELSEIF mTRANS_TYPE = 22 THEN /* Retur Pembelian */
                        DELETE FROM journals_items WHERE journal_item_trans_id=vTRANS_ID AND journal_item_type=mTRANS_TYPE;
                        
                        /* Hutang Usaha From Kontak */
                        SELECT contact_account_payable_account_id INTO mACCOUNT_PAYABLE FROM contacts WHERE contact_id=mCONTACT_ID;
                        /* PPN Masukan */ 
                        SELECT account_map_account_id INTO mACCOUNT_PPN_IN FROM accounts_maps WHERE account_map_branch_id=mBRANCH_ID AND account_map_for_transaction=1 AND account_map_type=2;

                        /* Persediaan */
                        OPEN mACTION_CURSOR_INVENTORY;
                        LOOP_INVENTORY:LOOP
                            FETCH mACTION_CURSOR_INVENTORY INTO mTRANS_ITEM_TOTAL_BARANG, mACCOUNT_INVENTORY;
                            IF mFINISHED = 1 THEN
                                LEAVE LOOP_INVENTORY;
                            END IF;
                            SET mTRANS_ITEM_SUBTOTAL_BARANG = mTRANS_ITEM_SUBTOTAL_BARANG + mTRANS_ITEM_TOTAL_BARANG;
                            /* Persedian Barang Bertambah */
                            INSERT INTO journals_items (`journal_item_type`,`journal_item_trans_id`,`journal_item_date`,`journal_item_account_id`,
                            `journal_item_credit`,`journal_item_branch_id`,`journal_item_user_id`,`journal_item_date_created`,`journal_item_date_updated`,
                            `journal_item_flag`)
                            VALUES(mTRANS_TYPE, vTRANS_ID, mJOURNAL_ITEM_DATE, mACCOUNT_INVENTORY,
                            mTRANS_ITEM_TOTAL_BARANG, mBRANCH_ID, mUSER_ID, CURDATE(), CURDATE(),1);
                        END LOOP LOOP_INVENTORY;

                        /* Jasa */
                        SET mFINISHED = 1;
                        SET mTRANS_ITEM_SUBTOTAL_JASA = 0;
                        SELECT trans_item_total, product_buy_account_id INTO mTRANS_ITEM_TOTAL_JASA, mACCOUNT_SERVICE
                        FROM trans_items 
                        LEFT JOIN products ON trans_item_product_id=product_id
                        WHERE trans_item_trans_id=vTRANS_ID AND trans_item_product_type=2;                  

                        WHILE mFINISHED <= FOUND_ROWS() DO
                            SET mTRANS_ITEM_SUBTOTAL_JASA = mTRANS_ITEM_SUBTOTAL_JASA + mTRANS_ITEM_TOTAL_JASA;
                            /* Jasa Bertambah */
                            INSERT INTO journals_items (`journal_item_type`,`journal_item_trans_id`,`journal_item_date`,`journal_item_account_id`,
                            `journal_item_credit`,`journal_item_branch_id`,`journal_item_user_id`,`journal_item_date_created`,`journal_item_date_updated`,
                            `journal_item_flag`)
                            VALUES(mTRANS_TYPE, vTRANS_ID, mJOURNAL_ITEM_DATE, mACCOUNT_SERVICE,
                            mTRANS_ITEM_TOTAL_JASA, mBRANCH_ID, mUSER_ID, CURDATE(), CURDATE(),1);
                            SET mFINISHED = mFINISHED + 1;
                        END WHILE;
                    
                        SET mTRANS_ITEM_SUBTOTAL = mTRANS_ITEM_SUBTOTAL_BARANG + mTRANS_ITEM_SUBTOTAL_JASA;
                        
                        /* Ppn Masukan & Hutang Usaha */            
                        IF mFOUND_PPN > 0 THEN
                            /* If Has PPN */
                            SELECT SUM(trans_item_out_price * trans_item_out_qty * (trans_item_ppn_value / 100)) INTO @total_ppn FROM trans_items 
                            WHERE trans_item_trans_id=vTRANS_ID AND trans_item_ppn=1;
                            -- SET @total_ppn := @total_ppn * 0.1;
                            SET mTRANS_ITEM_SUBTOTAL := mTRANS_ITEM_SUBTOTAL + @total_ppn;

                            /* Hutang Usaha (CREDIT) */
                            INSERT INTO journals_items (`journal_item_type`,`journal_item_trans_id`,`journal_item_date`,`journal_item_account_id`,
                            `journal_item_debit`,`journal_item_branch_id`,`journal_item_user_id`,`journal_item_date_created`,`journal_item_date_updated`,
                            `journal_item_flag`)
                            VALUES(mTRANS_TYPE, vTRANS_ID, mJOURNAL_ITEM_DATE, mACCOUNT_PAYABLE,
                            mTRANS_ITEM_SUBTOTAL, mBRANCH_ID, mUSER_ID, CURDATE(), CURDATE(),1);  

                            /* Ppn Masukan (DEBIT) */
                            INSERT INTO journals_items (`journal_item_type`,`journal_item_trans_id`,`journal_item_date`,`journal_item_account_id`,
                            `journal_item_credit`,`journal_item_branch_id`,`journal_item_user_id`,`journal_item_date_created`,`journal_item_date_updated`,
                            `journal_item_flag`)
                            VALUES(mTRANS_TYPE, vTRANS_ID, mJOURNAL_ITEM_DATE, mACCOUNT_PPN_IN,
                            @total_ppn, mBRANCH_ID, mUSER_ID, CURDATE(), CURDATE(),1);                          
                        ELSE
                            /* Kas & Hutang (DEBIT) */
                            /* Perlu pengecekan apakah sudah lunas */
                            INSERT INTO journals_items (`journal_item_type`,`journal_item_trans_id`,`journal_item_date`,`journal_item_account_id`,
                            `journal_item_debit`,`journal_item_branch_id`,`journal_item_user_id`,`journal_item_date_created`,`journal_item_date_updated`,
                            `journal_item_flag`)
                            VALUES(mTRANS_TYPE, vTRANS_ID, mJOURNAL_ITEM_DATE, mACCOUNT_PAYABLE,
                            mTRANS_ITEM_SUBTOTAL, mBRANCH_ID, mUSER_ID, CURDATE(), CURDATE(),1);                        
                        END IF;

                        /* Call SP Return */
                        CALL sp_trans_update_return('CREATE',vTRANS_ID);
                    ELSEIF mTRANS_TYPE = 23 THEN /* Retur Penjualan*/
                        DELETE FROM journals_items WHERE journal_item_trans_id=vTRANS_ID AND journal_item_type=mTRANS_TYPE;
                        
                        /* Piutang Usaha From Kontak */
                        SELECT contact_account_receivable_account_id INTO mACCOUNT_RECEIVABLE 
                        FROM contacts WHERE contact_id=mCONTACT_ID;
                        
                        /* Retur Penjualan */
                        SELECT account_map_account_id INTO mACCOUNT_RETURN 
                        FROM accounts_maps WHERE account_map_branch_id=mBRANCH_ID AND account_map_for_transaction=2 AND account_map_type=4;             
                        
                        /* PPN Keluaran */ 
                        SELECT account_map_account_id INTO mACCOUNT_PPN_OUT 
                        FROM accounts_maps WHERE account_map_branch_id=mBRANCH_ID AND account_map_for_transaction=2 AND account_map_type=2;

                        SET mFINISHED = 0;
                        SET mTRANS_ITEM_SUBTOTAL_BARANG = 0;
                        
                        /* Persediaan */
                        OPEN mACTION_CURSOR_INVENTORY_IN;
                        LOOP_INVENTORY:LOOP
                            FETCH mACTION_CURSOR_INVENTORY_IN INTO mTRANS_ITEM_TOTAL_BARANG, mACCOUNT_INVENTORY, mACCOUNT_INVENTORY_ADJUSMENT;
                            IF mFINISHED = 1 THEN LEAVE LOOP_INVENTORY; END IF;
                            SET mTRANS_ITEM_SUBTOTAL_BARANG = mTRANS_ITEM_SUBTOTAL_BARANG + mTRANS_ITEM_TOTAL_BARANG;
                            /* Persedian Barang Bertambah */
                            INSERT INTO journals_items (`journal_item_type`,`journal_item_trans_id`,`journal_item_date`,`journal_item_account_id`,
                            `journal_item_debit`,`journal_item_branch_id`,`journal_item_user_id`,`journal_item_date_created`,`journal_item_date_updated`,
                            `journal_item_flag`)
                            VALUES(mTRANS_TYPE, vTRANS_ID, mJOURNAL_ITEM_DATE, mACCOUNT_INVENTORY,
                            mTRANS_ITEM_TOTAL_BARANG, mBRANCH_ID, mUSER_ID, CURDATE(), CURDATE(),1);
                            /* mACCOUNT_INVENTORY_ADJUSTMENT */
                            INSERT INTO journals_items (`journal_item_type`,`journal_item_trans_id`,`journal_item_date`,`journal_item_account_id`,
                            `journal_item_credit`,`journal_item_branch_id`,`journal_item_user_id`,`journal_item_date_created`,`journal_item_date_updated`,
                            `journal_item_flag`)
                            VALUES(mTRANS_TYPE, vTRANS_ID, mJOURNAL_ITEM_DATE, mACCOUNT_INVENTORY_ADJUSMENT,
                            mTRANS_ITEM_TOTAL_BARANG, mBRANCH_ID, mUSER_ID, CURDATE(), CURDATE(),1);                            
                        END LOOP LOOP_INVENTORY;

                        SET mFINISHED = 1;
                        SET mTRANS_ITEM_SUBTOTAL_JASA = 0;
                        
                        /* Jasa */
                        SELECT IFNULL(trans_item_in_price*trans_item_in_qty,0), product_buy_account_id 
                        INTO mTRANS_ITEM_TOTAL_JASA, mACCOUNT_SERVICE
                        FROM trans_items 
                        LEFT JOIN products ON trans_item_product_id=product_id
                        WHERE trans_item_trans_id=vTRANS_ID AND trans_item_product_type=2;

                            WHILE mFINISHED <= FOUND_ROWS() DO
                                SET mTRANS_ITEM_SUBTOTAL_JASA = mTRANS_ITEM_SUBTOTAL_JASA + mTRANS_ITEM_TOTAL_JASA;
                                /* Jasa Bertambah */
                                INSERT INTO journals_items (`journal_item_type`,`journal_item_trans_id`,`journal_item_date`,`journal_item_account_id`,
                                `journal_item_debit`,`journal_item_branch_id`,`journal_item_user_id`,`journal_item_date_created`,`journal_item_date_updated`,
                                `journal_item_flag`)
                                VALUES(mTRANS_TYPE, vTRANS_ID, mJOURNAL_ITEM_DATE, mACCOUNT_SERVICE,
                                mTRANS_ITEM_TOTAL_JASA, mBRANCH_ID, mUSER_ID, CURDATE(), CURDATE(),1);
                                SET mFINISHED = mFINISHED + 1;
                            END WHILE;
                                            
                        /* Get Summary of Last Transaction */ 
                        SELECT SUM(trans_item_total) INTO mTRANS_ITEM_SUBTOTAL 
                        FROM trans_items
                        WHERE trans_item_trans_id=vTRANS_ID;
                        
                        -- SET mTRANS_ITEM_SUBTOTAL = mTRANS_ITEM_SUBTOTAL_BARANG + mTRANS_ITEM_SUBTOTAL_JASA;

                        /* Retur */
                        INSERT INTO journals_items (`journal_item_type`,`journal_item_trans_id`,`journal_item_date`,`journal_item_account_id`,
                        `journal_item_debit`,`journal_item_branch_id`,`journal_item_user_id`,`journal_item_date_created`,`journal_item_date_updated`,
                        `journal_item_flag`)
                        VALUES(mTRANS_TYPE, vTRANS_ID, mJOURNAL_ITEM_DATE, mACCOUNT_RETURN,
                        mTRANS_ITEM_SUBTOTAL, mBRANCH_ID, mUSER_ID, CURDATE(), CURDATE(),1);   

                        /* Ppn Keluaran & Piutang Usaha */            
                        IF mFOUND_PPN > 0 THEN
                            /* If Has PPN */
                            SELECT SUM(trans_item_sell_price * trans_item_in_qty * (trans_item_ppn_value / 100)) INTO @total_ppn FROM trans_items 
                            WHERE trans_item_trans_id=vTRANS_ID AND trans_item_ppn=1;
                            -- SET @total_ppn := @total_ppn * 0.1;
                            SET mTRANS_ITEM_SUBTOTAL := mTRANS_ITEM_SUBTOTAL + @total_ppn;

                            /* Kas & Piutang (DEBIT) */
                            INSERT INTO journals_items (`journal_item_type`,`journal_item_trans_id`,`journal_item_date`,`journal_item_account_id`,
                            `journal_item_credit`,`journal_item_branch_id`,`journal_item_user_id`,`journal_item_date_created`,`journal_item_date_updated`,
                            `journal_item_flag`)
                            VALUES(mTRANS_TYPE, vTRANS_ID, mJOURNAL_ITEM_DATE, mACCOUNT_RECEIVABLE,
                            mTRANS_ITEM_SUBTOTAL, mBRANCH_ID, mUSER_ID, CURDATE(), CURDATE(),1);

                            /* Ppn Keluaran (DEBIT) */
                            INSERT INTO journals_items (`journal_item_type`,`journal_item_trans_id`,`journal_item_date`,`journal_item_account_id`,
                            `journal_item_debit`,`journal_item_branch_id`,`journal_item_user_id`,`journal_item_date_created`,`journal_item_date_updated`,
                            `journal_item_flag`)
                            VALUES(mTRANS_TYPE, vTRANS_ID, mJOURNAL_ITEM_DATE, mACCOUNT_PPN_OUT,
                            @total_ppn, mBRANCH_ID, mUSER_ID, CURDATE(), CURDATE(),1);  

                        ELSE 
                            /* Kas & Piutang (DEBIT) */
                            INSERT INTO journals_items (`journal_item_type`,`journal_item_trans_id`,`journal_item_date`,`journal_item_account_id`,
                            `journal_item_credit`,`journal_item_branch_id`,`journal_item_user_id`,`journal_item_date_created`,`journal_item_date_updated`,
                            `journal_item_flag`)
                            VALUES(mTRANS_TYPE, vTRANS_ID, mJOURNAL_ITEM_DATE, mACCOUNT_RECEIVABLE,
                            mTRANS_ITEM_SUBTOTAL, mBRANCH_ID, mUSER_ID, CURDATE(), CURDATE(),1);
                        END IF;

                        /* Call SP Return */
                        CALL sp_trans_update_return('CREATE',vTRANS_ID);
                    ELSE 
                        SET @Message = CONCAT('Operation invalid');
                    END IF;
                    SET @SQL_Text := CONCAT('SELECT CONCAT(1) AS status, CONCAT("',mMESSAGE,'") AS message, CONCAT("Operator CREATE-UPDATE Transaction to Journal") AS operator, CONCAT("',vTRANS_ID,'") AS transaction_id, CONCAT("0") AS result');
                ELSEIF vACTION = 'DELETE' THEN
                    
                    IF (mTRANS_TYPE = 22) OR (mTRANS_TYPE = 23) THEN /* Retur Pembelian / Penjualan */
                        CALL sp_trans_update_return('DELETE',vTRANS_ID);
                    END IF;

                    /* Delete Current Data From Journal Trans ID */
                    IF vTRANS_ID > 0 THEN
                        DELETE FROM journals_items WHERE journal_item_trans_id=vTRANS_ID AND journal_item_type=mTRANS_TYPE;
                        DELETE FROM trans_items WHERE trans_item_trans_id=vTRANS_ID;
                        DELETE FROM trans WHERE trans_id=vTRANS_ID;
                    END IF;

                    SET @SQL_Text := CONCAT('SELECT CONCAT(1) AS status, CONCAT("-") AS message, CONCAT("Operator DELETE Transaction to Journal") AS operator, CONCAT("',vTRANS_ID,'") AS transaction_id, CONCAT("0") AS result');
                ELSE
                    SET @SQL_Text := CONCAT('SELECT CONCAT(0) AS status, CONCAT("Operator not found") AS message, CONCAT("-") AS operator, CONCAT("',vTRANS_ID,'") AS transaction_id, CONCAT("0") AS result');
                END IF;
            ELSE 
                SET @SQL_Text := CONCAT('SELECT CONCAT(0) AS status, CONCAT("This Branch dont want Journal") AS message, CONCAT("-") AS operator, CONCAT("',vTRANS_ID,'") AS transaction_id, CONCAT("0") AS result');       
            END IF;
        END BLOCK_A;
                
        /* Prepare Query Statement */
        PREPARE stmt FROM @SQL_Text; EXECUTE stmt; DEALLOCATE PREPARE stmt;    
    END$$
DELIMITER ;

DELIMITER $$
DROP PROCEDURE IF EXISTS `sp_product_stock`$$
CREATE PROCEDURE `sp_product_stock`(IN vACTION INT(255), IN vPRODUCT_ID BIGINT(50), IN vLOCATION_ID BIGINT(50))
    BEGIN
        DECLARE mPRODUCT_NAME VARCHAR(255);
        DECLARE mPRODUCT_UNIT VARCHAR(255);
        DECLARE mLOCATION_NAME VARCHAR(255);
        
        DECLARE mSTATUS INT(5) DEFAULT 0;
        DECLARE mMESSAGE VARCHAR(255) DEFAULT 'Failed';
        
        SELECT location_name INTO mLOCATION_NAME FROM locations WHERE location_id=vLOCATION_ID;
        SELECT product_name, product_unit INTO mPRODUCT_NAME, mPRODUCT_UNIT FROM products WHERE product_id=vPRODUCT_ID;

        IF vACTION = 1 THEN -- Non Grouping
            SELECT 
                CONCAT(vPRODUCT_ID) AS product_id, 
                CONCAT(mPRODUCT_NAME) AS product_name,
                CONCAT(mPRODUCT_UNIT) AS product_unit,      
                CONCAT(vLOCATION_ID) AS location_id,
                CONCAT(mLOCATION_NAME) AS location_name,
                IFNULL(SUM(trans_item_in_qty-trans_item_out_qty),0) AS product_qty
            FROM trans_items
            WHERE trans_item_product_id=vPRODUCT_ID AND trans_item_location_id=vLOCATION_ID
            HAVING SUM(trans_item_in_qty) - SUM(trans_item_out_qty) > 0
            ORDER BY trans_item_date ASC 
            LIMIT 1;
        ELSEIF vACTION = 2 THEN -- Grouping
            SELECT 
                CONCAT(vPRODUCT_ID) AS product_id, 
                CONCAT(mPRODUCT_NAME) AS product_name,
                CONCAT(mPRODUCT_UNIT) AS product_unit,      
                CONCAT(vLOCATION_ID) AS location_id,
                CONCAT(mLOCATION_NAME) AS location_name,
                IFNULL(SUM(trans_item_in_qty-trans_item_out_qty),0) AS product_qty,
                IFNULL(trans_item_in_price,0) AS product_in_price,
                IFNULL(trans_item_out_price,0) AS product_out_price,
                CASE WHEN trans_item_in_price > 0 THEN trans_item_in_price ELSE trans_item_out_price END AS product_price,
                trans_item_ref AS product_in_ref
            FROM trans_items
            WHERE trans_item_product_id=vPRODUCT_ID AND trans_item_location_id=vLOCATION_ID
            GROUP BY trans_item_ref
            HAVING SUM(trans_item_in_qty) - SUM(trans_item_out_qty) > 0
            ORDER BY trans_item_date ASC;
        END IF;
    END$$
DELIMITER ;

DELIMITER $$
DROP PROCEDURE IF EXISTS `sp_product_stock_update`$$
CREATE PROCEDURE `sp_product_stock_update`(IN vPRODUCT_ID BIGINT(50))
    BEGIN
        DECLARE mPRODUCT_TYPE INT(5);
        DECLARE mPRODUCT_WITH_STOCK INT(5);
        DECLARE mSTOCK_CALCULATE DOUBLE(18,2);
        
        SELECT product_type, product_with_stock INTO mPRODUCT_TYPE, mPRODUCT_WITH_STOCK FROM products WHERE product_id=vPRODUCT_ID;
        IF mPRODUCT_WITH_STOCK = 1 THEN 
            
            SELECT IFNULL(SUM(trans_item_in_qty),0) - IFNULL(SUM(trans_item_out_qty),0) INTO mSTOCK_CALCULATE 
            FROM trans_items
            WHERE trans_item_product_id=vPRODUCT_ID;

            -- IF mSTOCK_CALCULATE > 0 THEN
            --     SET mSTOCK_CALCULATE = mSTOCK_CALCULATE;
            -- ELSE
            --     SET mSTOCK_CALCULATE = '0.00';
            -- END IF;
            
            UPDATE products SET product_stock=mSTOCK_CALCULATE WHERE product_id=vPRODUCT_ID;
        END IF;
    END$$
DELIMITER ;


DELIMITER $$
DROP PROCEDURE IF EXISTS `sp_report_finance`$$
CREATE PROCEDURE `sp_report_finance`(IN vACTION INT(5),IN vSTART VARCHAR(255),IN vEND VARCHAR(255),IN vBRANCH_ID BIGINT(50),IN vACCOUNT_ID BIGINT(50),IN vSEARCH VARCHAR(255))
    BEGIN
        DECLARE mDATE_START_APP VARCHAR(255);
        /*
            ACTION
            1 = Jurnal Umum
            2 = Buku Besar 
            3 = Neraca Saldo => Neraca Saldo, Neraca Lajur, Laba Rugi, Neraca
            4 = Hutang
            5 = Piutang
            6 = Pemasukan Uang / Setoran
            7 = Pengeluaran Uang / Biaya

            Group Of Account 
            1 = Asset       Debit  - Credit
            2 = Liability   Credit + Debit
            3 = Ekuitas     Credit + Debit 
            4 = Income      Credit + Debit 
            5 = Cost        Debit  - Credit
        */
        IF vACTION = 1 THEN /* Jurnal Umum */
            DROP TEMPORARY TABLE IF EXISTS journals_temp;
            CREATE TEMPORARY TABLE journals_temp (
                `temp_id` BIGINT NOT NULL AUTO_INCREMENT,
                `journal_item_id` BIGINT(50),
                `journal_item_date` VARCHAR(255),
                `journal_item_note` VARCHAR(255),
                `journal_item_group_session` VARCHAR(255),
                `type_id` INT(5),
                `type_name` VARCHAR(255), 
                `journal_text` VARCHAR(255),
                `contact_name` VARCHAR(255),                
                `journal_number` VARCHAR(255), 
                `journal_id` BIGINT(255),
                `trans_id` BIGINT(255),
                `order_id` BIGINT(255),
                `trans_number` VARCHAR(255), 
                `account_id` BIGINT(50),
                `account_group` INT(5),
                `account_code` VARCHAR(255),
                `account_name` VARCHAR(255),
                `debit` DOUBLE(18,2) DEFAULT 0,
                `credit` DOUBLE(18,2) DEFAULT 0,
                `balance` DOUBLE(18,2) DEFAULT 0,
                `journal_session` VARCHAR(255),
                `trans_session` VARCHAR(255),                 
                `status` INT(5) DEFAULT 0,
                `message` VARCHAR(255),
                `total_data` INT(50),
                `search` VARCHAR(255),
                `running_date` VARCHAR(255),
                `period_date` VARCHAR(255),                
                PRIMARY KEY (`temp_id`),
                INDEX `JOURNAL_ITEM_ID`(`journal_item_id`) USING BTREE
            ) ENGINE=INNODB;    
            
            SELECT MIN(journal_item_date) INTO mDATE_START_APP FROM journals_items WHERE journal_item_branch_id=vBRANCH_ID;
            SET @start_date = mDATE_START_APP - INTERVAL 1 DAY;
            SET @end_date = CONCAT(vSTART,' 23:59:59') - INTERVAL 1 DAY;
            SET @running_date_start = CONCAT(vSTART,' 00:00:00');
            SET @running_date_end = CONCAT(vEND,' 23:59:59');
            
            SET @start_date_journal = @start_date;
            SET @end_date_journal = @end_date;
            
            SET @start_date = DATE_FORMAT(@start_date, "%Y%m%d%H%i%s"); 
            SET @end_date = DATE_FORMAT(@end_date, "%Y%m%d%H%i%s");
            
            BLOCK_A:BEGIN /* Get Running Balance */
                DECLARE mACCOUNT_ID INT(50);
                DECLARE mACCOUNT_CODE VARCHAR(255);
                DECLARE mACCOUNT_NAME VARCHAR(255);    
                DECLARE mACCOUNT_GROUP INT(5);
                
                DECLARE mJOURNAL_ITEM_ID BIGINT(50);
                DECLARE mJOURNAL_DATE DATETIME;
                DECLARE mJOURNAL_NOTE TEXT;
                DECLARE mTYPE_ID INT(5);
                DECLARE mTYPE_NAME VARCHAR(255);
                DECLARE mJOURNAL_NUMBER VARCHAR(255);
                DECLARE mJOURNAL_ID BIGINT(255);
                DECLARE mTRANS_ID BIGINT(255);
                DECLARE mORDER_ID BIGINT(255);     
                DECLARE mORDER_NUMBER VARCHAR(255);           
                DECLARE mTRANS_NUMBER VARCHAR(255);         
                DECLARE mTRANS_SESSIOn VARCHAR(255);
                DECLARE mJOURNAL_GROUP_SESSION VARCHAR(255);
                
                DECLARE mDEBIT DOUBLE(18,2) DEFAULT 0;
                DECLARE mCREDIT DOUBLE(18,2) DEFAULT 0;
                DECLARE mBALANCE DOUBLE(18,2) DEFAULT 0;
                DECLARE mTOTAL_DATA INT(50) DEFAULT 0;
                DECLARE mFINISHED INTEGER;
                DECLARE mACTION_CURSOR CURSOR FOR
                    SELECT 
                    `journals_items`.`journal_item_id`,
                    `journals_items`.`journal_item_date`, 
                    `journals_items`.`journal_item_note`, 
                    `journals_items`.`journal_item_group_session`,
                    `journals_items`.`journal_item_type`,
                    `journals_items`.`journal_item_order_id`,
                    `types`.`type_name`, 
                    `journals`.`journal_id`,
                    `journals`.`journal_number`, 
                    `trans`.`trans_id`,
                    `trans`.`trans_session`,
                    `trans`.`trans_number`,
                    `journals_items`.`journal_item_account_id` AS `account_id`, 
                    `accounts`.`account_code`, 
                    `accounts`.`account_name`, 
                    `accounts`.`account_group`,
                    IFNULL(journals_items.journal_item_debit,0) AS debit, 
                    IFNULL(journals_items.journal_item_credit,0) AS credit
                    FROM `journals_items`
                    LEFT JOIN `accounts` ON `journals_items`.`journal_item_account_id` = `accounts`.`account_id`
                    LEFT JOIN `types` ON journal_item_type=type_type AND type_for=3
                    LEFT JOIN `journals` ON journal_item_journal_id=journal_id
                    LEFT JOIN `trans` ON journal_item_trans_id=trans_id     
                    WHERE `journal_item_branch_id` = vBRANCH_ID 
                    AND `journal_item_date` > @running_date_start
                    AND `journal_item_date` < @running_date_end
                    AND `journal_item_flag`=1 
                    -- AND (
                        -- CASE
                            -- WHEN vSEARCH IS NULL OR LENGTH(vSEARCH)= 0 THEN CONCAT('')
                            -- ELSE CONCAT(' AND `account_name`=',vSEARCH,'')
                        -- END
                    -- )
                    ORDER BY `journal_item_date` ASC, `journal_item_group_session` ASC, `journal_item_debit` DESC;
                DECLARE CONTINUE HANDLER FOR NOT FOUND SET mFINISHED = 1;           
                
                OPEN mACTION_CURSOR;
                LOOP_1: LOOP
                    FETCH mACTION_CURSOR INTO mJOURNAL_ITEM_ID, mJOURNAL_DATE, mJOURNAL_NOTE, mJOURNAL_GROUP_SESSION, mTYPE_ID, mORDER_ID, mTYPE_NAME, mJOURNAL_ID, mJOURNAL_NUMBER, mTRANS_ID, mTRANS_SESSION, mTRANS_NUMBER, mACCOUNT_ID, mACCOUNT_CODE, mACCOUNT_NAME, mACCOUNT_GROUP, mDEBIT, mCREDIT;                
                    IF mFINISHED = 1 THEN
                        LEAVE LOOP_1;
                    END IF;       

                    IF mTYPE_ID = 66 OR mTYPE_ID = 77 THEN /* Only Uang Muka */
                        SELECT order_number INTO mORDER_NUMBER FROM orders WHERE order_id=mORDER_ID;
                        SET @DocumentNumber = mORDER_NUMBER;
                    ELSE
                        IF mTRANS_NUMBER IS NOT NULL THEN
                            SET @DocumentNumber = mTRANS_NUMBER;
                        END IF;
                        IF mJOURNAL_NUMBER IS NOT NULL THEN 
                            SET @DocumentNumber = mJOURNAL_NUMBER;
                        END IF;
                    END IF;
                    
                    SET @SetText = CONCAT(mTYPE_NAME,' #',@DocumentNumber);
                    INSERT INTO journals_temp(`journal_item_id`,`journal_text`,`journal_item_date`,`journal_item_note`,`journal_item_group_session`,`type_id`,`order_id`,`type_name`,`journal_id`,`journal_number`,`trans_id`,`trans_session`,`trans_number`,`account_id`,`account_group`,`account_code`,`account_name`,`debit`,`credit`,`running_date`,`period_date`)
                    VALUES(mJOURNAL_ITEM_ID,@SetText,mJOURNAL_DATE,mJOURNAL_NOTE,mJOURNAL_GROUP_SESSION,mTYPE_ID,mORDER_ID,mTYPE_NAME,mJOURNAL_ID,mJOURNAL_NUMBER,mTRANS_ID,mTRANS_SESSION,mTRANS_NUMBER,mACCOUNT_ID,mACCOUNT_GROUP,mACCOUNT_CODE,mACCOUNT_NAME,mDEBIT,mCREDIT,CONCAT(@running_date_start,' to ',@running_date_end),CONCAT(vSTART,' to ',vEND));
                END LOOP LOOP_1;                            
            END BLOCK_A;

            BLOCK_A2:BEGIN /* Updating Session n Contact */
                UPDATE `journals_temp` 
                LEFT JOIN `journals` ON `journals_temp`.`journal_id`=`journals`.`journal_id`
                LEFT JOIN `contacts` ON `journals`.`journal_contact_id`=`contacts`.`contact_id`
                LEFT JOIN `types` ON `journals_temp`.`type_id`=`types`.`type_type` AND type_for=3
                SET `journals_temp`.`contact_name`=`contacts`.`contact_name`,
                `journals_temp`.`journal_session`=`journals`.`journal_session`
                WHERE `journals_temp`.`journal_id` IS NOT NULL;
                
                UPDATE `journals_temp` 
                LEFT JOIN `trans` ON `journals_temp`.`trans_id`=`trans`.`trans_id`     
                LEFT JOIN `contacts` ON `trans`.`trans_contact_id`=`contacts`.`contact_id`                
                LEFT JOIN `types` ON `journals_temp`.`type_id`=`types`.`type_type` AND type_for=3
                SET `journals_temp`.`contact_name`=`contacts`.`contact_name`,      
                `journals_temp`.`trans_session`=`trans`.`trans_session`                          
                WHERE `journals_temp`.`trans_id` IS NOT NULL;               
            END BLOCK_A2;

            BLOCK_B:BEGIN /* Insert Total Debit, Credit From All */
                DECLARE mDEBIT DOUBLE(18,2);
                DECLARE mCREDIT DOUBLE(18,2);
                DECLARE mFOUND INT(5);
                SELECT COUNT(*) INTO mFOUND FROM journals_temp;
                IF mFOUND > 0 THEN
                    SELECT IFNULL(SUM(debit),0), IFNULL(SUM(credit),0) INTO mDEBIT, mCREDIT FROM journals_temp;
                    INSERT INTO journals_temp(`journal_item_group_session`,`journal_item_date`,`account_code`,`account_name`,`debit`,`credit`)
                    VALUES(CONCAT('TOTAL'),'','','',mDEBIT,mCREDIT);        
                END IF;
            END BLOCK_B;

            BLOCK_B1:BEGIN
                DECLARE mCOUNT INT(5);
                SELECT COUNT(*) INTO mCOUNT FROM journals_temp WHERE order_id IS NOT NULL;
                IF mCOUNT > 0 THEN
                    UPDATE journals_temp AS j LEFT JOIN orders AS o ON j.order_id=o.order_id
                    SET j.trans_number=o.order_number
                    WHERE j.order_id IS NOT NULL; 
                END IF;
            END BLOCK_B1;

            BLOCK_C:BEGIN /* Count Data */
                DECLARE mTOTAL_DATA BIGINT(255);
                /* Count All Data*/
                SELECT IFNULL(COUNT(journal_item_group_session),0) INTO mTOTAL_DATA FROM journals_temp GROUP BY journal_item_group_session LIMIT 1;
                UPDATE journals_temp 
                SET total_data=mTOTAL_DATA, 
                `status`=CASE WHEN mTOTAL_DATA > 0 THEN 1 ELSE 0 END, 
                `message`=CASE WHEN mTOTAL_DATA > 0 THEN 'Data found' ELSE 'Data not found' END,
                `search`=CASE WHEN vSEARCH != '' THEN vSEARCH ELSE '-' END;     
            END BLOCK_C;

            BLOCK_D:BEGIN /* Count Data */    
                -- UPDATE journals_temp SET balance = (
                -- CASE WHEN IFNULL(SUM(debit),0) - IFNULL(SUM(kredit),0) < 1 THEN IFNULL(SUM(debit),0) ELSE 1 END)
                -- GROUP BY journal_item_group_session;
                UPDATE journals_temp AS j LEFT JOIN (
                    SELECT journal_item_group_session, 
                        CASE 
                            WHEN IFNULL(SUM(debit),0) - IFNULL(SUM(credit),0) < 1 THEN IFNULL(SUM(debit),0) 
                            ELSE 1 
                        END AS total
                    FROM journals_temp
                    GROUP BY journal_item_group_session
                ) s ON j.journal_item_group_session=s.journal_item_group_session
                SET j.balance = s.total, j.journal_text=CONCAT(j.journal_text,', Rp ',s.total);
            END BLOCK_D;

            SELECT journals_temp.*, DATE_FORMAT(journal_item_date, "%d/%m/%Y, %H:%i") AS journal_item_date_format FROM journals_temp;
        ELSEIF vACTION = 2 THEN /* Buku Besar */
        
            DROP TEMPORARY TABLE IF EXISTS journals_temp;
            CREATE TEMPORARY TABLE journals_temp (
                `temp_id` BIGINT NOT NULL AUTO_INCREMENT,
                `journal_item_id` BIGINT(50),
                `journal_item_date` VARCHAR(255),
                `journal_item_note` VARCHAR(255),
                `journal_id` BIGINT(50),
                `trans_id` BIGINT(50),
                `order_id` BIGINT(50),                
                `type_id` BIGINT(50),               
                `type_name` VARCHAR(255), 
                `journal_number` VARCHAR(255), 
                `trans_number` VARCHAR(255), 
                `contact_name` VARCHAR(255),
                `account_id` BIGINT(50),
                `account_group` INT(5),
                `account_code` VARCHAR(255),
                `account_name` VARCHAR(255),
                `debit` DOUBLE(18,2) DEFAULT 0,
                `credit` DOUBLE(18,2) DEFAULT 0,
                `balance` DOUBLE(18,2) DEFAULT 0,
                `journal_session` VARCHAR(255),
                `trans_session` VARCHAR(255),                                
                `status` INT(5) DEFAULT 0,
                `message` VARCHAR(255),
                `total_data` INT(50),
                `search` VARCHAR(255),
                `running_date` VARCHAR(255),
                `period_date` VARCHAR(255),                
                PRIMARY KEY (`temp_id`),
                INDEX `JOURNAL_ITEM_ID`(`journal_item_id`) USING BTREE
            ) ENGINE=MEMORY;    
            
            SELECT MIN(journal_item_date) INTO mDATE_START_APP FROM journals_items WHERE journal_item_branch_id=vBRANCH_ID;
            SET @start_date = mDATE_START_APP - INTERVAL 1 DAY;
            SET @end_date = CONCAT(vSTART,' 23:59:59') - INTERVAL 1 DAY;
            SET @running_date_start = CONCAT(vSTART,' 00:00:00');
            SET @running_date_end = CONCAT(vEND,' 23:59:59');
            
            SET @start_date_journal = @start_date;
            SET @end_date_journal = @end_date;
            
            SET @start_date = DATE_FORMAT(@start_date, "%Y%m%d%H%i%s"); 
            SET @end_date = DATE_FORMAT(@end_date, "%Y%m%d%H%i%s");
            
            BLOCK_A:BEGIN /* Get Start Balance */
                DECLARE mACCOUNT_ID INT(50);
                DECLARE mACCOUNT_CODE VARCHAR(255);
                DECLARE mACCOUNT_NAME VARCHAR(255);    
                DECLARE mACCOUNT_GROUP INT(5);
                DECLARE mDEBIT DOUBLE(18,2) DEFAULT 0;
                DECLARE mCREDIT DOUBLE(18,2) DEFAULT 0;
                DECLARE mBALANCE DOUBLE(18,2) DEFAULT 0;

                DECLARE mFINISHED INTEGER;
                DECLARE mACTION_CURSOR CURSOR FOR
                    SELECT account_id, account_group, account_code, account_name,
                        CASE
                            WHEN (account_group = 1) OR (account_group = 5) THEN IFNULL(SUM(journal_item_debit)-SUM(journal_item_credit),0)
                            ELSE IFNULL(SUM(journal_item_credit)-SUM(journal_item_debit),0)
                        END
                        AS start_balance                        
                    FROM journals_items 
                    RIGHT JOIN accounts ON journal_item_account_id=account_id
                    WHERE `journal_item_branch_id` = vBRANCH_ID
                    AND `journal_item_date` > @start_date
                    AND `journal_item_date` < @end_date
                    AND `journal_item_flag`=1
                    AND `journal_item_account_id`=vACCOUNT_ID;
                DECLARE CONTINUE HANDLER FOR NOT FOUND SET mFINISHED = 1;           
                OPEN mACTION_CURSOR;

                LOOP_1: LOOP                                            
                    FETCH mACTION_CURSOR INTO mACCOUNT_ID, mACCOUNT_GROUP, mACCOUNT_CODE, mACCOUNT_NAME, mBALANCE;
                    IF mFINISHED = 1 THEN
                        LEAVE LOOP_1;
                    END IF;       
                    SET @type_name = 'Saldo Awal';
                    INSERT INTO journals_temp(`type_name`,`journal_item_date`,`account_id`,`account_group`,`account_code`,`account_name`,`balance`,`running_date`,`period_date`)
                    VALUES(@type_name, @end_date_journal, mACCOUNT_ID, mACCOUNT_GROUP, mACCOUNT_CODE, mACCOUNT_NAME,mBALANCE,CONCAT(@running_date_start,' to ',@running_date_end),CONCAT(@start_date,' to ',@end_date));
                END LOOP LOOP_1;     
            END BLOCK_A;    

            BLOCK_B:BEGIN /* Get Running Balance */
                DECLARE mACCOUNT_ID INT(50);
                DECLARE mACCOUNT_CODE VARCHAR(255);
                DECLARE mACCOUNT_NAME VARCHAR(255);    
                DECLARE mACCOUNT_GROUP INT(5);
                
                DECLARE mJOURNAL_ITEM_ID BIGINT(50);
                DECLARE mJOURNAL_DATE DATETIME;
                DECLARE mJOURNAL_NOTE TEXT;
                
                DECLARE mTYPE_ID INT(5);
                DECLARE mTYPE_NAME VARCHAR(255);
                
                DECLARE mJOURNAL_ID BIGINT(50);
                DECLARE mTRANS_ID BIGINT(50);
                DECLARE mORDER_ID BIGINT(50);                
                DECLARE mJOURNAL_NUMBER VARCHAR(255);
                DECLARE mTRANS_NUMBER VARCHAR(255);
                
                DECLARE mDEBIT DOUBLE(18,2) DEFAULT 0;
                DECLARE mCREDIT DOUBLE(18,2) DEFAULT 0;
                DECLARE mBALANCE DOUBLE(18,2) DEFAULT 0;
                
                DECLARE mTOTAL_DATA INT(50) DEFAULT 0;
                DECLARE mFINISHED INTEGER;
                DECLARE mACTION_CURSOR CURSOR FOR
                    SELECT 
                    `journals_items`.`journal_item_id`,
                    `journals_items`.`journal_item_date`, 
                    `journals_items`.`journal_item_note`, 
                    `journals_items`.`account_id` AS `account_id`, 
                    `journals_items`.`account_code`, 
                    `journals_items`.`account_name`, 
                    `journals_items`.`account_group`,
                    `journals_items`.`journal_item_type`,
                    `journals_items`.`journal_item_journal_id`,
                    `journals_items`.`journal_item_trans_id`,
                    `journals_items`.`journal_item_order_id`,                    
                    IFNULL(journals_items.journal_item_debit,0) AS debit, 
                    IFNULL(journals_items.journal_item_credit,0) AS credit,
                    (
                        SELECT CASE
                        WHEN (`journals_items`.`account_group` = 1) OR (`journals_items`.`account_group` = 5) THEN @saldo_awal := @saldo_awal + journals_items.journal_item_debit - journals_items.journal_item_credit
                        ELSE @saldo_awal := @saldo_awal + journals_items.journal_item_credit - journals_items.journal_item_debit
                        END
                    ) AS balance                
                    FROM (
                        SELECT @saldo_awal := (
                            SELECT IFNULL(balance,0) FROM journals_temp WHERE account_id=vACCOUNT_ID AND type_name='Saldo Awal'
                        )
                    ) AS start_balance
                    CROSS JOIN (
                        SELECT 
                        journals_items.journal_item_branch_id, journals_items.journal_item_flag, journals_items.journal_item_account_id,
                        journals_items.journal_item_journal_id, journals_items.journal_item_trans_id, journals_items.journal_item_order_id, journals_items.journal_item_type,
                        journals_items.journal_item_id, journals_items.journal_item_date, journals_items.journal_item_note, 
                            journals_items.journal_item_debit, journals_items.journal_item_credit, 
                            accounts.account_id, accounts.account_code, accounts.account_name, accounts.account_group
                        FROM journals_items
                        LEFT JOIN `accounts` ON `journals_items`.`journal_item_account_id` = `accounts`.`account_id`
                    ) AS `journals_items`
                    WHERE `journals_items`.`journal_item_branch_id` = vBRANCH_ID 
                    AND `journals_items`.`journal_item_date` > @running_date_start
                    AND `journals_items`.`journal_item_date` < @running_date_end
                    AND `journals_items`.`journal_item_flag`=1
                    AND `journals_items`.`journal_item_account_id`= vACCOUNT_ID 
                    ORDER BY `journals_items`.`journal_item_date` ASC;
                DECLARE CONTINUE HANDLER FOR NOT FOUND SET mFINISHED = 1;           
                
                OPEN mACTION_CURSOR;
                LOOP_1: LOOP                   
                    FETCH mACTION_CURSOR INTO mJOURNAL_ITEM_ID, mJOURNAL_DATE, mJOURNAL_NOTE, mACCOUNT_ID, mACCOUNT_CODE, mACCOUNT_NAME, mACCOUNT_GROUP, mTYPE_ID, mJOURNAL_ID, mTRANS_ID, mORDER_ID, mDEBIT, mCREDIT, mBALANCE;               
                    IF mFINISHED = 1 THEN
                        LEAVE LOOP_1;
                    END IF;       
                    INSERT INTO journals_temp(`journal_item_id`,`journal_item_date`,`journal_item_note`,`type_name`,`type_id`,`journal_id`,`trans_id`,`order_id`,`account_id`,`account_group`,`account_code`,`account_name`,`debit`,`credit`,`balance`,`running_date`,`period_date`)
                    VALUES(mJOURNAL_ITEM_ID,mJOURNAL_DATE,mJOURNAL_NOTE,mTYPE_NAME,mTYPE_ID,mJOURNAL_ID,mTRANS_ID,mORDER_ID,mACCOUNT_ID,mACCOUNT_GROUP,mACCOUNT_CODE,mACCOUNT_NAME,mDEBIT,mCREDIT,mBALANCE,CONCAT(@running_date_start,' to ',@running_date_end),CONCAT(@start_date,' to ',@end_date));
                END LOOP LOOP_1;     
            END BLOCK_B;
            
            BLOCK_C:BEGIN
                /* DECLARE mBALANCE DOUBLE(18,2);
                SELECT balance INTO mBALANCE FROM journals_temp ORDER BY temp_id DESC LIMIT 1;
                SET @type_name = 'Saldo Akhir';
                INSERT INTO journals_temp(`type_name`,`journal_item_date`,`balance`)
                VALUES(@type_name, @end_date_journal,mBALANCE); */
            END BLOCK_C;

            BLOCK_D:BEGIN /* Updating Trans n Journal Type */
                UPDATE `journals_temp` 
                LEFT JOIN `journals` ON `journals_temp`.`journal_id`=`journals`.`journal_id`
                LEFT JOIN `contacts` ON `journals`.`journal_contact_id`=`contacts`.`contact_id`
                LEFT JOIN `types` ON `journals_temp`.`type_id`=`types`.`type_type` AND type_for=3
                SET `journals_temp`.`journal_number`=`journals`.`journal_number`, 
                `journals_temp`.`type_name`=`types`.`type_name`,
                `journals_temp`.`contact_name`=`contacts`.`contact_name`,
                `journals_temp`.`journal_session`=`journals`.`journal_session`
                WHERE `journals_temp`.`journal_id` IS NOT NULL;
                
                UPDATE `journals_temp` 
                LEFT JOIN `trans` ON `journals_temp`.`trans_id`=`trans`.`trans_id`     
                LEFT JOIN `contacts` ON `trans`.`trans_contact_id`=`contacts`.`contact_id`                
                LEFT JOIN `types` ON `journals_temp`.`type_id`=`types`.`type_type` AND type_for=3
                SET `journals_temp`.`trans_number`=`trans`.`trans_number`, 
                `journals_temp`.`type_name`=`types`.`type_name`,
                `journals_temp`.`contact_name`=`contacts`.`contact_name`,      
                `journals_temp`.`trans_session`=`trans`.`trans_session`                          
                WHERE `journals_temp`.`trans_id` IS NOT NULL;               
            END BLOCK_D;
            
            BLOCK_D1:BEGIN /* Updating Order*/
                DECLARE mCOUNT INT(5);
                SELECT COUNT(*) INTO mCOUNT FROM journals_temp WHERE order_id IS NOT NULL;
                IF mCOUNT > 0 THEN
                    UPDATE journals_temp AS j 
                    JOIN orders AS o ON j.order_id=o.order_id
                    JOIN `types` AS t ON j.type_id=t.type_type AND type_for=3
                    SET j.trans_number=o.order_number, j.type_name=t.type_name
                    WHERE j.order_id IS NOT NULL;
                END IF;
            END BLOCK_D1;

            BLOCK_E:BEGIN
                DECLARE mTOTAL_DATA BIGINT(255);
                /* Count All Data*/
                SELECT IFNULL(COUNT(*),0) INTO mTOTAL_DATA FROM journals_temp WHERE account_id=vACCOUNT_ID;
                UPDATE journals_temp 
                SET total_data=mTOTAL_DATA, 
                `status`=CASE WHEN mTOTAL_DATA > 0 THEN 1 ELSE 0 END, 
                `message`=CASE WHEN mTOTAL_DATA > 0 THEN 'Data found' ELSE 'Data not found' END 
                WHERE account_id=vACCOUNT_ID;       
            END BLOCK_E;
            SELECT journals_temp.*, DATE_FORMAT(journal_item_date, "%d/%m/%Y, %H:%i") AS journal_item_date_format FROM journals_temp;
        ELSEIF vACTION = 3 THEN /* Neraca Saldo, Kertas Keja, Laba Rugi, Neraca */
            DROP TEMPORARY TABLE IF EXISTS journals_temp;
            CREATE TEMPORARY TABLE journals_temp (
                `temp_id` BIGINT NOT NULL AUTO_INCREMENT,           
                `parent_id` BIGINT(50),
                `account_id` BIGINT(50),
                `account_group` INT(5),
                `group_sub_id` INT(5),                
                `group_sub` VARCHAR(255),           
                `account_code` VARCHAR(255),
                `account_name` VARCHAR(255),
                `start_debit` DOUBLE(18,2) DEFAULT 0,
                `start_credit` DOUBLE(18,2) DEFAULT 0,
                    `movement_debit` DOUBLE(18,2) DEFAULT 0,
                    `movement_credit` DOUBLE(18,2) DEFAULT 0,
                `end_debit` DOUBLE(18,2) DEFAULT 0,
                `end_credit` DOUBLE(18,2) DEFAULT 0,
                    `profit_loss_debit` DOUBLE(18,2) DEFAULT 0,
                    `profit_loss_credit` DOUBLE(18,2) DEFAULT 0,
                    `profit_loss_end` DOUBLE(18,2) DEFAULT 0,
                `balance_debit` DOUBLE(18,2) DEFAULT 0,
                `balance_credit` DOUBLE(18,2) DEFAULT 0,
                `balance_end` DOUBLE(18,2) DEFAULT 0,   
                `start_date` VARCHAR(255),  
                `running_date` VARCHAR(255),
                `period_date` VARCHAR(255),                          
                `status` INT(5) DEFAULT 0,
                `message` VARCHAR(255),
                `total_data` INT(50),
                `search` VARCHAR(255),               
                PRIMARY KEY (`temp_id`),
                INDEX `ACCOUNT_ID`(`account_id`) USING BTREE
            ) ENGINE=MEMORY;    
            
            SELECT MIN(journal_item_date) INTO mDATE_START_APP FROM journals_items WHERE journal_item_branch_id=vBRANCH_ID;

            SET @run_date_start = DATE_FORMAT(CONCAT(vSTART,' 00:00:00'), "%Y%m%d%H%i%s"); -- 2023-02-09 00:00:00
            SET @run_date_end   = DATE_FORMAT(CONCAT(vEND,' 23:59:59'), "%Y%m%d%H%i%s");   -- 2023-02-28 23:59:59
            
            SET @start_date     = mDATE_START_APP - INTERVAL 1 DAY;                        -- 2023-02-07 10:41:37
            SET @start_date     = DATE_FORMAT(@start_date, "%Y%m%d%H%i%s");                -- 2023-02-07 10:41:37
            -- SET @end_date       = DATE_FORMAT(CONCAT(vSTART,' 23:59:59'), "%Y%m%d%H%i%s"); -- 2023-02-09 23:59:59
            SET @end_date       = DATE_FORMAT(CONCAT(vSTART,' 23:59:59'), "%Y%m%d%H%i%s") - INTERVAL 1 DAY; -- 2023-02-09 23:59:59            

            BLOCK_A:BEGIN /* DONE Get Group of Account */
                DECLARE mPARENT_ID BIGINT(50);
                -- DECLARE mACCOUNT_ID BIGINT(50);
                -- DECLARE mACCOUNT_CODE VARCHAR(255);
                -- DECLARE mACCOUNT_GROUP INT(5);
                DECLARE mACCOUNT_NAME VARCHAR(255);
                -- DECLARE mACCOUNT_GROUP_SUB VARCHAR(255);

                DECLARE mTOTAL_DATA INT(50) DEFAULT 0;
                DECLARE mFINISHED INTEGER;
                DECLARE mACTION_CURSOR CURSOR FOR
                    SELECT 
                    account_group AS group_id,
                    CASE 
                        WHEN account_group=1 THEN 'Aset'
                        WHEN account_group=2 THEN 'Kewajiban'
                        WHEN account_group=3 THEN 'Ekuitas'
                        WHEN account_group=4 THEN 'Pendapatan'
                        WHEN account_group=5 THEN 'Beban'
                        ELSE 'Lainnya'
                    END AS group_name
                    FROM accounts 
                    WHERE account_branch_id=vBRANCH_ID
                    GROUP BY account_group;
                DECLARE CONTINUE HANDLER FOR NOT FOUND SET mFINISHED = 1;           
                
                OPEN mACTION_CURSOR;
                LOOP_1: LOOP
                    FETCH mACTION_CURSOR INTO mPARENT_ID, mACCOUNT_NAME;                
                    IF mFINISHED = 1 THEN
                        LEAVE LOOP_1;
                    END IF;       
                    
                    INSERT INTO journals_temp(`parent_id`,`account_group`,`account_name`)
                    VALUES(mPARENT_ID,mPARENT_ID,mACCOUNT_NAME);
                END LOOP LOOP_1;                    
            END BLOCK_A;

            BLOCK_B:BEGIN /* DONE Get Account From Group By Journal Found */
                DECLARE mPARENT_ID BIGINT(50);
                DECLARE mACCOUNT_ID BIGINT(50);
                DECLARE mACCOUNT_CODE VARCHAR(255);
                DECLARE mACCOUNT_GROUP INT(5);
                DECLARE mACCOUNT_NAME VARCHAR(255);
                DECLARE mGROUP_SUB_ID INT(255);
                DECLARE mGROUP_SUB VARCHAR(255);
                
                DECLARE mSTART_DEBIT  DOUBLE(18,2) DEFAULT 0;
                DECLARE mSTART_CREDIT DOUBLE(18,2) DEFAULT 0;
                DECLARE mMOVEMENT_DEBIT DOUBLE(18,2) DEFAULT 0;
                DECLARE mMOVEMENT_CREDIT DOUBLE(18,2) DEFAULT 0;
                DECLARE mEND_DEBIT DOUBLE(18,2) DEFAULT 0;
                DECLARE mEND_CREDIT DOUBLE(18,2) DEFAULT 0;
                DECLARE mTOTAL_DATA INT(50) DEFAULT 0;
                DECLARE mFINISHED INTEGER;
                DECLARE mACTION_CURSOR CURSOR FOR
                    SELECT accounts.account_group, account_group_sub, account_group_sub_name, accounts.`account_id`, accounts.account_code, accounts.account_name 
                    FROM journals_items 
                    LEFT JOIN accounts ON journal_item_account_id=account_id 
                    WHERE journal_item_branch_id=vBRANCH_ID
                    GROUP BY journal_item_account_id;
                DECLARE CONTINUE HANDLER FOR NOT FOUND SET mFINISHED = 1;           
                
                OPEN mACTION_CURSOR;
                LOOP_1: LOOP
                    FETCH mACTION_CURSOR INTO mPARENT_ID, mGROUP_SUB_ID, mGROUP_SUB, mACCOUNT_ID, mACCOUNT_CODE, mACCOUNT_NAME;                
                    IF mFINISHED = 1 THEN
                        LEAVE LOOP_1;
                    END IF;       
                    
                    INSERT INTO journals_temp(`parent_id`,`account_group`,`group_sub_id`,`group_sub`,`account_id`,`account_code`,`account_name`)
                    VALUES(mPARENT_ID,mPARENT_ID,mGROUP_SUB_ID,mGROUP_SUB,mACCOUNT_ID,mACCOUNT_CODE,CONCAT('    ',mACCOUNT_NAME));
                END LOOP LOOP_1;                                                        
            END BLOCK_B;
            
            BLOCK_C:BEGIN /* DONE Update Start Balance @start_date, @end_date */
                DECLARE mPARENT_ID BIGINT(50);
                DECLARE mACCOUNT_ID BIGINT(50);
                DECLARE mACCOUNT_CODE VARCHAR(255);
                DECLARE mACCOUNT_GROUP INT(5);
                DECLARE mACCOUNT_NAME VARCHAR(255);
                DECLARE mGROUP_SUB VARCHAR(255);
                
                DECLARE mSTART_DEBIT  DOUBLE(18,2) DEFAULT 0;
                DECLARE mSTART_CREDIT DOUBLE(18,2) DEFAULT 0;
                DECLARE mMOVEMENT_DEBIT DOUBLE(18,2) DEFAULT 0;
                DECLARE mMOVEMENT_CREDIT DOUBLE(18,2) DEFAULT 0;
                DECLARE mEND_DEBIT DOUBLE(18,2) DEFAULT 0;
                DECLARE mEND_CREDIT DOUBLE(18,2) DEFAULT 0;
                DECLARE mTOTAL_DATA INT(50) DEFAULT 0;
                DECLARE mFINISHED INTEGER;
                DECLARE mACTION_CURSOR CURSOR FOR
                    SELECT journal_item_account_id, account_group,
                    -- IFNULL(SUM(journal_item_debit),0) AS debit, 
                    -- IFNULL(SUM(journal_item_credit),0) AS credit,
                    (CASE
                        WHEN account_group = 1 THEN (CASE
                            WHEN journal_item_debit - journal_item_credit > 0 THEN SUM(journal_item_debit-journal_item_credit)
                            WHEN journal_item_debit - journal_item_credit < 1 THEN 0
                            ELSE 0 END
                        )
                        WHEN account_group = 3 THEN (CASE
                            WHEN journal_item_debit - journal_item_credit > 0 THEN SUM(journal_item_debit-journal_item_credit)
                            WHEN journal_item_debit - journal_item_credit < 1 THEN 0
                            ELSE 0 END
                        )       
                        WHEN account_group = 4 THEN (CASE -- DONE
                            WHEN IFNULL(SUM(journal_item_credit-journal_item_debit),0) < 0 THEN
                                ABS(SUM(journal_item_credit-journal_item_debit))
                            ELSE 0
                            END
                        )
                        WHEN account_group = 5 THEN (CASE -- DONE
                            WHEN IFNULL(SUM(journal_item_debit-journal_item_credit),0) > 0 THEN
                                ABS(SUM(journal_item_debit-journal_item_credit))
                            ELSE 0
                            END
                        )       
                        ELSE 0
                        END
                    ) AS debit,
                    (CASE
                        WHEN account_group = 1 THEN (CASE
                            WHEN journal_item_debit - journal_item_credit > 0 THEN 0
                            WHEN journal_item_debit - journal_item_credit < 1 THEN ABS(SUM(journal_item_debit-journal_item_credit))
                            ELSE 0 END
                        )
                        WHEN account_group = 2 THEN IFNULL(SUM(journal_item_credit-journal_item_debit),0)
                        WHEN account_group = 3 THEN (CASE
                            WHEN journal_item_debit - journal_item_credit > 0 THEN 0
                            WHEN journal_item_debit - journal_item_credit < 1 THEN ABS(SUM(journal_item_debit-journal_item_credit))
                            ELSE 0 END
                        )
                        WHEN account_group = 4 THEN (CASE -- DONE
                            WHEN IFNULL(SUM(journal_item_credit-journal_item_debit),0) > 0 THEN
                                ABS(SUM(journal_item_credit-journal_item_debit))
                            ELSE 0
                            END
                        )
                        WHEN account_group = 5 THEN (CASE -- DONE
                            WHEN IFNULL(SUM(journal_item_debit-journal_item_credit),0) < 0 THEN
                                ABS(SUM(journal_item_debit-journal_item_credit))
                            ELSE 0
                            END
                        )
                        ELSE 0
                        END
                    ) AS credit             
                    FROM journals_items
                    LEFT JOIN accounts ON journal_item_account_id=account_id
                    WHERE `journal_item_branch_id` = vBRANCH_ID 
                    AND `journal_item_date` > @start_date
                    AND `journal_item_date` < @end_date
                    AND `journal_item_flag`=1 
                    GROUP BY journal_item_account_id;
                DECLARE CONTINUE HANDLER FOR NOT FOUND SET mFINISHED = 1;           
                
                OPEN mACTION_CURSOR;
                LOOP_1: LOOP
                    FETCH mACTION_CURSOR INTO mACCOUNT_ID, mACCOUNT_GROUP, mSTART_DEBIT, mSTART_CREDIT;             
                    IF mFINISHED = 1 THEN
                        LEAVE LOOP_1;
                    END IF;       
                UPDATE journals_temp SET 
                    start_debit=mSTART_DEBIT, 
                    start_credit=mSTART_CREDIT 
                WHERE account_id=mACCOUNT_ID;    
                END LOOP LOOP_1;
            END BLOCK_C;
            
            BLOCK_D:BEGIN /* DONE Update Movement Balance @running_date_start @running_date_end*/
                DECLARE mPARENT_ID BIGINT(50);
                DECLARE mACCOUNT_ID BIGINT(50);
                DECLARE mACCOUNT_CODE VARCHAR(255);
                DECLARE mACCOUNT_GROUP INT(5);
                DECLARE mACCOUNT_NAME VARCHAR(255);
                DECLARE mGROUP_SUB VARCHAR(255);
                
                DECLARE mSTART_DEBIT  DOUBLE(18,2) DEFAULT 0;
                DECLARE mSTART_CREDIT DOUBLE(18,2) DEFAULT 0;
                DECLARE mMOVEMENT_DEBIT DOUBLE(18,2) DEFAULT 0;
                DECLARE mMOVEMENT_CREDIT DOUBLE(18,2) DEFAULT 0;
                DECLARE mEND_DEBIT DOUBLE(18,2) DEFAULT 0;
                DECLARE mEND_CREDIT DOUBLE(18,2) DEFAULT 0;
                DECLARE mTOTAL_DATA INT(50) DEFAULT 0;
                DECLARE mFINISHED INTEGER;
                DECLARE mACTION_CURSOR CURSOR FOR
                    SELECT journal_item_account_id, account_group, IFNULL(SUM(journal_item_debit),0) AS debit, IFNULL(SUM(journal_item_credit),0) AS credit
                    FROM journals_items
                    LEFT JOIN accounts ON journal_item_account_id=account_id
                    WHERE `journal_item_branch_id` = vBRANCH_ID 
                    AND `journal_item_date` > @run_date_start
                    AND `journal_item_date` < @run_date_end
                    AND `journal_item_flag`=1 
                    GROUP BY journal_item_account_id;
                DECLARE CONTINUE HANDLER FOR NOT FOUND SET mFINISHED = 1;           
                
                OPEN mACTION_CURSOR;
                LOOP_1: LOOP
                    FETCH mACTION_CURSOR INTO mACCOUNT_ID, mACCOUNT_GROUP, mSTART_DEBIT, mSTART_CREDIT;             
                    IF mFINISHED = 1 THEN
                        LEAVE LOOP_1;
                    END IF;       
                UPDATE journals_temp SET movement_debit=CASE 
                        WHEN account_group = 1 THEN mSTART_DEBIT
                        WHEN account_group = 2 THEN mSTART_DEBIT
                        WHEN account_group = 3 THEN mSTART_DEBIT
                        WHEN account_group = 4 THEN mSTART_DEBIT
                        WHEN account_group = 5 THEN mSTART_DEBIT
                        ELSE 0
                    END, movement_credit=CASE 
                        WHEN account_group = 1 THEN mSTART_CREDIT
                        WHEN account_group = 2 THEN mSTART_CREDIT
                        WHEN account_group = 3 THEN mSTART_CREDIT
                        WHEN account_group = 4 THEN mSTART_CREDIT
                        WHEN account_group = 5 THEN mSTART_CREDIT
                        ELSE 0
                    END
                WHERE account_id=mACCOUNT_ID;    
                END LOOP LOOP_1;                
            END BLOCK_D;
            
            BLOCK_D1:BEGIN /* Update start_date, running_date */        
                UPDATE journals_temp SET `start_date`=
                CONCAT(
                IFNULL(DATE_FORMAT(@start_date,"%Y-%m-%d %H:%i:%s"),' ?'),
                ' <> ',
                IFNULL(DATE_FORMAT(@end_date,"%Y-%m-%d %H:%i:%s"),' ?')),
                `running_date`=CONCAT(
                    IFNULL(DATE_FORMAT(@run_date_start,"%Y-%m-%d %H:%i:%s"),'?'),' <> ',IFNULL(DATE_FORMAT(@run_date_end,"%Y-%m-%d %H:%i:%s"),'?')
                );  
            END BLOCK_D1;
            
            BLOCK_D1:BEGIN /* Tricky for Profit Lost set debit and credit = 0 */        
                -- UPDATE journals_temp SET `start_debit`='0.00', `start_credit`='0.00' WHERE account_group > 3;
            END BLOCK_D1;

            BLOCK_E:BEGIN /* DONE Update end_debit and end_credit  */
                UPDATE journals_temp SET end_debit = CASE
                    WHEN account_group = 1 THEN 
                        CASE 
                            WHEN (start_debit + movement_debit) > (start_credit + movement_credit) THEN
                                (start_debit + movement_debit) - (start_credit + movement_credit)
                            WHEN (start_debit + movement_debit) < (start_credit + movement_credit) THEN
                                0
                            ELSE 0
                        END    
                    WHEN account_group = 2 THEN 
                        CASE 
                            WHEN (start_debit + movement_debit) > (start_credit + movement_credit) THEN
                                (start_debit + movement_debit) - (start_credit + movement_credit)
                            WHEN (start_debit + movement_debit) < (start_credit + movement_credit) THEN
                                0
                            ELSE 0
                        END                                        
                    WHEN account_group = 3 THEN 
                        CASE 
                            WHEN (start_debit + movement_debit) > (start_credit + movement_credit) THEN
                                (start_debit + movement_debit) - (start_credit + movement_credit)
                            WHEN (start_debit + movement_debit) < (start_credit + movement_credit) THEN
                                0
                            ELSE 0
                        END
                    WHEN account_group = 4 THEN 
                        CASE 
                            WHEN (start_debit + movement_debit) > (start_credit + movement_credit) THEN
                                (start_debit + movement_debit) - (start_credit + movement_credit)
                            WHEN (start_debit + movement_debit) < (start_credit + movement_credit) THEN
                                0
                            ELSE 0
                        END
                    WHEN account_group = 5 THEN 
                        CASE 
                            WHEN (start_debit + movement_debit) > (start_credit + movement_credit) THEN
                                (start_debit + movement_debit) - (start_credit + movement_credit)
                            WHEN (start_debit + movement_debit) < (start_credit + movement_credit) THEN
                                0
                            ELSE 0
                        END
                END, end_credit = CASE
                    WHEN account_group = 1 THEN 
                        CASE 
                            WHEN (start_credit + movement_credit) > (start_debit + movement_debit) THEN
                                (start_credit + movement_credit) - (start_debit + movement_debit)
                            WHEN (start_credit + movement_credit) < (start_debit + movement_debit) THEN
                                0
                            ELSE 0
                        END                    
                    WHEN account_group = 2 THEN 
                        CASE 
                            WHEN (start_credit + movement_credit) > (start_debit + movement_debit) THEN
                                (start_credit + movement_credit) - (start_debit + movement_debit)
                            WHEN (start_credit + movement_credit) < (start_debit + movement_debit) THEN
                                0
                            ELSE 0
                        END
                    WHEN account_group = 3 THEN 
                        CASE 
                            WHEN (start_credit + movement_credit) > (start_debit + movement_debit) THEN
                                (start_credit + movement_credit) - (start_debit + movement_debit)
                            WHEN (start_credit + movement_credit) < (start_debit + movement_debit) THEN
                                0
                            ELSE 0
                        END
                    WHEN account_group = 4 THEN 
                        CASE 
                            WHEN (start_credit + movement_credit) > (start_debit + movement_debit) THEN
                                (start_credit + movement_credit) - (start_debit + movement_debit)
                            WHEN (start_credit + movement_credit) < (start_debit + movement_debit) THEN
                                0
                            ELSE 0
                        END
                    WHEN account_group = 5 THEN 
                        CASE 
                            WHEN (start_credit + movement_credit) > (start_debit + movement_debit) THEN
                                (start_credit + movement_credit) - (start_debit + movement_debit)
                            WHEN (start_credit + movement_credit) < (start_debit + movement_debit) THEN
                                0
                            ELSE 0
                        END
                END;                      
            END BLOCK_E;

            BLOCK_F:BEGIN /* Update profit_loss_debit / profit_loss_credit */  
                -- UPDATE journals_temp SET profit_loss_debit = (CASE
                --     WHEN account_group = 4 THEN (CASE
                --         WHEN (movement_debit) > (movement_credit) THEN
                --             movement_credit - movement_debit
                --         WHEN (movement_debit) < (movement_credit) THEN
                --             0
                --         ELSE 0 END
                --     )
                --     WHEN account_group = 5 THEN (CASE 
                --         WHEN (movement_debit) > (movement_credit) THEN
                --             movement_debit - movement_credit
                --         WHEN (movement_debit) < (movement_credit) THEN
                --             0
                --         ELSE 0 END
                --     )
                --     ELSE 0 END                  
                -- ), profit_loss_credit = (CASE
                --     WHEN account_group = 4 THEN (CASE
                --         WHEN (movement_credit) > (movement_debit) THEN
                --             movement_credit - movement_debit
                --         WHEN (movement_credit) < (movement_debit) THEN
                --             0
                --         ELSE 0 END                    
                --     )
                --     WHEN account_group = 5 THEN (CASE
                --         WHEN (movement_credit) > (movement_debit) THEN
                --             movement_debit - movement_credit
                --         WHEN (movement_credit) < (movement_debit) THEN
                --             0
                --         ELSE 0 END                        
                --     )
                --     ELSE 0 END
                -- );                 
                UPDATE journals_temp SET profit_loss_debit = CASE
                    WHEN (account_group = 4 ) OR (account_group = 5) THEN 
                        end_debit         
                    ELSE 0
                END, profit_loss_credit = CASE
                    WHEN (account_group = 4 ) OR (account_group = 5)  THEN 
                        end_credit             
                    ELSE 0
                END;                                                               
            END BLOCK_F;
            
            BLOCK_G:BEGIN /* Update balance_debit / balance_credit / balance_end */
                UPDATE journals_temp SET balance_debit = CASE
                    WHEN (account_group = 1 ) OR (account_group = 2) OR (account_group = 3) THEN 
                        end_debit         
                    ELSE 0
                END, balance_credit = CASE
                    WHEN (account_group = 1 ) OR (account_group = 2) OR (account_group = 3)  THEN 
                        end_credit             
                    ELSE 0
                END;                                  
            END BLOCK_G;
            
            BLOCK_H:BEGIN /* Update profit_loss_end End & balance_end */
                -- UPDATE journals_temp SET profit_loss_end = (CASE
                --     WHEN account_group = 4 THEN (profit_loss_debit - profit_loss_credit)
                --     WHEN account_group = 5 THEN (profit_loss_debit - profit_loss_credit)
                --     ELSE 0 END)
                -- , balance_end = (CASE
                --     WHEN account_group = 1 THEN (balance_debit - balance_credit)
                --     WHEN account_group = 2 THEN (balance_credit - balance_debit)
                --     WHEN account_group = 3 THEN (balance_credit - balance_debit) ELSE 0 END);
                -- UPDATE journals_temp SET profit_loss_end = (CASE
                --     WHEN account_group = 4 THEN (CASE 
                --         WHEN profit_loss_credit > 0 THEN profit_loss_credit
                --         WHEN profit_loss_debit < 0 THEN profit_loss_debit ELSE 0 END
                --     )
                --     WHEN account_group = 5 THEN (profit_loss_debit - profit_loss_credit)
                --     ELSE 0 END);  
                UPDATE journals_temp SET profit_loss_end = (CASE
                    WHEN account_group = 4 THEN profit_loss_credit - profit_loss_debit
                    WHEN account_group = 5 THEN profit_loss_debit - profit_loss_credit ELSE 0 END
                );
                UPDATE journals_temp SET balance_end = (CASE
                    WHEN account_group = 1 THEN (balance_debit - balance_credit)
                    WHEN account_group = 2 THEN (balance_credit - balance_debit)
                    WHEN account_group = 3 THEN (balance_credit - balance_debit) ELSE 0 END
                );
            END BLOCK_H;
            
            BLOCK_I:BEGIN /* Insert SUM of Journal Temp */
                DECLARE mTOTAL_DATA BIGINT(255);

                INSERT INTO journals_temp (`parent_id`,`account_name`,`start_debit`,`start_credit`,`movement_debit`,`movement_credit`,`end_debit`,`end_credit`,`profit_loss_debit`,`profit_loss_credit`,`profit_loss_end`,`balance_debit`,`balance_credit`,`balance_end`)
                SELECT CONCAT(6), CONCAT('Total'), IFNULL(SUM(`start_debit`),0), IFNULL(SUM(`start_credit`),0), 
                IFNULL(SUM(`movement_debit`),0), IFNULL(SUM(`movement_credit`),0), 
                IFNULL(SUM(`end_debit`),0), IFNULL(SUM(`end_credit`),0),
                IFNULL(SUM(`profit_loss_debit`),0), IFNULL(SUM(`profit_loss_credit`),0), IFNULL(SUM(`profit_loss_end`),0),
                IFNULL(SUM(`balance_debit`),0), IFNULL(SUM(`balance_credit`),0), IFNULL(SUM(`balance_end`),0)           
                FROM journals_temp;
                            
                SELECT IFNULL(COUNT(*),0) INTO mTOTAL_DATA FROM journals_temp;
                UPDATE journals_temp 
                SET total_data=mTOTAL_DATA, 
                `status`=CASE WHEN mTOTAL_DATA > 0 THEN 1 ELSE 0 END, 
                `message`=CASE WHEN mTOTAL_DATA > 0 THEN 'Data found' ELSE 'Data not found' END,
                `search`=CASE WHEN vSEARCH != '' THEN vSEARCH ELSE '-' END;
            END BLOCK_I;
            
            BLOCK_J:BEGIN /* Updaet Row Total for Profit Loss End & Balance */
                UPDATE journals_temp SET profit_loss_end=profit_loss_credit - profit_loss_debit
                WHERE account_name='Total' AND parent_id=6;
            END BLOCK_J;

            -- SELECT account_name, end_debit, end_credit, profit_loss_debit, profit_loss_credit, profit_loss_end FROM journals_temp WHERE account_group > 3 ORDER BY parent_id ASC, account_code ASC;
            
            SELECT journals_temp.* FROM journals_temp ORDER BY parent_id ASC, account_code ASC;
            -- SELECT account_name, start_debit, start_credit, movement_debit, movement_credit, end_debit, end_credit, profit_loss_debit, profit_loss_credit, profit_loss_end, `start_date`, `running_date` FROM journals_temp WHERE account_group > 3 ORDER BY parent_id ASC, account_code ASC;
            -- SELECT account_name, start_debit, start_credit, movement_debit, movement_credit, end_debit, end_credit FROM journals_temp WHERE account_group > 3 ORDER BY parent_id ASC, account_code ASC;                        
            -- SELECT start_debit, start_credit, movement_debit, movement_credit, end_debit, end_credit, start_date, running_date FROM journals_temp ORDER BY parent_id ASC, account_code ASC;      
        ELSEIF vACTION = 4 THEN /* Pergerakan Hutang */
            DROP TEMPORARY TABLE IF EXISTS journals_temp;
            CREATE TEMPORARY TABLE journals_temp (
                `temp_id` BIGINT NOT NULL AUTO_INCREMENT,
                `type_name` VARCHAR(255),
                `trans_id` BIGINT(50),
                `trans_session` VARCHAR(255),
                `trans_type` INT(5),
                `trans_date` DATETIME, 
                `trans_date_due` DATETIME,          
                `trans_date_due_over` VARCHAR(255),
                `trans_number` VARCHAR(255),
                `trans_note` VARCHAR(255),
                `trans_total_dpp` DOUBLE(18,2) DEFAULT 0,
                `trans_discount` DOUBLE(18,2) DEFAULT 0,
                `trans_total` DOUBLE(18,2) DEFAULT 0,
                `trans_total_paid` DOUBLE(18,2) DEFAULT 0,
                -- `trans_change` DOUBLE(18,2) DEFAULT 0,
                -- `trans_received` DOUBLE(18,2) DEFAULT 0,
                `trans_flag` INT(5) DEFAULT 0,
                `trans_paid` INT(5) DEFAULT 0,
                `contact_id` BIGINT(50),
                `contact_type` INT(5),
                `contact_code` VARCHAR(255),
                `contact_name` VARCHAR(255),
                `balance` DOUBLE(18,2) DEFAULT 0,
                `status` INT(5) DEFAULT 0,
                `message` VARCHAR(255),
                `total_data` INT(50),
                `search` VARCHAR(255),
                PRIMARY KEY (`temp_id`),
                INDEX `TRANS_ID`(`trans_id`) USING BTREE
            ) ENGINE=MEMORY;    

            SELECT MIN(trans_date) INTO mDATE_START_APP FROM trans WHERE trans_branch_id=vBRANCH_ID;
            SET @start_date = mDATE_START_APP - INTERVAL 1 DAY;
            SET @end_date = CONCAT(vEND,' 23:59:59');

            SET @start_date = DATE_FORMAT(@start_date, "%Y%m%d%H%i%s"); 
            SET @end_date = DATE_FORMAT(@end_date, "%Y%m%d%H%i%s");
                        
            BLOCK_A:BEGIN /* DONE Get Group of Account */
                DECLARE mTYPE_NAME VARCHAR(255); DECLARE mTRANS_ID BIGINT(50); DECLARE mTRANS_TYPE INT(5); DECLARE mTRANS_DATE VARCHAR(255); DECLARE mTRANS_DATE_DUE VARCHAR(255);
                DECLARE mTRANS_NUMBER VARCHAR(255); DECLARE mTRANS_NOTE VARCHAR(255); DECLARE mTRANS_TOTAL_DPP DOUBLE(18,2) DEFAULT 0; DECLARE mTRANS_DISCOUNT DOUBLE(18,2) DEFAULT 0; 
                DECLARE mTRANS_TOTAL DOUBLE(18,2) DEFAULT 0; DECLARE mTRANS_TOTAL_PAID DOUBLE(18,2) DEFAULT 0; DECLARE mTRANS_FLAG INT(5); DECLARE mTRANS_PAID INT(5);
                DECLARE mCONTACT_ID BIGINT(50); DECLARE mCONTACT_TYPE INT(5); DECLARE mCONTACT_CODE VARCHAR(255); DECLARE mCONTACT_NAME VARCHAR(255);
                DECLARE mBALANCE DOUBLE(18,2) DEFAULT 0; DECLARE mSTATUS INT(5); DECLARE mMESSAGE VARCHAR(255); DECLARE mTOTAL_DATA INT(50);
                DECLARE mTRANS_SESSION VARCHAR(255);

                DECLARE mFINISHED INTEGER;
                DECLARE mACTION_CURSOR CURSOR FOR
                    SELECT trans_id, trans_session, trans_date, trans_date_due, trans_note, trans_number, trans_total_dpp, trans_discount, trans_total, trans_total_paid, trans_flag, trans_paid,
                    contact_id, contact_code, contact_name
                    FROM trans
                    LEFT JOIN contacts ON trans_contact_id=contact_id
                    WHERE trans_branch_id=vBRANCH_ID
                    AND `trans_type` = 1
                    AND `trans_paid` = 0
                    AND `trans_date` > @start_date
                    AND `trans_date` < @end_date
                    AND CASE WHEN vACCOUNT_ID > 0 THEN `trans_contact_id`=vACCOUNT_ID ELSE `trans_contact_id` > 0 END;      
                DECLARE CONTINUE HANDLER FOR NOT FOUND SET mFINISHED = 1;           
                
                OPEN mACTION_CURSOR;
                LOOP_1: LOOP
                    FETCH mACTION_CURSOR INTO mTRANS_ID, mTRANS_SESSION, mTRANS_DATE, mTRANS_DATE_DUE, mTRANS_NOTE, mTRANS_NUMBER, 
                    mTRANS_TOTAL_DPP, mTRANS_DISCOUNT, mTRANS_TOTAL, mTRANS_TOTAL_PAID, mTRANS_FLAG, mTRANS_PAID, mCONTACT_ID, mCONTACT_CODE, mCONTACT_NAME;

                    IF mFINISHED = 1 THEN
                        LEAVE LOOP_1;
                    END IF;       
                    
                    SET mBALANCE = mTRANS_TOTAL - mTRANS_TOTAL_PAID;
                    INSERT INTO journals_temp(`trans_id`,`trans_session`,`type_name`,`trans_type`,`trans_date`,`trans_date_due`,`trans_date_due_over`,`trans_note`,`trans_number`,`trans_total_dpp`,`trans_discount`,`trans_total`,`trans_total_paid`,`trans_flag`,`trans_paid`,
                    `contact_id`,`contact_code`,`contact_name`,`balance`) 
                    VALUES (mTRANS_ID,mTRANS_SESSION,CONCAT('Pembelian'),CONCAT(1),mTRANS_DATE,mTRANS_DATE_DUE,fn_time_ago(mTRANS_DATE_DUE),mTRANS_NOTE,mTRANS_NUMBER,mTRANS_TOTAL_DPP,mTRANS_DISCOUNT,mTRANS_TOTAL,mTRANS_TOTAL_PAID,mTRANS_FLAG,mTRANS_paid,mCONTACT_ID,mCONTACT_CODE,mCONTACT_NAME,mBALANCE);   
                    SET mBALANCE = 0;
                END LOOP LOOP_1;                    
            END BLOCK_A;

            BLOCK_B:BEGIN /* Count Data */
                DECLARE mTOTAL_DATA BIGINT(255);
                /* Count All Data*/
                SELECT IFNULL(COUNT(temp_id),0) INTO mTOTAL_DATA FROM journals_temp LIMIT 1;
                UPDATE journals_temp 
                SET total_data=mTOTAL_DATA, 
                `status`=CASE WHEN mTOTAL_DATA > 0 THEN 1 ELSE 0 END, 
                `message`=CASE WHEN mTOTAL_DATA > 0 THEN 'Data found' ELSE 'Data not found' END,
                `search`=CASE WHEN vSEARCH != '' THEN vSEARCH ELSE '-' END;
            END BLOCK_B;

            SELECT * FROM journals_temp;    
        ELSEIF vACTION = 5 THEN /* Pergerakan Piutang */
            DROP TEMPORARY TABLE IF EXISTS journals_temp;
            CREATE TEMPORARY TABLE journals_temp (
                `temp_id` BIGINT NOT NULL AUTO_INCREMENT,
                `type_name` VARCHAR(255),
                `trans_id` BIGINT(50),
                `trans_session` VARCHAR(255),
                `trans_type` INT(5),
                `trans_date` DATETIME, 
                `trans_date_due` DATETIME,    
                `trans_date_due_over` VARCHAR(255),      
                `trans_number` VARCHAR(255),
                `trans_note` VARCHAR(255),
                `trans_total_dpp` DOUBLE(18,2) DEFAULT 0,
                `trans_discount` DOUBLE(18,2) DEFAULT 0,
                `trans_total` DOUBLE(18,2) DEFAULT 0,
                `trans_total_paid` DOUBLE(18,2) DEFAULT 0,
                -- `trans_change` DOUBLE(18,2) DEFAULT 0,
                -- `trans_received` DOUBLE(18,2) DEFAULT 0,
                `trans_flag` INT(5) DEFAULT 0,
                `trans_paid` INT(5) DEFAULT 0,
                `contact_id` BIGINT(50),
                `contact_type` INT(5),
                `contact_code` VARCHAR(255),
                `contact_name` VARCHAR(255),
                `balance` DOUBLE(18,2) DEFAULT 0,
                `status` INT(5) DEFAULT 0,
                `message` VARCHAR(255),
                `total_data` INT(50),
                `search` VARCHAR(255),
                PRIMARY KEY (`temp_id`),
                INDEX `TRANS_ID`(`trans_id`) USING BTREE
            ) ENGINE=MEMORY;    

            SELECT MIN(trans_date) INTO mDATE_START_APP FROM trans WHERE trans_branch_id=vBRANCH_ID;
            SET @start_date = mDATE_START_APP - INTERVAL 1 DAY;
            SET @end_date = CONCAT(vEND,' 23:59:59');

            SET @start_date = DATE_FORMAT(@start_date, "%Y%m%d%H%i%s"); 
            SET @end_date = DATE_FORMAT(@end_date, "%Y%m%d%H%i%s");
                        
            BLOCK_A:BEGIN /* DONE Get Group of Account */
                DECLARE mTYPE_NAME VARCHAR(255); DECLARE mTRANS_ID BIGINT(50); DECLARE mTRANS_TYPE INT(5); DECLARE mTRANS_DATE VARCHAR(255); DECLARE mTRANS_DATE_DUE VARCHAR(255);
                DECLARE mTRANS_NUMBER VARCHAR(255); DECLARE mTRANS_NOTE VARCHAR(255); DECLARE mTRANS_TOTAL_DPP DOUBLE(18,2) DEFAULT 0; DECLARE mTRANS_DISCOUNT DOUBLE(18,2) DEFAULT 0; 
                DECLARE mTRANS_TOTAL DOUBLE(18,2) DEFAULT 0; DECLARE mTRANS_TOTAL_PAID DOUBLE(18,2) DEFAULT 0; DECLARE mTRANS_FLAG INT(5); DECLARE mTRANS_PAID INT(5);
                DECLARE mCONTACT_ID BIGINT(50); DECLARE mCONTACT_TYPE INT(5); DECLARE mCONTACT_CODE VARCHAR(255); DECLARE mCONTACT_NAME VARCHAR(255);
                DECLARE mBALANCE DOUBLE(18,2) DEFAULT 0; DECLARE mSTATUS INT(5); DECLARE mMESSAGE VARCHAR(255); DECLARE mTOTAL_DATA INT(50);
                DECLARE mTRANS_SESSION VARCHAR(255);

                DECLARE mFINISHED INTEGER;
                DECLARE mACTION_CURSOR CURSOR FOR
                    SELECT trans_id, trans_session, trans_date, trans_date_due, trans_note, trans_number, trans_total_dpp, trans_discount, trans_total, trans_total_paid, trans_flag, trans_paid,
                    contact_id, contact_code, contact_name
                    FROM trans
                    LEFT JOIN contacts ON trans_contact_id=contact_id
                    WHERE trans_branch_id=vBRANCH_ID
                    AND `trans_type` = 2
                    AND `trans_paid` = 0
                    AND `trans_date` > @start_date
                    AND `trans_date` < @end_date
                    AND CASE WHEN vACCOUNT_ID > 0 THEN `trans_contact_id`=vACCOUNT_ID ELSE `trans_contact_id` > 0 END;               
                DECLARE CONTINUE HANDLER FOR NOT FOUND SET mFINISHED = 1;           
                
                OPEN mACTION_CURSOR;
                LOOP_1: LOOP
                    FETCH mACTION_CURSOR INTO mTRANS_ID, mTRANS_SESSION, mTRANS_DATE, mTRANS_DATE_DUE, mTRANS_NOTE, mTRANS_NUMBER, 
                    mTRANS_TOTAL_DPP, mTRANS_DISCOUNT, mTRANS_TOTAL, mTRANS_TOTAL_PAID, mTRANS_FLAG, mTRANS_PAID, mCONTACT_ID, mCONTACT_CODE, mCONTACT_NAME;

                    IF mFINISHED = 1 THEN
                        LEAVE LOOP_1;
                    END IF;       
                    
                    SET mBALANCE = mTRANS_TOTAL - mTRANS_TOTAL_PAID;
                    INSERT INTO journals_temp(`trans_id`,`trans_session`,`type_name`,`trans_type`,`trans_date`,`trans_date_due`,`trans_date_due_over`,`trans_note`,`trans_number`,`trans_total_dpp`,`trans_discount`,`trans_total`,`trans_total_paid`,`trans_flag`,`trans_paid`,
                    `contact_id`,`contact_code`,`contact_name`,`balance`) 
                    VALUES (mTRANS_ID,mTRANS_SESSION,CONCAT('Penjualan'),CONCAT(2),mTRANS_DATE,mTRANS_DATE_DUE,fn_time_ago(mTRANS_DATE_DUE),mTRANS_NOTE,mTRANS_NUMBER,mTRANS_TOTAL_DPP,mTRANS_DISCOUNT,mTRANS_TOTAL,mTRANS_TOTAL_PAID,mTRANS_FLAG,mTRANS_paid,mCONTACT_ID,mCONTACT_CODE,mCONTACT_NAME,mBALANCE);   
                    SET mBALANCE = 0;
                END LOOP LOOP_1;                    
            END BLOCK_A;

            BLOCK_B:BEGIN /* Count Data */
                DECLARE mTOTAL_DATA BIGINT(255);
                /* Count All Data*/
                SELECT IFNULL(COUNT(temp_id),0) INTO mTOTAL_DATA FROM journals_temp LIMIT 1;
                UPDATE journals_temp 
                SET total_data=mTOTAL_DATA, 
                `status`=CASE WHEN mTOTAL_DATA > 0 THEN 1 ELSE 0 END, 
                `message`=CASE WHEN mTOTAL_DATA > 0 THEN 'Data found' ELSE 'Data not found' END,
                `search`=CASE WHEN vSEARCH != '' THEN vSEARCH ELSE '-' END;
            END BLOCK_B;

            SELECT * FROM journals_temp;    
        ELSE
            SELECT 0 AS `status`, CONCAT('Action not found') AS `message`;
        END IF;
    END$$
DELIMITER ;

DELIMITER $$
DROP PROCEDURE IF EXISTS `sp_report_cashflow`$$
CREATE PROCEDURE `sp_report_cashflow`(
    IN vACTION INT(5),
    IN vSTART VARCHAR(255),
    IN vEND VARCHAR(255),
    IN vBRANCH_ID BIGINT(50),
    IN vACCOUNT_ID BIGINT(50),
    IN vSEARCH VARCHAR(255),
    IN vLIMIT_START INT(25),
    IN vLIMIT_END INT(25)
    )
    BEGIN
        DECLARE mDATE_START_APP VARCHAR(255);
        /*
            ACTION
            6 = Pemasukan Uang / Setoran
            7 = Pengeluaran Uang / Biaya

        */
        IF vACTION = 6 THEN /* Pemasukan Uang / Setoran */
        
            DROP TEMPORARY TABLE IF EXISTS journals_temp;
            CREATE TEMPORARY TABLE journals_temp (
                `temp_id` BIGINT NOT NULL AUTO_INCREMENT,
                `journal_item_id` BIGINT(50),
                `journal_item_date` VARCHAR(255),
                `journal_item_note` VARCHAR(255),
                `journal_id` BIGINT(50),
                `trans_id` BIGINT(50),
                `order_id` BIGINT(50),                
                `type_id` BIGINT(50),               
                `type_name` VARCHAR(255), 
                `journal_number` VARCHAR(255), 
                `trans_number` VARCHAR(255), 
                `contact_name` VARCHAR(255),
                `account_id` BIGINT(50),
                `account_group` INT(5),
                `account_code` VARCHAR(255),
                `account_name` VARCHAR(255),
                `debit` DOUBLE(18,2) DEFAULT 0,
                `credit` DOUBLE(18,2) DEFAULT 0,
                `balance` DOUBLE(18,2) DEFAULT 0,
                `journal_session` VARCHAR(255),
                `trans_session` VARCHAR(255),                                
                `status` INT(5) DEFAULT 0,
                `message` VARCHAR(255),
                `total_data` INT(50),
                `search` VARCHAR(255),
                `running_date` VARCHAR(255),
                `period_date` VARCHAR(255),                
                PRIMARY KEY (`temp_id`),
                INDEX `JOURNAL_ITEM_ID`(`journal_item_id`) USING BTREE
            ) ENGINE=MEMORY;    
            
            SELECT MIN(journal_item_date) INTO mDATE_START_APP FROM journals_items WHERE journal_item_branch_id=vBRANCH_ID;
            SET @start_date = mDATE_START_APP - INTERVAL 1 DAY;
            SET @end_date = CONCAT(vSTART,' 23:59:59') - INTERVAL 1 DAY;
            SET @running_date_start = CONCAT(vSTART,' 00:00:00');
            SET @running_date_end = CONCAT(vEND,' 23:59:59');
            
            SET @start_date_journal = @start_date;
            SET @end_date_journal = @end_date;
            
            SET @start_date = DATE_FORMAT(@start_date, "%Y%m%d%H%i%s"); 
            SET @end_date = DATE_FORMAT(@end_date, "%Y%m%d%H%i%s");
            
            BLOCK_A:BEGIN /* Get Running Balance */
                DECLARE mACCOUNT_ID INT(50);
                DECLARE mACCOUNT_CODE VARCHAR(255);
                DECLARE mACCOUNT_NAME VARCHAR(255);    
                DECLARE mACCOUNT_GROUP INT(5);
                
                DECLARE mJOURNAL_ITEM_ID BIGINT(50);
                DECLARE mJOURNAL_DATE DATETIME;
                DECLARE mJOURNAL_NOTE TEXT;
                
                DECLARE mTYPE_ID INT(5);
                DECLARE mTYPE_NAME VARCHAR(255);
                
                DECLARE mJOURNAL_ID BIGINT(50);
                DECLARE mTRANS_ID BIGINT(50);
                DECLARE mORDER_ID BIGINT(50);                
                DECLARE mJOURNAL_NUMBER VARCHAR(255);
                DECLARE mTRANS_NUMBER VARCHAR(255);
                
                DECLARE mDEBIT DOUBLE(18,2) DEFAULT 0;
                DECLARE mCREDIT DOUBLE(18,2) DEFAULT 0;
                DECLARE mBALANCE DOUBLE(18,2) DEFAULT 0;
                
                DECLARE mTOTAL_DATA INT(50) DEFAULT 0;
                DECLARE mFINISHED INTEGER;
                DECLARE mACTION_CURSOR CURSOR FOR
                    SELECT 
                    `journals_items`.`journal_item_id`,
                    `journals_items`.`journal_item_date`, 
                    `journals_items`.`journal_item_note`, 
                    `account_id`,`account_code`,`account_name`,`account_group`,
                    `journals_items`.`journal_item_type`,
                    `journals_items`.`journal_item_journal_id`,
                    `journals_items`.`journal_item_trans_id`,
                    `journals_items`.`journal_item_order_id`,                                      
                    IFNULL(journals_items.journal_item_debit,0) AS debit, 
                    IFNULL(journals_items.journal_item_credit,0) AS credit,
                    CONCAT(0) AS balance                
                    FROM `journals_items`
                    LEFT JOIN `accounts` ON `journal_item_account_id`=`account_id`
                    WHERE `journals_items`.`journal_item_branch_id` = vBRANCH_ID 
                    AND (CASE WHEN vACCOUNT_ID > 0 THEN `journal_item_account_id`=vACCOUNT_ID ELSE 1=1 END)
                    AND `journals_items`.`journal_item_date` > @running_date_start
                    AND `journals_items`.`journal_item_date` < @running_date_end
                    AND `journals_items`.`journal_item_flag` = 1
                    AND `journal_item_debit` > 0
                    AND `account_group_sub` = 3
                    AND (CASE WHEN vSEARCH > 0 THEN `journal_item_type`=vSEARCH ELSE `journal_item_type` IN (2,3,5,7,8) END)
                    ORDER BY `journals_items`.`journal_item_date` ASC;
                DECLARE CONTINUE HANDLER FOR NOT FOUND SET mFINISHED = 1;           
                
                OPEN mACTION_CURSOR;
                LOOP_1: LOOP                   
                    FETCH mACTION_CURSOR INTO mJOURNAL_ITEM_ID, mJOURNAL_DATE, mJOURNAL_NOTE, mACCOUNT_ID, mACCOUNT_CODE, mACCOUNT_NAME, mACCOUNT_GROUP, mTYPE_ID, mJOURNAL_ID, mTRANS_ID, mORDER_ID, mDEBIT, mCREDIT, mBALANCE;               
                    IF mFINISHED = 1 THEN
                        LEAVE LOOP_1;
                    END IF;       
                    INSERT INTO journals_temp(`journal_item_id`,`journal_item_date`,`journal_item_note`,`type_name`,`type_id`,`journal_id`,`trans_id`,`order_id`,`account_id`,`account_group`,`account_code`,`account_name`,`debit`,`credit`,`balance`,`running_date`,`period_date`)
                    VALUES(mJOURNAL_ITEM_ID,mJOURNAL_DATE,mJOURNAL_NOTE,mTYPE_NAME,mTYPE_ID,mJOURNAL_ID,mTRANS_ID,mORDER_ID,mACCOUNT_ID,mACCOUNT_GROUP,mACCOUNT_CODE,mACCOUNT_NAME,mDEBIT,mCREDIT,mBALANCE,CONCAT(@running_date_start,' to ',@running_date_end),CONCAT(@start_date,' to ',@end_date));
                END LOOP LOOP_1;     
            END BLOCK_A;
            
            BLOCK_B:BEGIN /* Updating Trans n Journal Type */
                UPDATE `journals_temp` 
                LEFT JOIN `journals` ON `journals_temp`.`journal_id`=`journals`.`journal_id`
                LEFT JOIN `contacts` ON `journals`.`journal_contact_id`=`contacts`.`contact_id`
                LEFT JOIN `types` ON `journals_temp`.`type_id`=`types`.`type_type` AND type_for=3
                SET `journals_temp`.`journal_number`=`journals`.`journal_number`, 
                `journals_temp`.`type_name`=`types`.`type_name`,
                `journals_temp`.`contact_name`=`contacts`.`contact_name`,
                `journals_temp`.`journal_session`=`journals`.`journal_session`
                WHERE `journals_temp`.`journal_id` IS NOT NULL;
                
                UPDATE `journals_temp` 
                LEFT JOIN `trans` ON `journals_temp`.`trans_id`=`trans`.`trans_id`     
                LEFT JOIN `contacts` ON `trans`.`trans_contact_id`=`contacts`.`contact_id`                
                LEFT JOIN `types` ON `journals_temp`.`type_id`=`types`.`type_type` AND type_for=3
                SET `journals_temp`.`trans_number`=`trans`.`trans_number`, 
                `journals_temp`.`type_name`=`types`.`type_name`,
                `journals_temp`.`contact_name`=`contacts`.`contact_name`,      
                `journals_temp`.`trans_session`=`trans`.`trans_session`                          
                WHERE `journals_temp`.`trans_id` IS NOT NULL;               
            END BLOCK_B;
            
            BLOCK_C:BEGIN /* Updating Order*/
                DECLARE mCOUNT INT(5);
                SELECT COUNT(*) INTO mCOUNT FROM journals_temp WHERE order_id IS NOT NULL;
                IF mCOUNT > 0 THEN
                    UPDATE journals_temp AS j 
                    JOIN orders AS o ON j.order_id=o.order_id
                    JOIN TYPES AS t ON j.type_id=t.type_type AND type_for=3
                    SET j.trans_number=o.order_number, j.type_name=t.type_name
                    WHERE j.order_id IS NOT NULL;
                END IF;
            END BLOCK_C;

            BLOCK_D:BEGIN
                DECLARE mTOTAL_DATA BIGINT(255);
                /* Count All Data*/
                SELECT IFNULL(COUNT(*),0) INTO mTOTAL_DATA FROM journals_temp;
                UPDATE journals_temp 
                SET total_data=mTOTAL_DATA, 
                `status`=CASE WHEN mTOTAL_DATA > 0 THEN 1 ELSE 0 END, 
                `message`=CASE WHEN mTOTAL_DATA > 0 THEN 'Data found' ELSE 'Data not found' END;       
            END BLOCK_D;
            
            BLOCK_E:BEGIN
                DECLARE mTOTAL_DATA BIGINT(255);
                IF vSEARCH IS NOT NULL AND vSEARCH > 0 THEN
                    /* Count All Data by vSEARCH */
                    SELECT IFNULL(COUNT(*),0) INTO mTOTAL_DATA FROM journals_temp WHERE `type_id`=vSEARCH;
                    UPDATE journals_temp 
                    SET total_data=mTOTAL_DATA, 
                    `search`=CONCAT('type_id = ',vSEARCH),
                    `status`=CASE WHEN mTOTAL_DATA > 0 THEN 1 ELSE 0 END, 
                    `message`=CASE WHEN mTOTAL_DATA > 0 THEN 'Data found' ELSE 'Data not found' END;       
                END IF;
            END BLOCK_E;

            SELECT journals_temp.*, DATE_FORMAT(journal_item_date, "%d/%m/%Y, %H:%i") AS journal_item_date_format 
            FROM journals_temp 
            WHERE debit > 0 AND CASE WHEN vSEARCH > 0 THEN `type_id`=vSEARCH ELSE 1=1 END LIMIT vLIMIT_START, vLIMIT_END;          
        ELSEIF vACTION = 7 THEN /* Pengeluaran Uang / Biaya */
        
            DROP TEMPORARY TABLE IF EXISTS journals_temp;
            CREATE TEMPORARY TABLE journals_temp (
                `temp_id` BIGINT NOT NULL AUTO_INCREMENT,
                `journal_item_id` BIGINT(50),
                `journal_item_date` VARCHAR(255),
                `journal_item_note` VARCHAR(255),
                `journal_id` BIGINT(50),
                `trans_id` BIGINT(50),
                `order_id` BIGINT(50),                
                `type_id` BIGINT(50),               
                `type_name` VARCHAR(255), 
                `journal_number` VARCHAR(255), 
                `trans_number` VARCHAR(255), 
                `contact_name` VARCHAR(255),
                `account_id` BIGINT(50),
                `account_group` INT(5),
                `account_code` VARCHAR(255),
                `account_name` VARCHAR(255),
                `debit` DOUBLE(18,2) DEFAULT 0,
                `credit` DOUBLE(18,2) DEFAULT 0,
                `balance` DOUBLE(18,2) DEFAULT 0,
                `journal_session` VARCHAR(255),
                `trans_session` VARCHAR(255),                                
                `status` INT(5) DEFAULT 0,
                `message` VARCHAR(255),
                `total_data` INT(50),
                `search` VARCHAR(255),
                `running_date` VARCHAR(255),
                `period_date` VARCHAR(255),                
                PRIMARY KEY (`temp_id`),
                INDEX `JOURNAL_ITEM_ID`(`journal_item_id`) USING BTREE
            ) ENGINE=MEMORY;    
            
            SELECT MIN(journal_item_date) INTO mDATE_START_APP FROM journals_items WHERE journal_item_branch_id=vBRANCH_ID;
            SET @start_date = mDATE_START_APP - INTERVAL 1 DAY;
            SET @end_date = CONCAT(vSTART,' 23:59:59') - INTERVAL 1 DAY;
            SET @running_date_start = CONCAT(vSTART,' 00:00:00');
            SET @running_date_end = CONCAT(vEND,' 23:59:59');
            
            SET @start_date_journal = @start_date;
            SET @end_date_journal = @end_date;
            
            SET @start_date = DATE_FORMAT(@start_date, "%Y%m%d%H%i%s"); 
            SET @end_date = DATE_FORMAT(@end_date, "%Y%m%d%H%i%s");
            
            BLOCK_A:BEGIN /* Get Running Balance */
                DECLARE mACCOUNT_ID INT(50);
                DECLARE mACCOUNT_CODE VARCHAR(255);
                DECLARE mACCOUNT_NAME VARCHAR(255);    
                DECLARE mACCOUNT_GROUP INT(5);
                
                DECLARE mJOURNAL_ITEM_ID BIGINT(50);
                DECLARE mJOURNAL_DATE DATETIME;
                DECLARE mJOURNAL_NOTE TEXT;
                
                DECLARE mTYPE_ID INT(5);
                DECLARE mTYPE_NAME VARCHAR(255);
                
                DECLARE mJOURNAL_ID BIGINT(50);
                DECLARE mTRANS_ID BIGINT(50);
                DECLARE mORDER_ID BIGINT(50);                
                DECLARE mJOURNAL_NUMBER VARCHAR(255);
                DECLARE mTRANS_NUMBER VARCHAR(255);
                
                DECLARE mDEBIT DOUBLE(18,2) DEFAULT 0;
                DECLARE mCREDIT DOUBLE(18,2) DEFAULT 0;
                DECLARE mBALANCE DOUBLE(18,2) DEFAULT 0;
                
                DECLARE mTOTAL_DATA INT(50) DEFAULT 0;
                DECLARE mFINISHED INTEGER;
                DECLARE mACTION_CURSOR CURSOR FOR
                    SELECT 
                    `journals_items`.`journal_item_id`,
                    `journals_items`.`journal_item_date`, 
                    `journals_items`.`journal_item_note`, 
                    `account_id`,`account_code`,`account_name`,`account_group`,
                    `journals_items`.`journal_item_type`,
                    `journals_items`.`journal_item_journal_id`,
                    `journals_items`.`journal_item_trans_id`,
                    `journals_items`.`journal_item_order_id`,                                      
                    IFNULL(journals_items.journal_item_debit,0) AS debit, 
                    IFNULL(journals_items.journal_item_credit,0) AS credit,
                    CONCAT(0) AS balance                
                    FROM `journals_items`
                    LEFT JOIN `accounts` ON `journal_item_account_id`=`account_id`
                    WHERE `journals_items`.`journal_item_branch_id` = vBRANCH_ID 
                    AND (CASE WHEN vACCOUNT_ID > 0 THEN `journal_item_account_id`=vACCOUNT_ID ELSE 1=1 END)
                    AND `journals_items`.`journal_item_date` > @running_date_start
                    AND `journals_items`.`journal_item_date` < @running_date_end
                    AND `journals_items`.`journal_item_flag` = 1
                    AND `journal_item_credit` > 0
                    AND `account_group_sub` = 3
                    AND (CASE WHEN vSEARCH > 0 THEN `journal_item_type`=vSEARCH ELSE `journal_item_type` IN (1,4,5,6,8) END)                    
                    ORDER BY `journals_items`.`journal_item_date` ASC;
                DECLARE CONTINUE HANDLER FOR NOT FOUND SET mFINISHED = 1;           
                
                OPEN mACTION_CURSOR;
                LOOP_1: LOOP                   
                    FETCH mACTION_CURSOR INTO mJOURNAL_ITEM_ID, mJOURNAL_DATE, mJOURNAL_NOTE, mACCOUNT_ID, mACCOUNT_CODE, mACCOUNT_NAME, mACCOUNT_GROUP, mTYPE_ID, mJOURNAL_ID, mTRANS_ID, mORDER_ID, mDEBIT, mCREDIT, mBALANCE;               
                    IF mFINISHED = 1 THEN
                        LEAVE LOOP_1;
                    END IF;       
                    INSERT INTO journals_temp(`journal_item_id`,`journal_item_date`,`journal_item_note`,`type_name`,`type_id`,`journal_id`,`trans_id`,`order_id`,`account_id`,`account_group`,`account_code`,`account_name`,`debit`,`credit`,`balance`,`running_date`,`period_date`)
                    VALUES(mJOURNAL_ITEM_ID,mJOURNAL_DATE,mJOURNAL_NOTE,mTYPE_NAME,mTYPE_ID,mJOURNAL_ID,mTRANS_ID,mORDER_ID,mACCOUNT_ID,mACCOUNT_GROUP,mACCOUNT_CODE,mACCOUNT_NAME,mDEBIT,mCREDIT,mBALANCE,CONCAT(@running_date_start,' to ',@running_date_end),CONCAT(@start_date,' to ',@end_date));
                END LOOP LOOP_1;     
            END BLOCK_A;
            
            BLOCK_B:BEGIN /* Updating Trans n Journal Type */
                UPDATE `journals_temp` 
                LEFT JOIN `journals` ON `journals_temp`.`journal_id`=`journals`.`journal_id`
                LEFT JOIN `contacts` ON `journals`.`journal_contact_id`=`contacts`.`contact_id`
                LEFT JOIN `types` ON `journals_temp`.`type_id`=`types`.`type_type` AND type_for=3
                SET `journals_temp`.`journal_number`=`journals`.`journal_number`, 
                `journals_temp`.`type_name`=`types`.`type_name`,
                `journals_temp`.`contact_name`=`contacts`.`contact_name`,
                `journals_temp`.`journal_session`=`journals`.`journal_session`
                WHERE `journals_temp`.`journal_id` IS NOT NULL;
                
                UPDATE `journals_temp` 
                LEFT JOIN `trans` ON `journals_temp`.`trans_id`=`trans`.`trans_id`     
                LEFT JOIN `contacts` ON `trans`.`trans_contact_id`=`contacts`.`contact_id`                
                LEFT JOIN `types` ON `journals_temp`.`type_id`=`types`.`type_type` AND type_for=3
                SET `journals_temp`.`trans_number`=`trans`.`trans_number`, 
                `journals_temp`.`type_name`=`types`.`type_name`,
                `journals_temp`.`contact_name`=`contacts`.`contact_name`,      
                `journals_temp`.`trans_session`=`trans`.`trans_session`                          
                WHERE `journals_temp`.`trans_id` IS NOT NULL;               
            END BLOCK_B;
            
            BLOCK_C:BEGIN /* Updating Order*/
                DECLARE mCOUNT INT(5);
                SELECT COUNT(*) INTO mCOUNT FROM journals_temp WHERE order_id IS NOT NULL;
                IF mCOUNT > 0 THEN
                    UPDATE journals_temp AS j 
                    JOIN orders AS o ON j.order_id=o.order_id
                    JOIN TYPES AS t ON j.type_id=t.type_type AND type_for=3
                    SET j.trans_number=o.order_number, j.type_name=t.type_name
                    WHERE j.order_id IS NOT NULL;
                END IF;
            END BLOCK_C;

            BLOCK_D:BEGIN
                DECLARE mTOTAL_DATA BIGINT(255);
                /* Count All Data*/
                SELECT IFNULL(COUNT(*),0) INTO mTOTAL_DATA FROM journals_temp;
                UPDATE journals_temp 
                SET total_data=mTOTAL_DATA, 
                `status`=CASE WHEN mTOTAL_DATA > 0 THEN 1 ELSE 0 END, 
                `message`=CASE WHEN mTOTAL_DATA > 0 THEN 'Data found' ELSE 'Data not found' END;       
            END BLOCK_D;

            BLOCK_E:BEGIN
                DECLARE mTOTAL_DATA BIGINT(255);
                IF vSEARCH IS NOT NULL AND vSEARCH > 0 THEN
                    /* Count All Data by vSEARCH */
                    SELECT IFNULL(COUNT(*),0) INTO mTOTAL_DATA FROM journals_temp WHERE `type_id`=vSEARCH;
                    UPDATE journals_temp 
                    SET total_data=mTOTAL_DATA, 
                    `search`=CONCAT('type_id = ',vSEARCH),
                    `status`=CASE WHEN mTOTAL_DATA > 0 THEN 1 ELSE 0 END, 
                    `message`=CASE WHEN mTOTAL_DATA > 0 THEN 'Data found' ELSE 'Data not found' END;       
                END IF;
            END BLOCK_E;

            SELECT journals_temp.*, DATE_FORMAT(journal_item_date, "%d/%m/%Y, %H:%i") AS journal_item_date_format 
            FROM journals_temp 
            WHERE credit > 0 AND CASE WHEN vSEARCH > 0 THEN `type_id`=vSEARCH ELSE 1=1 END LIMIT vLIMIT_START, vLIMIT_END;                
        ELSE
            SELECT 0 AS `status`, CONCAT('Action not found') AS `message`;
        END IF;
    END$$
DELIMITER ;

DELIMITER $$
DROP PROCEDURE IF EXISTS `sp_report_stock`$$
CREATE PROCEDURE `sp_report_stock`(
    IN vACTION int(5),
    IN vSTART varchar(255),
    IN vEND varchar(255),
    IN vBRANCH_ID bigint(50),
    IN vLOCATION bigint(50),
    IN vPRODUCT bigint(50),
    IN vORDER varchar(255),
    IN vDIR varchar(255),
    IN vSEARCH varchar(255),
    IN vCATEGORY bigint(50)
    )
    BEGIN
        DECLARE mDATE_START_APP VARCHAR(255);
        DECLARE mRUNNING_DATE_START VARCHAR(50);
        /*
            1 = Stock Gudang , vCATEGORY USED 
            2 = Pergerakan Stock , vCATEGORY USED 
            3 = Kartu Stok
            4 = Stok di Gudang
            5 = Nilai Stok Gudang , vCATEGORY USED 
        */
        IF vACTION = 1 THEN /* Stock Gudang */

			SELECT MIN(trans_item_date) INTO mRUNNING_DATE_START FROM trans_items;
			SET @running_date_start = DATE_FORMAT(mRUNNING_DATE_START, "%Y-%m-%d 00:00:00");
			SET @running_date_end = DATE_FORMAT(NOW(), "%Y-%m-%d 23:59:59");

            IF LENGTH(vEND) > 1 THEN SET @running_date_end = CONCAT(vEND,' 23:59:59'); END IF;            
            
            IF vLOCATION > 0 THEN SET @location = CONCAT(' AND trans_item_location_id=',vLOCATION); ELSE SET @location = ''; END IF;
            IF vPRODUCT > 0 THEN SET @product = CONCAT(' AND trans_item_product_id=',vPRODUCT); ELSE SET @product = ''; END IF;
            IF vCATEGORY > 0 THEN SET @category = CONCAT(' AND product_category_id=',vCATEGORY); ELSE SET @category = ''; END IF;            
            IF LENGTH(vSEARCH) > 0 THEN SET @search = CONCAT(' AND product_name LIKE "%',vSEARCH,'%"'); ELSE SET @search = ''; END IF;

            SET @SQL_Text = CONCAT('SELECT `trans_items`.`trans_item_product_id` AS `product_id`, `products`.`product_type`, `products`.`product_code`, `products`.`product_name`, `products`.`product_unit`,
                IFNULL( SUM( trans_items.trans_item_in_qty ), 0 ) AS in_qty,
                IFNULL( SUM( trans_items.trans_item_out_qty ), 0 ) AS out_qty,
                IFNULL( SUM( trans_items.trans_item_in_qty ), 0 ) - IFNULL( SUM( trans_items.trans_item_out_qty ), 0 ) AS balance,
                `locations`.`location_id`, `locations`.`location_name`,
                CONCAT(" > ',@running_date_start,' < ',@running_date_end,'") AS running_date,
                `category_id`, `category_name`
            FROM `trans_items`
            LEFT JOIN `products` ON `trans_items`.`trans_item_product_id` = `products`.`product_id`
            LEFT JOIN `locations` ON `trans_items`.`trans_item_location_id` = `locations`.`location_id`
            LEFT JOIN `categories` ON `products`.`product_category_id` = `categories`.`category_id`
            WHERE `trans_item_branch_id` = ',vBRANCH_ID,'
            AND `trans_item_product_type`=1     
            AND `trans_item_flag` = 1
            AND `trans_item_date` > "',@running_date_start,'"
            AND `trans_item_date` < "',@running_date_end,'"',@location,@product,@category,@search,'
            GROUP BY `trans_item_product_id` ORDER BY ',vORDER,' ',vDIR,'');
            PREPARE stmt FROM @SQL_Text;
            EXECUTE stmt;
            DEALLOCATE PREPARE stmt;
        ELSEIF vACTION = 2 THEN /* Pergerakan Stock */

            SELECT MIN(trans_item_date) INTO mDATE_START_APP FROM trans_items WHERE trans_item_branch_id=vBRANCH_ID;

            SET @start_date = mDATE_START_APP - INTERVAL 1 DAY;
            SET @end_date = CONCAT(vSTART,' 23:59:59') - INTERVAL 1 DAY;
            SET @running_date_start = CONCAT(vSTART,' 00:00:00');
            SET @running_date_end = CONCAT(vEND,' 23:59:59');

            IF vLOCATION > 0 THEN SET @location = CONCAT(' AND trans_item_location_id=',vLOCATION); ELSE SET @location = ''; END IF;
            IF vPRODUCT > 0 THEN SET @product = CONCAT(' AND trans_item_product_id=',vPRODUCT); ELSE SET @product = ''; END IF;
            IF vCATEGORY > 0 THEN SET @category = CONCAT(' AND product_category_id=',vCATEGORY); ELSE SET @category = ''; END IF;

            SET @SQL_Text = CONCAT('SELECT `trans_items`.`trans_item_product_id` AS `product_id`, `products`.`product_code`, `products`.`product_name`, `products`.`product_unit`,
                IFNULL(awal.start_qty,0) AS start_qty,
                IFNULL( SUM( trans_items.trans_item_in_qty ), 0 ) AS in_qty,
                IFNULL( SUM( trans_items.trans_item_out_qty ), 0 ) AS out_qty,
                IFNULL(awal.start_qty,0) + IFNULL( SUM( trans_items.trans_item_in_qty ), 0 ) - IFNULL( SUM( trans_items.trans_item_out_qty ), 0 ) AS balance,
                `category_id`, `category_name`, `location_name`
            FROM `trans_items`
            LEFT JOIN (
                SELECT trans_item_product_id AS product_id,
                IFNULL(SUM(trans_items.trans_item_in_qty),0) - IFNULL(SUM(trans_items.trans_item_out_qty),0) AS start_qty
                FROM trans_items
                WHERE `trans_item_branch_id` = ',vBRANCH_ID,'
                AND `trans_item_flag` = 1
                AND `trans_item_date` > "',@start_date,'"
                AND `trans_item_date` < "',@end_date,'"
                GROUP BY `trans_item_product_id`
            ) awal ON `trans_items`.`trans_item_product_id`=`awal`.`product_id`
            LEFT JOIN `products` ON `trans_items`.`trans_item_product_id` = `products`.`product_id`
            LEFT JOIN `locations` ON `trans_items`.`trans_item_location_id` = `locations`.`location_id`
            LEFT JOIN `categories` ON `products`.`product_category_id` = `categories`.`category_id`            
            WHERE `trans_item_branch_id` = ',vBRANCH_ID,'
            AND `trans_item_product_type`=1
            AND `trans_item_flag` = 1
            AND `trans_item_date` > "',@running_date_start,'"
            AND `trans_item_date` < "',@running_date_end,'"',@location,@product,@category,'
            GROUP BY `trans_item_product_id` ORDER BY ',vORDER,' ',vDIR,'');
            PREPARE stmt FROM @SQL_Text;
            EXECUTE stmt;
            DEALLOCATE PREPARE stmt;
        ELSEIF vACTION = 3 THEN /* Kartu Stock */

            DROP TEMPORARY TABLE IF EXISTS `stock_card_temp`;
            CREATE TEMPORARY TABLE `stock_card_temp`(
                `temp_id` BIGINT(255) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                `trans_id` BIGINT(255),
                `trans_item_id` BIGINT(255),
                `trans_type_id` INT(5),
                `trans_type_name` VARCHAR(255),
                `trans_date` DATETIME,
                `trans_number` VARCHAR(255),
                `trans_note` TEXT,
                `trans_product_id` BIGINT(255),
                `trans_location_id` BIGINT(255),
                `trans_item_unit` VARCHAR(255),
                `product_code` VARCHAR(255),
                `product_name` VARCHAR(255),
                `trans_ref` VARCHAR(255),
                `qty_in` DOUBLE(18,2),
                `qty_out` DOUBLE(18,2),
                `qty_balance` DOUBLE(18,2),
                `price_in` DOUBLE(18,2) DEFAULT 0,
                `price_out` DOUBLE(18,2) DEFAULT 0,
                `trans_session` VARCHAR(255),
                `date_balance_period` VARCHAR(255),
                `date_running_period` VARCHAR(255),
                `contact_id` BIGINT(255),
                `contact_name` VARCHAR(255),
                `contact_address` TEXT,
                `contact_phone` VARCHAR(255),
                `status` INT(5) DEFAULT 0,
                `message` VARCHAR(255),
                `total_data` INT(50),
                `search` VARCHAR(255),
                INDEX `TRANS`(`trans_id`) USING BTREE,
                INDEX `TRANS_ITEM`(`trans_item_id`) USING BTREE
            ) ENGINE=INNODB;

            SELECT MIN(trans_item_date) INTO mDATE_START_APP FROM trans_items WHERE trans_item_branch_id=vBRANCH_ID;
            SET @start_date = mDATE_START_APP - INTERVAL 1 DAY;
            SET @end_date = CONCAT(vSTART,' 23:59:59') - INTERVAL 1 DAY;
            SET @running_date_start = CONCAT(vSTART,' 00:00:00');
            SET @running_date_end = CONCAT(vEND,' 23:59:59');

            SET @start_date_journal = @start_date;
            SET @end_date_journal = @end_date;

            SET @start_date = DATE_FORMAT(@start_date, "%Y%m%d%H%i%s");
            SET @end_date = DATE_FORMAT(@end_date, "%Y%m%d%H%i%s");

            BLOCK_A:BEGIN /* Get Saldo Awal */
                DECLARE mTRANS_ID BIGINT(255);
                DECLARE mCONTACT_ID BIGINT(255);
                DECLARE mQTY_IN DOUBLE(18,2);
                DECLARE mQTY_OUT DOUBLE(18,2);
                DECLARE mQTY_BALANCE DOUBLE(18,2);
                DECLARE mFINISHED INTEGER;
                DECLARE mCURSOR CURSOR FOR
                    SELECT trans_item_trans_id,
                        IFNULL(SUM(trans_item_in_qty),0) AS qty_in,
                        IFNULL(SUM(trans_item_out_qty),0) AS qty_out,
                        (SELECT IFNULL(SUM(trans_item_in_qty),0) - IFNULL(SUM(trans_item_out_qty),0)) AS qty_balance
                    FROM trans_items
                    WHERE `trans_item_date` > @start_date
                    AND `trans_item_date` < @end_date
                    AND `trans_item_location_id`=vLOCATION
                    AND `trans_item_product_id`=vPRODUCT
                    AND `trans_item_flag`=1
                    AND `trans_item_branch_id`=vBRANCH_ID;
                DECLARE CONTINUE HANDLER FOR NOT FOUND SET mFINISHED = 1;

                OPEN mCURSOR;
                LOOP_CURSOR:LOOP
                    FETCH mCURSOR INTO mTRANS_ID, mQTY_IN, mQTY_OUT, mQTY_BALANCE;
                    IF mFINISHED = 1 THEN
                        LEAVE LOOP_CURSOR;
                    END IF;

                    -- SET mQTY_IN = 0.00;
                    SET mQTY_OUT = 0.00;
                    /* Insert Saldo Awal */
                    INSERT INTO `stock_card_temp` (`trans_type_name`,`trans_date`,`trans_product_id`,`trans_location_id`,`qty_in`,`qty_out`,`qty_balance`,`date_balance_period`,`date_running_period`)
                    VALUES ('Saldo Awal',@end_date,vPRODUCT,vLOCATION,'0.00','0.00',mQTY_BALANCE,CONCAT('> ',DATE_FORMAT(@start_date, "%Y-%m-%d %H:%i:%s"),' < ',DATE_FORMAT(@end_date, "%Y-%m-%d %H:%i:%s"),''),CONCAT('> ',@running_date_start,' < ',@running_date_end,''));

                END LOOP LOOP_CURSOR;
            END BLOCK_A;

            BLOCK_B:BEGIN /* Get Running Balance */
                DECLARE mLOCATION_ID BIGINT(255);
                DECLARE mPRODUCT_ID BIGINT(255);
                DECLARE mPRODUCT_CODE VARCHAR(255);
                DECLARE mPRODUCT_NAME VARCHAR(255);

                DECLARE mTRANS_ID BIGINT(50);
                DECLARE mTRANS_NUMBER VARCHAR(255);

                DECLARE mTRANS_ITEM_ID BIGINT(50);
                DECLARE mTRANS_ITEM_DATE DATETIME;
                DECLARE mTRANS_ITEM_NOTE TEXT;
                DECLARE mTRANS_ITEM_UNIT VARCHAR(255);
                DECLARE mTRANS_SESSION VARCHAR(255);
                DECLARE mTRANS_REF VARCHAR(255);

                DECLARE mTYPE_ID INT(5);
                DECLARE mTYPE_NAME VARCHAR(255);

                DECLARE mDEBIT DOUBLE(18,2) DEFAULT 0;
                DECLARE mCREDIT DOUBLE(18,2) DEFAULT 0;
                DECLARE mBALANCE DOUBLE(18,2) DEFAULT 0;

                DECLARE mPRICE_IN DOUBLE(18,2) DEFAULT 0;
                DECLARE mPRICE_OUT DOUBLE(18,2) DEFAULT 0;

                DECLARE mTOTAL_DATA INT(50) DEFAULT 0;
                DECLARE mFINISHED INTEGER;
                DECLARE mACTION_CURSOR CURSOR FOR
                    SELECT
                    `ti`.`trans_item_id`,
                    `ti`.`trans_item_date`,
                    IFNULL(`ti`.`trans_item_note`,'-'),
                    `ti`.`trans_item_unit`,
                    `ti`.`trans_item_in_price`,
                    `ti`.`trans_item_out_price`,
                    `ti`.`trans_item_ref`,
                    `ti`.`trans_item_session`,
                    `ti`.`trans_item_product_id`,
                    `ti`.`trans_item_location_id`,
                    `ti`.`trans_item_type`,
                    `ti`.`trans_item_trans_id`,
                    IFNULL(ti.trans_item_in_qty,0) AS debit,
                    IFNULL(ti.trans_item_out_qty,0) AS credit,
                    @saldo_awal := @saldo_awal + ti.trans_item_in_qty - ti.trans_item_out_qty AS balance
                    FROM (
                        SELECT @saldo_awal := (
                            SELECT IFNULL(qty_balance,0) FROM stock_card_temp WHERE trans_product_id=vPRODUCT AND trans_type_name='Saldo Awal'
                        )
                    ) AS start_balance
                    CROSS JOIN (
                        SELECT
                        t.trans_item_id, t.trans_item_date, t.trans_item_note, t.trans_item_product_id, t.trans_item_unit,
                        t.trans_item_in_qty, t.trans_item_out_qty, t.trans_item_location_id, t.trans_item_flag, t.trans_item_branch_id,
                        t.trans_item_in_price, t.trans_item_out_price, t.trans_item_session, t.trans_item_ref,
                        t.trans_item_type, t.trans_item_trans_id
                        FROM trans_items AS t
                    ) AS `ti`
                    WHERE `ti`.`trans_item_location_id` = vLOCATION
                    AND `ti`.`trans_item_product_id` = vPRODUCT
                    AND `ti`.`trans_item_date` > @running_date_start
                    AND `ti`.`trans_item_date` < @running_date_end
                    AND `ti`.`trans_item_flag`=1
                    AND `ti`.`trans_item_branch_id`= vBRANCH_ID
                    ORDER BY `ti`.`trans_item_date` ASC;
                DECLARE CONTINUE HANDLER FOR NOT FOUND SET mFINISHED = 1;

                OPEN mACTION_CURSOR;
                LOOP_1: LOOP
                    FETCH mACTION_CURSOR INTO mTRANS_ITEM_ID, mTRANS_ITEM_DATE, mTRANS_ITEM_NOTE, mTRANS_ITEM_UNIT,
                    mPRICE_IN, mPRICE_OUT, mTRANS_REF, mTRANS_SESSION, mPRODUCT_ID, mLOCATION_ID, mTYPE_ID, mTRANS_ID, mDEBIT, mCREDIT, mBALANCE;
                    IF mFINISHED = 1 THEN
                        LEAVE LOOP_1;
                    END IF;
                    -- INSERT INTO `stock_card_temp`(`trans_item_id`)VALUES(1);
                    INSERT INTO stock_card_temp(`trans_item_id`,`trans_date`,`trans_note`,`trans_item_unit`,
                        `trans_type_id`,`trans_id`,`trans_product_id`,`qty_in`,`qty_out`,`qty_balance`,
                        `trans_ref`,`trans_session`,`price_in`,`price_out`,`trans_location_id`,
                        `date_running_period`)
                    VALUES(mTRANS_ITEM_ID,mTRANS_ITEM_DATE,mTRANS_ITEM_NOTE,mTRANS_ITEM_UNIT,
                        mTYPE_ID,mTRANS_ID,mPRODUCT_ID,mDEBIT,mCREDIT,mBALANCE,mTRANS_REF,mTRANS_SESSION,mPRICE_IN,mPRICE_OUT,mLOCATION_ID,
                        CONCAT('> ',DATE_FORMAT(@running_date_start, "%Y-%m-%d %H:%i:%s"),' < ',DATE_FORMAT(@running_date_end, "%Y-%m-%d %H:%i:%s")));
                END LOOP LOOP_1;
            END BLOCK_B;

            BLOCK_C:BEGIN /* Updating Trans Type & Product */
                DECLARE mPRODUCT_CODE VARCHAR(255);
                DECLARE mPRODUCT_NAME VARCHAR(255);

                SELECT product_code, product_name INTO mPRODUCT_CODE, mPRODUCT_NAME
                FROM products WHERE product_id=vPRODUCT;

                UPDATE `stock_card_temp`
                JOIN `trans` ON `stock_card_temp`.`trans_id`=`trans`.`trans_id`
                JOIN `types` ON `stock_card_temp`.`trans_type_id`=`types`.`type_type` AND type_for=2
                SET `stock_card_temp`.`trans_number`=`trans`.`trans_number`,
                `stock_card_temp`.`trans_type_name`=`types`.`type_name`
                WHERE `stock_card_temp`.`trans_id` IS NOT NULL;

                UPDATE `stock_card_temp`
                SET `product_code`=mPRODUCT_CODE, `product_name`=mPRODUCT_NAME
                WHERE `trans_product_id`=vPRODUCT;

                -- UPDATE `journals_temp`
                -- JOIN `trans` ON `journals_temp`.`trans_id`=`trans`.`trans_id`
                -- JOIN `types` ON `journals_temp`.`type_id`=`types`.`type_type` AND type_for=3
                -- SET `journals_temp`.`trans_number`=`trans`.`trans_number`,
                -- `journals_temp`.`type_name`=`types`.`type_name`
                -- WHERE `journals_temp`.`trans_id` IS NOT NULL;
            END BLOCK_C;

            BLOCK_D:BEGIN
                DECLARE mTOTAL_DATA BIGINT(255);
                /* Count All Data*/
                SELECT IFNULL(COUNT(*),0) INTO mTOTAL_DATA FROM stock_card_temp WHERE trans_product_id=vPRODUCT;
                UPDATE stock_card_temp
                SET total_data=mTOTAL_DATA,
                `status`=CASE WHEN mTOTAL_DATA > 0 THEN 1 ELSE 0 END,
                `message`=CASE WHEN mTOTAL_DATA > 0 THEN 'Data found' ELSE 'Data not found' END
                WHERE trans_product_id=vPRODUCT;
            END BLOCK_D;

            BLOCK_E:BEGIN
                UPDATE stock_card_temp 
                JOIN trans ON stock_card_temp.trans_id=trans.trans_id
                JOIN contacts ON trans.trans_contact_id=contacts.contact_id
                SET stock_card_temp.contact_name=contacts.contact_name, stock_card_temp.contact_address=contacts.contact_address, stock_card_temp.contact_phone=contacts.contact_phone_1
                WHERE stock_card_temp.trans_id IS NOT NULL;
            END BLOCK_E;

            SET @SQL_Text = CONCAT('SELECT stock_card_temp.*, DATE_FORMAT(trans_date, "%d/%m/%Y, %H:%i") AS trans_item_date_format FROM stock_card_temp ORDER BY trans_date ASC, trans_item_id ASC');
            PREPARE stmt FROM @SQL_Text;
            EXECUTE stmt;
            DEALLOCATE PREPARE stmt;
        ELSEIF vACTION = 4 THEN /* Stock per Gudang */

            DROP TEMPORARY TABLE IF EXISTS `stock_temp`;
            CREATE TEMPORARY TABLE `stock_temp`(
                `temp_id` BIGINT(255) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                `location_id` BIGINT(255),
                `location_code` VARCHAR(255),
                `location_name` VARCHAR(255),
                `location_flag` INT(5),
                `product_id` BIGINT(255),
                `product_code` VARCHAR(255),
                `product_name` VARCHAR(255),
                `product_unit` VARCHAR(255),
                `product_flag` INT(5),
                `qty_in` DOUBLE(18,2) DEFAULT 0,
                `qty_out` DOUBLE(18,2) DEFAULT 0,
                `qty_balance` DOUBLE(18,2) DEFAULT 0,
                `status` INT(5) DEFAULT 0,
                `message` VARCHAR(255),
                `total_data` INT(50),
                `search` VARCHAR(255),
                INDEX `LOCATION`(`location_id`) USING BTREE,
                INDEX `PRODUCT`(`product_id`) USING BTREE
            ) ENGINE=MEMORY;

            SELECT MIN(trans_item_date) INTO mDATE_START_APP FROM trans_items WHERE trans_item_branch_id=vBRANCH_ID;

            BLOCK_A:BEGIN /* Group Location for Product */
                DECLARE mLOCATION_ID BIGINT(255);
                DECLARE mFINISHED INTEGER;
                DECLARE mCURSOR CURSOR FOR
                    SELECT trans_item_location_id FROM trans_items
                    WHERE `trans_item_product_id`=vPRODUCT
                    AND `trans_item_flag`=1
                    AND `trans_item_branch_id`=vBRANCH_ID
                    GROUP BY `trans_item_location_id`;
                DECLARE CONTINUE HANDLER FOR NOT FOUND SET mFINISHED = 1;

                OPEN mCURSOR;
                LOOP_CURSOR:LOOP
                    FETCH mCURSOR INTO mLOCATION_ID;
                    IF mFINISHED = 1 THEN
                        LEAVE LOOP_CURSOR;
                    END IF;
                    INSERT INTO `stock_temp` (`location_id`,`product_id`) VALUES (mLOCATION_ID,vPRODUCT);
                END LOOP LOOP_CURSOR;
            END BLOCK_A;

            BLOCK_B:BEGIN /* Updating In and Out Qty */
                -- UPDATE stock_temp AS s
                -- SET qty_in=(
                --  SELECT IFNULL(SUM(t.trans_item_in_qty),0)
                --  FROM trans_items AS t
                --  WHERE t.trans_item_location_id=s.location_id
                --  AND t.trans_item_product_id=s.product_id
                --  AND t.trans_item_flag=1
                -- );

                -- UPDATE stock_temp
                -- JOIN
                -- (
                --  SELECT trans_item_location_id, IFNULL(SUM(t.trans_item_in_qty),0) AS qty_in, IFNULL(SUM(t.trans_item_out_qty),0) AS qty_out
                --  FROM trans_items AS t
                --  WHERE t.trans_item_location_id=s.location_id
                --  AND t.trans_item_product_id=s.product_id
                --  AND t.trans_item_flag=1
                -- ) AS t ON stock_temp.location_id=t.trans_item_location_id
                -- SET stock_temp.qty_in=t.qty_in, stock_temp.qty_out=t.qty_out;

                UPDATE stock_temp AS s
                SET s.qty_in=(
                    SELECT IFNULL(SUM(t.trans_item_in_qty),0)
                    FROM trans_items AS t
                    WHERE t.trans_item_location_id=s.location_id
                    AND t.trans_item_product_id=s.product_id
                    AND t.trans_item_flag=1
                    GROUP BY t.trans_item_location_id
                ),s.qty_out=(
                    SELECT IFNULL(SUM(t.trans_item_out_qty),0)
                    FROM trans_items AS t
                    WHERE t.trans_item_location_id=s.location_id
                    AND t.trans_item_product_id=s.product_id
                    AND t.trans_item_flag=1
                    GROUP BY t.trans_item_location_id
                );

            END BLOCK_B;

            BLOCK_C:BEGIN /* Updating Balance Qty */
                UPDATE stock_temp SET qty_balance=IFNULL(qty_in - qty_out,0);
            END BLOCK_C;

            BLOCK_D:BEGIN /* Updating Location and Product */

                UPDATE stock_temp AS s JOIN locations AS l ON s.location_id=l.location_id
                SET s.location_code=l.location_code, s.location_name=l.location_name, s.location_flag=l.location_flag;

                UPDATE stock_temp AS s JOIN products AS p ON s.product_id=p.product_id
                SET s.product_code=p.product_code, s.product_name=p.product_name, s.product_flag=p.product_flag, s.product_unit=p.product_unit;
            END BLOCK_D;

            BLOCK_E:BEGIN
                DECLARE mTOTAL_DATA BIGINT(255);
                /* Count All Data*/
                SELECT IFNULL(COUNT(*),0) INTO mTOTAL_DATA FROM stock_temp WHERE product_id=vPRODUCT;
                UPDATE stock_temp
                SET total_data=mTOTAL_DATA,
                `status`=CASE WHEN mTOTAL_DATA > 0 THEN 1 ELSE 0 END,
                `message`=CASE WHEN mTOTAL_DATA > 0 THEN 'Data found' ELSE 'Data not found' END
                WHERE product_id=vPRODUCT;
            END BLOCK_E;

            SET @SQL_Text = CONCAT('SELECT * FROM stock_temp');
            PREPARE stmt FROM @SQL_Text;
            EXECUTE stmt;
            DEALLOCATE PREPARE stmt;
        ELSEIF vACTION = 5 THEN /* Stock Valuation */
            DROP TEMPORARY TABLE IF EXISTS `stock_temp`;
            CREATE TEMPORARY TABLE `stock_temp`(
                `temp_id` BIGINT(255) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                `branch_id` BIGINT(50),
                `location_id` BIGINT(255),
                `location_code` VARCHAR(255),
                `location_name` VARCHAR(255),
                `category_id` BIGINT(255),
                `category_name` VARCHAR(255),                
                `product_id` BIGINT(255),
                `product_code` VARCHAR(255),
                `product_name` VARCHAR(255),
                `product_unit` VARCHAR(255),
                `ref` VARCHAR(255),
                `qty_balance` DOUBLE(18,2) DEFAULT 0,
                `qty_in_price` DOUBLE(18,2) DEFAULT 0,
                `qty_in_price_total` DOUBLE(18,2) DEFAULT 0,
                `trans_item_trans_id` BIGINT(255),
                `trans_number` VARCHAR(255),               
                `contact_name` VARCHAR(255), 
                `trans_item_in_qty` DOUBLE(18,2) DEFAULT 0,
                `trans_item_date` DATETIME,
                `status` INT(5) DEFAULT 0,
                `message` VARCHAR(255),
                `total_data` INT(50),
                `search` VARCHAR(255),
                INDEX `LOCATION`(`location_id`) USING BTREE,
                INDEX `PRODUCT`(`product_id`) USING BTREE
            ) ENGINE=MEMORY;

            SELECT MIN(trans_item_date) INTO mDATE_START_APP FROM trans_items WHERE trans_item_branch_id=vBRANCH_ID;

            BLOCK_A:BEGIN /* Group for Product */
                DECLARE mLOCATION_ID BIGINT(255);
                DECLARE mPRODUCT_ID BIGINT(255);                
                DECLARE mIN_QTY DOUBLE(18,2);
                DECLARE mREF VARCHAR(255);                
                DECLARE mFINISHED INTEGER;
                DECLARE mCURSOR CURSOR FOR
                    SELECT trans_item_product_id, trans_item_location_id,
                        IFNULL(SUM(trans_item_in_qty-trans_item_out_qty),0) AS product_qty,
                        trans_item_ref
                    FROM trans_items
                    WHERE trans_item_product_type=1 AND trans_item_flag=1 AND trans_item_branch_id=vBRANCH_ID
                    -- WHERE trans_item_product_id=8 AND trans_item_location_id=5
                    GROUP BY trans_item_ref
                    HAVING SUM(trans_item_in_qty) - SUM(trans_item_out_qty) > 0
                    ORDER BY trans_item_date ASC;
                DECLARE CONTINUE HANDLER FOR NOT FOUND SET mFINISHED = 1;

                OPEN mCURSOR;
                LOOP_CURSOR:LOOP
                    FETCH mCURSOR INTO mPRODUCT_ID, mLOCATION_ID, mIN_QTY, mREF;
                    IF mFINISHED = 1 THEN
                        LEAVE LOOP_CURSOR;
                    END IF;
                    INSERT INTO `stock_temp` (`location_id`,`product_id`,`qty_balance`,`ref`,`branch_id`) 
                    VALUES (mLOCATION_ID,mPRODUCT_ID,mIN_QTY,mREF,vBRANCH_ID);
                END LOOP LOOP_CURSOR;
            END BLOCK_A;

            BLOCK_B:BEGIN /* Update Product & Category*/
                UPDATE stock_temp AS s 
                LEFT JOIN products AS p ON s.product_id=p.product_id
                LEFT JOIN categories AS c ON p.product_category_id=c.category_id
                SET s.product_code=p.product_code, s.product_name=p.product_name, s.product_unit=p.product_unit, 
                s.category_id=c.category_id, s.category_name=c.category_name;
            END BLOCK_B;
            
            BLOCK_C:BEGIN /* Update Location */
                UPDATE stock_temp AS s LEFT JOIN locations AS l ON s.location_id=l.location_id
                SET s.location_code=l.location_code, s.location_name=l.location_name;
            END BLOCK_C;

            BLOCK_D:BEGIN /* Update In Price by REF */
                UPDATE stock_temp AS s LEFT JOIN trans_items AS t 
                ON s.ref=t.trans_item_ref AND s.product_id=t.trans_item_product_id AND t.trans_item_position=1
                SET s.qty_in_price=t.trans_item_in_price, s.trans_item_trans_id=t.trans_item_trans_id,
                s.trans_item_date=t.trans_item_date, s.trans_item_in_qty=t.trans_item_in_qty;
            END BLOCK_D;

            BLOCK_E:BEGIN /* Update In Price Total */
                UPDATE stock_temp AS s
                SET s.qty_in_price_total=s.qty_in_price * s.qty_balance;
            END BLOCK_E;

            BLOCK_F:BEGIN /* Update Trans n Trans Item */
                UPDATE stock_temp AS s
                LEFT JOIN trans AS t ON s.trans_item_trans_id=t.trans_id
                LEFT JOIN contacts AS c ON t.trans_contact_id=c.contact_id
                SET s.trans_number=t.trans_number, s.contact_name=c.contact_name;
            END BLOCK_F;

			SELECT MIN(trans_item_date) INTO mRUNNING_DATE_START FROM trans_items;
			SET @running_date_start = DATE_FORMAT(mRUNNING_DATE_START, "%Y-%m-%d 00:00:00");
			SET @running_date_end = DATE_FORMAT(NOW(), "%Y-%m-%d 23:59:59");

            IF LENGTH(vEND) > 1 THEN SET @running_date_end = CONCAT(vEND,' 23:59:59'); END IF;            
            
            IF vLOCATION > 0 THEN SET @location = CONCAT(' AND location_id=',vLOCATION); ELSE SET @location = ''; END IF;
            IF vPRODUCT > 0 THEN SET @product = CONCAT(' AND product_id=',vPRODUCT); ELSE SET @product = ''; END IF;
            IF vCATEGORY > 0 THEN SET @category = CONCAT(' AND category_id=',vCATEGORY); ELSE SET @category = ''; END IF;
            IF LENGTH(vSEARCH) > 0 THEN SET @search = CONCAT(' AND product_name LIKE "%',vSEARCH,'%"'); ELSE SET @search = ''; END IF;

            -- SET @SQL_Text = CONCAT('SELECT *    
            -- FROM `stock_temp`
            -- LEFT JOIN `products` ON `trans_items`.`trans_item_product_id` = `products`.`product_id`
            -- LEFT JOIN `locations` ON `trans_items`.`trans_item_location_id` = `locations`.`location_id`
            -- LEFT JOIN `categories` ON `products`.`product_category_id` = `categories`.`category_id`
            -- WHERE `trans_item_branch_id` = ',vBRANCH_ID,'
            -- AND `trans_item_date` > "',@running_date_start,'"
            -- AND `trans_item_date` < "',@running_date_end,'"',@location,@product,@category,@search,'
            -- GROUP BY `trans_item_product_id` ORDER BY ',vORDER,' ',vDIR,'');
            SET @SQL_Text = CONCAT('SELECT *    
            FROM `stock_temp`
            WHERE `branch_id` = ',vBRANCH_ID,@location,@product,@category,@search,'
            ORDER BY ',vORDER,' ',vDIR,'');            
            -- SET @SQL_Text = CONCAT('SELECT * FROM stock_temp ORDER BY product_name ASC');
            PREPARE stmt FROM @SQL_Text;
            EXECUTE stmt;
            DEALLOCATE PREPARE stmt;
        ELSE
            SELECT * FROM products WHERE product_branch_id=1;
        END IF;
    END$$
DELIMITER ;

DELIMITER $$
DROP PROCEDURE IF EXISTS `sp_setup_account_from_branch` $$
CREATE PROCEDURE `sp_setup_account_from_branch`(IN vBRANCH_ID BIGINT(50), IN vSPECIALIST_ID BIGINT(50))
    BEGIN
        DECLARE mCOUNT_ACCOUNT INTEGER DEFAULT 0;
        DECLARE mCOUNT_ACCOUNT_MAP INTEGER DEFAULT 0;       
        SELECT COUNT(*) INTO mCOUNT_ACCOUNT FROM accounts WHERE account_branch_id=vBRANCH_ID;
        SELECT COUNT(*) INTO mCOUNT_ACCOUNT_MAP FROM accounts_maps WHERE account_map_branch_id=vBRANCH_ID;      
        
        IF ((mCOUNT_ACCOUNT = 0) AND (mCOUNT_ACCOUNT_MAP = 0)) THEN
            /* Get Insert = Branch Specialist Account to Accounts */
            BLOCK_A:BEGIN
                    INSERT INTO `accounts` (
                    `account_branch_id`,`account_parent_id`,
                    `account_group`,`account_group_sub`,`account_group_sub_name`,
                    `account_code`,`account_name`,
                    `account_side`,`account_show`,`account_tree`,`account_info`,
                    `account_date_created`,`account_date_updated`,`account_flag`, `account_session`
                    ) SELECT vBRANCH_ID, item_parent_id, item_group, item_group_sub, item_group_sub_name, item_code, item_name, 
                    item_side, item_show, CONCAT(1) AS account_tree, CONCAT('') AS account_info, NOW(), NOW(), item_flag, item_session
                    FROM branchs_specialists_accounts 
                    WHERE item_branch_specialist_id=1
                    ORDER BY item_group ASC, item_code ASC;
            END BLOCK_A;

            /* Get Insert = Branch Specialist Account Map to Account_Map */
            BLOCK_B:BEGIN
                    INSERT INTO `accounts_maps` (
                        `account_map_branch_id`,
                        `account_map_for_transaction`,
                        `account_map_type`,
                        `account_map_flag`,
                        `account_map_note`,
                        `account_map_formula`,
                        `account_map_date_created`,
                        `account_map_date_updated`,
                        `account_map_session`
                    ) SELECT vBRANCH_ID, account_map_for_transaction, account_map_type, account_map_flag, account_map_note,
                    account_map_formula, NOW(), NOW(), account_map_session
                    FROM branchs_specialists_accounts_maps
                    WHERE account_branch_specialist_id=1;
            END BLOCK_B;

            /* Update = Account Map */
            BLOCK_C:BEGIN
                UPDATE accounts_maps AS m 
                JOIN accounts AS a ON m.account_map_session=a.account_session
                SET m.account_map_account_id=a.account_id
                WHERE m.account_map_branch_id=vBRANCH_ID AND a.account_branch_id=vBRANCH_ID;
            END BLOCK_C;
        END IF;
     
        SET @QUERY := CONCAT('SELECT CONCAT(1) AS status, CONCAT("Success") AS message');
        PREPARE stmt FROM @QUERY;
        EXECUTE stmt;
        DEALLOCATE PREPARE stmt;
    END$$
DELIMITER ;

DELIMITER $$
DROP PROCEDURE IF EXISTS `sp_setup_user_menu_from_branch` $$
CREATE PROCEDURE `sp_setup_user_menu_from_branch`(IN vUSER_ID BIGINT(50))
    BEGIN
        INSERT INTO `users_menus`(`user_menu_user_id`,`user_menu_menu_parent_id`,`user_menu_menu_id`,
        `user_menu_action`,`user_menu_date_created`,`user_menu_date_updated`,`user_menu_flag`)
        SELECT CONCAT(vUSER_ID) AS user_id,`user_menu_menu_parent_id`,`user_menu_menu_id`,
        `user_menu_action`,`user_menu_date_created`,`user_menu_date_updated`,`user_menu_flag`
        FROM users_menus WHERE user_menu_user_id=1;
        
        SET @QUERY := CONCAT('SELECT CONCAT(1) AS status, CONCAT("Success") AS message');
        PREPARE stmt FROM @QUERY;
        EXECUTE stmt;
        DEALLOCATE PREPARE stmt;

    END $$
DELIMITER ;

DELIMITER $$
DROP PROCEDURE IF EXISTS `sp_trans_item_out`$$
CREATE PROCEDURE `sp_trans_item_out`(vTYPE INT(5),vDATE VARCHAR(255),vTRANS_ID BIGINT(50),vBRANCH_ID BIGINT(50),vPRODUCT_ID BIGINT(50),vLOCATION_ID BIGINT(50),vPRODUCT_UNIT VARCHAR(255),vOUT_QTY VARCHAR(255),vOUT_PRICE_SELL VARCHAR(255),vDISCOUNT VARCHAR(255),vPPN INT(5),vPPN_VALUE DOUBLE(18,2), vNOTE VARCHAR(255),vUSER_ID BIGINT(50),vFLAG INT(5),vQTY_PACK VARCHAR(255))
BEGIN
        DECLARE mSTATUS INT(5) DEFAULT 0;
        DECLARE mSTOCK_OUT_QTY_READY DOUBLE(18,4);
        DECLARE mSTOCK_OUT_PRICE_READY DOUBLE(18,4);
        DECLARE mSTOCK_OUT_REF VARCHAR(255);
        DECLARE mQTY_REMAINING DOUBLE(18,4);
        DECLARE mTOTAL VARCHAR(255) DEFAULT '0';
        
        DECLARE mFINISHED INTEGER;
        DECLARE mFOUND_ROW INTEGER;
        -- Get Stock on Location of Product        
        DECLARE mACTION_CURSOR CURSOR FOR       
        SELECT IFNULL(SUM(trans_item_in_qty-trans_item_out_qty),0), CASE WHEN trans_item_in_price > 0 THEN trans_item_in_price ELSE trans_item_out_price END AS price, trans_item_ref
        FROM trans_items
        WHERE trans_item_product_id=vPRODUCT_ID AND trans_item_location_id=vLOCATION_ID
        GROUP BY trans_item_ref
        HAVING SUM(trans_item_in_qty) - SUM(trans_item_out_qty) > 0
        ORDER BY trans_item_date ASC 
        LIMIT 1;
        DECLARE CONTINUE HANDLER FOR NOT FOUND SET mFINISHED = 1;   

        -- IF vTRANS_ID IS NULL OR vTRANS_ID = '' THEN SET vTRANS_ID = NULL; ELSE SET vTRANS_ID = vTRANS_ID; END IF;
        -- IF vNOTE IS NULL OR vNOTE = '' THEN SET vNOTE = NULL; ELSE SET vNOTE = vNOTE; END IF;    
        IF vTRANS_ID = 0 OR vTRANS_ID = '' THEN SET vTRANS_ID = NULL; ELSE SET vTRANS_ID = vTRANS_ID; END IF;
        IF vNOTE = 0 OR vTRANS_ID = '' THEN SET vNOTE = NULL; ELSE SET vNOTE = vNOTE; END IF;   

        OPEN mACTION_CURSOR;
        LOOP_1: LOOP
            FETCH mACTION_CURSOR INTO mSTOCK_OUT_QTY_READY, mSTOCK_OUT_PRICE_READY, mSTOCK_OUT_REF;
            IF mFINISHED = 1 THEN LEAVE LOOP_1; END IF;
        END LOOP LOOP_1;
        
        SET max_sp_recursion_depth=255;
        SET mFOUND_ROW = FOUND_ROWS();
        
        IF mFOUND_ROW > 0 THEN
            IF mSTOCK_OUT_QTY_READY >= vOUT_QTY THEN -- Jika Stok yang Tersedia lebih besar daripada yang diminta
                SET mSTOCK_OUT_PRICE_READY = ROUND(mSTOCK_OUT_PRICE_READY,4);
                SET vOUT_QTY = ROUND(vOUT_QTY,4);
                SET mTOTAL = (mSTOCK_OUT_PRICE_READY * vOUT_QTY) - vDISCOUNT;
                INSERT INTO `trans_items` (
                    `trans_item_branch_id`,`trans_item_type`,`trans_item_trans_id`,`trans_item_product_id`,`trans_item_location_id`,
                    `trans_item_product_type`,`trans_item_date`,`trans_item_unit`,
                    `trans_item_in_qty`,
                    `trans_item_in_price`,
                    `trans_item_out_qty`,
                    `trans_item_out_price`,
                    `trans_item_sell_price`,`trans_item_discount`,`trans_item_ppn`,`trans_item_ppn_value`,`trans_item_total`,`trans_item_note`,
                    `trans_item_date_created`,`trans_item_date_updated`,
                    `trans_item_user_id`,`trans_item_flag`,`trans_item_ref`,`trans_item_position`,`trans_item_pack`
                ) VALUES (
                    vBRANCH_ID,vTYPE,vTRANS_ID,vPRODUCT_ID,vLOCATION_ID,
                    1,vDATE,vPRODUCT_UNIT,
                    '0',
                    '0',
                    vOUT_QTY,
                    mSTOCK_OUT_PRICE_READY,
                    vOUT_PRICE_SELL,vDISCOUNT,vPPN,vPPN_VALUE,mTOTAL,vNOTE,
                    NOW(),NOW(),
                    vUSER_ID,vFLAG,mSTOCK_OUT_REF,2,
                    vQTY_PACK
                );        

                SELECT CONCAT('1') AS `status`, CONCAT('Operation Success') AS `message`;

            ELSE
                IF mSTOCK_OUT_QTY_READY > 0 THEN -- 1.6 > 0
                SET vOUT_QTY = ROUND(vOUT_QTY,4);
                SET mSTOCK_OUT_QTY_READY = ROUND(mSTOCK_OUT_QTY_READY,4);
                SET mQTY_REMAINING = vOUT_QTY - mSTOCK_OUT_QTY_READY; -- 3.9 = 5.5 - 1.6           
                SET mTOTAL = (mSTOCK_OUT_PRICE_READY * mSTOCK_OUT_QTY_READY) - vDISCOUNT;
                    -- SELECT CONCAT('CALL Procedure');
                    INSERT INTO `trans_items` (
                        `trans_item_branch_id`,
                        `trans_item_type`,
                        `trans_item_trans_id`,
                        `trans_item_product_id`,
                        `trans_item_location_id`,
                        `trans_item_product_type`,
                        `trans_item_date`,
                        `trans_item_unit`,
                        `trans_item_in_qty`,
                        `trans_item_in_price`,
                        `trans_item_out_qty`,
                        `trans_item_out_price`,
                        `trans_item_sell_price`,
                        `trans_item_discount`,
                        `trans_item_ppn`,
                        `trans_item_ppn_value`,
                        `trans_item_total`,
                        `trans_item_note`,
                        `trans_item_date_created`,
                        `trans_item_date_updated`,
                        `trans_item_user_id`,
                        `trans_item_flag`,
                        `trans_item_ref`,
                        `trans_item_position`,
                        `trans_item_pack`
                    )VALUES(
                        vBRANCH_ID,
                        vTYPE,
                        vTRANS_ID,
                        vPRODUCT_ID,
                        vLOCATION_ID,
                        1,
                        vDATE,
                        vPRODUCT_UNIT,
                        '0',
                        '0',
                        mSTOCK_OUT_QTY_READY,
                        mSTOCK_OUT_PRICE_READY,
                        vOUT_PRICE_SELL,
                        vDISCOUNT,
                        vPPN,
                        vPPN_VALUE,
                        mTOTAL,
                        vNOTE,
                        NOW(),
                        NOW(),
                        vUSER_ID,
                        vFLAG,
                        mSTOCK_OUT_REF,
                        2,
                        vQTY_PACK
                    );
                    /*
                    CALL `sp_trans_item_out`(
                        vTYPE,
                        vDATE,
                        vTRANS_ID,
                        vBRANCH_ID,
                        vPRODUCT_ID,
                        vLOCATION_ID,
                        vPRODUCT_UNIT,
                        mSTOCK_OUT_QTY_READY,
                        mSTOCK_OUT_PRICE_READY,
                        vDISCOUNT,
                        vPPN,
                        vNOTE,
                        vUSER_ID,
                        vFLAG,
                        vQTY_PACK
                    );
                    */
                    CALL `sp_trans_item_out`(
                        vTYPE,
                        vDATE,
                        vTRANS_ID,
                        vBRANCH_ID,
                        vPRODUCT_ID,
                        vLOCATION_ID,
                        vPRODUCT_UNIT,
                        mQTY_REMAINING,
                        vOUT_PRICE_SELL,
                        vDISCOUNT,
                        vPPN,
                        vPPN_VALUE,
                        vNOTE,
                        vUSER_ID,
                        vFLAG,
                        vQTY_PACK
                    );
                END IF;
            END IF;
        ELSE
            SELECT CONCAT('0') AS `status`, CONCAT('Stock not available') AS `message`;
        END IF;
    END$$
DELIMITER ;

DELIMITER $$
DROP PROCEDURE IF EXISTS `sp_trans_item_out_and_in`$$
CREATE PROCEDURE `sp_trans_item_out_and_in`(vTYPE INT(5),vDATE VARCHAR(255),vTRANS_ID BIGINT(50),vBRANCH_ID BIGINT(50),vPRODUCT_ID BIGINT(50),vLOCATION_ID BIGINT(50),vLOCATION_TO_ID BIGINT(50),vPRODUCT_UNIT VARCHAR(255),vOUT_QTY VARCHAR(255),vOUT_PRICE_SELL VARCHAR(255),vDISCOUNT VARCHAR(255),vPPN INT(5),vPPN_VALUE VARCHAR(50), vNOTE VARCHAR(255),vUSER_ID BIGINT(50),vFLAG INT(5),vSESSION VARCHAR(255))
BEGIN
    DECLARE mSTATUS INT(5) DEFAULT 0;
    DECLARE mSTOCK_OUT_QTY_READY DOUBLE(18,4);
    DECLARE mSTOCK_OUT_PRICE_READY DOUBLE(18,4);
    DECLARE mSTOCK_OUT_REF VARCHAR(255);
    DECLARE mQTY_REMAINING DOUBLE(18,4);
    DECLARE mTOTAL VARCHAR(255) DEFAULT '0';
    
    DECLARE mFINISHED INTEGER;
    DECLARE mFOUND_ROW INTEGER;
    DECLARE mACTION_CURSOR CURSOR FOR       
    -- Get Stock on Location of Product
    SELECT 
        IFNULL(SUM(trans_item_in_qty-trans_item_out_qty),0),
        CASE WHEN trans_item_out_price > 0 THEN trans_item_out_price
        ELSE trans_item_in_price 
        END AS price,
        trans_item_ref
    FROM trans_items
    WHERE trans_item_product_id=vPRODUCT_ID AND trans_item_location_id=vLOCATION_ID
    GROUP BY trans_item_ref
    HAVING SUM(trans_item_in_qty) - SUM(trans_item_out_qty) > 0
    ORDER BY trans_item_date ASC 
    LIMIT 1;
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET mFINISHED = 1;   
    
    -- IF vTRANS_ID IS NULL OR vTRANS_ID = '' THEN SET vTRANS_ID = NULL; ELSE SET vTRANS_ID = vTRANS_ID; END IF;
    -- IF vNOTE IS NULL OR vNOTE = '' THEN SET vNOTE = NULL; ELSE SET vNOTE = vNOTE; END IF;    
    IF vTRANS_ID = 0 OR vTRANS_ID = '' THEN SET vTRANS_ID = NULL; ELSE SET vTRANS_ID = vTRANS_ID; END IF;
    IF vNOTE = 0 OR vTRANS_ID = '' THEN SET vNOTE = NULL; ELSE SET vNOTE = vNOTE; END IF;   
    
    OPEN mACTION_CURSOR;
    LOOP_1: LOOP
        FETCH mACTION_CURSOR INTO mSTOCK_OUT_QTY_READY, mSTOCK_OUT_PRICE_READY, mSTOCK_OUT_REF;
        IF mFINISHED = 1 THEN
            LEAVE LOOP_1;
        END IF;
    END LOOP LOOP_1;
    SET max_sp_recursion_depth=255;
    SET mFOUND_ROW = FOUND_ROWS();
    IF mFOUND_ROW > 0 THEN
        -- SET mSTOCK_OUT_PRICE_READY = mSTOCK_OUT_PRICE_READY * vOUT_QTY;
        IF mSTOCK_OUT_QTY_READY >= vOUT_QTY THEN    
            -- SELECT CONCAT(mSTOCK_OUT_QTY_READY,' > ',vOUT_QTY);
            SET mTOTAL = mSTOCK_OUT_PRICE_READY * vOUT_QTY;
            -- Insert Out First
            INSERT INTO `trans_items` (
                `trans_item_branch_id`,
                `trans_item_type`,
                `trans_item_trans_id`,
                `trans_item_product_id`,
                `trans_item_location_id`,
                `trans_item_product_type`,
                `trans_item_date`,
                `trans_item_unit`,
                `trans_item_in_qty`,
                `trans_item_in_price`,
                `trans_item_out_qty`,
                `trans_item_out_price`,
                `trans_item_sell_price`,
                `trans_item_discount`,
                `trans_item_ppn`,
                `trans_item_ppn_value`,
                `trans_item_total`,
                `trans_item_note`,
                `trans_item_date_created`,
                `trans_item_date_updated`,
                `trans_item_user_id`,
                `trans_item_flag`,
                `trans_item_ref`,
                `trans_item_position`,
                `trans_item_session`
            ) VALUES (
                vBRANCH_ID,
                vTYPE,
                vTRANS_ID,
                vPRODUCT_ID,
                vLOCATION_ID,
                1,
                vDATE,
                vPRODUCT_UNIT,
                '0',
                '0',
                vOUT_QTY,
                mSTOCK_OUT_PRICE_READY,
                vOUT_PRICE_SELL,
                vDISCOUNT,
                vPPN,
                vPPN_VALUE,
                mTOTAL,
                vNOTE,
                NOW(),
                NOW(),
                vUSER_ID,
                vFLAG,
                mSTOCK_OUT_REF,
                2,
                vSESSION
            );        
            -- Insert In Second
            INSERT INTO `trans_items` (
                `trans_item_branch_id`,
                `trans_item_type`,
                `trans_item_trans_id`,
                `trans_item_product_id`,
                `trans_item_location_id`,
                `trans_item_product_type`,
                `trans_item_date`,
                `trans_item_unit`,
                `trans_item_in_qty`,
                `trans_item_in_price`,
                `trans_item_out_qty`,
                `trans_item_out_price`,
                `trans_item_sell_price`,
                `trans_item_discount`,
                `trans_item_ppn`,
                `trans_item_ppn_value`,
                `trans_item_total`,
                `trans_item_note`,
                `trans_item_date_created`,
                `trans_item_date_updated`,
                `trans_item_user_id`,
                `trans_item_flag`,
                `trans_item_ref`,
                `trans_item_position`,
                `trans_item_session`
            ) VALUES (
                vBRANCH_ID,
                vTYPE,
                vTRANS_ID,
                vPRODUCT_ID,
                vLOCATION_TO_ID,
                1,
                vDATE,
                vPRODUCT_UNIT,
                vOUT_QTY,
                mSTOCK_OUT_PRICE_READY,
                '0',
                '0',
                vOUT_PRICE_SELL,
                vDISCOUNT,
                vPPN,
                vPPN_VALUE,
                mTOTAL,
                vNOTE,
                NOW(),
                NOW(),
                vUSER_ID,
                vFLAG,
                mSTOCK_OUT_REF,
                1,
                vSESSION
            );                      
            SELECT CONCAT('1') AS `status`, CONCAT('Operation Success') AS `message`;
        ELSE
            IF mSTOCK_OUT_QTY_READY > 0 THEN
                SET mTOTAL = mSTOCK_OUT_PRICE_READY * mSTOCK_OUT_QTY_READY;             
                -- SELECT CONCAT('CALL Procedure');
                INSERT INTO `trans_items` (
                    `trans_item_branch_id`,
                    `trans_item_type`,
                    `trans_item_trans_id`,
                    `trans_item_product_id`,
                    `trans_item_location_id`,
                    `trans_item_product_type`,                  
                    `trans_item_date`,
                    `trans_item_unit`,
                    `trans_item_in_qty`,
                    `trans_item_in_price`,
                    `trans_item_out_qty`,
                    `trans_item_out_price`,
                    `trans_item_sell_price`,
                    `trans_item_discount`,
                    `trans_item_ppn`,
                    `trans_item_ppn_value`,
                    `trans_item_total`,
                    `trans_item_note`,
                    `trans_item_date_created`,
                    `trans_item_date_updated`,
                    `trans_item_user_id`,
                    `trans_item_flag`,
                    `trans_item_ref`,
                    `trans_item_position`,
                    `trans_item_session`
                ) VALUES (
                    vBRANCH_ID,
                    vTYPE,
                    vTRANS_ID,
                    vPRODUCT_ID,
                    vLOCATION_ID,
                    1,
                    vDATE,
                    vPRODUCT_UNIT,
                    '0',
                    '0',
                    mSTOCK_OUT_QTY_READY,
                    mSTOCK_OUT_PRICE_READY,
                    vOUT_PRICE_SELL,
                    vDISCOUNT,
                    vPPN,
                    vPPN_VALUE,
                    mTOTAL,
                    vNOTE,
                    NOW(),
                    NOW(),
                    vUSER_ID,
                    vFLAG,
                    mSTOCK_OUT_REF,
                    2,
                    vSESSION
                );      
                -- Insert In Second
                INSERT INTO `trans_items` (
                    `trans_item_branch_id`,
                    `trans_item_type`,
                    `trans_item_trans_id`,
                    `trans_item_product_id`,
                    `trans_item_location_id`,
                    `trans_item_product_type`,
                    `trans_item_date`,
                    `trans_item_unit`,
                    `trans_item_in_qty`,
                    `trans_item_in_price`,
                    `trans_item_out_qty`,
                    `trans_item_out_price`,
                    `trans_item_sell_price`,
                    `trans_item_discount`,
                    `trans_item_ppn`,
                    `trans_item_ppn_value`,
                    `trans_item_total`,
                    `trans_item_note`,
                    `trans_item_date_created`,
                    `trans_item_date_updated`,
                    `trans_item_user_id`,
                    `trans_item_flag`,
                    `trans_item_ref`,
                    `trans_item_position`,
                    `trans_item_session`
                ) VALUES (
                    vBRANCH_ID,
                    vTYPE,
                    vTRANS_ID,
                    vPRODUCT_ID,
                    vLOCATION_TO_ID,
                    1,
                    vDATE,
                    vPRODUCT_UNIT,
                    mSTOCK_OUT_QTY_READY,
                    mSTOCK_OUT_PRICE_READY,
                    '0',
                    '0',                    
                    vOUT_PRICE_SELL,
                    vDISCOUNT,
                    vPPN,
                    vPPN_VALUE,
                    mTOTAL,
                    vNOTE,
                    NOW(),
                    NOW(),
                    vUSER_ID,
                    vFLAG,
                    mSTOCK_OUT_REF,
                    1,
                    vSESSION
                );                      
                SET mQTY_REMAINING = vOUT_QTY - mSTOCK_OUT_QTY_READY;                       
                CALL `sp_trans_item_out_and_in`(
                    vTYPE,
                    vDATE,
                    vTRANS_ID,
                    vBRANCH_ID,
                    vPRODUCT_ID,
                    vLOCATION_ID,
                    vLOCATION_TO_ID,
                    vPRODUCT_UNIT,
                    mQTY_REMAINING,
                    vOUT_PRICE_SELL,
                    vDISCOUNT,
                    vPPN,
                    vPPN_VALUE,
                    vNOTE,
                    vUSER_ID,
                    vFLAG,
                    vSESSION
                );
            END IF;
        END IF;
        -- SELECT mSTOCK_OUT_QTY_READY, mSTOCK_OUT_PRICE_READY, mSTOCK_OUT_REF;     
    ELSE
        SELECT CONCAT('0') AS `status`, CONCAT('Stock not available') AS `message`;
    END IF;
    END$$
DELIMITER ;

DELIMITER $$
DROP PROCEDURE IF EXISTS `sp_trans_update_return`$$
CREATE PROCEDURE `sp_trans_update_return`(IN vACTION VARCHAR(50), IN vTRANS_ID BIGINT(50))
    BEGIN
        DECLARE mTRANS_TYPE INT(5);
        DECLARE mTRANS_SUBTOTAL DOUBLE(18,2) DEFAULT 0;
        DECLARE mTRANS_SOURCE_ID BIGINT(255);
        DECLARE mTRANS_TOTAL DOUBLE(18,2) DEFAULT 0;
        DECLARE mFOUND_ROWS INT(255);
        DECLARE mFINISHED INT(5) DEFAULT 0;
        DECLARE mTRANS_CURRENT_RETURN DOUBLE(18,2) DEFAULT 0;

        SELECT trans_type INTO mTRANS_TYPE FROM trans WHERE trans_id=vTRANS_ID;

        IF vACTION = 'create' THEN
            IF mTRANS_TYPE = 3 OR mTRANS_TYPE = 4 THEN

                -- SELECT t.trans_item_total, s.trans_item_trans_id INTO mTRANS_SUBTOTAL, mTRANS_SOURCE_ID
                -- FROM trans_items AS t
                -- LEFT JOIN (
                --     SELECT trans_item_trans_id, trans_item_ref FROM trans_items WHERE trans_item_type=2
                -- ) AS s ON t.trans_item_ref=s.trans_item_ref
                -- LEFT JOIN trans ON trans_id=trans_id_source
                -- WHERE t.trans_item_trans_id=vTRANS_ID;

                -- SELECT trans_item_total, trans_item_trans_id INTO mTRANS_SUBTOTAL, mTRANS_SOURCE_ID
                -- FROM trans_items
                -- WHERE trans_item_trans_id IN (
                --     SELECT trans_id FROM trans WHERE trans_id_source=vTRANS_ID
                -- );

                SELECT trans_id_source INTO mTRANS_SOURCE_ID FROM trans WHERE trans_id=vTRANS_ID;
                SELECT IFNULL(SUM(trans_total),0) INTO mTRANS_SUBTOTAL FROM trans WHERE trans_id_source=mTRANS_SOURCE_ID; 

                -- SET mFOUND_ROWS = FOUND_ROWS();

                -- IF mFOUND_ROWS > 0 THEN
                --     WHILE mFINISHED <= mFOUND_ROWS DO
                --         SET mTRANS_TOTAL = mTRANS_TOTAL + mTRANS_SUBTOTAL;
                --         SET mFINISHED = mFINISHED +1;
                --     END WHILE;
                -- END IF;
                
                /* Update Trans Pembelian ID from Retur ID*/
                UPDATE trans 
                SET trans_return=mTRANS_SUBTOTAL
                WHERE trans_id=mTRANS_SOURCE_ID;
            END IF;
        ELSEIF vACTION = 'update' THEN
            IF mTRANS_TYPE = 3 OR mTRANS_TYPE = 4 THEN

                -- SELECT t.trans_item_total, s.trans_item_trans_id INTO mTRANS_SUBTOTAL, mTRANS_SOURCE_ID
                -- FROM trans_items AS t
                -- LEFT JOIN (
                --     SELECT trans_item_trans_id, trans_item_ref FROM trans_items WHERE trans_item_type=2
                -- ) AS s ON t.trans_item_ref=s.trans_item_ref
                -- LEFT JOIN trans ON trans_id=trans_id_source
                -- WHERE t.trans_item_trans_id=vTRANS_ID;

                -- SELECT trans_item_total, trans_item_trans_id INTO mTRANS_SUBTOTAL, mTRANS_SOURCE_ID
                -- FROM trans_items
                -- WHERE trans_item_trans_id IN (
                --     SELECT trans_id FROM trans WHERE trans_id_source=vTRANS_ID
                -- );

                SELECT trans_id_source INTO mTRANS_SOURCE_ID FROM trans WHERE trans_id=vTRANS_ID;
                SELECT IFNULL(SUM(trans_total),0) INTO mTRANS_SUBTOTAL FROM trans WHERE trans_id_source=mTRANS_SOURCE_ID;

                -- SET mFOUND_ROWS = FOUND_ROWS();

                -- IF mFOUND_ROWS > 0 THEN
                --     WHILE mFINISHED <= mFOUND_ROWS DO
                --         SET mTRANS_TOTAL = mTRANS_TOTAL + mTRANS_SUBTOTAL;
                --         SET mFINISHED = mFINISHED +1;
                --     END WHILE;
                -- END IF;
                
                /* Update Trans Pembelian ID from Retur ID*/
                UPDATE trans 
                SET trans_return=mTRANS_SUBTOTAL
                WHERE trans_id=mTRANS_SOURCE_ID;
            END IF;
        ELSEIF vACTION = 'delete' THEN
            -- SET mTRANS_SOURCE_ID = vTRANS_ID;
            IF mTRANS_TYPE = 3 THEN /* Retur Beli*/
                
                SELECT trans_id_source, trans_total INTO mTRANS_SOURCE_ID, mTRANS_TOTAL 
                FROM trans WHERE trans_id=vTRANS_ID;

                SELECT trans_return INTO mTRANS_CURRENT_RETURN FROM trans WHERE trans_id=mTRANS_SOURCE_ID;

                /* Get Current Total Return */
                -- SELECT trans_return INTO mTRANS_CURRENT_RETURN FROM trans WHERE trans_id=vTRANS_ID;
                IF mTRANS_CURRENT_RETURN > 0 THEN
                    SET mTRANS_TOTAL = mTRANS_CURRENT_RETURN - mTRANS_TOTAL;
                ELSE 
                    SET mTRANS_TOTAL = mTRANS_TOTAL;
                END IF;
                -- SET mTRANS_TOTAL = mTRANS_CURRENT_RETURN;

                /* Update Trans Pembelian ID from Retur ID*/
                UPDATE trans 
                SET trans_return= mTRANS_TOTAL
                WHERE trans_id=mTRANS_SOURCE_ID;
            ELSEIF mTRANS_TYPE = 4 THEN /* Retur Jual */
                SELECT trans_id_source, trans_total INTO mTRANS_SOURCE_ID, mTRANS_TOTAL 
                FROM trans WHERE trans_id=vTRANS_ID;

                SELECT trans_return INTO mTRANS_CURRENT_RETURN FROM trans WHERE trans_id=mTRANS_SOURCE_ID;

                /* Get Current Total Return */
                -- SELECT trans_return INTO mTRANS_CURRENT_RETURN FROM trans WHERE trans_id=vTRANS_ID;
                IF mTRANS_CURRENT_RETURN > 0 THEN
                    SET mTRANS_TOTAL = mTRANS_CURRENT_RETURN - mTRANS_TOTAL;
                ELSE 
                    SET mTRANS_TOTAL = mTRANS_TOTAL;
                END IF;
                -- SET mTRANS_TOTAL = mTRANS_CURRENT_RETURN;

                /* Update Trans Penjualan ID from Retur ID*/
                UPDATE trans 
                SET trans_return= mTRANS_TOTAL
                WHERE trans_id=mTRANS_SOURCE_ID;
            END IF; 
        END IF; 
    END$$
DELIMITER ;

DELIMITER $$
DROP PROCEDURE IF EXISTS `sp_user_menu`$$
CREATE PROCEDURE `sp_user_menu`(vUSER_ID BIGINT(50))
    BEGIN
        DROP TEMPORARY TABLE IF EXISTS users_menus_temp;
        CREATE TEMPORARY TABLE users_menus_temp (
            `ID` INT(255) NOT NULL AUTO_INCREMENT,
            `MENU_PARENT_ID` BIGINT(50),
            `MENU_PARENT_NAME` VARCHAR(255),
            `MENU_CHILD_ID` BIGINT(50),
            `MENU_CHILD_NAME` VARCHAR(255),
            `MENU_ICON` VARCHAR(255),
            `MENU_LINK` VARCHAR(255),
            `MENU_SORTING` INT(255),
            `MENU_FLAG` VARCHAR(255),
            `USER_ID` BIGINT(50),
            `ACCESS_VIEW` INT(1) DEFAULT 0,
            `ACCESS_CREATE` INT(1) DEFAULT 0,
            `ACCESS_READ` INT(1) DEFAULT 0,
            `ACCESS_UPDATE` INT(1) DEFAULT 0,
            `ACCESS_DELETE` INT(1) DEFAULT 0,
            `ACCESS_PRINT` INT(1) DEFAULT 0,
            `ACCESS_APPROVAL` INT(1) DEFAULT 0,                                             
            PRIMARY KEY (`ID`),
            INDEX `ID`(`ID`) USING BTREE
        );

        BLOCK_A:BEGIN /*Fetch All Parent*/
        
            DECLARE mMENU_PARENT_ID BIGINT(50);
            DECLARE mMENU_PARENT_NAME VARCHAR(255);
            DECLARE mMENU_ICON VARCHAR(255);
            DECLARE mMENU_LINK VARCHAR(255);
            DECLARE mMENU_SORTING INT(5);
            DECLARE mMENU_FLAG INT(1);
        
            DECLARE mFINISHED INTEGER;
            DECLARE mFOUND_ROW INTEGER;
            DECLARE mACTION_CURSOR CURSOR FOR       
                SELECT menu_id, menu_name, menu_icon, menu_link, menu_sorting, menu_flag
                FROM menus WHERE menu_parent_id=0 AND menu_flag=1;
            DECLARE CONTINUE HANDLER FOR NOT FOUND SET mFINISHED = 1;   

            OPEN mACTION_CURSOR;
            LOOP_1: LOOP
                FETCH mACTION_CURSOR INTO mMENU_PARENT_ID, mMENU_PARENT_NAME, mMENU_ICON, mMENU_LINK, mMENU_SORTING, mMENU_FLAG;
                IF mFINISHED = 1 THEN
                    LEAVE LOOP_1;
                END IF;

                INSERT INTO users_menus_temp (`MENU_PARENT_ID`,`MENU_PARENT_NAME`,`MENU_CHILD_ID`,`MENU_CHILD_NAME`,`MENU_ICON`,`MENU_LINK`,`MENU_SORTING`,`MENU_FLAG`,`USER_ID`) 
                VALUES (mMENU_PARENT_ID, mMENU_PARENT_NAME, 0, '', mMENU_ICON, mMENU_LINK, mMENU_SORTING, mMENU_FLAG, vUSER_ID);
            END LOOP LOOP_1;
        END BLOCK_A;

        BLOCK_B:BEGIN /*Fetch All Parent*/

            DECLARE mMENU_PARENT_ID BIGINT(50); 
            DECLARE mMENU_CHILD_ID BIGINT(50);
            DECLARE mMENU_CHILD_NAME VARCHAR(255);
            DECLARE mMENU_ICON VARCHAR(255);
            DECLARE mMENU_LINK VARCHAR(255);
            DECLARE mMENU_SORTING INT(5);
            DECLARE mMENU_FLAG INT(1);
        
            DECLARE mFINISHED INTEGER;
            DECLARE mFOUND_ROW INTEGER;
            DECLARE mACTION_CURSOR CURSOR FOR       
                SELECT menu_parent_id, menu_id, menu_name, menu_icon, menu_link, menu_sorting, menu_flag
                FROM menus WHERE menu_parent_id > 0 AND menu_flag=1 ORDER BY menu_sorting ASC;
            DECLARE CONTINUE HANDLER FOR NOT FOUND SET mFINISHED = 1;   

            OPEN mACTION_CURSOR;
            LOOP_1: LOOP
                FETCH mACTION_CURSOR INTO mMENU_PARENT_ID, mMENU_CHILD_ID, mMENU_CHILD_NAME, mMENU_ICON, mMENU_LINK, mMENU_SORTING, mMENU_FLAG;
                IF mFINISHED = 1 THEN
                    LEAVE LOOP_1;
                END IF;

                INSERT INTO users_menus_temp (`MENU_PARENT_ID`,`MENU_PARENT_NAME`,`MENU_CHILD_ID`,`MENU_CHILD_NAME`,`MENU_ICON`,`MENU_LINK`,`MENU_SORTING`,`MENU_FLAG`,`USER_ID`) 
                VALUES (mMENU_PARENT_ID, '', mMENU_CHILD_ID, mMENU_CHILD_NAME, mMENU_ICON, mMENU_LINK, mMENU_SORTING, mMENU_FLAG, vUSER_ID);
            END LOOP LOOP_1;
        END BLOCK_B;

        BLOCK_C:BEGIN
            -- 1=view, 2=create, 3=read, 4=update, 5=delete, 6=approve, 7=print
            -- Update View Access
            UPDATE users_menus_temp JOIN users_menus
            ON users_menus_temp.menu_child_id=users_menus.user_menu_menu_id
            SET users_menus_temp.access_view=IFNULL(users_menus.user_menu_flag,0)
            WHERE users_menus.user_menu_menu_id=users_menus_temp.menu_child_id 
            AND users_menus_temp.menu_child_id > 0
            AND users_menus.user_menu_user_id=vUSER_ID 
            AND users_menus.user_menu_action=1; 

            -- Update Create Access
            UPDATE users_menus_temp JOIN users_menus
            ON users_menus_temp.menu_child_id=users_menus.user_menu_menu_id
            SET users_menus_temp.access_create=IFNULL(users_menus.user_menu_flag,0)
            WHERE users_menus.user_menu_menu_id=users_menus_temp.menu_child_id 
            AND users_menus_temp.menu_child_id > 0
            AND users_menus.user_menu_user_id=vUSER_ID 
            AND users_menus.user_menu_action=2;
            
            -- Update Read Access
            UPDATE users_menus_temp JOIN users_menus
            ON users_menus_temp.menu_child_id=users_menus.user_menu_menu_id
            SET users_menus_temp.access_read=IFNULL(users_menus.user_menu_flag,0)
            WHERE users_menus.user_menu_menu_id=users_menus_temp.menu_child_id 
            AND users_menus_temp.menu_child_id > 0
            AND users_menus.user_menu_user_id=vUSER_ID 
            AND users_menus.user_menu_action=3;
            
            -- Update Update Access
            UPDATE users_menus_temp JOIN users_menus
            ON users_menus_temp.menu_child_id=users_menus.user_menu_menu_id
            SET users_menus_temp.access_update=IFNULL(users_menus.user_menu_flag,0)
            WHERE users_menus.user_menu_menu_id=users_menus_temp.menu_child_id 
            AND users_menus_temp.menu_child_id > 0
            AND users_menus.user_menu_user_id=vUSER_ID 
            AND users_menus.user_menu_action=4; 
            
            -- Update Delete Access
            UPDATE users_menus_temp JOIN users_menus
            ON users_menus_temp.menu_child_id=users_menus.user_menu_menu_id
            SET users_menus_temp.access_delete=IFNULL(users_menus.user_menu_flag,0)
            WHERE users_menus.user_menu_menu_id=users_menus_temp.menu_child_id 
            AND users_menus_temp.menu_child_id > 0
            AND users_menus.user_menu_user_id=vUSER_ID 
            AND users_menus.user_menu_action=5;

            -- Update Approval Access
            UPDATE users_menus_temp JOIN users_menus
            ON users_menus_temp.menu_child_id=users_menus.user_menu_menu_id
            SET users_menus_temp.access_approval=IFNULL(users_menus.user_menu_flag,0)
            WHERE users_menus.user_menu_menu_id=users_menus_temp.menu_child_id 
            AND users_menus_temp.menu_child_id > 0
            AND users_menus.user_menu_user_id=vUSER_ID 
            AND users_menus.user_menu_action=6;     
            
            -- Update Print Access
            UPDATE users_menus_temp JOIN users_menus
            ON users_menus_temp.menu_child_id=users_menus.user_menu_menu_id
            SET users_menus_temp.access_print=IFNULL(users_menus.user_menu_flag,0)
            WHERE users_menus.user_menu_menu_id=users_menus_temp.menu_child_id 
            AND users_menus_temp.menu_child_id > 0
            AND users_menus.user_menu_user_id=vUSER_ID 
            AND users_menus.user_menu_action=7;
        END BLOCK_C;

        BLOCK_D:BEGIN
            UPDATE users_menus_temp
            LEFT JOIN menus AS m ON users_menus_temp.menu_parent_id=m.menu_id
            SET users_menus_temp.menu_sorting = m.menu_sorting
            WHERE users_menus_temp.menu_parent_id = m.menu_id;
        END BLOCK_D;

        SET @SQL_Text := CONCAT('SELECT * FROM users_menus_temp WHERE user_id=',vUSER_ID,' ORDER BY menu_sorting ASC');
        
        /* Prepare Query Statement */
        PREPARE stmt FROM @SQL_Text;
        EXECUTE stmt;
        DEALLOCATE PREPARE stmt;        
    END$$
DELIMITER ;

DELIMITER $$
DROP PROCEDURE IF EXISTS `sp_trans_history`$$
CREATE PROCEDURE `sp_trans_history`(
  IN vTRANS_ID BIGINT(255)
)
BEGIN

    /* Prepare Temporary Table */
    DROP TEMPORARY TABLE IF EXISTS temps;
    CREATE TEMPORARY TABLE temps (
        `temp_id` BIGINT(50) NOT NULL AUTO_INCREMENT PRIMARY KEY,
        `temp_date` DATETIME,
        `temp_trans_id` BIGINT(255),                
        `temp_source_id` BIGINT(255),
        `temp_session` VARCHAR(255),        
        `temp_number` VARCHAR(255),
        `temp_type` VARCHAR(255),
        `temp_transaction_type` VARCHAR(255),
        `temp_debit` DOUBLE(18,2) DEFAULT 0,
        `temp_credit` DOUBLE(18,2) DEFAULT 0,
        `temp_note` VARCHAR(255),
        `temp_memo` VARCHAR(255),
        `temp_group_session` VARCHAR(255),
        -- `temp_flag` INT(5),
        -- `temp_status` INT(5) DEFAULT 0,
        -- `temp_message` VARCHAR(255),
        -- `temp_total_data` INT(255) DEFAULT 0,
        INDEX `ID`(`temp_id`) USING BTREE
    ) ENGINE=MEMORY;

    BLOCK_0:BEGIN
        DECLARE mTRANS_TYPE INT(5);
        SELECT trans_type INTO mTRANS_TYPE FROM trans WHERE trans_id=vTRANS_ID;
        
        IF mTRANS_TYPE = 1 THEN -- Pembelian
            /* Get Trans Buy */
            BLOCK_A:BEGIN
                INSERT INTO temps (`temp_date`,`temp_source_id`,`temp_trans_id`,`temp_session`,`temp_number`,`temp_type`,`temp_credit`, `temp_transaction_type`,`temp_memo`) 
                SELECT `trans_date`, `trans_id`, `trans_id`, `trans_session`, `trans_number`, `trans_type`, `trans_total_dpp`+`trans_total_ppn`, CONCAT('trans'), `trans_note` 
                FROM trans WHERE trans_id=vTRANS_ID;
            END BLOCK_A;    
            
            /* Get Search Return if any*/
            BLOCK_B:BEGIN
                DECLARE mTRANS_RETURN_ID BIGINT(255);
                DECLARE mFINISHED INTEGER;
                DECLARE mTRANS_ID BIGINT(255);
                DECLARE mTRANS_ID_SOURCE BIGINT(255);                
                DECLARE mTRANS_SESSION VARCHAR(255);
                DECLARE mTRANS_NUMBER VARCHAR(255);
                DECLARE mTRANS_DATE DATETIME;
                DECLARE mTRANS_TOTAL DOUBLE(18,2);
                DECLARE mTRANS_NOTE VARCHAR(255);
                DECLARE mCURSOR CURSOR FOR 
                    SELECT trans_id, trans_id_source, trans_session, trans_number, trans_date, trans_total, trans_note 
                    FROM trans WHERE trans_id_source=vTRANS_ID;
                DECLARE CONTINUE HANDLER FOR NOT FOUND SET mFINISHED = 1;

                OPEN mCURSOR;
                -- WHILE mFINISHED < FOUND_ROWS() DO
                LOOP_1:LOOP
                -- WHILE mFINISHED < FOUND_ROWS() DO
                    FETCH mCURSOR INTO mTRANS_ID, mTRANS_ID_SOURCE, mTRANS_SESSION, mTRANS_NUMBER, mTRANS_DATE, mTRANS_TOTAL, mTRANS_NOTE;
                    IF mFINISHED = 1 THEN LEAVE LOOP_1; END IF;

                    INSERT INTO temps (`temp_date`,`temp_source_id`,`temp_trans_id`,`temp_session`,`temp_number`,`temp_type`,`temp_debit`,`temp_transaction_type`,`temp_memo`) 
                    VALUES (mTRANS_DATE,mTRANS_ID_SOURCE,mTRANS_ID,mTRANS_SESSION,mTRANS_NUMBER,mTRANS_TYPE,mTRANS_TOTAL, CONCAT('trans'),mTRANS_NOTE);
                    SET mFINISHED = mFINISHED + 1;
                -- END WHILE;
                END LOOP LOOP_1;
            END BLOCK_B;
        
            BLOCK_C:BEGIN
                DECLARE mTRANS_RETURN_ID BIGINT(255);
                DECLARE mFINISHED INTEGER;
                DECLARE mTRANS_ID BIGINT(255);
                DECLARE mJOURNAL_ID BIGINT(255);                
                DECLARE mTRANS_ID_SOURCE BIGINT(255);                
                DECLARE mTRANS_SESSION VARCHAR(255);
                DECLARE mTRANS_NUMBER VARCHAR(255);
                DECLARE mTRANS_DATE DATETIME;
                DECLARE mTRANS_TOTAL DOUBLE(18,2);
                DECLARE mGROUP_SESSION VARCHAR(255);
                DECLARE mJOURNAL_NOTE VARCHAR(255);
                DECLARE mCURSOR CURSOR FOR 
                    SELECT journal_item_journal_id, journal_item_trans_id, journal_session, journal_number, 
                    journal_item_date, journal_item_debit, journal_item_group_session, journal_note 
                    FROM journals_items 
                    LEFT JOIN journals ON journal_item_journal_id=journal_id
                    WHERE journal_item_trans_id=vTRANS_ID AND journal_item_type=1;
                DECLARE CONTINUE HANDLER FOR NOT FOUND SET mFINISHED = 1;

                OPEN mCURSOR;
                -- WHILE mFINISHED < FOUND_ROWS() DO
                LOOP_1:LOOP
                    FETCH mCURSOR INTO mJOURNAL_ID, mTRANS_ID, mTRANS_SESSION, mTRANS_NUMBER, mTRANS_DATE, mTRANS_TOTAL, mGROUP_SESSION, mJOURNAL_NOTE;
                    IF mFINISHED = 1 THEN LEAVE LOOP_1; END IF;
                    INSERT INTO temps (`temp_date`,`temp_source_id`,`temp_trans_id`,`temp_session`,`temp_number`,`temp_type`,`temp_debit`,`temp_transaction_type`,`temp_group_session`,`temp_memo`) 
                    VALUES (mTRANS_DATE,mTRANS_ID,mJOURNAL_ID,mTRANS_SESSION,mTRANS_NUMBER,mTRANS_TYPE,mTRANS_TOTAL,CONCAT('journals'),mGROUP_SESSION,mJOURNAL_NOTE);
                    -- SET mFINISHED = mFINISHED + 1;
                END LOOP LOOP_1;
                -- END WHILE;
            END BLOCK_C;

            BLOCK_D:BEGIN
                UPDATE temps
                LEFT JOIN trans ON temp_trans_id=trans_id
                LEFT JOIN contacts ON trans_contact_id=contact_id 
                SET temp_note=CONCAT('[',contact_code,'] - ',contact_name)
                WHERE temp_transaction_type='trans';

                UPDATE temps
                LEFT JOIN journals_items ON temp_group_session=journal_item_group_session AND journal_item_position=1 AND journal_item_credit > 0
                LEFT JOIN accounts ON journal_item_account_id=account_id 
                SET temp_note=CONCAT('[',account_code,'] - ',account_name)
                WHERE temp_transaction_type='journals';
            END BLOCK_D;

            BLOCK_FINAL:BEGIN
                DECLARE mBALANCE DOUBLE(18,2) DEFAULT 0;
                SELECT 
                    CASE WHEN temp_credit > 0 THEN temp_credit
                    WHEN temp_debit > 0 THEN temp_debit
                    ELSE 0
                    END INTO mBALANCE
                FROM temps WHERE temp_id=1;

                SELECT *,
                    @balance := @balance - temps.temp_credit - temps.temp_debit AS temp_balance
                FROM (SELECT @balance := mBALANCE + mBALANCE) AS start_balance
                CROSS JOIN (
                    SELECT * FROM temps
                ) AS temps
                ORDER BY temps.temp_date ASC;             
            END BLOCK_FINAL;

        ELSEIF mTRANS_TYPE = 2 THEN -- Penjualan
            /* Get Trans Buy */
            BLOCK_A:BEGIN
                INSERT INTO temps (`temp_date`,`temp_source_id`,`temp_trans_id`,`temp_session`,`temp_number`,`temp_type`,`temp_debit`, `temp_transaction_type`) 
                SELECT `trans_date`, `trans_id`, `trans_id`, `trans_session`, `trans_number`, `trans_type`, (`trans_total_dpp`+`trans_total_ppn`) - `trans_discount`, CONCAT('trans') 
                FROM trans WHERE trans_id=vTRANS_ID;
            END BLOCK_A;    
            
            /* Get Search Return if any*/
            BLOCK_B:BEGIN
                DECLARE mTRANS_RETURN_ID BIGINT(255);
                DECLARE mFINISHED INTEGER;
                DECLARE mTRANS_ID BIGINT(255);
                DECLARE mTRANS_ID_SOURCE BIGINT(255);                
                DECLARE mTRANS_SESSION VARCHAR(255);
                DECLARE mTRANS_NUMBER VARCHAR(255);
                DECLARE mTRANS_DATE DATETIME;
                DECLARE mTRANS_TOTAL DOUBLE(18,2);
                DECLARE mCURSOR CURSOR FOR 
                    SELECT trans_id, trans_id_source, trans_session, trans_number, trans_date, trans_total 
                    FROM trans WHERE trans_id_source=vTRANS_ID;
                DECLARE CONTINUE HANDLER FOR NOT FOUND SET mFINISHED = 1;

                OPEN mCURSOR;
                -- WHILE mFINISHED < FOUND_ROWS() DO
                LOOP_1:LOOP
                -- WHILE mFINISHED < FOUND_ROWS() DO
                    FETCH mCURSOR INTO mTRANS_ID, mTRANS_ID_SOURCE, mTRANS_SESSION, mTRANS_NUMBER, mTRANS_DATE, mTRANS_TOTAL;
                    IF mFINISHED = 1 THEN LEAVE LOOP_1; END IF;

                    INSERT INTO temps (`temp_date`,`temp_source_id`,`temp_trans_id`,`temp_session`,`temp_number`,`temp_type`,`temp_credit`,`temp_transaction_type`) 
                    VALUES (mTRANS_DATE,mTRANS_ID_SOURCE,mTRANS_ID,mTRANS_SESSION,mTRANS_NUMBER,mTRANS_TYPE,mTRANS_TOTAL, CONCAT('trans'));
                    SET mFINISHED = mFINISHED + 1;
                -- END WHILE;
                END LOOP LOOP_1;
            END BLOCK_B;
        
            BLOCK_C:BEGIN
                DECLARE mTRANS_RETURN_ID BIGINT(255);
                DECLARE mFINISHED INTEGER;
                DECLARE mTRANS_ID BIGINT(255);
                DECLARE mJOURNAL_ID BIGINT(255);                
                DECLARE mTRANS_ID_SOURCE BIGINT(255);                
                DECLARE mTRANS_SESSION VARCHAR(255);
                DECLARE mTRANS_NUMBER VARCHAR(255);
                DECLARE mTRANS_DATE DATETIME;
                DECLARE mTRANS_TOTAL DOUBLE(18,2);
                DECLARE mGROUP_SESSION VARCHAR(255);
                DECLARE mCURSOR CURSOR FOR 
                    SELECT journal_item_journal_id, journal_item_trans_id, journal_session, journal_number, journal_item_date, journal_item_credit, journal_item_group_session 
                    FROM journals_items 
                    LEFT JOIN journals ON journal_item_journal_id=journal_id
                    WHERE journal_item_trans_id=vTRANS_ID AND journal_item_type=2 AND journal_item_position=2;
                DECLARE CONTINUE HANDLER FOR NOT FOUND SET mFINISHED = 1;

                OPEN mCURSOR;
                -- WHILE mFINISHED < FOUND_ROWS() DO
                LOOP_1:LOOP
                    FETCH mCURSOR INTO mJOURNAL_ID, mTRANS_ID, mTRANS_SESSION, mTRANS_NUMBER, mTRANS_DATE, mTRANS_TOTAL, mGROUP_SESSION;
                    IF mFINISHED = 1 THEN LEAVE LOOP_1; END IF;
                    INSERT INTO temps (`temp_date`,`temp_source_id`,`temp_trans_id`,`temp_session`,`temp_number`,`temp_type`,`temp_credit`,`temp_transaction_type`,`temp_group_session`) 
                    VALUES (mTRANS_DATE,mTRANS_ID,mJOURNAL_ID,mTRANS_SESSION,mTRANS_NUMBER,mTRANS_TYPE,mTRANS_TOTAL,CONCAT('journals'),mGROUP_SESSION);
                    -- SET mFINISHED = mFINISHED + 1;
                END LOOP LOOP_1;
                -- END WHILE;
            END BLOCK_C;

            BLOCK_D:BEGIN
                UPDATE temps
                LEFT JOIN trans ON temp_trans_id=trans_id
                LEFT JOIN contacts ON trans_contact_id=contact_id 
                SET temp_note=CONCAT('[',contact_code,'] - ',contact_name)
                WHERE temp_transaction_type='trans';

                UPDATE temps
                LEFT JOIN journals_items ON temp_group_session=journal_item_group_session AND journal_item_position=1 AND journal_item_debit > 0
                LEFT JOIN accounts ON journal_item_account_id=account_id 
                SET temp_note=CONCAT('[',account_code,'] - ',account_name)
                WHERE temp_transaction_type='journals';
            END BLOCK_D;

            BLOCK_FINAL:BEGIN
                DECLARE mBALANCE DOUBLE(18,2) DEFAULT 0;
                SELECT 
                    CASE WHEN temp_credit > 0 THEN temp_credit
                    WHEN temp_debit > 0 THEN temp_debit
                    ELSE 0
                    END INTO mBALANCE
                FROM temps WHERE temp_id=1;

                SELECT *,
                    @balance := @balance - temps.temp_debit - temps.temp_credit AS temp_balance
                FROM (SELECT @balance := mBALANCE + mBALANCE) AS start_balance
                CROSS JOIN (
                    SELECT * FROM temps
                ) AS temps
                ORDER BY temps.temp_date ASC;             
            END BLOCK_FINAL;
        END IF;
    END BLOCK_0;

    -- SET @QUERY := CONCAT('SELECT * FROM temps ORDER BY temp_date ASC');
    -- PREPARE stmt FROM @QUERY;
    -- EXECUTE stmt;
    -- DEALLOCATE PREPARE stmt;

END$$
DELIMITER ;

DELIMITER $$
DROP PROCEDURE IF EXISTS `sp_contact_payable_receivable_update`$$
CREATE PROCEDURE `sp_contact_payable_receivable_update`(
  IN vTRANS_TYPE INT(50),
  IN vCONTACT_ID BIGINT(255)
)
BEGIN
    DECLARE mTOTAL INT(5) DEFAULT 0;
    DECLARE mTOTAL_PAID VARCHAR(255) DEFAULT 0;
    
    IF vTRANS_TYPE = 1 THEN 
        SELECT SUM(trans_total), SUM(trans_total_paid)
        INTO mTOTAL, mTOTAL_PAID
        FROM trans WHERE trans_type=vTRANS_TYPE AND trans_contact_id=vCONTACT_ID;

        UPDATE contacts 
        SET contact_payable_running=mTOTAL,
        contact_payable_paid=mTOTAL_PAID,
        contact_payable_balance=mTOTAL-mTOTAL_PAID
        WHERE contact_id=vCONTACT_ID;
    ELSEIF vTRANS_TYPE = 2 THEN
        SELECT SUM(trans_total), SUM(trans_total_paid)
        INTO mTOTAL, mTOTAL_PAID
        FROM trans WHERE trans_type=vTRANS_TYPE AND trans_contact_id=vCONTACT_ID;

        UPDATE contacts 
        SET contact_receivable_running=mTOTAL,
        contact_receivable_paid=mTOTAL_PAID,
        contact_receivable_balance=mTOTAL-mTOTAL_PAID
        WHERE contact_id=vCONTACT_ID;
    END IF;
END$$
DELIMITER ;

DELIMITER $$
DROP PROCEDURE IF EXISTS `sp_delete_branch`$$
CREATE PROCEDURE `sp_delete_branch`(
  IN vBRANCH_ID INT(255)
)
BEGIN
    IF vBRANCH_ID > 1 THEN 
        -- Core
        DELETE FROM users WHERE user_branch_id=vBRANCH_ID;
        DELETE FROM branchs WHERE branch_id=vBRANCH_ID;
        DELETE FROM locations WHERE location_branch_id=vBRANCH_ID;
        DELETE FROM accounts WHERE account_branch_id=vBRANCH_ID;
        DELETE FROM accounts_maps WHERE account_map_branch_id=vBRANCH_ID;

        -- Master
        DELETE FROM products WHERE product_branch_id=vBRANCH_ID;
        DELETE FROM contacts WHERE contact_branch_id=vBRANCH_ID;

        -- Trans
        DELETE FROM orders WHERE order_branch_id=vBRANCH_ID;
        DELETE FROM orders_items WHERE order_item_branch_id=vBRANCH_ID;

        DELETE FROM trans WHERE trans_branch_id=vBRANCH_ID;
        DELETE FROM trans_items WHERE trans_item_branch_id=vBRANCH_ID;  

        -- Journal
        DELETE FROM journals WHERE journal_branch_id=vBRANCH_ID;
        DELETE FROM journals_items WHERE journal_item_branch_id=vBRANCH_ID;   

        SELECT CONCAT(1) AS `status`, CONCAT('Branch ',vBRANCH_ID,' remove success') AS `message`;
    ELSE
        SELECT CONCAT(0) AS `status`, CONCAT('Branch not found') AS `message`;
    END IF;
END$$
DELIMITER ;

DELIMITER $$
DROP PROCEDURE IF EXISTS `sp_approval_list`$$
CREATE PROCEDURE `sp_approval_list`(
  IN vUSER_ID BIGINT(50)
)
BEGIN

    /* Prepare Temporary Table */
    DROP TEMPORARY TABLE IF EXISTS temps;
    CREATE TEMPORARY TABLE temps (
        `temp_id` BIGINT(50) NOT NULL AUTO_INCREMENT PRIMARY KEY,
        `approval_id` BIGINT(255),
        `approval_session` VARCHAR(255),        
        `approval_from_table` VARCHAR(255),
        `approval_from_id` VARCHAR(255),
        `approval_comment` VARCHAR(255),
        `approval_date_created` DATE,
        `approval_date_action` DATE,
        `approval_flag` BIGINT(255),  
        `approval_flag_name` VARCHAR(255),                
        `trans_id` VARCHAR(255),
        `trans_number` VARCHAR(255),
        `trans_total` VARCHAR(255),
        `contact_id` BIGINT(255),
        `contact_name` VARCHAR(255),
        `user_from_id` BIGINT(255),
        `user_from_username` VARCHAR(255),
        `user_to_id` BIGINT(255),
        `user_to_username` VARCHAR(255),
        `text_short` VARCHAR(255),
        `time_ago` VARCHAR(255),             
        INDEX `ID`(`temp_id`) USING BTREE
    ) ENGINE=MEMORY;

                                          
    -- `temp_status` INT(5) DEFAULT 0,
    -- `temp_message` VARCHAR(255),
    -- `temp_total_data` INT(255) DEFAULT 0,

    BLOCK_A:BEGIN
        DECLARE mCOLUMN_ONE VARCHAR(255) DEFAULT 0;
        DECLARE mCOLUMN_TWO VARCHAR(255) DEFAULT 0;
        DECLARE mCOLUMN_THREE VARCHAR(255) DEFAULT 0;
        DECLARE mCOLUMN_FOUR BIGINT(255) DEFAULT 0;              
        DECLARE mCOLUMN_FIVE BIGINT(255) DEFAULT 0;             
        DECLARE mCOLUMN_SIX BIGINT(255) DEFAULT 0;                      
        DECLARE mCOLUMN_SEVEN VARCHAR(255) DEFAULT 0; 

        DECLARE mCOLUMN_EIGHT BIGINT(255) DEFAULT 0;             
        DECLARE mCOLUMN_NINE BIGINT(255) DEFAULT 0;             
        DECLARE mCOLUMN_TEN BIGINT(255) DEFAULT 0;                      
        DECLARE mCOLUMN_ELEVEN VARCHAR(255) DEFAULT 0; 

        DECLARE mFINISHED INTEGER;
        DECLARE mACTION_CURSOR CURSOR FOR
            SELECT 
            approval_comment, approval_session, approval_from_table, approval_from_id, approval_user_id, 
            approval_user_from, fn_time_ago(approval_date_created)
            , approval_id, approval_date_created, approval_date_action, approval_flag
            FROM `approvals`
            WHERE approval_user_id=vUSER_ID AND approval_flag=0
            ORDER BY approval_date_created DESC;
        DECLARE CONTINUE HANDLER FOR NOT FOUND SET mFINISHED = 1;
        OPEN mACTION_CURSOR;

        LOOP_1: LOOP
            FETCH mACTION_CURSOR INTO mCOLUMN_ONE, mCOLUMN_TWO, mCOLUMN_THREE, mCOLUMN_FOUR, mCOLUMN_FIVE, 
            mCOLUMN_SIX, mCOLUMN_SEVEN, mCOLUMN_EIGHT, mCOLUMN_NINE, mCOLUMN_TEN, mCOLUMN_ELEVEN;
            IF mFINISHED = 1 THEN LEAVE LOOP_1; END IF;

            INSERT INTO temps(`approval_comment`,`approval_session`,`approval_from_table`,`approval_from_id`,`user_to_id`,
            `user_from_id`,`time_ago`,`approval_id`,`approval_date_created`,`approval_date_action`,`approval_flag`)
            VALUES (mCOLUMN_ONE, mCOLUMN_TWO, mCOLUMN_THREE, mCOLUMN_FOUR, mCOLUMN_FIVE, mCOLUMN_SIX, mCOLUMN_SEVEN
            ,mCOLUMN_EIGHT, mCOLUMN_NINE, mCOLUMN_TEN, mCOLUMN_ELEVEN);
        END LOOP LOOP_1;
    END BLOCK_A;

    /*
    BLOCK_B:BEGIN
        DECLARE mCOLUMN_ONE INT(5) DEFAULT 0;
        DECLARE mCOLUMN_TWO VARCHAR(255) DEFAULT 0;,
        DECLARE mCOLUMN_THREE VARCHAR(255) DEFAULT 0;
        DECLARE mFINISHED INTEGER;
        SET mFINISHED = 0;

        SELECT column_one, column_two, column_three 
        INTO mCOLUMN_ONE, mCOLUMN_TWO, mCOLUMN_THREE
        FROM table_name
        WHERE table_id > 0;

        WHILE mFINISHED < FOUND_ROWS() DO
            INSERT INTO temps(`COLUMN_ONE`,`COLUMN_TWO`,`COLUMN_THREE`)VALUES(mCOLUMN_ONE, mCOLUMN_TWO, mCOLUMN_THRE);
            SET mFINISHED = mFINISHED + 1;
        END WHILE;
    END BLOCK_B;
    */
    BLOCK_B:BEGIN -- Update Trans & Order
        UPDATE temps
        LEFT JOIN orders ON approval_from_id=order_id
        LEFT JOIN contacts ON orders.order_contact_id=contacts.contact_id
        SET trans_id=orders.order_id, trans_number=orders.order_number
        , temps.trans_total=orders.order_total, temps.contact_name=contacts.contact_name
        , temps.contact_id=orders.order_contact_id
        WHERE temps.approval_from_table='orders';

        UPDATE temps
        LEFT JOIN trans ON temps.approval_from_id=trans.trans_id
        LEFT JOIN contacts ON trans.trans_contact_id=contacts.contact_id
        SET temps.trans_id=trans.trans_id, temps.trans_number=trans.trans_number
        , temps.trans_total=trans.trans_total, temps.contact_name=contacts.contact_name 
        , temps.contact_id=trans.trans_contact_id
        WHERE temps.approval_from_table='trans';        

    END BLOCK_B;

    BLOCK_C:BEGIN -- Update User From & To
        UPDATE temps
        LEFT JOIN users ON user_to_id=user_id
        SET user_to_username=fn_capitalize(users.user_username);
        UPDATE temps
        LEFT JOIN users ON user_from_id=user_id
        SET user_from_username=fn_capitalize(users.user_username);        
    END BLOCK_C;

    BLOCK_D:BEGIN -- Update Flag
        UPDATE temps
        SET text_short= CONCAT(' mengajukan persetujuan '),
        approval_flag_name =
        CASE WHEN `approval_flag` = 0 THEN 'Pengajuan'
            WHEN `approval_flag` = 1 THEN 'Disetujui'
            WHEN `approval_flag` = 2 THEN 'Pending'
            WHEN `approval_flag` = 3 THEN 'Tolak'
            WHEN `approval_flag` = 4 THEN 'Dihapus'
            ELSE '-'
        END;

    END BLOCK_D;

    SET @QUERY := CONCAT('SELECT * FROM temps');
    PREPARE stmt FROM @QUERY;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;

END$$
DELIMITER ;

DELIMITER $$
DROP PROCEDURE IF EXISTS `sp_booking_list_test`$$
CREATE PROCEDURE `sp_booking_list_test`()
BEGIN
    SELECT
    b.branch_name,
    r.ref_name, rp.price_name, rp.price_sort,
    oi.order_item_start_date, oi.order_item_end_date, 
    oi.order_item_price, oi.order_item_qty, 
    oi.order_item_flag_checkin, 
    (
        CASE 
        WHEN oi.order_item_flag_checkin = 0 THEN 'Waiting' 
        WHEN oi.order_item_flag_checkin = 1 THEN 'Check-IN'
        WHEN oi.order_item_flag_checkin = 2 THEN 'Check-OUT'
        WHEN oi.order_item_flag_checkin = 4 THEN 'Batal'
        ELSE '' END
    ) AS order_item_flag_name,
    p.product_name,
    o.order_number, o.order_date, o.`order_contact_name`, o.order_contact_phone,
    oi.order_item_ref_id, order_item_ref_price_id, o.order_id, o.order_paid,
    (
        CASE 
        WHEN o.order_paid = 0 THEN 'Belum Lunas' 
        WHEN o.order_paid = 1 THEN 'Lunas'
        ELSE '' END
    ) AS order_paid_name
    FROM orders_items AS oi
    LEFT JOIN `references` AS r ON oi.order_item_ref_id=r.ref_id
    LEFT JOIN `references_prices` AS rp ON oi.order_item_ref_price_id=rp.price_id
    LEFT JOIN orders AS o ON oi.order_item_order_id=o.order_id
    LEFT JOIN branchs AS b ON oi.order_item_branch_id=b.branch_id
    LEFT JOIN products AS p ON oi.order_item_product_id=p.product_id
    ORDER BY oi.`order_item_start_date` DESC;
END $$
DELIMITER ;

DELIMITER $$

DROP PROCEDURE IF EXISTS `sp_room_check`$$
CREATE PROCEDURE `sp_room_check`(IN vROOM_ID VARCHAR(255), IN vSD DATETIME, IN vED DATETIME)
BEGIN
    DECLARE mCOUNT INTEGER DEFAULT 0;
    DECLARE mMESSAGE VARCHAR(255) DEFAULT 'Not Available';
    DECLARE mIS_CHECKIN INTEGER DEFAULT 0;
    DECLARE mPRODUCT_NAME VARCHAR(255);
    
    -- Check the room for global checkin
    SELECT COUNT(*) INTO mIS_CHECKIN 
    FROM orders_items 
    WHERE order_item_product_id = vROOM_ID 
    AND order_item_flag_checkin = 1;

    -- Retrieve product name
    SELECT product_name INTO mPRODUCT_NAME 
    FROM products 
    WHERE product_id = vROOM_ID;

    IF mIS_CHECKIN > 0 THEN
        -- Room is available, so check the date
        SELECT COUNT(*) INTO mCOUNT 
        FROM orders_items 
        WHERE order_item_type = 222 
        AND order_item_product_id = vROOM_ID
        AND order_item_flag_checkin IN (0, 1)
        AND (
            (order_item_start_date <= vSD AND order_item_end_date >= vSD)
            OR (vED <= order_item_start_date AND vED >= order_item_start_date)
            OR (order_item_start_date < vED AND order_item_end_date >= vED)
        );
        
        IF mCOUNT = 0 THEN
            SET mMESSAGE = 'Room is available';
        ELSE
            SET mMESSAGE = 'Room is not available for the given dates';
        END IF;
    ELSE
        -- Room not available
        SET mMESSAGE = 'Room is not available';
    END IF;

    -- Return the result
    SELECT mCOUNT AS room_is_available, mMESSAGE AS message, mPRODUCT_NAME AS room;
END $$

DELIMITER ;

DELIMITER $$
DROP PROCEDURE IF EXISTS `sp_room_check_only`$$
CREATE PROCEDURE `sp_room_check_only`(IN vROOM_ID INT(50))
BEGIN
    DECLARE mMESSAGE VARCHAR(255) DEFAULT 'Not Available';
    DECLARE mIS_CHECKIN INTEGER DEFAULT 0;
    DECLARE mPRODUCT_NAME VARCHAR(255);
    
    -- Retrieve product name
    SELECT product_name INTO mPRODUCT_NAME 
    FROM products 
    WHERE product_id = vROOM_ID;

    -- Check the room for global checkin
    SELECT COUNT(*) INTO mIS_CHECKIN 
    FROM orders_items 
    WHERE order_item_product_id = vROOM_ID 
    AND order_item_flag_checkin = 1;

    IF mIS_CHECKIN > 0 THEN
        SET mMESSAGE = 'Kamar masih checkin';
    ELSE
        -- Room not available
        SET mMESSAGE = 'Kamar tersedia';
    END IF;

    -- Return the result
    SELECT mIS_CHECKIN AS room_is_available, mMESSAGE AS message, mPRODUCT_NAME AS room;
END $$

DELIMITER ;