cutBrowsingOnTrialExpiration();

function cutBrowsingOnTrialExpiration() {
    var ownershipCookieName = Cookies.get('ownershipCookieName');
    if (ownershipCookieName) {
        var ownershipExpiresAt = Cookies.get(ownershipCookieName);
        if (ownershipExpiresAt) {
            return false;
        }
    }
    var trialCookieName = Cookies.get('trialCookieName');
    if (!trialCookieName) {
        return false;
    }
    var trialExpiresAt = Cookies.get(trialCookieName);
    if (typeof trialExpiresAt === 'undefined' || trialExpiresAt === '') {
        return false;
    }
    var trialExpiresAtMs = parseInt(trialExpiresAt) * 1000;
    var trialExpiredAtName = Cookies.get('trialExpiredAtCookieName');
    if (typeof trialExpiredAtName === 'undefined' || !trialExpiredAtName) {
        trialExpiredAtName = 'trial_expired_at';
    }
    setTimeout(function () {
        // have to use replace because reload is not sufficient as it ask for sending FORM again
        window.location.replace('/?' + encodeURIComponent(trialExpiredAtName) + '=' + trialExpiresAt);
    }, trialExpiresAtMs - Date.now());
    showSandGlass();
}

function showSandGlass() {
    document.addEventListener('DOMContentLoaded', function () {
        var sandGlass = document.createElement('span');
        sandGlass.classList.add('sand-glass');
        sandGlass.innerHTML = '⏳';
        sandGlass.title = 'Jen zkoušíš';
        var body = document.getElementsByTagName('body')[0];
        body.appendChild(sandGlass);
    });
}