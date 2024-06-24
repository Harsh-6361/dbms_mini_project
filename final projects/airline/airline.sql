-- Ensure no auto value on zero and transaction start
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- Create `admin` table
CREATE TABLE IF NOT EXISTS `admin` (
  `Admin_ID` varchar(20) NOT NULL,
  `Name` varchar(20) DEFAULT NULL,
  `Pswd` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`Admin_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default admin
INSERT INTO `admin` (`Admin_ID`, `Name`, `Pswd`) VALUES ('1', 'admin', 'admin');

-- Create `planes` table
CREATE TABLE IF NOT EXISTS `planes` (
  `Plane_Name` varchar(20) NOT NULL,
  `Class` varchar(10) DEFAULT NULL,
  `Seats` int(11) DEFAULT NULL,
  PRIMARY KEY (`Plane_Name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert sample data into `planes`
INSERT INTO `planes` (`Plane_Name`, `Class`, `Seats`) VALUES
('AirIndia', 'Business', 30),
('emirates', 'General', 10),
('indigo', 'Business', 3),
('Kingfisher', 'Business', 20),
('SpiceJet', 'Business', 5);

-- Create `aircraft` table (without inserting values)
CREATE TABLE IF NOT EXISTS `aircraft` (
  `Flight_ID` varchar(20) NOT NULL,
  `Dep_Time` datetime NOT NULL,
  `Arr_Time` datetime DEFAULT NULL,
  `Plane_Name` varchar(20) DEFAULT NULL,
  `Src` varchar(20) DEFAULT NULL,
  `Dstn` varchar(20) DEFAULT NULL,
  `Fare` decimal(10,2) DEFAULT NULL,
  `Dep_Date` date DEFAULT NULL,
  `Flight_Status` varchar(20) DEFAULT 'Scheduled',
  PRIMARY KEY (`Flight_ID`, `Dep_Time`),
  KEY `Plane_Name` (`Plane_Name`),
  CONSTRAINT `fk_aircraft_planes` FOREIGN KEY (`Plane_Name`) REFERENCES `planes` (`Plane_Name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create `users` table
CREATE TABLE IF NOT EXISTS `users` (
  `User_ID` int(11) NOT NULL AUTO_INCREMENT,
  `User_Name` varchar(20) NOT NULL,
  `Pswd` varchar(20) DEFAULT NULL,
  `Email` varchar(20) DEFAULT NULL,
  `Phone` varchar(13) DEFAULT NULL,
  `Age` int(11) DEFAULT NULL,
  PRIMARY KEY (`User_ID`),
  UNIQUE KEY `User_Name` (`User_Name`),
  UNIQUE KEY `Phone` (`Phone`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
ALTER TABLE users ADD Gender VARCHAR(10);



-- Create `bookings` table
CREATE TABLE IF NOT EXISTS `bookings` (
  `Booking_ID` int(11) NOT NULL AUTO_INCREMENT,
  `User_ID` int(11) NOT NULL,
  `Flight_ID` varchar(20) NOT NULL,
  `Dep_Time` datetime NOT NULL,
  `Name` varchar(100) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Phone` varchar(20) NOT NULL,
  `Age` int(11) NOT NULL,
  `Gender` enum('Male','Female','Other') NOT NULL,
  PRIMARY KEY (`Booking_ID`),
  KEY `User_ID` (`User_ID`),
  KEY `Flight_ID` (`Flight_ID`, `Dep_Time`),
  CONSTRAINT `fk_bookings_users` FOREIGN KEY (`User_ID`) REFERENCES `users` (`User_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_bookings_aircraft` FOREIGN KEY (`Flight_ID`, `Dep_Time`) REFERENCES `aircraft` (`Flight_ID`, `Dep_Time`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



-- Create `passenger` table
CREATE TABLE IF NOT EXISTS `passenger` (
  `P_ID` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(20) DEFAULT NULL,
  `Age` int(11) DEFAULT NULL,
  `Flight_ID` varchar(20) NOT NULL,
  `Dep_Time` datetime NOT NULL,
  `User_ID` int(11) DEFAULT NULL,
  PRIMARY KEY (`P_ID`),
  KEY `Flight_ID` (`Flight_ID`, `Dep_Time`),
  KEY `User_ID` (`User_ID`),
  CONSTRAINT `fk_passenger_aircraft` FOREIGN KEY (`Flight_ID`, `Dep_Time`) REFERENCES `aircraft` (`Flight_ID`, `Dep_Time`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_passenger_users` FOREIGN KEY (`User_ID`) REFERENCES `users` (`User_ID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Trigger to update `aircraft` table Flight_Status after insert in `passenger`
DELIMITER //

CREATE TRIGGER `passenger_insert_trigger` AFTER INSERT ON `passenger`
FOR EACH ROW
BEGIN
    UPDATE `aircraft` SET `Flight_Status` = 'Booked' WHERE `Flight_ID` = NEW.`Flight_ID` AND `Dep_Time` = NEW.`Dep_Time`;
END;

//

-- Trigger to update `aircraft` table Flight_Status after update in `passenger`
CREATE TRIGGER `passenger_update_trigger` AFTER UPDATE ON `passenger`
FOR EACH ROW
BEGIN
    UPDATE `aircraft` SET `Flight_Status` = 'Booked' WHERE `Flight_ID` = NEW.`Flight_ID` AND `Dep_Time` = NEW.`Dep_Time`;
END;

//

-- Trigger to update `aircraft` table Flight_Status after delete in `passenger`
CREATE TRIGGER `passenger_delete_trigger` AFTER DELETE ON `passenger`
FOR EACH ROW
BEGIN
    UPDATE `aircraft` SET `Flight_Status` = 'Scheduled' WHERE `Flight_ID` = OLD.`Flight_ID` AND `Dep_Time` = OLD.`Dep_Time`;
END;

//

DELIMITER ;



INSERT INTO `aircraft` (`Flight_ID`, `Dep_Time`, `Arr_Time`, `Plane_Name`, `Src`, `Dstn`, `Fare`, `Dep_Date`, `Flight_Status`) VALUES
('6000', '2024-06-24 19:00:00', '2024-06-24 21:00:00', 'AirIndia', 'Bangalore', 'Mumbai', 3000, '2024-06-24', 'Scheduled'),
('6001', '2024-06-24 20:00:00', '2024-06-24 22:00:00', 'AirIndia', 'Mumbai', 'Bangalore', 3000, '2024-06-24', 'Scheduled'),
('6002', '2024-06-24 20:00:00', '2024-06-25 00:00:00', 'AirIndia', 'Bangalore', 'Delhi', 4000, '2024-06-24', 'Scheduled'),
('6003', '2024-06-25 03:00:00', '2024-06-25 05:45:00', 'AirIndia', 'Delhi', 'Bangalore', 4000, '2024-06-25', 'Scheduled'),
('7000', '2024-06-24 19:00:00', '2024-06-24 21:30:00', 'emirates', 'Bangalore', 'Mumbai', 3250, '2024-06-24', 'Scheduled'),
('7001', '2024-06-24 20:30:00', '2024-06-24 21:00:00', 'emirates', 'Mumbai', 'Bangalore', 3250, '2024-06-24', 'Scheduled'),
('7002', '2024-06-24 19:00:00', '2024-06-24 22:15:00', 'emirates', 'Bangalore', 'Delhi', 4500, '2024-06-24', 'Scheduled'),
('7003', '2024-06-24 17:30:00', '2024-06-24 21:00:00', 'emirates', 'Delhi', 'Bangalore', 4500, '2024-06-24', 'Scheduled'),
('8000', '2024-06-24 20:00:00', '2024-06-24 22:30:00', 'indigo', 'Bangalore', 'Mumbai', 2750, '2024-06-24', 'Scheduled'),
('8001', '2024-06-24 22:00:00', '2024-06-25 01:50:00', 'indigo', 'Delhi', 'Bangalore', 3500, '2024-06-24', 'Scheduled'),
('9000', '2024-06-24 19:30:00', '2024-06-24 21:30:00', 'SpiceJet', 'Mumbai', 'Bangalore', 3100, '2024-06-24', 'Scheduled'),
('9001', '2024-06-24 21:00:00', '2024-06-24 23:45:00', 'SpiceJet', 'Bangalore', 'Delhi', 3600, '2024-06-24', 'Scheduled'),
('10000', '2024-06-24 20:30:00', '2024-06-24 22:45:00', 'Kingfisher', 'Mumbai', 'Bangalore', 3200, '2024-06-24', 'Scheduled'),
('10001', '2024-06-24 22:30:00', '2024-06-25 01:15:00', 'Kingfisher', 'Delhi', 'Bangalore', 4100, '2024-06-24', 'Scheduled'),
('11000', '2024-06-24 19:00:00', '2024-06-24 21:00:00', 'AirIndia', 'Bangalore', 'Mumbai', 3000, '2024-06-24', 'Scheduled'),
('11001', '2024-06-24 20:00:00', '2024-06-24 22:00:00', 'AirIndia', 'Mumbai', 'Bangalore', 3000, '2024-06-24', 'Scheduled'),
('12000', '2024-06-24 20:00:00', '2024-06-25 00:00:00', 'AirIndia', 'Bangalore', 'Delhi', 4000, '2024-06-24', 'Scheduled'),
('12001', '2024-06-25 03:00:00', '2024-06-25 05:45:00', 'AirIndia', 'Delhi', 'Bangalore', 4000, '2024-06-25', 'Scheduled'),
('13000', '2024-06-24 19:00:00', '2024-06-24 21:30:00', 'emirates', 'Bangalore', 'Mumbai', 3250, '2024-06-24', 'Scheduled'),
('13001', '2024-06-24 20:30:00', '2024-06-24 21:00:00', 'emirates', 'Mumbai', 'Bangalore', 3250, '2024-06-24', 'Scheduled'),
('14000', '2024-06-24 19:00:00', '2024-06-24 22:15:00', 'emirates', 'Bangalore', 'Delhi', 4500, '2024-06-24', 'Scheduled'),
('14001', '2024-06-24 17:30:00', '2024-06-24 21:00:00', 'emirates', 'Delhi', 'Bangalore', 4500, '2024-06-24', 'Scheduled'),
('15000', '2024-06-24 20:00:00', '2024-06-24 22:30:00', 'indigo', 'Bangalore', 'Mumbai', 2750, '2024-06-24', 'Scheduled'),
('15001', '2024-06-24 22:00:00', '2024-06-25 01:50:00', 'indigo', 'Delhi', 'Bangalore', 3500, '2024-06-24', 'Scheduled'),
('16000', '2024-06-24 19:30:00', '2024-06-24 21:30:00', 'SpiceJet', 'Mumbai', 'Bangalore', 3100, '2024-06-24', 'Scheduled'),
('16001', '2024-06-24 21:00:00', '2024-06-24 23:45:00', 'SpiceJet', 'Bangalore', 'Delhi', 3600, '2024-06-24', 'Scheduled');


COMMIT;

--Explanation:
--Triggers Overview:

--Insert Trigger (passenger_insert_trigger): Fires after an insert operation on the passenger table. It updates the corresponding row in the aircraft table, setting Flight_Status to 'Booked'.

--Update Trigger (passenger_update_trigger): Fires after an update operation on the passenger table. It updates the corresponding row in the aircraft table, setting Flight_Status to 'Booked'.

--Delete Trigger (passenger_delete_trigger): Fires after a delete operation on the passenger table. It updates the corresponding row in the aircraft table, setting Flight_Status back to 'Scheduled'.

--Execution:

--The DELIMITER command is used to change the delimiter from ; to // for creating triggers, as triggers contain multiple statements.
--After defining triggers, DELIMITER ; resets the delimiter back to ;.
--These triggers will silently update the Flight_Status in the aircraft table whenever a passenger booking is inserted, updated, or deleted, reflecting the current booking status of flights without generating any output or logging. Adjustments can be made based on specific requirements or additional functionality needed in your application.