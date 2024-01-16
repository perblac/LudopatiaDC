#LudopatiaDC
##Simulador de venta de lotería a lo loco

Modelo:
--------
Sorteo
--------
- Id int
- Nombre String
- Fecha y Hora DateTime
- PrecioCupón int
- NumTotal int
- Premio int
- NumGanador Relation [Cupon]
---------
Usuarios
--------
- Id int
- Nombre string
- Contraseña string
- Rol Array de roles
- Cartilla int
- Cupones Relation [Cupon]
---------
Cupones
--------
- Id int
- Número string
- Sorteo Relation [Sorteo]
- Usuario Relation [User]
- Estado tinyInt
