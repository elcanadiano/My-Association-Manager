USE cityofteufort;

CREATE TABLE `admin` (
 `id` integer NOT NULL AUTO_INCREMENT,
 `username` varchar(64) NOT NULL UNIQUE,
 `password` varchar(100) NOT NULL,
 PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

/* sha256(sha256('pikachu')) = '6c526ca48424d3474dd3756013bf383e27514ffd92f6a9ddd08cd147d421c480' */
insert into admin (username, password) values ('awpoon', '6c526ca48424d3474dd3756013bf383e27514ffd92f6a9ddd08cd147d421c480');

