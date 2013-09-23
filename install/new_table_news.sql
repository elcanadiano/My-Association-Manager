USE cityofteufort;

CREATE TABLE `news` (
 `id` integer NOT NULL AUTO_INCREMENT,
 `aid` integer NOT NULL,
 `title` varchar(63) NOT NULL,
 `date` timestamp NOT NULL,
 `message` text NOT NULL,
 `parsed` text NOT NULL,
 PRIMARY KEY (`id`),
 FOREIGN KEY (`aid`) REFERENCES admin(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;
