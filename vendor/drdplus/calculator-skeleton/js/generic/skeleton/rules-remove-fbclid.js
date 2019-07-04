let param = 'fbclid'
if (location.search.indexOf(param + '=') !== -1) {
    let replace = ''
    try {
        let url = new URL(location.toString())
        url.searchParams.delete(param)
        replace = url.pathname + url.search + url.hash
    } catch (ex) {
        let regExp = new RegExp('[?&]' + param + '=.*$')
        replace = location.search.replace(regExp, '')
        replace = location.pathname + replace + location.hash
    }
    history.replaceState(null, '', replace)
}