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
--	Table structure for `news`
--
CREATE TABLE `news` ( 
	`artid` int(32) NOT NULL,
	`title` varchar(50) NOT NULL,
	`author` varchar(50) NOT NULL,
	`create_date` datetime NOT NULL,
	`description` varchar(500) NOT NULL,
	`category` varchar(50) NOT NULL,
	`file_url` varchar(500) NOT NULL
);

--
--	Data dump for `news`
--
INSERT INTO `news` (`artid`, `title`, `author`, `create_date`, `description`, `category`, `file_url`) VALUES
(1540897071, 'Test%20post', '@admin', '2018-10-30 17:16:43', 'Welcome%20to%20the%20brand%20new%20NPWS.%20I%20am%20pretty%20sure%20that%20you%20will%20enjoy%20the%20simplicity%20of%20this%20system.', 'Local', 'uploads/@admin-0.39276800 1540897198.jpg');

--
--	Indices for all dumped tables
--
ALTER TABLE `accounts`
	ADD PRIMARY KEY (`name`);
ALTER TABLE `categories`
	ADD PRIMARY KEY (`cat`);
ALTER TABLE `news`
	ADD PRIMARY KEY (`artid`),
	ADD FOREIGN KEY `author` (`author`) REFERENCES `accounts` (`name`),
	ADD FOREIGN KEY `category` (`category`) REFERENCES `categories` (`cat`);