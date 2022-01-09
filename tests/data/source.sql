DROP TABLE IF EXISTS test_common;
DROP TABLE IF EXISTS test_composite_keys;
DROP TABLE IF EXISTS test_no_keys;
DROP TABLE IF EXISTS test_not_applicable_key;

CREATE TABLE IF NOT EXISTS `test_common` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `title` varchar(255) NOT NULL,
    `data`  NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;
