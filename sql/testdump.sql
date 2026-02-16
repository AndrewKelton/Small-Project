-- DO NOT RUN IN MAIN DATABASE

DROP TABLE IF EXISTS Contacts;
DROP TABLE IF EXISTS Users;

CREATE TABLE `SMALLPROJECT`.`Users`
(
    
    `ID` INT NOT NULL AUTO_INCREMENT,
    `Login` VARCHAR(50) NOT NULL,
    `Password` VARCHAR(256) NOT NULL,
    `Email` VARCHAR(50) NOT NULL DEFAULT '',
    `FirstName` VARCHAR(50) NOT NULL DEFAULT '',
    `LastName` VARCHAR(50) NOT NULL DEFAULT '',
    
    PRIMARY KEY (`ID`),
    UNIQUE(`Login`)

) ENGINE = InnoDB;

CREATE TABLE `SMALLPROJECT`.`Contacts`
(

    `ID` INT NOT NULL AUTO_INCREMENT,
    `UserID` INT NOT NULL,
    `FirstName` VARCHAR(50) NOT NULL DEFAULT '',
    `LastName` VARCHAR(50) NOT NULL DEFAULT '',
    `Email` VARCHAR(50) NOT NULL DEFAULT '',
    `Phone` VARCHAR(50) NOT NULL DEFAULT '',
    `DateCreated` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (`ID`),

    CONSTRAINT fk_contacts_users
        FOREIGN KEY(`UserID`)
        REFERENCES `Users`(`ID`)
        ON DELETE CASCADE

) ENGINE = InnoDB;

-- Insert `Users` test entries
INSERT INTO Users (Login, Password, Email, FirstName, LastName) VALUES
('jdoe', 'passwordhash1', 'email1@gmail.com', 'John', 'Doe'),
('asmith', 'passwordhash2', 'email2@gmail.com', 'Alice', 'Smith'),
('bwayne', 'passwordhash3', 'email3@gmail.com', 'Bruce', 'Wayne'),
('ckent', 'passwordhash4', 'email4@gmail.com', 'Clark', 'Kent'),
('dprince', 'passwordhash5', 'email5@gmail.com', 'Diana', 'Prince');

-- Insert `Contacts` test entries
INSERT INTO Contacts (UserID, FirstName, LastName, Email, Phone) VALUES
(1, 'Peter', 'Parker', 'peter.parker@example.com', '555-0101'),
(1, 'Mary', 'Jane', 'mary.jane@example.com', '555-0102'),
(2, 'Tony', 'Stark', 'tony.stark@example.com', '555-0201'),
(2, 'Natasha', 'Romanoff', 'natasha.romanoff@example.com', '555-0202'),
(3, 'Selina', 'Kyle', 'selina.kyle@example.com', '555-0301'),
(3, 'Alfred', 'Pennyworth', 'alfred.pennyworth@example.com', '555-0302'),
(4, 'Lois', 'Lane', 'lois.lane@example.com', '555-0401'),
(5, 'Steve', 'Rogers', 'steve.rogers@example.com', '555-0501');
