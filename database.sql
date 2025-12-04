-- Roles
CREATE TABLE roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE,
    description VARCHAR(255)
);

-- Usuarios
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

-- Tipos de ticket
CREATE TABLE types (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE,
    description VARCHAR(255)
);

-- Estados del ticket
CREATE TABLE status (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE,
    description VARCHAR(255)
);

-- Tabla principal de tickets
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

-- Entradas del historial de tickets
CREATE TABLE entries (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ticket_id INT NOT NULL,
    author_id INT NOT NULL,
    body TEXT NOT NULL,
    from_status_id INT DEFAULT NULL,
    to_status_id INT DEFAULT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (ticket_id) REFERENCES tickets(id),
    FOREIGN KEY (author_id) REFERENCES users(id),
    FOREIGN KEY (from_status_id) REFERENCES status(id),
    FOREIGN KEY (to_status_id) REFERENCES status(id)
);

-- Transiciones válidas de estado (opcional pero recomendado)
CREATE TABLE transitions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    from_status_id INT NOT NULL,
    to_status_id INT NOT NULL,
    UNIQUE KEY unique_transition (from_status_id, to_status_id),
    FOREIGN KEY (from_status_id) REFERENCES status(id),
    FOREIGN KEY (to_status_id) REFERENCES status(id)
);