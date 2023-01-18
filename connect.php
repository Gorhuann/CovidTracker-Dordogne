<?php

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

    try 
    {
       $db=new PDO('mysql:host=localhost;dbname=bddsae303','root','');
    }
    catch (Exception $e)
    {
        die('Erreur : ' . $e->getMessage());
    }

    $dataType = $_REQUEST["dataType"];

    if(isset($_REQUEST["tempo"])){
        $tempo = $_REQUEST["tempo"];

        if(isset($_REQUEST["dateMin"]))
        {
            $dateMin = $_REQUEST["dateMin"];
            $dateMax = $_REQUEST["dateMax"];    
        }
        else
        {
            $dateMin = '2020-03-18';
            $dateMax = '2022-11-10'; 
        }
    }
    else if(isset($_REQUEST["classeAge"]))
        $classeAge = $_REQUEST["classeAge"];
    else if(isset($_REQUEST["nomVacc"]))
        $nomVacc = $_REQUEST["nomVacc"];

    function vacc_getData($db, $dataType)
    {
        $data = [];

        $respStatement = $db->prepare("SELECT $dataType FROM sae303_donnees_vaccination WHERE semaine_injection = '2022-44' AND classe_age = 'TOUT_AGE' AND type_vaccin = 'Tout vaccin' ");
        $respStatement->execute();
        while($resp = $respStatement->fetch(PDO::FETCH_ASSOC))
            array_push($data, $resp[$dataType]);

        return json_encode(array('data' => $data));
    }

    function vacc_getDataAge($db, $dataType, $nomVacc)
    {
        $labels = [];
        $data = [];

        $respStatement = $db->prepare("SELECT libelle_classe_age, $dataType FROM sae303_donnees_vaccination WHERE type_vaccin = :nomVacc AND semaine_injection = '2022-44' ");
        $respStatement->execute(array("nomVacc" => $nomVacc));
        while($resp = $respStatement->fetch(PDO::FETCH_ASSOC))
        {
            if($resp["libelle_classe_age"] !== "Tout âge")
            {
                array_push($labels, $resp["libelle_classe_age"]);
                array_push($data, $resp[$dataType]);
            }
        }

        return json_encode(array('labels'=>$labels, 'data'=>$data));
    }
    
    function vacc_getDataNom($db, $dataType, $classeAge)
    {
        $labels = [];
        $data = [];
    
        $respStatement = $db->prepare("SELECT type_vaccin, $dataType FROM sae303_donnees_vaccination WHERE classe_age = :classeAge AND semaine_injection = '2022-44' ");
        $respStatement->execute(array("classeAge" => $classeAge));
        while($resp = $respStatement->fetch(PDO::FETCH_ASSOC))
        {
            if($resp["type_vaccin"] !== "Tout vaccin")
            {
                array_push($labels, $resp["type_vaccin"]);
                array_push($data, $resp[$dataType]);
            }
        }

    
        return json_encode(array('labels'=>$labels, 'data'=>$data));
    }

    function cov_getData($db, $dataType)
    {
        $data = [];

        $respStatement = $db->prepare("SELECT SUM($dataType) AS donnee FROM sae303_cov24");
        $respStatement->execute();
        while($resp = $respStatement->fetch(PDO::FETCH_ASSOC))
            array_push($data, $resp["donnee"]);

        return json_encode(array('data' => $data));
    }

    function cov_graphSemaine($dataType, $db, $dateMin, $dateMax)
    {
        $labels = [];
        $data = [];
        $moyData = [];
        $resMoyData = 0;

        $respStatement = $db->prepare("SELECT WEEK(date) AS semaine, YEAR(date) AS annee, $dataType FROM sae303_cov24 WHERE date BETWEEN :dateMin AND :dateMax");
        $respStatement->execute(array("dateMin" => $dateMin, "dateMax" => $dateMax));
        while($resp = $respStatement->fetch(PDO::FETCH_ASSOC))
        {
            if($resp["semaine"]."/".$resp["annee"] !== end($labels) || empty($labels))
            {
                array_push($labels, $resp["semaine"]."/".$resp["annee"]);
                if(!empty($moyData))
                {
                    for($i = 0; $i < count($moyData) ; $i++)
                        $resMoyData += $moyData[$i];
                    $resMoyData /= count($moyData);
                    array_push($data, floor($resMoyData));
                    $moyData = [];    
                    $resMoyData = 0;
                }
            }
            else if($resp[$dataType] !== "")
                array_push($moyData, $resp[$dataType]);
        }
        return json_encode(array('labels'=>$labels, 'data'=>$data));
    }

    function cov_graphTrim($dataType, $db)
    {
        $labels = [];
        $data = [];
        $moyData = [];
        $NbTrim = 1;
        $resMoyData = 0;

        $respStatement = $db->prepare("SELECT DAY(date) AS jours, MONTH(date) AS mois, YEAR(date) AS annee, $dataType FROM sae303_cov24");
        $respStatement->execute();
        while($resp = $respStatement->fetch(PDO::FETCH_ASSOC))
        {
            if(($resp["jours"] === 1) && ($resp["mois"] === 4 || $resp["mois"] === 7 || $resp["mois"] === 10 || $resp["mois"] === 1))
            {
                if($resp["mois"] === 1)
                    array_push($labels, "Trimestre ".$NbTrim." ".($resp["annee"]-1));
                else
                    array_push($labels, "Trimestre ".$NbTrim." ".$resp["annee"]);
                if(!empty($moyData))
                {
                    for($i = 0; $i < count($moyData) ; $i++)
                        $resMoyData += $moyData[$i];
                    $resMoyData /= count($moyData);
                    array_push($data, floor($resMoyData));
                    $moyData = [];    
                }
                if($NbTrim !== 4)
                    $NbTrim++;
                else
                    $NbTrim = 1;
            }
            if($resp[$dataType] !== "")
                array_push($moyData, $resp[$dataType]);
        }
        return json_encode(array('labels'=>$labels, 'data'=>$data));
    }

    function cov_graphMois($dataType, $db)
    {
        $labels = [];
        $data = [];
        $moyData = [];
        $resMoyData = 0;

        $respStatement = $db->prepare("SELECT MONTH(date) AS mois, YEAR(date) AS annee, $dataType FROM sae303_cov24");
        $respStatement->execute();
        while($resp = $respStatement->fetch(PDO::FETCH_ASSOC))
        {
            if($resp["mois"]."/".$resp["annee"] !== end($labels) || empty($labels))
            {
                array_push($labels, $resp["mois"]."/".$resp["annee"]);
                if(!empty($moyData))
                {
                    for($i = 0; $i < count($moyData) ; $i++)
                        $resMoyData += $moyData[$i];
                    $resMoyData /= count($moyData);
                    array_push($data, floor($resMoyData));
                    $moyData = [];    
                }
            }
            else if($resp[$dataType] !== "")
                array_push($moyData, $resp[$dataType]);
        }
        return json_encode(array('labels'=>$labels, 'data'=>$data));
    }

    $graphData = " ";

    if(isset($_REQUEST["nomTab"]))
    {
        if($_REQUEST["nomTab"] === "cov")
        {
           if(isset($tempo))
           {
            if($tempo === "mois")
                $graphData = cov_graphMois($dataType, $db);
            else if($tempo === "trimestre")
                $graphData = cov_graphTrim($dataType, $db);
            else if($tempo === "semaine")
                $graphData = cov_graphSemaine($dataType, $db, $dateMin, $dateMax);
           }
            else if($_REQUEST["labelType"] === "soloData")
                $graphData = cov_getData($db, $dataType);

        }
        else if($_REQUEST["nomTab"] === "vac")
        {
            if($_REQUEST["labelType"] === "nomVacc")
                $graphData = vacc_getDataAge($db, $dataType , $nomVacc);
            else if($_REQUEST["labelType"] === "classeAge")
                $graphData = vacc_getDataNom($db, $dataType , $classeAge);
            else if($_REQUEST["labelType"] === "soloData")
                $graphData = vacc_getData($db, $dataType);
        }
    }

    echo $graphData;
?>

