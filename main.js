random();

// Рандомный pin
function random() {
    if (!pin.value) {
        for (var i = 0; i < 9; i++) {
            document.getElementById('pin').value += Math.floor( Math.random() * (10 - 1) + 1 );
        }
    }
}

// Валидация
function validate(param) {
    var subject = document.getElementById('subject');
    var text = document.getElementById('text');
    var email = document.getElementById('email');
    var pin = document.getElementById('pin');

    switch(param) {
        case 'subject':
            if (!subject.value) {
                subject.style.border = "1px solid red";
            } else {
                subject.style.border = "1px solid black";
            }
            break;
        case 'text':
            if (!text.value) {
                text.style.border = "1px solid red";
            } else {
                text.style.border = "1px solid black";
            }
            break;
        case 'email':
            if (!email.value) {
                email.style.border = "1px solid red";
            } else {
                email.style.border = "1px solid black";
            }
            break;
        case 'pin':
            if (!pin.value) {
                pin.style.border = "1px solid red";
            } else {
                pin.style.border = "1px solid black";
            }
            break;
    }
    // Отключение кнопки POST
    document.getElementById('button').disabled = subject.value
    && text.value && email.value && pin.value ? false : ":disabled;";
}