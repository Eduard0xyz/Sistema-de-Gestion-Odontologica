
create database if not exists clinica_dental character set utf8mb4;
use clinica_dental;

create table usuario (
    id_usuario int auto_increment primary key,
    usuario varchar(50) not null unique,
    password varchar(255) not null,
    rol enum('Recepcionista','Odontologo','Administrador') not null
);

create table paciente (
    id_paciente int auto_increment primary key,
    dni varchar(8) not null unique,
    nombres varchar(80) not null,
    apellidos varchar(80) not null,
    telefono varchar(20),
    correo varchar(100),
    direccion varchar(150),
    fecha_nacimiento date
);

create table odontologo (
    id_odontologo int auto_increment primary key,
    nombres varchar(80) not null,
    apellidos varchar(80) not null,
    especialidad varchar(80),
    telefono varchar(20),
    correo varchar(100)
);

create table cita (
    id_cita int auto_increment primary key,
    id_paciente int not null,
    id_odontologo int not null,
    fecha date not null,
    hora time not null,
    motivo varchar(200),
    estado enum('Programada','Reprogramada','Cancelada','Atendida') default 'Programada',
    foreign key (id_paciente) references paciente(id_paciente),
    foreign key (id_odontologo) references odontologo(id_odontologo)
);

create table historial_clinico (
    id_historial int auto_increment primary key,
    id_paciente int not null,
    id_odontologo int not null,
    fecha_atencion date not null,
    diagnostico text,
    observaciones text,
    tratamiento varchar(200),
    foreign key (id_paciente) references paciente(id_paciente),
    foreign key (id_odontologo) references odontologo(id_odontologo)
);

create table recordatorio (
    id_recordatorio int auto_increment primary key,
    id_cita int not null,
    medio enum('Correo','SMS') not null,
    fecha_envio datetime not null,
    estado enum('Pendiente','Enviado','Fallido') default 'Pendiente',
    foreign key (id_cita) references cita(id_cita)
);