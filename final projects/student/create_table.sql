--student	

CREATE TABLE `student` (
 `USN` varchar(50) NOT NULL,
 `NAME` varchar(50) NOT NULL,
 `SEM` int(10) NOT NULL,
 `SGPA` decimal(4,2) DEFAULT NULL,
 `DEPARTMENT` varchar(50) NOT NULL,
 `PHONE_NO` int(10) NOT NULL,
 UNIQUE KEY `USN` (`USN`),
 UNIQUE KEY `PHONE_NO` (`PHONE_NO`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci


--teacher	


CREATE TABLE `teacher` (
 `Username` varchar(50) NOT NULL,
 `password` varchar(50) NOT NULL,
 `FirstName` varchar(50) NOT NULL,
 `LastName` varchar(50) NOT NULL,
 `Email` varchar(50) NOT NULL,
 `Phone` int(10) NOT NULL,
 `Department` varchar(50) NOT NULL,
 UNIQUE KEY `Username` (`Username`),
 UNIQUE KEY `Email` (`Email`),
 UNIQUE KEY `Phone` (`Phone`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci