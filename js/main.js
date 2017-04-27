window.addEventListener('load', function () {
    var form = document.getElementById('configurator');
    var inputs = document.getElementsByTagName('input');
    var formula = document.getElementById('formula');
    var submitForm = function () {
        form.submit();
    };
    var disableInputs = function () {
        for (var j = 0, length = inputs.length; j < length; j++) {
            inputs[j].disabled = true;
        }
        formula.disabled = true;
    };
    var invalidateResult = function () {
        document.getElementById('result').className += ' obsolete';
        document.getElementById('result').style.opacity = '0.5';
    };
    for (var i = 0, length = inputs.length; i < length; i++) {
        var input = inputs[i];
        input.addEventListener('change', function () {
            submitForm();
            disableInputs();
            invalidateResult();
        });
    }
    formula.addEventListener('change', function () {
        submitForm();
        disableInputs();
        invalidateResult();
    });
});