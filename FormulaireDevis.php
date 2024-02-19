<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport", content="width=device-width, initial-scale=1.0">
        <title> formulaire Devis </title>
        <style>
            /* style CSS pour le formulaire */

            form{
                width: 400px;
                margin: auto;
            }

            label, input{
                display:  block;
                margin-bottom: 10px;

            }

            label{
                font-weight: bold;
            }

            input[type="text"], input[type="number"], input[type="date"]{
                width: 100%;
                padding: 5px;
            }
            input[type="submit"]{
                background-color: #4CAF50;
                color: white;
                padding: 10px 20px;
                border: none;
                cursor: pointer;
            }
            input[type="submit"]:hover{
                background-color: #45a049
            }
        </style>
        <?php
        include("MenuNav.php"); 
        ?>
    </head>
    <body>
        <h2>hey</h2>
        <h2>Formulaire Devis </h2>
        <form action="TraitementFormulaire.php" method="post">
            <label for="nom_entreprise">Nom de l'entreprise : </label>
            <input type="text" name="nom_entreprise" required>

            <label for="nom_dirigeant">Nom du dirigeant : </label>
            <input type="text" name="nom_dirigeant" required>

            <label for="statut_juridique">Statut Juridique : </label>
            <input type="text" name="statut_juridique" required>

            <label for="date"> Date : </label>
            <input type="date" name="date" required>

            <label for="activite">Activite : </label>
            <input type="text" name="activite" required>

            <label for="adresse">Adresse : </label>
            <input type="text" name="adresse" required>

            <label for="portable">Téléphone portable : </label>
            <input type="text" name="portable" required maxlength="14" oninput="FormatPhoneNumber(this)" placeholder="06-__-__-__-__">

            <script>
                function FormatPhoneNumber(input){
                    //supprimer tous les caractères non numérique du numéro de téléphone
                    var phoneNumber = input.value.replace(/\D/g, '');

                    //formate le numéro de téléphone en ajoutant des tirets
                    var formattedPhoneNumber=phoneNumber.replace(/(\d{2})(\d{2})(\d{2})(\d{2})(\d{2})/, '$1-$2-$3-$4-$5');
                    
                    // met à jour la valeur de l'input avec le numéro de téléphone formaté 
                    input.value=formattedPhoneNumber;
                    
                }
            </script>

            <label for="mail">E-mail : </label>
            <input type="text" name="mail">

            <label for="objectif">Objectif : </label>
            <input type="text" name="objectif" required>
            
            <label for="TableDescriptions">Descriptions : </label>
            <table id="TableDescriptions">
                <tr>
                    <th>Mission</th>
                    <th>Quantité</th>
                    <th>Prix unitaire</th>
                </tr>
                <tr>
                    <td><input type="text" name=" description[]" ></td>
                    <td><input type="number" name=" quantite[]" ></td>
                    <td><input type="number" name=" prix_unitaire[]" step="0.01"></td>
                </tr>
            </table>

            <button type="button" onclick="ajouterLigne()">Ajouter une description</button>
            <br><br>
            <input type="submit" value="Créer le devis">
         
        </form>
        <script>
            function ajouterLigne(){
                var table= document.getElementById("TableDescriptions");
                var row=table.insertRow();
                var cell1= row.insertCell(0);
                var cell2= row.insertCell(1);
                var cell3= row.insertCell(2);

                cell1.innerHTML='<input type="text" name="description[]" >';
                cell2.innerHTML='<input type="number" name="quantite[]" >';
                cell3.innerHTML='<input type="number" name="prix_unitaire[]" step="0.01" >';

            }

        </script>
    </body>
</html>