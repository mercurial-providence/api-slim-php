CREATE TABLE `ART` (
  `ID` int(10) NOT NULL,
  `TITLE` varchar(118) DEFAULT NULL,
  `DATE` varchar(52) DEFAULT NULL,
  `TECHNIQUE` varchar(226) DEFAULT NULL,
  `URL` varchar(70) DEFAULT NULL,
  `AUTHOR_ID` int(10) NOT NULL,
  `FORM_ID` int(10) NOT NULL,
  `LOCATION_ID` int(10) NOT NULL,
  `SCHOOL_ID` int(10) NOT NULL,
  `TIMEFRAME_ID` int(10) NOT NULL,
  `TYPE_ID` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Stand-in structure for view `ARTDATA`
-- (See below for the actual view)
--
CREATE TABLE `ARTDATA` (
`ID` int(10)
,`TITLE` varchar(118)
,`DATE` varchar(52)
,`TECHNIQUE` varchar(226)
,`URL` varchar(70)
,`AUTHOR_ID` int(10)
,`FORM_ID` int(10)
,`LOCATION_ID` int(10)
,`SCHOOL_ID` int(10)
,`TIMEFRAME_ID` int(10)
,`TYPE_ID` int(10)
,`AUTHOR` varchar(75)
,`BORN_DIED` varchar(75)
,`FORM` varchar(100)
,`LOCATION` varchar(100)
,`SCHOOL` varchar(100)
,`TIMEFRAME` varchar(100)
,`TYPE` varchar(100)
);

-- --------------------------------------------------------

--
-- Table structure for table `AUTHOR`
--

CREATE TABLE `AUTHOR` (
  `ID` int(10) NOT NULL,
  `AUTHOR` varchar(75) NOT NULL,
  `BORN_DIED` varchar(75) DEFAULT NULL,
  `FIMAGE` int(10) NOT NULL COMMENT 'Contains the Portrait of the Author'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `FORM`
--

CREATE TABLE `FORM` (
  `ID` int(10) NOT NULL,
  `FORM` varchar(100) DEFAULT NULL,
  `FIMAGE` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `LOCATION`
--

CREATE TABLE `LOCATION` (
  `ID` int(10) NOT NULL,
  `LOCATION` varchar(100) DEFAULT NULL,
  `FIMAGE` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `LOG_TABLE`
--

CREATE TABLE `LOG_TABLE` (
  `ID` int(10) NOT NULL,
  `CATEGORY` varchar(50) NOT NULL,
  `VALUE` varchar(5000) NOT NULL,
  `IP` varchar(50) DEFAULT NULL,
  `TIMESTAMP` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `SCHOOL`
--

CREATE TABLE `SCHOOL` (
  `ID` int(10) NOT NULL,
  `SCHOOL` varchar(100) DEFAULT NULL,
  `FIMAGE` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `TIMEFRAME`
--

CREATE TABLE `TIMEFRAME` (
  `ID` int(10) NOT NULL,
  `TIMEFRAME` varchar(100) DEFAULT NULL,
  `FIMAGE` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `TYPE`
--

CREATE TABLE `TYPE` (
  `ID` int(10) NOT NULL,
  `TYPE` varchar(100) DEFAULT NULL,
  `FIMAGE` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure for view `ARTDATA`
--
DROP TABLE IF EXISTS `ARTDATA`;

CREATE VIEW `ARTDATA`  AS  (select `t1`.`ID` AS `ID`,`t1`.`TITLE` AS `TITLE`,`t1`.`DATE` AS `DATE`,`t1`.`TECHNIQUE` AS `TECHNIQUE`,`t1`.`URL` AS `URL`,`t1`.`AUTHOR_ID` AS `AUTHOR_ID`,`t1`.`FORM_ID` AS `FORM_ID`,`t1`.`LOCATION_ID` AS `LOCATION_ID`,`t1`.`SCHOOL_ID` AS `SCHOOL_ID`,`t1`.`TIMEFRAME_ID` AS `TIMEFRAME_ID`,`t1`.`TYPE_ID` AS `TYPE_ID`,`t3`.`AUTHOR` AS `AUTHOR`,`t3`.`BORN_DIED` AS `BORN_DIED`,`t5`.`FORM` AS `FORM`,`t7`.`LOCATION` AS `LOCATION`,`t9`.`SCHOOL` AS `SCHOOL`,`t11`.`TIMEFRAME` AS `TIMEFRAME`,`t13`.`TYPE` AS `TYPE` from ((((((`ART` `t1` join `AUTHOR` `t3` on(`t3`.`ID` = `t1`.`AUTHOR_ID`)) join `FORM` `t5` on(`t1`.`FORM_ID` = `t5`.`ID`)) join `LOCATION` `t7` on(`t1`.`LOCATION_ID` = `t7`.`ID`)) join `SCHOOL` `t9` on(`t1`.`SCHOOL_ID` = `t9`.`ID`)) join `TIMEFRAME` `t11` on(`t1`.`TIMEFRAME_ID` = `t11`.`ID`)) join `TYPE` `t13` on(`t1`.`TYPE_ID` = `t13`.`ID`))) ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ART`
--
ALTER TABLE `ART`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `fk_author` (`AUTHOR_ID`),
  ADD KEY `fk_form` (`FORM_ID`),
  ADD KEY `fk_loc` (`LOCATION_ID`),
  ADD KEY `fk_school` (`SCHOOL_ID`),
  ADD KEY `fk_timeframe` (`TIMEFRAME_ID`),
  ADD KEY `fk_type` (`TYPE_ID`),
  ADD KEY `URL` (`URL`);
ALTER TABLE `ART` ADD FULLTEXT KEY `TITLE` (`TITLE`);
ALTER TABLE `ART` ADD FULLTEXT KEY `TECHNIQUE` (`TECHNIQUE`);

--
-- Indexes for table `AUTHOR`
--
ALTER TABLE `AUTHOR`
  ADD PRIMARY KEY (`ID`);
ALTER TABLE `AUTHOR` ADD FULLTEXT KEY `AUTHOR` (`AUTHOR`);

--
-- Indexes for table `FORM`
--
ALTER TABLE `FORM`
  ADD PRIMARY KEY (`ID`);
ALTER TABLE `FORM` ADD FULLTEXT KEY `FORM` (`FORM`);

--
-- Indexes for table `LOCATION`
--
ALTER TABLE `LOCATION`
  ADD PRIMARY KEY (`ID`);
ALTER TABLE `LOCATION` ADD FULLTEXT KEY `LOCATION` (`LOCATION`);
ALTER TABLE `LOCATION` ADD FULLTEXT KEY `LOCATION_2` (`LOCATION`);

--
-- Indexes for table `LOG_TABLE`
--
ALTER TABLE `LOG_TABLE`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `SCHOOL`
--
ALTER TABLE `SCHOOL`
  ADD PRIMARY KEY (`ID`);
ALTER TABLE `SCHOOL` ADD FULLTEXT KEY `SCHOOL` (`SCHOOL`);

--
-- Indexes for table `TIMEFRAME`
--
ALTER TABLE `TIMEFRAME`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `TYPE`
--
ALTER TABLE `TYPE`
  ADD PRIMARY KEY (`ID`);
ALTER TABLE `TYPE` ADD FULLTEXT KEY `TYPE` (`TYPE`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `LOG_TABLE`
--
ALTER TABLE `LOG_TABLE`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `ART`
--
ALTER TABLE `ART`
  ADD CONSTRAINT `fk_author` FOREIGN KEY (`AUTHOR_ID`) REFERENCES `AUTHOR` (`ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_form` FOREIGN KEY (`FORM_ID`) REFERENCES `FORM` (`ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_loc` FOREIGN KEY (`LOCATION_ID`) REFERENCES `LOCATION` (`ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_school` FOREIGN KEY (`SCHOOL_ID`) REFERENCES `SCHOOL` (`ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_timeframe` FOREIGN KEY (`TIMEFRAME_ID`) REFERENCES `TIMEFRAME` (`ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_type` FOREIGN KEY (`TYPE_ID`) REFERENCES `TYPE` (`ID`) ON DELETE CASCADE;

