
-- USER TABLE
LOAD DATA LOCAL INFILE 'C:/xampp/htdocs/animalshelter-team8-kayla-code/data/User.csv'
INTO TABLE User
FIELDS TERMINATED BY ','
ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 ROWS
(UserID, password, userRole, firstName, lastName, email, phoneNum, address, dob);

-- EMPLOYEE
LOAD DATA LOCAL INFILE 'C:/xampp/htdocs/animalshelter-team8-kayla-code/data/Employee.csv'
INTO TABLE Employee
FIELDS TERMINATED BY ','
ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 ROWS
(E_userID);

-- MANAGER
LOAD DATA LOCAL INFILE 'C:/xampp/htdocs/animalshelter-team8-kayla-code/data/Manager.csv'
INTO TABLE Manager
FIELDS TERMINATED BY ','
ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 ROWS
(M_userID);

-- ADOPTER
LOAD DATA LOCAL INFILE 'C:/xampp/htdocs/animalshelter-team8-kayla-code/data/Adopter.csv'
INTO TABLE Adopter
FIELDS TERMINATED BY ','
ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 ROWS
(A_userID);

-- ADOPTION COORDINATOR
LOAD DATA LOCAL INFILE 'C:/xampp/htdocs/animalshelter-team8-kayla-code/data/AdoptionCoordinator.csv'
INTO TABLE AdoptionCoordinator
FIELDS TERMINATED BY ','
ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 ROWS
(E_userID);

-- ADOPTION REQUEST
LOAD DATA LOCAL INFILE 'C:/xampp/htdocs/animalshelter-team8-kayla-code/data/AdoptionRequest.csv'
INTO TABLE AdoptionRequest
FIELDS TERMINATED BY ','
ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 ROWS
(adoptionID, A_userID, animalID, requestStatus, E_userID, adoptionFee);

-- ANIMALS
LOAD DATA LOCAL INFILE 'C:/xampp/htdocs/animalshelter-team8-kayla-code/data/Animals.csv'
INTO TABLE Animals
FIELDS TERMINATED BY ','
ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 ROWS
(animalID, name, ward, breed, sex, healthStatus, yearOfBirth, arrivalDate, adoptionDate);

-- CARETAKER
LOAD DATA LOCAL INFILE 'C:/xampp/htdocs/animalshelter-team8-kayla-code/data/Caretaker.csv'
INTO TABLE Caretaker
FIELDS TERMINATED BY ','
ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 ROWS
(E_userID);

-- WARD
LOAD DATA LOCAL INFILE 'C:/xampp/htdocs/animalshelter-team8-kayla-code/data/Ward.csv'
INTO TABLE Ward
FIELDS TERMINATED BY ','
ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 ROWS
(wardName, managerID);

-- WORKIN
LOAD DATA LOCAL INFILE 'C:/xampp/htdocs/animalshelter-team8-kayla-code/data/WorkIn.csv'
INTO TABLE WorkIn
FIELDS TERMINATED BY ','
ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 ROWS
(WardName, E_userID);
