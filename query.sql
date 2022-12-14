CREATE DATABASE lazada;

USE lazada;

CREATE TABLE users (
	Uid INT NOT NULL AUTO_INCREMENT,
	UName VARCHAR(50) NOT NULL,
	Adress VARCHAR(200),
	Longitude DECIMAL,
   	Latitude DECIMAL,
   	Username VARCHAR(50) NOT NULL,
   	Pwd VARCHAR(255) NOT NULL,
	URole INT NOT NULL,
	PRIMARY KEY (Uid),
	UNIQUE (Username)
) ENGINE InnoDB;

CREATE TABLE products (
	Pid INT NOT NULL AUTO_INCREMENT,
	Pname VARCHAR(50) NOT NULL,
    Price DECIMAL NOT NULL,
    Vid  INT NOT NULL,
    PRIMARY KEY (Pid),
    FOREIGN KEY (Vid) REFERENCES users(Uid)
) ENGINE = InnoDB;

CREATE TABLE orders (
	Pid INT NOT NULL,
	Cid INT NOT NULL,
	Hid INT,
	Pstatus VARCHAR (20),
	FOREIGN KEY (Pid) REFERENCES products(Pid),
	FOREIGN KEY (Cid) REFERENCES users(Uid),
	FOREIGN KEY (Hid) REFERENCES hubs(Hid)
)Engine Innodb;

CREATE TABLE hubs (
	Hid INT NOT NULL,
    Longitude DECIMAL(10),
    Latitude DECIMAL(10),
    PRIMARY KEY (Hid)
) ENGINE InnoDB;
INSERT INTO hubs VALUES (1, 2000, 3000);
INSERT INTO hubs VALUES (2, 5000, 7000);
INSERT INTO hubs VALUES (3, 8000, 9000);
