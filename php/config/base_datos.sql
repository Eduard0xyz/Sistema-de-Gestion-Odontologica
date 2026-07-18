CREATE DATABASE IF NOT EXISTS clinica_dental CHARACTER SET utf8mb4;
USE clinica_dental;

CREATE TABLE usuario (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    usuario VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    rol ENUM('Recepcionista','Odontologo','Administrador') NOT NULL
);
select*from usuario;
CREATE TABLE paciente (
    id_paciente INT AUTO_INCREMENT PRIMARY KEY,
    dni VARCHAR(8) NOT NULL UNIQUE,
    nombres VARCHAR(80) NOT NULL,
    apellidos VARCHAR(80) NOT NULL,
    telefono VARCHAR(20),
    correo VARCHAR(100),
    direccion VARCHAR(150),
    fecha_nacimiento DATE
);
select *from paciente;
CREATE TABLE odontologo (
    id_odontologo INT AUTO_INCREMENT PRIMARY KEY,
    nombres VARCHAR(80) NOT NULL,
    apellidos VARCHAR(80) NOT NULL,
    especialidad VARCHAR(80),
    telefono VARCHAR(20),
    correo VARCHAR(100)
);

CREATE TABLE cita (
    id_cita INT AUTO_INCREMENT PRIMARY KEY,
    id_paciente INT NOT NULL,
    id_odontologo INT NOT NULL,
    fecha DATE NOT NULL,
    hora TIME NOT NULL,
    motivo VARCHAR(200),
    estado ENUM('Programada','Reprogramada','Cancelada','Atendida') DEFAULT 'Programada',
    FOREIGN KEY (id_paciente) REFERENCES paciente(id_paciente),
    FOREIGN KEY (id_odontologo) REFERENCES odontologo(id_odontologo)
);

CREATE TABLE historial_clinico (
    id_historial INT AUTO_INCREMENT PRIMARY KEY,
    id_paciente INT NOT NULL,
    id_odontologo INT NOT NULL,
    fecha_atencion DATE NOT NULL,
    diagnostico TEXT,
    observaciones TEXT,
    tratamiento VARCHAR(200),
    FOREIGN KEY (id_paciente) REFERENCES paciente(id_paciente),
    FOREIGN KEY (id_odontologo) REFERENCES odontologo(id_odontologo)
);

CREATE TABLE recordatorio (
    id_recordatorio INT AUTO_INCREMENT PRIMARY KEY,
    id_cita INT NOT NULL,
    medio ENUM('Correo','SMS') NOT NULL,
    fecha_envio DATETIME NOT NULL,
    estado ENUM('Pendiente','Enviado','Fallido') DEFAULT 'Pendiente',
    FOREIGN KEY (id_cita) REFERENCES cita(id_cita)
);

-- Datos de ejemplo para pruebas
INSERT INTO paciente (dni, nombres, apellidos, telefono, correo, direccion, fecha_nacimiento) VALUES
('71234567', 'Ana', 'Rojas P�rez', '987654321', 'ana.rojas@correo.com', 'Jr. Los Olivos 123', '1990-05-15'),
('76543210', 'Luis', 'Torres Vega', '912345678', 'luis.torres@correo.com', 'Av. Central 456', '1985-11-20'),
('70123456', 'Mar�a', 'Garc�a Luna', '998877665', 'maria.garcia@correo.com', 'Calle Las Flores 789', '1995-03-10'),
('72345678', 'Pedro', 'Huam�n R�os', '955443322', 'pedro.huaman@correo.com', 'Pasaje El Sol 321', '1988-07-22');

INSERT INTO odontologo (nombres, apellidos, especialidad, telefono, correo) VALUES
('Carlos', 'Mendoza Ruiz', 'Ortodoncia', '945612378', 'c.mendoza@clinicadental.com'),
('Rosa', 'Fern�ndez Luna', 'Odontolog�a general', '956123478', 'r.fernandez@clinicadental.com'),
('Miguel', 'S�nchez Paredes', 'Endodoncia', '967891234', 'm.sanchez@clinicadental.com');

INSERT INTO cita (id_paciente, id_odontologo, fecha, hora, motivo, estado) VALUES
(1, 1, CURDATE(), '10:00:00', 'Control de ortodoncia', 'Programada'),
(2, 2, CURDATE(), '15:30:00', 'Limpieza dental', 'Programada'),
(1, 2, DATE_ADD(CURDATE(), INTERVAL -5 DAY), '09:00:00', 'Dolor dental', 'Atendida'),
(3, 1, DATE_ADD(CURDATE(), INTERVAL 1 DAY), '11:00:00', 'Revisi�n general', 'Programada'),
(4, 3, DATE_ADD(CURDATE(), INTERVAL 2 DAY), '08:30:00', 'Extracci�n', 'Programada'),
(2, 2, DATE_ADD(CURDATE(), INTERVAL -10 DAY), '14:00:00', 'Caries', 'Atendida'),
(3, 1, DATE_ADD(CURDATE(), INTERVAL -3 DAY), '16:00:00', 'Limpieza', 'Cancelada');

INSERT INTO historial_clinico (id_paciente, id_odontologo, fecha_atencion, diagnostico, observaciones, tratamiento) VALUES
(1, 1, DATE_ADD(CURDATE(), INTERVAL -5 DAY), 'Caries en pieza 26', 'Paciente indica sensibilidad leve', 'Resina compuesta'),
(1, 2, DATE_ADD(CURDATE(), INTERVAL -30 DAY), 'Control de rutina', 'Sin observaciones', 'Limpieza dental'),
(2, 2, DATE_ADD(CURDATE(), INTERVAL -10 DAY), 'Caries interproximal', 'Se recomienda uso de hilo dental', 'Obturaci�n'),
(3, 1, DATE_ADD(CURDATE(), INTERVAL -15 DAY), 'Gingivitis leve', 'Mejorar t�cnica de cepillado', 'Limpieza profunda');
