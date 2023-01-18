<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="main.css">
        <title>Dashboard</title>
    </head>
    <body>
        <header>
            <div>
                <h1>Tableaux de bord COVID-19 région Dordogne</h1>
                <img src="dordogne.png" alt="logo departement dordogne">
            </div>
            <h2>Bienvenue sur le tableau de bord présentant les informations concernant le Covid ainsi que sa vaccination pour la région de la Dordogne.</h2>
        </header>
        <section id="cov">
            <h3>Données concernant la pandémie</h3>
            <p>
                Via le graphique juste en dessous vous pourrez observer les données concernant le nombre d'hospitalisation, de réanimation, de décès et de cas en réanimation à travers le temps.
                Vous pouvez choisir la temporalite désiré avec le formulaire ci-dessous, avec "semaine" vous pourrez sélectionner entre deux date pour accentuer la précision du graphique là ou vous le désirez.
                Enfin, vous pouvez cliquer sur chaque étiquette de données afin de ne pas les voir sur le graphique. 
            </p>
            <form action="" id="formCov">
                <label for="temporalite">Choissisez temporalité :</label>
                <select name="temporalite" id="temporalite">
                    <option value="semaine">Semaine</option>
                    <option value="mois">Mois</option>
                    <option value="trimestre">Trimestre</option>
                </select>
                <div id="weekChoisi">
                    <input type="date" name="dateDebut" id="dateDebut" value="2020-03-18" min="2020-03-18" max="2022-11-10"> 
                    <input type="date" name="dateFin" id="dateFin" value="2022-11-10" min="2020-03-18" max="2022-11-10">
                </div>
                <input type="submit" id="choisir" value="Choisir">
            </form>
            <div class="graph">
                <canvas id="covGraph">vous ne pouvez pas voir le canvas</canvas>
            </div>
        </section>
        <section id="vac">
            <h3>Données concernant la vaccination</h3>
            <div>
                <div class="num">
                    <h4>Nombre de personne vacciné</h4>
                    <p></p>
                </div>
                <div class="num">
                    <h4>Pourcentage de la population vacciné</h4>
                    <p></p>
                </div>
                <div class="num">
                    <h4>Nombre de personne ayant effectuées un rappel</h4>
                    <p></p>
                </div>
            </div>
            <div id="age_nom_graph">
                <div>
                    <h3>Nombre de vaccination en fonction des classes d'age</h3>
                    <div class="graph">
                        <canvas id="age_vacc">vous ne pouvez pas voir le canvas</canvas>
                    </div>
                </div>
                <div>                    
                    <h3>Répartition des vaccins les plus utilisés pour la vaccination</h3>
                    <div class="graph">
                        <canvas id="nom_vacc">vous ne pouvez pas voir le canvas</canvas>
                    </div>
                </div>
            </div>
            <div>
                <h3>Répartition des classes d'age en fonction du vaccin choisi avec le formulaire</h3>
                <p>
                    Grâce à ce graphique vous pouvez observer quel vaccin les différentes classes d'âges ont pris, le formulaire vous permet de choisir le vaccin souhaité. 
                    Les données vertes correspondent à tous les vaccins.
                </p>
                <form action="" id="formVacc">
                    <label for="libVacc">Choissisez le vaccin :</label>
                    <select name="libVacc" id="libVacc">
                        <option value="Tout vaccin" selected="selected">Tout Vaccin</option>
                        <option value="COMIRNATY Pfizer-BioNTech">COMIRNATY Pfizer-BioNTech</option>
                        <option value="SPIKEVAX Moderna">SPIKEVAX Moderna</option>
                        <option value="VAXZEVRIA AstraZeneca">VAXZEVRIA AstraZeneca</option>
                        <option value="JCOVDEN Janssen">JCOVDEN Janssen</option>
                        <option value="COMIRNATY Pfizer-BioNTech pédiatrique">COMIRNATY Pfizer-BioNTech pédiatrique</option>
                        <option value="NUVAXOVID Novavax">NUVAXOVID Novavax</option>
                        <option value="COMIRNATY ORIGINAL/OMICRON BA.5 Pfizer-B">COMIRNATY ORIGINAL/OMICRON BA.5 Pfizer-B</option>
                        <option value="SPIKEVAX ORIGINAL/OMICRON BA.1 Moderna">SPIKEVAX ORIGINAL/OMICRON BA.1 Moderna</option>
                    </select>
                </form>
                <div class="graph">
                    <canvas id="age_nom_vacc">vous ne pouvez pas voir le canvas</canvas>
                </div>
            </div>
        </section>
        <script src="js/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>        
        <script src="js/main.js"></script>
        
    </body>
    </html>