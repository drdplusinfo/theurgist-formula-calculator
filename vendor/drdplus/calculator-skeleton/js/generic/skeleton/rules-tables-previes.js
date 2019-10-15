let removeIdsFromElement = function (element) {
    element.id = ''
    for (let i = 0, childrenLength = element.children.length; i < childrenLength; i++) {
        removeIdsFromElement(element.children[i])
    }
}

let removeAnchorsFromElement = function (element) {
    if (element.tagName === 'A') {
        element.onclick = function () {
            return false
        }
    }
    for (let i = 0, childrenLength = element.children.length; i < childrenLength; i++) {
        let child = element.children[i]
        if (child.tagName === 'A') {
            let replacement = document.createElement('span')
            replacement.innerHTML = child.innerHTML
            element.replaceChild(replacement, child)
        } else {
            removeAnchorsFromElement(element.children[i])
        }
    }
}

let showPreview = function (onElement, getElementByHrefForPreview) {
    let previewWrapped = onElement.getElementsByClassName('preview')
    let preview

    if (previewWrapped.length > 0) {
        preview = previewWrapped[0]
        preview.className = preview.className.replace('hidden', '').trim() // reveal if hidden
        return true
    }

    preview = document.createElement('div')
    preview.className = 'preview'
    let linkedTable = getElementByHrefForPreview(onElement.href)
    if (!linkedTable) {
        console.log('No linked element found for ' + onElement.href)
        return false
    }
    preview.appendChild(linkedTable)
    onElement.appendChild(preview) // add newly created
    return true
}

let addPreviewToTableLinks = function (isDesiredAnchor, getElementByHrefForPreview) {
    let anchors = document.getElementsByTagName('a')
    for (let i = 0, anchorsLength = anchors.length; i < anchorsLength; i++) {
        let anchor = anchors[i]
        if (!isDesiredAnchor(anchor)) {
            continue
        }
        // to trigger mouseout after touch and its mouseover effect
        anchor.addEventListener('touchstart', function (event) {
            event.target.dataset.blockPreview = true
        })
        anchor.addEventListener('mouseover', function (event) {
            if (event.target.dataset.blockPreview) {
                event.target.dataset.blockPreview = false
                return
            }
            showPreview(this, getElementByHrefForPreview)
        })
        anchor.addEventListener('mouseout', function () { // hide on mouse out
            let previewWrapped = this.getElementsByClassName('preview')
            if (previewWrapped.length === 0) {
                console.log('Can not find .preview for anchor ' + this.href)
                return
            }
            let tablePreview = previewWrapped[0]
            if (!tablePreview.className.includes('hidden')) {
                tablePreview.className += ' hidden'
            }
        })
    }
}

let elementParentIsTargetTable = function (element, tableId) {
    let parent = element.parentNode
    do {
        if (parent.id === tableId) {
            return true
        }
        if (!parent.parentNode) {
            return false
        }
        parent = parent.parentNode
    } while (parent.tagName !== 'TABLE' && parent.tagName !== 'BODY' && parent.tagName !== 'HTML')
    if (parent.tagName !== 'TABLE') {
        return false
    }
    let titles = parent.getElementsByClassName('title')
    for (let titlesLength = titles.length, titlesIndex = 0; titlesIndex < titlesLength; titlesIndex++) {
        if (titles[titlesIndex].id === tableId) {
            return true
        }
    }
    let headerCells = parent.getElementsByTagName('TH')
    for (let headerCellsLength = headerCells.length, headerCellsIndex = 0; headerCellsIndex < headerCellsLength; headerCellsIndex++) {
        if (headerCells[headerCellsIndex].id === tableId) {
            return true
        }
    }

    return false
}

let isAnchorToTable = function (anchor) {
    return anchor.hash !== 'undefined' && anchor.hash
        && anchor.hash.match(/#tabulka/i)
        && !elementParentIsTargetTable(anchor, anchor.hash.substring(1) /* id */)
}

let getTableByHrefForPreview = function (hrefToTable) {
    if (hrefToTable === 'undefined' || !hrefToTable) {
        console.log('Missing href to a table')
        return ''
    }
    let element
    let id = hrefToTable.replace(/[^#]*#/, '')
    if (hrefToTable.match(/[^/]*\/\//)) { // two slashes = possible external URL
        let linkHost = hrefToTable.match(/[^/]*\/\/([^/]+)\//)[1]
        let currentHost = window.location.href.match(/[^/]*\/\/([^/]+)\//)[1]
        if (linkHost !== currentHost) { // external URL
            let iFrame = document.getElementById(linkHost)
            if (!iFrame) {
                console.log('Could not find iframe by ID ' + linkHost)
                return ''
            }
            element = iFrame.contentWindow.document.getElementById(id)
        }
    }
    if (!element) {
        element = document.getElementById(id)
    }
    if (element === 'undefined' || !element) {
        console.log('Element in a table not found by ID ' + hrefToTable)
        return ''
    }
    let searchedTable = element
    while (searchedTable.tagName !== 'TABLE' && searchedTable.tagName !== 'BODY') {
        searchedTable = searchedTable.parentNode
    }
    if (searchedTable.tagName !== 'TABLE') {
        console.log('Wrapping table not found for an element with ID ' + hrefToTable)
        return ''
    }
    let table = searchedTable.cloneNode(true)
    removeIdsFromElement(table)
    removeAnchorsFromElement(table)

    return table
}

document.addEventListener('DOMContentLoaded', function () {
        // let just second level domain to be the document domain to allow access to iframes from other sub-domains
        document.domain = document.domain.replace(/^(?:[^.]+\.)*([^.]+\.[^.]+).*/, '$1')
        addPreviewToTableLinks(isAnchorToTable, getTableByHrefForPreview)
    }
)