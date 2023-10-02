# dbconnect
Composant composer : Gestion de la base de donnée.

# Installation du composant:
Exécuter: composer require rtgroup/dbconnect

#Usage:
<u>exemple:</u>

$hostname="localhost"; // hostname <br>
$username="root"; //username de la db. <br>
$password="12345"; //mot de pass <br>
$dbName="exempleDatabase"; //nom de la base de donnée <br>
<br>
<u>Configuration de la base de donnée</u><br>
$dbConfig=new Dbconfig($hostname,$username,$password,$dbName); <br>

<u>Connection de la base de donnée</u><br>
$db=new Dbconnect($dbConfig);

<p>La classe : Dbconnect contient plusieurs méthodes permettant d'éxecuter toutes sortes des requetes sql.</p>
