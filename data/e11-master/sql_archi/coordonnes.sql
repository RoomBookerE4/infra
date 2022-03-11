CREATE TABLE Coordinate (
    id int NOT NULL,
    x int NOT NULL,
    y int NOT NULL,
    line int NOT NULL,
    idRoom int,
    PRIMARY KEY (id),
    FOREIGN KEY (idRoom) REFERENCES Room(id)
);