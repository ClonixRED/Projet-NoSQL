<style>
    
    .navigationBar {
        overflow: hidden;
        background-color: #333; 
    }
    .navigationBar a {
        float: left;
        font-size: 16px;
        color: white;
        text-align: center;
        padding: 14px 16px;
        text-decoration: none;
    }
    .navigationBar a:hover {
        background-color: #7f7f7f;
    }
    .navigationBar div.rightSide {
        float: right;
    }
    .navigationBar a.rightSide {
        float: right;
    }
    .navigationBar a.active1 {
        background-color: #28a745;
        color: white;
    }
    .navigationBar a.active3 {
        background-color: #dc3545;
        color: white;
    }
    .subNavigationBar {
        float: left;
        overflow: hidden;
    }
    .subNavigationBar .subNavigationBarbtn {
        font-size: 16px;
        border: none;
        outline: none;
        color: white;
        padding: 14px 16px;
        background-color: inherit;
        font-family: inherit;
        margin: 0;
        cursor: pointer;
    }
    .subNavigationBar-content {
        display: none;
        position: absolute;
        background-color: #4c4c4c;
        min-width: 160px;
        z-index: 1;
    }
    .subNavigationBar-content a {
        float: none;
        color: white;
        padding: 12px 16px;
        text-decoration: none;
        display: block;
        text-align: left;
    }
    .subNavigationBar-content a:hover {
        background-color: #7f7f7f;
        color: white;
    }
    .subNavigationBar:hover .subNavigationBar-content {
        display: block;
    }
    
</style>

<div class="navigationBar">
    <a class="active1" href="./landing.php">Accueil</a>
    <div class="subNavigationBar">
        <button class="subNavigationBarbtn">Service&nbsp;▾</button>
        <div class="subNavigationBar-content">
            <a href="./service.php">Prise de Service</a>
            <a href="./historique.php">Historique des Services</a>
        </div>
    </div> 
    <div class="subNavigationBar">
        <button class="subNavigationBarbtn">Effectif&nbsp;▾</button>
        <div class="subNavigationBar-content">
            <a href="./addEffectif.php">Ajouter un Effectif</a>
            <a href="./allEffectif.php">Liste des Effectifs</a>
        </div>
    </div> 
    <div class="subNavigationBar">
        <button class="subNavigationBarbtn">Client&nbsp;▾</button>
        <div class="subNavigationBar-content">
            <a href="./allClient.php">Liste des Clients</a>
        </div>
    </div> 
    <div class="subNavigationBar">
        <button class="subNavigationBarbtn">Commande&nbsp;▾</button>
        <div class="subNavigationBar-content">
            <a href="./addCommande.php">Ajouter une Commande</a>
            <a href="./allCommande.php">Liste des Commande</a>
        </div>
    </div> 
    <a href="./deconnexion.php" class="rightSide active3">Déconnexion</a>
</div>
