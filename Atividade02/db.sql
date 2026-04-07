drop database if exists exercicio;

create database exercicio;

use exercicio;

create table pessoas (
	id integer primary key auto_increment,
    nome varchar(50) not null,
    tipo char(1) not null,
    cpf_cnpj varchar(14) not null
);

insert into pessoas( nome, tipo, cpf_cnpj)
values ('Joao', 'F', '00000000000');
insert into pessoas( nome, tipo, cpf_cnpj)
values ('Empresa', 'J', '11111111111111');
insert into pessoas( nome, tipo, cpf_cnpj)
values ('Maria', 'J', '22222222222');