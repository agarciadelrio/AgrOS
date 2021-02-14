# Casos de uso

## Herencia de usuarios
```plantumlcode
@startuml
skinparam handwritten true
skinparam actor {
  FontName Comic Sans MS
}
skinparam usecase {
  FontName Comic Sans MS
}
left to right direction
:Invitado: as i
:Usuario: as u
:Trabajador: as t
:Productor: as p
:Propietario: as o
:Administrador: as a
i <|-- u
u <|-- t
t <|- p
o --|> p
a --|> o
@enduml
```

## Uso general de la aplicación
```plantumlcode
@startuml
skinparam handwritten true
skinparam actor {
  FontName Comic Sans MS
}
skinparam usecase {
  FontName Comic Sans MS
}
:Usuario: as u
u -- (Use)
:Administrador: as a
"Usa la aplicación" as (Use)
a -- (Administra la aplicación)
u <|- a
@enduml
```

## Navegar por sitio web público

```plantumlcode
@startuml
skinparam handwritten true
skinparam actor {
  FontName Comic Sans MS
}
skinparam usecase {
  FontName Comic Sans MS
}
skinparam frame {
  FontName Comic Sans MS
}
left to right direction
actor "Invitado" as fc
frame "Sitio web público" {
  (Navegar) as n
  n ..>> (Página pública): <<include>>
}
fc -- n
@enduml
```
(descripción)

## Registro de nuevo usuario

```plantumlcode
@startuml
skinparam handwritten true
skinparam actor {
  FontName Comic Sans MS
}
skinparam usecase {
  FontName Comic Sans MS
}
left to right direction
:Invitado: as i
:Admin: as ad
(Registra\ncuenta) as r
(Activa\ncuenta) as a
(Administra\ncuentas) as b
(Confirmación contraseña\n& Captcha) as p
(Email\n& Contraseña) as e
i -- r
r <<.. a: <<include>>
a -- ad
p <<.. r: <<include>>
p ..>> e: <<include>>
b -- ad
b <<.. a: <<extend>>
@enduml
```
(descripción)

## Login de usuario

```plantumlcode
@startuml
skinparam handwritten true
skinparam actor {
  FontName Comic Sans MS
}
skinparam usecase {
  FontName Comic Sans MS
}
left to right direction
:Invitado: as i
(Cuenta registrada\ny activada) as ra
(Login) as l
(Email\n& Contraseña) as ep
i -- l
l ..>> ep: <<include>>
ra <<.. l: <<include>>
@enduml
```
(descripción)

## Recuperación de contraseña

```plantumlcode
@startuml
skinparam handwritten true
skinparam actor {
  FontName Comic Sans MS
}
skinparam usecase {
  FontName Comic Sans MS
}
left to right direction
:Invitado: as i
(Cuenta registrada\ny activada) as ra
(Recuperar contraseña) as r
(Email) as ep
(Login) as l
i -- r
r ..>> ep: <<include>>
r .>> l: <<extend>>
ra <<.. r: <<include>>
@enduml
```
(descripción)

## Cerrar sesión (Logout)
```plantumlcode
@startuml
skinparam handwritten true
skinparam actor {
  FontName Comic Sans MS
}
skinparam usecase {
  FontName Comic Sans MS
}
left to right direction
:Usuario: as u
(logout) as o
(login) as l
u -- o
o ..>> l: <<include>>
@enduml
```
(descripción)

## Editar datos personales
```plantumlcode
@startuml
skinparam handwritten true
skinparam actor {
  FontName Comic Sans MS
}
skinparam usecase {
  FontName Comic Sans MS
}
left to right direction
:Usuario: as u
(Editar\nperfil) as o
(login) as l
u -- o
o ..>> l: <<include>>
@enduml
```
(descripción)

## Cancelar cuenta
```plantumlcode
@startuml
skinparam handwritten true
skinparam actor {
  FontName Comic Sans MS
}
skinparam usecase {
  FontName Comic Sans MS
}
left to right direction
:Usuario: as u
:Admin: as a
(Cancelar\ncuenta) as o
(login) as l
(Eliminar\ncuenta) as d
u -- o
o ..>> l: <<include>>
d -- a
o -- a
d ..>> o: <<include>>
@enduml
```
(descripción)

## Uso específico de la aplicación

### Invitado
```plantumlcode
@startuml
skinparam handwritten true
skinparam actor {
  FontName Comic Sans MS
}
skinparam usecase {
  FontName Comic Sans MS
}
left to right direction
:Invitado: as u
(Navega) as n
u -- n
@enduml
```
(descripción)

### Usuario
```plantumlcode
@startuml
skinparam handwritten true
skinparam actor {
  FontName Comic Sans MS
}
skinparam usecase {
  FontName Comic Sans MS
}
left to right direction
:Usuario: as u
:Invitado: as u2
(Login) as l
(Editar perfil) as e
u -- l
u -- e
e ..>> l: <<include>>
u2 <|-- u
@enduml
```
(descripción)

### Trabajador
```plantumlcode
@startuml
skinparam handwritten true
skinparam actor {
  FontName Comic Sans MS
}
skinparam usecase {
  FontName Comic Sans MS
}
left to right direction
:Trabajador: as u
:Usuario: as u2
(Sembrar) as s
(Aplicar\nTratamiento) as t
(Regar) as r
(Abonar) as a
(Inventariar) as i
u -- s
u --- t
u --- r
u -- a
u2 <|-- u
s <<.. i: <<extend>>
t <<.. i: <<extend>>
r <<.. i: <<extend>>
a <<.. i: <<extend>>
@enduml
```
(descripción)

### Productor
```plantumlcode
@startuml
skinparam handwritten true
skinparam actor {
  FontName Comic Sans MS
}
skinparam usecase {
  FontName Comic Sans MS
}
left to right direction
:Productor: as u
:Trabajador: as u2
(Comprar) as t
(Vender) as r
(Inventariar) as i
(Admin.\nTrabajadores) as a
(Admin.\nVariedades) as v
(Admin.\nPropiedades) as c
(Prescribir) as p
u -- i
u --- t
u --- r
u ---- a
u ---- v
u --- c
u -- p
i ..>> t:<<extend>>
i ..>> r:<<extend>>
u2 <|-- u
@enduml
```
(descripción)

### Administrador
```plantumlcode
@startuml
skinparam handwritten true
skinparam actor {
  FontName Comic Sans MS
}
skinparam usecase {
  FontName Comic Sans MS
}
left to right direction
:Administrador: as u
:Productor: as u2
(Administrar\nUsuarios) as a
u -- a
u2 <|-- u
@enduml
```
(descripción)

### CRUD Genérico
```plantumlcode
@startuml
skinparam handwritten true
skinparam actor {
  FontName Comic Sans MS
}
skinparam usecase {
  FontName Comic Sans MS
}
left to right direction
:Usuario: as u
(Listar) as l
(Buscar) as b
(Imprimir) as i
(Nuevo) as n
(Modificar) as m
(Eliminar) as e
u - l
u - b
u -- i
u -- n
u -- m
u -- e
b ..>> l:<<extend>>
m ..>> n:<<extend>>
i ..>> b:<<extend>>
@enduml
```
(descripción)
