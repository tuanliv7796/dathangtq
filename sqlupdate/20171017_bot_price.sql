ALTER TABLE `booking_tours` ADD `bot_price` DOUBLE NOT NULL DEFAULT '0' AFTER `bot_transaction_id`, ADD `bot_price_child` DOUBLE NOT NULL DEFAULT '0' AFTER `bot_price`;
ALTER TABLE `booking_tours` CHANGE `bot_time_payment` `bot_time_payment` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '';