// Voorbeeld: Pop-up herinnering demo (run op pagina load)
document.addEventListener('DOMContentLoaded', function() {
    // Simuleer herinnering voor events met reminder
    // In productie: gebruik setTimeout gebaseerd op datum
    if (document.querySelector('#calendar')) {
        alert('Herinnering: Check je events!');
    }
});

// Validatie voorbeeld voor forms (client-side)
function validateForm(form) {
    let inputs = form.querySelectorAll('input[required]');
    for (let input of inputs) {
        if (!input.value.trim()) {
            alert('Vul alle verplichte velden in.');
            return false;
        }
    }
    return true;
}

// Voeg toe aan forms: onsubmit="return validateForm(this);"