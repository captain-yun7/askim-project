/*
 * Toast UI Editor 공통 wrapper
 * SmartEditor의 attachSmartEditor(id, folder) / getSmartEditor(id) 시그니처와 1:1 호환
 *
 * 사용:
 *   attachToastEditor("info", "goods");
 *   attachToastEditor("content", { uploadUrl: "/admin/board/editor_image_upload", height: "600px" });
 *
 *   var html = getToastEditor("info");  // submit 직전 추출용 (form submit 핸들러가 자동 처리하므로 보통 불필요)
 */
(function(global){
	var TUI_BASE = 'https://uicdn.toast.com/editor/latest';
	var COLOR_PICKER_BASE = 'https://uicdn.toast.com/tui-color-picker/latest';
	var COLOR_PLUGIN_BASE = 'https://uicdn.toast.com/editor-plugin-color-syntax/latest';
	// folder(컨텍스트) → 이미지 업로드 endpoint 매핑. 어드민은 인증 자동.
	var UPLOAD_ENDPOINTS = {
		'goods':  '/admin/goods/editor_image_upload',
		'board':  '/admin/goods/editor_image_upload',
		'popup':  '/admin/goods/editor_image_upload',
		'terms':  '/admin/goods/editor_image_upload',
		'front':  '/board/editor_image_upload'  // 프론트 게시판 사용자 글쓰기 — endpoint 추가 시
	};
	var assetsLoaded = false;
	var assetsLoading = null;

	function injectCss(href) {
		var link = document.createElement('link');
		link.rel = 'stylesheet';
		link.href = href;
		document.head.appendChild(link);
	}

	function injectScript(src) {
		return new Promise(function(resolve, reject){
			var s = document.createElement('script');
			s.src = src;
			s.onload = resolve;
			s.onerror = reject;
			document.head.appendChild(s);
		});
	}

	function loadAssets() {
		if (assetsLoaded) return Promise.resolve();
		if (assetsLoading) return assetsLoading;
		// CSS는 순서 무관, 한꺼번에 inject
		injectCss(TUI_BASE + '/toastui-editor.min.css');
		injectCss(COLOR_PICKER_BASE + '/tui-color-picker.min.css');
		injectCss(COLOR_PLUGIN_BASE + '/toastui-editor-plugin-color-syntax.min.css');
		// JS는 의존성 순서: color-picker → editor + i18n → color-syntax plugin
		assetsLoading = injectScript(COLOR_PICKER_BASE + '/tui-color-picker.min.js')
			.then(function(){ return injectScript(TUI_BASE + '/toastui-editor-all.min.js'); })
			.then(function(){ return injectScript(TUI_BASE + '/i18n/ko-kr.js'); })
			.then(function(){ return injectScript(COLOR_PLUGIN_BASE + '/toastui-editor-plugin-color-syntax.min.js'); })
			.then(function(){ assetsLoaded = true; });
		return assetsLoading;
	}

	function attachToastEditor(id, optsOrFolder) {
		var opts = (typeof optsOrFolder === 'string') ? { folder: optsOrFolder } : (optsOrFolder || {});
		var uploadUrl = opts.uploadUrl || UPLOAD_ENDPOINTS[opts.folder] || UPLOAD_ENDPOINTS.goods;
		var height = opts.height || '550px';

		var ta = document.getElementById(id);
		if (!ta) { console.warn('[toast-editor] textarea not found:', id); return; }

		// textarea 숨김 + tui wrap div 뒤에 삽입
		ta.style.display = 'none';
		var wrapId = id + '_tui';
		var wrap = document.getElementById(wrapId);
		if (!wrap) {
			wrap = document.createElement('div');
			wrap.id = wrapId;
			wrap.className = 'tui-editor-wrap';
			ta.parentNode.insertBefore(wrap, ta.nextSibling);
		}

		loadAssets().then(function(){
			var colorSyntax = (toastui.Editor && toastui.Editor.plugin && toastui.Editor.plugin.colorSyntax)
				|| (window.toastui && window.toastui.Editor && window.toastui.Editor.plugin && window.toastui.Editor.plugin.colorSyntax)
				|| window.toastuiEditorPluginColorSyntax;
			var plugins = colorSyntax ? [colorSyntax] : [];
			var editor = new toastui.Editor({
				el: wrap,
				height: height,
				initialEditType: 'wysiwyg',
				previewStyle: 'tab',
				language: 'ko-KR',
				initialValue: ta.value || '',
				plugins: plugins,
				hooks: {
					addImageBlobHook: function(blob, callback){
						var fd = new FormData();
						fd.append('image', blob, blob.name || ('image_' + Date.now() + '.png'));
						fetch(uploadUrl, { method:'POST', body:fd, credentials:'same-origin' })
							.then(function(r){ return r.json(); })
							.then(function(d){
								if (d.url) { callback(d.url, ''); }
								else { alert('이미지 업로드 실패: ' + (d.error || 'unknown')); }
							})
							.catch(function(e){ alert('이미지 업로드 오류: ' + e.message); });
					}
				}
			});

			// form submit 직전 본문 → textarea 반영
			var form = ta.form || ta.closest('form');
			if (form && !form.__tuiBound) {
				form.__tuiBound = true;
				form.addEventListener('submit', function(){
					Object.keys(window).forEach(function(k){
						if (k.indexOf('__tuiEditor_') === 0) {
							var ed = window[k];
							var taId = k.replace('__tuiEditor_', '');
							var t = document.getElementById(taId);
							if (ed && t) t.value = ed.getHTML();
						}
					});
				});
			}

			window['__tuiEditor_' + id] = editor;
		});
	}

	function getToastEditor(id) {
		var ed = window['__tuiEditor_' + id];
		if (!ed) return '';
		var html = ed.getHTML();
		if (html === '<p><br></p>') html = '';
		return html;
	}

	global.attachToastEditor = attachToastEditor;
	global.getToastEditor = getToastEditor;
})(window);
