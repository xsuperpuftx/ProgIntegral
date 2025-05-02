//funcion callback

function sumNumber(num, callback){
    callback(num+10);
}

sumNumber(95, function(value){
    console.log(`El resultado es: ${value}`)
})

function sumarDosNumeros(a, b, callback) {
    callback(a + b);
}

// Ejecución cuando el DOM está listo
document.addEventListener('DOMContentLoaded', function() {
    // Definimos nuestras variables
    const numero1 = 90;
    const numero2 = 5;
    
    // Llamamos a la función con las dos variables
    sumarDosNumeros(numero1, numero2, function(resultado) {
        // Mostramos en consola
        console.log(`La suma de ${numero1} + ${numero2} es: ${resultado}`);
        
    });
});