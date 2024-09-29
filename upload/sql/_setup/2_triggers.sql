DELIMITER $$
DROP TRIGGER IF EXISTS `tr_branch_before_insert` $$
CREATE TRIGGER `tr_branch_before_insert` BEFORE INSERT ON `branchs` FOR EACH ROW 
  BEGIN
    DECLARE mPROVINCE_ID VARCHAR(255);
    DECLARE mCITY_ID VARCHAR(255);
    DECLARE mDISTRICT_ID VARCHAR(255);

    DECLARE mPROVINCE_NAME VARCHAR(255);
    DECLARE mCITY_NAME VARCHAR(255);
    DECLARE mDISTRICT_NAME VARCHAR(255);
    DECLARE mDATE DATE;
    DECLARE mLOCATION VARCHAR(255);

    SET mPROVINCE_ID = NEW.branch_province_id;
    SET mCITY_ID = NEW.branch_city_id;
    SET mDISTRICT_ID = NEW.branch_district_id;
    SET mDATE = NOW();

    IF mPROVINCE_ID IS NOT NULL THEN 
      SELECT IFNULL(province_name,'') INTO mPROVINCE_NAME FROM provinces WHERE province_id=mPROVINCE_ID;
      SET NEW.branch_province = mPROVINCE_NAME;
    END IF;

    IF mCITY_ID IS NOT NULL THEN 
      SELECT IFNULL(city_name,'') INTO mCITY_NAME FROM cities WHERE city_id=mCITY_ID;
      SET NEW.branch_city = mCITY_NAME;
    END IF;
    
    IF mDISTRICT_ID IS NOT NULL THEN 
      SELECT IFNULL(district_name,'') INTO mDISTRICT_NAME FROM districts WHERE district_id=mDISTRICT_ID;
      SET NEW.branch_district = mDISTRICT_NAME;
    END IF;      

    IF NEW.branch_logo IS NULL THEN
      SET NEW.branch_logo = 'upload/branch/default_logo.png';
      SET NEW.branch_logo_sidebar = 'upload/branch/default_logo.png';
    END IF;

    -- CLoning Branch Alias Location
    INSERT INTO `locations` (`location_session`,`location_name`,`location_date_created`,`location_flag`)
    VALUES (NEW.branch_session,NEW.branch_name,NOW(),1);

    SELECT location_id INTO mLOCATION FROM locations WHERE location_session=NEW.branch_session;
    SET NEW.branch_location_id=mLOCATION;
    -- End Cloning Branch
  END$$
DELIMITER ;

DELIMITER $$
DROP TRIGGER IF EXISTS `tr_branch_after_insert` $$
CREATE TRIGGER `tr_branch_after_insert` AFTER INSERT ON `branchs` FOR EACH ROW 
  BEGIN
    DECLARE mLOCATION INT(255);
    -- Location 
    UPDATE locations SET location_branch_id = NEW.branch_id WHERE location_session=NEW.branch_session;
  END$$
DELIMITER ;

DELIMITER $$
DROP TRIGGER IF EXISTS `tr_branch_before_update` $$
CREATE TRIGGER `tr_branch_before_update` BEFORE UPDATE ON `branchs` FOR EACH ROW 
  BEGIN
    DECLARE mPROVINCE_ID VARCHAR(255);
    DECLARE mCITY_ID VARCHAR(255);
    DECLARE mDISTRICT_ID VARCHAR(255);

    DECLARE mPROVINCE_NAME VARCHAR(255);
    DECLARE mCITY_NAME VARCHAR(255);
    DECLARE mDISTRICT_NAME VARCHAR(255);
                  
    SET mPROVINCE_ID = NEW.branch_province_id;
    SET mCITY_ID = NEW.branch_city_id;
    SET mDISTRICT_ID = NEW.branch_district_id;
    
    IF mPROVINCE_ID IS NOT NULL THEN 
      SELECT IFNULL(province_name,'') INTO mPROVINCE_NAME FROM provinces WHERE province_id=mPROVINCE_ID;
      SET NEW.branch_province = mPROVINCE_NAME;
    END IF;

    IF mCITY_ID IS NOT NULL THEN 
      SELECT IFNULL(city_name,'') INTO mCITY_NAME FROM cities WHERE city_id=mCITY_ID;
      SET NEW.branch_city = mCITY_NAME;
    END IF;
    
    IF mDISTRICT_ID IS NOT NULL THEN 
      SELECT IFNULL(district_name,'') INTO mDISTRICT_NAME FROM districts WHERE district_id=mDISTRICT_ID;
      SET NEW.branch_district = mDISTRICT_NAME;
    END IF;       
  END$$
DELIMITER ;

DELIMITER $$
DROP TRIGGER IF EXISTS `tr_branch_after_update` $$
CREATE TRIGGER `tr_branch_after_update` AFTER UPDATE ON `branchs` FOR EACH ROW 
  BEGIN
    DECLARE mBRANCH_NAME VARCHAR(255);
    IF OLD.branch_name != NEW.branch_name THEN 
        UPDATE locations SET location_name = NEW.branch_name WHERE location_id=OLD.branch_location_id;
    END IF;
  END$$
DELIMITER ;

DELIMITER $$
DROP TRIGGER IF EXISTS `tr_contact_before_insert`$$
CREATE TRIGGER `tr_contact_before_insert` BEFORE INSERT ON `contacts` FOR EACH ROW 
  BEGIN
    DECLARE mLAST_NUMBER INTEGER DEFAULT 0;
    DECLARE mASCII INT(255);
    DECLARE mCONTACT_TYPE INT(5);
    DECLARE mCONTACT_NAME VARCHAR(255);
    DECLARE mFIRST_WORD_NAME VARCHAR(255);
    DECLARE mCODE VARCHAR(255);
    DECLARE mCONTACT_BRANCH BIGINT(50);
    DECLARE mBRANCH_CODE VARCHAR(255);
    DECLARE mPARENT_CONTACT_ID BIGINT(50);
    DECLARE mCITY_BIRTH_ID BIGINT(50);
    
    SET mPARENT_CONTACT_ID = NEW.contact_parent_id;
    SET mCITY_BIRTH_ID = NEW.contact_birth_city_id;
    SET mCONTACT_BRANCH = NEW.contact_branch_id;
    SET mCONTACT_TYPE = NEW.contact_type;
    SET mCONTACT_NAME = SUBSTR(NEW.contact_name,1,1);
    SET mASCII = ASCII(mCONTACT_NAME);
    
    /*
      1=Supplier, 2=Customer, 3=Karyawan, 4=Pasien, 5=Insuranse
    */
    -- IF mCONTACT_TYPE = 4 THEN /* Patient */
    
      /* Get Branch Code */
    --   SELECT branch_code INTO mBRANCH_CODE FROM branchs WHERE branch_id=mCONTACT_BRANCH;
    --   SELECT IFNULL(MAX(RIGHT(contact_code,5)),0) INTO mLAST_NUMBER 
    --   FROM contacts 
    --   WHERE contact_branch_id=mCONTACT_BRANCH 
    --   AND contact_type=mCONTACT_TYPE 
    --   AND contact_ascii=mASCII;   
      
    --   SET mFIRST_WORD_NAME = mCONTACT_NAME;
    --   IF mLAST_NUMBER = 0 THEN
    --     SET mCODE := CONCAT(mFIRST_WORD_NAME);
    --     SET mCODE := CONCAT(mCODE,mBRANCH_CODE);
    --     SET mCODE := CONCAT(mCODE,"00001");
    --   ELSE 
    --     SET mLAST_NUMBER = mLAST_NUMBER+1;
    --     SELECT LPAD(mLAST_NUMBER, 5, 0) INTO @mLAST_NUMBER;
    --     SET mCODE := CONCAT(mFIRST_WORD_NAME,mBRANCH_CODE);
    --     SET mCODE := CONCAT(mCODE,@mLAST_NUMBER);
    --   END IF;
      
      IF mPARENT_CONTACT_ID IS NOT NULL THEN 
        SELECT contact_name INTO @mCONTACT_NAME FROM contacts WHERE contact_id=mPARENT_CONTACT_ID;
        SET NEW.contact_parent_name = @mCONTACT_NAME;
      END IF;
      
      IF mCITY_BIRTH_ID IS NOT NULL THEN
        SELECT city_name INTO @mCITY_NAME FROM cities WHERE city_id=mCITY_BIRTH_ID;
        SET NEW.contact_birth_city_name = @mCITY_NAME;    
      END IF;
      
      -- SET NEW.contact_code = mCODE;
      SET NEW.contact_ascii = mASCII;
    -- END IF;
    
    /* Activity
    SET @mBRANCH_ID := NEW.contact_branch_id;
    SET @mUSER_ID := NEW.contact_user_id;
    SET @mTABLE := 'contacts';
    SET @mTABLE_ID := NEW.contact_id;
    SET @mACTION := 2;

    SET @mTEXT_1 = NULL;
        IF mCONTACT_TYPE = 1 THEN SET @mTEXT_1 := 'Supplier';
        ELSEIF mCONTACT_TYPE = 2 THEN SET @mTEXT_1 := 'Customer';
        ELSEIF mCONTACT_TYPE = 3 THEN SET @mTEXT_1 := 'Karyawan';
        ELSEIF mCONTACT_TYPE = 4 THEN SET @mTEXT_1 := 'Pasien';
        ELSEIF mCONTACT_TYPE = 5 THEN SET @mTEXT_1 := 'Insurance';
        ELSE SET @mTEXT_1 := 'Unknown';
        END IF;        
    SET @mTEXT_2 := NEW.contact_name;
    SET @mTEXT_3 := NULL;

    INSERT `activities` (
        `activity_branch_id`,`activity_user_id`,`activity_action`,
        `activity_table`,`activity_table_id`,
        `activity_text_1`,`activity_text_2`,`activity_text_3`,
        `activity_date_created`,`activity_flag`,`activity_type`
    ) VALUES (@mBRANCH_ID,@mUSER_ID,@mACTION,
        @mTABLE,@mTABLE_ID,
        @mTEXT_1,@mTEXT_2,@mTEXT_3,
        NOW(),1,1
    );
    */
    /*Get Type Name*/
    SET @mCONTACT_TYPE = NEW.contact_type;
    SET @mCONTACT_TYPE_NAME = '-';
    SELECT type_name INTO @mCONTACT_TYPE_NAME FROM `types` WHERE type_type=@mCONTACT_TYPE AND type_for=5;
    SET NEW.contact_type_name = @mCONTACT_TYPE_NAME;

  END$$
DELIMITER ;

DELIMITER $$
DROP TRIGGER IF EXISTS `tr_contact_before_update`$$
CREATE TRIGGER `tr_contact_before_update` BEFORE UPDATE ON `contacts` FOR EACH ROW 
  BEGIN
    DECLARE mCONTACT_TYPE INT(5); 
    DECLARE mPARENT_CONTACT_ID BIGINT(50);
    DECLARE mCITY_BIRTH_ID BIGINT(50);
    
    SET mCONTACT_TYPE = NEW.contact_type;
    SET mPARENT_CONTACT_ID = NEW.contact_parent_id;
    SET mCITY_BIRTH_ID = NEW.contact_birth_city_id;
    
    IF mPARENT_CONTACT_ID IS NOT NULL THEN 
        SELECT contact_name INTO @mCONTACT_NAME FROM contacts WHERE contact_id=mPARENT_CONTACT_ID;
        SET NEW.contact_parent_name = @mCONTACT_NAME;
    END IF;
    
    IF mCITY_BIRTH_ID IS NOT NULL THEN
        SELECT city_name INTO @mCITY_NAME FROM cities WHERE city_id=mCITY_BIRTH_ID;
        SET NEW.contact_birth_city_name = @mCITY_NAME;    
    END IF;

    /*Get Type Name*/
    SET @mCONTACT_TYPE = NEW.contact_type;
    SET @mCONTACT_TYPE_NAME = '-';
    SELECT type_name INTO @mCONTACT_TYPE_NAME FROM `types` WHERE type_type=@mCONTACT_TYPE AND type_for=5;
    SET NEW.contact_type_name = @mCONTACT_TYPE_NAME;
    
  END$$
DELIMITER ;

DELIMITER $$
DROP TRIGGER IF EXISTS `tr_contact_after_insert`$$ 
CREATE TRIGGER `tr_contact_after_insert` AFTER INSERT ON `contacts`
FOR EACH ROW 
BEGIN
    IF NEW.contact_category_id IS NOT NULL THEN
        UPDATE categories SET category_count_data=(
            SELECT COUNT(*) FROM contacts WHERE contact_category_id=NEW.contact_category_id
        ) WHERE category_id=NEW.contact_category_id; 
    END IF;
END $$
DELIMITER ;

DELIMITER $$
DROP TRIGGER IF EXISTS `tr_contact_after_update`$$ 
CREATE TRIGGER `tr_contact_after_update` AFTER UPDATE ON `contacts`
FOR EACH ROW 
BEGIN
    IF NEW.contact_category_id IS NOT NULL THEN
        IF NEW.contact_category_id != OLD.contact_category_id THEN
            UPDATE categories SET category_count_data=(
                SELECT COUNT(*) FROM contacts WHERE contact_category_id=NEW.contact_category_id
            ) WHERE category_id=NEW.contact_category_id; 

            UPDATE categories SET category_count_data=(
                SELECT COUNT(*) FROM contacts WHERE contact_category_id=OLD.contact_category_id
            ) WHERE category_id=OLD.contact_category_id;
        END IF;
    END IF;

    IF NEW.contact_category_id IS NULL THEN
        IF OLD.contact_category_id IS NOT NULL THEN 
            UPDATE categories SET category_count_data=(
                SELECT COUNT(*) FROM contacts WHERE contact_category_id=OLD.contact_category_id
            ) WHERE category_id=OLD.contact_category_id;            
        END IF;  
    END IF;
END $$
DELIMITER ;

DELIMITER $$
DROP TRIGGER IF EXISTS `tr_contact_after_delete`$$ 
CREATE TRIGGER `tr_contact_after_delete` AFTER DELETE ON `contacts`
FOR EACH ROW 
BEGIN
    IF OLD.contact_category_id IS NOT NULL THEN
        UPDATE categories SET category_count_data=(
            SELECT COUNT(*) FROM contacts WHERE contact_category_id=OLD.contact_category_id
        ) WHERE category_id=OLD.contact_category_id;
    END IF;
END $$
DELIMITER ;

DELIMITER $$
DROP TRIGGER IF EXISTS `tr_journal_items_after_delete`$$
CREATE TRIGGER `tr_journal_items_after_delete` AFTER DELETE ON `journals_items` FOR EACH ROW 
    BEGIN
        DECLARE mJOURNAL_ITEM_TYPE INT(5);
        DECLARE mJOURNAL_ITEM_POSITION INT(5);
        DECLARE mJOURNAL_ITEM_TRANS_ID BIGINT(50);
        DECLARE mTRANS_TOTAL DOUBLE(18,2);
        DECLARE mJOURNAL_TOTAL DOUBLE(18,2);
        DECLARE mJOURNAL_VOUCHER DOUBLE(18,2) DEFAULT 0;
        DECLARE mPAID_INTO_TRANS DOUBLE(18,2);

        SET mJOURNAL_ITEM_TYPE = OLD.journal_item_type;
        SET mJOURNAL_ITEM_POSITION = OLD.journal_item_position;
        SET mTRANS_TOTAL = 0;
        SET mJOURNAL_TOTAL = 0;
        SET mPAID_INTO_TRANS = 0;
        /*
        1=BayarHutang,
        2=BayarPiutang,
        3=KasMasuk,
        4=KasKeluar,
        5=Transfer Uang / MutasiKas,
        6=UangMukaBeli,
        7=UangMukaJual,
        8=JurnalUmum,
        9=KirimUang,
        10=Pembelian,
        11=Penjualan,
        12=ReturPembelian,
        13=ReturPenjualan,
        14=OpnamePlus,
        15=OpnameMinus,
        16=AssetBeli,
        17=AssetSusut,
        18=AssetJual,
        */
        IF mJOURNAL_ITEM_TYPE = 1 THEN /* Bayar Hutang */
            IF mJOURNAL_ITEM_POSITION = 2 THEN 
                SET mJOURNAL_ITEM_TRANS_ID = OLD.journal_item_trans_id;
                SELECT IFNULL(trans_total,0) INTO mTRANS_TOTAL FROM trans WHERE trans_id=mJOURNAL_ITEM_TRANS_ID;
                SELECT IFNULL(SUM(journal_item_debit),0) INTO mJOURNAL_TOTAL 
                FROM journals_items WHERE journal_item_trans_id=mJOURNAL_ITEM_TRANS_ID AND journal_item_type=1;
                UPDATE trans SET trans_total_paid=mJOURNAL_TOTAL WHERE trans_id=mJOURNAL_ITEM_TRANS_ID;
                IF mJOURNAL_TOTAL >= mTRANS_TOTAL THEN
                    UPDATE trans SET trans_paid=1 WHERE trans_id=mJOURNAL_ITEM_TRANS_ID;
                ELSE 
                    UPDATE trans SET trans_paid=0 WHERE trans_id=mJOURNAL_ITEM_TRANS_ID;
                END IF;     
            END IF;
        ELSEIF mJOURNAL_ITEM_TYPE = 2 THEN /* Bayar Piutang */
            IF mJOURNAL_ITEM_POSITION = 2 THEN 
                SET mJOURNAL_ITEM_TRANS_ID = OLD.journal_item_trans_id;
                SELECT IFNULL(trans_total,0) INTO mTRANS_TOTAL FROM trans WHERE trans_id=mJOURNAL_ITEM_TRANS_ID;
                SELECT IFNULL(SUM(journal_item_credit),0) INTO mJOURNAL_TOTAL FROM journals_items 
                WHERE journal_item_trans_id=mJOURNAL_ITEM_TRANS_ID AND journal_item_type=2;
                
                -- Detect Voucher and Get Account
                -- SELECT IFNULL(SUM(journal_item_debit),0) INTO mJOURNAL_VOUCHER 
                -- FROM journals_items LEFT JOIN accounts ON journal_item_account_id=account_id AND account_group=4
                -- WHERE journal_item_trans_id=mJOURNAL_ITEM_TRANS_ID 
                -- AND journal_item_type=2 
                -- AND journal_item_position=1;

                -- IF mJOURNAL_VOUCHER > 0 THEN
                --   SET mJOURNAL_TOTAL = mJOURNAL_TOTAL - mJOURNAL_VOUCHER;
                -- END IF;
                                
                UPDATE trans SET trans_total_paid=mJOURNAL_TOTAL WHERE trans_id=mJOURNAL_ITEM_TRANS_ID;
                IF mJOURNAL_TOTAL >= mTRANS_TOTAL THEN
                    UPDATE trans SET trans_paid=1 WHERE trans_id=mJOURNAL_ITEM_TRANS_ID;
                ELSE 
                    UPDATE trans SET trans_paid=0, trans_paid_type=0, trans_voucher=0, trans_discount=0, trans_voucher_id=NULL,
                    trans_change=0, trans_received=0, trans_card_bank_name=NULL, trans_card_expired=NULL  
                    WHERE trans_id=mJOURNAL_ITEM_TRANS_ID;
                END IF;     
            END IF;        
        ELSEIF mJOURNAL_ITEM_TYPE = 19 THEN /* Biaya di Produksi */
            -- IF mJOURNAL_ITEM_POSITION = 2 THEN 
                SET mJOURNAL_ITEM_TRANS_ID = OLD.journal_item_trans_id;
                SELECT IFNULL(SUM(trans_item_total),0) INTO mTRANS_TOTAL FROM trans_items 
                WHERE trans_item_trans_id=mJOURNAL_ITEM_TRANS_ID AND trans_item_position=2;
                SELECT IFNULL(SUM(journal_item_debit),0) INTO mJOURNAL_TOTAL FROM journals_items 
                WHERE journal_item_trans_id=mJOURNAL_ITEM_TRANS_ID 
                AND journal_item_type=19 AND journal_item_position=3;
                
                SET @mTOTAL_PRODUCTION = mJOURNAL_TOTAL + mTRANS_TOTAL;
                UPDATE trans SET trans_total_dpp=@mTOTAL_PRODUCTION, trans_total=@mTOTAL_PRODUCTION WHERE trans_id=mJOURNAL_ITEM_TRANS_ID;
                -- IF mJOURNAL_TOTAL >= mTRANS_TOTAL THEN
                    -- UPDATE trans SET trans_paid=1 WHERE trans_id=mJOURNAL_ITEM_TRANS_ID;
                -- ELSE 
                    -- UPDATE trans SET trans_paid=0 WHERE trans_id=mJOURNAL_ITEM_TRANS_ID;
                -- END IF;     
            -- END IF;        
        END IF;    
    END$$
DELIMITER ;

DELIMITER $$
DROP TRIGGER IF EXISTS `tr_journal_items_after_insert`$$
CREATE TRIGGER `tr_journal_items_after_insert` AFTER INSERT ON `journals_items` FOR EACH ROW 
    BEGIN
        DECLARE mJOURNAL_ITEM_TYPE INT(5);
        DECLARE mJOURNAL_ITEM_POSITION INT(5);
        DECLARE mJOURNAL_ITEM_TRANS_ID BIGINT(50);
        DECLARE mTRANS_TOTAL DOUBLE(18,2);
        DECLARE mJOURNAL_TOTAL DOUBLE(18,2);
        DECLARE mJOURNAL_VOUCHER DOUBLE(18,2) DEFAULT 0;
        DECLARE mPAID_INTO_TRANS DOUBLE(18,2);

        SET mJOURNAL_ITEM_TYPE = NEW.journal_item_type;
        SET mJOURNAL_ITEM_POSITION = NEW.journal_item_position;
        SET mTRANS_TOTAL = 0;
        SET mJOURNAL_TOTAL = 0;
        SET mPAID_INTO_TRANS = 0;
        /*
        1=BayarHutang,
        2=BayarPiutang,
        3=KasMasuk,
        4=KasKeluar,
        5=Transfer Uang / MutasiKas,
        6=UangMukaBeli,
        7=UangMukaJual,
        8=JurnalUmum,
        9=KirimUang,
        10=Pembelian,
        11=Penjualan,
        12=ReturPembelian,
        13=ReturPenjualan,
        14=OpnamePlus,
        15=OpnameMinus,
        16=AssetBeli,
        17=AssetSusut,
        18=AssetJual,
        */
        IF mJOURNAL_ITEM_TYPE = 1 THEN /* Bayar Hutang */
            IF mJOURNAL_ITEM_POSITION = 2 THEN 
                SET mJOURNAL_ITEM_TRANS_ID = NEW.journal_item_trans_id;
                SELECT IFNULL(trans_total,0) INTO mTRANS_TOTAL FROM trans WHERE trans_id=mJOURNAL_ITEM_TRANS_ID;
                SELECT IFNULL(SUM(journal_item_debit),0) INTO mJOURNAL_TOTAL FROM journals_items 
                WHERE journal_item_trans_id=mJOURNAL_ITEM_TRANS_ID AND journal_item_type=1;
                
                UPDATE trans SET trans_total_paid=mJOURNAL_TOTAL WHERE trans_id=mJOURNAL_ITEM_TRANS_ID;
                IF mJOURNAL_TOTAL >= mTRANS_TOTAL THEN
                    UPDATE trans SET trans_paid=1 WHERE trans_id=mJOURNAL_ITEM_TRANS_ID;
                ELSE 
                    UPDATE trans SET trans_paid=0 WHERE trans_id=mJOURNAL_ITEM_TRANS_ID;
                END IF;     
            END IF;
        ELSEIF mJOURNAL_ITEM_TYPE = 2 THEN /* Bayar Piutang */
            IF mJOURNAL_ITEM_POSITION = 2 THEN 
                SET mJOURNAL_ITEM_TRANS_ID = NEW.journal_item_trans_id;
                SELECT IFNULL(trans_total,0) INTO mTRANS_TOTAL FROM trans WHERE trans_id=mJOURNAL_ITEM_TRANS_ID;
                
                SELECT IFNULL(SUM(journal_item_credit),0) INTO mJOURNAL_TOTAL 
                FROM journals_items 
                WHERE journal_item_trans_id=mJOURNAL_ITEM_TRANS_ID AND journal_item_type=2;
                
                -- Detect Voucher and Get Account
                SELECT IFNULL(SUM(journal_item_debit),0) INTO mJOURNAL_VOUCHER 
                FROM journals_items LEFT JOIN accounts ON journal_item_account_id=account_id AND account_group=4
                WHERE journal_item_trans_id=mJOURNAL_ITEM_TRANS_ID 
                AND journal_item_type=2 
                AND journal_item_position=1;

                IF mJOURNAL_VOUCHER > 0 THEN
                  SET mJOURNAL_TOTAL = mJOURNAL_TOTAL - mJOURNAL_VOUCHER;
                END IF;

                UPDATE trans SET trans_total_paid=mJOURNAL_TOTAL WHERE trans_id=mJOURNAL_ITEM_TRANS_ID;
                IF mJOURNAL_TOTAL >= mTRANS_TOTAL THEN
                    UPDATE trans SET trans_paid=1 WHERE trans_id=mJOURNAL_ITEM_TRANS_ID;
                ELSE 
                    UPDATE trans SET trans_paid=0 WHERE trans_id=mJOURNAL_ITEM_TRANS_ID;
                END IF;
            END IF;    
        ELSEIF mJOURNAL_ITEM_TYPE = 19 THEN /* Biaya di Produksi */
            -- IF mJOURNAL_ITEM_POSITION = 2 THEN 
                SET mJOURNAL_ITEM_TRANS_ID = NEW.journal_item_trans_id;
                            SELECT IFNULL(SUM(trans_item_total),0) INTO mTRANS_TOTAL FROM trans_items 
                            WHERE trans_item_trans_id=mJOURNAL_ITEM_TRANS_ID AND trans_item_position=2;
                SELECT IFNULL(SUM(journal_item_debit),0) INTO mJOURNAL_TOTAL FROM journals_items 
                WHERE journal_item_trans_id=mJOURNAL_ITEM_TRANS_ID 
                AND journal_item_type=19 AND journal_item_position=3;
                
                SET @mTOTAL_PRODUCTION = mJOURNAL_TOTAL + mTRANS_TOTAL;
                UPDATE trans SET trans_total_dpp=@mTOTAL_PRODUCTION, trans_total=@mTOTAL_PRODUCTION WHERE trans_id=mJOURNAL_ITEM_TRANS_ID;
                -- IF mJOURNAL_TOTAL >= mTRANS_TOTAL THEN
                    -- UPDATE trans SET trans_paid=1 WHERE trans_id=mJOURNAL_ITEM_TRANS_ID;
                -- ELSE 
                    -- UPDATE trans SET trans_paid=0 WHERE trans_id=mJOURNAL_ITEM_TRANS_ID;
                -- END IF;     
            -- END IF;        
        END IF;    
    END$$
DELIMITER ;

DELIMITER $$
DROP TRIGGER IF EXISTS `tr_journal_items_after_update`$$
CREATE TRIGGER `tr_journal_items_after_update` AFTER UPDATE ON `journals_items` FOR EACH ROW 
    BEGIN
        DECLARE mJOURNAL_ITEM_TYPE INT(5);
        DECLARE mJOURNAL_ITEM_POSITION INT(5);
        DECLARE mJOURNAL_ITEM_TRANS_ID BIGINT(50);
        DECLARE mTRANS_TOTAL DOUBLE(18,2);
        DECLARE mJOURNAL_TOTAL DOUBLE(18,2);
        DECLARE mJOURNAL_VOUCHER DOUBLE(18,2) DEFAULT 0;
        DECLARE mPAID_INTO_TRANS DOUBLE(18,2);

        SET mJOURNAL_ITEM_TYPE = NEW.journal_item_type;
        SET mJOURNAL_ITEM_POSITION = NEW.journal_item_position;
        SET mTRANS_TOTAL = 0;
        SET mJOURNAL_TOTAL = 0;
        SET mPAID_INTO_TRANS = 0;
        /*
        1=BayarHutang,
        2=BayarPiutang,
        3=KasMasuk,
        4=KasKeluar,
        5=Transfer Uang / MutasiKas,
        6=UangMukaBeli,
        7=UangMukaJual,
        8=JurnalUmum,
        9=KirimUang,
        10=Pembelian,
        11=Penjualan,
        12=ReturPembelian,
        13=ReturPenjualan,
        14=OpnamePlus,
        15=OpnameMinus,
        16=AssetBeli,
        17=AssetSusut,
        18=AssetJual,
        */
        IF mJOURNAL_ITEM_TYPE = 1 THEN /* Bayar Hutang */
          IF mJOURNAL_ITEM_POSITION = 2 THEN 
          
            SET mJOURNAL_ITEM_TRANS_ID = NEW.journal_item_trans_id;
            SELECT IFNULL(trans_total,0) INTO mTRANS_TOTAL FROM trans WHERE trans_id=mJOURNAL_ITEM_TRANS_ID;
            SELECT IFNULL(SUM(journal_item_debit),0) INTO mJOURNAL_TOTAL FROM journals_items WHERE journal_item_trans_id=mJOURNAL_ITEM_TRANS_ID AND journal_item_type=1;
            
            UPDATE trans SET trans_total_paid=mJOURNAL_TOTAL WHERE trans_id=mJOURNAL_ITEM_TRANS_ID;
            IF mJOURNAL_TOTAL >= mTRANS_TOTAL THEN
              UPDATE trans SET trans_paid=1 WHERE trans_id=mJOURNAL_ITEM_TRANS_ID;
                ELSE 
                    UPDATE trans SET trans_paid=0 WHERE trans_id=mJOURNAL_ITEM_TRANS_ID;            
            END IF;
          END IF;
        ELSEIF mJOURNAL_ITEM_TYPE = 2 THEN /* Bayar Piutang */
            IF mJOURNAL_ITEM_POSITION = 2 THEN 
                SET mJOURNAL_ITEM_TRANS_ID = NEW.journal_item_trans_id;
                SELECT IFNULL(trans_total,0) INTO mTRANS_TOTAL FROM trans WHERE trans_id=mJOURNAL_ITEM_TRANS_ID;
                SELECT IFNULL(SUM(journal_item_credit),0) INTO mJOURNAL_TOTAL FROM journals_items 
                WHERE journal_item_trans_id=mJOURNAL_ITEM_TRANS_ID AND journal_item_type=2;
                
                -- Detect Voucher and Get Account
                -- SELECT IFNULL(SUM(journal_item_debit),0) INTO mJOURNAL_VOUCHER 
                -- FROM journals_items LEFT JOIN accounts ON journal_item_account_id=account_id AND account_group=4
                -- WHERE journal_item_trans_id=mJOURNAL_ITEM_TRANS_ID 
                -- AND journal_item_type=2 
                -- AND journal_item_position=1;

                -- IF mJOURNAL_VOUCHER > 0 THEN
                --   SET mJOURNAL_TOTAL = mJOURNAL_TOTAL - mJOURNAL_VOUCHER;
                -- END IF;

                UPDATE trans SET trans_total_paid=mJOURNAL_TOTAL WHERE trans_id=mJOURNAL_ITEM_TRANS_ID;
                IF mJOURNAL_TOTAL >= mTRANS_TOTAL THEN
                    UPDATE trans SET trans_paid=1 WHERE trans_id=mJOURNAL_ITEM_TRANS_ID;
                ELSE 
                    UPDATE trans SET trans_paid=0 WHERE trans_id=mJOURNAL_ITEM_TRANS_ID;                
                END IF;
            END IF;
        END IF;
    END$$
DELIMITER ;

DELIMITER $$
DROP TRIGGER IF EXISTS `tr_journal_items_before_insert`$$
CREATE TRIGGER `tr_journal_items_before_insert` BEFORE INSERT ON `journals_items` FOR EACH ROW 
    BEGIN
    /*
    1=BayarHutang,
    2=BayarPiutang,
    3=KasMasuk,
    4=KasKeluar,
    5=Transfer Uang / MutasiKas,
    6=UangMukaBeli,
    7=UangMukaJual,
    8=JurnalUmum,
    9=KirimUang,
    10=Pembelian,
    11=Penjualan,
    12=ReturPembelian,
    13=ReturPenjualan,
    14=OpnamePlus,
    15=OpnameMinus,
    16=AssetBeli,
    17=AssetSusut,
    18=AssetJual,
    */
     
    DECLARE mBRANCH BIGINT(50);
    DECLARE mJOURNAL_ITEM_TYPE INT(5);
    DECLARE mJOURNAL_ITEM_JOURNAL_ID BIGINT(50);
    DECLARE mJOURNAL_ITEM_TRANS_ID BIGINT(50);
    DECLARE mJOURNAL_ITEM_ORDER_ID BIGINT(50);    
    DECLARE mTEXT VARCHAR(255);
    DECLARE mSESSION VARCHAR(255);
    
    SET mBRANCH = NEW.journal_item_branch_id;
    SET mJOURNAL_ITEM_TYPE = NEW.journal_item_type;
    SET mJOURNAL_ITEM_JOURNAL_ID = NEW.journal_item_journal_id;
    SET mJOURNAL_ITEM_TRANS_ID = NEW.journal_item_trans_id;
    SET mJOURNAL_ITEM_ORDER_ID = NEW.journal_item_order_id;    
    
    IF (mJOURNAL_ITEM_TYPE = 6) OR (mJOURNAL_ITEM_TYPE = 7) THEN
      SET mJOURNAL_ITEM_ORDER_ID = NEW.journal_item_journal_id;
      SET mTEXT=CONCAT(mBRANCH,mJOURNAL_ITEM_ORDER_ID,mJOURNAL_ITEM_TYPE); 
      SET mTEXT=MD5(mTEXT);
    ELSEIF (mJOURNAL_ITEM_TYPE = 10) OR (mJOURNAL_ITEM_TYPE = 11) THEN
      SET mJOURNAL_ITEM_TRANS_ID = NEW.journal_item_trans_id;
      SET mTEXT=CONCAT(mBRANCH,mJOURNAL_ITEM_TRANS_ID,mJOURNAL_ITEM_TYPE); 
      SET mTEXT=MD5(mTEXT);
    ELSEIF (mJOURNAL_ITEM_TYPE = 22) OR (mJOURNAL_ITEM_TYPE = 23) THEN
      SET mJOURNAL_ITEM_TRANS_ID = NEW.journal_item_trans_id;
      SET mTEXT=CONCAT(mBRANCH,mJOURNAL_ITEM_TRANS_ID,mJOURNAL_ITEM_TYPE);
      SET mTEXT=MD5(mTEXT);
    ELSEIF mJOURNAL_ITEM_TYPE = 14 THEN
      SET mJOURNAL_ITEM_TRANS_ID = NEW.journal_item_trans_id;
      SET mTEXT=CONCAT(mBRANCH,mJOURNAL_ITEM_TRANS_ID,mJOURNAL_ITEM_TYPE); 
      SET mTEXT=MD5(mTEXT);       
    ELSEIF mJOURNAL_ITEM_TYPE = 19 THEN
      SET mJOURNAL_ITEM_TRANS_ID = NEW.journal_item_trans_id;
      SET mTEXT=CONCAT(mBRANCH,mJOURNAL_ITEM_TRANS_ID,mJOURNAL_ITEM_TYPE); 
      SET mTEXT=MD5(mTEXT); 
    ELSEIF mJOURNAL_ITEM_TYPE = 20 THEN
      SET mJOURNAL_ITEM_TRANS_ID = NEW.journal_item_trans_id;
      SET mTEXT=CONCAT(mBRANCH,mJOURNAL_ITEM_TRANS_ID,mJOURNAL_ITEM_TYPE); 
      SET mTEXT=MD5(mTEXT);
    ELSEIF mJOURNAL_ITEM_TYPE = 21 THEN
      SET mJOURNAL_ITEM_TRANS_ID = NEW.journal_item_trans_id;
      SET mTEXT=CONCAT(mBRANCH,mJOURNAL_ITEM_TRANS_ID,mJOURNAL_ITEM_TYPE); 
      SET mTEXT=MD5(mTEXT);
    ELSE
      IF mJOURNAL_ITEM_JOURNAL_ID IS NOT NULL THEN
        SET mJOURNAL_ITEM_JOURNAL_ID = NEW.journal_item_journal_id;
        SET mTEXT=CONCAT(mBRANCH,mJOURNAL_ITEM_JOURNAL_ID,mJOURNAL_ITEM_TYPE);
        SET mTEXT=MD5(mTEXT);
        /* Else on Trigger Before Update */
      ELSE
        SET mTEXT=CONCAT('UNGROUP');
      END IF;
    END IF;
    
    SET mSESSION = UPPER(mTEXT);
    SET NEW.journal_item_group_session = LEFT(mSESSION,10);     
    
    SET @mJOURNAL_TYPE = NEW.journal_item_type;
    SET @mJOURNAL_TYPE_NAME = '-';
    SELECT type_name INTO @mJOURNAL_TYPE_NAME FROM `types` WHERE type_type=@mJOURNAL_TYPE AND type_for=3;
    SET NEW.journal_item_type_name = @mJOURNAL_TYPE_NAME;
    SET NEW.journal_item_session = fn_create_session();
    END$$
DELIMITER ;

DELIMITER $$
DROP TRIGGER IF EXISTS `tr_journal_items_before_update`$$
CREATE TRIGGER `tr_journal_items_before_update` BEFORE UPDATE ON `journals_items` FOR EACH ROW 
  BEGIN
        /*
            1=BayarHutang,
            2=BayarPiutang,
            3=KasMasuk,
            4=KasKeluar,
            5=Transfer Uang / MutasiKas,
            6=UangMukaBeli,
            7=UangMukaJual,
            8=JurnalUmum,
            9=KirimUang,
            10=Pembelian,
            11=Penjualan,
            12=ReturPembelian,
            13=ReturPenjualan,
            14=OpnamePlus,
            15=OpnameMinus,
            16=AssetBeli,
            17=AssetSusut,
            18=AssetJual,
        */   
        DECLARE mBRANCH BIGINT(50);
        DECLARE mJOURNAL_ITEM_TYPE INT(5);
        DECLARE mJOURNAL_GROUP_SESSION VARCHAR(255);
        DECLARE mJOURNAL_ITEM_JOURNAL_ID BIGINT(50);
        DECLARE mJOURNAL_ITEM_TRANS_ID BIGINT(50);
        DECLARE mJOURNAL_ITEM_ORDER_ID BIGINT(50);          
        DECLARE mTEXT VARCHAR(255);
        DECLARE mSESSION VARCHAR(255);

        SET mBRANCH = OLD.journal_item_branch_id;
        SET mJOURNAL_ITEM_TYPE = OLD.journal_item_type;
        SET mJOURNAL_GROUP_SESSION = NEW.journal_item_group_session;
        SET mJOURNAL_ITEM_JOURNAL_ID = NEW.journal_item_journal_id;
            
        -- IF LENGTH(mJOURNAL_GROUP_SESSION) = 0 THEN     
        SET mJOURNAL_ITEM_ORDER_ID = NEW.journal_item_order_id;    
    
        IF (mJOURNAL_ITEM_TYPE = 6) OR (mJOURNAL_ITEM_TYPE = 7) THEN
          SET mJOURNAL_ITEM_ORDER_ID = NEW.journal_item_journal_id;
          SET mTEXT=CONCAT(mBRANCH,mJOURNAL_ITEM_ORDER_ID,mJOURNAL_ITEM_TYPE); 
          SET mTEXT=MD5(mTEXT);
        ELSEIF (mJOURNAL_ITEM_TYPE = 10) OR (mJOURNAL_ITEM_TYPE = 11) THEN
          SET mJOURNAL_ITEM_TRANS_ID = OLD.journal_item_trans_id;
          SET mTEXT=CONCAT(mBRANCH,mJOURNAL_ITEM_TRANS_ID,mJOURNAL_ITEM_TYPE); 
          SET mTEXT=MD5(mTEXT);
        ELSEIF (mJOURNAL_ITEM_TYPE = 12) OR (mJOURNAL_ITEM_TYPE = 13) THEN
          SET mJOURNAL_ITEM_TRANS_ID = NEW.journal_item_trans_id;
          SET mTEXT=CONCAT(mBRANCH,mJOURNAL_ITEM_TRANS_ID,mJOURNAL_ITEM_TYPE);
          SET mTEXT=MD5(mTEXT);         
        ELSEIF mJOURNAL_ITEM_TYPE = 14 THEN
          SET mJOURNAL_ITEM_TRANS_ID = NEW.journal_item_trans_id;
          SET mTEXT=CONCAT(mBRANCH,mJOURNAL_ITEM_TRANS_ID,mJOURNAL_ITEM_TYPE); 
          SET mTEXT=MD5(mTEXT);       
        ELSEIF mJOURNAL_ITEM_TYPE = 19 THEN
          SET mJOURNAL_ITEM_TRANS_ID = NEW.journal_item_trans_id;
          SET mTEXT=CONCAT(mBRANCH,mJOURNAL_ITEM_TRANS_ID,mJOURNAL_ITEM_TYPE); 
          SET mTEXT=MD5(mTEXT);       
        ELSEIF mJOURNAL_ITEM_TYPE = 20 THEN
          SET mJOURNAL_ITEM_TRANS_ID = NEW.journal_item_trans_id;
          SET mTEXT=CONCAT(mBRANCH,mJOURNAL_ITEM_TRANS_ID,mJOURNAL_ITEM_TYPE); 
          SET mTEXT=MD5(mTEXT);
        ELSEIF mJOURNAL_ITEM_TYPE = 21 THEN
          SET mJOURNAL_ITEM_TRANS_ID = NEW.journal_item_trans_id;
          SET mTEXT=CONCAT(mBRANCH,mJOURNAL_ITEM_TRANS_ID,mJOURNAL_ITEM_TYPE); 
          SET mTEXT=MD5(mTEXT);         
        ELSE
          IF mJOURNAL_ITEM_JOURNAL_ID IS NOT NULL THEN
            -- SET mJOURNAL_GROUP_SESSION = NEW.journal_item_group_session;
            SET mTEXT=CONCAT(mBRANCH,mJOURNAL_ITEM_JOURNAL_ID,mJOURNAL_ITEM_TYPE);
            SET mTEXT=MD5(mTEXT);
            /* If on Trigger Before Insert */
          ELSE
              SET mTEXT=CONCAT('UNGROUP');
          END IF;
        END IF;
        SET mSESSION = UPPER(mTEXT);
        SET NEW.journal_item_group_session = LEFT(mSESSION,10);           
      -- END IF;
    END$$
DELIMITER ;

DELIMITER $$
DROP TRIGGER IF EXISTS `tr_menu_before_delete` $$
CREATE TRIGGER `tr_menu_before_delete` BEFORE DELETE ON `menus` FOR EACH ROW 
  BEGIN
    DECLARE mMENU_ID BIGINT(50);
    DECLARE mPARENT_MENU_ID BIGINT(50);
    SET mPARENT_MENU_ID = OLD.menu_parent_id;
    SET mMENU_ID = OLD.menu_id;
    DELETE FROM users_menus WHERE user_menu_menu_id=mMENU_ID;
  END$$
DELIMITER ;

DELIMITER $$
DROP TRIGGER IF EXISTS `tr_order_items_after_delete`$$
CREATE TRIGGER `tr_order_items_after_delete` AFTER DELETE ON `orders_items` FOR EACH ROW 
  BEGIN
    DECLARE mORDER_ITEM_TYPE INT(5);
    DECLARE mORDER_ITEM_ID BIGINT(50);
    DECLARE mORDER_ITEM_TOTAL_NONPPN DOUBLE(18,2) DEFAULT 0;
    DECLARE mORDER_ITEM_TOTAL_PPN DOUBLE(18,2) DEFAULT 0;
    DECLARE mORDER_ITEM_TOTAL DOUBLE(18,2);
    DECLARE mORDER_ITEM_PPN INT(5);
    DECLARE mORDER_ITEM_DISCOUNT DOUBLE(18,2) DEFAULT 0;
    
    SET mORDER_ITEM_TYPE = OLD.order_item_type;
    SET mORDER_ITEM_ID = OLD.order_item_order_id;
    SET mORDER_ITEM_PPN = OLD.order_item_ppn;
    SET mORDER_ITEM_TOTAL = 0;
    /*
    1=PurchaseOrder
    2=SalesOrder
    3=PenawaranPembelian
    4=PenawaranPenjualan
    5=CheckUp Medicine
    6=CheckUp Laboratory
    */  
    IF mORDER_ITEM_ID IS NOT NULL THEN
      SELECT 
      IFNULL(SUM(CASE
        WHEN order_item_ppn = 1 THEN
          order_item_price * order_item_qty + (order_item_price * order_item_qty * 0.1)
        WHEN order_item_ppn = 0 THEN
          order_item_price * order_item_qty
        ELSE 0 END
      ),0), IFNULL(SUM(order_item_discount),0) INTO mORDER_ITEM_TOTAL, mORDER_ITEM_DISCOUNT 
      FROM orders_items 
      WHERE order_item_order_id=OLD.order_item_order_id;
      UPDATE orders SET order_total_dpp=mORDER_ITEM_TOTAL, 
      order_discount=mORDER_ITEM_DISCOUNT,
      order_total=order_total_dpp-order_discount-order_with_dp 
      WHERE order_id=OLD.order_item_order_id;
    END IF; 
  END$$
DELIMITER ;

DELIMITER $$
DROP TRIGGER IF EXISTS `tr_order_items_after_insert`$$
CREATE TRIGGER `tr_order_items_after_insert` AFTER INSERT ON `orders_items` FOR EACH ROW 
  BEGIN
    DECLARE mORDER_ITEM_TYPE INT(5);
    DECLARE mORDER_ITEM_ID BIGINT(50);
    DECLARE mORDER_ITEM_TOTAL_NONPPN DOUBLE(18,2) DEFAULT 0;
    DECLARE mORDER_ITEM_TOTAL_PPN DOUBLE(18,2) DEFAULT 0;
    DECLARE mORDER_ITEM_TOTAL DOUBLE(18,2);
    DECLARE mORDER_ITEM_PPN INT(5);
    DECLARE mORDER_ITEM_DISCOUNT DOUBLE(18,2) DEFAULT 0;

    SET mORDER_ITEM_TYPE = NEW.order_item_type;
    SET mORDER_ITEM_ID = NEW.order_item_order_id;
    SET mORDER_ITEM_PPN = NEW.order_item_ppn;
    SET mORDER_ITEM_TOTAL = 0;
    /*
    1=PurchaseOrder
    2=SalesOrder
    3=PenawaranPembelian
    4=PenawaranPenjualan
    5=CheckUp Medicine
    6=CheckUp Laboratory
    */  
    IF mORDER_ITEM_ID IS NOT NULL THEN
      SELECT 
      IFNULL(SUM(CASE
        WHEN order_item_ppn = 1 THEN
          order_item_price * order_item_qty + (order_item_price * order_item_qty * 0.1)
        WHEN order_item_ppn = 0 THEN
          order_item_price * order_item_qty
        ELSE 0 END
      ),0), IFNULL(SUM(order_item_discount),0) INTO mORDER_ITEM_TOTAL, mORDER_ITEM_DISCOUNT 
      FROM orders_items 
      WHERE order_item_order_id=NEW.order_item_order_id;
      UPDATE orders SET order_total_dpp=mORDER_ITEM_TOTAL, 
      order_discount=mORDER_ITEM_DISCOUNT,
      order_total=order_total_dpp-order_discount-order_with_dp 
      WHERE order_id=NEW.order_item_order_id;
    END IF;
  END$$
DELIMITER ;

DELIMITER $$
DROP TRIGGER IF EXISTS `tr_order_items_after_update`$$
CREATE TRIGGER `tr_order_items_after_update` AFTER UPDATE ON `orders_items` FOR EACH ROW 
  BEGIN
    DECLARE mORDER_ITEM_TYPE INT(5);
    DECLARE mORDER_ITEM_ID BIGINT(50);
    DECLARE mORDER_ITEM_TOTAL_NONPPN DOUBLE(18,2) DEFAULT 0;
    DECLARE mORDER_ITEM_TOTAL_PPN DOUBLE(18,2) DEFAULT 0;
    DECLARE mORDER_ITEM_TOTAL DOUBLE(18,2);
    DECLARE mORDER_ITEM_PPN INT(5);
    DECLARE mORDER_ITEM_DISCOUNT DOUBLE(18,2) DEFAULT 0;

    SET mORDER_ITEM_TYPE = NEW.order_item_type;
    SET mORDER_ITEM_ID = NEW.order_item_order_id;
    SET mORDER_ITEM_PPN = NEW.order_item_ppn;
    SET mORDER_ITEM_TOTAL = 0;
    /*
    1=PurchaseOrder
    2=SalesOrder
    3=PenawaranPembelian
    4=PenawaranPenjualan
    5=CheckUp Medicine
    6=CheckUp Laboratory
    */
    
    IF mORDER_ITEM_ID IS NOT NULL THEN
      SELECT 
      IFNULL(SUM(CASE
        WHEN order_item_ppn = 1 THEN
          order_item_price * order_item_qty + (order_item_price * order_item_qty * 0.1)
        WHEN order_item_ppn = 0 THEN
          order_item_price * order_item_qty
        ELSE 0 END
      ),0), IFNULL(SUM(order_item_discount),0) INTO mORDER_ITEM_TOTAL, mORDER_ITEM_DISCOUNT 
      FROM orders_items 
      WHERE order_item_order_id=NEW.order_item_order_id;
      UPDATE orders SET order_total_dpp=mORDER_ITEM_TOTAL, 
      order_discount=mORDER_ITEM_DISCOUNT,
      order_total=order_total_dpp-order_discount-order_with_dp 
      WHERE order_id=NEW.order_item_order_id;
    END IF;
  END$$
DELIMITER ;

DELIMITER $$
DROP TRIGGER IF EXISTS `tr_order_items_before_insert`$$
CREATE TRIGGER `tr_order_items_before_insert` BEFORE INSERT ON `orders_items` FOR EACH ROW 
  BEGIN
    SET @mORDER_TYPE = NEW.order_item_type;
    SET @mORDER_TYPE_NAME = '-';
    SELECT type_name INTO @mORDER_TYPE_NAME FROM `types` WHERE type_type=@mORDER_TYPE AND type_for=1;
    SET NEW.order_item_type_name = @mORDER_TYPE_NAME;
  END$$
DELIMITER ;

DELIMITER $$
DROP TRIGGER IF EXISTS `tr_product_before_insert`$$
CREATE TRIGGER `tr_product_before_insert` BEFORE INSERT ON `products` FOR EACH ROW
    BEGIN
        IF NEW.product_type = 3 THEN /* Inventaris */
            IF NEW.product_reminder IS NOT NULL THEN
                IF NEW.product_reminder = 'daily' THEN
                    SET NEW.product_reminder_date = DATE_ADD(NOW(),INTERVAL 1 DAY);
                ELSEIF NEW.product_reminder = 'weekly' THEN
                    SET NEW.product_reminder_date = DATE_ADD(NOW(),INTERVAL 7 DAY);
                ELSEIF NEW.product_reminder = 'monthly' THEN
                    SET NEW.product_reminder_date = DATE_ADD(NOW(),INTERVAL 30 DAY);
                ELSEIF NEW.product_reminder = 'yearly' THEN
                    SET NEW.product_reminder_date = DATE_ADD(NOW(),INTERVAL 365 DAY);
                END IF;
            END IF;
        END IF;

        IF NEW.product_barcode IS NULL THEN 
          SET NEW.product_barcode = fn_create_session_length(8);
        END IF;
    END$$
DELIMITER ;

DELIMITER $$
DROP TRIGGER IF EXISTS `tr_product_before_update`$$
CREATE TRIGGER `tr_product_before_update` BEFORE UPDATE ON `products` FOR EACH ROW
    BEGIN
        IF NEW.product_type = 3 THEN /* Inventaris */
            IF NEW.product_reminder IS NOT NULL THEN
                IF NEW.product_reminder = 'daily' THEN
                    SET NEW.product_reminder_date = DATE_ADD(NOW(),INTERVAL 1 DAY);
                ELSEIF NEW.product_reminder = 'weekly' THEN
                    SET NEW.product_reminder_date = DATE_ADD(NOW(),INTERVAL 7 DAY);
                ELSEIF NEW.product_reminder = 'monthly' THEN
                    SET NEW.product_reminder_date = DATE_ADD(NOW(),INTERVAL 30 DAY);
                ELSEIF NEW.product_reminder = 'yearly' THEN
                    SET NEW.product_reminder_date = DATE_ADD(NOW(),INTERVAL 365 DAY);
                END IF;
            END IF;
        END IF;
    END$$
DELIMITER ;

DELIMITER $$
DROP TRIGGER IF EXISTS `tr_product_after_insert`$$ 
CREATE TRIGGER `tr_product_after_insert` AFTER INSERT ON `products`
FOR EACH ROW 
BEGIN
    IF NEW.product_category_id IS NOT NULL THEN
        UPDATE categories SET category_count_data=(
            SELECT COUNT(*) FROM products WHERE product_category_id=NEW.product_category_id
        ) WHERE category_id=NEW.product_category_id; 
    END IF;
END $$
DELIMITER ;

DELIMITER $$
DROP TRIGGER IF EXISTS `tr_product_after_update`$$ 
CREATE TRIGGER `tr_product_after_update` AFTER UPDATE ON `products`
FOR EACH ROW 
BEGIN
    IF NEW.product_category_id IS NOT NULL THEN
        IF NEW.product_category_id != OLD.product_category_id THEN
            UPDATE categories SET category_count_data=(
                SELECT COUNT(*) FROM products WHERE product_category_id=NEW.product_category_id
            ) WHERE category_id=NEW.product_category_id; 

            UPDATE categories SET category_count_data=(
                SELECT COUNT(*) FROM products WHERE product_category_id=OLD.product_category_id
            ) WHERE category_id=OLD.product_category_id;
        END IF;
    END IF;

    IF NEW.product_category_id IS NULL THEN
        IF OLD.product_category_id IS NOT NULL THEN 
            UPDATE categories SET category_count_data=(
                SELECT COUNT(*) FROM products WHERE product_category_id=OLD.product_category_id
            ) WHERE category_id=OLD.product_category_id;            
        END IF;  
    END IF;
END $$
DELIMITER ;

DELIMITER $$
DROP TRIGGER IF EXISTS `tr_product_after_delete`$$ 
CREATE TRIGGER `tr_product_after_delete` AFTER DELETE ON `products`
FOR EACH ROW 
BEGIN
    IF OLD.product_category_id IS NOT NULL THEN
        UPDATE categories SET category_count_data=(
            SELECT COUNT(*) FROM products WHERE product_category_id=OLD.product_category_id
        ) WHERE category_id=OLD.product_category_id;
    END IF;
END $$
DELIMITER ;

DELIMITER $$
DROP TRIGGER IF EXISTS `tr_trans_after_insert`$$
CREATE TRIGGER `tr_trans_after_insert` AFTER INSERT ON `trans` FOR EACH ROW 
  BEGIN
    DECLARE mTRANS_ID BIGINT(50);
    DECLARE mTRANS_SOURCE_ID BIGINT(50);  
    DECLARE mTRANS_TYPE INT(5);
    DECLARE mTRANS_LOCATION BIGINT(50);
    DECLARE mFINISHED INT(5);
    DECLARE mTRANS_SUBTOTAL DOUBLE(18,2) DEFAULT 0;
    DECLARE mTRANS_PAID INT(5) DEFAULT 0;
    DECLARE mTRANS_TOTAL_PAID DOUBLE(18,2);
    DECLARE mTRANS_TOTAL DOUBLE(18,2);
    DECLARE mFOUND_ROWS INT(255) DEFAULT 0;

    SET mTRANS_TOTAL = 0;
    SET mTRANS_TOTAL_PAID = 0;
    -- SET mPAID_INTO_TRANS = 0;
    SET mFINISHED = 0;

    SET mTRANS_ID = NEW.trans_id;
    SET mTRANS_TYPE = NEW.trans_type;
    SET mTRANS_LOCATION = NEW.trans_location_id;
    
    /* Transfer Stok = Update Qty In for trans_id */
    IF mTRANS_TYPE = 5 THEN 
      UPDATE trans_items SET trans_item_id=mTRANS_ID 
      WHERE trans_item_trans_id IS NULL AND trans_item_ref IN (
      SELECT trans_item_ref FROM trans_items WHERE trans_item_trans_id=mTRANS_ID
      );
    ELSEIF mTRANS_TYPE = 1 THEN 
      CALL sp_contact_payable_receivable_update(mTRANS_TYPE,NEW.trans_contact_id);
    ELSEIF mTRANS_TYPE = 2 THEN
      CALL sp_contact_payable_receivable_update(mTRANS_TYPE,NEW.trans_contact_id);
    END IF;
  END$$
DELIMITER ;

DELIMITER $$
DROP TRIGGER IF EXISTS `tr_trans_before_insert`$$
CREATE TRIGGER `tr_trans_before_insert` BEFORE INSERT ON `trans` FOR EACH ROW 
  BEGIN
    DECLARE mTRANS_ID BIGINT(50);
    DECLARE mTRANS_TYPE INT(5);
    DECLARE mTRANS_LOCATION BIGINT(50);
    DECLARE mTRANS_DISCOUNT DOUBLE(18,2);
    DECLARE mTRANS_VOUCHER DOUBLE(18,2);
    DECLARE mTRANS_TOTAL DOUBLE(18,2);
    DECLARE mTRANS_TOTAL_DPP DOUBLE(18,2);
    DECLARE mTRANS_RETURN DOUBLE(18,2);

    SET mTRANS_ID = NEW.trans_id;
    SET mTRANS_TYPE = NEW.trans_type;
    SET mTRANS_LOCATION = NEW.trans_location_id;
    SET mTRANS_DISCOUNT = NEW.trans_discount;
    SET mTRANS_VOUCHER = NEW.trans_voucher;
    SET mTRANS_RETURN = NEW.trans_return; 
    SET mTRANS_TOTAL = NEW.trans_total;
    SET mTRANS_TOTAL_DPP = NEW.trans_total_dpp;
    
    SET mTRANS_TOTAL = mTRANS_TOTAL_DPP;
    IF mTRANS_DISCOUNT > 0 THEN 
      SET mTRANS_TOTAL = mTRANS_TOTAL_DPP - mTRANS_DISCOUNT;
    END IF;

    IF mTRANS_VOUCHER > 0 THEN
      SET mTRANS_TOTAL = mTRANS_TOTAL - mTRANS_VOUCHER;
    END IF;

    IF mTRANS_RETURN > 0 THEN 
      SET mTRANS_TOTAL = mTRANS_TOTAL - mTRANS_RETURN;
    END IF;

    SET NEW.trans_total = mTRANS_TOTAL;

    IF NEW.trans_sales_id IS NOT NULL THEN
      SELECT contact_name INTO @mCONTACT_NAME FROM contacts WHERE contact_id=NEW.trans_sales_id;
      SET NEW.trans_sales_name=@mCONTACT_NAME;
    END IF;

  END$$
DELIMITER ;

DELIMITER $$ 
DROP TRIGGER IF EXISTS `tr_trans_before_update`$$
CREATE TRIGGER `tr_trans_before_update` BEFORE UPDATE ON `trans` FOR EACH ROW 
  BEGIN
    DECLARE mTRANS_ID BIGINT(50);
    DECLARE mTRANS_TYPE INT(5);
    DECLARE mTRANS_LOCATION BIGINT(50);
    DECLARE mTRANS_DISCOUNT DOUBLE(18,2);
    DECLARE mTRANS_VOUCHER DOUBLE(18,2);
    DECLARE mTRANS_TOTAL DOUBLE(18,2);
    DECLARE mTRANS_TOTAL_DPP DOUBLE(18,2);
    DECLARE mTRANS_TOTAL_PPN DOUBLE(18,2);    
    DECLARE mTRANS_RETURN DOUBLE(18,2);
    DECLARE mPRODUCTION_COST DOUBLE(18,2);
    DECLARE mTRANS_PAID INT(5);

    SET mTRANS_ID = NEW.trans_id;
    SET mTRANS_TYPE = NEW.trans_type;
    SET mTRANS_LOCATION = NEW.trans_location_id;
    SET mTRANS_DISCOUNT = NEW.trans_discount;
    SET mTRANS_VOUCHER = NEW.trans_voucher;
    SET mTRANS_RETURN = NEW.trans_return; 
    SET mTRANS_TOTAL = NEW.trans_total;
    SET mTRANS_TOTAL_DPP = NEW.trans_total_dpp;
    SET mTRANS_TOTAL_PPN = NEW.trans_total_ppn;    
    SET mTRANS_PAID = NEW.trans_paid;

    SET mTRANS_TOTAL = mTRANS_TOTAL_DPP + mTRANS_TOTAL_PPN;
    IF mTRANS_DISCOUNT > 0 THEN 
      SET mTRANS_TOTAL = mTRANS_TOTAL - mTRANS_DISCOUNT;
    END IF;
    
    IF mTRANS_VOUCHER > 0 THEN
      SET mTRANS_TOTAL = mTRANS_TOTAL - mTRANS_VOUCHER;
    END IF;

    IF mTRANS_RETURN > 0 THEN 
      SET mTRANS_TOTAL = mTRANS_TOTAL - mTRANS_RETURN;
      
      -- Only Trans Return for Sales / Purchase
      IF mTRANS_PAID = 1 THEN -- Jika Faktur sudah lunas
        -- SET NEW.trans_fee = 2; 
        IF NEW.trans_total_paid >= mTRANS_TOTAL THEN
          SET NEW.trans_paid = 1;
        ELSE
          SET NEW.trans_paid = 0;
        END IF;
      ELSEIF mTRANS_PAID = 0 THEN -- Jika Faktur belum lunas
        IF NEW.trans_total_paid >= mTRANS_TOTAL THEN
          SET NEW.trans_paid = 1;
        ELSE
          SET NEW.trans_paid = 0;
        END IF;
      END IF;
    ELSE
      IF NEW.trans_total_paid >= mTRANS_TOTAL THEN
        SET NEW.trans_paid = 1;
      ELSE
        SET NEW.trans_paid = 0;
      END IF;      
    END IF;

    SET NEW.trans_total = mTRANS_TOTAL;
    SET NEW.trans_date_updated = NOW();

    IF NEW.trans_sales_id IS NOT NULL THEN
      SELECT contact_name INTO @mCONTACT_NAME FROM contacts WHERE contact_id=NEW.trans_sales_id;
      SET NEW.trans_sales_name=@mCONTACT_NAME;
    END IF;
    
  END$$
DELIMITER ;

DELIMITER $$ 
DROP TRIGGER IF EXISTS `tr_trans_after_update`$$
CREATE TRIGGER `tr_trans_after_update` AFTER UPDATE ON `trans` FOR EACH ROW 
  BEGIN
    DECLARE mTRANS_TYPE INT(5);
    SET mTRANS_TYPE = NEW.trans_type;
    IF mTRANS_TYPE = 1 THEN 
      CALL sp_contact_payable_receivable_update(mTRANS_TYPE,NEW.trans_contact_id);
    ELSEIF mTRANS_TYPE = 2 THEN
      CALL sp_contact_payable_receivable_update(mTRANS_TYPE,NEW.trans_contact_id);
    END IF;
  END$$
DELIMITER ;

DELIMITER $$
DROP TRIGGER IF EXISTS `tr_trans_items_after_delete`$$
CREATE TRIGGER `tr_trans_items_after_delete` AFTER DELETE ON `trans_items` FOR EACH ROW 
  BEGIN
    /*
      1=Pembelian
      2=Penjualan
      3=ReturPembelian
      4=ReturPenjualan
      5=MutasiGudang
      6=StokOpnamePlus
      7=StokOpnameMinus
    */  
    DECLARE mTRANS_ITEM_TYPE INT(5);
    DECLARE mTRANS_ITEM_ID BIGINT(50);
    DECLARE mTRANS_ITEM_TOTAL_DPP DOUBLE(18,2) DEFAULT 0;
    DECLARE mTRANS_ITEM_TOTAL_PPN DOUBLE(18,2) DEFAULT 0;
    DECLARE mTRANS_ITEM_DISCOUNT DOUBLE(18,2) DEFAULT 0;    
    DECLARE mTRANS_ITEM_TOTAL DOUBLE(18,2);
    DECLARE mTRANS_ITEM_PPN INT(5);
    DECLARE mTRANS_ITEM_PRODUCT_ID BIGINT(50);
    DECLARE mPRODUCT_STOCK DOUBLE(18,2);
    DECLARE mTRANS_ITEM_REF VARCHAR(255);
    DECLARE mTRANS_ITEM_POSITION INT(5);
    DECLARE mTRANS_ITEM_FLAG INT(5);
    
    SET mTRANS_ITEM_TYPE = OLD.trans_item_type;
    SET mTRANS_ITEM_ID = OLD.trans_item_trans_id;
    SET mTRANS_ITEM_PPN = OLD.trans_item_ppn;
    SET mTRANS_ITEM_TOTAL = 0;
    SET mTRANS_ITEM_PRODUCT_ID = OLD.trans_item_product_id;
    SET mTRANS_ITEM_REF = OLD.trans_item_ref;
    SET mTRANS_ITEM_POSITION = OLD.trans_item_position; 
    SET mTRANS_ITEM_FLAG = OLD.trans_item_flag;
    
    -- Update Total if Has PPN    
    IF mTRANS_ITEM_ID IS NOT NULL THEN
      IF mTRANS_ITEM_TYPE = 1 THEN /* Pembelian */
        SELECT 
        IFNULL(SUM(trans_item_total),0),        
        IFNULL(SUM((trans_item_in_price * trans_item_in_qty) * (trans_item_ppn_value / 100)),0)
        INTO mTRANS_ITEM_TOTAL_DPP, mTRANS_ITEM_TOTAL_PPN    
        FROM trans_items 
        WHERE trans_item_trans_id=OLD.trans_item_trans_id;
      ELSEIF mTRANS_ITEM_TYPE = 2 THEN /* Penjualan */
        SELECT 
        IFNULL(SUM(trans_item_sell_total),0)
        INTO mTRANS_ITEM_TOTAL_DPP   
        FROM trans_items 
        WHERE trans_item_trans_id=OLD.trans_item_trans_id;  

        /* Cari Produk yg ber Ppn Saja*/
        SELECT IFNULL(SUM(trans_item_sell_total * (trans_item_ppn_value / 100)),0) INTO mTRANS_ITEM_TOTAL_PPN   
        FROM trans_items WHERE trans_item_trans_id=OLD.trans_item_trans_id AND trans_item_ppn=1;            
        -- SELECT IFNULL(SUM(trans_item_sell_total),0) INTO mTRANS_ITEM_TOTAL     
        -- FROM trans_items 
        -- WHERE trans_item_trans_id=NEW.trans_item_trans_id;
      ELSEIF mTRANS_ITEM_TYPE = 3 THEN /* Retur beli */
        SELECT 
        IFNULL(SUM(trans_item_total),0),        
        IFNULL(SUM((trans_item_out_price * trans_item_out_qty) * (trans_item_ppn_value / 100)),0)
        INTO mTRANS_ITEM_TOTAL_DPP, mTRANS_ITEM_TOTAL_PPN    
        FROM trans_items 
        WHERE trans_item_trans_id=OLD.trans_item_trans_id;         
      ELSEIF mTRANS_ITEM_TYPE = 4 THEN /* Retur Jual */
        SELECT 
        IFNULL(SUM(trans_item_total),0),        
        IFNULL(SUM((trans_item_sell_price * trans_item_in_qty) * (trans_item_ppn_value / 100)),0)
        INTO mTRANS_ITEM_TOTAL_DPP, mTRANS_ITEM_TOTAL_PPN    
        FROM trans_items 
        WHERE trans_item_trans_id=trans_item_trans_id; 
      ELSEIF mTRANS_ITEM_TYPE = 6 THEN /* Stock Opname Plus */
        SELECT 
        IFNULL(SUM(trans_item_in_price * trans_item_in_qty),0) INTO mTRANS_ITEM_TOTAL_DPP    
        FROM trans_items 
        WHERE trans_item_trans_id=OLD.trans_item_trans_id;    
      ELSEIF mTRANS_ITEM_TYPE = 5 THEN /* Transfer Stock */

        /* Remove In Other Location */
        /*IF mTRANS_ITEM_POSITION = 2 THEN
          DELETE FROM trans_items WHERE trans_item_product_id=mTRANS_ITEM_PRODUCT_ID AND trans_item_position=1 AND trans_item_ref=mTRANS_ITEM_REF;
        END IF;     
        */
        SELECT IFNULL(SUM(trans_item_total),0) INTO mTRANS_ITEM_TOTAL_DPP 
        FROM trans_items 
        WHERE trans_item_trans_id=OLD.trans_item_trans_id
        AND trans_item_position = 2; -- Get Cost of Raw Material
      ELSEIF mTRANS_ITEM_TYPE = 8 THEN /* Produksi */
        SELECT IFNULL(SUM(trans_item_total),0) INTO mTRANS_ITEM_TOTAL_DPP 
        FROM trans_items 
        WHERE trans_item_trans_id=OLD.trans_item_trans_id
        AND trans_item_position = 2; -- Get Cost of Raw Material
      ELSE 
        SELECT IFNULL(SUM(trans_item_total),0) INTO mTRANS_ITEM_TOTAL_DPP    
        FROM trans_items 
        WHERE trans_item_trans_id=OLD.trans_item_trans_id;        
      END IF;
    
      /* Update Total to Trans */     
      SET mTRANS_ITEM_TOTAL = mTRANS_ITEM_TOTAL_DPP + mTRANS_ITEM_TOTAL_PPN;      
      UPDATE trans 
      SET 
        trans_total_dpp=mTRANS_ITEM_TOTAL_DPP + mTRANS_ITEM_DISCOUNT,
        trans_total_ppn=mTRANS_ITEM_TOTAL_PPN
      WHERE trans_id=OLD.trans_item_trans_id;

      /* Re Calculate Product Stock */
      IF mTRANS_ITEM_FLAG = 1 THEN
        CALL sp_product_stock_update(mTRANS_ITEM_PRODUCT_ID);
      END IF;
    
    END IF;
    /* Delete In Qty on Transfer Stok */
    /*
    IF mTRANS_ITEM_TYPE = 5 THEN
      IF mTRANS_ITEM_POSITION = 2 THEN
        DELETE FROM trans_items WHERE trans_item_product_id=mTRANS_ITEM_PRODUCT_ID AND trans_item_position=1 AND trans_item_ref=mTRANS_ITEM_REF;
      END IF;
    END IF;
    */
  END$$
DELIMITER ;

DELIMITER $$
DROP TRIGGER IF EXISTS `tr_trans_items_after_insert`$$
CREATE TRIGGER `tr_trans_items_after_insert` AFTER INSERT ON `trans_items` FOR EACH ROW 
  BEGIN
    /*
      1=Pembelian
      2=Penjualan
      3=ReturPembelian
      4=ReturPenjualan
      5=MutasiGudang
      6=StokOpnamePlus
      7=StokOpnameMinus
    */  
    DECLARE mTRANS_ITEM_TYPE INT(5);
    DECLARE mTRANS_ITEM_ID BIGINT(50);
    DECLARE mTRANS_ITEM_TOTAL_DPP DOUBLE(18,2) DEFAULT 0;
    DECLARE mTRANS_ITEM_TOTAL_PPN DOUBLE(18,2) DEFAULT 0;
    DECLARE mTRANS_ITEM_DISCOUNT DOUBLE(18,2) DEFAULT 0;
    DECLARE mTRANS_ITEM_TOTAL DOUBLE(18,2) DEFAULT 0;
    DECLARE mTRANS_ITEM_PPN INT(5);
    DECLARE mTRANS_ITEM_PRODUCT_ID BIGINT(50);
    DECLARE mTRANS_ITEM_FLAG INT(5);
    
    SET mTRANS_ITEM_TYPE = NEW.trans_item_type;
    SET mTRANS_ITEM_ID = NEW.trans_item_trans_id;
    SET mTRANS_ITEM_PPN = NEW.trans_item_ppn;
    SET mTRANS_ITEM_TOTAL = 0;
    SET mTRANS_ITEM_PRODUCT_ID = NEW.trans_item_product_id; 
    SET mTRANS_ITEM_FLAG = NEW.trans_item_flag;
      
    IF mTRANS_ITEM_ID IS NOT NULL THEN
      IF mTRANS_ITEM_TYPE = 1 THEN /* Pembelian */
        SELECT 
        IFNULL(SUM(trans_item_total),0),        
        IFNULL(SUM((NEW.trans_item_in_price * NEW.trans_item_in_qty) * (NEW.trans_item_ppn_value / 100)),0)
        INTO mTRANS_ITEM_TOTAL_DPP, mTRANS_ITEM_TOTAL_PPN    
        FROM trans_items 
        WHERE trans_item_trans_id=NEW.trans_item_trans_id;
      ELSEIF mTRANS_ITEM_TYPE = 2 THEN /* Penjualan */
        SELECT 
        IFNULL(SUM(trans_item_sell_total),0),
        IFNULL(SUM(trans_item_discount),0) 
        INTO mTRANS_ITEM_TOTAL_DPP, mTRANS_ITEM_DISCOUNT    
        FROM trans_items 
        WHERE trans_item_trans_id=NEW.trans_item_trans_id; 

        /* Cari Produk yg ber Ppn Saja*/
        SELECT IFNULL(SUM(trans_item_sell_total * (trans_item_ppn_value / 100)),0) INTO mTRANS_ITEM_TOTAL_PPN   
        FROM trans_items WHERE trans_item_trans_id=NEW.trans_item_trans_id AND trans_item_ppn=1;

        -- SELECT IFNULL(SUM(trans_item_sell_total),0) INTO mTRANS_ITEM_TOTAL     
        -- FROM trans_items 
        -- WHERE trans_item_trans_id=NEW.trans_item_trans_id;
      ELSEIF mTRANS_ITEM_TYPE = 3 THEN /* Retur beli */
        SELECT 
        IFNULL(SUM(trans_item_total),0),        
        IFNULL(SUM((NEW.trans_item_out_price * NEW.trans_item_out_qty) * (NEW.trans_item_ppn_value / 100)),0)
        INTO mTRANS_ITEM_TOTAL_DPP, mTRANS_ITEM_TOTAL_PPN    
        FROM trans_items 
        WHERE trans_item_trans_id=NEW.trans_item_trans_id;     
      ELSEIF mTRANS_ITEM_TYPE = 4 THEN /* Retur Jual */
        SELECT 
        IFNULL(SUM(trans_item_total),0),        
        IFNULL(SUM((NEW.trans_item_sell_price * NEW.trans_item_in_qty) * (NEW.trans_item_ppn_value / 100)),0)
        INTO mTRANS_ITEM_TOTAL_DPP, mTRANS_ITEM_TOTAL_PPN    
        FROM trans_items 
        WHERE trans_item_trans_id=NEW.trans_item_trans_id;                  
      ELSEIF mTRANS_ITEM_TYPE = 6 THEN /* Stock Opname Plus */
        SELECT 
        IFNULL(SUM(trans_item_in_price * trans_item_in_qty),0) INTO mTRANS_ITEM_TOTAL_DPP    
        FROM trans_items 
        WHERE trans_item_trans_id=NEW.trans_item_trans_id;
      ELSEIF mTRANS_ITEM_TYPE = 5 THEN /* Transfer Stock */
        SELECT IFNULL(SUM(trans_item_total),0) INTO mTRANS_ITEM_TOTAL_DPP    
        FROM trans_items 
        WHERE trans_item_trans_id=NEW.trans_item_trans_id
        AND trans_item_position=2;    
      ELSEIF mTRANS_ITEM_TYPE = 8 THEN /* Produksi */
        SELECT IFNULL(SUM(trans_item_total),0) INTO mTRANS_ITEM_TOTAL_DPP    
        FROM trans_items 
        WHERE trans_item_trans_id=NEW.trans_item_trans_id
        AND trans_item_position=2;      
      ELSE 
        SELECT IFNULL(SUM(trans_item_total),0) INTO mTRANS_ITEM_TOTAL_DPP    
        FROM trans_items 
        WHERE trans_item_trans_id=NEW.trans_item_trans_id;    
      END IF;

      /* Trans Has Discount
      IF mTRANS_ITEM_DISCOUNT > 0 THEN
        SET mTRANS_ITEM_TOTAL_DPP = mTRANS_ITEM_TOTAL_DPP + mTRANS_ITEM_DISCOUNT;
      END IF;
      */

      /* Update Total to Trans */   
      SET mTRANS_ITEM_TOTAL = mTRANS_ITEM_TOTAL_DPP + mTRANS_ITEM_TOTAL_PPN;
      UPDATE trans 
      SET 
        trans_total_dpp=mTRANS_ITEM_TOTAL_DPP + mTRANS_ITEM_DISCOUNT, 
        trans_total_ppn=mTRANS_ITEM_TOTAL_PPN
        -- trans_total_discount=mTRANS_ITEM_DISCOUNT 
      WHERE trans_id=NEW.trans_item_trans_id;
    
      /* Re Calculate Product Stock */    
      IF mTRANS_ITEM_FLAG = 1 THEN
        CALL sp_product_stock_update(mTRANS_ITEM_PRODUCT_ID);
      END IF;
    END IF;
  END$$
DELIMITER ;

DELIMITER $$
DROP TRIGGER IF EXISTS `tr_trans_items_after_update`$$
CREATE TRIGGER `tr_trans_items_after_update` AFTER UPDATE ON `trans_items` FOR EACH ROW 
  BEGIN
    DECLARE mTRANS_ITEM_TYPE INT(5);
    DECLARE mTRANS_ITEM_ID BIGINT(50);
    DECLARE mTRANS_ITEM_TOTAL_DPP DOUBLE(18,2) DEFAULT 0;
    DECLARE mTRANS_ITEM_TOTAL_PPN DOUBLE(18,2) DEFAULT 0;
    DECLARE mTRANS_ITEM_DISCOUNT DOUBLE(18,2) DEFAULT 0;
    DECLARE mTRANS_ITEM_TOTAL DOUBLE(18,2) DEFAULT 0;
    DECLARE mTRANS_ITEM_PPN INT(5);
    DECLARE mTRANS_ITEM_PRODUCT_ID BIGINT(50);
    DECLARE mTRANS_ITEM_FLAG INT(5);
    
    SET mTRANS_ITEM_TYPE = NEW.trans_item_type;
    SET mTRANS_ITEM_ID = NEW.trans_item_trans_id;
    SET mTRANS_ITEM_PPN = NEW.trans_item_ppn;
    SET mTRANS_ITEM_TOTAL = 0;
    SET mTRANS_ITEM_PRODUCT_ID = NEW.trans_item_product_id; 
    SET mTRANS_ITEM_FLAG = NEW.trans_item_flag;
    /*
      1=Pembelian
      2=Penjualan
      3=ReturPembelian
      4=ReturPenjualan
      5=MutasiGudang
      6=StokOpnamePlus
      7=StokOpnameMinus
    */  
    IF mTRANS_ITEM_ID IS NOT NULL THEN
      IF mTRANS_ITEM_TYPE = 1 THEN /* Pembelian */
        SELECT 
        IFNULL(SUM(trans_item_total),0),        
        IFNULL(SUM((trans_item_in_price * trans_item_in_qty) * (trans_item_ppn_value / 100)),0)
        INTO mTRANS_ITEM_TOTAL_DPP, mTRANS_ITEM_TOTAL_PPN    
        FROM trans_items 
        WHERE trans_item_trans_id=NEW.trans_item_trans_id;
      ELSEIF mTRANS_ITEM_TYPE = 2 THEN /* Penjualan */
        SELECT 
        IFNULL(SUM(trans_item_sell_total),0),
        IFNULL(SUM(trans_item_discount),0) 
        INTO mTRANS_ITEM_TOTAL_DPP, mTRANS_ITEM_DISCOUNT  
        FROM trans_items 
        WHERE trans_item_trans_id=NEW.trans_item_trans_id;   

        /* Cari Produk yg ber Ppn Saja*/
        SELECT IFNULL(SUM(trans_item_sell_total * (trans_item_ppn_value / 100)),0) INTO mTRANS_ITEM_TOTAL_PPN   
        FROM trans_items WHERE trans_item_trans_id=NEW.trans_item_trans_id AND trans_item_ppn=1;              
        -- SELECT IFNULL(SUM(trans_item_sell_total),0) INTO mTRANS_ITEM_TOTAL     
        -- FROM trans_items 
        -- WHERE trans_item_trans_id=NEW.trans_item_trans_id;
      ELSEIF mTRANS_ITEM_TYPE = 3 THEN /* Retur Beli */
        SELECT 
        IFNULL(SUM(trans_item_total),0),        
        IFNULL(SUM((trans_item_out_price * trans_item_out_qty) * (trans_item_ppn_value / 100)),0)
        INTO mTRANS_ITEM_TOTAL_DPP, mTRANS_ITEM_TOTAL_PPN    
        FROM trans_items 
        WHERE trans_item_trans_id=NEW.trans_item_trans_id;
      ELSEIF mTRANS_ITEM_TYPE = 4 THEN /* Retur Jual */
        SELECT 
        IFNULL(SUM(trans_item_total),0),        
        IFNULL(SUM((trans_item_sell_price * trans_item_in_qty) * (trans_item_ppn_value / 100)),0)
        INTO mTRANS_ITEM_TOTAL_DPP, mTRANS_ITEM_TOTAL_PPN    
        FROM trans_items 
        WHERE trans_item_trans_id=NEW.trans_item_trans_id;        
      ELSEIF mTRANS_ITEM_TYPE = 6 THEN /* Stock Opname Plus */
        SELECT 
        IFNULL(SUM(trans_item_in_price * trans_item_in_qty),0) INTO mTRANS_ITEM_TOTAL_DPP    
        FROM trans_items 
        WHERE trans_item_trans_id=NEW.trans_item_trans_id;      
      ELSEIF mTRANS_ITEM_TYPE = 5 THEN /* Transfer Stock */
        SELECT IFNULL(SUM(trans_item_total),0) INTO mTRANS_ITEM_TOTAL_DPP 
        FROM trans_items 
        WHERE trans_item_trans_id=NEW.trans_item_trans_id
        AND trans_item_position = 2; -- Get Cost of Raw Material
        -- SET mTRANS_ITEM_TOTAL = 123;   
      ELSEIF mTRANS_ITEM_TYPE = 8 THEN /* Produksi */
        SELECT IFNULL(SUM(trans_item_total),0) INTO mTRANS_ITEM_TOTAL_DPP 
        FROM trans_items 
        WHERE trans_item_trans_id=NEW.trans_item_trans_id
        AND trans_item_position = 2; -- Get Cost of Raw Material
        -- SET mTRANS_ITEM_TOTAL = 123;   
      ELSE 
        SELECT IFNULL(SUM(trans_item_total),0) INTO mTRANS_ITEM_TOTAL_DPP    
        FROM trans_items 
        WHERE trans_item_trans_id=NEW.trans_item_trans_id;    
      END IF;
      
      /* Trans Has Discount
      IF mTRANS_ITEM_DISCOUNT > 0 THEN
        SET mTRANS_ITEM_TOTAL_DPP = mTRANS_ITEM_TOTAL_DPP + mTRANS_ITEM_DISCOUNT;
      END IF; */

      /* Update Total to Trans */
      SET mTRANS_ITEM_TOTAL = mTRANS_ITEM_TOTAL_DPP + mTRANS_ITEM_TOTAL_PPN;
      UPDATE trans 
      SET 
        trans_total_dpp=mTRANS_ITEM_TOTAL_DPP + mTRANS_ITEM_DISCOUNT, 
        trans_total_ppn=mTRANS_ITEM_TOTAL_PPN
        -- trans_discount=mTRANS_ITEM_DISCOUNT 
      WHERE trans_id=NEW.trans_item_trans_id;
      
      /* Re Calculate Product Stock */
      IF mTRANS_ITEM_FLAG = 1 THEN
        CALL sp_product_stock_update(mTRANS_ITEM_PRODUCT_ID);
      END IF;
    END IF; 
  END$$
DELIMITER ;

DELIMITER $$
DROP TRIGGER IF EXISTS `tr_trans_items_before_insert`$$
CREATE TRIGGER `tr_trans_items_before_insert` BEFORE INSERT ON `trans_items` FOR EACH ROW 
  BEGIN
    IF NEW.trans_item_in_qty > 0 THEN
      SET NEW.trans_item_position = 1;
    END IF;

    IF NEW.trans_item_out_qty > 0 THEN
      SET NEW.trans_item_position = 2;
    END IF;
    
    IF NEW.trans_item_type = 2 THEN 
      SET NEW.trans_item_sell_total = (NEW.trans_item_sell_price * NEW.trans_item_out_qty) - NEW.trans_item_discount;
    END IF;
    
    SET @mTRANS_TYPE = NEW.trans_item_type;
    SET @mTRANS_TYPE_NAME = '-';
    SELECT type_name INTO @mTRANS_TYPE_NAME FROM `types` WHERE type_type=@mTRANS_TYPE AND type_for=2;
    SET NEW.trans_item_type_name = @mTRANS_TYPE_NAME;
    END$$
DELIMITER ;


DELIMITER $$
DROP TRIGGER IF EXISTS `tr_activities_before_insert`$$
CREATE TRIGGER `tr_activities_before_insert` BEFORE INSERT ON `activities` FOR EACH ROW
    BEGIN
        DECLARE mNAME VARCHAR(255);
        DECLARE mTYPE INT(5);
        DECLARE mTYPE_NAME VARCHAR(255);
        IF NEW.activity_table = 'contacts' THEN
            SELECT contact_type INTO mTYPE FROM contacts WHERE contact_id=NEW.activity_table_id;
            IF mTYPE = 1 THEN SET NEW.activity_text_1 = 'Supplier';
            ELSEIF mTYPE = 2 THEN SET NEW.activity_text_1 = 'Customer';
            ELSEIF mTYPE = 3 THEN SET NEW.activity_text_2 = 'Karyawan';
            END IF;
            SET NEW.activity_flag=1;
        ELSEIF NEW.activity_table = 'accounts' THEN
            SET NEW.activity_text_1 = 'Akun';
        ELSEIF NEW.activity_table = 'branchs' THEN
            SET NEW.activity_text_1 = 'Cabang';        
        ELSEIF NEW.activity_table = 'categories' THEN
            SET NEW.activity_text_1 = 'Kategori';        
        ELSEIF NEW.activity_table = 'journals' THEN
            SET mNAME = NULL;
            SELECT type_name, type_id INTO mTYPE_NAME, mTYPE FROM journals LEFT JOIN `types` ON journal_type=type_type AND type_for=3 WHERE journal_id=NEW.activity_table_id;
            SELECT contact_name INTO mNAME FROM journals LEFT JOIN contacts ON journal_contact_id=contact_id WHERE journal_id=NEW.activity_table_id;
            SET NEW.activity_text_1 = mTYPE_NAME;
            SET NEW.activity_text_3 = mNAME;
            SET NEW.activity_flag=1;
        ELSEIF NEW.activity_table = 'journals_items' THEN
            SET NEW.activity_text_1 = 'Jurnal Item';                    
        ELSEIF NEW.activity_table = 'locations' THEN
            SET NEW.activity_text_1 = 'Gudang';
        ELSEIF NEW.activity_table = 'menus' THEN
            SET NEW.activity_text_1 = 'Menu';        
        ELSEIF NEW.activity_table = 'news' THEN
            SET NEW.activity_text_1 = 'Berita';        
        ELSEIF NEW.activity_table = 'orders' THEN
            SET NEW.activity_flag = 1;        
        ELSEIF NEW.activity_table = 'order_items' THEN
            SET NEW.activity_flag = 0;
            SET NEW.activity_text_1 = NULL;        
        ELSEIF NEW.activity_table = 'products' THEN
            SET NEW.activity_flag = 1;
            SET NEW.activity_text_3 = NULL;
            SET NEW.activity_text_1 = 'Produk';          
        ELSEIF NEW.activity_table = 'product_categories' THEN
            SET NEW.activity_flag = 1;
            SET NEW.activity_text_3 = NEW.activity_text_1;
            SET NEW.activity_text_1 = NULL;
        ELSEIF NEW.activity_table = 'reference' THEN
            SET NEW.activity_flag = 1;
            SET NEW.activity_text_3 = NEW.activity_text_1;
            SET NEW.activity_text_1 = NULL;        
        ELSEIF NEW.activity_table = 'units' THEN
            SET NEW.activity_flag = 1;
            SET NEW.activity_text_3 = NEW.activity_text_1;
            SET NEW.activity_text_1 = NULL;        
        ELSEIF NEW.activity_table = 'trans' THEN
            SELECT contact_name INTO mNAME FROM trans LEFT JOIN contacts ON trans_contact_id=contact_id WHERE trans_id=NEW.activity_table_id;
            SET NEW.activity_text_3 = mNAME;
        ELSEIF NEW.activity_table = 'trans_items' THEN
            SET NEW.activity_flag = 0;        
        ELSEIF NEW.activity_table = 'users' THEN
            SET NEW.activity_flag = 1;
            SET NEW.activity_text_3 = NEW.activity_text_1;
            SET NEW.activity_text_1 = NULL;                
        END IF;

        -- Update order_item_expired_day = yg Booking bulanan dan sudah checkin
        -- IF NEW.activity_action = '1' THEN
        --   UPDATE orders_items 
        --   SET order_item_expired_day = DATEDIFF(order_item_end_date,NOW())
        --   WHERE order_item_flag_checkin = 1 AND order_item_ref_price_sort = 1;
        -- END IF;
    END$$
DELIMITER ;

DELIMITER $$
DROP TRIGGER IF EXISTS `tr_tax_before_insert`$$ 
CREATE TRIGGER `tr_tax_before_insert` BEFORE INSERT ON `taxs`
  FOR EACH ROW 
  BEGIN
      SET NEW.tax_decimal_0 = NEW.tax_percent / 100;
      SET NEW.tax_decimal_1 = NULL;
      SET NEW.tax_date_created = NOW();
      SET NEW.tax_session = fn_create_session();
      IF NEW.tax_flag = 0 THEN SET NEW.tax_flag=0; END IF;
  END $$
DELIMITER ;

DELIMITER $$
DROP TRIGGER IF EXISTS `tr_tax_before_update`$$ 
CREATE TRIGGER `tr_tax_before_update` BEFORE UPDATE ON `taxs`
  FOR EACH ROW 
  BEGIN
      IF NEW.tax_id > 1 THEN
          SET NEW.tax_decimal_0 = NEW.tax_percent / 100;
          SET NEW.tax_decimal_1 = NULL;
          SET NEW.tax_date_updated = NOW();
      END IF;
  END $$
DELIMITER ;

DELIMITER $$
DROP TRIGGER IF EXISTS `tr_order_before_insert`$$ 
CREATE TRIGGER `tr_order_before_insert` BEFORE INSERT ON `orders`
  FOR EACH ROW 
  BEGIN
    DECLARE mCONTACT_NAME VARCHAR(255);
    IF NEW.order_sales_id IS NOT NULL THEN
      SELECT contact_name INTO mCONTACT_NAME FROM contacts WHERE contact_id=NEW.order_sales_id;
      SET NEW.order_sales_name=mCONTACT_NAME;  
    END IF;
  END $$
DELIMITER ;

DELIMITER $$
DROP TRIGGER IF EXISTS `tr_order_before_update`$$ 
CREATE TRIGGER `tr_order_before_update` BEFORE UPDATE ON `orders`
  FOR EACH ROW 
  BEGIN
    DECLARE mCONTACT_NAME VARCHAR(255);
    IF NEW.order_sales_id IS NOT NULL THEN
      SELECT contact_name INTO mCONTACT_NAME FROM contacts WHERE contact_id=NEW.order_sales_id;
      SET NEW.order_sales_name=mCONTACT_NAME;  
    END IF;
  END $$
DELIMITER ;

DELIMITER $$
DROP TRIGGER IF EXISTS `tr_branch_special_account_before_insert`$$ 
CREATE TRIGGER `tr_branch_special_account_before_insert` BEFORE INSERT ON `branchs_specialists_accounts`
FOR EACH ROW 
BEGIN
   DECLARE mGROUP_NAME VARCHAR(255);
   SELECT group_name INTO mGROUP_NAME FROM accounts_groups WHERE group_group_sub_id=NEW.item_group_sub;
   SET NEW.item_group_sub_name=mGROUP_NAME;
END $$
DELIMITER ;

DELIMITER $$
DROP TRIGGER IF EXISTS `tr_branch_special_account_before_update`$$ 
CREATE TRIGGER `tr_branch_special_account_before_update` BEFORE UPDATE ON `branchs_specialists_accounts`
FOR EACH ROW 
BEGIN
   DECLARE mGROUP_NAME VARCHAR(255);
   SELECT group_name INTO mGROUP_NAME FROM accounts_groups WHERE group_group_sub_id=NEW.item_group_sub;
   SET NEW.item_group_sub_name=mGROUP_NAME;
END $$
DELIMITER ;


DELIMITER $$
DROP TRIGGER IF EXISTS `tr_recipient_after_insert`$$ 
CREATE TRIGGER `tr_recipient_after_insert` AFTER INSERT ON `recipients`
FOR EACH ROW 
BEGIN
    IF NEW.recipient_group_id IS NOT NULL THEN
        UPDATE recipients_groups SET group_count=(
            SELECT COUNT(*) FROM recipients WHERE recipient_group_id=NEW.recipient_group_id
        ) WHERE group_id=NEW.recipient_group_id; 
    END IF;
END $$
DELIMITER ;

DELIMITER $$
DROP TRIGGER IF EXISTS `tr_recipient_after_update`$$ 
CREATE TRIGGER `tr_recipient_after_update` AFTER UPDATE ON `recipients`
FOR EACH ROW 
BEGIN
    IF NEW.recipient_group_id IS NOT NULL THEN
        IF NEW.recipient_group_id != OLD.recipient_group_id THEN
            UPDATE recipients_groups SET group_count=(
                SELECT COUNT(*) FROM recipients WHERE recipient_group_id=NEW.recipient_group_id
            ) WHERE group_id=NEW.recipient_group_id; 

            UPDATE recipients_groups SET group_count=(
                SELECT COUNT(*) FROM recipients WHERE recipient_group_id=OLD.recipient_group_id
            ) WHERE group_id=OLD.recipient_group_id;
        END IF;
    END IF;
END $$
DELIMITER ;

DELIMITER $$
DROP TRIGGER IF EXISTS `tr_file_after_insert`$$
CREATE TRIGGER `tr_file_after_insert` AFTER INSERT ON `files` 
    FOR EACH ROW 
BEGIN
    DECLARE mCOUNT INT(50) DEFAULT 0;
    SELECT COUNT(*) INTO mCOUNT FROM files WHERE file_from_table=NEW.file_from_table AND file_from_id=NEW.file_from_id;
    IF NEW.file_from_table = 'orders' THEN
        UPDATE orders SET order_files_count=mCOUNT WHERE order_id=NEW.file_from_id;
    ELSEIF NEW.file_from_table = 'trans' THEN
        UPDATE trans SET trans_files_count=mCOUNT WHERE trans_id=NEW.file_from_id;
    ELSEIF NEW.file_from_table = 'orders-checkouts' THEN
        INSERT INTO `activities` (`activity_branch_id`, `activity_user_id`, `activity_action`, `activity_table`, `activity_table_id`, `activity_text_1`, `activity_text_2`, `activity_text_3`, `activity_date_created`, `activity_flag`, `activity_type`)
        VALUES('activity_branch_id', NEW.file_user_id, 2, 'files', NEW.file_id, 'Gambar', NEW.file_note, NEW.file_url, NOW(), 1, 1);
    END IF;   
END$$
DELIMITER ;

DELIMITER $$
DROP TRIGGER IF EXISTS `tr_file_after_delete`$$ 
CREATE TRIGGER `tr_file_after_delete` AFTER DELETE ON `files`
FOR EACH ROW 
BEGIN
    DECLARE mCOUNT INT(50) DEFAULT 0;
    SELECT COUNT(*) INTO mCOUNT FROM files WHERE file_from_table=OLD.file_from_table AND file_from_id=OLD.file_from_id;
    IF OLD.file_from_table = 'orders' THEN
        UPDATE orders SET order_files_count=mCOUNT WHERE order_id=OLD.file_from_id;
    ELSEIF OLD.file_from_table = 'trans' THEN
        UPDATE trans SET trans_files_count=mCOUNT WHERE trans_id=OLD.file_from_id;
    END IF;   
END $$
DELIMITER ;

DELIMITER $$
DROP TRIGGER IF EXISTS `tr_approval_after_insert`$$ 
CREATE TRIGGER `tr_approval_after_insert` AFTER INSERT ON `approvals`
FOR EACH ROW 
BEGIN
    DECLARE mCOUNT INT(50) DEFAULT 0;
    SELECT COUNT(*) INTO mCOUNT FROM approvals WHERE approval_from_table=NEW.approval_from_table AND approval_from_id=NEW.approval_from_id;
    IF NEW.approval_from_table = 'orders' THEN
        UPDATE orders SET order_approval_count=mCOUNT WHERE order_id=NEW.approval_from_id;
    ELSEIF NEW.approval_from_table = 'trans' THEN
        UPDATE trans SET trans_approval_count=mCOUNT WHERE trans_id=NEW.approval_from_id;
    END IF;   
END $$
DELIMITER ;

DELIMITER $$
DROP TRIGGER IF EXISTS `tr_approval_after_delete`$$ 
CREATE TRIGGER `tr_approval_after_delete` AFTER DELETE ON `approvals`
FOR EACH ROW 
BEGIN
    DECLARE mCOUNT INT(50) DEFAULT 0;
    SELECT COUNT(*) INTO mCOUNT FROM approvals WHERE approval_from_table=OLD.approval_from_table AND approval_from_id=OLD.approval_from_id;
    IF OLD.approval_from_table = 'orders' THEN
        UPDATE orders SET order_approval_count=mCOUNT WHERE order_id=OLD.approval_from_id;
    ELSEIF OLD.approval_from_table = 'trans' THEN
        UPDATE trans SET trans_approval_count=mCOUNT WHERE trans_id=OLD.approval_from_id;
    END IF;   
END $$
DELIMITER ;