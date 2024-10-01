DELIMITER $$
DROP PROCEDURE IF EXISTS `sp_cronjob_booking_expired_day`$$
CREATE PROCEDURE `sp_cronjob_booking_expired_day`()
BEGIN
    /* Update Bookinng bulanan , Update order_item_expired_day = yg Booking bulanan dan sudah checkin*/
    UPDATE orders_items 
    SET order_item_expired_day = DATEDIFF(order_item_end_date,NOW())
    WHERE order_item_flag_checkin = 1 AND order_item_ref_price_sort = 1;

    UPDATE orders_items 
    SET order_item_expired_day = TIMESTAMPDIFF(HOUR, NOW(), order_item_end_date)
    WHERE order_item_flag_checkin = 1 
    AND order_item_ref_price_sort > 1;

    SET @QUERY := CONCAT('SELECT CONCAT(1) AS `status`, CONCAT("Success") AS `message`');
    PREPARE stmt FROM @QUERY;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;

END $$
DELIMITER ;