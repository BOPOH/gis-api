CREATE TABLE IF NOT EXISTS `t_building` (
    `f_id` INT UNSIGNED AUTO_INCREMENT,
    `f_address` VARCHAR(255) NOT NULL DEFAULT '',
    `f_latitude` FLOAT NOT NULL DEFAULT 0,
    `f_longitude` FLOAT NOT NULL DEFAULT 0,
    PRIMARY KEY (`f_id`)
) ENGINE=InnoDB DEFAULT CHARSET 'utf8';

CREATE TABLE IF NOT EXISTS `t_rubric` (
    `f_id` INT UNSIGNED AUTO_INCREMENT,
    `f_parent_id` INT UNSIGNED NULL DEFAULT NULL,
    `f_name` VARCHAR(255) NOT NULL DEFAULT '',
    PRIMARY KEY (`f_id`)
) ENGINE=InnoDB DEFAULT CHARSET 'utf8';

CREATE TABLE IF NOT EXISTS `t_rubric_global` (
    `f_parent_id` INT UNSIGNED NOT NULL,
    `f_rubric_id` INT UNSIGNED NOT NULL,
    PRIMARY KEY (`f_parent_id`, `f_rubric_id`)
) ENGINE=InnoDB DEFAULT CHARSET 'utf8';

CREATE TABLE IF NOT EXISTS `t_company` (
    `f_id` INT UNSIGNED AUTO_INCREMENT,
    `f_name` VARCHAR(255) NOT NULL DEFAULT '',
    PRIMARY KEY (`f_id`)
) ENGINE=InnoDB DEFAULT CHARSET 'utf8';

CREATE TABLE IF NOT EXISTS `t_company_address` (
    `f_company_id` INT UNSIGNED,
    `f_building_id` INT UNSIGNED,
    PRIMARY KEY (`f_company_id`, `f_building_id`)
) ENGINE=InnoDB DEFAULT CHARSET 'utf8';

CREATE TABLE IF NOT EXISTS `t_company_rubric` (
    `f_company_id` INT UNSIGNED,
    `f_rubric_id` INT UNSIGNED,
    PRIMARY KEY (`f_company_id`, `f_rubric_id`)
) ENGINE=InnoDB DEFAULT CHARSET 'utf8';

CREATE TABLE IF NOT EXISTS `t_company_phones` (
    `f_company_id` INT UNSIGNED,
    `f_phone` VARCHAR(20) NOT NULL,
    PRIMARY KEY (`f_company_id`, `f_phone`)
) ENGINE=InnoDB DEFAULT CHARSET 'utf8';
