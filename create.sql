DROP TABLE IF EXISTS WorkIn, AdoptionRequest, Animals, Ward,
Adopter, Manager, Caretaker, AdoptionCoordinator, Employee, User;

CREATE TABLE User (
    UserID INT PRIMARY KEY NOT NULL,
    password VARCHAR(50) NOT NULL,
    userRole ENUM('Employee', 'Adopter') NOT NULL DEFAULT 'Adopter',
    firstName VARCHAR(50) NOT NULL,
    lastName VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phoneNum VARCHAR(15) NOT NULL,
    address VARCHAR(100) NOT NULL,
    dob DATE NOT NULL
);

CREATE TABLE Employee(
    E_userID INT PRIMARY KEY,
    FOREIGN KEY (E_userID) REFERENCES User(UserID)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

CREATE TABLE AdoptionCoordinator(
    E_userID INT PRIMARY KEY,
    FOREIGN KEY (E_userID) REFERENCES Employee(E_userID)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

CREATE TABLE Caretaker(
    E_userID INT PRIMARY KEY,
    FOREIGN KEY (E_userID) REFERENCES Employee(E_userID)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

CREATE TABLE Manager(
    M_userID INT PRIMARY KEY,
    FOREIGN KEY (M_userID) REFERENCES Employee(E_userID)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

CREATE TABLE Adopter(
    A_userID INT PRIMARY KEY,
    FOREIGN KEY (A_userID) REFERENCES User(UserID)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

CREATE TABLE Ward(
    wardName ENUM('Dog', 'Cat', 'Rabbit', 'Bird', 'Rodent') PRIMARY KEY,
    managerID INT NOT NULL,
    FOREIGN KEY (managerID) REFERENCES Manager(M_userID)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

CREATE TABLE Animals(
    animalID INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    ward ENUM('Dog', 'Cat', 'Rabbit', 'Bird', 'Rodent') DEFAULT NULL,
    breed VARCHAR(50),
    sex ENUM('Female', 'Male', 'Intersex') DEFAULT NULL,
    healthStatus VARCHAR(50),
    yearOfBirth CHAR(4),
    arrivalDate DATE,
    adoptionDate DATE,
    FOREIGN KEY (ward) REFERENCES Ward(wardName)
        ON DELETE SET NULL
        ON UPDATE CASCADE
);

CREATE TABLE AdoptionRequest(
    adoptionID INT AUTO_INCREMENT PRIMARY KEY,
    A_userID INT,
    animalID INT,
	requestDate DATE NOT NULL,
    requestStatus ENUM('pending', 'approved', 'rejected') NOT NULL DEFAULT 'pending',
    E_userID INT,
    adoptionFee INT,
    FOREIGN KEY (E_userID) REFERENCES Employee(E_userID)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

CREATE TABLE WorkIn(
    wardName ENUM('Dog', 'Cat', 'Rabbit', 'Bird', 'Rodent') NOT NULL,
    E_userID INT NOT NULL,
    PRIMARY KEY (wardName, E_userID),
    FOREIGN KEY (wardName) REFERENCES Ward(wardName),
    FOREIGN KEY (E_userID) REFERENCES Employee(E_userID)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);
