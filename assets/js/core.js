function jankx_find_element_parent(element, selector) {
    var e = element, s = selector;

    const parent = e.matches(s);
    if (parent) {
        return e;
    }

    if (e.parentElement) {
        return jankx_find_element_parent(e.parentElement, s);
    }
}

HTMLElement.prototype.findParent = function(selector) {
    return jankx_find_element_parent(this, selector);
}
