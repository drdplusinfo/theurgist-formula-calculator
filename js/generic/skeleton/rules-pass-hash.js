injectHashToPassAction()

function injectHashToPassAction() {
    document.addEventListener('DOMContentLoaded', function () {
        const hash = String(window.location.hash)
        if (hash === '') {
            return
        }
        const forms = document.getElementsByTagName('form')
        for (let form of forms) {
            let action = String(form.getAttribute('action'))
            if (!action.match(/^http?s:[/][/]/) && !action.match(/#/)) {
                action += hash
                form.setAttribute('action', action)
            }
        }
    })
}