function jankx_create_form_data(body) {
    if (body instanceof FormData) {
        return body;
    }
    formData = new FormData();
    dataKeys = Object.keys(body);
    for (i = 0; i < dataKeys.length; i++) {
        dataKey = dataKeys[i];
        formData.append(dataKey, body[dataKey]);
    }

    return formData;
}

function camelize(str) {
    return str.replace(/(?:^\w|[A-Z]|\b\w|\s+)/g, function(match, index) {
        if (+match === 0) return "";
        return index === 0 ? match.toLowerCase() : match.toUpperCase();
    });
}

function jankx_ajax(url, method = 'GET', body = {}, options = {}, headers = {}) {
    var jankx_xhr = window.XMLHttpRequest
        ? new XMLHttpRequest() :
        new ActiveXObject("Microsoft.XMLHTTP");

    options = Object.assign({
        beforeSend: function() {},
        complete: function() {}
    }, options);

    queryString = new URLSearchParams(jankx_create_form_data(body));

    if (method.toUpperCase() === 'GET') {
        url += '?' + queryString;
    }

    header_keys = Object.keys(headers);
    if ( header_keys.length > 0) {
        for(i = 0; i < header_keys.length; i++) {
            header = header_keys[i];
            jankx_xhr.setRequestHeader(header, header_keys[header]);
        }
    }

    jankx_xhr.addEventListener('loadstart', options.beforeSend);
    jankx_xhr.onreadystatechange = function () {
        // In local files, status is 0 upon success in Mozilla Firefox
        if(jankx_xhr.readyState === XMLHttpRequest.DONE) {
            contentType = jankx_xhr.getResponseHeader("Content-Type");
            if (contentType.indexOf('application/json') > -1) {
                jankx_xhr.responseJSON = JSON.parse(jankx_xhr.response);
            }

            options.complete(jankx_xhr);
        }
    }

    jankx_xhr.open(method, url);

    method.toUpperCase() === 'GET'
        ? jankx_xhr.send()
        : jankx_xhr.send(queryString);

    return jankx_xhr;
}

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

HTMLElement.prototype.parent = function() {
    return this.parentElement;
}

HTMLElement.prototype.find = function(selector) {
    return this.querySelector(selector);
}

HTMLElement.prototype.appendHTML = function(html) {
    this.innerHTML += html;
}

HTMLElement.prototype.html = function(html) {
    this.innerHTML = html;
}

HTMLElement.prototype.removeClass = function(clsName) {
    // For modern browers
    if (this.classList) {
        this.classList.remove(clsName);
    } else {
        // This case for old IE browser
        var classes = this.className.split(" ");
        var i = classes.indexOf(clsName);
        if (i >= 0) {
            classes.splice(i, 1);
            this.className = classes.join(" ");
        }
    }
}

HTMLElement.prototype.addClass = function(clsName) {
    // For modern browers
    if (this.classList) {
        // Add class when the class is not exists
        if (!this.classList.contains(clsName)) {
            this.classList.add(clsName);
        }
    } else {
        // This case for old IE browser
        var classes = this.className.split(" ");
        var i = classes.indexOf(clsName);
        if (i < 0 ) {
            classes.push(clsName);
            this.className = classes.join(" ");
        }
    }
}

HTMLElement.prototype.hasClass = function(clsName) {
    if (this.classList) {
        return this.classList.contains(clsName);
    }

    var classes = this.className.split(" ");
    return classes.indexOf(clsName) >= 0;
}

HTMLElement.prototype.toggleClass = function(clsName) {
    if (this.hasClass(clsName)) {
        return this.removeClass(clsName);
    }
    return this.addClass(clsName);
}

// NodeList forEach polyfill for old browsers
if ('NodeList' in window && !NodeList.prototype.forEach) {
    NodeList.prototype.forEach = function (callback, thisArg) {
        thisArg = thisArg || window;
        for (var i = 0; i < this.length; i++) {
            callback.call(thisArg, this[i], i, this);
        }
    };
}


const stickyHeader = document.querySelector('.jankx-site-header.sticky-header');

if (stickyHeader) {
    window.onscroll = function() {jankxStickyHeader()};
    var sticky = stickyHeader.offsetTop + stickyHeader.clientHeight + 100;

    function jankxStickyHeader() {
        if (window.scrollY >= sticky) {
            stickyHeader.classList.add('sticky')
            document.querySelector('html').classList.add('pin-menu');
            if (document.querySelector('body.admin-bar')) {
                document.querySelector('html').classList.add('has-admin-bar');
            }
        } else {
            stickyHeader.classList.remove('sticky');
            document.querySelector('html').classList.remove('pin-menu');
            if (document.querySelector('body.admin-bar')) {
                document.querySelector('html').classList.remove('has-admin-bar');
            }
        }
    }
}

const megaMenu = document.querySelector('.sticky-header .mega-menu');
if (megaMenu) {
    megaMenu.addEventListener('mouseover', function(e){
        document.querySelector('.jankx-site-header').classList.add('menu-hover');
        window['hovermenu'] = true;
    });
    document.querySelector('body').addEventListener('mouseover', function(e){
        const target = jQuery(e.target);
        const isHoveredMenu = target.hasClass('mega-menu') || target.parents('.mega-menu').length > 0;
        if (typeof window['hovermenu'] !== 'undefined' && window['hovermenu'] && !isHoveredMenu) {
            document.querySelector('.jankx-site-header').classList.remove('menu-hover')
            delete window['hovermenu'];
        }
    });
}