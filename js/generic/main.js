var form = document.getElementById('configurator');
var inputs = document.getElementsByTagName('input');
var selects = document.getElementsByTagName('select');
var controls = [];
for (var inputIndex = 0, inputsLength = inputs.length; inputIndex < inputsLength; inputIndex++) {
    var input = inputs[inputIndex];
    if (input.type !== 'hidden') {
        controls.push(inputs[inputIndex]);
    }
}
for (var selectIndex = 0, selectsLength = selects.length; selectIndex < selectsLength; selectIndex++) {
    controls.push(selects[selectIndex]);
}
var submitForm = function () {
    form.submit();
};
var enableControls = function () {
    for (var j = 0, length = controls.length; j < length; j++) {
        controls[j].disabled = null;
    }
};
var disableControls = function (forMilliSeconds) {
    for (var j = 0, length = controls.length; j < length; j++) {
        controls[j].disabled = true;
    }
    if (forMilliSeconds) {
        window.setTimeout(enableControls, forMilliSeconds /* unlock after */)
    }
};
var invalidateResult = function () {
    document.getElementById('result').className += ' obsolete';
    document.getElementById('result').style.opacity = '0.5';
};
for (selectIndex = 0; selectIndex < selectsLength; selectIndex++) {
    selects[selectIndex].addEventListener('change', (function (selectIndex) {
        return function () {
            var inputsWithSelects = selects[selectIndex].parentNode.parentNode.getElementsByTagName('input');
            for (var inputWithSelectIndex = 0, inputsWithSelectsLength = inputsWithSelects.length; inputWithSelectIndex < inputsWithSelectsLength; inputWithSelectIndex++) {
                inputsWithSelects[inputWithSelectIndex].checked = true;
            }
        }
    })(selectIndex));
}
for (var i = 0, controlsLength = controls.length; i < controlsLength; i++) {
    var control = controls[i];
    control.addEventListener('change', function () {
        submitForm();
        disableControls(5000);
        invalidateResult();
    });
}