(function (global, factory) {
  typeof exports === 'object' && typeof module !== 'undefined' ? factory(exports) :
  typeof define === 'function' && define.amd ? define(['exports'], factory) :
  (global = typeof globalThis !== 'undefined' ? globalThis : global || self, factory(global.VanillaSharing = {}));
}(this, (function (exports) { 'use strict';

  var WIN_PARAMS = 'scrollbars=0, resizable=1, menubar=0, left=100, top=100, width=550, height=440, toolbar=0, status=0'; // eslint-disable-line import/prefer-default-export

  function encodeParams(obj) {
    return Object.keys(obj).filter(function (k) {
      return typeof obj[k] !== 'undefined' && obj[k] !== '';
    }).map(function (k) {
      return "".concat(encodeURIComponent(k), "=").concat(encodeURIComponent(obj[k]));
    }).join('&');
  }

  function getFbFeedUrl() {
    var options = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};
    var fbAppId = options.fbAppId,
        url = options.url,
        redirectUri = options.redirectUri;

    if (!fbAppId) {
      throw new Error('fbAppId is not defined');
    }

    var params = encodeParams({
      app_id: fbAppId,
      display: 'popup',
      redirect_uri: redirectUri,
      link: url
    });
    return "https://www.facebook.com/dialog/feed?".concat(params);
  }
  function fbFeed() {
    var options = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};
    return window.open(getFbFeedUrl(options), '_blank', WIN_PARAMS);
  }

  function getFbShareUrl() {
    var options = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};
    var fbAppId = options.fbAppId,
        url = options.url,
        hashtag = options.hashtag,
        redirectUri = options.redirectUri;

    if (!fbAppId) {
      throw new Error('fbAppId is not defined');
    }

    var params = encodeParams({
      app_id: fbAppId,
      display: 'popup',
      redirect_uri: redirectUri,
      href: url,
      hashtag: hashtag
    });
    return "https://www.facebook.com/dialog/share?".concat(params);
  }
  function fbShare() {
    var options = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};
    return window.open(getFbShareUrl(options), '_blank', WIN_PARAMS);
  }

  function getFbButtonUrl() {
    var options = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};
    var url = options.url;

    if (!url) {
      throw new Error('url is not defined');
    }

    var params = encodeParams({
      kid_directed_site: '0',
      sdk: 'joey',
      u: url,
      display: 'popup',
      ref: 'plugin',
      src: 'share_button'
    });
    return "https://www.facebook.com/sharer/sharer.php?".concat(params);
  }
  function fbButton() {
    var options = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};
    return window.open(getFbButtonUrl(options), '_blank', WIN_PARAMS);
  }

  function mail() {
    var options = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};
    var url = options.url,
        title = options.title,
        description = options.description,
        image = options.image;
    var params = encodeParams({
      share_url: url,
      title: title,
      description: description,
      imageurl: image
    });
    return window.open("http://connect.mail.ru/share?".concat(params), '_blank', WIN_PARAMS);
  }

  function getEmailUrl() {
    var options = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};
    var to = options.to,
        url = options.url,
        title = options.title,
        description = options.description,
        subject = options.subject;
    var params = encodeParams({
      subject: subject,
      body: "".concat(title || '', "\r\n").concat(description || '', "\r\n").concat(url || '')
    });
    return "mailto:".concat(to || '', "?").concat(params);
  }
  function email() {
    var options = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};
    return window.location.assign(getEmailUrl(options));
  }

  function getOkUrl() {
    var options = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};
    var url = options.url,
        title = options.title,
        image = options.image;
    var params = encodeParams({
      url: url,
      title: title,
      imageUrl: image
    });
    return "https://connect.ok.ru/offer?".concat(params);
  }
  function ok() {
    var options = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};
    return window.open(getOkUrl(options), '_blank', WIN_PARAMS);
  }

  function getTelegramUrl() {
    var options = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};
    var url = options.url,
        title = options.title;
    var params = encodeParams({
      url: url,
      text: title
    });
    return "https://t.me/share/url?".concat(params);
  }
  function telegram() {
    var options = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};
    return window.open(getTelegramUrl(options), '_blank', WIN_PARAMS);
  }

  function getTwUrl() {
    var options = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};
    var title = options.title,
        url = options.url,
        _options$hashtags = options.hashtags,
        hashtags = _options$hashtags === void 0 ? [] : _options$hashtags;
    var params = encodeParams({
      text: title,
      url: url,
      hashtags: hashtags.join(',')
    });
    return "https://twitter.com/intent/tweet?".concat(params);
  }
  function tw() {
    var options = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};
    return window.open(getTwUrl(options), '_blank', WIN_PARAMS);
  }

  function reddit() {
    var options = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};
    var url = options.url,
        title = options.title;
    var params = encodeParams({
      url: url,
      title: title
    });
    return window.open("https://www.reddit.com/submit?".concat(params), '_blank', WIN_PARAMS);
  }

  function pinterest() {
    var options = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};
    var description = options.description,
        url = options.url,
        media = options.media;
    var params = encodeParams({
      url: url,
      description: description,
      media: media
    });
    return window.open("https://pinterest.com/pin/create/button/?".concat(params), '_blank', WIN_PARAMS);
  }

  function tumblr() {
    var options = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};
    var url = options.url,
        title = options.title,
        caption = options.caption,
        _options$tags = options.tags,
        tags = _options$tags === void 0 ? [] : _options$tags,
        _options$posttype = options.posttype,
        posttype = _options$posttype === void 0 ? 'link' : _options$posttype;
    var params = encodeParams({
      canonicalUrl: url,
      title: title,
      caption: caption,
      tags: tags.join(','),
      posttype: posttype
    });
    return window.open("https://www.tumblr.com/widgets/share/tool?".concat(params), '_blank', WIN_PARAMS);
  }

  function isMobileSafari() {
    return !!window.navigator.userAgent.match(/Version\/[\d.]+.*Safari/);
  }

  function mobileShare(link) {
    return isMobileSafari() ? window.open(link) : window.location.assign(link);
  }

  function getViberUrl() {
    var options = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};
    var url = options.url,
        title = options.title;

    if (!url && !title) {
      throw new Error('url and title not specified');
    }

    var params = encodeParams({
      text: [title, url].filter(function (item) {
        return item;
      }).join(' ')
    });
    return "viber://forward?".concat(params);
  }
  function viber() {
    var options = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};
    return mobileShare(getViberUrl(options));
  }

  var VK_MAX_LENGTH = 80;
  function getVkUrl() {
    var options = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};
    var url = options.url,
        image = options.image,
        isVkParse = options.isVkParse;
    var description = options.description,
        title = options.title;

    if (description && description.length > VK_MAX_LENGTH) {
      description = "".concat(description.substr(0, VK_MAX_LENGTH), "...");
    }

    if (title && title.length > VK_MAX_LENGTH) {
      title = "".concat(title.substr(0, VK_MAX_LENGTH), "...");
    }

    var params;

    if (isVkParse) {
      params = encodeParams({
        url: url
      });
    } else {
      params = encodeParams({
        url: url,
        title: title,
        description: description,
        image: image,
        noparse: true
      });
    }

    return "https://vk.com/share.php?".concat(params);
  }
  function vk() {
    var options = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};
    return window.open(getVkUrl(options), '_blank', WIN_PARAMS);
  }

  function getWhatsappUrl() {
    var options = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};
    var phone = options.phone,
        title = options.title,
        url = options.url;
    var params = encodeParams({
      text: [title, url].filter(function (item) {
        return item;
      }).join(' '),
      phone: phone
    });
    return "https://api.whatsapp.com/send?".concat(params);
  }
  function whatsapp() {
    var options = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};
    return window.open(getWhatsappUrl(options), '_blank', WIN_PARAMS);
  }

  function linkedin() {
    var options = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};
    var title = options.title,
        url = options.url,
        description = options.description;
    var params = encodeParams({
      title: title,
      summary: description,
      url: url
    });
    return window.open("https://www.linkedin.com/shareArticle?mini=true&".concat(params), '_blank', WIN_PARAMS);
  }

  function messenger() {
    var options = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};
    var fbAppId = options.fbAppId,
        url = options.url;

    if (!fbAppId) {
      throw new Error('fbAppId is not defined');
    }

    var params = encodeParams({
      app_id: fbAppId,
      link: url
    });
    return window.location.assign("fb-messenger://share?".concat(params));
  }

  function line() {
    var options = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};
    var title = options.title,
        url = options.url;

    if (!url) {
      throw new Error('url is not defined');
    }

    var params = encodeURIComponent("".concat(url));

    if (title) {
      params = "".concat(encodeURIComponent("".concat(title, " "))).concat(params);
    }

    return window.open("https://line.me/R/msg/text/?".concat(params), '_blank', WIN_PARAMS);
  }

  exports.email = email;
  exports.fbButton = fbButton;
  exports.fbFeed = fbFeed;
  exports.fbShare = fbShare;
  exports.getEmailUrl = getEmailUrl;
  exports.getFbButtonUrl = getFbButtonUrl;
  exports.getFbFeedUrl = getFbFeedUrl;
  exports.getFbShareUrl = getFbShareUrl;
  exports.getOkUrl = getOkUrl;
  exports.getTelegramUrl = getTelegramUrl;
  exports.getTwUrl = getTwUrl;
  exports.getViberUrl = getViberUrl;
  exports.getVkUrl = getVkUrl;
  exports.getWhatsappUrl = getWhatsappUrl;
  exports.line = line;
  exports.linkedin = linkedin;
  exports.mail = mail;
  exports.messenger = messenger;
  exports.ok = ok;
  exports.pinterest = pinterest;
  exports.reddit = reddit;
  exports.telegram = telegram;
  exports.tumblr = tumblr;
  exports.tw = tw;
  exports.viber = viber;
  exports.vk = vk;
  exports.whatsapp = whatsapp;

  Object.defineProperty(exports, '__esModule', { value: true });

})));
