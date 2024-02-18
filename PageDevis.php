<?php 
include("MenuNav.php");
include("ConnexionDB.php");
try{
    $conn=new PDO("mysql: host=$servername; dbname=$dbname",$username,$password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO :: ERRMODE_EXCEPTION);
    $devis_id=$_GET['id'];

    //récupérer les données du devis.

    $sqlDevis=" SELECT * FROM devis WHERE id = ?";
    $stmtDevis=$conn->prepare($sqlDevis);
    $stmtDevis->bindParam(1,$devis_id);
    if(!$stmtDevis->execute()){
        echo"erreur lors de l'éxecution de la requête: " ;
        exit();
    }

    //assigner ces données à des variables
    if($stmtDevis->rowCount()>0){
        $row=$stmtDevis->fetch(PDO::FETCH_ASSOC);
        $nom_entreprise=$row['nom_entreprise'];
        $nom_dirigeant=$row['nom_dirigeant'];
        $statut_juridique=$row['statut_juridique'];
        $date=$row['date'];
        $activite=$row['activite'];
        $adresse=$row['adresse'];
        $portable=$row['portable'];
        $mail=$row['mail'];
        $objectif=$row['objectif'];
    } else{
        echo "Devis non trouvé";
    }

    //récupérer les données de la table mission.
    $sqlMission ="SELECT * FROM mission WHERE devis_id=? ";
    $stmtMission=$conn->prepare($sqlMission);
    $stmtMission->bindParam(1,$devis_id);
    if(!$stmtMission->execute()){
        echo "erreur lors de l'execution de la requête : ";
        exit();
    }

    //Requete pour récupérer les données de la table author
    $sqlAuthor="SELECT * FROM author";
    $stmtAuthor=$conn->prepare($sqlAuthor);
    if(!$stmtAuthor->execute()){
        echo "erreur lors de  l'execution de la requête: " ;
        exit();
    }

    //assigner ces données dans des variables.
    if($stmtAuthor->rowCount()>0){
        $row=$stmtAuthor->fetch(PDO::FETCH_ASSOC);
        $author_nom=$row['author_nom'];
        $author_prenom=$row['author_prenom'];
        $author_portable=$row['author_portable'];
        $author_adresse=$row['author_adresse'];
        $author_mail=$row['author_mail'];
        $author_siret=$row['author_siret'];
    }else{
        echo"auteur non trouvé";
        exit();
    }

    //afficher les données de la table author
    echo"<h2>Prestataire : </h2>";
    echo"<ul>";
        echo"<li> Nom : " .$author_nom ."</li>";
        echo"<li> Prénom : " .$author_prenom ."</li>";
        echo"<li> Portable : " .$author_portable ."</li>";
        echo"<li> Mail : " .$author_mail."</li>";
        echo"<li> Adresse : " .$author_adresse."</li>";
        echo"<li> SIRET : " .$author_siret."</li>";
    echo"</ul>";
    //afficher les données de la table devis
    echo"<h2> Client : </h2>";
    echo"<ul>";
        echo"<li>Nom entreprise : " .$nom_entreprise ."</li>";
        echo"<li>Nom dirigeant : " .$nom_dirigeant ."</li>";
        echo"<li>Statut Juridique : " .$statut_juridique ."</li>";
        echo"<li>Activité : " .$activite."</li>";
        echo"<li>Adresse  : " .$adresse."</li>";
        echo"<li>Portable : " .$portable."</li>";
        echo"<li>Mail : " .$mail ."</li>";
    echo"</ul>";

    echo"<p>Emis le " .$date ."</p>";

    echo"<p> Devis n° " .$devis_id ."</p>";

    echo "<p> Objectif : " .$objectif ."</p>";

    echo"<table>";
    echo"<tr><th>Description</th><th>Quantité</th><th>Prix_unitaire</th><th>Montant</th></tr>";

    while($row=$stmtMission->fetch(PDO::FETCH_ASSOC)){
        echo "<tr>";
        echo"<td>" .$row['description']."</td>";
        echo"<td>" .$row['quantite']."</td>";
        echo"<td>" .$row['prix_unitaire']."</td>";
        echo"<td>" .$row['montant']."</td>";
        echo"</tr>";
    }

    // Réexécutez la requête pour obtenir à nouveau les résultats

    $stmtMission->execute();

    if($stmtMission->rowCount()>0){
        $row=$stmtMission->fetch(PDO:: FETCH_ASSOC);
        echo"<tr><td colspan='3'>Montant total HT</td><td>" .$row['montant_total_ht'] . "€ </td></tr>";
        echo"<tr><td colspan='3'>TVA(20%) </td><td>" .$row['tva'] . "€ </td></tr>";
        echo"<tr><td colspan='3'>Montant total TTC</td><td>" .$row['montant_total_ttc'] . "€ </td></tr>";


    }
    



    













    echo"</table>";
    ?>
    <page backtop="10mm" backleft="10mm" backright ="10mm"  backbottom="10mm" footer="page;">
        <page_footer>
            <hr />
            <p>Fait à Paris le <?php echo date('Y-m-d H:i:s'); ?></p>
            <p>Signature du Particuler, suive de la mension manuscrite "bon pour accord " .</p>
        </page_footer>
    </page>
    <?php 
        $conn=null;
        exit();

}catch(PDOException $e){
    echo " erreur de connexion à la base de donnée :".$e->getMessage();
    exit();
}
?>