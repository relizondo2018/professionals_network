# Api para Professional Networks




# Instalación

El proyecto no cuenta con un archivo dump para restaurar una base de datos, todo se realizó con base en migraciones, antes de comenzar a usar correr las migraciones con el comanado
> php artisan migrate

# Users Endpoint

El endpoint de Users cuenta con las siguientes rutas:
|       Ruta         |Método                          |  Descripción   | Datos |
|----------------|-------------------------------|-----------------------------|----|
| /users 		 |		POST					 |      Guarda un nuevo User   | email, first_name, last_name, password, country|
|/import          |.    POST		             | Genera X Users aleatorios   | number_of_users |
|/users          | PATCH 						 |Actualiza la información de un User|email, first_name, last_name, password, country |
|/delete          |DELETE						 | Elimina el registro de un User |email, password|


# Relations Endpoint

El endpoint de Relations cuenta con las siguientes rutas:
|       Ruta         |Método                          |  Descripción   | Datos |
|----------------|-------------------------------|-----------------------------|----|
| /relation 	 |		POST					 |     Guarda una nueva relación   | email, password, related_email |
|/relation|    DELETE		             | Elimina una relación   | email, password, related_email |
|/relation/list/{depth}| GET			 |Trae las relaciones directas/indirectas de un User| depth(direct/indirect), email, password |
|/relation_random      |GET						 | Genera relaciones aleatorias a un User | email, password, random |



# Tests

El proyecto cuenta con tests (Feature y Unit) para validar que no se ingresen datos no válidos en las peticiones.

Si se unifica la entrevista y se dan los nombres de los endpoints que se espera utilizar, se podrían copiar estos tests y aplicar para los demás candidatos para validar sus API.

Para correr los test se utiliza el comando:

>vendor/bin/phpunit

