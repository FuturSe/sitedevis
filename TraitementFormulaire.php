<?php 
include("ConnexionDB.php");
include("MenuNav.php");
try{
    $conn=new PDO("mysql: host=$servername; dbname=$dbname",$username,$password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO :: ERRMODE_EXCEPTION);

    //Récupération des données du formulaire
    $nom_entreprise=$_POST['nom_entreprise'];
    $nom_dirigeant=$_POST['nom_dirigeant'];
    $statut_juridique=$_POST['statut_juridique'];
    $statut_juridique=$_POST['statut_juridique'];
    $date=date('Y-m-d',strtotime(str_replace('-','/', $_POST['date'])));
    $activite=$_POST['activite'];
    $adresse=$_POST['adresse'];
    $portable=$_POST['portable'];
    $mail=$_POST['mail'];
    $objectif=$_POST['objectif'];

    $montant_total_ht=0;
    $montants=array(); // Tableau pour stocker les montants caclulés 

    $description=$_POST['description'];
    $quantite=$_POST['quantite'];
    $prix_unitaire=$_POST['prix_unitaire'];

    for ($i=0 ; $i<count($description);$i++){
        //effectuer le calcul du montant
        $montant=$quantite[$i]*$prix_unitaire[$i];

        //Ajouter le montant calculé au tableau 
        $montants[]=$montant;

        //ajouter le montant calculé au montant total 
        $montant_total_ht+=$montant;
    }

    $tva=$montant_total_ht*0.2;
    $montant_total_ttc=$montant_total_ht+$tva;

    // Insertion des données dans la base de données.

    $sql="INSERT INTO devis (nom_entreprise, nom_dirigeant, statut_juridique, date, activite, adresse, portable, mail, objectif, montant_total_ht)
    VALUES(?,?,?,?,?,?,?,?,?,?)";
    $stmp=$conn->prepare($sql);
    if (!$stmp){
        echo"erreur de préparation de la requête: ";
        exit();
    }

    //Liaision des paramètres avec les valeurs 
    $stmp->bindParam(1,$nom_entreprise);
    $stmp->bindParam(2,$nom_dirigeant);
    $stmp->bindParam(3,$statut_juridique);
    $stmp->bindParam(4,$date);
    $stmp->bindParam(5,$activite);
    $stmp->bindParam(6,$adresse);
    $stmp->bindParam(7,$portable);
    $stmp->bindParam(8,$mail);
    $stmp->bindParam(9,$objectif);
    $stmp->bindParam(10,$montant_total_ht);

    // execution de la requête.
    if (!$stmp->execute()){
        echo"erreur lors de l'insertion dans la base de donnnées : " ;
        exit();
    }

    $devis_id= $conn->lastInsertId();
    
    $sql_mission=" INSERT INTO mission(devis_id, description, quantite,prix_unitaire,montant,montant_total_ht,tva,montant_total_ttc)
    VALUE(?,?,?,?,?,?,?,?)";

    $stmt_mission=$conn->prepare($sql_mission);

    //Parcourir les descriptions,quantités, prix_unitaires et montants ensuite les insérer dans la table "mission"

    for ($i=0;$i<count($description);$i++){
        $stmt_mission->bindParam(1,$devis_id);
        $stmt_mission->bindParam(2,$description[$i]);
        $stmt_mission->bindParam(3,$quantite[$i]);
        $stmt_mission->bindParam(4,$prix_unitaire[$i]);
        // Utiliser le montant calculé pour la laison de paramètre
        $stmt_mission->bindParam(5,$montants[$i]);
        $stmt_mission->bindParam(6,$montant_total_ht);
        $stmt_mission->bindParam(7,$tva);
        $stmt_mission->bindParam(8,$montant_total_ttc);

        if (!$stmt_mission->execute()){
            echo"erreur lors de l'insertion des données dans la table 'mission' : " ;
            exit();
        }
    }

    // requête pour récupérer les données de la table author
    $sqlAuthor="SELECT author_nom,author_prenom,author_portable,author_adresse,author_mail FROM author";
    $resultAuthor=$conn->query($sqlAuthor);

    //requête pour récupérer les données de la table devis
    $sqlDevis="SELECT nom_entreprise, nom_dirigeant, statut_juridique, date, activite, adresse, portable, mail, objectif FROM devis WHERE id=?";

    $stmtDevis=$conn->prepare($sqlDevis);
    if(!$stmtDevis){
        echo"erreur lors de la préparation de la requête : " ;
        exit();
    }

    if (!$stmtDevis->execute([$devis_id])){
        echo"erreur lors de l'exécution  de la requête : " ;
        exit();
    }

    //vérifier si les requêtes ont renvoyé des resultats 

    if($resultAuthor->rowCount()>0|| $stmtDevis->rowCount()>0){
        echo"<h2>Prestataire : </h2>";
        echo"<ul>";
        //afficher les données de la table author
        while($row=$resultAuthor->fetch(PDO::FETCH_ASSOC)){
            echo"<li> Nom :" .$row["author_nom"] ."</li>";
            echo"<li> Prénom :" .$row["author_prenom"] ."</li>";
            echo"<li> Portable :" .$row["author_portable"] ."</li>";
            echo"<li> Mail :" .$row["author_mail"] ."</li>";
            echo"<li> Adresse :" .$row["author_adresse"] ."</li>";
           # echo"<li> SIRET:" .$row["author_siret"] ."</li>";

        }
        echo"</ul>";

        echo"<h2> Client : </h2>";
        echo"<ul>";
        //afficher les données de la table author
        while($row=$stmtDevis->fetch(PDO::FETCH_ASSOC)){
            echo"<li>Nom entreprise :" .$row["nom_entreprise"] ."</li>";
            echo"<li>Nom dirigeant :" .$row["nom_dirigeant"] ."</li>";
            echo"<li>Statut Juridique :" .$row["statut_juridique"] ."</li>";
            echo"<li>Activité :" .$row["activite"] ."</li>";
            echo"<li>Adresse:" .$row["adresse"] ."</li>";
            echo"<li>Portable:" .$row["portable"] ."</li>";
            echo"<li>Mail:" .$row["mail"] ."</li>";
        }
        echo"</ul>";
    }else{
        echo"Aucun résultat trouvé.";
    }

    echo"<p>Emis le " .$date ."</p>";

    echo"<p> Devis n° " .$devis_id ."</p>";

    echo "<p> Objectif : " .$objectif ."</p>";

    //afficher le devis sous forme de tableau assez simple  

    echo"<table>";
    echo"<tr><th>Description</th><th>Quantité</th><th>Prix_unitaire</th><th>Montant</th></tr>";
    for ($i=0; $i<count($description); $i++){
        echo"<tr>";
        echo"<td>" .$description[$i] . "</td>";
        echo"<td>" .$quantite[$i] . "</td>";
        echo"<td>" .$prix_unitaire[$i] . "</td>";
        echo"<td>" .$montants[$i] . " € </td>";
        echo"</tr>";
    }

    echo" <tr><td colspan='3'> Montant total HT </td><td>" .$montant_total_ht ."€ </td></tr>";
  #  echo" <tr><td colspan='3'> TVA </td><td>" .$tva."€ </td></tr>";
# echo" <tr><td colspan='3'> Montant total TTC</td><td>" .$montant_total_ttc ."€ </td></tr>";

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
    echo " erreur de connexion à la base de donnée :";
    exit();
}

?>