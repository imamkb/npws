--
--	For database `npws`
--

--
--	Table structure for `accounts`
--
CREATE TABLE `accounts` ( 
	`name` varchar(50) NOT NULL,
	`pass` varchar(100) NOT NULL,
	`last_login` datetime DEFAULT NULL,
	`admin` tinyint(1) DEFAULT NULL
);

--
--	Data dump for `accounts`
--
INSERT INTO `accounts` (`name`, `pass`, `last_login`, `admin`) VALUES
('@admin','f429ec54f354b72bed77a5c0afedecb91f347f479a09f74f4107592764b56d1c',NULL,1);
#Account: name: @admin, pass(hashed): admin, last login: NULL, is_admin: 1

--
--	Table structure for `categories`
--
CREATE TABLE `categories` ( 
	`cat` varchar(50) NOT NULL
);

--
--	Data dump for `categories`
--
INSERT INTO `categories` (`cat`) VALUES 
('Entertainment'),
('Infotainment'),
('Local'),
('National'),
('Sports'),
('Technology');

--
-- Table structure for table `interested_in`
--

CREATE TABLE `interested_in` (
  `cat` varchar(15) NOT NULL,
  `fingerprint` varchar(15) NOT NULL,
  `count` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
--	Table structure for `news`
--
CREATE TABLE `news` ( 
	`artid` int(32) NOT NULL,
	`title` varchar(50) NOT NULL,
	`author` varchar(50) NOT NULL,
	`create_date` datetime NOT NULL,
	`description` text NOT NULL,
	`category` varchar(50) NOT NULL,
	`file_url` varchar(255) NOT NULL
);

--
--	Data dump for `news`
--
INSERT INTO `news` (`artid`, `title`, `author`, `create_date`, `description`, `category`, `file_url`) VALUES
(1540897071, 'Test%20post', '@admin', '2018-10-30 17:16:43', 'Welcome%20to%20the%20brand%20new%20NPWS.%20I%20am%20pretty%20sure%20that%20you%20will%20enjoy%20the%20simplicity%20of%20this%20system.', 'Local', 'uploads/@admin-0.39276800 1540897198.jpg');

--
-- Table structure for table `user_interest`
--
CREATE TABLE `user_interest` (
  `cati` varchar(15) NOT NULL,
  `session` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
--	Indices for all dumped tables
--
ALTER TABLE `accounts`
	ADD PRIMARY KEY (`name`);
ALTER TABLE `categories`
	ADD PRIMARY KEY (`cat`);
ALTER TABLE `interested_in`
	ADD PRIMARY KEY (`cat`,`fingerprint`);
	ADD CONSTRAINT `efk` FOREIGN KEY (`cat`) REFERENCES `categories` (`cat`) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE `news`
	ADD PRIMARY KEY (`artid`),
	ADD CONSTRAINT `author` FOREIGN KEY (`author`) REFERENCES `accounts` (`name`),
	ADD CONSTRAINT `category` FOREIGN KEY (`category`) REFERENCES `categories` (`cat`);
ALTER TABLE `user_interest`
	ADD UNIQUE KEY `cati` (`cati`),
	ADD UNIQUE KEY `session` (`session`);

DELIMITER $$
--
-- Procedures
--
CREATE PROCEDURE `insert_user_interest` (IN `cati` VARCHAR(15), IN `session` VARCHAR(15))  BEGIN
	delete from user_interest where 1;
	insert into user_interest(`cati`,`session`) values (cati, session);
END$$

--
-- Functions
--
CREATE FUNCTION `fingpt` () RETURNS INT(11) NO SQL
    DETERMINISTIC
return @fingpt$$

--
-- Triggers `user_interest`
--
CREATE TRIGGER `after_update_interested` AFTER INSERT ON `user_interest` FOR EACH ROW BEGIN
DECLARE t integer;
(select count into t from interested_in  where `fingerprint` = new.session AND `cat` = new.cati);
if (t > 0) then
	UPDATE `interested_in`
        SET count=(t+1)
    where `fingerprint`=new.session AND `cat`= new.cati;    
ELSE
	insert into interested_in(`fingerprint`,`cat`,`count`)
	VALUES (new.session, new.cati, 1);
END IF;
END
$$

DELIMITER ;

--
-- Insert the view to help in displaying the news articles
--
CREATE view `news_temp`
as
(select `artid`, `title`, `author`, `create_date`, `description`, `category`, `file_url`,`count`
	from news n, interested_in i
	where `i`.`fingerprint` = `fingpt`() and `n`.`category` = `i`.`cat`
	order by `i`.`count` desc);

-- then to get the all the news as needed
-- select * from ((select temp.`artid`,
--  		temp.`title`,
--  		temp.`author`,
--  		temp.`create_date`,
--  		temp.`description`,
--  		temp.`category`,
--  		temp.`file_url`,
--  		temp.`count`	-- the posts will be sorted based on interest
--  from (select @fingpt:="456") t , news_temp temp)
-- UNION
-- (select `artid`,
--  		`title`,
--  		`author`,
--  		`create_date`,
--  		`description`,
--  		`category`,
--  		`file_url`,
--  		@`count`:=0 `count`	-- the remaining posts with 0 interest are shown next
--  from news where artid not in
-- (select temp.artid from (select @fingpt:="456") t , news_temp temp))) temp1
-- order by temp1.`count` desc;