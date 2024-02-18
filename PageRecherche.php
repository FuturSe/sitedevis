<?php 
include("MenuNav.php");
include("ConnexionDB.php");
?>

<!DOCTYPE html>
<html>
    <head>
        <style>
            table{
                border-collapse: collapse;
                width: 100%;
            }
            th,td{
                padding: 8px;
                text-align: left;
                border-bottom: 1px solid #ddd;
            }
            th{
                background-color: #f2f2f2;
            }

        </style>
    </head>

    <body>
        <form action="PageRecherche.php" method="post">
            <label for="nom_entreprise">Nom entreprise</label>
            <input type="text" name="nom_entreprise" required>
            <input type="submit" value="Rechercher le devis ">
        </form>
        <?php 
        try{
            $conn=new PDO("mysql: host=$servername; dbname=$dbname",$username,$password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO :: ERRMODE_EXCEPTION);

            if(isset($_POST['nom_entreprise'])){
                $nom_entreprise=$_POST['nom_entreprise'];
                $sqlRechercheDevis="SELECT id, nom_entreprise, nom_dirigeant, statut_juridique, date, activite, adresse,portable, mail, objectif FROM devis WHERE nom_entreprise LIKE :nom_entreprise  LIMIT 5";
                $stmRechercheDevis=$conn->prepare($sqlRechercheDevis);
                $stmRechercheDevis->bindValue(':nom_entreprise','%' .$nom_entreprise.'%');

                if(!$stmRechercheDevis->execute()){
                    echo "erreur lors de l'exécution de la requête : ";
                    exit();
                }
                echo "<table>";
                echo" <tr><th>id</th><th>nom_entreprise</th><th>nom_dirigeant</th><th>statut_juridique</th><th>date</th><th>activite</th><th>adresse</th><th>portable</th><th>mail</th><th>objectif</th><th>voir</th></tr>";

                $results=$stmRechercheDevis->fetchAll(PDO::FETCH_ASSOC);
                foreach($results as $row){
                    echo"<tr>";
                    echo"<td>" .$row['id'] . "</td>";
                    echo"<td>" .$row['nom_entreprise'] . "</td>";
                    echo"<td>" .$row['nom_dirigeant'] . "</td>";
                    echo"<td>" .$row['statut_juridique'] . "</td>";
                    echo"<td>" .$row['date'] . "</td>";
                    echo"<td>" .$row['activite'] . "</td>";
                    echo"<td>" .$row['adresse'] . "</td>";
                    echo"<td>" .$row['portable'] . "</td>";
                    echo"<td>" .$row['mail'] . "</td>";
                    echo"<td>" .$row['objectif'] . "</td>";
                    echo"<td><a href='PageDevis.php?id=" .$row['id'] . "'>Voir</a></td>";
                    echo"</tr>";

                }
                echo"</table>";
            }
            $conn=null;
        }catch(PDOException $e){
    echo " erreur de connexion à la base de donnée :";
    exit();
}  
        ?>
    </body>
</html>