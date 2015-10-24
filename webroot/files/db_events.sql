DROP EVENT IF EXISTS `check_store_status`;
DROP EVENT IF EXISTS `check_delivery_status`;

DELIMITER $$

CREATE 
	EVENT `check_store_status` 
	ON SCHEDULE EVERY 1 HOUR
	DO BEGIN
		UPDATE sysvars SET status = IF(HOUR(NOW()) = (SELECT open_time FROM hours_of_operation WHERE weekday = DAYNAME(NOW())), 1, status) WHERE name in ('store_open', 'online_ordering', 'delivery_availalbe');
		UPDATE sysvars SET status = IF(HOUR(NOW()) = (SELECT close_time FROM hours_of_operation WHERE weekday = DAYNAME(NOW())), 0, status) WHERE name in ('store_open', 'online_ordering');
		
	END; 
CREATE
	EVENT `check_delivery_status`
	ON SCHEDULE EVERY 30 MINUTE
	DO BEGIN
		UPDATE sysvars SET status = IF(HOUR(NOW()) = (SELECT close_time FROM hours_of_operation WHERE weekday = DAYNAME(NOW()))-1 and MINUTE(NOW) >= 30, 0, status) WHERE name = 'delivery_available';

	END;/*$$

