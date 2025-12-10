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

INSERT INTO roles (id, name, description) VALUES
(1, 'Superadministrador', 'Acceso total al sistema, gestión de usuarios y tickets'),
(2, 'Operador', 'Atiende tickets, gestiona su estado y agrega entradas'),
(3, 'Usuario', 'Crea tickets y revisa el estado de sus solicitudes');

INSERT INTO types (id, name, description) VALUES
(1, 'Petición', 'Solicitud de un nuevo servicio o recurso'),
(2, 'Incidente', 'Reporte de un fallo o problema en un servicio existente');

INSERT INTO status (id, name, description) VALUES
(1, 'No Asignado', 'Ticket creado y aún sin operador asignado'),
(2, 'Asignado', 'Ticket asignado a un operador'),
(3, 'En Proceso', 'El operador está trabajando activamente'),
(4, 'En Espera de Terceros', 'El operador espera información del usuario o un tercero'),
(5, 'Solucionado', 'El operador considera resuelto el ticket'),
(6, 'Cerrado', 'El usuario acepta la solución');

INSERT INTO transitions (fromStatusId, toStatusId) VALUES
(1, 2), -- No Asignado -> Asignado
(2, 3), -- Asignado -> En Proceso
(3, 4), -- En Proceso -> En Espera de Terceros
(4, 3), -- En Espera de Terceros -> En Proceso
(3, 5), -- En Proceso -> Solucionado
(5, 6), -- Solucionado -> Cerrado
(5, 2); -- Solucionado -> Asignado (rechazo de solución)

INSERT INTO users (fullname, username, passwordHash, roleId, isActive)
VALUES ('Super Administrador', 'admin', '$2y$10$0vC6LcSqXHPE7w0xXz1Nee3FPLZkQ0pKTOCfVQo6IpRy7lHzWWste', 1, 1);
