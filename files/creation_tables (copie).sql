CREATE TABLE Establishment (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    address VARCHAR(255),
    timeOpen VARCHAR(255) NOT NULL,
    timeClose VARCHAR(255) NOT NULL
);

CREATE TABLE Room (
    id INT PRIMARY KEY AUTO_INCREMENT,
    idEstablishment INT NOT NULL,
    name VARCHAR(255),
    idNumber VARCHAR(255) NOT NULL,
    timeOpen VARCHAR(255),
    timeClose VARCHAR(255),
    isBookable BOOLEAN NOT NULL,
    maxTime VARCHAR(255),
    FOREIGN KEY (idEstablishment) REFERENCES Establishment(id)
);

CREATE TABLE User (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    surname VARCHAR(255) NOT NULL,
    role VARCHAR(255) NOT NULL
);

CREATE TABLE Reservation (
    id INT PRIMARY KEY AUTO_INCREMENT,
    idRoom INT NOT NULL,
    timeStart VARCHAR(255) NOT NULL,
    timeEnd VARCHAR(255) NOT NULL,
    FOREIGN KEY (idRoom) REFERENCES Room(id)
);

CREATE TABLE Participant (
    id INT PRIMARY KEY AUTO_INCREMENT,
    idReservation INT NOT NULL,
    idUser INT NOT NULL,
    isInvitation BOOLEAN NOT NULL,
    invitationStatus VARCHAR(255) NOT NULL,
    FOREIGN KEY (idReservation) REFERENCES Reservation(id),
    FOREIGN KEY (idUser) REFERENCES Userr(id)
);

CREATE TABLE Coordinate (
    id int NOT NULL,
    x int NOT NULL,
    y int NOT NULL,
    line int NOT NULL,
    idRoom int,
    PRIMARY KEY (id),
    FOREIGN KEY (idRoom) REFERENCES Room(id)
);
