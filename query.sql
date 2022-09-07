//To fix table users (remove after use)
ALTER TABLE users CHANGE Latidtude Latitude DECIMAL(10);

CREATE DATABASE lazada;

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
	Oid INT NOT NULL AUTO_INCREMENT,
	Pid INT NOT NULL,
    Cid INT NOT NULL,
    Pstatus VARCHAR (20),
    PRIMARY KEY (Oid),
    FOREIGN KEY (Pid) REFERENCES products(Pid),
    FOREIGN KEY (Cid) REFERENCES users(Uid)
)Engine Innodb;
