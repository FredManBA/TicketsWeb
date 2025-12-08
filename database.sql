CREATE TABLE roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE,
    description VARCHAR(255)
);

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fullname VARCHAR(150) NOT NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    passwordHash VARCHAR(255) NOT NULL,
    roleId INT NOT NULL,
    isActive INT NOT NULL DEFAULT 1,
    createdAt DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updatedAt DATETIME DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (roleId) REFERENCES roles(id)
);

CREATE TABLE types (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE,
    description VARCHAR(255)
);

CREATE TABLE status (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE,
    description VARCHAR(255)
);

CREATE TABLE tickets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    summary TEXT NOT NULL,
    typeId INT NOT NULL,
    statusId INT NOT NULL,
    createdBy INT NOT NULL,
    assignedTo INT DEFAULT NULL,
    createdAt DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updatedAt DATETIME DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (typeId) REFERENCES types(id),
    FOREIGN KEY (statusId) REFERENCES status(id),
    FOREIGN KEY (createdBy) REFERENCES users(id),
    FOREIGN KEY (assignedTo) REFERENCES users(id)
);

CREATE TABLE entries (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ticketId INT NOT NULL,
    authorId INT NOT NULL,
    body TEXT NOT NULL,
    fromStatusId INT DEFAULT NULL,
    toStatusId INT DEFAULT NULL,
    createdAt DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (ticketId) REFERENCES tickets(id),
    FOREIGN KEY (authorId) REFERENCES users(id),
    FOREIGN KEY (fromStatusId) REFERENCES status(id),
    FOREIGN KEY (toStatusId) REFERENCES status(id)
);

CREATE TABLE transitions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fromStatusId INT NOT NULL,
    toStatusId INT NOT NULL,
    UNIQUE KEY unique_transition (fromStatusId, toStatusId),
    FOREIGN KEY (fromStatusId) REFERENCES status(id),
    FOREIGN KEY (toStatusId) REFERENCES status(id)
);
