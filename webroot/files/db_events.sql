DROP EVENT IF EXISTS `check_store_status`;

DELIMITER $$

SET GLOBAL event_scheduler = ON;

CREATE 
	EVENT `check_store_status` 
	ON SCHEDULE EVERY 1 HOUR
	DO BEGIN
		UPDATE sysvars SET status = IF(HOUR(NOW()) = (SELECT open_time FROM hours_of_operation WHERE weekday = DAYNAME(NOW())), 1, status) WHERE name = 'store_open';
		UPDATE sysvars SET status = IF(HOUR(NOW()) = (SELECT close_time FROM hours_of_operation WHERE weekday = DAYNAME(NOW())), 0, status) WHERE name = 'store_open';
	END; /*$$
