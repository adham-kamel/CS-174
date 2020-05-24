CREATE DATABASE filecontent;
USE filecontent;
GRANT ALL ON filecontent.* TO 'adham'@'localhost' IDENTIFIED BY 'adham';
CREATE TABLE contents(
    Name VARCHAR(128) NOT NULL,
    Content VARCHAR(16000) NOT NULL, 
    id INT NOT NULL AUTO_INCREMENT,
    PRIMARY KEY (id)
);