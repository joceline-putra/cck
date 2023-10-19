DELIMITER $$
DROP FUNCTION IF EXISTS `fn_capitalize`$$
CREATE FUNCTION `fn_capitalize`(input varchar(255)) RETURNS varchar(255) CHARSET latin1 DETERMINISTIC
  BEGIN
    DECLARE len INT;
    DECLARE i INT;
    SET len   = CHAR_LENGTH(input);
    SET input = LOWER(input);
    SET i = 0;

    WHILE (i < len) DO
      IF (MID(input,i,1) = ' ' OR i = 0) THEN
        IF (i < len) THEN
          SET input = CONCAT(
            LEFT(input,i),
            UPPER(MID(input,i + 1,1)),
            RIGHT(input,len - i - 1)
          );
        END IF;
      END IF;
      SET i = i + 1;
    END WHILE;
    RETURN input;
  END$$
DELIMITER ;

DELIMITER $$
DROP FUNCTION IF EXISTS `fn_create_random`$$
CREATE FUNCTION `fn_create_random`() RETURNS VARCHAR(128) CHARSET utf8
  BEGIN
    SET @random = CONCAT(
      SUBSTRING('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789', RAND(@seed:=ROUND(RAND(@lid)*4294967296))*36+1, 1),
      SUBSTRING('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789', RAND(@seed:=ROUND(RAND(@seed)*4294967296))*36+1, 1),
      SUBSTRING('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789', RAND(@seed:=ROUND(RAND(@seed)*4294967296))*36+1, 1),
      SUBSTRING('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789', RAND(@seed:=ROUND(RAND(@seed)*4294967296))*36+1, 1),
      SUBSTRING('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789', RAND(@seed:=ROUND(RAND(@seed)*4294967296))*36+1, 1),
      SUBSTRING('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789', RAND(@seed:=ROUND(RAND(@seed)*4294967296))*36+1, 1),
      SUBSTRING('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789', RAND(@seed:=ROUND(RAND(@seed)*4294967296))*36+1, 1),
      SUBSTRING('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789', RAND(@seed)*36+1, 1)
    );      
    RETURN @random ;
    END$$
DELIMITER ;

DELIMITER $$
DROP FUNCTION IF EXISTS `fn_create_session`$$
CREATE FUNCTION `fn_create_session`() RETURNS VARCHAR(128) CHARSET utf8
  BEGIN
    SET @chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
    SET @charLen = LENGTH(@chars);
    SET @random = '';
    WHILE LENGTH(@random) < 20
      DO
      SET @random = CONCAT(@random, SUBSTRING(@chars,CEILING(RAND() * @charLen),1));
    END WHILE;
    RETURN @random ;
  END$$
DELIMITER ;

DELIMITER $$
DROP FUNCTION IF EXISTS `fn_create_session_length`$$
CREATE FUNCTION `fn_create_session_length`(vLENGTH INT) RETURNS VARCHAR(128) CHARSET utf8
  BEGIN
    SET @chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
    SET @charLen = LENGTH(@chars);
    SET @random = '';
    WHILE LENGTH(@random) < vLENGTH
      DO
      SET @random = CONCAT(@random, SUBSTRING(@chars,CEILING(RAND() * @charLen),1));
    END WHILE;
    RETURN @random ;
  END$$
DELIMITER ;

DELIMITER $$
DROP FUNCTION IF EXISTS `fn_time_ago`$$
CREATE FUNCTION `fn_time_ago`(vDATE VARCHAR(255)) RETURNS VARCHAR(255) CHARSET latin1 DETERMINISTIC
  BEGIN
    DECLARE mDATE1 VARCHAR(255) DEFAULT "";
    DECLARE mDATE2 VARCHAR(255) DEFAULT "";     
    
    SELECT UNIX_TIMESTAMP(), UNIX_TIMESTAMP(vDATE) INTO mDATE1, mDATE2;
    SET @second = mDATE1 - mDATE2; 
    SET @minute = ROUND(@second/60);
    SET @hour = ROUND(@second/3600);
    SET @day = ROUND(@second/86400);
    SET @week = ROUND(@second/604800);
    SET @month = ROUND(@second/2419200);
    SET @year = ROUND(@second/29030400);
    
    IF @second <= 60 THEN
      SET @time_ago := CONCAT(@second,' detik yang lalu');
    ELSEIF @minute <= 60 THEN
      SET @time_ago := CONCAT(@minute,' menit yang lalu');
    ELSEIF @hour <= 24 THEN
      SET @time_ago := CONCAT(@hour,' jam yang lalu');      
    ELSEIF @day <= 7 THEN
      SET @time_ago := CONCAT(@day,' hari yang lalu');      
    ELSEIF @week <= 4 THEN  
      SET @time_ago := CONCAT(@week,' minggu yang lalu');     
    ELSEIF @month <= 12 THEN                
      SET @time_ago := CONCAT(@month,' bulan yang lalu');           
    ELSE
      SET @time_ago := CONCAT(@year,' tahun yang lalu');      
    END IF;
    SET @result := @time_ago;
    RETURN @result;
  END$$
DELIMITER ;

DELIMITER $$
DROP FUNCTION IF EXISTS `fn_decode`$$
CREATE FUNCTION `fn_decode`(vTEXT VARCHAR(255)) RETURNS VARCHAR(255) CHARSET utf8mb4 DETERMINISTIC
  BEGIN
    DECLARE mRESULT VARCHAR(255);
    DECLARE mSALT VARCHAR(255);
    SET mSALT = 'm45t3rj03';
    SELECT DECODE(vTEXT,mSALT) INTO mRESULT;
    RETURN mRESULT;
  END$$
DELIMITER ;

DELIMITER $$
DROP FUNCTION IF EXISTS `fn_decrypt`$$
CREATE FUNCTION `fn_decrypt`(vTEXT VARCHAR(255)) RETURNS VARCHAR(255) CHARSET utf8mb4 DETERMINISTIC
  BEGIN
    DECLARE mRESULT VARCHAR(255);
    DECLARE mSALT VARCHAR(255);
    SET mSALT = 'm45t3rj03';
    SELECT AES_DECRYPT(vTEXT,mSALT) INTO mRESULT;
    RETURN mRESULT;
  END$$
DELIMITER ;

DELIMITER $$
DROP FUNCTION IF EXISTS `fn_encode`$$
CREATE FUNCTION `fn_encode`(vTEXT VARCHAR(255)) RETURNS VARCHAR(255) CHARSET utf8mb4 DETERMINISTIC
  BEGIN
    DECLARE mRESULT VARCHAR(255);
    DECLARE mSALT VARCHAR(255);
    SET mSALT = 'm45t3rj03';
    SELECT ENCODE(vTEXT,mSALT) INTO mRESULT;
    RETURN mRESULT;
  END$$
DELIMITER ;

DELIMITER $$
DROP FUNCTION IF EXISTS `fn_encrypt`$$
CREATE FUNCTION `fn_encrypt`(vTEXT VARCHAR(255)) RETURNS VARCHAR(255) CHARSET utf8mb4 DETERMINISTIC
  BEGIN
    DECLARE mRESULT VARCHAR(255);
    DECLARE mSALT VARCHAR(255);
    SET mSALT = 'm45t3rj03';
    SELECT AES_ENCRYPT(vTEXT,mSALT) INTO mRESULT;
    RETURN mRESULT;
  END$$
DELIMITER ;

DELIMITER $$
DROP FUNCTION IF EXISTS `fn_create_number`$$
CREATE FUNCTION `fn_create_number`(
  vTABLE VARCHAR(255),
  vTYPE INT(5),
  vDATE DATE
) RETURNS VARCHAR(255) CHARSET latin1 DETERMINISTIC
  BEGIN
    DECLARE mNUMBER VARCHAR(255);
    DECLARE mLAST_NUMBER INT(255);
    DECLARE mRESULT VARCHAR(255);
    
      /* Search Table */
      IF vTABLE = 'trans' THEN
          SELECT IFNULL(MAX(RIGHT(`trans_number`,5)),0) INTO mLAST_NUMBER
          FROM trans
          WHERE DATE_FORMAT(trans_date_created, '%Y%m')=DATE_FORMAT(vDATE,'%Y%m')
          AND trans_type=vTYPE;
      END IF; 

      /* Generate Number */
    IF mLAST_NUMBER = 0 THEN
      SET @mNUMBER := '00001';
    ELSE
      SET mNUMBER := mLAST_NUMBER + 1; 	
      SELECT LPAD(mNUMBER,5,0) INTO @mNUMBER;
    END IF;
    SET mRESULT = CONCAT('INV-',DATE_FORMAT(vDATE, '%y%m'),'-',@mNUMBER); -- INV-2206-00001
    RETURN mRESULT;
  END$$
DELIMITER ;

DELIMITER $$
DROP FUNCTION IF EXISTS `fn_create_url`$$
CREATE FUNCTION `fn_create_url`() RETURNS VARCHAR(128) CHARSET utf8
	BEGIN
		SET @chars = 'JTUVWKLMNOPQRSXYZ1qrv23stu40aabcdGHIefghijkl56789mnpwABCDEFxyz';
		SET @charLen = LENGTH(@chars);
		SET @random = '';
		WHILE LENGTH(@random) < 20
			DO
			SET @random = CONCAT(@random, SUBSTRING(@chars,CEILING(RAND() * @charLen),1));
		END WHILE;
		RETURN @random ;
	END$$
DELIMITER ;