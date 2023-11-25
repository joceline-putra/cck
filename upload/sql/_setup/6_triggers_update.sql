DELIMITER $$
DROP TRIGGER IF EXISTS `tr_order_paid_after_insert`$$ 
CREATE TRIGGER `tr_order_paid_after_insert` AFTER INSERT ON `orders_paids`
FOR EACH ROW 
BEGIN
   DECLARE mCOUNT INT(50) DEFAULT 0;
   IF NEW.paid_order_id IS NOT NULL THEN
        SELECT COUNT(*) INTO mCOUNT FROM orders_paids WHERE paid_order_id=NEW.paid_order_id;
    
        UPDATE orders AS o
        LEFT OUTER JOIN (
            SELECT paid_order_id, IFNULL(SUM(paid_total),0) AS paid_total FROM orders_paids
            WHERE paid_order_id=NEW.paid_order_id
        ) AS p ON p.paid_order_id=o.order_id
        SET o.order_total_paid = p.paid_total,
        o.order_paid = (
        CASE 
            WHEN o.order_total <= p.paid_total THEN '1'
            WHEN o.order_total >= p.paid_total THEN '0'
            ELSE '0'
            END
        ), o.order_order_paid_count = mCOUNT
        WHERE o.order_id = NEW.paid_order_id;
   END IF;   
END $$
DELIMITER ;

DELIMITER $$
DROP TRIGGER IF EXISTS `tr_order_paid_after_update`$$ 
CREATE TRIGGER `tr_order_paid_after_update` AFTER UPDATE ON `orders_paids`
FOR EACH ROW 
BEGIN
   IF NEW.paid_order_id IS NOT NULL THEN
        UPDATE orders AS o
        LEFT OUTER JOIN (
            SELECT paid_order_id, IFNULL(SUM(paid_total),0) AS paid_total FROM orders_paids
            WHERE paid_order_id=NEW.paid_order_id
        ) AS p ON p.paid_order_id=o.order_id
        SET o.order_total_paid = p.paid_total,
        o.order_paid = (
        CASE 
            WHEN o.order_total <= p.paid_total THEN '1'
            WHEN o.order_total >= p.paid_total THEN '0'
            ELSE '0'
            END
        )
        WHERE o.order_id = NEW.paid_order_id;
   END IF;
END $$
DELIMITER ;

DELIMITER $$
DROP TRIGGER IF EXISTS `tr_order_paid_after_delete`$$ 
CREATE TRIGGER `tr_order_paid_after_delete` AFTER DELETE ON `orders_paids`
FOR EACH ROW 
BEGIN
    DECLARE mCOUNT INT(50) DEFAULT 0;
    IF OLD.paid_order_id IS NOT NULL THEN
        SELECT COUNT(*) INTO mCOUNT FROM orders_paids WHERE paid_order_id=OLD.paid_order_id;
    
        UPDATE orders AS o
        LEFT OUTER JOIN (
            SELECT paid_order_id, IFNULL(SUM(paid_total),0) AS paid_total FROM orders_paids
            WHERE paid_order_id=OLD.paid_order_id
        ) AS p ON p.paid_order_id=o.order_id
        SET o.order_total_paid = p.paid_total,
        o.order_paid = (
        CASE 
            WHEN o.order_total <= p.paid_total THEN '1'
            WHEN o.order_total >= p.paid_total THEN '0'
            ELSE '0'
            END
        ), o.order_order_paid_count = mCOUNT
        WHERE o.order_id = OLD.paid_order_id;
   END IF;   
END $$
DELIMITER ;