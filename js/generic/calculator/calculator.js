document.addEventListener('DOMContentLoaded', function () {
    var allInputs = document.getElementsByTagName('input');
    var allSelects = document.getElementsByTagName('select');
    var allButtons = document.getElementsByTagName('button');
    var allControls = [];
    for (var inputIndex = 0, inputsLength = allInputs.length; inputIndex < inputsLength; inputIndex++) {
        var input = allInputs[inputIndex];
        if (input.type !== 'hidden' && input.type !== 'submit' && !input.classList.contains('manual')) {
            allControls.push(input);
        }
    }
    for (var selectIndex = 0, selectsLength = allSelects.length; selectIndex < selectsLength; selectIndex++) {
        var select = allSelects[selectIndex];
        if (!select.classList.contains('manual')) {
            allControls.push(select);
        }
    }
    for (var buttonIndex = 0, buttonsLength = allButtons.length; buttonIndex < buttonsLength; buttonIndex++) {
        var button = allButtons[buttonIndex];
        if (button.type === 'button' && !button.classList.contains('manual')) {
            allControls.push(button);
        }
    }

    for (selectIndex = 0; selectIndex < selectsLength; selectIndex++) {
        allSelects[selectIndex].addEventListener('change', (function (selectIndex) {
            return function () {
                var inputsWithSelects = allSelects[selectIndex].parentNode.parentNode.getElementsByTagName('input');
                for (var inputWithSelectIndex = 0, inputsWithSelectsLength = inputsWithSelects.length;
                     inputWithSelectIndex < inputsWithSelectsLength;
                     inputWithSelectIndex++
                ) {
                    inputsWithSelects[inputWithSelectIndex].checked = true;
                }
            }
        })(selectIndex));
    }

    for (var controlIndex = 0, controlsLength = allControls.length; controlIndex < controlsLength; controlIndex++) {
        var control = allControls[controlIndex];
        if (typeof control.type !== 'undefined' && control.type !== 'submit') {
            if (typeof control.type === 'undefined' || control.type !== 'button') {
                control.addEventListener('change', function () {
                    submitOnChange(this)
                });
            } else {
                control.addEventListener('click', function () {
                    submitOnChange(this)
                });
            }
        }
    }

    function submitOnChange(changedInput) {
        var form;
        var node = changedInput;
        do {
            form = node.parentElement;
            node = form;
        } while (form && form.tagName.toUpperCase() !== 'FORM');
        if (!form || form.tagName.toUpperCase() !== 'FORM') {
            throw 'No form found for an input ' + changedInput.outerHTML
        }
        if (submit(form) && requiredInputsAreFilled(form)) {
            invalidateResult();
        }
    }

    function submit(form) {
        var formButtons = form.getElementsByTagName('button');
        for (var buttonIndex = 0, buttonsLength = formButtons.length; buttonIndex < buttonsLength; buttonIndex++) {
            var button = formButtons[buttonIndex];
            if (button.type === 'submit' && !button.disabled) {
                button.click();
                return true;
            }
        }
        var formInputs = form.getElementsByTagName('input');
        for (var inputIndex = 0, inputsLength = formInputs.length; inputIndex < inputsLength; inputIndex++) {
            var input = formInputs[inputIndex];
            if (input.type === 'submit' && !input.disabled) {
                input.click();
                return true;
            }
        }
        throw 'No submit has been found in form ' + form.outerHTML;
    }

    function requiredInputsAreFilled(form) {
        var inputs = form.getElementsByTagName('input');
        for (var i = 0, inputsLength = inputs.length; i < inputsLength; i++) {
            var input = inputs[i];
            if (input.required && input.value.toString() === '') {
                return false;
            }
        }
        return true;
    }

    function invalidateResult() {
        var result = document.getElementById('result');
        if (!result) {
            return;
        }
        result.classList.add('obsolete');
        result.style.opacity = '0.5';
    }
});