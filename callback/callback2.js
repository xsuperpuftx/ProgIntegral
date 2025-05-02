function primero(){
    console.log(`PRIMERO`)
}

function segundo(){
    console.log("SEGUNDO")
}

function tercero(){
    setTimeout(function(){
        console.log("TERCERO")
    }, 3000);
}

function cuarto(){
    console.log("CUARTA");
}

primero();
segundo();
tercero();
cuarto();

