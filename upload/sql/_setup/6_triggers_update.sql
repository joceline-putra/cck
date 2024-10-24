
DELIMITER $$
DROP TRIGGER IF EXISTS `tr_order_paid_after_update`$$ 
CREATE TRIGGER `tr_order_paid_after_update` AFTER UPDATE ON `orders_paids`
FOR EACH ROW 
BEGIN
    IF NEW.paid_order_id IS NOT NULL THEN
        CALL sp_update_order_paid(NEW.paid_order_id);
    END IF;
END $$
DELIMITER ;

DELIMITER $$
DROP TRIGGER IF EXISTS `tr_order_paid_after_delete`$$ 
CREATE TRIGGER `tr_order_paid_after_delete` AFTER DELETE ON `orders_paids`
FOR EACH ROW 
BEGIN
    IF OLD.paid_order_id IS NOT NULL THEN
        CALL sp_update_order_paid(OLD.paid_order_id);
    END IF;
END $$
DELIMITER ;

DELIMITER $$
DROP TRIGGER IF EXISTS `tr_ref_price_before_insert`$$ 
CREATE TRIGGER `tr_ref_price_before_insert` BEFORE INSERT ON `references_prices`
FOR EACH ROW 
BEGIN
   IF NEW.price_sort = 1 THEN SET NEW.price_name ='Bulanan';
   ELSEIF NEW.price_sort = 2 THEN SET NEW.price_name ='Harian';
   ELSEIF NEW.price_sort = 3 THEN SET NEW.price_name ='Midnight';
   ELSEIF NEW.price_sort = 4 THEN SET NEW.price_name ='4 Jam';
   ELSEIF NEW.price_sort = 5 THEN SET NEW.price_name ='2 Jam';
   ELSEIF NEW.price_sort = 6 THEN SET NEW.price_name ='3 Jam';   
   ELSE SET NEW.price_name = '';
   END IF;            
END $$
DELIMITER ;

DELIMITER $$
DROP PROCEDURE IF EXISTS `sp_update_order_paid`$$
CREATE PROCEDURE `sp_update_order_paid`(
  IN vORDER_ID INT(50)
)
BEGIN
    UPDATE orders AS o
    LEFT OUTER JOIN (
        SELECT paid_order_id, IFNULL(SUM(paid_total),0) AS total_paid FROM orders_paids WHERE paid_order_id=vORDER_ID GROUP BY paid_order_id
    ) AS p ON p.paid_order_id=o.order_id 
    SET o.order_total_paid = IFNULL(p.total_paid,0), 
    o.order_paid = (
        CASE
            WHEN p.total_paid >= o.order_total THEN '1'
            WHEN p.total_paid <= o.order_total THEN '0'
            ELSE '4'
        END        
    )         
    WHERE order_id=vORDER_ID;
END $$
DELIMITER ;