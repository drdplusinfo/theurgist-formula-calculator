window.addEventListener('load', function () {
    var form = document.getElementById('configurator');
    var inputs = document.getElementsByTagName('input');
    var formula = document.getElementById('formula');
    var buttons = form.getElementsByTagName('button');
    var submitForm = function () {
        form.submit();
    };
    var disableInputs = function () {
        for (var j = 0, length = inputs.length; j < length; j++) {
            inputs[j].disabled = true;
        }
        formula.disabled = true;
    };
    for (var i = 0, length = inputs.length; i < length; i++) {
        var input = inputs[i];
        input.addEventListener('change', function () {
            submitForm();
            disableInputs();
        });
    }
    formula.addEventListener('change', function () {
        submitForm();
        disableInputs();
    });
    for (var buttonsI = 0, buttonsLength = buttons.length; buttonsI < buttonsLength; buttonsI++) {
        buttons[buttonsI].className = 'hidden';
    }
});