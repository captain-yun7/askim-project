/*
 * CHEditor
 * Copyright © 1997-2025 CHCODE. All rights reserved.
 */
let GB = {
    doctype: '<!DOCTYPE html>',
    colors: ['#000000','#333333','#535353','#666666','#999999','#a0a0a0','#b5b5b5','#cccccc','#dedede','#eeeeee','#ffffff','rgba(0, 0, 0, 0)',
             '#ff0000','#ff8000','#ffff00','#80ff00','#00ff00','#00ff99','#00ffff','#0080ff','#0000ff','#7f00ff','#ff00ff','#ff007f',
             '#ffcccc','#ffe5cc','#ffffcc','#e5ffcc','#ccffcc','#ccffe5','#ccffff','#cce5ff','#ccccff','#e5ccff','#ffccff','#ffcce5',
             '#ff9999','#ffcc99','#ffff99','#ccff99','#99ff99','#99ffcc','#99ffff','#99ccff','#9999ff','#cc99ff','#ff99ff','#ff99cc',
             '#ff6666','#ffb266','#ffff66','#b2ff66','#66ff66','#66ffb2','#66ffff','#66b2ff','#6666ff','#b266ff','#ff66ff','#ff66b2',
             '#ff3333','#ff9933','#ffff33','#99ff33','#33ff33','#33ff99','#33ffff','#3399ff','#3333ff','#9933ff','#ff33ff','#ff3399',
             '#cc0000','#cc6600','#cccc00','#66cc00','#00cc00','#00cc66','#00cccc','#0066cc','#0000cc','#6600cc','#cc00cc','#cc0066',
             '#990000','#994c00','#999900','#4c9900','#009900','#00994c','#009999','#004c99','#000099','#4c0099','#990099','#99004c',
             '#660000','#663300','#666600','#336600','#006600','#006633','#006666','#003366','#000066','#330066','#660066','#660033'],
    inlineElements: [
        "A", "ABBR", "ACRONYM", "B", "BDO", "BIG", "BR", "BUTTON", "CITE", "CODE", "DFN", "EM", "I",
        "IMG", "INPUT", "KBD", "LABEL", "MAP", "OBJECT", "OUTPUT", "Q", "S", "SAMP", "SCRIPT", "SELECT",
        "SMALL", "SPAN", "STRONG", "SUB", "SUP", "SVG", "TEMPLATE", "TEXTAREA", "TIME", "TT", "U", "VAR"
    ],
    blockElements: [
        "ADDRESS", "ASIDE", "BLOCKQUOTE", "CANVAS", "DD", "DIV", "DL", "DT", "FIELDSET", "FIGCAPTION",
        "FIGURE", "FOOTER", "FORM", "H1", "H2", "H3", "H4", "H5", "H6", "HEADER", "HR", "LI", "MAIN",
        "NAV", "NOSCRIPT", "OL", "P", "PRE", "SECTION", "TABLE", "TD", "TFOOT", "TH", "UL", "VIDEO"
    ],
    lineHeightElements: [
        "ADDRESS", "BLOCKQUOTE", "DIV", "DL", "FIGCAPTION", "H1", "H2", "H3", "H4", "H5", "H6", "LI", "P",
        "PRE", "SECTION", "TD", "TH",
    ],
    textIndentElements: [
        "ADDRESS", "BLOCKQUOTE", "DIV", "FIGURE", "H1", "H2", "H3", "H4", "H5", "H6", "P", "PRE", "TABLE"
    ],
    textFormatElements: [
        "A", "ACRONYM", "ADDR", "B", "BDO", "BIG", "CITE", "CODE", "DEL", "DFN", "EM", "FONT", "I",
        "INS", "KBD", "MARK", "Q", "S", "SAMP", "SMALL", "SPAN", "STRIKE", "STRONG", "SUB", "SUP", "TIME",
        "TT", "U", "VAR"
    ],
    selfClosingElements: [
        "AREA", "BASE", "BR", "COL", "EMBED", "HR", "IMG", "INPUT", "LINK", "META", "PARAM", "SOURCE", "TRACK", "WBR"
    ],
    forceRemoveElements: [ "BLOCKQUOTE", "FIGURE", "HR", "TABLE" ],
    removeBreakElements: [ "BLOCKQUOTE", "BODY", "DIV", "FIGURE", "LI", "P", "TD" ],
    tableElements: [ "TABLE", "THEAD", "TBODY", "TFOOT", "TH", "TR", "TD" ],
    quickInsertParagraphElements: [ "BLOCKQUOTE", "CANVAS", "FIGURE", "HR", "TABLE" ],
    listElements: {
        starts: [ "DL", "OL", "UL" ], items: [ "DD", "DT", "LI" ], entire: [ "DL", "OL", "UL", "DD", "DT", "LI" ]
    },
    popupWindowTmpl: {
        Emoji: { tmpl: "emoji.html", title: "이모티콘" },
        ImageUpload: { tmpl: "image.html", title: "이미지 넣기"},
        Link: { tmpl: "hyperlink.html", title: "하이퍼링크" },
        Symbol: { tmpl: "symbol.html", title: "특수 문자" },
        Video: { tmpl: "video.html", title: "동영상 넣기" },
        CodeEdit: { tmpl: "source.html", title: "소스 편집" },
    },
    textAlign: {
        JustifyLeft: "left", JustifyCenter: "center", JustifyRight: "right", JustifyFull: "justify"
    },
    dropdown: {
        fontName: [
            "맑은 고딕", "돋움", "굴림", "바탕", "궁서체",
            "Arial", "Comic Sans MS", "Courier New", "Times New Roman", "Sans-serif"
        ],
        fontStyle: {
            FontSize: "font-size", FontName: "font-family", ForeColor: "color", BackColor: "background-color"
        },
        listStyle: {
            ordered: [
                { title: "숫자", type: "decimal" },
                { title: "영문 소문자", type: "lower-alpha" },
                { title: "영문 대문자", type: "upper-alpha" },
                { title: "로마 소문자", type: "lower-roman" },
                { title: "로마 대문자", type: "upper-roman" }
            ],
            unOrdered: [
                { title: "동그라미", type: "disc" },
                { title: "빈원", type: "circle" },
                { title: "사각형", type: "square" }
            ]
        },
        fontSize: [ 9, 10, 11, 12, 14, 16, 18, 20, 22, 24, 26, 28, 36, 48, 72 ]
            .sort((a, b) => a - b),
        formatBlock: {
            P: "기본",
            H1: "제목 1",
            H2: "제목 2",
            H3: "제목 3",
            H4: "제목 4",
            PRE: "코드",
            ADDRESS: "주소",
        },
        lineHeight: [
            { title: "한 줄 간격", value: 1 },
            { title: "120%", value: 1.2 },
            { title: "150%", value: 1.5 },
            { title: "170%", value: 1.7 },
            { title: "180%", value: 1.8 },
            { title: "두 줄 간격", value: 2 },
            { title: "250%", value: 2.5 }
        ],
        quoteBlock: [
            {
                menu: "background-color: #f4f5f6;",
                apply:"background-color: #f4f5f6; border-radius: 0.25rem; overflow: hidden; padding: 0.5em 1em; margin: 1em 0"
            },
            {
                menu: "background-color: #e8f9ff;",
                apply:"background-color: #e8f9ff; border-radius: 0.25rem; overflow: hidden; padding: 0.5em 1em; margin: 1em 0"
            },
            {
                menu: "background-color: #fbefff;",
                apply:"background-color: #fbefff; border-radius: 0.25rem; overflow: hidden; padding: 0.5em 1em; margin: 1em 0"
            },
            {
                menu: "background-color: #fef9d9;",
                apply:"background-color: #fef9d9; border-radius: 0.25rem; overflow: hidden; padding: 0.5em 1em; margin: 1em 0"
            },
            {
                menu: "background-color: #dafbe1;",
                apply:"background-color: #dafbe1; border-radius: 0.25rem; overflow: hidden; padding: 0.5em 1em; margin: 1em 0"
            },
            {
                menu: "vertical-bar",
                apply:"border-left: 5px #dee3ed solid; overflow: hidden; padding: 0.5em 1em 0.5em calc(1em - 5px); margin: 1em 0"
            }
        ],
        table: {
            maxRow: 10, maxCol: 10,
            tableStyle: "border: 1px double #b1b7c3; border-collapse: collapse; border-spacing: 0; width: 100%; table-layout: fixed; margin: 0;",
            cellStyle: "border: 1px solid #b1b7c3; padding: 0.3em 0.3em; word-break: break-word; min-width: 2em; background-color: #fff;",
        },
        menuMap: {
            FontName: "dropdownFontType",
            FontSize: "dropdownFontSize",
            ForeColor: "dropdownColor",
            BackColor: "dropdownColor",
            FormatBlock: "dropdownFormatBlock",
            QuoteBlock: "dropdownQuoteBlock",
            LineHeight: "dropdownLineHeight",
            OrderedList: "dropdownOrderedList",
            UnOrderedList: "dropdownOrderedList",
            Table: "dropdownTable",
        }
    },
    appleOSFontFamily: ["Apple SD Gothic Neo"],
    elementsStyle: {
        hr: "height: 0; overflow: visible; border: 0; margin: 0.75rem 0; border-top: 1px solid #e4e4e4; box-sizing: content-box",
        figure: {
            style: "margin: 0.7em auto; display: table; clear: both; position: relative",
            attrs: [
                { name: "contenteditable", value: false }
            ],
            align: { left: "0.7em 0", center: "0.7em auto", right: "0.7em 0 0.7em auto" },
            image: {
                style: "min-width: 100%; max-width: 100%; height: auto; display: block"
            },
            caption: {
                style: "background-color: #f5f6f7; display: table-caption; caption-side: bottom; " +
                    "font-size: 14px; padding: 0.4em 0.3em; text-align: justify; " +
                    "color: #5e636e; word-break: break-word;",
                attrs: [
                    { name: "role", value: "textbox" },
                    { name: "spellcheck", value: "false" }
                ],
            },
            picture: {
                style: "display: block"
            }
        },
        pageBreak: "border: 0; border-top: 1px #999 dotted; page-break-after: always; margin: 20px 0; box-sizing: content-box;",
    },
    escapeHTMLRegex: /[&<>"']/g,
    escapeHTMLMap: {'&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&#34;', "'": '&#39;'},
    unescapeHTMLRegex: /&(amp|lt|gt|quot|#34|#39);/g,
    unescapeHTMLMap: {'&amp;': '&', '&lt;': '<', '&gt;': '>', '&quot;': '"', '&#34;': '"', '&#39;': "'"},
    dragWindow: null,
    colorDropper: null,
    readyEditor: 0,
    browser: {},
    zeroWidthSpace: "\u200b",
    removeZeroWidthSpaceAttr: "remove-zw-space",
    removeRangeMarkerAttr: "remove",
    ioObserver: null,
    defaultOffsetIncrement: 40
};

function isUndefined(obj) {
    return obj === void 0; // obj === undefined;
}

function detectBrowser() {
    function detect(ua) {
        let iosDevice = getFirstMatch(/(ipod|iphone|ipad)/i).toLowerCase(),
            likeAndroid = /like android/i.test(ua),
            android = !likeAndroid && /android/i.test(ua),
            versionIdentifier = getFirstMatch(/version\/(\d+(\.\d+)?)/i),
            tablet = /tablet/i.test(ua),
            mobile = !tablet && /[^-]mobi/i.test(ua),
            result,
            osVersion = '',
            osMajorVersion,
            osName,
            app;

        function getFirstMatch(regex) {
            const match = ua.match(regex);
            return match && match.length > 1 && match[1] || '';
        }

        if (/opera|opr/i.test(ua)) {
            result = {
                name: 'Opera', opera: true,
                version: versionIdentifier || getFirstMatch(/(?:opera|opr)[\s/](\d+(\.\d+)?)/i)
            };
        } else if (/windows phone/i.test(ua)) {
            result = {
                name: 'Windows Phone', windowsPhone: true, msie: true,
                version: getFirstMatch(/iemobile\/(\d+(\.\d+)?)/i)
            };
        } else if (/msie|trident/i.test(ua)) {
            result = {
                name: 'Internet Explorer', msie: true, version: getFirstMatch(/(?:msie |rv:)(\d+(\.\d+)?)/i)
            };
        } else if (/edge/i.test(ua)) {
            result = {
                name: 'edge', edge: true, version: getFirstMatch(/edge\/(\d+(\.\d+)?)/i)
            };
        } else if (/chrome|crios|crmo/i.test(ua)) {
            result = {
                name: 'Chrome', chrome: true, version: getFirstMatch(/(?:chrome|crios|crmo)\/(\d+(\.\d+)?)/i)
            };
        } else if (iosDevice) {
            result = {
                name: iosDevice === 'iphone' ? 'iPhone' : iosDevice === 'ipad' ? 'iPad' : 'iPod'
            };
            if (versionIdentifier) {
                result.version = versionIdentifier;
            }
        } else if (/firefox|iceweasel/i.test(ua)) {
            result = {
                name: 'Firefox', firefox: true,
                version: getFirstMatch(/(?:firefox|iceweasel)[ /](\d+(\.\d+)?)/i)
            };
            if (/\((mobile|tablet);[^)]*rv:[\d.]+\)/i.test(ua)) {
                result.firefoxos = true;
            }
        } else if (/silk/i.test(ua)) {
            result =  {
                name: 'Amazon Silk', silk: true, version : getFirstMatch(/silk\/(\d+(\.\d+)?)/i)
            };
        } else if (android) {
            result = { name: 'Android', version: versionIdentifier };
        } else if (/(web|hpw)os/i.test(ua)) {
            result = {
                name: 'WebOS', webos: true,
                version: versionIdentifier || getFirstMatch(/w(?:eb)?osbrowser\/(\d+(\.\d+)?)/i)
            };
            if (/touchpad\//i.test(ua)) {
                result.touchpad = true;
            }
        } else if (/safari/i.test(ua)) {
            result = {
                name: 'Safari', safari: true, version: versionIdentifier
            };
        } else {
            result = {};
        }

        if (/(apple)?webkit/i.test(ua)) {
            result.name = result.name || 'Webkit';
            result.webkit = true;
            if (!result.version && versionIdentifier) {
                result.version = versionIdentifier;
            }
        } else if (!result.opera && /gecko\//i.test(ua)) {
            result.gecko = true;
            result.version = result.version || getFirstMatch(/gecko\/(\d+(\.\d+)?)/i);
            result.name = result.name || 'Gecko';
        }

        if (android || result.silk) {
            result.android = true;
        } else if (iosDevice) {
            result[iosDevice] = true;
            result.ios = true;
        }

        if (iosDevice) {
            osVersion = getFirstMatch(/os (\d+([_\s]\d+)*) like mac os x/i);
            osVersion = osVersion.replace(/[_\s]/g, '.');
        } else if (android) {
            osVersion = getFirstMatch(/android[ /-](\d+(\.\d+)*)/i);
        } else if (result.windowsPhone) {
            osVersion = getFirstMatch(/windows phone (?:os)?\s?(\d+(\.\d+)*)/i);
        }

        if (osVersion) {
            result.osversion = osVersion;
        }
        osMajorVersion = osVersion.split('.')[0];

        if (tablet || iosDevice === 'ipad' ||
            android && (osMajorVersion === 3 || osMajorVersion === 4 && !mobile) ||
            result.silk) {
            result.tablet = true;
        } else if (mobile || iosDevice === 'iphone' || iosDevice === 'ipod' || android) {
            result.mobile = true;
        }

        if (result.edge ||
            result.chrome && result.version >= 20 ||
            result.firefox && result.version >= 20.0 ||
            result.safari && result.version >= 6 ||
            result.opera && result.version >= 10.0 ||
            result.ios && result.osversion && result.osversion.split('.')[0] >= 6) {
            result.a = true;
        } else if (result.msie ||
            result.chrome && result.version < 20 ||
            result.firefox && result.version < 20.0 ||
            result.safari && result.version < 6 ||
            result.opera && result.version < 10.0 ||
            result.ios && result.osversion && result.osversion.split('.')[0] < 6) {
            result.c = true;
        } else {
            result.x = true;
        }

        if (/windows/i.test(ua)) {
            osName = 'Windows';
        } else if (/mac/i.test(ua)) {
            osName = 'MacOS';
        } else if (/x11/i.test(ua)) {
            osName = 'UNIX';
        } else if (/linux/i.test(ua)) {
            osName = 'Linux';
        } else {
            osName = 'Unknown OS';
        }
        result.osname = osName;

        if (osName === 'Windows') {
            app = getFirstMatch(/(Windows NT\s(\d+)\.(\d+))/i);
            switch (app) {
                case 'Windows NT 5.1' : result.os = 'Windows XP'; break;
                case 'Windows NT 5.2' : result.os = 'Windows 2003'; break;
                case 'Windows NT 6.0' : result.os = 'Windows Vista'; break;
                case 'Windows NT 6.1' : result.os = 'Windows 7'; break;
                case 'Windows NT 6.2' : result.os = 'Windows 8'; break;
                case 'Windows NT 6.3' : result.os = 'Windows 8.1'; break;
                case 'Windows NT 10.0' : result.os = 'Windows 10'; break;
                default : result.os = app;
            }
        }

        return result;
    }
    return detect(!isUndefined(navigator) ? window.navigator.userAgent : null);
}

function URI(uri) {
    this.scheme = null;
    this.authority = null;
    this.path = '';
    this.query = null;
    this.fragment = null;

    this.parseUri = function (uri) {
        const m = uri.match(/^(([A-Za-z][0-9A-Za-z+.-]*)(:))?((\/\/)([^/?#]*))?([^?#]*)((\?)([^#]*))?((#)(.*))?/);
        this.scheme = m[3] ? m[2] : null;
        this.authority = m[5] ? m[6] : null;
        this.path = m[7];
        this.query = m[9] ? m[10] : null;
        this.fragment = m[12] ? m[13] : null;
        return this;
    };

    this.azToString = function () {
        let result = '';
        if (this.scheme !== null) {
            result = result + this.scheme + ':';
        }
        if (this.authority !== null) {
            result = result + '//' + this.authority;
        }
        if (this.path !== null) {
            result = result + this.path;
        }
        if (this.query !== null) {
            result = result + '?' + this.query;
        }
        if (this.fragment !== null) {
            result = result + '#' + this.fragment;
        }
        return result;
    };

    this.toAbsolute = function (location) {
        const baseUri = new URI(location),
            URIAbs = this,
            target = new URI(),
            removeDotSegments = function (path) {
                let result = '', rm;
                while (path) {
                    if (path.substring(0, 3) === '../' || path.substring(0, 2) === './') {
                        path = path.replace(/^\.+/, '').substring(1);
                    } else if (path.substring(0, 3) === '/./' || path === '/.') {
                        path = '/' + path.substring(3);
                    } else if (path.substring(0, 4) === '/../' || path === '/..') {
                        path = '/' + path.substring(4);
                        result = result.replace(/\/?[^/]*$/, '');
                    } else if (path === '.' || path === '..') {
                        path = '';
                    } else {
                        rm = path.match(/^\/?[^/]*/)[0];
                        path = path.substring(rm.length);
                        result = result + rm;
                    }
                }
                return result;
            };

        if (baseUri.scheme === null) {
            return false;
        }
        if (URIAbs.scheme !== null && URIAbs.scheme.toLowerCase() === baseUri.scheme.toLowerCase()) {
            URIAbs.scheme = null;
        }

        if (URIAbs.scheme !== null) {
            target.scheme = URIAbs.scheme;
            target.authority = URIAbs.authority;
            target.path = removeDotSegments(URIAbs.path);
            target.query = URIAbs.query;
        } else {
            if (URIAbs.authority !== null) {
                target.authority = URIAbs.authority;
                target.path = removeDotSegments(URIAbs.path);
                target.query = URIAbs.query;
            } else {
                if (URIAbs.path === '') {
                    target.path = baseUri.path;
                    target.query = URIAbs.query || baseUri.query;
                } else {
                    if (URIAbs.path.substring(0, 1) === '/') {
                        target.path = removeDotSegments(URIAbs.path);
                    } else {
                        if (baseUri.authority !== null && baseUri.path === '') {
                            target.path = '/' + URIAbs.path;
                        } else {
                            target.path = baseUri.path.replace(/[^/]+$/, '') + URIAbs.path;
                        }
                        target.path = removeDotSegments(target.path);
                    }
                    target.query = URIAbs.query;
                }
                target.authority = baseUri.authority;
            }
            target.scheme = baseUri.scheme;
        }
        target.fragment = URIAbs.fragment;
        return target;
    };
    if (uri) {
        this.parseUri(uri);
    }
}

function setConfig() {
    let config = {
        allowedOnEvent      : false,
        allowPasteImage     : true,
        maxPasteImageCount  : 5,
        colorToHex          : true,
        docTitle            : "내 문서",
        editAreaMargin      : "5px 10px",
        editorBgColor       : "#ffffff",
        editorFontColor     : "#14141f",
        editorFontFamily    : `"맑은 고딕", "Apple SD Gothic Neo"`,
        editorFontWeight    : "normal",
        editorFontSize      : "14px",
        editorHeight        : "300px",
        editorPath          : "",
        editorWidth         : "100%",
        exceptedElements    : { script: true, style: true, iframe: false },
        fullHTMLSource      : false,
        imgJpegQuality      : 1,        // JPEG 사진의 퀄리티 값, 최대값 1
        imgMaxWidth         : 1024,     // 사진 최대 가로 크기, 이 크기 보다 크면 리사이징 처리, 0으로 설정하면 제한 없음.
        imgResizeMinLimit   : 120,      // 사진 리사이징의 사용자 직접 입력 값이 이 값 보다 작으면, 이 값으로 설정
        imgResizeSelected   : 800,      // 사진 리사이징의 선택 입력 폼의 기본 선택 값
        imgResizeValue      : [240, 320, 640, 800, 1024, -1], // -1 = 사용자 직접 입력
        imgSetAttrAlt       : true,
        imgUploadNumber     : 4,
        imgWaterMarkAlpha   : 1,        // 워터마크 불투명도 (최대값 1)
        imgWaterMarkUrl     : "",       // 워터마크 이미지 URL (예: 'http://udomain.com/cheditor/icons/watermark.png')
        includeHostname     : true,
        lineHeight          : 1.7,
        linkTargetAttr      : "_blank", // 하이퍼링크(A 태그) target 속성
        makeThumbnail       : false,    // 사진의 썸네일 이미지 생성, 가로 크기는 thumbnailWidth 값, 세로는 자동 계산
        thumbnailWidth      : 240,
        showTagSelector     : true,
        showToolbarTooltip  : true,
        tabIndent           : 2,
        tabIndex            : 0,
        uploadScript        : 'imageUpload/upload.php',

        // 버튼 사용 유무
        useSource           : true,
        usePrint            : true,
        useNewDocument      : true,
        useUndo             : true,
        useRedo             : true,
        useCopy             : true,
        useCut              : true,
        useSelectAll        : true,
        useStrikethrough    : true,
        useUnderline        : true,
        useItalic           : true,
        useSuperscript      : false,
        useSubscript        : false,
        useJustifyLeft      : true,
        useJustifyCenter    : true,
        useJustifyRight     : true,
        useJustifyFull      : true,
        useBold             : true,
        useOrderedList      : true,
        useUnOrderedList    : true,
        useOutdent          : true,
        useIndent           : true,
        useFontName         : true,
        useFormatBlock      : true,
        useFontSize         : true,
        useFontSizeUp       : true,
        useFontSizeDown     : true,
        useLineHeight       : true,
        useBackColor        : true,
        useForeColor        : true,
        useRemoveFormat     : true,
        useClearTag         : true,
        useSymbol           : true,
        useLink             : true,
        useUnLink           : true,
        useVideo            : true,
        useImage            : true,
        useEmoji            : true,
        useHR               : true,
        useTable            : true,
        useModifyTable      : true,
        useMap              : false,
        useQuoteBlock       : true,
        useFullScreen       : true,
        usePageBreak        : false
    },
    base, elem, i, editorURI, locationAbs;

    if (!config.editorPath) {
        base = location.href;
        elem = document.getElementsByTagName('script');
        for (i = 0; i < elem.length; i++) {
            if (elem[i].src) {
                editorURI = new URI(elem[i].src);
                if (/\/cheditor\.js$/.test(editorURI.path)) {
                    locationAbs = editorURI.toAbsolute(base).azToString();
                    delete locationAbs.query;
                    delete locationAbs.fragment;
                    config.editorPath = locationAbs.replace(/[^/]+$/, '');
                }
            }
        }
        if (!config.editorPath) {
            throw "CHEditor 경로가 바르지 않습니다.\n'config.editorPath'를 설정하여 주십시오.";
        }
    }

    this.storedSelections = [];
    this.keyPressStoredSelections = [];
    this.images = [];
    this.cheditor = {};
    this.initEventHandler = false;
    this.editarea = null;
    this.toolbar = {};
    this.dropdown = {};
    this.pictureBogusCaret = null;
    this.pictureResizer = null;
    this.popper = null;
    this.modalZIndex = 1001;
    this.config = config;
    this.inputForm = 'textAreaId';
    this.range = null;
    // this.undoStack = [];
    this.tempTimer = null;
    this.cheditor.tabSpaces = '';
    this.cheditor.modifyState = false;
    this.cheditor.tabSpaces = new Array(this.config.tabIndent + 1).join(' ');
	this.URI = URI;
}

function cheditorPolyfill() {
    if (typeof Element.prototype.replaceChildren !== 'function') {
        const replaceChildren = function (...children) {
            const { childNodes } = this;
            while (childNodes.length) {
                childNodes[0].remove();
            }
            this.append(...children);
        };
        Document.prototype.replaceChildren = replaceChildren;
        DocumentFragment.prototype.replaceChildren = replaceChildren;
        Element.prototype.replaceChildren = replaceChildren;
    }
}

function cheditor() {
    this.toType = (function (global) {
        const toString = cheditor.prototype.toString, regExp = /^.*\s(\w+).*$/;
        return function (obj) {
            if (obj === global) {
                return 'global';
            }
            return toString.call(obj).replace(regExp, '$1').toLowerCase();
        };
    }(this));

    this.isUndefined = isUndefined;
    GB.browser = this.browser = detectBrowser();

    if (this.browser.msie) {
        alert('마이크로소프트 인터넷 익스플로러 브라우저는 지원하지 않습니다.');
        return null;
    }

    try {
        cheditorPolyfill();
        setConfig.call(this);
        this.config.browser = this.browser;
        this.cheditor.id = this.isUndefined(GB.readyEditor) ? 1 : GB.readyEditor++;
        // this.undoManager = new GB.UndoManager();
    } catch (e) {
        alert(e.toString());
        return null;
    }

    return this;
}

cheditor.prototype = {
    //----------------------------------------------------------------
    prependContents: function (contents) {
        this.updateContents(contents, 'afterbegin', 'firstChild', 'setEndBefore', 'setStartBefore');
    },

    appendContents: function (contents) {
        this.updateContents(contents, 'beforeend', 'lastChild', 'setStartAfter', 'setEndAfter');
    },

    insertContents: function (contents) {
        const htmlContent = this.normalizeContents(contents);
        this.insertHtmlAtSelection(htmlContent);
        this.editareaFocus();
        this.updateToolbar();
    },

    replaceContents: function (contents) {
        this.loadContents(contents);
    },

    loadContents: function (contents) {
        this.editareaFocus();
        this.emptyChildren();
        this.body.insertAdjacentHTML('afterbegin', this.validateAndParseContents(contents));
        this.initContentsEvent();
        this.placeCaretAt(this.body.firstChild, true);
        this.updateToolbar();
    },

    updateContents: function (contents, insertPosition, childSelector, rangeStart, rangeEnd) {
        const range = this.createRange();
        const referenceChild = this.body[childSelector];

        this.body.insertAdjacentHTML(insertPosition, this.validateAndParseContents(contents));

        if (!referenceChild) {
            range.selectNodeContents(this.body);
        } else {
            range[rangeStart](referenceChild);
            range[rangeEnd](this.body[childSelector]);
        }

        const selection = this.getSelection();
        selection.empty();
        selection.addRange(range);

        this.clearHelperNodes();
        this.updateToolbar();

        const alignTo = childSelector === 'firstChild' ? "start" : "end";
        this.body.scrollIntoView({ block: alignTo, behavior: "smooth" });
        this.editareaFocus();
    },

    initContentsEvent: function () {
        const figures = this.body.querySelectorAll('figure');

        if (!figures.length) {
            return;
        }

        figures.forEach((figure) => {
            const table = figure.querySelector('table');
            if (table) {
                if (!table.querySelector('colgroup')) {
                    const columnLength = table.rows[0].cells.length;
                    table.prepend(this.createTableColumnGroup(columnLength));
                }
                this.createTableEventHandler(table);
            }

            const figCaptionElem = figure.querySelector('figcaption');
            if (figCaptionElem) {
                this.createFigCaptionEventHandler(figure, figCaptionElem, this.getSelection());
            }
        });
    },

    normalizeContents: function (contents) {
        return this.removeNls(this.trimSpace(contents)).replace(/\s*(<\/)/g, '$1');
    },

    validateAndParseContents: function (contents) {
        const normalizedHTML = this.normalizeContents(contents);
        const shadow = this.doc.createElement('temp-shadow');

        shadow.insertAdjacentHTML('afterbegin', normalizedHTML);

        if (!shadow.hasChildNodes()) {
            shadow.append(this.createEmptyParagraph());
        } else {
            const walker = this.doc.createTreeWalker(
                shadow,
                NodeFilter.SHOW_ELEMENT | NodeFilter.SHOW_TEXT,
                {
                    acceptNode: (node) => {
                        if (node.nodeType === Node.ELEMENT_NODE &&
                            this.config.exceptedElements[node.nodeName.toLowerCase()]) {
                            node.remove();
                            return NodeFilter.FILTER_REJECT;
                        }
                        return NodeFilter.FILTER_ACCEPT;
                    }
                }
            );

            let currNode = walker.nextNode();
            let currPara = null;

            while (currNode) {
                const isTextOrInline = currNode.nodeType === Node.TEXT_NODE ||
                    GB.inlineElements.includes(currNode.nodeName);

                if (isTextOrInline) {
                    if (!currPara) {
                        currPara = this.doc.createElement('p');
                        currNode.before(currPara);
                    }
                    currPara.append(currNode);
                    walker.currentNode = currPara;
                } else {
                    currPara = null;
                }

                currNode = walker.nextSibling();
            }
        }

        const CONTENTEDITABLE = 'contenteditable';
        const CONTENTEDITABLE_TRUE = 'true';
        const CONTENTEDITABLE_FALSE = 'false';

        const makeFigcaptionEditable = figure => {
            if (!figure.querySelector('img')) {
                return false;
            }
            figure.setAttribute(CONTENTEDITABLE, CONTENTEDITABLE_FALSE);
            const figCaption = figure.querySelector('figcaption');
            if (figCaption) {
                figCaption.setAttribute(CONTENTEDITABLE, CONTENTEDITABLE_TRUE);
                return true;
            }
            return false;
        };

        const makeTableCellsEditable = figure => {
            const table = figure.querySelector('table');
            let isEditable = false;

            if (table) {
                const tableCells = table.querySelectorAll('td, th');
                tableCells.forEach(cell => {
                    cell.setAttribute(CONTENTEDITABLE, CONTENTEDITABLE_TRUE);
                    if (!cell.hasAttribute('style')) {
                        cell.setAttribute('style', GB.dropdown.table.cellStyle);
                    }
                    isEditable = true;
                });

                if (isEditable) {
                    if (!table.hasAttribute('style')) {
                        table.setAttribute('style', GB.dropdown.table.tableStyle);
                    }
                }
            }

            return isEditable;
        };

        const figureNodes = shadow.querySelectorAll('figure');
        figureNodes.forEach(figure => {
            if (!makeFigcaptionEditable(figure)) {
                makeTableCellsEditable(figure);
            }
        });

        return shadow.getHTML();
    },

    setFolderPath : function () {
        const TRAILING_SLASH = '/';
        const path = this.config.editorPath;
        const basePath = path.endsWith(TRAILING_SLASH)
            ? path
            : path + TRAILING_SLASH;
        const paths = {
            iconPath: 'icons',
            cssPath: 'css',
            popupPath: 'popup',
            uploadScript: this.config.uploadScript,
        };

        for (let [key, folder] of Object.entries(paths)) {
            if (folder.endsWith(TRAILING_SLASH)) {
                folder = folder.substring(0, folder.length - 1);
            }
            this.config[key] = basePath + folder;
        }
    },

    initializeInputForm : function () {
        const textarea = document.getElementById(this.inputForm);
        if (!textarea) {
            throw `ID가 ${this.inputForm}인 textarea 요소를 찾을 수 없습니다.`;
        }
        textarea.style.display = 'none';
        this.cheditor.inputForm = textarea;
    },

    setDesignMode: function (designMode = true, doc = this.doc) {
        doc.designMode = designMode ? 'on' : 'off';
    },

    createDocument: function () {
        const createStyleElement = () => document.createElement('style');
        const newDocument = document.implementation.createHTMLDocument(this.config.docTitle);

        newDocument.head.append(createStyleElement());
        this.doc.documentElement.replaceWith(this.doc.importNode(newDocument.documentElement, true));
        this.body = this.doc.body;
    },

    getWindowHandle: function (iframe) {
        return iframe.contentWindow;
    },

    createDocumentFragment: function () {
        return this.doc.createDocumentFragment();
    },

    createCustomElements: function () {
        if (!this.isUndefined(customElements.get('range-marker'))) {
            return;
        }

        customElements.define(
            "range-marker",
            class extends HTMLElement {
                static get observedAttributes() {
                    return ["class"];
                }
                constructor() {
                    super();
                    this.attachShadow({ mode: "open" }).append('\ufeff');
                }
                connectedCallback() {
                    if (this.parentNode.nodeName !== 'BODY') {
                        this.parentNode.normalize();
                    }
                }
                adoptedCallback() {
                    this.before(GB.zeroWidthSpace);
                    this.after(GB.zeroWidthSpace);
                }
                attributeChangedCallback(attrName, oldValue, newValue) {
                    if (newValue === GB.removeZeroWidthSpaceAttr) {
                        const prevNode = this.previousSibling;
                        const nextNode = this.nextSibling;

                        [prevNode, nextNode].forEach(node => {
                            if (node?.textContent) {
                                node.textContent = node.textContent.replaceAll(GB.zeroWidthSpace, '');
                            }
                        });
                        return;
                    }
                    if (newValue === GB.removeRangeMarkerAttr) {
                        this.remove();
                    }
                }
                create(range, toStart) {
                    const node = document.createElement('span');
                    node.style.display = 'none';
                    node.append(toStart ? 'anchorNode' : 'focusNode');
                    this.shadowRoot.append(node);
                    range.collapse(toStart);
                    range.insertNode(this);
                }
            }
        );
        customElements.define(
            "temp-shadow",
            class extends HTMLElement {
                constructor() {
                    super();
                    this.attachShadow({ mode: "open" });
                }
                connectedCallback() {
                }
            }
        );
    },

    initCustomElements: function () {
        this.createCustomElements();
    },

    resetEditorDocument: async function () {
        if (this.isUndefined(this.cheditor.editarea)) {
            return false;
        }
        try {
            this.editarea = this.getWindowHandle(this.cheditor.editarea);
            this.doc = this.cheditor.editarea.contentDocument;
            await this.documentOpen();
            this.initCustomElements();
            return true;
        } catch (error) {
            console.error("에디터 문서 초기화 실패:", error);
            this.alert(`에디터 문서 초기화 오류: ${error.message}`);
            return false;
        }
    },

    initEditorFocus: function () {
        this.setEditorVisibility();
        this.editareaFocus();
        this.initBodyContent();
        this.finalizeEditorSetup();
    },

    initBodyContent: function () {
        if (this.body && this.body.hasChildNodes()) {
            this.placeCaretAt(this.body.childNodes[0], true);
            return;
        }
        this.initDefaultParagraphSeparator();
    },

    finalizeEditorSetup: function () {
        this.updateToolbar();
        this.setEditorMode('rich');
        this.loadExScript();
    },

    documentOpen: async function () {
        const openHandler = () => {
            this.createDocument();
            this.setDocumentBodyStyle();
            this.setDesignMode(true);
            this.loadContents(this.cheditor.inputForm.value);
        };

        if (this.doc.readyState !== 'complete') {
            this.doc.open(); this.doc.write('<!DOCTYPE html>'); this.doc.close();
            await new Promise((resolve, reject) => {
                this.addEvent(this.doc, 'readystatechange', evt => {
                    if (evt.target.readyState === 'complete') {
                        this.removeEvent(this.doc, 'readystatechange', evt);
                        resolve('loaded');
                    } else {
                        reject('document initialization error');
                    }
                });
            }).then(result => {
                if (result === 'loaded') {
                    openHandler();
                }
            }).catch(err => {
                console.error(err);
            });
        } else {
            openHandler();
        }
    },

    resetDocumentBody: function () {
        this.body.replaceWith(this.doc.createElement('body'));
        this.setDocumentBodyAttributes();
    },

    setDocumentBodyAttributes: function () {
        this.body.setAttribute('spellcheck', 'false');
    },

    setEditorVisibility: function (visible = true) {
        this.cheditor.container.style.visibility = visible ? 'visible' : 'hidden';
    },

    setEditorMode: function (mode) {
        this.cheditor.mode = mode;
    },

    loadExScript: function () {
        if (!Object.hasOwn(HTMLElement.prototype, 'popover')) {
            const script = this.doc.createElement('script');
            script.type = 'text/javascript';
            script.src = `${this.config.popupPath}/js/popover-polyfill.min.js`;
            this.doc.head.appendChild(script);
        }
    },

    generateBodyStyle: function () {
        return `font-size: ${this.config.editorFontSize};
            font-family: ${this.config.editorFontFamily};
            font-weight: ${this.config.editorFontWeight};
            color: ${this.config.editorFontColor};
            line-height: ${this.config.lineHeight};`;
    },

    addStyleRules: function (sheet, styles) {
        styles.forEach((rule, idx) => sheet.insertRule(rule, idx));
    },

    setDocumentBodyStyle: function () {
        const sheet = this.doc.styleSheets[0];
        const bodyStyle = this.generateBodyStyle().replace(/\s+/g, ' ');

        this.cheditor.container.style.backgroundColor = this.config.editorBgColor;
        this.setDefaultCss({css: 'ui.css', doc: window.document});
        this.setDefaultCss({css: 'editarea.css', doc: this.doc});
        this.addStyleRules(sheet, [
            `body, table {${bodyStyle}}`,
            `br.is-bogus {visibility: hidden}`
        ]);

        if (this.browser.osname === 'MacOS') {
            this.body.classList.add('macos');
            if (GB.readyEditor === 1) {
                GB.dropdown.fontName.unshift(...GB.appleOSFontFamily);
            }
        }

        this.setDefaultParagraphSeparator();
        this.setDocumentBodyAttributes();
    },

    initDefaultParagraphSeparator: function () {
        const para = this.createEmptyParagraph();
        if (this.body.firstChild?.nodeName === 'BR') {
            this.body.firstChild.remove();
        }
        if (!this.body.hasChildNodes()) {
            this.body.appendChild(para);
            this.placeCaretAt(para, true);
        }
    },

    createHTMLDocument: function (title) {
        return this.doc.implementation.createHTMLDocument(title || this.config.docTitle);
    },

    processPasteImageItem: async function (file, bogusWindow) {
        const maxImageWidth = this.config.imgMaxWidth;
        const createAndDecodeImage = async (file) => {
            const urlManager = window.URL || window.webkitURL;
            const image = new Image();
            image.src = urlManager.createObjectURL(file);
            await image.decode();
            urlManager.revokeObjectURL(image.src);
            return image;
        };
        const calculateResizedDimensions = (width, height) => {
            if (width > maxImageWidth) {
                const newWidth = maxImageWidth;
                const newHeight = Math.round(height * (newWidth / width));
                return { width: newWidth, height: newHeight };
            }
            return { width, height };
        };

        const image = await createAndDecodeImage(file);
        const reader = new FileReader();
        reader.readAsArrayBuffer(file);

        reader.onload = () => {
            const { width, height } = calculateResizedDimensions(image.width, image.height);
            const sendMessage = {
                type: 'file',
                value: {
                    bitmap: reader.result,
                    type: file.type,
                    name: file.name,
                    width,
                    height,
                }
            };
            this.postMessage(bogusWindow, sendMessage);
        };
    },

    handlePaste: function (evt) {
        this.stopEvent(evt);
        const MIME_TYPE_FILES = 'Files';
        const dataTransfer = evt.clipboardData || window.clipboardData;

        if (dataTransfer.types.includes(MIME_TYPE_FILES)) {
            if (!this.config.allowPasteImage) return;
            this.handleFilePaste(dataTransfer);
        } else {
            this.handleNonFilePaste(dataTransfer);
        }
    },

    handleFilePaste: function (dataTransfer) {
        const SUPPORTED_IMAGE_TYPES = ['png', 'jpeg', 'gif', 'webp'];
        const MIME_TYPE_IMAGE = 'image';
        const existingBogusWindow = this.doc.getElementById('bogus-window');
        if (existingBogusWindow) existingBogusWindow.remove();

        let items = [];
        const images = [];
        const bogusWindow = this.addBogusWindow();

        const insertDataHandler = (evt) => {
            const data = evt.data.value;
            if (data) {
                images.push(data);
                if (!items.length) {
                    this.doInsertImage({ images });
                    this.cleanUpBogusWindow(bogusWindow, insertDataHandler);
                    return;
                }
                void this.processPasteImageItem(items.shift(), bogusWindow);
            } else {
                this.cleanUpBogusWindow(bogusWindow, insertDataHandler);
            }
        };

        this.addEvent(this.editarea, 'message', insertDataHandler);

        for (const item of dataTransfer.items) {
            const [type, subtype] = item.type.split('/');
            if (type === MIME_TYPE_IMAGE && SUPPORTED_IMAGE_TYPES.includes(subtype)) {
                if (items.length >= this.config.maxPasteImageCount) break;
                items.push(item.getAsFile());
            }
        }

        this.addEvent(bogusWindow, 'load', () => {
            if (items.length) {
                this.postMessage(bogusWindow, { type: 'init', value: this.config });
                void this.processPasteImageItem(items.shift(), bogusWindow);
            }
        });
    },

    handleNonFilePaste: function (dataTransfer) {
        const HTML_CONTENT_TYPE = 'text/html';
        const PLAIN_CONTENT_TYPE = 'text/plain';
        const contentType = dataTransfer.types.includes(HTML_CONTENT_TYPE)
            ? HTML_CONTENT_TYPE
            : PLAIN_CONTENT_TYPE;
        const content = dataTransfer.getData(contentType);
        if (content) {
            this.getPasteFragment(content, contentType);
        }
    },

    addBogusWindow: function () {
        const bogusWindow = this.doc.createElement('iframe');
        bogusWindow.style.display = 'none';
        bogusWindow.id = 'bogus-window';
        bogusWindow.src = `${this.config.popupPath}/paste-image.html`;
        this.body.append(bogusWindow);
        return bogusWindow;
    },

    cleanUpBogusWindow: function (bogusWindow, handler) {
        bogusWindow.remove();
        this.removeEvent(this.editarea, 'message', handler);
    },

    sanitizePasteFragment: function (root, msoList) {
        const LIST_TYPES_BY_LEVEL = {
            1: 'decimal',
            2: 'upper-alpha',
            3: 'lower-alpha',
            4: 'upper-roman',
            5: 'lower-roman',
            6: 'upper-alpha',
            7: 'upper-roman',
            8: 'lower-roman',
            9: 'disc',
            10: 'circle' },
            DEFAULT_LIST_STYLE = 'square',
            walker = this.doc.createTreeWalker(
                root,
                NodeFilter.SHOW_ELEMENT|NodeFilter.SHOW_COMMENT,
                {
                    acceptNode() {
                        return NodeFilter.FILTER_ACCEPT;
                    }
                }
            ),
            getListType = (level) => LIST_TYPES_BY_LEVEL[level] || DEFAULT_LIST_STYLE,
            LIST_START = 'ol',
            LIST_ITEM = 'li';

        let currNode,
            listLevel = 0,
            beforeList = [],
            i = 0, idx,
            omitOl, omitLi, ol, li,
            bRoot = 0;

        root.querySelectorAll('style, script, meta').forEach(el => el.remove());

        currNode = walker.nextNode();
        if (currNode?.nodeName === 'BLOCKQUOTE') {
            const firstChild = currNode.firstChild;
            currNode.before(firstChild);
            currNode.remove();
            currNode = firstChild;
            walker.currentNode = currNode;
        }

        while (currNode) {
            if (currNode.nodeType === Node.COMMENT_NODE) {
                walker.previousNode();
                currNode.remove();
                currNode = walker.currentNode;
            } else if (msoList) {
                if (/P|LI|DIV/.test(currNode.nodeName)) {
                    const css = currNode.getAttribute('style')?.split(';');
                    listLevel = 0;
                    if (css) {
                        css.forEach(function (val) {
                            currNode.removeAttribute('style');
                            const attr = val.split(':');
                            if (attr[0] === 'mso-list') {
                                const found = attr[1].match(/level(\d+)/);
                                if (found) {
                                    listLevel = Number(found[1]);
                                }
                            }
                        });
                    }

                    if (listLevel > 0) {
                        li = this.doc.createElement(LIST_ITEM);
                        idx = listLevel === 1 ? 0 : listLevel - 1;

                        if (bRoot < listLevel) {
                            ol = this.doc.createElement(LIST_START);
                            ol.append(li);
                            currNode.before(ol);
                            li.append(currNode);

                            if (idx > 0 && beforeList[idx-1]) {
                                beforeList[idx-1].append(ol);
                            } else {
                                omitOl = null;
                                for (i = 0; i < idx; i++) {
                                    omitOl = this.doc.createElement(LIST_START);
                                    omitLi = this.doc.createElement(LIST_ITEM);
                                    omitLi.style.listStyleType = 'none';
                                    omitOl.append(omitLi);
                                    omitOl.style.listStyleType = getListType(i+1);

                                    if (i === 0) {
                                        ol.before(omitOl);
                                    } else {
                                        beforeList[i-1].append(omitOl);
                                    }
                                    beforeList[i] = omitLi;
                                }
                                if (omitOl) {
                                    omitOl.append(ol);
                                }
                            }
                            ol.style.listStyleType = getListType(listLevel);
                            beforeList[idx] = li;
                        } else {
                            if (beforeList[idx]) {
                                li.append(currNode);
                                beforeList[idx].append(li);
                                beforeList[idx] = li;
                            } else {
                                ol = this.doc.createElement(LIST_START);
                                ol.style.listStyleType = getListType(listLevel);
                                ol.append(li);
                                currNode.before(ol);
                                li.append(currNode);
                                beforeList[idx] = ol;
                            }
                        }

                        bRoot = listLevel;

                        while (currNode.firstChild) {
                            currNode.before(currNode.firstChild);
                        }
                    } else {
                        beforeList = [];
                    }
                }
            } else if (currNode.nodeName === 'DIV') {
                while (currNode.childNodes.length === 1 && currNode.firstChild.nodeName === 'DIV') {
                    const nextNode = currNode.firstChild;
                    currNode.before(nextNode);
                    currNode.remove();
                    currNode = nextNode;
                    walker.currentNode = currNode;
                }

                if (['TEMP-SHADOW', 'P'].includes(currNode.parentNode.nodeName)) {
                    const range = this.doc.createRange();
                    range.selectNodeContents(currNode);
                    const para = this.doc.createElement('p');
                    para.append(range.extractContents());
                    currNode.before(para);
                    currNode.remove();
                    walker.currentNode = currNode = para;
                }
            }

            if (!['IMG', 'FIGURE', 'FIGCAPTION', 'PICTURE'].includes(currNode.nodeName)) {
                this.removeAttributes(currNode);
            }

            const css = this.cssProperties(currNode);
            if (css) {
                css.forEach(attr => {
                    switch (attr.name) {
                        case 'color':
                        case 'background-color':
                        case 'width':
                            if (attr.value !== 'initial' && attr.value !== 'inherit') {
                                currNode.style[attr.name] = attr.value;
                            }
                            break;
                        default:
                            if (attr.name.match(/border-|padding-/)) {
                                currNode.style[attr.name] = attr.value;
                            }
                    }
                });
            }

            if (currNode?.nodeName === 'SPAN' && !currNode.hasAttribute('style')) {
                const nextNode = walker.nextNode();
                const range = this.doc.createRange();
                range.selectNodeContents(currNode);
                currNode.before(range.extractContents());
                currNode.remove();
                currNode = nextNode;
            } else {
                currNode?.normalize();
                currNode = walker.nextNode();
            }
        }
    },

    getPasteFragment: function (content, contentType) {
        if (contentType === 'text/plain') {
            content = content.trim().replace(/\r?\n/g, '<br>');
            if (content.length) {
                const lines = content.split('<br>');
                const clip = this.doc.createElement('temp-shadow');
                lines.forEach(line => {
                    const para = this.doc.createElement('p');
                    para.append(line || '\u00a0');
                    clip.append(para);
                    }
                );
                this.queryCommand('insertHTML', this.getInnerHTML(clip));
            }
            return;
        }

        const startTag = '<!--StartFragment-->';
        const endTag = '<!--EndFragment-->';
        let startPos = content.indexOf(startTag);
        let endPos = content.indexOf(endTag);

        startPos = startPos === -1 ? 0 : startPos + startTag.length;
        endPos = endPos === -1 ? content.length : endPos;

        content = content.substring(startPos, endPos)
            .trim()
            .replace(/<!(--)?\[if\s(![^\]]+?)](--)?>([\s\S]*?)<!(--)?\[endif](--)?>/igm, '')
            .replace(/<\/?[a-z]+?:[^>]*>/g, '');

        if (content.length === 0) {
            return;
        }

        const isMso = ['schemas-microsoft-com:office', 'mso-list:']
            .some(marker => content.includes(marker));

        const clip = this.doc.createElement('temp-shadow');
        clip.insertAdjacentHTML('afterbegin', content);

        let hasFigcaptionNodes = false;

        if (clip.hasChildNodes()) {
            clip.querySelectorAll('figure').forEach(elem => {
                const image = elem.querySelector('img');
                if (image && image.src) {
                    const width = image.getAttribute('width') || 'auto';
                    const height = image.getAttribute('height') || 'auto';
                    const imageInfo = {
                        images: [{fileUrl: image.src, width: width, height: height, alt: ''}]
                    };
                    const shadow = this.processImage(imageInfo);
                    if (shadow) {
                        const figure = shadow.firstChild;
                        if (elem.querySelector('figcaption')) {
                            const figCaptionElem = this.createFigCaptionElement();
                            figCaptionElem.append(elem.querySelector('figcaption').textContent.trim());
                            figure.append(figCaptionElem);
                            hasFigcaptionNodes = true;
                        }
                        elem.before(figure);
                    }
                    elem.remove();
                }
            });

            this.sanitizePasteFragment(clip, isMso);
            const html = this.removeEmptyTags(this.getInnerHTML(clip));

            clip.replaceChildren();
            clip.insertAdjacentHTML('afterbegin', html);
            clip.querySelectorAll('*').forEach(elem => {
                const tagName = `</${elem.tagName}>`;
                if (elem.outerHTML.slice(-tagName.length).toUpperCase() === tagName &&
                    !elem.hasChildNodes()) {
                    elem.remove();
                }
            });

            this.queryCommand('insertHTML', this.getInnerHTML(clip));

            if (hasFigcaptionNodes) {
                const nodes = this.doc.querySelectorAll('figure figcaption');
                nodes.forEach(node => {
                    if (!node.hasAttribute('contenteditable')) {
                        node.setAttribute('contenteditable', 'true');
                        this.createFigCaptionEventHandler(node.closest('figure'), node, this.getSelection());
                    }
                });
            }
        }
    },

    editareaFocus: function () {
        if (!this.doc.hasFocus()) {
            this.editarea.focus();
        }
    },

    editareaResizeStart: function (evt) {
        const editWrapper = this.cheditor.editWrapper;
        const container = this.cheditor.container;
        const backdrop = document.createElement('div');
        let editWrapperHeight = parseInt(editWrapper.style.height, 10);
        let mouseY = evt.pageY;

        this.stopEvent(evt);
        if (isNaN(editWrapperHeight)) {
            editWrapperHeight = 0;
        }

        const mousemove = (evt) => {
            let posY = evt.pageY - mouseY;
            let height = editWrapperHeight + posY;

            if (height < 1) {
                this.stopEvent(evt);
                return;
            }

            this.config.editorHeight = editWrapper.style.height = height + 'px';
        };
        const mouseup = (evt) => {
            this.stopEvent(evt);
            this.removeEvent(document, 'mouseup', mouseup);
            this.removeEvent(document, 'mousemove', mousemove);
            backdrop.remove();
            this.editareaFocus();
        };

        backdrop.className = 'cheditor-modal-drag-window';
        backdrop.style.cursor = 'ns-resize';
        container.appendChild(backdrop);

        this.addEvent(document, 'mousemove', mousemove);
        this.addEvent(document, 'mouseup', mouseup);
    },

    initTemplate : function () {
        this.loadTemplate();
        // const event = document.createEvent('Event');
        // event.initEvent(this.cheditor.id, true, true);
        // document.dispatchEvent(event);
    },

    toolbarDropdownChecked: function (button, checked) {
        button.checked = checked;
        button.elem.classList.toggle('checked', checked);
    },

    toolbarButtonChecked: function (button, active) {
        button.checked = active;
        button.elem.classList.toggle('active', active);
    },

    toolbarSetDisabled: function (button, disabled) {
        button.elem.classList.toggle('disabled', disabled);
        this.toolbar[button.name].disabled = disabled;
        return disabled;
    },

    colorConvert: function (color, which, opacity) {
        const colorDefs = [
            {
                re: /^rgb\((\d{1,3}),\s*(\d{1,3}),\s*(\d{1,3})\)$/,
                process : function (bits) {
                    return [
                        parseInt(bits[1], 10),
                        parseInt(bits[2], 10),
                        parseInt(bits[3], 10),
                        1
                    ];
                }
            }, {
                re : /^rgba\((\d{1,3}),\s*(\d{1,3}),\s*(\d{1,3}),\s*(\d+(?:\.\d+)?|\.\d+)\s*\)/,
                process : function (bits) {
                    return [
                        parseInt(bits[1], 10),
                        parseInt(bits[2], 10),
                        parseInt(bits[3], 10),
                        parseFloat(bits[4])
                    ];
                }
            }, {
                re: /^([0-9a-fA-F]{2})([0-9a-fA-F]{2})([0-9a-fA-F]{2})$/,
                process : function (bits) {
                    return [
                        parseInt(bits[1], 16),
                        parseInt(bits[2], 16),
                        parseInt(bits[3], 16),
                        1
                    ];
                }
            }, {
                re: /^([0-9a-fA-F])([0-9a-fA-F])([0-9a-fA-F])$/,
                process : function (bits) {
                    return [
                        parseInt((bits[1] * 2).toString(), 16),
                        parseInt((bits[2] * 2).toString(), 16),
                        parseInt((bits[3] * 2).toString(), 16),
                        1
                    ];
                }
            }
        ];
        let r = null, g = null, b = null, a = null,
            i, re, processor, bits, channels, min, result = null;

        if (!which) {
            which = 'rgba';
        }

        color = color.replace(/^\s*#|\s*$/g, '');
        if (color.length === 3) {
            color = color.replace(/(.)/g, '$1$1');
        }

        color = color.toLowerCase();
        which = which.toLowerCase();

        for (i = 0; i < colorDefs.length; i++) {
            re = colorDefs[i].re;
            processor = colorDefs[i].process;
            bits = re.exec(color);
            if (bits) {
                channels = processor(bits);
                r = channels[0];
                g = channels[1];
                b = channels[2];
                a = channels[3];
            }
        }

        r = r < 0 || isNaN(r) ? 0 : r > 255 ? 255 : r;
        g = g < 0 || isNaN(g) ? 0 : g > 255 ? 255 : g;
        b = b < 0 || isNaN(b) ? 0 : b > 255 ? 255 : b;
        a = a < 0 || isNaN(a) ? 0 : a > 1 ? 1 : a;

        function hex(x) {
            return ('0' + parseInt(x, 10).toString(16)).slice(-2);
        }

        switch (which) {
            case 'rgba':
                if (opacity) {
                    a = (255 - (min = Math.min(r, g, b))) / 255;
                    r = ((r - min) / a).toFixed(0);
                    g = ((g - min) / a).toFixed(0);
                    b = ((b - min) / a).toFixed(0);
                    a = a.toFixed(4);
                }
                result = 'rgba(' + r + ',' + g + ',' + b + ',' + a + ')';
                break;
            case 'rgb':
                result = 'rgb(' + r + ',' + g + ',' + b + ')';
                break;
            case 'hex':
                if (isNaN(parseInt(r, 10)) || isNaN(parseInt(g, 10)) || isNaN(parseInt(b, 10))) {
                    return color;
                }
                result = '#' + hex(r) + hex(g) + hex(b);
                break;
        }
        return result;
    },

    setToolbarDropdownValue: function (button, aCommandName, aValue) {
        const wrapper = button.elem;

        if (aCommandName === 'FontName') {
            const idx = aValue.indexOf(',');
            if (idx !== -1) {
                aValue = aValue.substring(0, idx);
            }
            aValue = aValue.replace(/['"]/g, '');
        } else if (aCommandName === 'FontSize') {
            aValue = aValue.replace(/[^\d.]+/g, '');
        }

        const textSpan = wrapper.querySelector('.menu-item span');
        if (textSpan) {
            textSpan.textContent = aValue;
        }
        button.data = aValue;
    },

    getQueryCommandValue: function (currElem, aCommandName) {
        const attrs = {
            FontSize: { name: 'font-size', default: this.config.editorFontSize },
            FontName: { name: 'font-family', default: this.config.editorFontFamily },
            LineHeight: { name: 'line-height', default: this.config.lineHeight},
            BackColor: { name: 'background-color', default: this.config.editorBgColor},
            ForeColor: { name: 'color', default: this.config.editorFontColor},
        };
        const attr = attrs[aCommandName];
        let styleValue;

        if ((aCommandName === 'ForeColor' || aCommandName === 'BackColor')) {
            styleValue = this.doc.queryCommandValue(aCommandName);
            if (styleValue.startsWith('rgba')) {
                styleValue = attr.default;
            }
        } else {
            styleValue = this.getComputedStyleValue(currElem, attr.name) || attr.default;
        }

        return styleValue;
    },

    isFormatBlockTagName: function (currElem) {
        let blockTags = Object.keys(GB.dropdown.formatBlock).toString();
        return currElem.closest(blockTags)?.tagName || '기본';
    },

    isUnlinkEnable: function (currElem) {
        return currElem.closest('a');
    },

    updateToolbarListButtonState: function (tbButton, rangeElem, listType) {
        const isListActive = rangeElem.closest('li')?.parentNode.nodeName === listType;
        this.toolbarButtonChecked(tbButton, isListActive);
    },

    updateToolbar: function (targetElement = null) {
        let bNoOff = { 'Link': 1 },
            currElem,
            elem,
            fontAttr,
            isControl = false,
            isIgnore = null,
            isTable = false,
            liItem,
            rangeElem,
            // ancestorsLength = 0,
            // shouldDisable,
            state;
        const range = this.getRange();
        const selection = this.getSelection();

        rangeElem = this.getRangeElement(range);
        currElem = targetElement || rangeElem;
        isIgnore = !this.isElementNode(currElem) || currElem.classList.contains('table-col-resizer');

        if (isIgnore) {
            return;
        }

        const isBogusCaret = this.hasBogusCaret();
        const tagName = rangeElem.tagName;
        const isCollapsed = range.collapsed;

        if (!isCollapsed) {
            isControl = ['IMG', 'TABLE', 'BLOCKQUOTE', 'HR'].includes(tagName);
        } else {
            elem = currElem;
            while (elem && !this.isBody(elem)) {
                // ancestors.push(elem);
                if (GB.tableElements.includes(elem.tagName)) {
                    isTable = true;
                }
                elem = elem.parentElement;
            }
            // ancestorsLength = ancestors.length;
        }

        if (!isTable && GB.tableElements.includes(currElem.tagName)) {
            isTable = true;
        }

        Object.entries(this.toolbar).forEach(([tbName, tbButton]) => {
            if (typeof tbButton !== 'object' || !tbButton.action) {
                return;
            }
            if (isBogusCaret && isBogusCaret.hasCaret) {
                this.toolbarSetDisabled(tbButton, true);
                this.toolbarButtonChecked(tbButton, false);
                return;
            }

            let autoOff = false;

            if (isBogusCaret && isBogusCaret.hasCaption) {
                autoOff = !['Color', 'Format', 'Alignment', 'InsertSymbol'].includes(tbButton.group);
                this.toolbarSetDisabled(tbButton, autoOff);
            } else {
                switch (tbName) {
                    case 'Indent':
                        liItem = rangeElem.tagName !== 'LI' ? rangeElem.closest('li') : rangeElem;
                        autoOff = liItem && !liItem.previousElementSibling;
                        break;
                    case 'ModifyTable':
                        autoOff = !isTable;
                        break;
                }
            }

            if (isControl) {
                const isNotAlignmentGroup = tbButton.group !== 'Alignment';
                autoOff = isNotAlignmentGroup &&
                    !(currElem.tagName === 'IMG' && bNoOff[tbButton.action]);
            }

            this.toolbarSetDisabled(tbButton, autoOff);
            tbButton.data = null;

            const command = tbButton.argv;
            switch (command) {
                case 'QuoteBlock':
                    elem = rangeElem.closest('blockquote');
                    this.toolbarDropdownChecked(tbButton, !!elem);
                    tbButton.data = elem;
                    break;
                case 'Copy': case 'Cut': case 'RemoveFormat':
                    this.toolbarSetDisabled(tbButton, isCollapsed);
                    break;
                case 'UnLink':
                    this.toolbarSetDisabled(tbButton, !this.isUnlinkEnable(rangeElem));
                    break;
                case 'FormatBlock':
                    this.setToolbarDropdownValue(tbButton, command, this.isFormatBlockTagName(currElem));
                    break;
                case 'ForeColor': case 'BackColor':
                    fontAttr = this.getQueryCommandValue(currElem, command);
                    if (!fontAttr.startsWith('rgb') && !fontAttr.startsWith('#')) {
                        fontAttr = ((fontAttr & 0x0000ff) << 16 | fontAttr & 0x00ff00 | (fontAttr & 0xff0000) >>> 16).toString(16);
                        fontAttr = '#000000'.slice(0, 7 - fontAttr.length) + fontAttr;
                    }
                    tbButton.icon.lastElementChild.style.backgroundColor = fontAttr;
                    break;
                case 'FontName': case 'FontSize':
                    this.setToolbarDropdownValue(tbButton, command, this.getQueryCommandValue(currElem, command));
                    break;
                case 'LineHeight':
                    elem = this.findClosestBlockElement(currElem);
                    tbButton.data = String(elem?.style.lineHeight || this.config.lineHeight);
                    break;
                case 'InsertOrderedList':
                    this.updateToolbarListButtonState(tbButton, rangeElem, 'OL');
                    break;
                case 'InsertUnOrderedList':
                    this.updateToolbarListButtonState(tbButton, rangeElem, 'UL');
                    break;
                default:
                    if (!this.doc.queryCommandSupported(command)) {
                        return;
                    }
                    state = this.doc.queryCommandState(command);
                    if (state === null) {
                        return;
                    }
                    this.toolbarButtonChecked(tbButton, state);
            }
        });

        if (this.config.showTagSelector) {
            this.outputTagSelector(currElem);
        }
    },

    createToolbarButton: function (prop) {
        let elem = document.createElement("div");
        elem.setAttribute("name", prop.name);
        elem.className = prop.class;

        const icon = document.createElement('div');
        icon.className = prop.action === "showDropdown" ? "menu-item" : "icon";

        if (prop.icon > -1) {
  //           if (btn.name === "Source") {
  //               btn_elem.insertAdjacentHTML('afterbegin', `<svg class="tb-icon" width="16" height="16" fill="currentColor">
  // <use href="https://cheditor.local/icons/toolbar.svg#code"/></svg>`);
  //           } else {
                icon.style.backgroundImage = `url(${this.config.iconPath}/toolbar.png)`;
                icon.style.backgroundPosition = `${~prop.icon+1}px center`;
            // }
        }

        elem.append(icon);
        const button = {
            autoCheck: prop.check || false,
            className: prop.class,
            checked: false,
            icon: icon,
            argv: prop.value,
            elem: elem,
            colorNode: {},
            disabled: false,
            action: prop.action,
            name: prop.name,
            node: prop.node || null,
            data: null
        };

        if (button.icon.className === "menu-item") {
            const span = document.createElement("span");

            if (prop.item_text) {
                span.append(prop.item_text);
                span.className = "menu-item-text";
                button.data = prop.item_text;
            }

            button.icon.append(span);

            if (prop.icon > -1) {
                button.icon.classList.add("icon");
            }
        }

        this.toolbar[prop.name] = button;
        this.addEvent(elem, 'mousedown', evt => {
            if (!this.getSelection().isCollapsed || this.pictureBogusCaret) {
                this.stopEvent(evt);
            }
        });
        this.addEvent(elem, "click", evt => {
            switch (button.action) {
                case "doCmd":
                    this.execEditorCommand(button.argv);
                    break;
                case "applyTextFormat":
                    this.applyTextFormat(button.argv);
                    break;
                case "applyFontSize":
                    this.fontSizeUpDown(button.argv);
                    break;
                case "popupWindowOpen":
                    this.popupWindowOpen(button);
                    break;
                case "showDropdown":
                    if (button.checked) {
                        if (button.name === 'QuoteBlock' && button.data) {
                            this.removeBlockquote(button.data);
                            this.toolbarDropdownChecked(button, false);
                            button.data = null;
                        }
                        break;
                    }
                    this.showDropdown(button.argv, elem);
                    this.toolbarButtonChecked(button, true);
                    break;
                default :
                    alert("해당 명령은 지원하지 않는 기능입니다.");
            }
            this.stopEvent(evt);
        });

        return button;
    },

    createButtonHint: function (wrapper, button, content) {
        let tooltipElem = null;
        let timer = null;
        const removeTooltip = () => {
            tooltipElem.remove();
            tooltipElem = null;
        };
        const getTooltipPosition = (button, tooltip) => {
            const buttonRect = button.getBoundingClientRect();
            const top = `${buttonRect.top + window.scrollY + button.offsetHeight}px`;
            const left = `${buttonRect.left - (tooltip.offsetWidth / 2 - button.offsetWidth / 2)}px`;
            return { top, left };
        };

        this.addEvent(button, 'mouseenter',  () => {
            timer = setTimeout(() => {
                tooltipElem = document.createElement('div');
                tooltipElem.className = 'cheditor-btn-tooltip';
                wrapper.append(tooltipElem);

                const contentElem = document.createElement('div');
                contentElem.className = 'tooltip-inner';
                contentElem.append(content);

                tooltipElem.append(contentElem);
                tooltipElem.popover = 'hint';

                Object.assign(tooltipElem.style, getTooltipPosition(button, tooltipElem));

                this.addEvent(tooltipElem, 'beforetoggle', evt => {
                    if (evt.newState === 'closed') {
                        removeTooltip();
                    }
                });
                tooltipElem.showPopover();
            }, 500);
        });

        const hideTooltip = (evt) => {
            this.stopEvent(evt);
            if (timer) {
                clearTimeout(timer);
                timer = null;
            }
            if (tooltipElem) {
                tooltipElem.classList.add('hide');
                tooltipElem.hidePopover();
            }
        };
        this.addEvent(button, 'mouseleave', hideTooltip);
        this.addEvent(button, 'mousedown', hideTooltip);
    },

    showToolbar : function (wrapper) {
        const elem = document.createElement('div');
        const colStart = elem.cloneNode(false);
        const showButtonHint = this.config.showToolbarTooltip;

        colStart.className = 'col-start';
        wrapper.append(colStart);

        if (this.config.useFullScreen) {
            const btnFullscreen = elem.cloneNode(false);
            btnFullscreen.className = 'btn-fullscreen';
            btnFullscreen.dataset.action = 'fullscreen';

            this.addEvent(btnFullscreen, 'mousedown', evt => this.stopEvent(evt));
            this.addEvent(btnFullscreen, "click", (evt) => {
                evt.target.classList.toggle('actual');
                this.cheditor.resizeBar.classList.toggle('full-screen');
                this.fullScreenMode(evt);
            });

            const colEnd = elem.cloneNode(false);
            colEnd.className = 'col-end';
            colEnd.append(btnFullscreen);
            wrapper.append(colEnd);

            // -------- tooltip
            if (showButtonHint) {
                this.createButtonHint(wrapper, btnFullscreen, '전체 화면');
            }
        }

        TMPL.toolbar.forEach((group) => {
            if (group.name === 'Split') {
                const split = elem.cloneNode(false);
                split.className = 'cheditor-toolbar-split';
                colStart.append(split);
                return;
            }

            // const btn_group = [];
            const groupOuter = elem.cloneNode(false);
            groupOuter.className = 'cheditor-toolbar-btn-outer';

            group.button.forEach((prop) => {
                if (!prop.node && this.config['use' + prop.name] !== true) {
                    return;
                }
                if (prop.type === 'combobox' && this.config['use' + prop.node] !== true) {
                    return;
                }

                const tbButton = this.createToolbarButton(prop);
                tbButton.group = group.name;

                if (tbButton.name === 'ForeColor' || tbButton.name === 'BackColor') {
                    const color = elem.cloneNode(false);
                    color.className = 'current-color';
                    color.style.backgroundColor = prop.default;
                    tbButton.icon.append(color);
                }
                groupOuter.append(tbButton.elem);

                if (showButtonHint) {
                    this.createButtonHint(wrapper, tbButton.elem, prop.label);
                }
            });

            // if (btn_group.length > 0) {
            //     if (btn_group.length === 1) {
            //         btn_group.shift().classList.add('btn-single');
            //     } else {
            //         btn_group.shift().classList.add('btn-start');
            //         btn_group.pop().classList.add('btn-end');
            //         while (btn_group.length) {
            //             btn_group.shift().classList.add('btn-middle');
            //         }
            //     }
            // }
            if (groupOuter.childElementCount > 0) {
                colStart.append(groupOuter);
            }
        });

        this.addEvent(document, 'readystatechange', evt => {
            if (evt.target.readyState === 'complete') {
                wrapper.querySelectorAll('.cheditor-toolbar-dropdown')
                    .forEach(btn => btn.classList.add('trans'));
            }
        });
    },

    createEditorElement: function (app_container) {
        let elem = app_container.firstElementChild, id, editarea;

        while (elem) {
            id = elem.getAttribute('id');
            if (!id) {
                elem = elem.nextElementSibling;
                continue;
            }

            switch (id) {
                case 'toolbar':
                    this.showToolbar(elem);
                    this.cheditor.toolbarWrapper = elem;
                    break;
                case 'editarea-wrapper':
                    editarea = elem.querySelector('.cheditor-editarea');
                    editarea.style.setProperty('height', '100%', 'important');
                    editarea.style.setProperty('width', '100%', 'important');
                    elem.style.setProperty('height', this.config.editorHeight, 'important');
                    this.cheditor.editarea = editarea;
                    this.cheditor.editWrapper = elem;
                    this.cheditor.textarea = elem.querySelector('.cheditor-textarea');
                    this.cheditor.insertParagraphLine = elem.querySelector('#insert-paragraph-line');
                    break;
                case 'statusbar':
                    if (this.config.showTagSelector) {
                        this.cheditor.htmlTagSelector = elem.querySelector('.cheditor-html-selector');
                        elem.classList.remove('d-none');
                    }
                    this.cheditor.resizeBar = elem.querySelector('.cheditor-textarea-resizer');
                    this.addEvent(this.cheditor.resizeBar, 'mousedown', evt => this.editareaResizeStart(evt));
                    break;
                case 'cheditor-source-edit':
                    this.cheditor.htmlEditable = elem;
                    break;
                case 'image-resizer':
                    elem.removeAttribute('id');
                    this.cheditor.imageResizer = elem;
                    break;
                case 'image-popover':
                    elem.removeAttribute('id');
                    this.cheditor.imagePopover = elem;
                    break;
                case 'cheditor-modal':
                    this.cheditor.modal = elem;
                    this.cheditor.modal.classList.add('fade');
                    this.cheditor.modal.titlebar = elem.querySelector('.cheditor-modal-title-bar');
                    this.cheditor.modal.title_text = elem.querySelector('.cheditor-modal-title-bar .title');
                    this.cheditor.modal.close = elem.querySelector('.cheditor-modal-title-bar .close');
                    this.cheditor.modal.frameWrapper = elem.querySelector('.cheditor-modal-frame');
                    break;
                case 'cheditor-dialog':
                    this.cheditor.dialog = elem;
                    break;
                default:
                    break;
            }
            // elem.removeAttribute('id');
            elem = elem.nextElementSibling;
        }

        app_container.style.setProperty('width', this.config.editorWidth, 'important');
        this.cheditor.container = app_container;
    },

    loadTemplate: function () {
        const fragment = document.createRange().createContextualFragment(TMPL.container);
        const app_container = fragment.firstElementChild;
        this.createEditorElement(app_container);
        this.setEditorVisibility(false);
        this.cheditor.inputForm.after(app_container);
        GB.modalBackdrop = null;
    },

    findElementPosition: function (elem) {
        let x, y;
        if (typeof elem.offsetParent !== 'undefined') {
            for (x = 0, y = 0; elem; elem = elem.offsetParent) {
                x += elem.offsetLeft;
                y += elem.offsetTop;
            }
            return {left: x, top: y};
        }
        return { left: elem.x, top: elem.y };
    },

    run: async function () {
        try {
            this.setFolderPath();
            this.initializeInputForm();
        } catch (e) {
            alert(e.toString());
            return;
        }

        this.initTemplate();

        if (await this.resetEditorDocument()) {
            this.editorEventHandler();
            this.initEditorFocus();
        }
    },

    fullScreenMode: function (evt) {
        const btnFullScreen = evt.target;
        const isFullScreen = btnFullScreen.classList.contains('actual');
        const container = this.cheditor.container;
        const editWrapper = this.cheditor.editWrapper;
        const setEditorHeight = (height) => {
            editWrapper.style.setProperty('height', height, 'important');
        };

        if (isFullScreen) {
            const toolbarHeight = this.cheditor.toolbarWrapper.offsetHeight;
            this.config.editorHeight = editWrapper.offsetHeight;
            setEditorHeight(`calc(100% - ${toolbarHeight}px)`);
            container.classList.add('full-screen');

            const afterToolbarHeight = this.cheditor.toolbarWrapper.offsetHeight;
            if (toolbarHeight > afterToolbarHeight) {
                setEditorHeight(`calc(100% - ${afterToolbarHeight}px)`);
            }
        } else {
            setEditorHeight(`${this.config.editorHeight}px`);
            container.classList.remove('full-screen');
        }
    },

    showDropdown: function (command, button) {
        const methodName = GB.dropdown.menuMap[command];
        if (methodName && typeof this[methodName] === 'function') {
            const dropdownId = this[methodName](button);
            if (dropdownId) {
                this.showDropdownMenu(button, dropdownId);
            }
        }
    },

    dropdownOrderedList: function (button) {
        const name = button.getAttribute('name');

        if (typeof this.dropdown[name] === 'undefined') {
            const dropdownContainer = document.createElement('div');
            const isUnordered = name === 'UnOrderedListCombo';
            const command = isUnordered ? 'UnOrderedList' : 'OrderedList';
            const styleList = isUnordered
                ? GB.dropdown.listStyle.unOrdered
                : GB.dropdown.listStyle.ordered;

            styleList.forEach(style => {
                const dropdownItem = document.createElement('div');
                dropdownItem.dataset.value = style.type;
                dropdownItem.onclick = () => this.doCmdPopup(command, style.type);

                const itemLabel = document.createElement('span');
                const listStartElem = document.createElement('ul');

                Object.assign(listStartElem.style, {
                    width: '90px',
                    padding: '0 15px',
                    margin: '0',
                    listStyleType: style.type,
                    cursor: 'default',
                    textAlign: 'left'
                });

                const listItemElem = document.createElement('li');
                listItemElem.append(style.title);
                listStartElem.append(listItemElem);
                itemLabel.append(listStartElem);
                dropdownItem.append(itemLabel);
                dropdownContainer.append(dropdownItem);
            });

            this.createDropdown(name, 120, dropdownContainer);
        }

        return name;
    },

    dropdownColor: function (button) {
        const name = button.getAttribute('name');
        const tickIcon = `url("${this.config.iconPath}/color_picker_tick.png")`;
        const colorNode = this.toolbar[name].colorNode;
        const currColor = button.querySelector('.current-color').style.backgroundColor;
        const selectedColor = this.colorConvert(currColor, 'hex');
        let elem = this.dropdown[name];

        if (typeof elem === 'undefined') {
            const outputHtml = this.setColorTable(name);
            this.createDropdown(name, 220, outputHtml, 'color-palette');
            elem = this.dropdown[name];
        }

        colorNode.selectedValue.style.backgroundColor = selectedColor;
        colorNode.colorPicker.hidePicker();
        colorNode.colorPicker.fromString(selectedColor);
        colorNode.showPicker = false;

        const nodes = elem.getElementsByTagName('span');
        const len = nodes.length;

        for (let i = 0; i < len; i++) {
            const node = nodes[i];
            node.style.backgroundImage = '';
            if (node?.id.toLowerCase() === selectedColor.toLowerCase()) {
                node.style.backgroundImage = tickIcon;
                node.style.backgroundRepeat = 'no-repeat';
                node.style.backgroundPosition = 'center center';

            }
        }

        colorNode.selectedValue.style.backgroundImage = tickIcon;
        colorNode.selectedValue.style.backgroundRepeat = 'no-repeat';
        colorNode.selectedValue.style.backgroundPosition = 'center center';

        return name;
    },

    dropdownTable: function (button) {
        const name = button.getAttribute('name');

        if (typeof this.dropdown[name] === 'undefined') {
            const outputHtml = this.createTableDropdown();
            this.createDropdown(name, 0, outputHtml, 'table-dropdown');
        }

        return name;
    },

    dropdownFontType: function (button) {
        const name = button.getAttribute('name');

        if (typeof this.dropdown[name] === 'undefined') {
            const defaultFont = this.config.editorFontFamily.split(','),
                setFontFamily = font => {
                    const result = defaultFont.filter(name => name.replace(/["']/g, '').trim() !== font);
                    result.unshift(font);
                    return result.join(',');
                },
                outputHtml = document.createElement('div');
            let div, label;

            GB.dropdown.fontName.forEach(font => {
                div = document.createElement('div');
                label = document.createElement('span');
                div.dataset.value = font;
                div.onclick = () => this.doCmdPopup(name, font);
                label.style.fontFamily = setFontFamily(font);
                label.append(font);
                div.append(label);
                outputHtml.append(div);
            });

            this.createDropdown(name, 155, outputHtml);
        }

        return name;
    },

    dropdownFormatBlock: function (button) {
        const name = button.getAttribute('name');

        if (typeof this.dropdown[name] === 'undefined') {
            const convertToFontsize = {
                    H1: "2em", H2: "1.5em", H3: "1.17em", H4: "1em", H5: "0.83em", H6: "0.75em"
                };
            let itemText, div, outputHtml = document.createElement('div');

            Object.entries(GB.dropdown.formatBlock).forEach(([tag, title]) => {
                div = document.createElement('div');
                div.dataset.value = tag;
                div.onclick = () => this.doCmdPopup(name, `<${tag}>`);
                itemText = document.createElement('div');

                if (tag.match(/H[123456]/)) {
                    itemText.style.fontWeight = "bold";
                    itemText.style.fontSize = convertToFontsize[tag];
                } else if (tag === 'ADDRESS') {
                    itemText.style.fontStyle = "italic";
                } else if (tag === 'PRE') {
                    const pre = document.createElement('pre');
                    pre.style.margin = "0";
                    itemText.append(pre);
                    itemText = pre;
                }

                title = title.trim();
                itemText.append(title);
                div.append(itemText);
                itemText.setAttribute('name', title);
                outputHtml.append(div);
            });

            this.createDropdown(name, 155, outputHtml);
        }

        return name;
    },

    dropdownFontSize: function (button) {
        const name = button.getAttribute('name');

        if (typeof this.dropdown[name] === 'undefined') {
            const outputHtml = document.createElement('div');

            GB.dropdown.fontSize.forEach(size => {
                const div = document.createElement('div'),
                    label = document.createElement('span');
                size = size.toString();
                label.append(size);
                div.dataset.value = size;
                div.append(label);
                div.onclick = () => this.doCmdPopup(name, size);
                outputHtml.append(div);
            });

            outputHtml.style.height = '260px';
            outputHtml.style.overflowX = 'hidden';

            this.createDropdown(name, 55, outputHtml);
        }

        return name;
    },

    dropdownLineHeight: function (button) {
        const name = button.getAttribute('name');

        if (typeof this.dropdown[name] === 'undefined') {
            let div, label, outputHtml = document.createElement('div');

            GB.dropdown.lineHeight.forEach((item) => {
                div = document.createElement('div');
                label = document.createElement('span');
                label.append(item.title);
                div.dataset.value = item.value;
                div.append(label);
                div.onclick = () => this.doCmdPopup(name, item.value);
                outputHtml.append(div);
            });

            this.createDropdown(name, 100, outputHtml);
        }

        return name;
    },

    dropdownQuoteBlock: function (button) {
        const name = button.getAttribute('name');
        if (typeof this.dropdown[name] === 'undefined') {
            const outputHtml = document.createElement('div');
            GB.dropdown.quoteBlock.forEach(style => {
                const div = document.createElement('div');
                const quote = document.createElement('div');
                div.className = 'dropdown-item-quote';
                if (style.menu === 'vertical-bar') {
                    const span = document.createElement('span');
                    span.append("가나다라");
                    quote.append(span);
                    quote.className = 'vertical-bar';
                } else {
                    quote.style.cssText = style.menu;
                    quote.append("가나다라");
                }
                quote.onclick = () => this.doCmdPopup(name, style.apply);
                div.append(quote);
                outputHtml.append(div);
            });
            this.createDropdown(name, 100, outputHtml);
        }

        return name;
    },

    createDropdownFrame : function (contents, id) {
        const div = document.createElement('div');
        div.className = 'cheditor-dropdown-frame fade d-none';
        div.append(contents);
        this.dropdown[id] = div;
        this.cheditor.toolbarWrapper.append(div);
    },

    setDefaultCss : function (ar) {
        let found = false, href, css;

        ar = ar || { css: 'editarea.css', doc: this.doc };

        const css_file = this.config.cssPath + '/' + ar.css,
            head = ar.doc.getElementsByTagName('head')[0];

        if (this.isUndefined(head)) {
            return;
        }

        for (const child of head.children) {
            if (child.tagName === 'LINK') {
                href = child.getAttribute('href');
                if (href && href === css_file) {
                    found = true;
                    break;
                }
            }
        }

        if (!found) {
            css = head.appendChild(ar.doc.createElement('link'));
            css.setAttribute('type', 'text/css');
            css.setAttribute('rel', 'stylesheet');
            css.setAttribute('media', 'all');
            css.setAttribute('href', css_file);
        }
    },

    editorEventHandler : function () {
        if (this.initEventHandler) {
            return;
        }
        const keydownEventHandler = evt => {
                this.onKeydown(evt);
            },
            keypressEventHandler = evt => {
                this.onKeypress(evt);
            },
            keyupEventHandler = evt => {
                this.onKeyup(evt);
            },
            clickEventHandler = evt => {
                if (isReturn) {
                    isReturn = false;
                    return;
                }
                if (evt.clientX <= this.body.offsetWidth) {
                    this.editareaEventHandler(evt);
                    if (GB.browser.firefox) {
                        const selection = this.getSelection();
                        if (selection.anchorNode.nodeName === 'IMG' && !this.pictureBogusCaret) {
                            selection.anchorNode.click();
                        }
                    }
                }
            },
            mousedownEventHandler = evt => {
                if (evt.clientX <= this.body.offsetWidth) {
                    if (evt.target.nodeName === 'IMG' && this.pictureBogusCaret) {
                        this.stopEvent(evt);
                        this.editareaFocus();
                    } else if (GB.browser.firefox && this.pictureResizer) {
                        const rect = this.pictureResizer.getBoundingClientRect();
                        if (rect.y <= evt.clientY && rect.bottom >= evt.clientY) {
                            this.stopEvent(evt);
                            isReturn = true;
                        }
                    } else {
                        this.getSelection().collapse(evt.target);
                    }
                }
                this.closeDropdownMenu();
            },
            imageResizingEventHandler = evt => {
                this.stopEvent(evt);
                isReturn = true;
            },
            focusoutEventHandler = (evt) => {
                if (this.pictureBogusCaret) {
                    if (this.popper &&
                        !clickPopover &&
                        evt.relatedTarget?.nodeName !== 'FIGCAPTION') {
                        this.pictureResizer.parentElement.classList.add('disabled');
                        this.popper.hidePopover();
                    }
                }
                clickPopover = false;
            },
            focusEventHandler = () => {
                if (this.pictureBogusCaret) {
                    this.pictureResizer?.parentElement.classList.remove('disabled');
                }
            },
            focusout = GB.browser.firefox ? 'blur' : 'focusout',
            focusin = GB.browser.firefox ? 'focus' : 'focusin';
        let isReturn = false, clickPopover = false;

        this.addEvent(this.doc, focusin, focusEventHandler);
        this.addEvent(this.doc, focusout, focusoutEventHandler);
        this.addEvent(this.doc, 'keydown', keydownEventHandler);
        this.addEvent(this.doc, 'keypress', keypressEventHandler);
        this.addEvent(this.doc, 'keyup', keyupEventHandler);
        this.addEvent(this.doc, 'click', clickEventHandler);
        this.addEvent(this.doc, 'mousedown', mousedownEventHandler);
        this.addEvent(this.doc, 'image-resizing', imageResizingEventHandler);
        this.addEvent(this.doc, 'click-popover', () => clickPopover = true);
        this.addEvent(this.doc, 'paste', evt => this.handlePaste(evt) );

        this.observer = new MutationObserver((mutations, observer) => {
            this.applyFontStyle(mutations, observer);
        });

        this.removeNode = this.removeNodeProxy(this.removeNode);

        if (this.config.showTagSelector) {
            this.addEvent(this.cheditor.htmlTagSelector, 'mousedown', evt => {
                this.stopEvent(evt);
                this.editareaFocus();
                if (evt.target.tagName === 'A' && !this.pictureResizer) {
                    this.tagSelection(evt.target.el);
                }
            });
            this.addEvent(this.cheditor.htmlTagSelector, 'mouseover', evt => {
                if (evt.target.tagName === 'A' && !this.pictureResizer) {
                    this.showNodeRange(evt.target.el);
                }
            });
            this.addEvent(this.cheditor.htmlTagSelector, 'mouseout', evt => {
                if (evt.target.tagName === 'A' && !this.pictureResizer) {
                    this.hideNodeRange(evt.target.el);
                }
            });
        }
        this.initEventHandler = true;
    },

    applyFontStyle: function (mutations, observer) {
        observer.disconnect();

        const styleRules = observer.modify.rules;
        const marker = this.saveRangeMarker(this.getRange());

        mutations.forEach(mutation => {
            if (mutation.type === 'attributes' && mutation.attributeName === 'style') {
                this.muHandleAttribute(mutation);
            }
            mutation.addedNodes.forEach((node) => {
                if (this.isElementNode(node)) {
                    this.muProcessAddedNode(node, observer, styleRules);
                }
            });
        });

        this.restoreRangeMaker(marker);
        this.updateToolbar();
    },

    muProcessAddedNode: function (node, observer, styleRules) {
        if (node.nodeName !== observer.modify.oldName) {
            if (this.isTextFormatElement(node)) {
                if (!node.hasChildNodes()) {
                    node.remove();
                } else {
                    this.muHandleTransparentProperty(node, styleRules.attributeNewValue);
                }
            }
            return;
        }

        const range = this.createRange();
        range.selectNodeContents(node);

        if (observer.modify.type === 'replace') {
            this.muReplaceNode(node, range, observer.modify.newName);
        } else {
            this.muApplyStyleRulesToNode(node, styleRules, range, observer.modify.newName);
        }
    },

    muReplaceNode: function (node, range, newNodeName) {
        const newElement = this.doc.createElement(newNodeName);
        newElement.appendChild(range.extractContents());
        node.replaceWith(newElement);

        if (newElement.hasChildNodes()) {
            this.replaceTextFormatElement(newElement);
        }
    },

    muHandleTransparentProperty: function (node, prop) {
        if (prop !== 'color' && prop !== 'background-color') return;

        const color = node.style[prop];
        if (!color) return;

        if (color.startsWith('rgba')) {
            node.style.removeProperty(prop);
            if (node.style.length === 0) {
                const range = this.createRange();
                range.selectNodeContents(node);
                node.before(range.extractContents());
                node.remove();
            }
        }
    },

    muHandleAttribute: function (mutation) {
        const target = mutation.target;
        const styleAttributes = target.getAttribute('style');

        if (!styleAttributes || styleAttributes.length === 0) {
            target.removeAttribute('style');
            return;
        }

        this.muHandleTransparentProperty(target, 'background-color');
        this.muHandleTransparentProperty(target, 'color');
    },

    muHasMatchingStyles: function (node, refStyles, refStyleCount) {
        const nodeStyles = this.cssProperties(node);
        return (nodeStyles.length === refStyleCount &&
            nodeStyles.every((style, i) =>
                style.name === refStyles[i].name && style.value === refStyles[i].value
            )
        );
    },

    muApplyStyleRulesToNode: function (node, styleRules, range, newNodeName) {
        let styleValue = (
            styleRules.name === 'color' ||
            styleRules.name === 'backgroundColor' ||
            styleRules.name === 'fontFamily')
            ? node.getAttribute(styleRules.attributeNewValue) || styleRules.attributeNewValue
            : styleRules.attributeNewValue;
        const extractedContents = range.extractContents();

        if (['color', 'backgroundColor'].includes(styleRules.name)) {
            if (styleValue.startsWith('rgba')) {
                node.before(extractedContents);
                node.remove();
                return;
            }
        }

        if (node.parentNode?.childNodes.length === 1 && node.parentNode.nodeName === 'SPAN') {
            node.parentNode.style[styleRules.name] = styleValue;
            node.before(extractedContents);
            node.remove();
        } else {
            const newElement = this.doc.createElement(newNodeName);
            newElement.style[styleRules.name] = styleValue;

            if (extractedContents.childNodes.length === extractedContents.children.length) {
                const refNodes = [...extractedContents.children];
                const refNode = refNodes.shift();
                const refNodeStyles = this.cssProperties(refNode);
                let stylesMatch = false;

                if (refNodeStyles) {
                    stylesMatch = refNodes
                        .every(child => this.muHasMatchingStyles(child, refNodeStyles, refNodeStyles.length));
                }

                if (stylesMatch) {
                    refNodes.unshift(refNode);
                    refNodes.forEach(child => {
                        const styles = this.cssProperties(child);
                        if (!styles) {
                            return;
                        }

                        styles.forEach((style) => {
                            const prop = node.style.getPropertyValue(style.name);
                            if (!prop || prop === style.value) {
                                newElement.style[style.name] = style.value;
                                child.style.removeProperty(style.name);
                            }
                        });

                        if (child.style.length === 0) {
                            child.removeAttribute('style');
                            if (child.attributes.length === 0) {
                                const range = this.createRange();
                                range.selectNodeContents(child);
                                child.before(range.extractContents());
                                child.remove();
                            }
                        }
                    });
                }
            }

            newElement.appendChild(extractedContents);
            newElement.normalize();
            node.replaceWith(newElement);
        }
    },

    addEvent: function (target, type, handler) {
        if (target.addEventListener) {
            target.addEventListener(type, handler, false);
        }
    },

    removeEvent: function (elem, evt, func) {
        if (elem.removeEventListener) {
            elem.removeEventListener(evt, func, false);
        }
    },

    stopEvent: function (evt) {
        if (evt && evt.preventDefault) {
            evt.preventDefault();
            evt.stopPropagation();
        }
    },

    getElement: function (elem, tagName) {
        if (!elem || !tagName) {
            return null;
        }

        const TAG_NAME_MATCH = (elem, tagName) => elem.tagName === tagName.toUpperCase();

        while (elem && !TAG_NAME_MATCH(elem, tagName)) {
            if (this.isBody(elem)) {
                return null;
            }
            elem = elem.parentNode;
        }

        return elem;
    },

    removeHyperLink: function () {
        const selection = this.getSelection();

        if (selection.type === 'None') {
            return;
        }
        if (selection.type === 'Range') {
            this.queryCommand('UnLink');
            return;
        }

        const range = this.getRange();
        const anchor = this.getRangeElement(range).closest('a');

        if (anchor && anchor.nodeName === 'A') {
            const parent = anchor.parentNode;
            const staticRange = this.createStaticRange(range);
            const anchorRange = this.createRange();
            anchorRange.selectNodeContents(anchor);
            anchor.before(anchorRange.extractContents());
            anchor.remove();
            this.restoreStaticRange(selection, staticRange);
            parent.normalize();
        }
    },

    hyperLinkCollapsedRange: function (range, attr) {
        const anchor = this.getRangeElement(range).closest('a');
        const text = this.doc.createTextNode(attr.href);

        if (!anchor) {
            range.insertNode(text);
            range.selectNode(text);
            return null;
        }

        let staticRange = null;

        if (anchor.href === anchor.textContent.trim()) {
            anchor.replaceChildren(text);
        } else {
            staticRange = this.createStaticRange(range.cloneRange());
        }

        range.selectNodeContents(anchor);
        return staticRange;
    },

    setAnchorAttributes: function (anchor, range, attr) {
        const LINK_NODE_NAME = 'A';

        while (anchor && range.intersectsNode(anchor)) {
            if (anchor.nodeName === LINK_NODE_NAME) {
                anchor.setAttribute('href', attr.href);
                if (this.config.linkTargetAttr) {
                    anchor.setAttribute('target', this.config.linkTargetAttr);
                }
                if (attr.title) {
                    anchor.setAttribute('title', attr.title);
                }
                if (!this.isBlockElement(this.getRootNode(anchor))) {
                    anchor.id = this.makeRandomString();
                    this.queryCommand('formatBlock', 'p');

                    anchor = this.doc.getElementById(anchor.id);
                    anchor.removeAttribute('id');
                    range = this.getRange();
                }
            }
            anchor = this.getNextElement(anchor, LINK_NODE_NAME);
        }
    },

    restoreSelectionRange: function (selection, range, staticRange) {
        if (staticRange) {
            this.restoreStaticRange(selection, staticRange);
        } else {
            range.collapse(false);
            selection.empty();
            selection.addRange(range);
        }
    },

    createHyperLink: function (attr) {
        const selection = this.getSelection();

        if (selection.type === 'None') {
            return;
        }

        let range = selection.getRangeAt(0);
        const isCollapsed = range.collapsed;
        let staticRange = null;

        if (isCollapsed) {
            staticRange = this.hyperLinkCollapsedRange(range, attr);
        }

        this.queryCommand("CreateLink", attr.href);
        range = this.getRange();

        let anchorElem = range.startContainer;
        this.setAnchorAttributes(anchorElem, range, attr);

        if (isCollapsed) {
            this.restoreSelectionRange(selection, range, staticRange);
        }
    },

    insertParagraph: function () {
        const hr = this.createHorizontalRule();
        if (!hr) {
            return null;
        }
        const para = this.createEmptyParagraph();
        hr.replaceWith(para);
        return para;
    },

    createHorizontalRule: function () {
        let hr;

        if (GB.browser.firefox) {
            const range = this.getRange();
            if (!range.collapsed) {
                this.queryCommand('delete');
            }
            this.queryCommand('insertHorizontalRule');
            hr = this.body.querySelector('hr[_moz_dirty=""]');
            if (!hr) {
                return null;
            }
            hr.removeAttribute('_moz_dirty');
        } else {
            const uniqueId = this.makeRandomString();
            this.queryCommand('insertHorizontalRule', uniqueId);
            hr = this.doc.getElementById(uniqueId);
            if (!hr) {
                return null;
            }
            hr.removeAttribute('id');
        }

        return hr;
    },

    insertHorizontalRule: function () {
        let hr = this.createHorizontalRule();

        if (!hr) {
            return;
        }
        hr.setAttribute('style', GB.elementsStyle.hr);

        const nextSibling = hr.nextSibling;
        if (!nextSibling || this.removeBrElement(nextSibling) && !hr.nextSibling) {
            hr.after(this.createEmptyParagraph());
        }

        const prevSibling = hr.previousSibling;
        if (!prevSibling || this.removeBrElement(prevSibling) && !hr.previousSibling) {
            hr.before(this.createEmptyParagraph());
        }

        hr = hr.nextSibling;
        while (hr.firstChild) {
            hr = hr.firstChild;
        }

        this.getSelection().setPosition(hr);
    },

    getCompatProperty: function (docProp, bodyProp) {
        const isCSS1Compat = (this.doc.compatMode || '') === 'CSS1Compat';
        return isCSS1Compat ? this.doc.documentElement[docProp] : this.body[bodyProp];
    },

    getOffsetLeft: function (node) {
        let currNode = node;
        let offsetLeft = 0;
        while (currNode) {
            offsetLeft += currNode.offsetLeft;
            currNode = currNode.offsetParent;
        }
        return offsetLeft;
    },

    getOffsetTop: function (node) {
        let currNode = node, offsetTop = 0;

        while (currNode) {
            offsetTop += currNode.offsetTop;
            currNode = currNode.offsetParent;
        }

        return offsetTop;
    },

    scrollXY: function () {
        const scrollTop = this.getCompatProperty('scrollTop', 'scrollTop');
        const scrollLeft = this.getCompatProperty('scrollLeft', 'scrollLeft');
        return { scrollTop, scrollLeft };
    },

    clientXY: function () {
        const clientTop = this.getCompatProperty('clientTop', 'clientTop');
        const clientLeft = this.getCompatProperty('clientLeft', 'clientLeft');
        return { clientTop, clientLeft };
    },

    getOffsetBox: function (elem) {
        if (!elem) return null;

        const rect = elem.getBoundingClientRect();
        const { scrollTop, scrollLeft } = this.scrollXY();
        const { clientTop, clientLeft } = this.clientXY();
        const left = Math.round(rect.left + scrollLeft - clientLeft);
        return {
            top: Math.round(rect.top + scrollTop - clientTop),
            bottom: Math.round(rect.bottom + scrollTop - clientTop),
            left: left,
            center: left + Math.round(rect.width / 2),
            width: Math.round(rect.width),
            height: Math.round(rect.height)
        };
    },

    prepareNodeForBlockquote: function(node) {
        if (this.isInlineElement(node)) {
            node = this.wrapInlineNodeInBlockquote(node);
        }
        return node;
    },

    wrapContentInBlockquote: function (startNode, endNode, style) {
        const quoteElem = this.doc.createElement('blockquote');
        quoteElem.style.cssText = style;

        startNode.before(quoteElem);
        let currNode = startNode;

        while (currNode && currNode !== endNode) {
            currNode = this.prepareNodeForBlockquote(currNode, endNode);
            if (currNode.contains(endNode)) {
                break;
            }
            const nextNode = currNode.nextSibling;
            quoteElem.append(currNode);
            currNode = nextNode;
        }

        if (currNode) {
            currNode = this.prepareNodeForBlockquote(currNode, endNode);
            if (currNode) {
                quoteElem.append(currNode);
            }
        }
    },

    wrapInlineNodeInBlockquote: function (node) {
        const paraElem = this.doc.createElement('p');
        let currBlockElem = null;
        let currNode = node;

        while (currNode) {
            if (this.isInlineElement(currNode)) {
                if (!currBlockElem) {
                    currBlockElem = paraElem.cloneNode(false);
                    currNode.before(currBlockElem);
                }
                currBlockElem.append(currNode);
                currNode = currBlockElem;
            } else {
                break;
            }
            currNode = currNode.nextSibling;
        }

        return currBlockElem || node;
    },

    removeBlockquote: function (blockquote) {
        if (blockquote) {
            this.editareaFocus();
            const range = this.getRange();
            const staticRange = this.createStaticRange(range);
            range.selectNodeContents(blockquote);
            blockquote.before(range.extractContents());
            blockquote.remove();
            this.restoreStaticRange(this.getSelection(), staticRange);
        }
    },

    blockquoteCommand: function (style) {
        const range = this.getRange();
        const staticRange = this.createStaticRange(range);
        const {startContainer, endContainer} = staticRange;
        const startNode = this.getRootNode(startContainer);
        const endNode = this.getRootNode(endContainer);
        const selection = this.getSelection();
        const quoteAncestor = this.isElementNode(startNode)
            ? startNode.closest('blockquote') : null;

        if (quoteAncestor) {
            quoteAncestor.style.cssText = style;
            this.restoreStaticRange(selection, staticRange);
            return;
        }

        this.wrapContentInBlockquote(startNode, endNode, style);
        this.restoreStaticRange(selection, staticRange);
        this.updateToolbar();
    },

    insertHtmlPopup: function (elem) {
        this.insertHtmlAtSelection(elem);
    },

    insertHTML: function (html) {
        this.insertHtmlAtSelection(html);
    },

    placeCaretAt: function (targetElem, collapseToStart = false) {
        const caretRange = this.createRange();

        try {
            const lastChild = targetElem?.lastChild;
            if (this.isBrElement(lastChild)) {
                collapseToStart = true;
            }
            caretRange.selectNodeContents(targetElem);
        } catch (e) {
            caretRange.selectNode(targetElem);
        }

        try {
            const selection = this.getSelection();
            selection.empty();
            caretRange.collapse(collapseToStart);
            selection.addRange(caretRange);
        } catch (e) {
            this.placeCaretAt(this.body, collapseToStart);
        }
    },

    selectNodeContents: function (node, collapseToStart) {
        const isCollapsed = !this.isUndefined(collapseToStart);
        const selection = this.getSelection();
        const range = this.getRange();

        if (this.isElementNode(node)) {
            range.selectNode(node);
            if (isCollapsed) {
                range.collapse(collapseToStart);
            }
        }

        selection.empty();
        selection.addRange(range);
        return range;
    },

    insertNodeAtSelection: function (html) {
        const range = this.getRange();

        if (!range.collapsed) {
            range.deleteContents();
        }

        const shadow = this.doc.createElement('temp-shadow');
        shadow.append(html);

        const newRange = this.createRange();
        newRange.selectNodeContents(shadow);

        range.insertNode(newRange.extractContents());
        range.collapse(false);

        const node = this.getRangeElement(range);
        node.normalize();

        this.updateToolbar();
    },

    insertHtmlAtSelection: function (html) {
        this.editareaFocus();

        if (typeof html !== 'string') {
            return;
        }

        let range = this.getRange();
        const selection = this.getSelection();
        const frag = range.createContextualFragment(html);

        if (!range.collapsed) {
            this.queryCommand('delete');
            range = this.getRange();
        }

        range.insertNode(frag);
        range.collapse(false);

        if (!this.findClosestBlockElement(range.startContainer)) {
            this.queryCommand('formatBlock', 'p');
            range = this.getRange();
        }

        const rangeNode = this.getRangeElement(range);
        rangeNode.normalize();

        selection.empty();
        selection.addRange(range);
    },

    applyLineHeight : function (opt) {
        const range = this.getRange();
        const isCollapsed = range.collapsed;
        const staticRange = this.createStaticRange(range);
        const selection = this.getSelection();
        const { startContainer, endContainer } = staticRange;
        const startNode = this.findClosestBlockElement(startContainer);
        const endNode = this.findClosestBlockElement(endContainer);
        let rootNode = startNode;

        if (!startNode.contains(endNode)) {
            rootNode = rootNode.parentNode;
            while (rootNode && !rootNode.contains(endNode)) {
                rootNode = startNode.parentNode;
            }
        }
        const walker = this.doc.createTreeWalker(rootNode,
            NodeFilter.SHOW_ELEMENT | NodeFilter.SHOW_TEXT,
            {
                acceptNode: function (node) {
                    if (isCollapsed) {
                        return NodeFilter.FILTER_ACCEPT;
                    }
                    return range.intersectsNode(node)
                        ? NodeFilter.FILTER_ACCEPT
                        : NodeFilter.FILTER_REJECT;
                }
            });

        let currNode = startNode;
        walker.currentNode = currNode;

        let currPara = null;

        while (currNode) {
            if (GB.lineHeightElements.includes(currNode.nodeName)) {
                currNode.style.lineHeight = opt;
                currNode = walker.nextSibling();
            } else {
                const isTextOrInline = currNode.nodeType === Node.TEXT_NODE ||
                    GB.inlineElements.includes(currNode.nodeName);

                if (isTextOrInline) {
                    if (!currPara) {
                        currPara = this.doc.createElement('p');
                        currPara.style.lineHeight = opt;
                        currNode.before(currPara);
                    }
                    currPara.append(currNode);
                    walker.currentNode = currPara;
                    currNode = walker.nextSibling();
                    continue;
                }

                currPara = null;
                currNode = walker.nextNode();
            }
        }

        this.restoreStaticRange(selection, staticRange);
    },

    processImage: function (data) {
        if (!data) {
            return null;
        }
        const FIGURE_STYLE = GB.elementsStyle.figure;
        const createImage = (attr) => {
            const image = new Image();
            image.src =  attr.fileUrl;
            image.sizes = "100vw";
            image.srcset = `${attr.fileUrl} 2x`;
            image.width = attr.width;
            image.height = attr.height;
            image.setAttribute('style', FIGURE_STYLE.image.style);
            image.setAttribute('alt',this.config.imgSetAttrAlt ? attr.origName : '');
            return image;
        };
        const createStyledElement = (tagName, style, attrs = []) => {
            const elem = this.doc.createElement(tagName);
            elem.setAttribute('style', style);
            attrs.forEach(({name, value}) => elem.setAttribute(name, value));
            return elem;
        };

        const align = GB.elementsStyle.figure.align;
        const figure = createStyledElement('figure', FIGURE_STYLE.style, FIGURE_STYLE.attrs);
        const picture = createStyledElement('picture', FIGURE_STYLE.picture.style);
        let margin = null;

        if (data.alignment && Object.hasOwn(align, data.alignment)) {
            margin = align[data.alignment];
        }

        if (margin) {
            figure.style.margin = margin;
        }

        const shadow = this.doc.createElement('temp-shadow');

        data.images.forEach((imageData) => {
            const image = createImage(imageData);
            const newFigure = figure.cloneNode(false);
            const newPicture = picture.cloneNode(false);
            newPicture.appendChild(image);
            newFigure.appendChild(newPicture);
            shadow.appendChild(newFigure);
        });

        return shadow;
    },

    doInsertImage: function (data) {
        const shadow = this.processImage(data);
        if (!shadow) {
            return;
        }

        const lastPara = this.insertParagraph();
        if (!lastPara) {
            return;
        }

        const range = this.createRange();
        range.selectNodeContents(shadow);
        lastPara.before(range.extractContents());

        const nextSibling = lastPara.nextSibling || lastPara;
        if (nextSibling !== lastPara) {
            lastPara.remove();
        }

        this.placeCaretAt(nextSibling, true);
    },

    insertVideo: function (data) {
        const figure = this.doc.createElement('figure');
        figure.setAttribute('style', GB.elementsStyle.figure.style);
        figure.setAttribute('contenteditable', 'false');
        figure.className = 'video';

        const range = this.createRange();
        figure.append(range.createContextualFragment(data.source));

        const para = this.insertParagraph();
        para.replaceWith(figure);
    },

    tabRepeat: function (count) {
        let i = 0, tab = '';
        if (count < 1) {
            return tab;
        }
        for (; i < count; i++) {
            tab += this.cheditor.tabSpaces;
        }
        return tab;
    },

    checkHyperLinks: function () {
        const links = this.doc.links;
        const len = links.length;
        const host = location.host;
        let i, href;

        this.cheditor.links = [];

        for (i = 0; i < len; i++) {
            if (!this.config.includeHostname) {
                href = links[i].href;
                if (href.indexOf(host) !== -1) {
                    links[i].setAttribute('href',
                        href.substring(href.indexOf(host) + host.length));
                }
            }
            if (this.config.linkTargetAttr) {
                if (!links[i].getAttribute('target')) {
                    links[i].setAttribute('target', this.config.linkTargetAttr);
                }
            }
        }
    },

    checkInsertedImages: function () {
        const images = this.doc.images;
        const len = images.length;
        const host = location.host;
        let i = 0, imgUrl;

        for (; i < len; i++) {
            if (!this.config.includeHostname) {
                imgUrl = images[i].src;
                if (imgUrl) {
                    if (imgUrl.indexOf(host) !== -1) {
                        images[i].src = imgUrl.substring(imgUrl.indexOf(host) + host.length);
                    }
                }
            }
            if (images[i].style.width) {
                images[i].removeAttribute('width');
            }
            if (images[i].style.height) {
                images[i].removeAttribute('height');
            }
        }
    },

    createNbspTextNode: function () {
        return this.doc.createTextNode('\u00a0');
    },

    getNodeTree: function (root, cb) {
        function Nodes(node) {
            this.node = node;
            this.name = node.nodeName.toLowerCase();
            this.type = node.nodeType;
            this.parent = node.parentNode;
            this.indent = 0;
            this.attrs = node.attributes;
            this.close = false;
            this.self_closing = true;
        }

        (function recurse(parentNode, indent) {
            let i,
                node = new Nodes(parentNode),
                nodes = parentNode.childNodes,
                len = nodes.length;

            node.indent = indent;

            if (!node.node.isSameNode(root)) {
                if (node.type === Node.ELEMENT_NODE &&
                    !GB.selfClosingElements.includes(node.node.nodeName))
                {
                    node.self_closing = false;
                }
                cb(node);
            }

            for (i = 0; i < len; i++) {
                recurse(nodes[i], indent + 1);
            }

            if (!node.self_closing) {
                node.close = true;
                cb(node);
            }
        })(root, -1);
    },

    getContents: function () {
        let self = this,
            indentNodes = [], i, node,
            allowedIndent = this.getContents.caller !== this.getBodyContents,

            insertTabSpace = (indent) => {
                return this.doc.createTextNode('\n' + this.tabRepeat(indent));
            };

        this.checkHyperLinks();
        this.checkInsertedImages();

        this.getNodeTree(this.body, (node) => {
            if (!node.node) {
                return;
            }

            if (this.config.exceptedElements[node.name]) {
                node.node.replaceWith(this.doc.createTextNode(''));
                return;
            }

            if (this.isTextNode(node.node)) {
                if (!this.isTextVisible(node.node.nodeValue)) {
                    node.node.replaceWith(this.doc.createTextNode(''));
                }
                return;
            }

            if (this.isElementNode(node.node) && allowedIndent) {
                if (this.isBlockElement(node.node) || GB.listElements.items.includes(node.name.toUpperCase())) {
                    indentNodes.push(node);
                }
                if (GB.selfClosingElements.includes(node.name)) {
                    node.node.setAttribute('self-close', '1');
                }
            }
        });

        for (i in indentNodes) {
            node = indentNodes[i];
            node.node.before(insertTabSpace(node.indent));
            node.node.prepend(insertTabSpace(node.indent + 1));
            node.node.append(insertTabSpace(node.indent));
        }

        indentNodes = [];
        let contents = this.getBodyHTML();

        return contents.replace(/^\s*[\r\n]/gm, '')
            .replace(/<[^>]+>/gm, match=> {
                match = match.replace(/\sself-close="1"([^>]*?)/g, '$1');
                if (!this.config.allowedOnEvent) {
                    match = match.replace(/\s+on([A-Za-z]+)=("[^"]*"|'[^']*'|[^\s>]*)/g, '');
                }
                if (this.config.colorToHex) {
                    match = match.replace(/(background-color|color)\s*([:=])\s*(rgba?)\(\s*(\d+)\s*,\s*(\d+),\s*(\d+)\)/ig,
                        (all, p, s, rgb, r, g, b) => {
                            return p + s + ' ' + this.colorConvert(rgb + '(' + r + ',' + g + ',' + b + ')', 'hex');
                        });
                } else {
                    match = match.replace(/(background-color|color)\s*([:=])\s*(#[A-Fa-f0-9]{3,6})/ig,
                        function (all, p, s, hex) {
                            return p + s + ' ' + self.colorConvert(hex, 'rgb');
                        });
                }
                return match;
            });
    },

    returnContents: function (html) {
        this.setDesignMode(true);
        this.cheditor.inputForm.value = html;
        return html;
    },

    br2nl: function (html, mode) {
        return html.replace(/<\/?(br|br[\s/]+?[^>]*?)>/ig, mode ? '\n' : '');
    },

    nl2br: function (text, mode) {
        return (text + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g,
            mode ? '$1<br>' : '$1<br>$2');
    },

    removeNls: function (text) {
        return (text + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)\s*/g, '$1');
    },

    removeEmptyTags: function (text) {
        const regExp = /<(?<open>\w+)[^>]*?><\/(?<close>[^>]+)>/g;
        let cleanedText = this.removeNls(text);
        let match;

        while ((match = regExp.exec(cleanedText)) !== null) {
            if (match.groups.open === match.groups.close) {
                const beforeMatch = cleanedText.substring(0, match.index);
                cleanedText = beforeMatch + cleanedText.substring(match.index + match[0].length);
                regExp.lastIndex = 0;
            }
        }

       return cleanedText;
    },

    removeEmptyNode: function (node) {
        if (!node.hasChildNodes()) {
            node.remove();
        }
    },

    escapeHTML: function (html) {
        return html.replace(GB.escapeHTMLRegex, char => GB.escapeHTMLMap[char] || char);
    },

    unescapeHTML: function (text) {
        return text.replace(GB.unescapeHTMLRegex, char => GB.unescapeHTMLMap[char] || char);
    },

    getOutputContents: function () {
        return this.removeNls(this.getBodyContents());
    },

    createOutputHTML: function () {
        const contents = `
        <!DOCTYPE html>
        <html lang="ko">
          <head>
            <title>${this.config.docTitle}</title>
          </head>
          <body>
          ${this.getBodyContents()}
          </body>
        </html>`;
        return this.removeNls(contents);
    },

    outputHTML: function () {
        return this.returnContents(this.createOutputHTML());
    },

    getBodyContents: function () {
        this.clearHelperNodes();
        return this.getContents();
    },

    outputBodyHTML: function () {
        return this.returnContents(this.getOutputContents());
    },

    outputBodyText: function () {
        return this.returnContents(this.getBodyText());
    },

    getBodyHTML: function (includeBodyTag = false) {
        return includeBodyTag ? this.body.outerHTML : this.getInnerHTML(this.body);
    },

    getBodyText: function () {
        return this.trimSpace(this.body.textContent);
    },

    returnFalse: function () {
        let images = this.doc.images, i;
        this.editareaFocus();

        for (i = 0; i < images.length; i++) {
            const image = images[i];
            if (image.src) {
                if (image.getAttribute('onload')) {
                    image.onload = 'true';
                }
            } else {
                images.removeAttribute('onload');
                images.removeAttribute('className');
            }
        }
        return false;
    },

    removeZeroSpace: function (rawText) {
        return rawText.replace(/[\u200b-\u200d\ufeff]+/g, '');
    },

    trimSpace: function (rawText) {
        return rawText
            .trim()
            .replace(/^[\u200b-\u200d\ufeff]+|[\u200b-\u200d\ufeff]+$/g, '');
    },

    makeRandomString: function () {
        const allowedChars  = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXTZabcdefghiklmnopqrstuvwxyz';
        const defaultLength = 16;
        let result = '';

        for (let i = 0; i < defaultLength; i++) {
            result += allowedChars[Math.floor(Math.random() * allowedChars.length)];
        }

        return result;
    },

    calcContentsLength: function (str) {
        let len = str.length, multiBytes = 0, i, c;

        for (i = 0; i < len; i++) {
            c = str.charCodeAt(i);
            if (c > 128) {
                multiBytes++;
            }
        }

        return len - multiBytes + multiBytes * 2;
    },

    contentsLengthAll: function () {
        return this.calcContentsLength(this.createOutputHTML());
    },

    contentsLength: function () {
        const content = this.trimSpace(this.getOutputContents());
        if (!content) {
            return 0;
        }
        return this.calcContentsLength(content);
    },

    inputLength: function () {
        const content = this.getBodyText();
        if (!content) {
            return 0;
        }
        return this.calcContentsLength(content);
    },

    showDropdownMenu: function (button, id) {
        const dropdown = this.dropdown[id];
        dropdown.style.left = button.offsetLeft + 'px';
        dropdown.style.top = button.offsetTop + button.offsetHeight + 2 + 'px';

        this.backupRange();
        this.closeDropdownMenu(id);

        dropdown.querySelectorAll('.dropdown-item').forEach(elem => {
            if (elem.dataset.value === this.toolbar[id].data) {
                elem.classList.add('active');
            } else {
                elem.classList.remove('active');
            }
        });
        dropdown.classList.remove('d-none');

        setTimeout(() => {
            this.removeEvent(dropdown, 'transitionend', this.dropdownEffectEvent);
            dropdown.classList.add('show');
        }, 10);

        dropdown.style.zIndex = (this.modalZIndex + 1).toString();

        const dropdownShowEvent = new CustomEvent('dropdown-show', {
            cancelable: true,
            bubbles: true,
            detail: id
        });
        this.doc.dispatchEvent(dropdownShowEvent);
    },

    dropdownEffectEvent: function (evt) {
        evt.target.classList.add('d-none');
    },

    closeDropdownMenu: function (showId = null) {
        let dropdown, displayNone;

        for (const button in this.dropdown) {
            dropdown = this.dropdown[button];
            if (dropdown.classList.contains('show')) {
                this.addEvent(dropdown, 'transitionend', this.dropdownEffectEvent);
                dropdown.classList.remove('show');
            }

            displayNone = !showId ? true : button !== showId;
            if (displayNone && this.toolbar[button].checked) {
                this.toolbar[button].checked = false;
                this.toolbar[button].elem.classList.remove('active');
            }
        }
    },

    createDropdown: function (name, width, elem, className = null) {
        const dropdown = document.createElement('div');

        elem.className = "cheditor-dropdown-container";
        if (width > 0) {
            elem.style.setProperty("width", `${width}px`, "important");
        }

        if (elem.childElementCount > 0) {
            for (const child of elem.children) {
                if (!child.classList.contains("dropdown-item-quote") &&
                    !child.classList.contains("table-container")) {
                    child.className = "dropdown-item";
                    if (className) {
                        child.classList.add(className);
                    }
                }
            }
        }

        dropdown.className = 'cheditor-dropdown-frame fade d-none';
        dropdown.append(elem);
        this.dropdown[name] = dropdown;
        this.cheditor.toolbarWrapper.append(dropdown);
    },

    createTableDropdown: function () {
        const TABLE_CELL_SIZE = 15;
        const CLASS_NAMES = {
            dropdown: 'dropdown-item-table',
            container: 'table-container',
            outer: 'table-outer',
            info: 'info',
        };
        const dropdown = document.createElement('div');
        const outer = dropdown.cloneNode(false);
        const container = dropdown.cloneNode(false);
        const info = dropdown.cloneNode(false);
        const maxRow = GB.dropdown.table.maxRow;
        const maxCol = GB.dropdown.table.maxCol;
        const table = document.createElement('table');

        for (let i = 0; i < maxRow; i++) {
            const row = table.insertRow();
            for (let j = 0; j < maxCol; j++) {
                const cell = row.insertCell();
                cell.append(GB.zeroWidthSpace);
            }
        }

        outer.append(table);
        dropdown.className = CLASS_NAMES.dropdown;
        container.className = CLASS_NAMES.container;
        outer.className = CLASS_NAMES.outer;
        info.className = CLASS_NAMES.info;

        container.append(outer, info);
        dropdown.append(container);

        const updateSelection = (row, col) => {
            for (let i = 0; i < maxRow; i++) {
                for (let j = 0; j < maxCol; j++) {
                    const cell = table.rows[i].cells[j];
                    if (i + 1 <= row && j + 1 <= col) {
                        cell.className = 'selected';
                        info.dataset.row = String(i + 1);
                        info.dataset.col = String(j + 1);
                        info.textContent = `${i + 1} X ${j + 1}`;
                    } else {
                        cell.removeAttribute('class');
                    }
                }
            }
        };

        this.addEvent(this.doc, 'dropdown-show', (evt) => {
            this.stopEvent(evt);
            if (evt.detail === 'Table') {
                updateSelection(1, 1);
            }
        });

        let prevRow = 1, prevCol = 1;

        this.addEvent(outer, 'mousemove', (evt) => {
            const rect = outer.getBoundingClientRect();
            const row = Math.min(Math.max(Math.ceil((evt.clientY - rect.top) / TABLE_CELL_SIZE), 1), maxRow);
            const col = Math.min(Math.max(Math.ceil((evt.clientX - rect.left) / TABLE_CELL_SIZE), 1), maxCol);

            if (prevRow !== row || prevCol !== col) {
                updateSelection(row, col);
                prevRow = row;
                prevCol = col;
            }
        });

        this.addEvent(outer, 'mouseup', (evt) => {
            this.stopEvent(evt);
            this.closeDropdownMenu();
            this.createTable(info.dataset.row, info.dataset.col);
        });

        return dropdown;
    },

    createTableEventHandler: function (table, figure = null) {
        const RESIZER_POSITION_ADJUSTMENT = 4.5;
        const columnsResizer = [];
        let columnMinWidth = parseFloat(this.getComputedStyleValue(this.doc.documentElement, 'font-size'));
        let cursorStartX = 0;
        let currColumn = null;
        let currColumnWidth = 0;
        let nextColumn = null;
        let nextColumnWidth = 0;
        let figureRect = null;
        let currResizer = null;
        let isDragging = false;
        let rootNodeWidth = 0;
        let figureWidth = 0;
        let isMouseLeave = false;
        let minTableWidth = 0;
        let targetColumn = null;

        const resizeObserver = new ResizeObserver((entries) => {
            for (const entry of entries) {
                const cell = entry.target;
                const rect = cell.getBoundingClientRect();
                const resizer = columnsResizer[cell.cellIndex];
                resizer.style.left = setResizerPosition(rect.right, figureRect.left);
            }
        });

        if (columnMinWidth < 15) {
            columnMinWidth *= 2;
        }

        if (!figure) {
            figure = table.closest('figure');
            if (!figure) {
                figure = this.doc.createElement('figure');
                const range = this.createRange();
                range.selectNode(table);
                range.surroundContents(figure);
            }
            figure.setAttribute('style', GB.elementsStyle.figure.style);
            figure.setAttribute('contenteditable', 'false');
        }

        const setResizerPosition = (x1, x2) => {
            return `${x1 - (x2 + RESIZER_POSITION_ADJUSTMENT)}px`;
        };

        const columnResizeHandler = (evt) => {
            if (evt.movementX === 0) {
                return;
            }
            const movementX = evt.pageX - cursorStartX;
            const updateCurrColumnWidth = currColumnWidth + movementX;
            let updateNextColumnWidth = 0;

            if (nextColumn) {
                updateNextColumnWidth = nextColumnWidth - movementX;

                if (updateCurrColumnWidth < columnMinWidth || updateNextColumnWidth < columnMinWidth) {
                    this.stopEvent(evt);
                    return;
                }

                targetColumn.style.width =
                    `${((updateCurrColumnWidth / figureWidth) * 100).toFixed(2)}%`;
                nextColumn.style.width = `${((updateNextColumnWidth / figureWidth) * 100).toFixed(2)}%`;
            } else {
                const updateFigureWidth = figureWidth + (movementX * 2);
                let newFigureWidth = ((updateFigureWidth / rootNodeWidth) * 100).toFixed(2);

                if (updateFigureWidth < minTableWidth) {
                    this.stopEvent(evt);
                    return;
                }

                if (newFigureWidth > 100) {
                    this.stopEvent(evt);
                    newFigureWidth = 100;
                }

                figure.style.width = `${newFigureWidth}%`;
                figureRect = figure.getBoundingClientRect();
            }
        };

        const removeResizer = () => {
            columnsResizer.forEach(rect => rect.remove());
            columnsResizer.length = 0;
        };

        this.addEvent(figure, 'mouseenter', (evt) => {
            isMouseLeave = false;

            if (isDragging) {
                this.stopEvent(evt);
                return;
            }

            this.createTableButton(figure);

            const columnResizer = this.doc.createElement('div');
            const colGroup = figure.querySelector('table colgroup');

            columnResizer.className = 'table-col-resizer';
            figureRect = figure.getBoundingClientRect();

            const stopResizerHandler = () => {
                this.removeEvent(this.doc, 'mousemove', columnResizeHandler);
                this.removeEvent(this.doc, 'mouseup', stopResizerHandler);
                isDragging = false;

                figure.classList.remove('col-resizing');

                if (isMouseLeave) {
                    removeResizer();
                    this.removeTableButton(figure);
                    return;
                }

                currResizer.classList.remove('active');
            };
            const getColumnsRect = () => {
                const row = table.rows[0];
                return [...row.cells].map((cell, idx, cells) => {
                    const rect = cell.getBoundingClientRect();
                    const isLast = idx === cells.length - 1;
                    rect.node = cell;
                    rect.col = colGroup.children[idx];
                    rect.nextCol = isLast ? null : colGroup.children[idx + 1];
                    rect.nextCell = isLast ? null : cells[idx + 1];
                    rect.index = idx;
                    rect.isLastChild = isLast;
                    return rect;
                });
            };
            const createResizerNodes = () => {
                const columnsRect = getColumnsRect();

                minTableWidth = columnsRect.length * columnMinWidth;

                columnsRect.forEach((rect) => {
                    const cloneResizer = columnResizer.cloneNode(false);
                    cloneResizer.style.left = setResizerPosition(rect.right, figureRect.left);
                    figure.append(cloneResizer);
                    columnsResizer.push(cloneResizer);

                    resizeObserver.observe(rect.node);

                    if (rect.isLastChild) {
                        cloneResizer.classList.add('last-child');
                    }

                    this.addEvent(cloneResizer, 'mousedown', (evt) => {
                        this.stopEvent(evt);

                        currColumn = rect.node;
                        cursorStartX = evt.pageX;
                        currColumnWidth = currColumn.getBoundingClientRect().width;
                        currResizer = cloneResizer;
                        currResizer.dataset.column = String(rect.index);
                        currResizer.classList.add('active');
                        isDragging = true;
                        rootNodeWidth = this.getElementWidth(figure.parentElement);
                        figureWidth = this.getElementWidth(figure);
                        figure.classList.add('col-resizing');

                        targetColumn = rect.col;
                        nextColumn = rect.nextCol;

                        if (nextColumn) {
                            nextColumnWidth = rect.nextCell.getBoundingClientRect().width;
                        }

                        this.addEvent(this.doc, 'mousemove', columnResizeHandler);
                        this.addEvent(this.doc, 'mouseup', stopResizerHandler);
                    });
                });
            };

            createResizerNodes();
        });

        this.addEvent(figure, 'mouseleave', () => {
            if (!isDragging) {
                resizeObserver.disconnect();
                removeResizer();
                this.removeTableButton(figure);
            }
            isMouseLeave = true;
        });
    },

    createTableColumnGroup: function (count) {
        const PERCENTAGE_WIDTH = (100 / count).toFixed(2);
        const colGroup = this.doc.createElement('colgroup');
        for (let i = 0; i < count; i++) {
            const col = this.doc.createElement('col');
            col.style.width = `${PERCENTAGE_WIDTH}%`;
            colGroup.append(col);
        }
        return colGroup;
    },

    insertTableRows: function (table, rowCount, colCount) {
        for (let i = 0; i < rowCount; i++) {
            const row = table.insertRow();
            this.insertTableCells(row, colCount);
        }
        return table;
    },

    insertTableCells: function (row, count) {
        for (let i = 0; i < count; i++) {
           const cell = row.insertCell();
           cell.append(this.doc.createElement('br'));
        }
    },

    applyTableCellAttributes: function (cell) {
        cell.setAttribute('contenteditable', 'true');
        cell.setAttribute('style', GB.dropdown.table.cellStyle);
    },

    applyTableCellsAttributes: function (cells) {
        cells.forEach(cell => this.applyTableCellAttributes(cell));
    },

    createTable: function (rowCount, colCount) {
        const TABLE_STYLE = GB.dropdown.table.tableStyle;
        const FIGURE_STYLE = GB.elementsStyle.figure.style;

        // Step 1: 테이블 행, 열 생성
        const table = this.doc.createElement('table');
        table.prepend(this.createTableColumnGroup(colCount));
        this.insertTableRows(table, rowCount, colCount);

        // 테이블 속성 지정
        const tableId = this.makeRandomString();
        table.id = tableId;
        table.setAttribute('style', TABLE_STYLE);

        // Step 2: 테이블 삽입
        this.queryCommand('insertHTML', table.outerHTML);
        this.editareaFocus();

        const insertedTable = this.doc.getElementById(tableId);
        const firstCell = insertedTable.querySelector('td');
        insertedTable.removeAttribute('id');
        this.applyTableCellsAttributes([...insertedTable.querySelectorAll('td')]);

        // Step 3: 테이블을 figure로 감싸서 이벤트 처리
        const figure = this.doc.createElement('figure');
        const range = this.createRange();
        range.selectNode(insertedTable);
        range.surroundContents(figure);
        figure.setAttribute('style', FIGURE_STYLE);
        figure.setAttribute('contenteditable', 'false');
        figure.className = 'table';

        // Step 4: 테이블 컬럼 크기 조절, 이벤트 처리
        this.createTableEventHandler(insertedTable, figure);

        this.placeCaretAt(firstCell);
        this.updateToolbar();
    },

    setColorTable: function (aCommand) {
        const TOOLTIP_SHOW = '더 많은 색 보기';
        const TOOLTIP_HIDE = '감추기';
        const createElementWithClass = (tag, className, attrs = null) => {
            const elem = document.createElement(tag);
            if (className) {
                elem.className = className;
            }
            if (!attrs) {
                return elem;
            }

            Object.entries(attrs).forEach(([attr, value]) => {
                value = value.toString();
                if (value.startsWith('rgba')) {
                    elem.classList.add('remove');
                } else {
                    elem.style.backgroundColor = value;
                }
                elem.setAttribute(attr, value);
            });

            return elem;
        };
        const buildColorCell = color => {
            const cellBorder = createElementWithClass('span', 'color-cell');
            const cell = createElementWithClass('span', 'color', {'data-color': color});
            cell.textContent = '\u00a0';
            cell.onclick = evt => this.doCmdPopup(aCommand, evt.target.dataset.color);
            cell.onmouseover = evt => colorPicker.fromString(evt.target.dataset.color);
            cellBorder.appendChild(cell);
            return cellBorder;
        };
        const togglePickerVisibility = () => {
            const colorNode = this.toolbar[aCommand].colorNode;
            const isVisible = colorNode.showPicker;
            const newState = !isVisible;

            colorPicker[newState ? 'showPicker' : 'hidePicker']();
            colorNode.showPicker = newState;
            pickerSwitch.setAttribute('title', newState ? TOOLTIP_HIDE : TOOLTIP_SHOW);
        };

        const dropdown = document.createElement('div');
        const container = createElementWithClass('div', 'color-container');
        const selected = createElementWithClass('input', '', {type: 'text', maxlength: '7', spellcheck: 'false'});
        const selectedValue = createElementWithClass('input', '', {type: 'text'});
        const paletteWrapper = createElementWithClass('div', 'palette-wrapper', {style: 'position:relative'});
        const pickerSwitch = createElementWithClass('span', 'show-picker', {'title': TOOLTIP_SHOW});
        const submitButton = createElementWithClass('span', 'cheditor-btn btn-xs');
        const palette = createElementWithClass('div', 'palette');
        const inputGroup = createElementWithClass('div', 'input-group');
        const colorPicker = new GB.colorDropper(selected, {iconDir: this.config.iconPath});

        GB.colors.forEach(color => palette.appendChild(buildColorCell(color)));

        selectedValue.onfocus = () => selected.focus();
        pickerSwitch.textContent = '\u00a0';
        pickerSwitch.onclick = togglePickerVisibility;
        submitButton.onclick = () => this.doCmdPopup(aCommand, selected.value);

        inputGroup.append(selectedValue, selected, pickerSwitch, submitButton);
        paletteWrapper.append(palette, inputGroup);
        container.appendChild(paletteWrapper);
        dropdown.appendChild(container);

        Object.assign(this.toolbar[aCommand].colorNode, {selectedValue, colorPicker});
        return dropdown;
    },

    isElementNode: function (node) {
        return !!node && node.nodeType === Node.ELEMENT_NODE;
    },

    isTextNode: function (node) {
        return !!node && node.nodeType === Node.TEXT_NODE;
    },

    getParentIfTextNode: function (node) {
        return this.isTextNode(node) ? node.parentNode : node;
    },

    removeInvisibleText: function (removeNode, cb) {
        const node = cb(removeNode);
        removeNode.remove();
        return node;
    },

    handleForceElementRemove: function (node) {
        const handleNodeRemoval = node => this.removeNode(node);

        if (node.nodeName === 'FIGURE') {
            const hasImage = node.querySelector('img');
            hasImage ? this.createImageFigure(node) : handleNodeRemoval(node);
        } else {
            handleNodeRemoval(node);
        }

        return true;
    },

    handleBackwardDelete: function (node) {
        let currNode = this.getPreviousSibling(node);
        return this.processInvisibleText(currNode, this.getPreviousSibling);
    },

    handleForwardDelete: function (node) {
        let currNode = this.getNextSibling(node);
        currNode = this.processInvisibleText(currNode, this.getNextSibling);

        const brElem = this.isBrElement(currNode);
        if (brElem) {
            brElem.replaceWith('\n');
            currNode = this.getNextSibling(currNode);
        }

        return currNode;
    },

    processInvisibleText: function (node, siblingGetter) {
        if (this.isTextNode(node) && !this.isTextVisible(node.data)) {
            return this.removeInvisibleText(node, siblingGetter);
        }
        return node;
    },

    removeNodeFromKeyEvent: function (node, direction) {
        let currNode = node;

        currNode = direction === 'backward'
            ? this.handleBackwardDelete(currNode)
            : this.handleForwardDelete(currNode);

        if (this.isForceRemoveElement(currNode)) {
            return this.handleForceElementRemove(currNode);
        }

        return false;
    },

    keyEventToolbarUpdateHandler: function (evt) {
        if (this.tempTimer) {
            clearTimeout(this.tempTimer);
        }
        this.tempTimer = setTimeout( () => {
            if (this.config.showTagSelector) {
                this.editareaEventHandler(evt.target);
            } else {
                this.updateToolbar();
            }
            this.tempTimer = null;
        }, 50);
    },

    onKeydown: function (evt) {
        if (evt.key === 'ArrowDown' || evt.key === 'ArrowUp' || evt.key === 'ArrowLeft' || evt.key === 'ArrowRight') {
            this.keyEventToolbarUpdateHandler(evt);
            return;
        }
        if (evt.key !== 'Backspace' && evt.key !== 'Delete') {
            return;
        }

        const selection = this.getSelection();
        const range = this.getRange();
        const cloneRange = range.cloneRange();
        let stopEvent = false,
            direction = evt.key === 'Delete' ? 'forward' : 'backward';

        if (selection.isCollapsed) {
            selection.modify("move", direction, "lineBoundary");
            const refRange = this.getRange();
            const refOffset = refRange.startOffset;
            const refNode = refRange.startContainer;
            let emptyParagraph = this.isEmptyParagraph(refRange.commonAncestorContainer),
                comparePos = cloneRange.comparePoint(refNode, refOffset),
                leaf;

            switch (comparePos) {
                case 0:
                    if (direction === 'backward' && refOffset > 0) {
                        comparePos = -1;
                        break;
                    }
                    if (direction === 'forward' && refNode.textContent.length > refOffset) {
                        comparePos = 1;
                        break;
                    }

                    leaf = this.getNextSibling(refNode);
                    if (leaf && this.isTextNode(leaf) && !this.isTextVisible(leaf.data)) {
                        leaf = this.removeInvisibleText(leaf, this.getNextSibling);
                    }
                    if (direction === 'forward' && !leaf && refOffset === 0) {
                        direction = 'backward';
                    }
                    break;
                case 1:
                    if (direction === 'backward' && refNode.textContent.indexOf('\n') !== -1) {
                        refNode.textContent = refNode.textContent.trimStart();
                        comparePos = 0;
                    } else if (direction === 'forward' && refNode.textContent.length === 0) {
                        comparePos = 0;
                    }
                    break;
                default:
                    if (direction === 'forward') {
                        comparePos = 0;
                    }
                    break;
            }

            if (comparePos === 0) {
                stopEvent = this.removeNodeFromKeyEvent(refNode, direction);
                if (stopEvent && emptyParagraph) {
                    emptyParagraph.remove();
                    emptyParagraph = null;
                }
            }

            if (direction === 'backward' &&
                emptyParagraph &&
                emptyParagraph.parentNode.isSameNode(this.body) &&
                !emptyParagraph.previousSibling) {
                stopEvent = true;
            }

            if (!stopEvent) {
                selection.empty();
                selection.addRange(cloneRange);
            }
        } else if (range.endContainer.nodeName === 'IMG') {
            let prevNode = this.getPreviousSibling(range.endContainer);
            if (prevNode) {
                if (this.isTextNode(prevNode) && !this.isTextVisible(prevNode.data)) {
                    prevNode = this.getPreviousSibling(prevNode);
                }
                range.setEndBefore(prevNode.nextSibling);
                selection.empty();
                selection.addRange(range);
            }
        } else if (range.startContainer.nodeName === 'IMG') {
            let nextNode = this.getNextSibling(range.startContainer);
            if (nextNode) {
                range.setStart(nextNode, 0);
                selection.empty();
                selection.addRange(range);
            }
        }

        if (!stopEvent && this.pictureBogusCaret) {
            const hasFakeCaret = this.hasBogusCaret();
            if (hasFakeCaret && hasFakeCaret.hasCaret) {
                const figure = this.pictureResizer.closest('figure');
                if (figure) {
                    stopEvent = this.removeNode(figure);
                }
            }
        }
        if (stopEvent) {
            this.stopEvent(evt);
        }
        this.keyEventToolbarUpdateHandler(evt);
    },

    onKeyup: function (evt) {
        if (evt.key !== 'Backspace' && evt.key !== 'Delete') {
            return;
        }
        const selection = this.getSelection(),
            direction = evt.key === 'Delete' ? 'forward' : 'backward',
            anchorNode = selection.anchorNode,
            anchorOffset = selection.anchorOffset;
        let brElem;

        if (direction === 'forward' &&
            this.isTextNode(anchorNode) &&
            !this.isTextVisible(anchorNode.textContent))
        {
            brElem = this.isBrElement(anchorNode.nextSibling);
            if (brElem) {
                selection.setPosition(brElem);
            }
        }

        brElem = this.isBrElement(anchorNode?.childNodes[anchorOffset]);
        if (!brElem) {
            return;
        }

        const staticRange = this.createStaticRange();
        this.setSelectionLine();

        if (this.getRange().getBoundingClientRect().width === 0) {
            let parentNode = brElem.parentNode;

            while (!GB.removeBreakElements.includes(parentNode.nodeName)) {
                parentNode = parentNode.parentNode;
            }

            if (this.isBody(parentNode)) {
                const p = this.createEmptyParagraph();
                brElem.replaceWith(p);
                selection.setPosition(p, 0);
            } else if (parentNode.childNodes.length > 1) {
                const text = parentNode.outerHTML.replace(/<\/?([A-Za-z]+)[^>]*?>/g,
                    (all, name) => { return name === 'img' ? all : ''; });
                if (text === '') {
                    parentNode.replaceChildren(brElem);
                }
            }
        } else {
            this.restoreStaticRange(selection, staticRange);
        }
    },

    onKeypress: function (evt) {
        if (evt.key !== 'Enter') {
            return;
        }

        const selection = this.getSelection();

        if (this.isElementNode(selection.anchorNode) &&
            selection.anchorNode.classList.contains('is-bogus-caret') &&
            this.pictureResizer)
        {
            const figure = this.pictureResizer.parentElement;
            if (figure) {
                const p = this.createEmptyParagraph();
                figure.after(p);
                selection.empty();
                selection.setPosition(p, 0);
                this.stopEvent(evt);
            }
        }

        this.setDefaultParagraphSeparator();
    },

    // eslint-disable-next-line no-unused-vars
    removeNode: function (node, selection = null, result = false) {
        if (node) {
            node.remove();
        }
        return result;
    },

    removeNodeProxy: function (func) {
        return new Proxy(func, {
            apply(target, that, args) {
                let node = args[0];
                const selection = args[1] ??= that.getSelection();

                if (that.isElementNode(node) && GB.forceRemoveElements.includes(node.nodeName)) {
                    const p = that.createEmptyParagraph();
                    selection.empty();
                    node.replaceWith(p);
                    selection.setPosition(p, 0);
                    args[0] = null;
                    args[2] = true;
                }

                return Reflect.apply(target, that, args);
            }
        });
    },

    setWindowPosition: function (oWindow, popupAttr, windowSize) {
        const windowHalfWidth = Math.round(windowSize.innerWidth / 2);
        const windowHalfHeight = Math.round(windowSize.innerHeight / 2);
        const popupHalfWidth = Math.round(oWindow.offsetWidth / 2);
        const popupHalfHeight = Math.round(oWindow.offsetHeight / 2);

        let offsetLeft = windowHalfWidth - popupHalfWidth;
        if (offsetLeft < 0) {
            offsetLeft = 0;
        }

        let top = windowHalfHeight - popupHalfHeight;
        if (top < 0) {
            top = 0;
        }

        oWindow.style.top = `${top}px`;
        oWindow.style.left = `${offsetLeft}px`;
    },

    getWindowSize: function (doc = document) {
        const rect = this.cheditor.container.getBoundingClientRect();
        const documentMetrics = {
                width: doc.body.clientWidth,
                height: doc.body.clientHeight,
                scrollHeight: doc.body.scrollHeight,
                scrollWidth: doc.body.scrollWidth,
                scrollY: window.scrollY,
                scrollX: window.scrollX,
                innerHeight: window.innerHeight,
                innerWidth: window.innerWidth,
                clientTop: doc.body.clientTop || 0,
                clientLeft: doc.body.clientLeft || 0
            };
        return {
            ...documentMetrics,
            editorRect: rect,
            offsetLeft: rect.left + documentMetrics.scrollX - documentMetrics.clientLeft
        };
    },

    popupWindowConfirm : function (obj, popup) {
        this.confirm(obj.message, (yes) => {
            if (yes) {
                this.postMessage(popup, {type: "confirm", action: true});
            }
        });
    },

    postMessage : function (obj, message) {
        obj.contentWindow.postMessage(message, '*');
    },

    isValidURL: function (str) {
        let url;
        try {
            url = new URL(str);
        } catch (e) {
            return null;
        }
        return url.protocol === 'http:' || url.protocol === 'https:' || url.protocol === 'mailto:';
    },

    getHyperlink: function () {
        const selection = this.getSelection(), links = {};

        if (selection.type === 'None') {
            return links;
        }

        const range = this.getRange();
        let container = range.commonAncestorContainer;

        if (container.nodeType === Node.TEXT_NODE) {
            container = container.parentNode;
        }

        container = container.closest('a');
        if (container && this.isValidURL(container.href)) {
            links.href = container.href;
            links.title = container.title;
        }

        return links;
    },

    popupWindowExecHandle: function (exec, argv) {
        let isReturn = true,
            isCancel = false;

        this.setDesignMode(true);
        this.restoreRange();

        switch (exec) {
            case 'insertHTML': this.insertHtmlAtSelection(argv); break;
            case 'createLink': this.createHyperLink(argv); break;
            case 'insertImage': this.doInsertImage(argv); break;
            case 'insertVideo': this.insertVideo(argv); break;
            case 'applySourceCode': this.loadContents(argv); break;
            case 'cancel':
                this.popupWindowCancel();
                isCancel = true;
                break;
            default:
                isReturn = false;
        }
        if (!isCancel) {
            this.popupWindowClose();
        }
        return isReturn;
    },

    popupWindowLoad: function (popupAttr) {
        const popupWindow = document.createElement('iframe'),
            docBody = document.querySelector('body'),
            popupReceiveMessage = (evt) => {
                const exec = evt.data.exec, value = evt.data.value;
                let isReturn = false;

                switch (exec) {
                    case 'requestSelected':
                        this.postMessage(popupWindow, {
                            type: 'responseData',
                            value: this.getHyperlink()
                        });
                        break;
                    case 'setDialog':
                        this.cheditor.modal.frameWrapper.style.height = value.bodyScrollHeight + 'px';
                        popupWindow.setAttribute('height', '100%');
                        popupWindow.setAttribute('width', value.bodyScrollWidth);
                        this.setWindowPosition(this.cheditor.modal, popupAttr, this.getWindowSize());
                        GB.modalBackdrop.classList.add('show');
                        this.cheditor.modal.classList.add('show');
                        break;
                    case 'confirm':
                        this.popupWindowConfirm(value, popupWindow);
                        break;
                    default:
                        isReturn = this.popupWindowExecHandle(exec, value);
                }

                if (!isReturn) {
                    return;
                }

                this.removeEvent(window, 'message', popupReceiveMessage);
                this.removeEvent(this.cheditor.modal.close, 'click', close);
            },
            popupWindowResize = () => {
                this.setDesignMode(false);
                const messageData = { type: 'init', value: this.config };
                if (popupAttr.tmpl === 'source.html') {
                    messageData.value.body = this.getContents();
                }
                popupWindow.contentWindow.focus();
                this.postMessage(popupWindow, messageData);
                this.removeEvent(popupWindow, 'load', popupWindowResize);
            }, close = () => {
                this.postMessage(popupWindow, { type: 'close' });
            };

        this.addEvent(window, 'message', popupReceiveMessage);
        this.addEvent(popupWindow, 'load', popupWindowResize);
        this.addEvent(this.cheditor.modal.close, 'click', close);

        // self.cheditor.modal.id = 'cheditor-modal';
        this.cheditor.modal.title_text.append(document.createTextNode(popupAttr.title));
        this.cheditor.modal.style.zIndex = (this.modalZIndex + 1).toString();

        popupWindow.setAttribute('name', popupAttr.tmpl);
        popupWindow.setAttribute('src', this.config.popupPath + '/' + popupAttr.tmpl);
        popupWindow.id = popupAttr.tmpl;

        // backdrop
        GB.modalBackdrop = document.createElement('div');
        GB.modalBackdrop.classList.add('cheditor-backdrop', 'fade');
        GB.modalBackdrop.style.zIndex = this.modalZIndex;
        docBody.append(GB.modalBackdrop);
        GB.modalBackdrop.before(this.cheditor.modal);

        this.cheditor.modal.frameWrapper.append(popupWindow);
        this.cheditor.modal.style.display = 'block';

        this.addEvent(this.cheditor.modal, 'dragover', evt => {
            evt.dataTransfer.effectAllowed = 'none';
            evt.dataTransfer.dropEffect = 'none';
            this.stopEvent(evt);
        });
        this.addEvent(GB.modalBackdrop, 'dragover', evt => {
            evt.dataTransfer.effectAllowed = 'none';
            evt.dataTransfer.dropEffect = 'none';
            this.stopEvent(evt);
        });

        GB.dragWindow.init(this.cheditor.modal.titlebar, this.cheditor.modal);
    },

    popupWindowCancel: function () {
        this.popupWindowClose();
    },

    popupWindowClose: function () {
        if (GB.modalBackdrop) {
            GB.modalBackdrop.classList.remove('show');
            GB.modalBackdrop.ontransitionend = () => {
                GB.modalBackdrop.remove();
                GB.modalBackdrop = null;
            };
        }

        if (!this.cheditor.modal) {
            return;
        }

        this.cheditor.modal.classList.remove('show');
        this.cheditor.modal.ontransitionend = () => {
            if (!this.cheditor.modal.classList.contains('show')) {
                this.cheditor.modal.style.display = 'none';
                if (this.cheditor.modal.frameWrapper.hasChildNodes()) {
                    this.cheditor.modal.frameWrapper.removeChild(this.cheditor.modal.frameWrapper.firstChild);
                }
                if (this.cheditor.modal.title_text.hasChildNodes()) {
                    this.cheditor.modal.title_text.removeChild(this.cheditor.modal.title_text.firstChild);
                }
            }
        };

        if (this.modalReSize) {
            if (GB.browser.opera) {
                self.removeEvent(window, 'resize', this.modalReSize);
            }
            this.modalReSize = null;
        }
        if (this.toolbar[this.cheditor.modal.id].checked) {
            this.toolbarButtonChecked(this.toolbar[this.cheditor.modal.id], false);
        }
    },

    clearStoredSelections: function () {
        this.storedSelections.length = 0;
    },

    restoreRange: function () {
        this.editareaFocus();
        if (this.storedSelections.length > 0) {
            const selection = this.getSelection();
            selection.empty();

            for (const storedRange of this.storedSelections) {
                selection.addRange(storedRange);
            }
        }
        this.clearStoredSelections();
    },

    keyPressBackupRange: function () {
        const selection = this.getSelection();

        for (let i = 0; i < selection.rangeCount; i++) {
            this.keyPressStoredSelections.push(selection.getRangeAt(i));
        }
    },

    backupRange: function () {
        const selection = this.getSelection();

        this.clearStoredSelections();
        for (let i = 0; i < selection.rangeCount; i++) {
            this.storedSelections.push(selection.getRangeAt(i));
        }
    },

    getSelection: function () {
        return this.editarea.getSelection();
    },

    clearSelection: function () {
        const selection = this.getSelection();

        if (selection.rangeCount > 0) {
            selection.empty();
        }

        return selection;
    },

    isBackwardSelection: function (selection) {
        const position = selection.anchorNode.compareDocumentPosition(selection.focusNode);

        return !position && selection.anchorOffset > selection.focusOffset ||
            position === Node.DOCUMENT_POSITION_PRECEDING;
    },

    getRange: function () {
        let selection = this.getSelection(),
            range;

        if (selection.getRangeAt) {
            range = selection.rangeCount ? selection.getRangeAt(0) : this.createRange();
        } else {
            range = this.createRange();
            if (this.isBackwardSelection(selection)) {
                range.setStart(selection.focusNode, selection.focusOffset);
                range.setEnd(selection.anchorNode, selection.anchorOffset);
            } else {
                range.setStart(selection.anchorNode, selection.anchorOffset);
                range.setEnd(selection.focusNode, selection.focusOffset);
            }
        }

        this.range = range;
        return range;
    },

    createRange: function () {
        return this.doc.createRange();
    },

    getRangeElement: function (range) {
        const { startContainer, startOffset, endContainer, endOffset, collapsed } = range;
        const shouldUseChildNode = () => {
            return (
                !collapsed &&
                this.isElementNode(startContainer) &&
                startContainer === endContainer &&
                endOffset - startOffset === 1 &&
                startContainer.hasChildNodes()
            );
        };
        const node = shouldUseChildNode()
            ? startContainer.childNodes[startOffset]
            : startContainer;

        return this.getParentIfTextNode(node);
    },

    getSelectionType: function () {
        const selection = this.getSelection();
        let selectionType = selection.type;

        if (selectionType === 'None') {
            return 1;
        }

        if (selectionType === 'Range' && !selection.toString()) {
            return 3;
        }

        return 2;
    },

    popupWindowOpen: function (button) {
        this.closeDropdownMenu();
        this.editareaFocus();
        this.backupRange();

        const popup = GB.popupWindowTmpl[button.argv];
        if (this.isUndefined(popup)) {
            alert("사용할 수 없는 명령입니다.");
            return;
        }

        this.popupWindowLoad(popup);
        this.cheditor.modal.id = button.name;
        this.toolbarButtonChecked(button, true);
    },

    fontSizeUpDown: function (command) {
        const currSize = Number(this.toolbar["FontSize"].data);
        let idx;

        if (isNaN(currSize) || currSize < 1) {
            return;
        }

        if (command === "FontSizeDown") {
            idx = GB.dropdown.fontSize.findLastIndex(size => size <= currSize);
            if (idx > 0) {
                idx = idx - 1;
            }
        } else {
            idx = GB.dropdown.fontSize.findIndex(size => size >= currSize);
            if (idx < GB.dropdown.fontSize.length - 1) {
                idx = idx + 1;
            }
        }

        const selection = this.getSelection();
        if (selection.isCollapsed) {
            const range = selection.getRangeAt(0);
            let ancestor = selection.focusNode;

            if (!this.isTextNode(ancestor)) {
                return;
            }

            ancestor = this.findTextFormatAncestor(selection.focusNode);
            const staticRange = this.createStaticRange(range);

            selection.selectAllChildren(ancestor);
            this.doCmdPopup("FontSize", GB.dropdown.fontSize[idx], ancestor);
            this.restoreStaticRange(selection, staticRange);
            return;
        }

        this.doCmdPopup("FontSize", GB.dropdown.fontSize[idx]);
    },

    pageBreak: function () {
        const hr = this.createHorizontalRule();
        hr.setAttribute('style', GB.elementsStyle.pageBreak);
        if (hr.nextSibling) {
            this.placeCaretAt(hr.nextSibling, true);
        }
    },

    getPreviousSibling: function (node) {
        while (!node.previousSibling) {
            node = node.parentNode;
            if (!node) {
                return node;
            }
        }
        return node.previousSibling;
    },

    getPreviousLeaf: function (node) {
        let leaf = this.getPreviousSibling(node),
            iterationLimit = 1000;

        if (!leaf) {
            return null;
        }

        while (leaf.lastChild && iterationLimit-- > 0) {
            leaf = leaf.lastChild;
        }

        if (iterationLimit === 0) {
            console.warn('getPreviousElementLeaf: DOM 구조를 더 이상 순환할 수 없습니다.');
            return null;
        }
        return leaf;
    },

    getPreviousElementSibling: function (elem) {
        while (!elem.previousElementSibling) {
            elem = elem.parentElement;
            if (elem === this.body || !elem) {
                return elem;
            }
        }
        return elem.previousElementSibling;
    },

    getPreviousElementLeaf: function (elem) {
        let leaf = this.getPreviousElementSibling(elem),
            iterationLimit = 1000;

        if (!leaf) {
            return null;
        }

        while (leaf.lastElementChild && iterationLimit-- > 0) {
            leaf = leaf.lastElementChild;
        }

        if (iterationLimit === 0) {
            console.warn('getPreviousElementLeaf: DOM 구조를 더 이상 순환할 수 없습니다.');
            return null;
        }

        return leaf;
    },

    getNextSibling: function (node) {
        while (!node.nextSibling) {
            node = node.parentNode;
            if (!node) {
                return node;
            }
        }
        return node.nextSibling;
    },

    getNextLeaf: function (node) {
        let leaf = this.getNextSibling(node),
            iterationLimit = 1000;

        if (!leaf) {
            return null;
        }

        while (leaf.firstChild && iterationLimit-- > 0) {
            leaf = leaf.firstChild;
        }

        if (iterationLimit === 0) {
            console.warn('getNextLeaf: DOM 구조를 더 이상 순환할 수 없습니다.');
            return null;
        }

        return leaf;
    },

    getNextElementSibling: function (elem) {
        while (!elem.nextElementSibling) {
            elem = elem.parentElement;
            if (!elem) {
                return elem;
            }
        }
        return elem.nextElementSibling;
    },

    getNextElementLeaf: function (elem) {
        let leaf = this.getNextElementSibling(elem),
            iterationLimit = 1000;

        if (!leaf) {
            return null;
        }

        while (leaf.firstElementChild && iterationLimit-- > 0) {
            leaf = leaf.firstElementChild;
        }

        if (iterationLimit === 0) {
            console.warn('getNextElementLeaf: DOM 구조를 더 이상 순환할 수 없습니다.');
            return null;
        }

        return leaf;
    },

    isTextVisible: function (text) {
        return (text ?? '').trim().length > 0;
    },

    isBrElement: function (node) {
        return this.isElementNode(node) && node.nodeName === 'BR' ? node : null;
    },

    isEmptyParagraph: function (node) {
        if (!this.isElementNode(node) ||
            node.nodeName !== 'P' && node.nodeName !== 'DIV') {
            return null;
        }
        return this.getTextValue(node).trim() === '' &&
            this.getChildElementsExceptBr(node).length === 0 ? node : null;
/*
        return Array.prototype.filter.call(node.childNodes, child => {
            return this.isElementNode(child) && child.nodeName !== 'BR' ||
                this.isTextNode(child) && this.isTextVisible(child.textContent);
        }).length === 0;
*/
    },

    checkCssValue : function (elem, prop) {
        let css = this.cssProperties(elem), i;
        if (!css) {
            return null;
        }
        for (i = 0; i < css.length; i++) {
            if (css[i].name === prop) {
                return css[i];
            }
        }
        return null;
    },

    cssProperties: function (elem) {
        if (!elem?.style) {
            return null;
        }
        const cssProperties = Object.keys(elem.style)
            .filter(prop => !isNaN(parseInt(prop)) && Object.hasOwn(elem.style, prop))
            .map(prop => this.extractCssProperty(elem.style, prop));
        return cssProperties.length > 0 ? cssProperties : null;
    },

    extractCssProperty: function (style, propIndex) {
        const propertyName = style[propIndex];
        return {
            name: propertyName,
            value: style.getPropertyValue(propertyName)
        };
    },

    makeFontCss : function (command, opt, elem) {
        switch (command) {
            case 'font-size' : elem.style.fontSize = opt + 'px'; break;
            case 'font-family' : elem.style.fontFamily = opt; break;
            case 'color': elem.style.color = opt; break;
            case 'background-color': elem.style.backgroundColor = opt; break;
        }
        return elem;
    },

    insertRangeMarker: function (range, toStart) {
        const boundaryRange = range.cloneRange();
        const rangeMarker = document.createElement('range-marker');
        rangeMarker.create(boundaryRange, toStart);
        return rangeMarker;
    },

    restoreRangeMaker: function (staticRange) {
        const selection = this.getSelection();
        const anchorMarker = staticRange.startContainer;

        if (staticRange.collapsed) {
            const parent = anchorMarker.parentNode;
            selection.setPosition(anchorMarker.nextSibling);
            anchorMarker.className = GB.removeZeroWidthSpaceAttr;
            anchorMarker.className = GB.removeRangeMarkerAttr;
            parent.normalize();
        } else {
            const focusMarker = staticRange.endContainer;
            const range = this.createRange();

            anchorMarker.className = GB.removeZeroWidthSpaceAttr;
            focusMarker.className = GB.removeZeroWidthSpaceAttr;

            range.setStartAfter(anchorMarker);
            range.setEndBefore(focusMarker);

            selection.empty();
            selection.addRange(range);

            anchorMarker.className = GB.removeRangeMarkerAttr;
            focusMarker.className = GB.removeRangeMarkerAttr;
        }

        return selection;
    },

    createStaticRange: function (range = this.getRange()) {
        return new StaticRange({
            startContainer: range.startContainer,
            startOffset: range.startOffset,
            endContainer: range.endContainer,
            endOffset: range.endOffset
        });
    },

    restoreStaticRange: function (selection, staticRange) {
        selection.empty();
        selection.setBaseAndExtent(
            staticRange.startContainer,
            staticRange.startOffset,
            staticRange.endContainer,
            staticRange.endOffset
        );
    },

    saveRangeMarker: function (range) {
        const marker = this.insertRangeMarker(range, true);
        return new StaticRange({
            startContainer: marker,
            startOffset: 0,
            endContainer: range.collapsed ? marker : this.insertRangeMarker(range, false),
            endOffset: 0,
            collapsed: range.collapsed
        });
    },

    restoreAndSaveRangeMarker: function (staticRange, rangeHandler) {
        this.restoreRangeMaker(staticRange);
        const range = this.getRange();
        rangeHandler(range);
        return this.saveRangeMarker(range);
    },

    restoreAndCreateStaticRange: function (selection, staticRange, rangeHandler) {
        this.restoreStaticRange(selection, staticRange);
        const range = this.getRange();
        rangeHandler(range);
        return this.createStaticRange(range);
    },

    isLastElementIndex: function (arr, idx) {
        return idx === arr.length - 1;
    },

    getComputedStyleValue: function (elem, prop) {
        const styleValue = getComputedStyle(elem)?.getPropertyValue(prop);
        return styleValue ? styleValue : null;
    },

    getChildNodeIndex: function (node) {
        const parent = node.parentNode;
        return Array.prototype.findIndex
            .call(parent.childNodes, (child) => child === node);
    },

    isListStartElement: function (elem) {
        if (!this.isElementNode(elem)) {
            return false;
        }
        return GB.listElements.starts.includes(elem.nodeName);
    },

    getListStartElement: function (elem) {
        if (!elem) {
            return null;
        }
        return elem.closest(GB.listElements.starts.toString());
    },

    checkListsElementStructure: function (targetLiElem) {
        const rootNodeOfNestedNode = this.checkListElement(targetLiElem);
        if (!rootNodeOfNestedNode) {
            return null;
        }
        const treeWalker = this.doc.createTreeWalker(
            rootNodeOfNestedNode,
            NodeFilter.SHOW_ELEMENT,
            {
                acceptNode: (node) => {
                    return GB.listElements.entire.includes(node.nodeName)
                        ? NodeFilter.FILTER_ACCEPT
                        : NodeFilter.FILTER_REJECT;
                }
            }
        );

        let nextNode = treeWalker.nextNode();

        while (nextNode) {
            const currNode = treeWalker.currentNode;
            if (this.isListStartElement(currNode) &&
                GB.listElements.starts.includes(currNode.parentElement.nodeName)) {
                let prevElem = currNode.previousElementSibling;

                if (prevElem && GB.listElements.items.includes(prevElem.nodeName)) {
                    prevElem.append(currNode);
                    treeWalker.currentNode = prevElem;
                } else {
                    prevElem = this.doc.createElement('LI');
                    currNode.before(prevElem);
                    prevElem.append(currNode);
                    treeWalker.currentNode = prevElem;
                }
            } else {
                nextNode = treeWalker.nextNode();
            }
        }

        return rootNodeOfNestedNode;
    },

    isSameElementName: function (elem, otherName) {
        if (!otherName || !this.isElementNode(elem)) {
            return false;
        }
        return elem.nodeName === otherName.toUpperCase();
    },

    findClosestBlockElement: function (node) {
        let currentNode = node;

        while (!this.isBody(currentNode)) {
            if (this.isBlockElement(currentNode)) {
                return currentNode;
            }
            currentNode = currentNode.parentNode;
        }

        return null;
    },

    getRootNode: function (node) {
        let currNode = node;
        const isNotRootNode = (elem) => elem?.parentNode && !elem.parentNode.isSameNode(this.body);

        while (isNotRootNode(currNode)) {
            currNode = currNode.parentNode;
        }

        return currNode;
    },

    getNextElement: function (elem, otherName = null) {
        const findNextSiblingAncestor = (currElem, otherName) => {
            while (!currElem.nextElementSibling) {
                currElem = currElem.parentElement;
                if (!currElem || this.isSameElementName(currElem, otherName)) {
                    return currElem;
                }
            }
            return currElem.nextElementSibling;
        };
        const findFirstChildMatch = (currElem, otherName) => {
            while (currElem.firstElementChild) {
                currElem = currElem.firstElementChild;
                if (this.isSameElementName(currElem, otherName)) {
                    break;
                }
            }
            return currElem;
        };

        let currElem = findNextSiblingAncestor(elem, otherName);
        if (!currElem || this.isSameElementName(currElem, otherName)) {
            return currElem;
        }

        return findFirstChildMatch(currElem, otherName);
    },

    checkListElement: function (node) {
        const NESTED_LIST_SELECTOR = 'ol > ol, ol > ul, ol > dl, ul > ul, ul > ol, ul > dl, dl > dl, dl > ol, dl > ul';
        const shouldTraverseToParent = (elem) => {
            return elem.parentElement &&
                !this.isBody(elem.parentElement) &&
                GB.listElements.entire.includes(elem.parentElement.nodeName);
        };
        const getParentListElement = (currNode) => {
            while (shouldTraverseToParent(currNode)) {
                currNode = currNode.parentElement;
            }
            return currNode;
        };

        const liElem = getParentListElement(node);
        if (liElem.querySelector(NESTED_LIST_SELECTOR)) {
            return liElem;
        }

        return null;
    },

    copyAttributes: function (sourceElem, targetElem) {
        const shouldSkipAttribute = (attributeName) => attributeName === 'id';
        Array.from(sourceElem.attributes).forEach(attribute => {
            if (!shouldSkipAttribute(attribute.nodeName)) {
                targetElem.setAttributeNode(attribute.cloneNode(true));
            }
        });
    },

    removeAttributes: function (elem) {
        if (elem?.hasAttributes()) {
            elem.getAttributeNames().forEach(attr => elem.removeAttribute(attr));
        }
    },

/*
    setUndo: function (id) {
        this.undoStack.push({id, data: this.body.cloneNode(true)});
    },
*/

    doCmdPopup: function (aCommand, aValue = null, elem = null) {
        let range, ancestor, styleAttributes, button;
        this.closeDropdownMenu();
        this.restoreRange();
/*
        const groupId = this.makeRandomString();
        this.undoManager.add({
            groupId: groupId,
            undo: () => { this.setUndo(groupId); },
            redo: () => { console.log('redo'); },
        });
*/

        try {
            switch (aCommand) {
                case 'LineHeight':
                    this.applyLineHeight(aValue);
                    break;
                case 'QuoteBlock':
                    this.blockquoteCommand(aValue);
                    break;
                case 'OrderedList': case 'UnOrderedList':
                    button = this.toolbar[aCommand];
                    range = this.getRange();

                    if (!button.checked) {
                        this.queryCommandWithRange(button.argv, null, range);
                        range = this.getRange();
                    }

                    ancestor = range.commonAncestorContainer;
                    ancestor.normalize();
                    ancestor = this.getParentIfTextNode(ancestor);

                    while (ancestor) {
                        if (ancestor.nodeName === 'BODY') {
                            break;
                        }
                        if (ancestor.nodeName === 'OL' || ancestor.nodeName === 'UL') {
                            if (aValue === 'desc' || aValue === 'decimal') {
                                aValue = '';
                            }
                            ancestor.style.listStyleType = aValue;
                            break;
                        }
                        ancestor = ancestor.parentNode;
                    }
                    break;
                case 'FontSize': case 'ForeColor': case 'BackColor': case 'FontName':
                    styleAttributes = {
                        FontSize: {
                            name: "fontSize",
                            attributeNewValue: `${aValue}px`
                        },
                        FontName: {
                            name: "fontFamily",
                            attributeNewValue: "face"
                        },
                        ForeColor: {
                            name: "color",
                            attributeNewValue: "color"
                        },
                        BackColor: {
                            name: "backgroundColor",
                            attributeNewValue: aValue
                        }
                    };

                    if (elem) {
                        ancestor = elem;
                    } else {
                        range = this.getRange();
                        ancestor = this.findTextFormatAncestor(range.commonAncestorContainer);
                    }

                    this.observer.modify = {
                        rules: styleAttributes[aCommand],
                        type: "fontStyle",
                        newName: "SPAN",
                        oldName: "FONT",
                        isSameStyle: node => {
                            if (!node.hasAttribute('style')) {
                                return false;
                            }
                            const value = node.style[styleAttributes[aCommand].name];
                            return !!(value && value === styleAttributes[aCommand].value);
                        },
                    };
                    this.observer.observe(ancestor, {
                        childList: true, subtree: true, attributeOldValue: true, attributes: true
                    });

                    if (aCommand === 'FontSize') {
                        aValue = '2';
                    }

                    this.queryCommand(aCommand, aValue);
                    break;
                default:
                    this.queryCommand(aCommand, aValue);
                    this.updateToolbar();
            }
        } catch (error) {
            alert(error.toString());
        }

        if (this.config.showTagSelector) {
            this.outputTagSelector();
        }
    },

    isTextFormatElement: function (node) {
        return this.isElementNode(node) ? GB.textFormatElements.includes(node.nodeName) : false;
    },
    isForceRemoveElement: function (node) {
        return this.isElementNode(node) ? GB.forceRemoveElements.includes(node.nodeName) : false;
    },
    isBlockElement: function (node) {
        const REGEX = /(table(.*?)|block)/;
        return this.isElementNode(node)
            ? this.getComputedStyleValue(node, 'display').match(REGEX)
            : false;
    },
    isInlineElement: function (node) {
        return !this.isBlockElement(node);
    },
    isTextIndentElement: function (node) {
        return this.isElementNode(node) ? GB.textIndentElements.includes(node.nodeName) : false;
    },

    calcIndentOffset: function (elem, direction) {
        const currentOffset = Number.parseInt(elem.style.getPropertyValue('margin-left'), 10);

        if (isNaN(currentOffset)) {
            return direction === 'decrease' ? 0 : GB.defaultOffsetIncrement;
        }

        const newOffset = direction === 'decrease' ? -GB.defaultOffsetIncrement
            : GB.defaultOffsetIncrement;

        return Math.max(currentOffset + newOffset, 0);
    },

    setIndentOffset: function (elem, command) {
        const offset = this.calcIndentOffset(elem, command === 'Indent' ? 'increase' : 'decrease');

        if (offset < 1) {
            elem.style.removeProperty('margin-left');
            if (elem.style.length === 0) {
                elem.removeAttribute('style');
            }
        } else {
            elem.style.marginLeft = `${offset}px`;
        }
    },

    extendSelection: function (selection, direction, granularity) {
        selection.modify('extend', direction, granularity);
    },

    setSelectionLine: function (useBoundary = true) {
        const selection = this.getSelection();
        const granularity = useBoundary ? "lineBoundary" : "line";

        this.extendSelection(selection, "backward", granularity);
        selection.collapseToStart();
        this.extendSelection(selection, "forward", granularity);

        return selection;
    },

    isAfterNode: function (container, offset, node) {
        let compare = node, i = offset;

        while (compare.parentNode !== container) {
            compare = compare.parentNode;
        }

        while (compare && i > 0) {
            compare = compare.previousSibling;
            i -= 1;
        }

        return i > 0;
    },

    compareCaretPositions: function (firstNode, firstOffset, secondNode, secondOffset) {
        if (firstNode === secondNode) {
            return firstOffset - secondOffset;
        }

        const compare = firstNode.compareDocumentPosition(secondNode);

        if ((compare & Node.DOCUMENT_POSITION_CONTAINED_BY) !== 0) {
            return this.isAfterNode(firstNode, firstOffset, secondNode) ? 1 : -1;
        }
        if ((compare & Node.DOCUMENT_POSITION_CONTAINS) !== 0) {
            return this.isAfterNode(secondNode, secondOffset, firstNode) ? -1 : 1;
        }
        if ((compare & Node.DOCUMENT_POSITION_FOLLOWING) !== 0) {
            return -1;
        }
        if ((compare & Node.DOCUMENT_POSITION_PRECEDING) !== 0) {
            return 1;
        }
    },

    stringifyElementStart: function (node, isNewline) {
        if (this.isBrElement(node)) {
            return '\n';
        }
        if (node.nodeName === 'P' || node.nodeName === 'DIV') {
            if (!isNewline) {
                return '\n';
            }
        }
        return '';
    },

    positions: function* (node, isNewline = true) {
        let child = node.firstChild, offset = 0;
        yield {
            node: node,
            offset: offset,
            text: this.stringifyElementStart(node, isNewline)
        };

        while (child) {
            if (this.isTextNode(child)) {
                yield {
                    node: child,
                    offset: 0/0,
                    text: child.data
                };
                isNewline = false;
            } else {
                isNewline = yield* this.positions(child, isNewline);
            }
            child = child.nextSibling;
            offset += 1;
            yield {
                node: node,
                offset: offset,
                text: ''
            };
        }

        return isNewline;
    },

    getTextOffset: function (ctxNode, selectionNode, selectionOffset) {
        let offset = 0;

        for (let pos of this.positions(ctxNode)) {
            if (!this.isTextNode(selectionNode) && selectionNode === pos.node &&
                selectionOffset === pos.offset) {
                return offset;
            }

            if (this.isTextNode(selectionNode) && selectionNode === pos.node) {
                return offset + selectionOffset;
            }

            offset += pos.text.length;
        }

        return this.compareCaretPositions(selectionNode, selectionOffset, ctxNode, 0) < 0
            ? 0
            : offset;
    },

    getCaretPosition: function (ctxNode, textPosition) {
        let textOffset = 0, lastNode = null, lastOffset = 0;

        for (let pos of this.positions(ctxNode)) {
            if (pos.text.length > textPosition - textOffset) {
                return {
                    node: pos.node,
                    offset: this.isTextNode(pos.node) ? textPosition - textOffset : pos.offset
                };
            }

            textOffset += pos.text.length;
            lastNode = pos.node;
            lastOffset = this.isTextNode(pos.node) ? pos.text.length : pos.offset;
        }

        return { node: lastNode, offset: lastOffset };
    },

    getTextValue: function (node) {
        let value = '';
        for (const pos of this.positions(node)) {
            value += pos.text;
        }
        return value;
    },

    getChildElementsExceptBr: function (node) {
        return Array.prototype.filter.call(node.children, child => {
            return child.nodeName !== 'BR';
        });
    },

    setSelectionRange: function (node, start, end) {
        const selection = this.getSelection();
        const sPos = this.getCaretPosition(node, start);
        const ePos = this.getCaretPosition(node, end);
        selection.setBaseAndExtent(sPos.node, sPos.offset, ePos.node, ePos.offset);
    },

    getSelectionStart: function (ctxNode) {
        const selection = this.getSelection();
        const compare = this.compareCaretPositions(selection.anchorNode, selection.anchorOffset, selection.focusNode, selection.focusOffset);
        return compare < 0
            ? this.getTextOffset(ctxNode, selection.anchorNode, selection.anchorOffset)
            : this.getTextOffset(ctxNode, selection.focusNode, selection.focusOffset);
    },

    getSelectionEnd: function (ctxNode) {
        const selection = this.getSelection();
        const compare = this.compareCaretPositions(selection.anchorNode, selection.anchorOffset, selection.focusNode, selection.focusOffset);
        return compare < 0
            ? this.getTextOffset(ctxNode, selection.focusNode, selection.focusOffset)
            : this.getTextOffset(ctxNode, selection.anchorNode, selection.anchorOffset);
    },

    rangeCompareNode: function (range, node) {
        const nodeRange = this.createRange();

        try {
            nodeRange.selectNode(node);
        } catch (e) {
            nodeRange.selectNodeContents(node);
        }

        const nodeIsBefore = range.compareBoundaryPoints(Range.START_TO_START, nodeRange) === 1;
        const nodeIsAfter = range.compareBoundaryPoints(Range.END_TO_END, nodeRange) === -1;

        // NODE_BEFORE (0)
        if (nodeIsBefore && !nodeIsAfter) {
            return 0;
        }
        // NODE_AFTER (1)
        if (!nodeIsBefore && nodeIsAfter) {
            return 1;
        }
        // NODE_BEFORE && NODE_AFTER (2)
        if (nodeIsBefore && nodeIsAfter) {
            return 2;
        }
        // NODE_INSIDE (3)
        return 3;
    },

    findLeftBlockElement: function (node) {
        let leaf;

        while (!node.previousElementSibling) {
            node = node.parentElement;
            if (!node || this.isBlockElement(node)) {
                return node;
            }
        }

        leaf = node.previousElementSibling;

        while (leaf && !this.isBlockElement(leaf)) {
            leaf = leaf.previousElementSibling;
        }

        return leaf;
    },

    findTextFormatAncestor: function (node) {
        node = this.getParentIfTextNode(node);
        while (this.isTextFormatElement(node)) {
            node = node.parentNode;
        }
        return node;
    },

    replaceTextFormatElement: function (rootElem) {
        const OLD_ELEMENTS = ['STRIKE', 'B', 'I'];
        const NEW_ELEMENTS = ['S', 'STRONG', 'EM'];

        const walker = this.doc.createTreeWalker(
            rootElem,
            NodeFilter.SHOW_ELEMENT,
            {
                acceptNode: node => OLD_ELEMENTS.includes(node.nodeName)
                    ? NodeFilter.FILTER_ACCEPT
                    : NodeFilter.FILTER_REJECT
            }
        );

        let currNode = walker.nextNode();
        while (currNode) {
            const idx = OLD_ELEMENTS.indexOf(currNode.nodeName);
            if (idx > -1) {
                const newElem = this.createReplacementElement(currNode, NEW_ELEMENTS[idx]);
                currNode.replaceWith(newElem);
                walker.currentNode = newElem;
            }
            currNode = walker.nextNode();
        }
    },

    createReplacementElement: function (oldNode, newElementName) {
        const newElem = this.doc.createElement(newElementName);
        this.nodeReplaceWith(oldNode, newElem);
        return newElem;
    },

    applyTextFormat: function (command, args = null) {
        const styleAttr = {
            Strikethrough: {
                name: 'textDecorationLine',
                type: 'replace',
                newName: 'S',
                tagName: 'STRIKE',
                attributeNewValue: 'lineThrough'
            },
            Bold: {
                name: 'bold',
                type: 'replace',
                newName: 'STRONG',
                tagName: 'B',
                attributeNewValue: 'fontWeight'
            },
            Underline: {
                name: 'underline',
                type: 'replace',
                newName: 'U',
                tagName: 'U',
                attributeNewValue: 'underline'
            },
            Italic: {
                name: 'italic',
                type: 'replace',
                newName: 'EM',
                tagName: 'I',
                attributeNewValue: 'italic'
            },
        };

        this.editareaFocus();

        if (!Object.hasOwn(styleAttr, command)) {
            this.queryCommand(command, args);
            this.body.normalize();
            return;
        }

        const range = this.getRange();
        let ancestor = this.findTextFormatAncestor(range.commonAncestorContainer);

        this.observer.modify = {
            rules: styleAttr[command],
            type: styleAttr[command].type,
            newName: styleAttr[command].newName,
            oldName: styleAttr[command].tagName
        };

        this.observer.observe(ancestor, {
            childList: true,
            subtree: true,
            attributeOldValue: true
        });

        const selection = this.getSelection();
        selection.empty();
        selection.addRange(range);
        this.queryCommand(command, args);
    },

    getInnerHTML: function (elem) {
        if (!this.isElementNode(elem)) {
            return null;
        }

        if (typeof elem.getHTML === 'function') {
            return elem.getHTML();
        }

        const outerHTML = elem.outerHTML;
        const tagName = elem.tagName;
        const regex = new RegExp(String.raw`<${tagName}[^>]*?>([\s\S]*?)<\/${tagName}>`, 'gmi');

        return outerHTML.replace(regex, '$1');
    },

    setDefaultParagraphSeparator: function () {
        this.execCommand('defaultParagraphSeparator', 'p');
    },

    queryCommand: function (command, opt = null) {
        if (command === 'insertParagraph') {
            this.setDefaultParagraphSeparator();
        }
        return this.execCommand(command, opt);
    },

    execCommand: function (command, opt = null) {
        return this.doc.execCommand(command, false, opt);
    },

    queryCommandWithRange: function (command, opt, range) {
        const staticRange = this.saveRangeMarker(range);
        this.queryCommand(command, opt);
        this.restoreRangeMaker(staticRange);
    },

    emptyChildren: function (node = null) {
        if (!node) {
            this.body.replaceChildren();
            return;
        }
        node.replaceChildren();
    },

    removeBrElement: function (node) {
        if (this.isElementNode(node) && node.nodeName === 'BR') {
            node.remove();
            return true;
        }
        return false;
    },

    createEmptyParagraph: function () {
        const para = this.doc.createElement('p');
        para.appendChild(this.doc.createElement('br'));
        return para;
    },

    nodeReplaceWith: function (oldNode, newNode) {
        const range = this.createRange();
        range.selectNodeContents(oldNode);
        newNode.appendChild(range.extractContents());
        this.copyAttributes(oldNode, newNode);
        oldNode.replaceWith(newNode);
    },

    setupDialog: function (type, titleText, message) {
        const dialog = this.cheditor.dialog;
        const title = dialog.querySelector('.cheditor-dialog-title');
        const content = dialog.querySelector('.cheditor-dialog-body');
        const offsetTop = this.findElementPosition(this.cheditor.editWrapper).top + 50;
        const windowScrollY = this.getWindowSize().scrollY;

        dialog.setAttribute('type', type);
        dialog.style.setProperty('top', `${offsetTop - windowScrollY}px`, 'important');
        title.replaceChildren(this.doc.createTextNode(titleText));
        content.replaceChildren(this.doc.createTextNode(message));

        return dialog;
    },

    dialogAddEventHandlers: function (dialog, confirmHandler, cancelHandler, closeHandler) {
        const confirmButton = dialog.querySelector('button[name="submit"]');
        const cancelButton = dialog.querySelector('button[name="cancel"]');

        this.addEvent(confirmButton, 'click', confirmHandler);
        if (cancelHandler) this.addEvent(cancelButton, 'click', cancelHandler);
        this.addEvent(dialog, 'close', closeHandler);
    },

    dialogRemoveEventHandlers: function (dialog, confirmHandler, cancelHandler, closeHandler) {
        const confirmButton = dialog.querySelector('button[name="submit"]');
        const cancelButton = dialog.querySelector('button[name="cancel"]');

        this.removeEvent(confirmButton, 'click', confirmHandler);
        if (cancelHandler) this.removeEvent(cancelButton, 'click', cancelHandler);
        this.removeEvent(dialog, 'close', closeHandler);
    },

    alert: function (message) {
        const dialog = this.setupDialog('alert', '알림', message);
        const cancelButton = dialog.querySelector('button[name="cancel"]');
        cancelButton.classList.add('d-none');

        const confirmHandler = (evt) => {
            this.stopEvent(evt);
            dialog.close();
        };
        const closeHandler = () => {
            this.dialogRemoveEventHandlers(dialog, confirmHandler, null, closeHandler);
            cancelButton.classList.remove('d-none');
            this.editareaFocus();
        };

        this.dialogAddEventHandlers(dialog, confirmHandler, null, closeHandler);
        dialog.showModal();
    },

    confirm: function (message, callMe) {
        const dialog = this.setupDialog('confirm', '확인', message);

        const cancelHandler = (evt) => {
            this.stopEvent(evt);
            dialog.close('no');
        };

        const confirmHandler = (evt) => {
            this.stopEvent(evt);
            dialog.close('yes');
        };

        const closeHandler = () => {
            this.dialogRemoveEventHandlers(dialog, confirmHandler, cancelHandler, closeHandler);
            this.editareaFocus();
            callMe(dialog.returnValue === 'yes');
        };

        this.dialogAddEventHandlers(dialog, confirmHandler, cancelHandler, closeHandler);
        dialog.showModal();
    },

    handleNewDocument: function () {
        this.toolbarButtonChecked(this.toolbar['NewDocument'], true);
        this.confirm('글 내용이 모두 사라집니다. 계속하시겠습니까?', (yes) => {
            if (yes) {
                this.emptyChildren();
                this.initDefaultParagraphSeparator();
            }
            this.toolbarButtonChecked(this.toolbar['NewDocument'], false);
        });
    },

    handleClearTag: function () {
        this.toolbarButtonChecked(this.toolbar['ClearTag'], true);
        this.confirm('P, BR 태그를 제외한 모든 HTML 태그를 제거합니다. 계속하시겠습니까?', (yes) => {
            if (yes) {
                const contents = this.getBodyHTML();
                this.emptyChildren();
                this.body.insertAdjacentHTML('afterbegin', this.clearNonEssentialHtmlTags(contents));
            }
            this.toolbarButtonChecked(this.toolbar['ClearTag'], false);
        });
    },

    clearNonEssentialHtmlTags: function(contents) {
        return contents.replace(/<(\/?)([^>]*)>/gi, (match, slash, tagContent) => {
            const tagName = tagContent.split(/ /)[0].toLowerCase();
            return (tagName !== 'p' && tagName !== 'br') ? '' : `<${slash}${tagName}>`;
        }).replace(/><br>/gi, '');
    },

    handleJustifyCommand: function(command) {
        const range = this.getRange();
        const currElem = this.findClosestBlockElement(range.commonAncestorContainer);

        if (currElem && currElem.nodeName === 'FIGCAPTION') {
            currElem.style.textAlign = GB.textAlign[command];
            return;
        }
        if (GB.browser.firefox) {
            this.firefoxJustifyCorrectionObserver(command, range);
        }

        this.queryCommand(command);
    },

    firefoxJustifyCorrectionObserver: function(command, range) {
        const rootNode = this.getJustifyRootNode(range);
        const applyJustifyCorrection = (node) => {
            node.style.textAlign = GB.textAlign[command];
            node.removeAttribute('align');
            if (node.nodeName === 'DIV') {
                this.nodeReplaceWith(node, this.doc.createElement('p'));
            }
        };

        if (rootNode) {
            const observer = new MutationObserver((mutations) => {
                observer.disconnect();
                const staticRange = this.createStaticRange();
                const selection = this.getSelection();

                mutations.forEach(mutation => {
                    if (mutation.type === 'attributes' && mutation.attributeName === 'align') {
                        applyJustifyCorrection(mutation.target);
                    }
                    mutation.addedNodes.forEach(node => {
                        if (this.isElementNode(node) && node.nodeName === 'DIV') {
                            applyJustifyCorrection(node);
                        }
                    });
                });
                this.restoreStaticRange(selection, staticRange);
            });

            let targetNode = rootNode;

            if (targetNode.nodeName === 'P') {
                targetNode.style.textAlign = GB.textAlign[command];
            } else if (['THEAD', 'TBODY', 'TR', 'TFOOT'].includes(rootNode.nodeName)) {
                const tbl = targetNode.closest('TABLE');
                if (tbl) {
                    targetNode = tbl;
                }
            }

            observer.observe(targetNode, {
                childList: true, subtree: true, attributeOldValue: true, attributes: true
            });
        }
    },

    getJustifyRootNode: function(range) {
        let ancestor = this.getParentIfTextNode(range.commonAncestorContainer);
        const isNotRootNode = (elem) => !this.isBlockElement(ancestor) && !this.isBody(elem);
        while (isNotRootNode(ancestor)) {
            ancestor = ancestor.parentNode;
        }
        return ancestor;
    },

    execEditorCommand: function (command) {
        this.closeDropdownMenu();
        this.editareaFocus();

        switch (command) {
            case 'NewDocument':
                this.handleNewDocument();
                break;
            case 'Print':
                this.editarea.print();
                break;
            case 'PageBreak':
                this.pageBreak();
                break;
            case 'ClearTag':
                this.handleClearTag();
                break;
            case 'InsertHorizontalRule':
                this.insertHorizontalRule();
                break;
            case 'JustifyLeft': case 'JustifyCenter': case 'JustifyRight': case 'JustifyFull':
                this.backupRange();
                this.handleJustifyCommand(command);
                this.restoreRange();
                break;
            case 'Indent': case 'Outdent':
                this.indentListCommand(command);
                break;
            case 'InsertOrderedList': case 'InsertUnOrderedList':
                this.insertListCommand(command);
                break;
            case 'UnLink':
                this.removeHyperLink();
                break;
            case 'SelectAll':
                this.selectAll();
                break;
            case 'RemoveFormat': case 'Copy':
                this.queryCommand(command);
                break;
            case 'Cut':
                this.cutSelectedNode();
                break;
/*
            case 'Undo':
                this.undoManager.undo();
                break;
            case 'Redo':
                this.undoManager.redo();
                break;
*/
            default:
                break;
        }

        this.updateToolbar();
    },

    selectAll: function () {
        const selection = this.getSelection();
        selection.selectAllChildren(this.body);
    },

    canIndentOutdent: function (node) {
        return node
            && GB.listElements.items.includes(node.nodeName)
            && node.previousElementSibling;
    },

    indentListElement: function (node) {
        if (!this.canIndentOutdent(node)) {
            return;
        }

        const prevSibling = node.previousElementSibling;

        if (this.isListStartElement(prevSibling)) {
            prevSibling.append(node);
        } else if (prevSibling.nodeName === 'LI') {
            const lastChild = prevSibling.lastElementChild;

            if (lastChild && this.isListStartElement(lastChild)) {
                lastChild.append(node);
            } else {
                const startElem = this.doc.createElement(node.parentNode.nodeName || 'UL');
                startElem.append(node);
                prevSibling.append(startElem);
            }
        }
    },

    indentListCommand: function (command) {
        const range = this.getRange();
        const ancestor = this.getParentIfTextNode(range.commonAncestorContainer);
        const liStart = this.getListStartElement(ancestor);

        if (liStart) {
            if (command === 'Outdent') {
                this.outdentListElement(range);
                return;
            }

            const staticRange = this.saveRangeMarker(range);
            const tagsName = GB.listElements.entire.toString();
            let [sc, ec] = [range.startContainer, range.endContainer];
            let prevNode = null;

            sc = this.getParentIfTextNode(sc).closest(tagsName);
            ec = this.getParentIfTextNode(ec).closest(tagsName);

            if (!sc || !ec) {
                return;
            }

            const nodes = sc.contains(ec)
                ? [sc]
                : [...ancestor.children].filter((el) => {
                    return GB.listElements.items.includes(el.tagName);
                });

            for (let node of nodes) {
                if (node.compareDocumentPosition(ec) & Node.DOCUMENT_POSITION_PRECEDING) {
                    break;
                }
                if (node.compareDocumentPosition(sc) & Node.DOCUMENT_POSITION_FOLLOWING) {
                    continue;
                }
                if (prevNode &&
                    node.compareDocumentPosition(prevNode) & Node.DOCUMENT_POSITION_CONTAINS) {
                    continue;
                }

                this.indentListElement(node);
                prevNode = node;
            }

            this.restoreRangeMaker(staticRange);
            return;
        }

        const staticRange = this.saveRangeMarker(range);
        let prevNode = null;
        const self = this;
        const walker = this.doc.createTreeWalker(
            ancestor,
            NodeFilter.SHOW_ELEMENT | NodeFilter.SHOW_TEXT,
            {
                acceptNode(node) {
                    if (range.intersectsNode(node)) {
                        return (node.nodeType === Node.TEXT_NODE &&
                            !self.isTextVisible(node.nodeValue) ||
                            node.nodeName === 'RANGE-MARKER') ?
                            NodeFilter.FILTER_SKIP : NodeFilter.FILTER_ACCEPT;
                    }
                    return NodeFilter.FILTER_REJECT;
                }
            });
        const applyIndentOffset = ((elem, isRoot) => {
            const isTextIndented = this.isTextIndentElement(elem);
            if (isTextIndented) {
                this.setIndentOffset(elem, command);
            }
            if (isTextIndented || this.isListStartElement(elem)) {
                return isRoot ? null : true;
            }
            return isRoot ? walker.firstChild() : false;
        });
        const handleElementNode = (currNode) => {
            if (applyIndentOffset(currNode, false)) {
                prevNode = currNode;
                if (!currNode.nextSibling) {
                    while (!currNode.nextSibling) {
                        currNode = currNode.parentNode;
                        if (currNode === walker.root) {
                            break;
                        }
                    }
                    walker.currentNode = currNode;
                }
                return walker.nextSibling();
            } else {
                return walker.nextNode();
            }
        };
        const handleTextNodeFormatting = (currNode) => {
            if (prevNode && prevNode.contains(currNode)) {
                walker.currentNode = prevNode;
                return walker.nextSibling();
            } else {
                const selection = this.getSelection();
                const nodeRange = this.createRange();
                nodeRange.selectNode(currNode);
                selection.empty();
                selection.addRange(nodeRange);

                this.queryCommand('formatBlock', 'p');

                currNode = this.getParentIfTextNode(selection.focusNode).closest('p');
                selection.selectAllChildren(currNode);

                this.setIndentOffset(currNode, command);

                walker.currentNode = currNode;
                prevNode = null;
                return walker.nextSibling();
            }
        };

        let node = applyIndentOffset(walker.root, true);

        while (node) {
            node = this.isElementNode(walker.currentNode)
                ? handleElementNode(node)
                : handleTextNodeFormatting(node);
        }

        this.restoreRangeMaker(staticRange);
    },

    outdentListElement: function (range) {
        const currElem = this.getRangeElement(range);
        const liStart = this.getListStartElement(currElem);

        if (!liStart) {
            return;
        }

        let staticRange = this.saveRangeMarker(range);
        if (this.checkListsElementStructure(liStart)) {
            staticRange = this.restoreAndSaveRangeMarker(staticRange, (newRange) => range = newRange);
        }

        const liEntireTag = GB.listElements.entire.toString();
        const ancestor = this.getParentIfTextNode(range.commonAncestorContainer).closest(liEntireTag);

        if (!ancestor) {
            return;
        }

        const liItemsTag = GB.listElements.items.toString();
        const sc = this.getParentIfTextNode(range.startContainer).closest(liItemsTag);
        const ec = this.getParentIfTextNode(range.endContainer).closest(liItemsTag);
        const walker = this.doc.createTreeWalker(
            ancestor,
            NodeFilter.SHOW_ELEMENT,
            {
                acceptNode(node) {
                    return GB.listElements.entire.includes(node.nodeName)
                        ? NodeFilter.FILTER_ACCEPT : NodeFilter.FILTER_REJECT;
                }
            }
        );

        const targetNodes = [];
        const RELATIVE_POSITION_BEFORE = 0;
        const RELATIVE_POSITION_AFTER = 1;
        const RELATIVE_POSITION_WITHIN = 2;

        const getNodeRelativePosition = (targetNode) => {
            if (sc.compareDocumentPosition(targetNode) & Node.DOCUMENT_POSITION_PRECEDING) {
                return RELATIVE_POSITION_BEFORE;
            }
            if (ec.compareDocumentPosition(targetNode) & Node.DOCUMENT_POSITION_FOLLOWING) {
                return RELATIVE_POSITION_AFTER;
            }
            return RELATIVE_POSITION_WITHIN;
        };
        const getValidNextNodes = (startNode) => {
            const nodes = [];
            let node = startNode.nextElementSibling;

            while (node) {
                if (GB.listElements.items.includes(node.nodeName)) {
                    nodes.push(node);
                }
                node = node.nextElementSibling;
            }
            return nodes;
        };
        const getNodeInformation = (node) => {
            const parentNode = node.parentNode;
            const liItem = parentNode.closest(liItemsTag);
            let refNode = null;

            if (liItem) {
                refNode = liItem.nextElementSibling || liItem;
            }
            return {
                liItem: node,
                liStart: parentNode,
                firstChild: parentNode.firstElementChild === node,
                lastChild: parentNode.lastElementChild === node,
                refNode: refNode
            };
        };

        let currNode = walker.root;
        const getNextValidNode = (treeWalker, curNode) => {
            let nextNode = treeWalker.nextSibling();
            if (!nextNode) {
                nextNode = treeWalker.nextNode();
                while (nextNode && curNode.contains(nextNode)) {
                    nextNode = treeWalker.nextNode();
                }
            }
            return nextNode;
        };

        while (currNode) {
            const position = getNodeRelativePosition(currNode);
            if (position === RELATIVE_POSITION_AFTER) {
                break;
            }
            if (position === RELATIVE_POSITION_WITHIN) {
                const nodeInfo = getNodeInformation(currNode);
                targetNodes.push(nodeInfo);

                if (currNode === walker.root) {
                    break;
                }

                currNode = getNextValidNode(walker, currNode);
            } else {
                currNode = walker.nextNode();
            }
        }

        let isFirstChildDetected = false;
        const createNewListElement = (liStart, lastChild) => {
            return lastChild && this.isListStartElement(lastChild)
                ? lastChild
                : this.doc.createElement(liStart ? liStart.nodeName : 'OL');
        };
        const handleFirstChild = (isFirstChildDetected, firstChild) => {
            if (firstChild) {
                return true;
            }
            return isFirstChildDetected;
        };
        const handleLastNode = (nodes, idx, lastChild, currElem) => {
            if (!lastChild && nodes.length === idx + 1) {
                const liStart = this.getListStartElement(currElem);
                const newLiStart = createNewListElement(liStart, currElem.lastChild);
                const validNextNodes = getValidNextNodes(currElem);
                if (validNextNodes.length) {
                    newLiStart.append(...validNextNodes);
                }
                currElem.append(newLiStart);
            }
        };
        const RANGE_ACTIONS = {
            createWrappedParagraph: (range, itemNode) => {
                const elem = this.doc.createElement('p');
                range.selectNode(itemNode);
                range.surroundContents(elem);
                return elem;
            },
            insertParagraphAfterNode: (paragraph, refNode, altNode) => {
                if (altNode) {
                    altNode.after(paragraph);
                } else {
                    refNode.after(paragraph);
                }
            },
            moveItemContentsToParagraph: (range, liItemNode, parentNode, insertAfterNode) => {
                const paragraph = RANGE_ACTIONS.createWrappedParagraph(range, liItemNode);
                RANGE_ACTIONS.insertParagraphAfterNode(paragraph, parentNode, insertAfterNode);

                range.selectNodeContents(liItemNode);
                paragraph.appendChild(range.extractContents());
                liItemNode.remove();
                this.removeEmptyNode(parentNode);

                return paragraph;
            }
        };
        const insertNodeAtRefNode = (refNode, currItem, currStart, insertAfterNode) => {
            if (refNode.contains(currStart)) {
                if (!insertAfterNode) {
                    insertAfterNode = refNode;
                }
                insertAfterNode.after(currItem);
                return currItem;
            }
            refNode.before(currItem);
            return insertAfterNode;
        };

        let insertAfterNode = null, insertAfterRefNode = null;
        const processInsertPosition = (refNode, currItem, currStart, range) => {
            if (refNode) {
                insertAfterRefNode = insertNodeAtRefNode(refNode, currItem, currStart, insertAfterRefNode);
                this.removeEmptyNode(currStart);
            } else {
                const paragraph = RANGE_ACTIONS.moveItemContentsToParagraph(range, currItem, currStart, insertAfterNode);
                const liStart = paragraph.querySelector(GB.listElements.starts.toString());

                if (liStart) {
                    paragraph.after(liStart);
                    insertAfterNode = liStart;
                } else {
                    insertAfterNode = paragraph;
                }
            }
        };

        targetNodes.forEach(({liItem, liStart, firstChild, lastChild, refNode}, idx) => {
            isFirstChildDetected = handleFirstChild(isFirstChildDetected, firstChild);
            handleLastNode(targetNodes, idx, lastChild, liItem);
            processInsertPosition(refNode, liItem, liStart, this.createRange());
        });

        this.restoreRangeMaker(staticRange);
    },

    insertListCommand: function (command) {
        const button = this.toolbar[command.substring(6)];
        const range = this.getRange();

        if (button.checked) {
            this.outdentListElement(range);
            return;
        }

        const currNode = this.getRangeElement(range);
        let selection = this.getSelection(),
            staticRange = this.saveRangeMarker(range),
            ec = this.getParentIfTextNode(range.endContainer),
            liStart = this.getListStartElement(currNode);
        const replaceWithNewListElement = (tagName) => {
            const elem = this.doc.createElement(tagName);
            elem.replaceChildren(...liStart.childNodes);
            liStart.before(elem);
            liStart.remove();
            command = null;
        };
        const handleListElementToggle = (command, target) => {
            const liStartTag = command === 'InsertUnOrderedList' ? 'UL' : 'OL';
            if (target.nodeName !== liStartTag) {
                replaceWithNewListElement(liStartTag);
            }
        };
        const shouldReplaceParentParagraph = (node) => {
            return node && node.nodeName === 'P' && node.childNodes.length === 1;
        };

        if (liStart) {
            if (!this.getListStartElement(ec)) {
                selection.empty();
                range.setStartAfter(liStart);
                range.setEndAfter(ec);
                selection.addRange(range);
            } else if (command) {
                handleListElementToggle(command, liStart);
            }
        }

        selection = this.restoreRangeMaker(staticRange);

        if (command) {
            this.queryCommand(command);
        }

        staticRange = this.createStaticRange(selection.getRangeAt(0));
        liStart = this.getListStartElement(this.getRangeElement(staticRange));
        ec = liStart ? liStart.parentNode : null;

        if (shouldReplaceParentParagraph(ec)) {
            ec.replaceWith(liStart);
        }

        this.restoreStaticRange(selection, staticRange);
    },

    setPictureResizer: function (resizer, picture) {
        const rect = picture.getBoundingClientRect();
        resizer.style.width = `${rect.width}px`;
        resizer.style.height = `${rect.height}px`;
    },

    createImageResizeEventHandler: function (resizerWrapper) {
        let targetNodeWidth = 0,
            mouseX = 0,
            targetNode,
            pictureNode,
            resizer,
            rootNode,
            rootNodeWidth,
            newWidth,
            newWidthPercent,
            xPos,
            popover,
            status;
        const MINIMUM_SIZE = 16;
        const resizeHandler = evt => {
            xPos = evt.pageX - mouseX;
            if (resizer.classList.contains('resizer-handle-bottom-right')) {
                newWidth = targetNodeWidth + xPos;
            } else if (resizer.classList.contains('resizer-handle-bottom-left')) {
                newWidth = targetNodeWidth - xPos;
            } else if (resizer.classList.contains('resizer-handle-top-right')) {
                newWidth = targetNodeWidth + xPos;
            } else { /* resizer-handle-top-left */
                newWidth = targetNodeWidth - xPos;
            }
            if (newWidth > MINIMUM_SIZE) {
                newWidthPercent = ((100 * newWidth) / rootNodeWidth).toFixed(2);
                targetNode.style.width = newWidthPercent + '%';
                status.textContent = newWidthPercent + '%';
                resizerWrapper.style.height = pictureNode.offsetHeight + 'px';
            }
        };
        const stopResizeHandler = () => {
            this.removeEvent(this.doc, 'mousemove', resizeHandler);
            this.removeEvent(this.doc, 'mouseup', stopResizeHandler);
            status.remove();
            if (popover) {
                popover.showPopover();
            }
        };
        const imageResizingEvent = new CustomEvent("image-resizing", {
            cancelable: true,
            bubbles: true,
            detail: 'image-resizing',
        });

        resizerWrapper.childNodes.forEach((node) => {
            this.addEvent(node, 'mousedown', evt => {
                this.stopEvent(evt);
                targetNode = node.closest('figure');
                pictureNode = targetNode.querySelector('picture');

                if (targetNode) {
                    popover = this.popper;
                    if (popover) {
                        popover.hidePopover();
                    }

                    status = this.doc.createElement('div');
                    status.className = 'image-resize-status';
                    targetNode.appendChild(status);
                    rootNode = targetNode.parentElement;

                    while (!rootNode.isSameNode(this.body) && !this.isBlockElement(rootNode)) {
                        rootNode = rootNode.parentElement;
                    }

                    rootNodeWidth = this.getElementWidth(rootNode);
                    targetNodeWidth = this.getElementWidth(targetNode);
                    mouseX = evt.pageX;
                    resizer = node;

                    this.addEvent(this.doc, 'mousemove', resizeHandler);
                    this.addEvent(this.doc, 'mouseup', stopResizeHandler);
                    this.pictureBogusCaret.dispatchEvent(imageResizingEvent);
                }
            });
        });
    },

    getElementWidth: function (elem) {
        return parseFloat(getComputedStyle(elem, null)
            .getPropertyValue('width')
            .replace('px', ''));
    },

    createModifyImageHandler: function (figure) {
        const picture = figure.querySelector('picture');

        this.pictureResizer = this.cheditor.imageResizer.cloneNode(true);
        this.setPictureResizer(this.pictureResizer, picture);
        figure.appendChild(this.pictureResizer);

        this.createBogusCaret();
        this.createImageResizeEventHandler(this.pictureResizer);
    },

    hasBogusCaret: function (selection = this.getSelection()) {
        const fakeCaret = this.pictureBogusCaret;

        if (!fakeCaret || selection.type === 'None') {
            return null;
        }

        const isCollapsed = selection.isCollapsed;
        const activeElement = this.doc.activeElement;
        const isFigCaption = activeElement && activeElement.nodeName === 'FIGCAPTION';

        return (isCollapsed && !isFigCaption)
            ? null
            : { hasCaret: !isCollapsed && selection.focusNode.isSameNode(fakeCaret),
                hasCaption: isFigCaption };
    },

    clearHelperNodes: function () {
        const disabledFigureNodes = this.doc.querySelectorAll('figure.disabled');
        disabledFigureNodes.forEach(node => {
            node.classList.remove('disabled');
        });
        const contentEditableNodes = this.doc.querySelectorAll('[contenteditable="true"]');
        contentEditableNodes.forEach(node => {
            node.removeAttribute('contenteditable');
        });

        this.removePictureResizer();
    },

    removeBogusCaret: function () {
        if (this.hasBogusCaret()) {
            return false;
        }
        this.removePictureResizer();
        return true;
    },

    selectionBogusCaret: function (selection = this.getSelection()) {
        if (this.pictureBogusCaret) {
            selection.selectAllChildren(this.pictureBogusCaret);
        }
    },

    createBogusCaret: function () {
        const fakeCaret = this.doc.createElement('div');

        fakeCaret.className = 'is-bogus-caret active';
        fakeCaret.append('is fake caret');

        const handleSelectionChange = (evt, evtHandler) => {
            if (evt.target.activeElement?.nodeName === 'FIGCAPTION') {
                this.stopEvent(evt);
                return;
            }
            if (this.removeBogusCaret()) {
                this.removeEvent(this.doc, 'selectionchange', evtHandler);
            }
        };
        const evtHandler = (evt) => handleSelectionChange(evt, evtHandler);

        this.addEvent(this.doc, 'selectionchange', evtHandler);
        this.pictureBogusCaret = this.body.appendChild(fakeCaret);
        this.selectionBogusCaret();
    },

    isInBody: function (node) {
        return this.isBody(node) ? false : this.body.contains(node);
    },

    isBody: function (node) {
        return this.body.isSameNode(node);
    },

    determineCurrentNode: function (node) {
        const range = this.getRange();
        if (this.isElementNode(node) &&
            this.isInBody(node) &&
            (range.collapsed || this.pictureBogusCaret)) {
            return node;
        }
        return this.getParentIfTextNode(range.commonAncestorContainer);
    },

    updateEditBlock: function (command, currNode) {
        if (command === 'img') {
            const figure = currNode.closest('figure');
            if (figure) {
                this.createImageFigure(figure, currNode);
            }
        }
    },

    removeTableButton: function (figure) {
        const btn = figure.querySelector('.btn-figure');
        if (btn) {
            btn.remove();
        }
    },

    createTableButton: function (figure) {
        const buttonWrapper = this.doc.createElement('div');
        const btnRemove = this.doc.createElement('div');
        buttonWrapper.append(btnRemove);
        buttonWrapper.className = 'btn-figure';
        btnRemove.className = 'btn-remove';

        this.addEvent(btnRemove, 'mousedown', evt => this.stopEvent(evt));
        this.addEvent(btnRemove, 'click', () => this.removeNode(figure));

        figure.append(buttonWrapper);
    },

    outputTagSelector: function (elem) {
        const htmlTagSelector = this.cheditor.htmlTagSelector;
        const buildTagHierarchy = (node) => {
            const hierarchy = [];
            while (node && !this.isBody(node)) {
                hierarchy.unshift(node);
                node = node.parentNode;
            }
            return hierarchy;
        };
        const createTagSelector = (tag) => {
            let anchor, tagName = tag.tagName;

            if (tagName === 'FIGURE') {
                anchor = tagName;
            } else {
                anchor = document.createElement('a');
                anchor.href = '#';
                anchor.el = tag;
                anchor.textContent = tagName;
            }

            const item = document.createElement('li');
            item.className = 'tag-selector-item';
            return { anchor, item };
        };

        this.emptyChildren(htmlTagSelector);

        const { anchor, item } = createTagSelector(this.body);
        item.append(anchor);
        htmlTagSelector.append(item);

        if (!elem) {
            elem = this.getRangeElement(this.getRange());
        }

        const hasBogusCaret = this.hasBogusCaret() || elem.closest('figcaption');

        buildTagHierarchy(elem).forEach((tag, idx) => {
            const { anchor, item } = createTagSelector(tag, idx);
            const isTable = tag.nodeName === 'TABLE' && tag.parentElement.nodeName === 'FIGURE';

            if (idx === 0 ||
                (!hasBogusCaret && GB.quickInsertParagraphElements.includes(tag.tagName)) && !isTable)
            {
                const insertTop = this.quickInsertParagraphEventHandler(tag, 'top');
                const insertBottom = this.quickInsertParagraphEventHandler(tag, 'bottom');
                item.append(insertTop, anchor, insertBottom);
            } else {
                item.append(hasBogusCaret ? anchor.textContent : anchor);
            }
            htmlTagSelector.append(item);
        });

        const end = item.cloneNode(false);
        end.append(this.createNbspTextNode());
        htmlTagSelector.append(end);
    },

    quickInsertParagraphEventHandler: function (targetElem, position) {
        const button = this.doc.createElement('span');
        button.dataset.insert = position;
        button.el = targetElem;
        button.className = `cheditor-insert-paragraph-btn-${position}`;

        this.addEvent(button, 'mouseover', evt => {
            this.showInsertParagraphLine(evt);
            this.showNodeRange(evt.target.el);
        });
        this.addEvent(button, 'mouseout', (evt) => {
            this.cheditor.insertParagraphLine.classList.add('d-none');
            this.hideNodeRange(evt.target.el);
        });
        this.addEvent(button, 'click', evt => {
            this.insertParagraphTopBottom(`insert-paragraph-${position}`,
                evt.target.el, this.getSelection());
            this.cheditor.insertParagraphLine.classList.add('d-none');
            this.hideNodeRange(evt.target.el);
            this.updateToolbar();
        });

        return button;
    },

    showNodeRange: function (elem) {
        elem.classList.add('show-node-range');
    },
    hideNodeRange: function (elem) {
        elem.classList.remove('show-node-range');
        if (elem.classList.length === 0) {
            elem.removeAttribute('class');
        }
    },

    showInsertParagraphLine: function (evt) {
        const { scrollTop } = this.scrollXY();
        const targetElement = evt.target.el;
        const position = evt.target.dataset;
        const rect = this.getOffsetBox(targetElement);
        let targetOffsetTop = rect.top;

        const updateParagraphLinePosition = (position) => {
            this.cheditor.insertParagraphLine.style.top = position + 'px';
            const left = rect.left + 'px';
            const width= targetElement.offsetWidth + 'px';
            this.cheditor.insertParagraphLine.style.left = left;
            this.cheditor.insertParagraphLine.style.width = width;
            this.cheditor.insertParagraphLine.classList.remove('d-none');
        };

        if (position.insert === 'bottom') {
            targetOffsetTop += rect.height;
        }

        const paragraphLinePosition = targetOffsetTop - scrollTop;
        updateParagraphLinePosition(paragraphLinePosition);
    },

/*
    getTableCellIndex: function (cell) {
        const row = cell.closest('tr');
        const rowRect = row.getBoundingClientRect();
        const colRect = cell.getBoundingClientRect();
        let colWidth = (colRect.width / rowRect.width) * 100;

        return { rowIndex: row.rowIndex, cellIndex: cell.cellIndex };
    },
*/

    handleFirefoxEditableMode: function (elem, isFirefoxEditable) {
        if (isFirefoxEditable) {
            const blurEventHandler = () => {
                this.setDesignMode(true);
                this.removeEvent(elem, 'blur', blurEventHandler);
            };
            this.addEvent(elem, 'blur', blurEventHandler);
            this.setDesignMode(false);
            elem.focus();
        }
        return 'table';
    },

    handleFigureCommand: function (figure) {
        if (!figure || !figure.classList.contains('video')) {
            return;
        }
        const video = figure.querySelector('iframe');
        if (video) {
            figure.classList.add('active');
            this.createBogusCaret();
            this.createTableButton(figure);
        }
    },

    getCommandFromElement: function (elem) {
        if (!elem?.tagName) return null;

        const isFirefoxEditableCell =
            GB.browser.firefox && elem.hasAttribute('contenteditable');

        switch (elem.tagName) {
            case 'IMG':
                return 'img';
            case 'TD': case 'TH':
                return this.handleFirefoxEditableMode(elem, isFirefoxEditableCell);
            case 'FIGURE':
                this.handleFigureCommand(elem);
                break;
            default:
                return elem.classList.contains('resizer') ? 'resizer' : null;
        }
    },

    editareaEventHandler: function (evt) {
        const currElem = this.determineCurrentNode(evt.target);
        const command = this.getCommandFromElement(currElem);
        this.updateEditBlock(command, currElem);
        this.updateToolbar(currElem);
    },

    createImageFigure: function (figure, image = null) {
        if (!image) {
            image = figure.querySelector('img');
            if (!image) {
                return;
            }
        }

        figure.ondragstart = () => false;

        if (this.pictureResizer) {
            const currParent = this.pictureResizer.parentNode;

            if (currParent === figure) {
                const isDisabled = figure.classList.contains('disabled');

                if (isDisabled) {
                    const selection = this.getSelection();

                    this.selectionBogusCaret(selection);
                    selection.collapseToEnd();
                    this.selectionBogusCaret(selection);

                    figure.classList.remove('disabled');
                }

                this.popper.showPopover();
                return;
            }

            currParent.classList.remove('disabled');
        }

        this.createPictureResizer(figure);
    },

    popoverBtnEventHandler: function (evt) {
        const button = evt.target;
        const figure = this.pictureResizer.closest('figure');

        if (!button.classList.contains('btn-popover') || !figure) {
            return;
        }

        const command = button.dataset.cmd;
        const resizer = figure.querySelector('.resizer');
        const picture = figure.querySelector('picture');
        const selection = this.getSelection();
        let captionElem = null;

        switch (command) {
            case 'insert-paragraph-bottom': case 'insert-paragraph-top':
                this.insertParagraphTopBottom(command, figure, selection);
                break;
            case 'remove':
                this.removeNode(figure, selection);
                this.updateToolbar();
                break;
            case 'figcaption':
                captionElem = figure.querySelector('figcaption');
                if (captionElem) {
                    captionElem.remove();
                    button.classList.remove('active');
                    this.selectionBogusCaret(selection);
                    this.updateToolbar();
                } else {
                    this.handleCaptionCreation(figure, selection);
                }
                break;
            case 'left': case 'center': case 'right':
                this.popper.hidePopover();
                this.alignFigure(command, figure, resizer, picture);
                this.setPopoverAlignButton(figure);
                this.popper.showPopover();
                break;
            case 'reset':
                this.alignFigure(command, figure, resizer, picture);
                break;
            default:
                this.popper.hidePopover();
        }
    },

    insertParagraphTopBottom: function (position, elem, selection) {
        const para = this.createEmptyParagraph();
        position === 'insert-paragraph-bottom' ? elem.after(para) : elem.before(para);
        selection.empty();
        selection.setPosition(para, 0);
    },

    createFigCaptionElement: function () {
        const captionConfig = GB.elementsStyle.figure.caption;
        const captionElem = this.doc.createElement('figcaption');
        captionElem.setAttribute('style', captionConfig.style);
        captionConfig
            .attrs
            .forEach(attr => captionElem.setAttribute(attr.name, attr.value));
        return captionElem;
    },

    createFigCaptionEventHandler: function (figure, figcaption, selection) {
        if (GB.browser.firefox) {
            this.addEvent(figcaption, 'blur', () => this.handleCaptionBlur(selection));
            this.addEvent(figcaption, 'mousedown', () => this.setDesignMode(false));
            this.setDesignMode(false);
        }

        this.addEvent(figcaption, 'focus', () => {
            console.log('has');
            if (this.pictureResizer?.parentNode === figure) {
                this.popper.showPopover();
            } else {
                this.createPictureResizer(figure);
            }
        });
    },

    handleCaptionCreation: function (figure, selection) {
        const CONTENT_EDITABLE = 'contenteditable';
        let figCaptionElem = figure.querySelector('figcaption');

        const toggleContentEditable = (value) => {
            figCaptionElem.setAttribute(CONTENT_EDITABLE, value);
        };

        if (!figCaptionElem) {
            figCaptionElem = this.createFigCaptionElement();
            figCaptionElem.appendChild(this.doc.createElement('br'));
            figure.appendChild(figCaptionElem);

            this.popper.querySelector('.btn-figcaption').classList.add('active');
            this.createFigCaptionEventHandler(figure, figCaptionElem, selection);
        }

        if (GB.browser.firefox) {
            this.setDesignMode(false);
        }
        if (!figCaptionElem.hasAttribute(CONTENT_EDITABLE)) {
            toggleContentEditable(true);
        }

        selection.setPosition(figCaptionElem, 0);
        this.updateToolbar();
    },

    alignFigure: function (command, figure, resizer, picture) {
        if (command === 'reset') {
            figure.style.removeProperty('width');
            resizer.style.height = picture.offsetHeight + 'px';
        } else {
            figure.style.margin = GB.elementsStyle.figure.align[command];
        }
    },

    /* firefox */
    handleCaptionBlur: function (selection) {
        let focusNode = this.getParentIfTextNode(selection.focusNode);

        if (!focusNode.closest('figcaption')) {
            this.setDesignMode(true);
            return;
        }

        setTimeout(() => {
            this.setDesignMode(this.doc.hasFocus() && focusNode === selection.focusNode);
        }, 300);
    },

    createPopoverEvent: function () {
        const popoverClickEvent = new CustomEvent("click-popover", {
            cancelable: true,
            bubbles: true,
            detail: 'click-popover',
        });
        this.popper.dispatchEvent(popoverClickEvent);
    },

    getFigureAlign: function (figure) {
        const { style } = figure;

        if (style.marginLeft === 'auto' && style.marginRight === 'auto') {
            return 'center';
        }
        if (style.marginLeft === 'auto' && style.marginRight !== 'auto') {
            return 'right';
        }

        return 'left';
    },

    setPopoverAlignButton: function (figure) {
        const figureAlign = this.getFigureAlign(figure);
        this.popper.querySelectorAll('span[class*="btn-align"]')
            .forEach((alignButton) => {
                alignButton.classList.toggle('active', figureAlign === alignButton.dataset.cmd);
            });
    },

    createPopover: function (figure) {
        const OFFSET_TOP = 10;
        const OFFSET_BOTTOM = 40;
        const MINIMUM_LEFT = 3;

        if (!this.popper) {
            this.popper = this.cheditor.imagePopover.cloneNode(true);
        }

        const popover = this.popper;
        const setupPopoverEvents = () => {
            ['mousedown', 'mouseup', 'click']
                .forEach(type => this.addEvent(popover, type, evt => this.stopEvent(evt)));
            this.addEvent(popover, 'click', evt => this.popoverBtnEventHandler(evt));
            this.addEvent(popover, 'mousedown', () => this.createPopoverEvent());
        };
        const calculatePopoverPosition = () => {
            const wrapperBox = this.getOffsetBox(this.pictureResizer);
            const popperBox = popover.getBoundingClientRect();
            popover.style.top = `${wrapperBox.top + OFFSET_TOP}px`;
            popover.style.left = `${wrapperBox.center - Math.round(popperBox.width / 2)}px`;

            if (wrapperBox.width < popperBox.width) {
                const recalcBox = popover.getBoundingClientRect();
                if (recalcBox.left < 1) {
                    popover.style.left = `${wrapperBox.left + MINIMUM_LEFT}px`;
                } else if (popperBox.width > recalcBox.width) {
                    popover.style.left = `${wrapperBox.left + wrapperBox.width - popperBox.width - MINIMUM_LEFT}px`;
                }
            }
        };

        popover.classList.add('d-none');
        setupPopoverEvents();
        this.setPopoverAlignButton(figure);
        this.body.appendChild(popover);

        if (!this.ioObserver) {
            this.ioObserver = new IntersectionObserver(entries => {
                entries.forEach(entry => {
                    if (!entry.isIntersecting) {
                        const resizerBox = this.getOffsetBox(this.pictureResizer);
                        if (!resizerBox) return;

                        const targetBox = this.getOffsetBox(entry.target);
                        const isTopAligned = resizerBox.top + OFFSET_TOP === targetBox.top;
                        entry.target.style.top = (isTopAligned
                            ? resizerBox.bottom - OFFSET_BOTTOM
                            : resizerBox.top + OFFSET_TOP) + 'px';
                    }
                });
            }, { threshold: 1 });
        }

        this.addEvent(popover, 'toggle', evt => {
            const figCaptionExists = figure.querySelector('figcaption');
            const captionBtn = this.popper.querySelector('.btn-figcaption');

            if (evt.newState === 'open') {
                if (figCaptionExists) {
                    captionBtn.classList.add('active');
                }
                popover.classList.remove('d-none');
                calculatePopoverPosition();
                this.ioObserver.observe(popover);
            } else {
                popover.classList.add('d-none');
                this.ioObserver.disconnect();
            }
        });

        this.popper.showPopover();
    },

    removePopover: function () {
        if (this.popper) {
            this.popper.remove();
            this.popper = null;
        }
    },

    createPictureResizer: function (figure) {
        this.removePictureResizer();
        this.createModifyImageHandler(figure);
        this.createPopover(figure);
        figure.classList.add('active');
    },

    cleanUpElement: function (elemName) {
        if (this[elemName]) {
            this[elemName].remove();
            this[elemName] = null;
        }
    },

    removePictureResizer: function () {
        const figureElements = this.doc.querySelectorAll('figure.active');

        figureElements.forEach(figure => {
            figure.classList.remove('active');
            if (!figure.classList.length) {
                figure.removeAttribute('class');
            }
            this.removeTableButton(figure);
        });

        this.removePopover();
        this.cleanUpElement('pictureResizer');
        this.cleanUpElement('pictureBogusCaret');
    },

    cutSelectedNode: function () {
        const NON_REMOVABLE_TAGS = ['TD', 'TH'];
        const selection = this.getSelection();

        selection.deleteFromDocument();

        const rangeElem = this.getRangeElement(selection.getRangeAt(0));
        let currNode = rangeElem, parentNode;

        while (currNode &&
            !currNode.hasChildNodes() &&
            this.isInBody(currNode) &&
            !NON_REMOVABLE_TAGS.includes(currNode.tagName)) {
            parentNode = currNode.parentNode;
            currNode.remove();
            currNode = parentNode;
        }

        this.updateToolbar();
    },

    tagSelection: function (parentNode) {
        const selection = this.getSelection();
        selection.selectAllChildren(parentNode);
        this.updateToolbar();
    },

    getBrowser : function () {
        return GB.browser;
    },
};

(function () {
    const dragWindow = {
        obj: null,
        init: function (drag, oRoot, minX, maxX, minY, maxY) {
            drag.style.curser = 'default';
            drag.onmousedown = dragWindow.start;
            drag.onmouseover = function () {
                this.style.cursor = 'default';
            };
            drag.hmode = true;
            drag.vmode = true;
            drag.root = oRoot;
            drag.transId = oRoot.id + '_trans';

            if (drag.hmode  && isNaN(parseInt(drag.root.style.left, 10))) {
                drag.root.style.left = '0';
            }
            if (drag.vmode  && isNaN(parseInt(drag.root.style.top, 10))) {
                drag.root.style.top = '0';
            }
            if (!drag.hmode && isNaN(parseInt(drag.root.style.right, 10))) {
                drag.root.style.right = '0';
            }
            if (!drag.vmode && isNaN(parseInt(drag.root.style.bottom, 10))) {
                drag.root.style.bottom = '0';
            }

            drag.minX = minX !== undefined ? minX : null;
            drag.minY = minY !== undefined ? minY : null;
            drag.maxX = maxX !== undefined ? maxX : null;
            drag.maxY = maxY !== undefined ? maxY : null;
            drag.root.onDragStart = new Function();
            drag.root.onDragEnd = new Function();
            drag.root.onDrag = new Function();
        },
        start: function (evt) {
            const drag = dragWindow.obj = this,
                dragBackdrop = document.createElement('div'),
                y = parseInt(drag.vmode ? drag.root.style.top  : drag.root.style.bottom, 10),
                x = parseInt(drag.hmode ? drag.root.style.left : drag.root.style.right, 10),
                lastEl = drag.root.lastElementChild;

            evt = dragWindow.fixEvent(evt);
            drag.root.onDragStart(x, y);
            drag.lastMouseX = evt.clientX;
            drag.lastMouseY = evt.clientY;

            document.onmousemove = dragWindow.drag;
            document.onmouseup = dragWindow.end;

            if (lastEl.id === drag.transId) {
                return false;
            }

            dragBackdrop.className = 'cheditor-modal-drag-window modal-backdrop';
            dragBackdrop.id = drag.transId;
            dragBackdrop.style.top = lastEl.offsetTop + 'px';
            dragBackdrop.style.width = lastEl.offsetWidth + 'px';
            dragBackdrop.style.height = lastEl.offsetHeight + 'px';
            dragBackdrop.position = 'absolute';
            drag.root.appendChild(dragBackdrop);
            return false;
        },
        drag: function (evt) {
            evt = dragWindow.fixEvent(evt);
            const drag = dragWindow.obj,
                ey = evt.clientY,
                ex = evt.clientX,
                y = parseInt(drag.vmode ? drag.root.style.top : drag.root.style.bottom, 10),
                x = parseInt(drag.hmode ? drag.root.style.left : drag.root.style.right, 10);
            let nx, ny;

            nx = x + (ex - drag.lastMouseX) * (drag.hmode ? 1 : -1);
            ny = y + (ey - drag.lastMouseY) * (drag.vmode ? 1 : -1);

            dragWindow.obj.root.style.left = nx + 'px';
            dragWindow.obj.root.style.top = ny + 'px';
            dragWindow.obj.lastMouseX = ex;
            dragWindow.obj.lastMouseY = ey;
            dragWindow.obj.root.onDrag(nx, ny);
            return false;
        },
        end: function () {
            document.onmousemove = null;
            document.onmouseup = null;
            dragWindow.obj.root.onDragEnd(parseInt(dragWindow.obj.root.style[dragWindow.obj.hmode ? 'left' : 'right'], 10),
                    parseInt(dragWindow.obj.root.style[dragWindow.obj.vmode ? 'top' : 'bottom'], 10));

            if (dragWindow.obj.root.lastElementChild.id === dragWindow.obj.transId) {
                dragWindow.obj.root.lastElementChild.remove();
            }
            dragWindow.obj = null;
        },
        fixEvent: function (evt) {
            if (evt.layerX === undefined) {
                evt.layerX = evt.offsetX;
            }
            if (evt.layerY === undefined) {
                evt.layerY = evt.offsetY;
            }
            return evt;
        }
    };
    GB.dragWindow = dragWindow;
})();

// --------------------------------------------------------------------------
// UndoManager
//
(function () {
    function removeFromTo(array, from, to) {
        array.splice(from, !to || 1 + to - from +
            (!((to < 0) ^ (from >= 0)) && (to < 0 || -1) * array.length));
        return array.length;
    }

    GB.UndoManager = function () {
        let commands = [],
            index = -1,
            limit = 0,
            isExecuting = false,
            callback;

        function execute(command, action) {
            if (!command || typeof command[action] !== 'function') {
                return this;
            }
            isExecuting = true;
            command[action]();
            isExecuting = false;

            return this;
        }

        return {
            add: function (command) {
                if (isExecuting) {
                    return this;
                }
                commands.splice(index + 1, commands.length - index);
                commands.push(command);

                if (limit && commands.length > limit) {
                    removeFromTo(commands, 0, -(limit + 1));
                }

                index = commands.length - 1;
                if (callback) {
                    callback();
                }
                return this;
            },
            setCallback: function (callbackFunc) {
                callback = callbackFunc;
            },
            undo: function () {
                let command = commands[index];
                if (!command) {
                    return this;
                }

                const groupId = command.groupId;
                while (command.groupId === groupId) {
                    execute(command, 'undo');
                    index -= 1;
                    command = commands[index];
                    if (!command || !command.groupId) break;
                }

                if (callback) {
                    callback();
                }
                return this;
            },
            redo: function () {
                let command = commands[index + 1];
                if (!command) {
                    return this;
                }

                const groupId = command.groupId;
                while (command.groupId === groupId) {
                    execute(command, 'redo');
                    index += 1;
                    command = commands[index + 1];
                    if (!command || !command.groupId) break;
                }

                if (callback) {
                    callback();
                }
                return this;
            },
            clear: function () {
                const prev_size = commands.length;

                commands = [];
                index = -1;

                if (callback && prev_size > 0) {
                    callback();
                }
            },
            hasUndo: function () { return index !== -1; },
            hasRedo: function () { return index < commands.length - 1; },
            getCommands: function (groupId) {
                return groupId ? commands.filter(c => c.groupId === groupId) : commands;
            },
            getIndex: function () { return index; },
            setLimit: function (max) { limit = max; }
        };
    };
})();

// --------------------------------------------------------------------------
// Color Picker
//
(function () {
    const colorDropper = {
        images: {pad: [181, 101], sld: [16, 101], cross: [15, 15], arrow: [7, 11]},
        fetchElement: function (mixed) {
            return typeof mixed === 'string' ? document.getElementById(mixed) : mixed;
        },

        addEvent: function (el, ev, func) {
            el.addEventListener(ev, func, false);
        },

        fireEvent: function (el, ev) {
            if (!el) {
                return;
            }
            let c_ev = document.createEvent('HTMLEvents');
            c_ev.initEvent(ev, true, true);
            el.dispatchEvent(c_ev);
        },

        getElementPos: function (ev) {
            let e1 = ev, e2 = ev, x = 0, y = 0;
            if (e1.offsetParent) {
                do {
                    x += e1.offsetLeft;
                    y += e1.offsetTop;
                    e1 = e1.offsetParent;
                } while (e1);
            }

            while (e2 && e2.nodeName.toLowerCase() !== 'body') {
                x -= e2.scrollLeft;
                y -= e2.scrollTop;
                e2 = e2.parentNode;
            }
            return [x, y];
        },

        getElementSize: function (e) {
            return [e.offsetWidth, e.offsetHeight];
        },

        getRelMousePos: function (e) {
            let x = 0, y = 0;
            if (!e) {
                e = window.event;
            }
            if (typeof e.offsetX === 'number') {
                x = e.offsetX;
                y = e.offsetY;
            } else if (typeof e.layerX === 'number') {
                x = e.layerX;
                y = e.layerY;
            }
            return {x: x, y: y};
        },

        color: function (target, prop) {
            this.required = true;
            this.adjust = true;
            this.hash = true;
            this.caps = false;
            this.valueElement = target;
            this.styleElement = target;
            this.onImmediateChange = null;
            this.hsv = [0, 0, 1];
            this.rgb = [1, 1, 1];
            this.minH = 0;
            this.maxH = 6;
            this.minS = 0;
            this.maxS = 1;
            this.minV = 0;
            this.maxV = 1;

            // this.pickerOnfocus = true;
            this.pickerMode = 'HSV';
            this.pickerFace = 3;
            this.pickerFaceColor = '#fff';
            this.pickerInset = 1;
            this.pickerInsetColor = '#c2c9d6';
            // this.pickerZIndex = 10003;

            let p,
                self = this,
                modeID = this.pickerMode.toLowerCase() === 'hvs' ? 1 : 0,
                abortBlur = false,
                valueElement = colorDropper.fetchElement(this.valueElement),
                styleElement = colorDropper.fetchElement(this.styleElement),
                holdPad = false,
                holdSld = false,
                touchOffset = {},
                leaveValue = 1 << 0,
                leaveStyle = 1 << 1,
                leavePad = 1 << 2,
                leaveSld = 1 << 3,
                updateFieldEventHandler = function () {
                    self.fromString(valueElement.value, leaveValue);
                    dispatchImmediateChange();
                };

            colorDropper.addEvent(target, 'blur', function () {
                if (!abortBlur) {
                    window.setTimeout(function () {
                        abortBlur || blurTarget();
                        abortBlur = false;
                    }, 0);
                } else {
                    abortBlur = false;
                }
            });

            for (p in prop) {
                if (Object.hasOwn(prop, p)) {
                    this[p] = prop[p];
                }
            }

            this.hidePicker = function () {
                if (isPickerOwner()) {
                    removePicker();
                }
            };

            this.showPicker = function () {
                if (!isPickerOwner()) {
                    drawPicker();
                }
            };

            this.importColor = function () {
                if (!valueElement) {
                    this.exportColor();
                } else {
                    if (!this.adjust) {
                        if (!this.fromString(valueElement.value, leaveValue)) {
                            styleElement.style.backgroundImage = styleElement.jscStyle.backgroundImage;
                            styleElement.style.backgroundColor = styleElement.jscStyle.backgroundColor;
                            styleElement.style.color = styleElement.jscStyle.color;
                            this.exportColor(leaveValue | leaveStyle);
                        }
                    } else if (!this.required && /^\s*$/.test(valueElement.value)) {
                        valueElement.value = '';
                        styleElement.style.backgroundImage = styleElement.jscStyle.backgroundImage;
                        styleElement.style.backgroundColor = styleElement.jscStyle.backgroundColor;
                        styleElement.style.color = styleElement.jscStyle.color;
                        this.exportColor(leaveValue | leaveStyle);
                    } else if (this.fromString(valueElement.value)) {
                        // ignore
                    } else {
                        this.exportColor();
                    }
                }
            };

            this.exportColor = function (flags) {
                if (!(flags & leaveValue) && valueElement) {
                    let value = this.toString();
                    if (this.caps) {
                        value = value.toUpperCase();
                    }
                    if (this.hash) {
                        value = '#' + value;
                    }
                    valueElement.value = value;
                }
                if (!(flags & leaveStyle) && styleElement) {
                    styleElement.style.backgroundImage = 'none';
                    styleElement.style.backgroundColor = '#' + this.toString();
                    styleElement.style.color = 0.213 * this.rgb[0] + 0.715 * this.rgb[1] + 0.072 * this.rgb[2] < 0.5 ? '#ffffff' : '#000000';
                }
                if (!(flags & leavePad) && isPickerOwner()) {
                    redrawPad();
                }
                if (!(flags & leaveSld) && isPickerOwner()) {
                    redrawSld();
                }
            };

            this.fromHSV = function (h, s, v, flags) {
                if (h) {
                    h = Math.max(0.0, this.minH, Math.min(6.0, this.maxH, h));
                }
                if (s) {
                    s = Math.max(0.0, this.minS, Math.min(1.0, this.maxS, s));
                }
                if (v) {
                    v = Math.max(0.0, this.minV, Math.min(1.0, this.maxV, v));
                }

                this.rgb = this.HSV_RGB(
                    h === null ? this.hsv[0] : this.hsv[0] = h,
                    s === null ? this.hsv[1] : this.hsv[1] = s,
                    v === null ? this.hsv[2] : this.hsv[2] = v
                );
                this.exportColor(flags);
            };

            this.fromRGB = function (r, g, b, flags) {
                if (r) {
                    r = Math.max(0.0, Math.min(1.0, r));
                }
                if (g) {
                    g = Math.max(0.0, Math.min(1.0, g));
                }
                if (b) {
                    b = Math.max(0.0, Math.min(1.0, b));
                }

                const hsv = this.RGB_HSV(
                    r === null ? this.rgb[0] : r,
                    g === null ? this.rgb[1] : g,
                    b === null ? this.rgb[2] : b
                );
                if (hsv[0] !== null) {
                    this.hsv[0] = Math.max(0.0, this.minH, Math.min(6.0, this.maxH, hsv[0]));
                }
                if (hsv[2] !== 0) {
                    this.hsv[1] = hsv[1] === null ? null : Math.max(0.0, this.minS, Math.min(1.0, this.maxS, hsv[1]));
                }
                this.hsv[2] = hsv[2] === null ? null : Math.max(0.0, this.minV, Math.min(1.0, this.maxV, hsv[2]));

                const rgb = this.HSV_RGB(this.hsv[0], this.hsv[1], this.hsv[2]);
                this.rgb[0] = rgb[0];
                this.rgb[1] = rgb[1];
                this.rgb[2] = rgb[2];

                this.exportColor(flags);
            };

            this.fromString = function (hex, flags) {
                const m = hex.match(/^\W*([0-9A-F]{3}([0-9A-F]{3})?)\W*$/i);

                if (!m) {
                    valueElement.value = '색 없음';
                    valueElement.style.color = '#000';
                    valueElement.style.backgroundColor = '#fff';
                    return false;
                }

                if (m[1].length === 6) {
                    this.fromRGB(
                        parseInt(m[1].substring(0, 2), 16) / 255,
                        parseInt(m[1].substring(2, 4), 16) / 255,
                        parseInt(m[1].substring(4, 6), 16) / 255,
                        flags
                    );
                } else {
                    this.fromRGB(
                        parseInt(m[1].charAt(0) + m[1].charAt(0), 16) / 255,
                        parseInt(m[1].charAt(1) + m[1].charAt(1), 16) / 255,
                        parseInt(m[1].charAt(2) + m[1].charAt(2), 16) / 255,
                        flags
                    );
                }

                return true;
            };

            this.toString = function () {
                return (
                    (0x100 | Math.round(255 * this.rgb[0])).toString(16).substring(1) +
                    (0x100 | Math.round(255 * this.rgb[1])).toString(16).substring(1) +
                    (0x100 | Math.round(255 * this.rgb[2])).toString(16).substring(1)
                );
            };

            this.RGB_HSV = function (r, g, b) {
                const n = Math.min(Math.min(r, g), b),
                    v = Math.max(Math.max(r, g), b),
                    m = v - n;
                if (m === 0) {
                    return [null, 0, v];
                }
                let h = r === n ? 3 + (b - g) / m : g === n ? 5 + (r - b) / m : 1 + (g - r) / m;
                return [h === 6 ? 0 : h, m / v, v];
            };

            this.HSV_RGB = function (h, s, v) {
                if (h === null) {
                    return [v, v, v];
                }
                const i = Math.floor(h),
                    f = i % 2 ? h - i : 1 - (h - i),
                    m = v * (1 - s),
                    n = v * (1 - s * f);
                switch (i) {
                    case 6:
                    case 0:
                        return [v, n, m];
                    case 1:
                        return [n, v, m];
                    case 2:
                        return [m, v, n];
                    case 3:
                        return [m, n, v];
                    case 4:
                        return [n, m, v];
                    case 5:
                        return [v, m, n];
                }
            };

            function removePicker() {
                delete colorDropper.picker.owner;
                colorDropper.picker.boxB.parentNode.removeChild(colorDropper.picker.boxB);
            }

            function drawPicker() {
                const touchMoveEventHandler = function (e) {
                        let event = {
                            'offsetX': e.touches[0].pageX - touchOffset.X,
                            'offsetY': e.touches[0].pageY - touchOffset.Y
                        };
                        if (holdPad || holdSld) {
                            holdPad && setPad(event);
                            holdSld && setSld(event);
                            dispatchImmediateChange();
                        }
                        e.stopPropagation();
                        e.preventDefault();
                    },
                    dims = getPickerDims(self),
                    padImg = modeID ? 'color_picker_hv.png' : 'color_picker_hs.png';
                let i, seg, segSize;

                if (!colorDropper.picker) {
                    colorDropper.picker = {
                        box: document.createElement('div'),
                        boxB: document.createElement('div'),
                        pad: document.createElement('div'),
                        padB: document.createElement('div'),
                        padM: document.createElement('div'),
                        sld: document.createElement('div'),
                        sldB: document.createElement('div'),
                        sldM: document.createElement('div')
                    };
                    for (i = 0, segSize = 2; i < colorDropper.images.sld[1]; i += segSize) {
                        seg = document.createElement('div');
                        seg.style.height = segSize + 'px';
                        seg.style.fontSize = '1px';
                        seg.style.lineHeight = '0';
                        colorDropper.picker.sld.appendChild(seg);
                    }
                    colorDropper.picker.sldB.appendChild(colorDropper.picker.sld);
                    colorDropper.picker.box.appendChild(colorDropper.picker.sldB);
                    colorDropper.picker.box.appendChild(colorDropper.picker.sldM);
                    colorDropper.picker.padB.appendChild(colorDropper.picker.pad);
                    colorDropper.picker.box.appendChild(colorDropper.picker.padB);
                    colorDropper.picker.box.appendChild(colorDropper.picker.padM);
                    colorDropper.picker.boxB.appendChild(colorDropper.picker.box);
                }

                p = colorDropper.picker;
                p.box.onmouseup = p.box.onmouseout = function () {
                    target.focus();
                };
                p.box.onmousedown = function () {
                    abortBlur = true;
                };
                p.box.onmousemove = function (e) {
                    if (holdPad || holdSld) {
                        holdPad && setPad(e);
                        holdSld && setSld(e);
                        if (document.selection) {
                            document.selection.empty();
                        } else if (window.getSelection) {
                            window.getSelection().removeAllRanges();
                        }
                        dispatchImmediateChange();
                    }
                };

                if ('ontouchstart' in window) {
                    p.box.removeEventListener('touchmove', touchMoveEventHandler, false);
                    p.box.addEventListener('touchmove', touchMoveEventHandler, false);
                }
                p.padM.onmouseup = p.padM.onmouseout = function () {
                    if (holdPad) {
                        holdPad = false;
                        colorDropper.fireEvent(valueElement, 'change');
                    }
                };
                p.padM.onmousedown = function (e) {
                    switch (modeID) {
                        case 0:
                            if (self.hsv[2] === 0) {
                                self.fromHSV(null, null, 1.0);
                            }
                            break;
                        case 1:
                            if (self.hsv[1] === 0) {
                                self.fromHSV(null, 1.0, null);
                            }
                            break;
                    }
                    holdSld = false;
                    holdPad = true;
                    setPad(e);
                    dispatchImmediateChange();
                };

                if ('ontouchstart' in window) {
                    p.padM.addEventListener('touchstart', function (e) {
                        touchOffset = {'X': getOffsetParent(e.target).Left, 'Y': getOffsetParent(e.target).Top};
                        this.onmousedown({
                            'offsetX': e.touches[0].pageX - touchOffset.X,
                            'offsetY': e.touches[0].pageY - touchOffset.Y
                        });
                    });
                }
                p.sldM.onmouseup = p.sldM.onmouseout = function () {
                    if (holdSld) {
                        holdSld = false;
                        colorDropper.fireEvent(valueElement, 'change');
                    }
                };
                p.sldM.onmousedown = function (e) {
                    holdPad = false;
                    holdSld = true;
                    setSld(e);
                    dispatchImmediateChange();
                };
                if ('ontouchstart' in window) {
                    p.sldM.addEventListener('touchstart', function (e) {
                        touchOffset = {'X': getOffsetParent(e.target).Left, 'Y': getOffsetParent(e.target).Top};
                        this.onmousedown({
                            'offsetX': e.touches[0].pageX - touchOffset.X,
                            'offsetY': e.touches[0].pageY - touchOffset.Y
                        });
                    });
                }

                p.box.style.width = dims[0] + 'px';
                p.box.style.height = dims[1] + 'px';

                p.boxB.style.position = 'relative';
                p.boxB.style.clear = 'both';
                p.boxB.style.border = 'none';
                p.boxB.style.background = self.pickerFaceColor;

                p.pad.style.width = colorDropper.images.pad[0] + 'px';
                p.pad.style.height = colorDropper.images.pad[1] + 'px';

                p.padB.style.position = 'absolute';
                p.padB.style.left = self.pickerFace + 'px';
                p.padB.style.top = self.pickerFace + 'px';
                p.padB.style.border = self.pickerInset + 'px solid';
                p.padB.style.borderColor = self.pickerInsetColor;

                p.padM.style.position = 'absolute';
                p.padM.style.left = '0';
                p.padM.style.top = '0';
                p.padM.style.width = self.pickerFace + 2 * self.pickerInset + colorDropper.images.pad[0] + colorDropper.images.arrow[0] + 'px';
                p.padM.style.height = p.box.style.height;
                p.padM.style.cursor = 'crosshair';

                p.sld.style.overflow = 'hidden';
                p.sld.style.width = '13px';
                p.sld.style.height = colorDropper.images.sld[1] + 'px';

                p.sldB.style.position = 'absolute';
                p.sldB.style.right = self.pickerFace + 'px';
                p.sldB.style.top = self.pickerFace + 'px';
                p.sldB.style.border = self.pickerInset + 'px solid';
                p.sldB.style.borderColor = self.pickerInsetColor;

                p.sldM.style.position = 'absolute';
                p.sldM.style.right = '0';
                p.sldM.style.top = '0';
                p.sldM.style.width = 14 + colorDropper.images.arrow[0] + self.pickerFace + 2 * self.pickerInset + 'px';
                p.sldM.style.height = p.box.style.height;

                try {
                    p.sldM.style.cursor = 'pointer';
                } catch (e) {
                    p.sldM.style.cursor = 'hand';
                }

                p.padM.style.backgroundImage = 'url("' + self.iconDir + '/color_picker_cross.gif")';
                p.padM.style.backgroundRepeat = 'no-repeat';
                p.sldM.style.backgroundImage = 'url("' + self.iconDir + '/color_picker_arrow.gif")';
                p.sldM.style.backgroundRepeat = 'no-repeat';
                p.pad.style.backgroundImage = 'url("' + self.iconDir + '/' + padImg + '")';
                p.pad.style.backgroundRepeat = 'no-repeat';
                p.pad.style.backgroundPosition = '0 0';
                redrawPad();
                redrawSld();
                colorDropper.picker.owner = self;
                target.parentNode.parentNode.appendChild(p.boxB);
            }

            function getPickerDims(o) {
                return [2 * o.pickerInset + 2 * o.pickerFace + colorDropper.images.pad[0] +
                2 * o.pickerInset + 2 * colorDropper.images.arrow[0] + colorDropper.images.sld[0],
                    2 * o.pickerInset + 2 * o.pickerFace + colorDropper.images.pad[1]];
            }

            function redrawPad() {
                let yComponent, x, y, i = 0, rgb, s, c, f;
                const seg = colorDropper.picker.sld.children;

                switch (modeID) {
                    case 0:
                        yComponent = 1;
                        break;
                    case 1:
                        yComponent = 2;
                        break;
                }
                x = Math.round(self.hsv[0] / 6 * (colorDropper.images.pad[0] - 1));
                y = Math.round((1 - self.hsv[yComponent]) * (colorDropper.images.pad[1] - 1));
                colorDropper.picker.padM.style.backgroundPosition =
                    self.pickerFace + self.pickerInset + x - Math.floor(colorDropper.images.cross[0] / 2) + 'px ' +
                    (self.pickerFace + self.pickerInset + y - Math.floor(colorDropper.images.cross[1] / 2)) + 'px';

                switch (modeID) {
                    case 0:
                        rgb = self.HSV_RGB(self.hsv[0], self.hsv[1], 1);
                        if (window.File && window.FileReader) {
                            colorDropper.picker.sld.style.background = 'linear-gradient(rgb(' +
                                rgb[0] * (1 - i / seg.length) * 100 + '%,' +
                                rgb[1] * (1 - i / seg.length) * 100 + '%,' +
                                rgb[2] * (1 - i / seg.length) * 100 + '%), black)';
                        } else {
                            for (i = 0; i < seg.length; i += 1) {
                                seg[i].style.backgroundColor = 'rgb(' +
                                    rgb[0] * (1 - i / seg.length) * 100 + '%,' +
                                    rgb[1] * (1 - i / seg.length) * 100 + '%,' +
                                    rgb[2] * (1 - i / seg.length) * 100 + '%)';
                            }
                        }
                        break;
                    case 1:
                        c = [self.hsv[2], 0, 0];
                        i = Math.floor(self.hsv[0]);
                        f = i % 2 ? self.hsv[0] - i : 1 - (self.hsv[0] - i);
                        switch (i) {
                            case 6:
                            case 0:
                                rgb = [0, 1, 2];
                                break;
                            case 1:
                                rgb = [1, 0, 2];
                                break;
                            case 2:
                                rgb = [2, 0, 1];
                                break;
                            case 3:
                                rgb = [2, 1, 0];
                                break;
                            case 4:
                                rgb = [1, 2, 0];
                                break;
                            case 5:
                                rgb = [0, 2, 1];
                                break;
                        }

                        for (i = 0; i < seg.length; i += 1) {
                            s = 1 - 1 / (seg.length - 1) * i;
                            c[1] = c[0] * (1 - s * f);
                            c[2] = c[0] * (1 - s);
                            seg[i].style.backgroundColor = 'rgb(' +
                                c[rgb[0]] * 100 + '%,' +
                                c[rgb[1]] * 100 + '%,' +
                                c[rgb[2]] * 100 + '%)';
                        }
                        break;
                }
            }

            function getOffsetParent(el) {
                let parent = el.offsetParent, top = 0, left = 0;
                while (parent) {
                    top += parent.offsetTop;
                    left += parent.offsetLeft;
                    parent = parent.offsetParent;
                }
                return {Left: left, Top: top};
            }

            function redrawSld() {
                let yComponent, y;
                switch (modeID) {
                    case 0:
                        yComponent = 2;
                        break;
                    case 1:
                        yComponent = 1;
                        break;
                }
                y = Math.round((1 - self.hsv[yComponent]) * (colorDropper.images.sld[1] - 1));
                colorDropper.picker.sldM.style.backgroundPosition =
                    '0 ' + (self.pickerFace + self.pickerInset + y - Math.floor(colorDropper.images.arrow[1] / 2)) + 'px';
            }

            function isPickerOwner() {
                return colorDropper.picker && colorDropper.picker.owner === self;
            }

            function blurTarget() {
                if (valueElement === target) {
                    self.importColor();
                }
            }

            function blurValue() {
                if (valueElement !== target) {
                    self.importColor();
                }
            }

            function setPad(e) {
                const pos = colorDropper.getRelMousePos(e);
                let x = pos.x - self.pickerFace - self.pickerInset,
                    y = pos.y - self.pickerFace - self.pickerInset;
                switch (modeID) {
                    case 0:
                        self.fromHSV(x * (6 / (colorDropper.images.pad[0] - 1)), 1 - y / (colorDropper.images.pad[1] - 1), null, leaveSld);
                        break;
                    case 1:
                        self.fromHSV(x * (6 / (colorDropper.images.pad[0] - 1)), null, 1 - y / (colorDropper.images.pad[1] - 1), leaveSld);
                        break;
                }
            }

            function setSld(e) {
                const pos = colorDropper.getRelMousePos(e);
                let y = pos.y - self.pickerFace - self.pickerInset;
                switch (modeID) {
                    case 0:
                        self.fromHSV(null, null, 1 - y / (colorDropper.images.sld[1] - 1), leavePad);
                        break;
                    case 1:
                        self.fromHSV(null, 1 - y / (colorDropper.images.sld[1] - 1), null, leavePad);
                        break;
                }
            }

            function dispatchImmediateChange() {
                if (self.onImmediateChange) {
                    let callback;
                    if (typeof self.onImmediateChange === 'string') {
                        callback = new Function(self.onImmediateChange);
                    } else {
                        callback = self.onImmediateChange;
                    }
                    callback.call(self);
                }
            }

            if (valueElement) {
                colorDropper.addEvent(valueElement, 'keyup', updateFieldEventHandler);
                colorDropper.addEvent(valueElement, 'input', updateFieldEventHandler);
                colorDropper.addEvent(valueElement, 'blur', blurValue);
                valueElement.setAttribute('autocomplete', 'off');
            }

            this.importColor();
        }
    };
    GB.colorDropper = colorDropper.color;
})();

const TMPL = {
    container: `
        <div class="cheditor-app">
          <div id="toolbar" class="cheditor-toolbar"></div>
          <div id="editarea-wrapper" class="cheditor-editarea-wrapper">
            <iframe class="cheditor-editarea"></iframe>
            <textarea name="text-content" class="cheditor-textarea" spellcheck="false"></textarea>
            <div id="insert-paragraph-line" class="cheditor-insert-paragraph-line d-none"></div>
          </div>
          <div id="statusbar" class="cheditor-statusbar">
            <ul class="cheditor-html-selector"></ul>
            <div class="cheditor-textarea-resizer"></div>
          </div>
          <div id="cheditor-modal" class="cheditor-modal-window">
            <div class="cheditor-modal-title-bar">
              <div class="title"></div>
              <div class="close">&nbsp;</div>
            </div>
            <div id="cheditor-modal-frame" class="cheditor-modal-frame"></div>
          </div>
          <dialog id="cheditor-dialog" class="cheditor-dialog">
            <div class="cheditor-dialog-content">
              <form method="dialog">
                <div class="cheditor-dialog-header">
                  <div class="cheditor-dialog-title"></div>
                </div>
                <div class="cheditor-dialog-body"></div>
                <div class="cheditor-dialog-footer">
                  <button name="cancel" class="cheditor-btn btn-light">취소</button>
                  <button name="submit" class="cheditor-btn btn-primary">확인</button>
                </div>
              </form>
            </div>
          </dialog>
          <div id="image-resizer" class="resizer">
            <div class="resizer-handle resizer-handle-top-left"></div>
            <div class="resizer-handle resizer-handle-top-right"></div>
            <div class="resizer-handle resizer-handle-bottom-left"></div>
            <div class="resizer-handle resizer-handle-bottom-right"></div>
          </div>
          <div id="image-popover" class="image-control-popover" popover="manual">
            <span class="btn-popover btn-align-left" data-cmd="left"></span>
            <span class="btn-popover btn-align-center" data-cmd="center"></span>
            <span class="btn-popover btn-align-right" data-cmd="right"></span>
            <span class="btn-split"></span>
            <span class="btn-popover btn-figcaption" data-cmd="figcaption"></span>
            <span class="btn-popover btn-reset" data-cmd="reset"></span>
            <span class="btn-popover btn-insert-paragraph-top" data-cmd="insert-paragraph-top"></span>
            <span class="btn-popover btn-insert-paragraph-bottom" data-cmd="insert-paragraph-bottom"></span>
            <span class="btn-split"></span>
            <span class="btn-popover btn-trash" data-cmd="remove"></span>
          </div>
        </div>`,
    toolbar: [{
            name: "Source",
            button: [{
                name: "Source",
                label: "소스 편집",
                class: "cheditor-toolbar-btn source w-40",
                icon: 726,
                action: "popupWindowOpen",
                value: "CodeEdit"
            }]
        }, {
            name: "Print",
            button: [{
                name: "Print",
                label: "인쇄",
                class: "cheditor-toolbar-btn",
                icon: 0,
                action: "doCmd",
                value: "Print"
            }, {
                name: "NewDocument",
                label: "새 문서",
                class: "cheditor-toolbar-btn",
                icon: 16,
                action: "doCmd",
                value: "NewDocument"
            }]
        }, {
            name: "Undo",
            button: [{
                name: "Undo",
                label: "실행 취소",
                class: "cheditor-toolbar-btn",
                icon: 32,
                action: "doCmd",
                value: "Undo"
            }, {
                name: "Redo",
                label: "다시 실행",
                class: "cheditor-toolbar-btn",
                icon: 48,
                action: "doCmd",
                value: "Redo"
            }]
        }, {
            name: "Edit",
            button: [{
                name: "Copy",
                label: "복사하기",
                class: "cheditor-toolbar-btn",
                icon: 64,
                action: "doCmd",
                value: "Copy"
            }, {
                name: "Cut",
                label: "잘라내기",
                class: "cheditor-toolbar-btn",
                icon: 80,
                action: "doCmd",
                value: "Cut"
            }, {
                name: "SelectAll",
                label: "전체선택",
                class: "cheditor-toolbar-btn",
                icon: 128,
                action: "doCmd",
                value: "SelectAll"
            }]
        }, {
            name: "Color",
            button: [{
                name: "BackColor",
                label: "형광펜",
                class: "cheditor-toolbar-btn",
                icon: 240,
                default: "#fff",
                action: "showDropdown",
                value: "BackColor"
            }, {
                name: "ForeColor",
                label: "글자색",
                class: "cheditor-toolbar-btn",
                icon: 263,
                default: "#000",
                action: "showDropdown",
                value: "ForeColor"
            }]
        }, {
            name: "Format",
            button: [{
                name: "Bold",
                label: "진하게",
                class: "cheditor-toolbar-btn",
                icon: 144,
                action: "applyTextFormat",
                value: "Bold"
            }, {
                name: "Italic",
                label: "기울임",
                class: "cheditor-toolbar-btn",
                icon: 160,
                action: "applyTextFormat",
                value: "Italic"
            }, {
                name: "Underline",
                label: "밑줄",
                class: "cheditor-toolbar-btn",
                icon: 176,
                action: "applyTextFormat",
                value: "Underline"
            }, {
                name: "Strikethrough",
                label: "취소선",
                class: "cheditor-toolbar-btn",
                icon: 192,
                action: "applyTextFormat",
                value: "Strikethrough"
            }, {
                name: "Superscript",
                label: "위 첨자",
                class: "cheditor-toolbar-btn",
                icon: 208,
                action: "applyTextFormat",
                value: "Superscript"
            }, {
                name: "Subscript",
                label: "아래 첨자",
                class: "cheditor-toolbar-btn",
                icon: 224,
                action: "applyTextFormat",
                value: "Subscript"
            }]
        }, {
            name: "Alignment",
            button: [{
                name: "JustifyLeft",
                label: "왼쪽 정렬",
                class: "cheditor-toolbar-btn",
                icon: 286,
                action: "doCmd",
                value: "JustifyLeft"
            }, {
                name: "JustifyCenter",
                label: "가운데 정렬",
                class: "cheditor-toolbar-btn",
                icon: 302,
                action: "doCmd",
                value: "JustifyCenter"
            }, {
                name: "JustifyRight",
                label: "오른쪽 정렬",
                class: "cheditor-toolbar-btn",
                icon: 318,
                action: "doCmd",
                value: "JustifyRight"
            }, {
                name: "JustifyFull",
                label: "양쪽 정렬",
                class: "cheditor-toolbar-btn",
                icon: 334,
                action: "doCmd",
                value: "JustifyFull"
            }]
        }, {
            name: "List",
            button: [{
                name: "OrderedList",
                label: "문단 번호",
                class: "cheditor-toolbar-btn",
                icon: 350,
                action: "doCmd",
                value: "InsertOrderedList"
            }, {
                name: "OrderedListCombo",
                label: "문단 번호 확장",
                class: "cheditor-toolbar-btn-combo",
                node: "OrderedList",
                icon: -1,
                iconClass: "cheditor-toolbar-combo-ico",
                action: "showDropdown",
                value: "OrderedList"
            }, {
                name: "UnOrderedList",
                label: "글 머리표",
                class: "cheditor-toolbar-btn",
                icon: 366,
                action: "doCmd",
                value: "InsertUnOrderedList"
            }, {
                name: "UnOrderedListCombo",
                label: "글 머리표 확장",
                class: "cheditor-toolbar-btn-combo",
                node: "UnOrderedList",
                icon: -1,
                iconClass: "cheditor-toolbar-combo-ico",
                action: "showDropdown",
                value: "UnOrderedList"
            }]
        }, {
            name: "Outdent",
            button: [{
                name: "Outdent",
                label: "왼쪽 여백 줄이기",
                class: "cheditor-toolbar-btn",
                icon: 382,
                action: "doCmd",
                value: "Outdent"
            }, {
                name: "Indent",
                label: "왼쪽 여백 늘리기",
                class: "cheditor-toolbar-btn",
                icon: 398,
                action: "doCmd",
                value: "Indent"
            }]
        }, {
            name: "Split"
        }, {
            name: "FormatBlock",
            button: [{
                name: "FormatBlock",
                label: "문단 스타일",
                class: "cheditor-toolbar-dropdown",
                icon: -1,
                action: "showDropdown",
                item_text: "스타일",
                value: "FormatBlock"
            }]
        }, {
            name: "FontName",
            button: [{
                name: "FontName",
                label: "글꼴",
                class: "cheditor-toolbar-dropdown w-65",
                icon: -1,
                action: "showDropdown",
                item_text: "맑은 고딕",
                value: "FontName"
            }]
        }, {
            name: "FontSize",
            button: [{
                name: "FontSize",
                label: "글꼴 크기",
                class: "cheditor-toolbar-dropdown w-40",
                icon: -1,
                action: "showDropdown",
                item_text: "14",
                value: "FontSize"
            }]
        }, {
            name: "FontSizeUpDown",
            button: [{
            }, {
                name: "FontSizeUp",
                label: "글꼴 크기 증가",
                class: "cheditor-toolbar-btn",
                icon: 782,
                action: "applyFontSize",
                value: "FontSizeUp"
            }, {
                name: "FontSizeDown",
                label: "글꼴 크기 감소",
                class: "cheditor-toolbar-btn",
                icon: 798,
                action: "applyFontSize",
                value: "FontSizeDown"
            }]
        }, {
            name: "LineHeight",
            button: [{
                name: "LineHeight",
                label: "줄 간격",
                class: "cheditor-toolbar-dropdown w-30",
                icon: 766,
                action: "showDropdown",
                item_text: "",
                value: "LineHeight"
            }]
        }, {
            name: "QuoteBlock",
            button: [{
                name: "QuoteBlock",
                label: "글 인용",
                class: "cheditor-toolbar-dropdown w-30",
                icon: 446,
                action: "showDropdown",
                item_text: "",
                value: "QuoteBlock"
            }]
        }, {
            name: "RemoveFormat",
            button: [{
                name: "RemoveFormat",
                label: "텍스트 효과 지우기",
                class: "cheditor-toolbar-btn",
                icon: 414,
                action: "doCmd",
                value: "RemoveFormat"
            }, {
                name: "ClearTag",
                label: "HTML 태그 제거",
                class: "cheditor-toolbar-btn",
                icon: 430,
                action: "doCmd",
                value: "ClearTag"
            }]
        }, {
            name: "InsertSymbol",
            button: [{
                name: "Symbol",
                label: "특수 문자",
                class: "cheditor-toolbar-btn",
                icon: 462,
                action: "popupWindowOpen",
                value: "Symbol"
            }, {
                name: "Emoji",
                label: "이모티콘",
                class: "cheditor-toolbar-btn",
                icon: 494,
                action: "popupWindowOpen",
                value: "Emoji"
            }]
        }, {
            name: "HR",
            button: [{
                name: "HR",
                label: "가로줄",
                class: "cheditor-toolbar-btn",
                icon: 478,
                action: "doCmd",
                value: "InsertHorizontalRule"
            }]
        }, {
            name: "Table",
            button: [{
                name: "Table",
                label: "표 만들기",
                class: "cheditor-toolbar-dropdown w-30",
                icon: 510,
                action: "showDropdown",
                item_text: "",
                value: "Table"
            }]
        }, {
            name: "Link",
            button: [{
                name: "Link",
                label: "하이퍼링크",
                class: "cheditor-toolbar-btn",
                icon: 558,
                action: "popupWindowOpen",
                value: "Link"
            }, {
                name: "UnLink",
                label: "하이퍼링크 없애기",
                class: "cheditor-toolbar-btn",
                icon: 574,
                action: "doCmd",
                value: "UnLink"
            }]
        }, {
            name: "Media",
            button: [{
                name: "Image",
                label: "이미지 넣기",
                class: "cheditor-toolbar-btn",
                icon: 590,
                action: "popupWindowOpen",
                value: "ImageUpload"
            }, {
                name: "Video",
                label: "동영상 넣기",
                class: "cheditor-toolbar-btn",
                icon: 662,
                action: "popupWindowOpen",
                value: "Video"
            }]
        }, {
            name: "PageBreak",
            button: [{
                name: "PageBreak",
                label: "인쇄 쪽 나눔",
                class: "cheditor-toolbar-btn",
                icon: 710,
                action: "doCmd",
                value: "PageBreak"
            }]
        }]
};
