cutBrowsingOnTrialExpiration()

function cutBrowsingOnTrialExpiration() {
    let ownershipCookieName = Cookies.get('ownershipCookieName')
    if (ownershipCookieName) {
        let ownershipExpiresAt = Cookies.get(ownershipCookieName)
        if (ownershipExpiresAt) {
            return false
        }
    }
    let trialCookieName = Cookies.get('trialCookieName')
    if (!trialCookieName) {
        return false
    }
    let trialExpiresAt = Cookies.get(trialCookieName)
    if (typeof trialExpiresAt === 'undefined' || trialExpiresAt === '') {
        return false
    }
    let trialExpiresAtMs = parseInt(trialExpiresAt) * 1000
    let trialExpiredAtName = Cookies.get('trialExpiredAtCookieName')
    if (typeof trialExpiredAtName === 'undefined' || !trialExpiredAtName) {
        trialExpiredAtName = 'trial_expired_at'
    }
    setTimeout(
        function () {
            const reloadTarget = '/?' + encodeURIComponent(trialExpiredAtName) + '=' + trialExpiresAt + String(window.location.hash)
            // have to use replace because reload is not sufficient as it ask for sending FORM again
            window.location.replace(reloadTarget)
        },
        trialExpiresAtMs - Date.now()
    )
    showSandGlass()
}

function showSandGlass() {
    document.addEventListener('DOMContentLoaded', function () {
        let sandGlass = document.createElement('span')
        sandGlass.classList.add('sand-glass')
        sandGlass.innerHTML = '⏳'
        sandGlass.title = 'Jen zkoušíš'
        let body = document.getElementsByTagName('body')[0]
        body.appendChild(sandGlass)
    })
}