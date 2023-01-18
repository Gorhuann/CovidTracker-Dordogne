
function getDataCov(dataType, content, tempo = "semaine"){
    
    let labels = [];
    let data = [];

    $.ajax({
        url: "connect.php",
        method: "POST",
        data: {"nomTab" : "cov", "dataType" : dataType, "tempo" : tempo},
        datatype: "JSON",
        async: false,
        success : function(responseText){      
            labels = JSON.parse(responseText).labels;
            data = JSON.parse(responseText).data;
                       
        }            
    });

    if(content === "labels")
        return labels;
    else if(content === "data")
        return data; 
}

function chgDataCov(formResp, dataType, content)
{
    let labels = [];
    let data = [];

    let dateMin = formResp[1].value;
    let dateMax = formResp[2].value;

    $.ajax({
        url: "connect.php",
        method: "POST",
        data: {"dataType" : dataType, "tempo" : "semaine", "dateMin" : dateMin, "dateMax" : dateMax, "nomTab" : "cov"},
        datatype: "JSON",
        async: false,
        success : function(responseText){      
            console.log(responseText);         
            labels = JSON.parse(responseText).labels;
            data = JSON.parse(responseText).data;
        } 
    });

    if(content === "labels")
        return labels;
    else if(content === "data")
        return data;
}

function getDataVacc_Age(dataType, content, nomVacc = "Tout vaccin"){
    
    let labels = [];
    let data = [];

    $.ajax({
        url: "connect.php",
        method: "POST",
        data: {"dataType" : dataType, "nomTab" : "vac", "labelType" : "nomVacc", "nomVacc" : nomVacc},
        datatype: "JSON",
        async: false,
        success : function(responseText){   
            console.log(responseText);   
            data = JSON.parse(responseText).data; 
            labels = JSON.parse(responseText).labels; 
        }            
    });

    if(content === "labels")
        return labels;
    else if(content === "data")
        return data; 
}

function getDataVacc_Nom(dataType, content, classeAge = "TOUT_AGE"){
    
    let labels = [];
    let data = [];

    $.ajax({
        url: "connect.php",
        method: "POST",
        data: {"dataType" : dataType, "nomTab" : "vac", "labelType" : "classeAge", "classeAge" : classeAge},
        datatype: "JSON",
        async: false,
        success : function(responseText){      
            data = JSON.parse(responseText).data; 
            labels = JSON.parse(responseText).labels;  
        }            
    });

    if(content === "labels")
        return labels;
    else if(content === "data")
        return data; 
}

function getData(dataType, nomTab){
    
    let data = [];

    $.ajax({
        url: "connect.php",
        method: "POST",
        data: {"dataType" : dataType, "nomTab" : nomTab, "labelType" : "soloData"},
        datatype: "JSON",
        async: false,
        success : function(responseText){      
            data = JSON.parse(responseText).data; 
        }            
    });

    return data;
}

//---- Premier Graph ----
var cov = new Chart(document.getElementById('covGraph'),
{
    type: 'line',
    data : {
        labels : getDataCov("hosp", "labels"),
        datasets : [
            {
                label: "Nombre d'hospitalisation en fonction du temps",
                data : getDataCov("hosp", "data"),
                borderColor : 'rgb(204, 0, 0)',
                tension: 0.1
            },
            {
                label: "Nombre de cas positif en fonction du temps",
                data : getDataCov("pos", "data"),
                borderColor : 'rgb(204, 0, 204)',
                tension: 0.1
            },
            {
                label: "Nombre de cas en réanimation en fonction du temps",
                data : getDataCov("rea", "data"),
                borderColor : "rgb(0, 222, 222)",
                tension: 0.1
            },
            {
                label: "Nombre de décès en fonction du temps",
                data : getDataCov("dchosp", "data"),
                borderColor : "rgb(150, 150, 0)",
                tension: 0.1
            },
        ]
    }
});

//---- Deuxième Graph ----
var age_vacc = new Chart(document.getElementById('age_vacc'),
{
    type: 'bar',
    data : {
        labels : getDataVacc_Age("effectif_cumu_termine", "labels"),
        datasets : [{
            label: "Nombre de vaccination en fonction des classes d'age",
            data : getDataVacc_Age("effectif_cumu_termine", "data"),
            backgroundColor: [
                'rgb(255, 99, 132)',
                'rgb(255, 159, 64)',
                'rgb(255, 205, 86)',
                'rgb(75, 192, 192)',
                'rgb(54, 162, 235)',
                'rgb(153, 102, 255)',
                'rgb(201, 203, 207)'
              ],
              borderColor: [
                'rgb(255, 99, 132)',
                'rgb(255, 159, 64)',
                'rgb(255, 205, 86)',
                'rgb(75, 192, 192)',
                'rgb(54, 162, 235)',
                'rgb(153, 102, 255)',
                'rgb(201, 203, 207)'
              ]
        }]
    }
});

//---- Troisième Graph ----
var nom_vacc = new Chart(document.getElementById('nom_vacc'),
{
    type: 'pie',
    data : {
        labels : getDataVacc_Nom("effectif_cumu_termine", "labels"),
        datasets : [{
            label: "Répartition des vaccins les plus utilisés pour la vaccination",
            data : getDataVacc_Nom("effectif_cumu_termine", "data"),
        }]
    }
});

//---- Quatrième Graph ----
var age_nom_vacc = new Chart(document.getElementById('age_nom_vacc'),
{
    type: 'radar',
    data : {
        labels : getDataVacc_Age("effectif_cumu_termine", "labels"),
        datasets : [
            {
                label: "Répartition des ages ayant les plus utilisés en fonction de tous les vaccins",
                data : getDataVacc_Age("effectif_cumu_termine", "data"),
                borderColor : "rgb(0, 150, 0)"
            },
            {
                label: "Répartition des ages ayant les plus utilisés en fonction du vaccin choisi",
                data : getDataVacc_Age("effectif_cumu_termine", "data"),
                borderColor : "rgb(150, 0, 0)"
            }
        ]
    }
});

$("#formCov #choisir").click(function (evt){
    evt.preventDefault();
    
    let formResp = $("#formCov").serializeArray()
    let tempo = formResp[0].value;
    
    if($("#weekChoisi").html() === "")
    {
        cov.data.labels = getDataCov("hosp", "labels", tempo);
        cov.data.datasets[0].data = getDataCov("hosp", "data", tempo);
        cov.data.datasets[1].data = getDataCov("pos", "data", tempo);
        cov.data.datasets[2].data = getDataCov("rea", "data", tempo);
        cov.data.datasets[3].data = getDataCov("dchosp", "data", tempo);
    }
    else
    {
        cov.data.labels = chgDataCov(formResp, "hosp", "labels");
        cov.data.datasets[0].data = chgDataCov(formResp, "hosp", "data");
        cov.data.datasets[1].data = chgDataCov(formResp, "pos", "data");
        cov.data.datasets[2].data = chgDataCov(formResp, "rea", "data");
        cov.data.datasets[3].data = chgDataCov(formResp, "dchosp", "data");
    }
    
    cov.update();
    
});

$("#formVacc select").change(function (evt){
    evt.preventDefault();
    
    let formResp = $("#formVacc").serializeArray()
    let nomVacc = formResp[0].value;
    
    age_nom_vacc.data.datasets[1].data = getDataVacc_Age("effectif_cumu_termine", "data", nomVacc);
    
    age_nom_vacc.update();
    
});

$("form select").change(function(){
    if($("select option:selected").val() !== "semaine")
        $("#weekChoisi").html("");
    else
        $("#weekChoisi").html('<input type="date" value="2020-03-18" min="2020-03-18" max="2022-11-10" name="dateDebut" id="dateDebut"> <input type="date" value="2022-11-10" min="2020-03-18" max="2022-11-10" name="dateFin" id="dateFin">');  
});

$(".num:nth-child(1) p").html(getData("effectif_cumu_termine", "vac"));
$(".num:nth-child(2) p").html(Math.floor((getData("effectif_cumu_termine", "vac")*100)/getData("population_insee", "vac")) + "%");
$(".num:nth-child(3) p").html(getData("Effectif_cumu_rappel", "vac"));
