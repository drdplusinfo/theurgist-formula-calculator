document.addEventListener('DOMContentLoaded', function () {
    let pageTitle = document.getElementById('pageTitle')
    if (pageTitle && pageTitle.dataset.type === 'tables') {
        // let just second level domain to be the document domain to allow access to iframes from other sub-domains
        document.domain = document.domain.replace(/^(?:[^.]+\.)*([^.]+\.[^.]+).*/, '$1')
    }
})