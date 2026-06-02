/*
 * 리치 에디터 공통 wrapper — CKEditor 5 (super-build, 네이버 블로그 톤의 풍부한 WYSIWYG)
 *
 * 2026-05-26: Toast UI Editor → CKEditor 5 교체.
 *   운영자 친화(폰트 크기/색/서체/정렬/이미지 리사이즈/표 등 직관적 toolbar).
 *
 * 호출부 호환: 기존 attachToastEditor / getToastEditor / flushToastEditors 시그니처를 그대로 유지(alias).
 *   → goods_reg / board_write / popup_reg 등 호출부 코드 변경 0.
 *
 * 사용:
 *   attachRichEditor("info", "goods");           // = attachToastEditor("info", "goods")
 *   var html = getRichEditor("info");            // submit 직전 자동 sync 되므로 보통 불필요
 */
(function(global){
	var CKE_BASE = 'https://cdn.ckeditor.com/ckeditor5/40.0.0/super-build';
	// folder(컨텍스트) → 이미지 업로드 endpoint. 어드민은 ADMIN_Controller 인증 자동.
	var UPLOAD_ENDPOINTS = {
		'goods':  '/admin/goods/editor_image_upload',
		'board':  '/admin/goods/editor_image_upload',
		'popup':  '/admin/goods/editor_image_upload',
		'terms':  '/admin/goods/editor_image_upload',
		'front':  '/board/editor_image_upload'
	};
	// super-build에 번들된 협업/유료 플러그인 — 라이선스 키 없으면 에러 → 제거 필수
	var REMOVE_PLUGINS = [
		'CKBox', 'CKFinder', 'EasyImage', 'CloudServices',
		'RealTimeCollaborativeComments', 'RealTimeCollaborativeTrackChanges',
		'RealTimeCollaborativeRevisionHistory', 'PresenceList', 'Comments',
		'TrackChanges', 'TrackChangesData', 'RevisionHistory', 'Pagination',
		'WProofreader', 'MathType', 'SlashCommand', 'Template', 'DocumentOutline',
		'FormatPainter', 'TableOfContents', 'PasteFromOfficeEnhanced', 'CaseChange',
		'AIAssistant', 'OpenAITextAdapter', 'Markdown', 'Title', 'MultiLevelList',
		'Mention', 'ExportPdf', 'ExportWord'
	];
	var assetsLoaded = false;
	var assetsLoading = null;

	function injectScript(src){
		return new Promise(function(resolve, reject){
			var s = document.createElement('script');
			s.src = src;
			s.onload = resolve;
			s.onerror = reject;
			document.head.appendChild(s);
		});
	}

	function injectEditorStyle(){
		if (document.getElementById('rich-editor-style')) return;
		var st = document.createElement('style');
		st.id = 'rich-editor-style';
		// 본문 편집 영역 높이 확보 (기본 한 줄짜리 → 넉넉하게) + 가독성
		st.textContent =
			'.ck-editor__editable_inline{min-height:520px !important;}' +
			'.ck.ck-editor{max-width:100%;}' +
			'.ck-editor__editable_inline{padding:16px 24px !important;}' +
			'.ck-content{font-size:15px; line-height:1.7;}' +
			/* 임시저장 바 */
			'.rich-draftbar{display:flex; align-items:center; gap:8px; padding:7px 10px; background:#f7f8fa; border:1px solid #e1e4e8; border-bottom:0; border-radius:4px 4px 0 0; font-size:13px;}' +
			'.rich-draft-btn{padding:5px 12px; font-size:12px; line-height:1; border:1px solid #c4c9d0; background:#fff; border-radius:3px; cursor:pointer; color:#333;}' +
			'.rich-draft-btn:hover{background:#eef0f3;}' +
			'.rich-draft-save{border-color:#0A2C51; color:#0A2C51; font-weight:600;}' +
			'.rich-draft-status{margin-left:auto; color:#8a9099; font-size:12px;}' +
			/* 토스트 */
			'.rich-toast{position:fixed; left:50%; bottom:40px; transform:translateX(-50%) translateY(20px); background:#222; color:#fff; padding:12px 22px; border-radius:6px; font-size:14px; z-index:99999; opacity:0; transition:all .3s ease; pointer-events:none; box-shadow:0 4px 16px rgba(0,0,0,.25);}' +
			'.rich-toast.show{opacity:1; transform:translateX(-50%) translateY(0);}';
		document.head.appendChild(st);
	}

	function loadAssets(){
		if (assetsLoaded) return Promise.resolve();
		if (assetsLoading) return assetsLoading;
		injectEditorStyle();
		// super-build는 CSS가 JS 번들에 포함 → 별도 CSS link 불필요
		assetsLoading = injectScript(CKE_BASE + '/ckeditor.js')
			.then(function(){ return injectScript(CKE_BASE + '/translations/ko.js'); })
			.then(function(){ assetsLoaded = true; });
		return assetsLoading;
	}

	function editorConfig(uploadUrl){
		return {
			removePlugins: REMOVE_PLUGINS,
			language: 'ko',
			placeholder: '내용을 입력하세요',
			toolbar: {
				items: [
					'undo', 'redo', '|',
					'heading', '|',
					'fontFamily', 'fontSize', 'fontColor', 'fontBackgroundColor', '|',
					'bold', 'italic', 'underline', 'strikethrough', '|',
					'alignment', '|',
					'bulletedList', 'numberedList', 'outdent', 'indent', '|',
					'link', 'blockQuote', 'insertTable', 'uploadImage', 'mediaEmbed', 'horizontalLine', '|',
					'removeFormat'
				],
				shouldNotGroupWhenFull: true
			},
			fontSize: {
				options: [10, 12, 14, 'default', 18, 20, 24, 28, 32, 40, 48],
				supportAllValues: true
			},
			fontFamily: {
				supportAllValues: true
			},
			heading: {
				options: [
					{ model: 'paragraph', title: '본문', class: 'ck-heading_paragraph' },
					{ model: 'heading1', view: 'h1', title: '제목 1', class: 'ck-heading_heading1' },
					{ model: 'heading2', view: 'h2', title: '제목 2', class: 'ck-heading_heading2' },
					{ model: 'heading3', view: 'h3', title: '제목 3', class: 'ck-heading_heading3' }
				]
			},
			image: {
				toolbar: [
					'imageStyle:inline', 'imageStyle:block', 'imageStyle:side', '|',
					'imageStyle:alignLeft', 'imageStyle:alignCenter', 'imageStyle:alignRight', '|',
					'toggleImageCaption', 'imageTextAlternative', '|',
					'resizeImage'
				],
				resizeOptions: [
					{ name: 'resizeImage:original', value: null, label: '원본' },
					{ name: 'resizeImage:50', value: '50', label: '50%' },
					{ name: 'resizeImage:75', value: '75', label: '75%' }
				]
			},
			table: {
				contentToolbar: ['tableColumn', 'tableRow', 'mergeTableCells', 'tableCellProperties', 'tableProperties']
			},
			simpleUpload: {
				uploadUrl: uploadUrl,
				withCredentials: true
			},
			link: {
				addTargetToExternalLinks: true
			}
		};
	}

	// ===== 임시저장(localStorage) =====
	function draftKey(id){
		return 'rich_draft:' + location.pathname + location.search + ':' + id;
	}
	function hhmm(){
		var d = new Date();
		return ('0'+d.getHours()).slice(-2) + ':' + ('0'+d.getMinutes()).slice(-2);
	}
	function setDraftStatus(id, text){
		var el = document.getElementById('draftstatus_' + id);
		if (el) el.textContent = text;
	}
	function richToast(message){
		var t = document.createElement('div');
		t.className = 'rich-toast';
		t.textContent = message;
		document.body.appendChild(t);
		setTimeout(function(){ t.classList.add('show'); }, 10);
		setTimeout(function(){ t.classList.remove('show'); setTimeout(function(){ t.remove(); }, 300); }, 1800);
	}
	function injectDraftBar(ta, id, editor){
		if (document.getElementById('draftbar_' + id)) return;
		var bar = document.createElement('div');
		bar.id = 'draftbar_' + id;
		bar.className = 'rich-draftbar';
		bar.innerHTML =
			'<button type="button" class="rich-draft-btn rich-draft-save">임시저장</button>' +
			'<button type="button" class="rich-draft-btn rich-draft-restore">불러오기</button>' +
			'<span class="rich-draft-status" id="draftstatus_' + id + '"></span>';
		var cke = ta.nextElementSibling; // CKEditor가 생성한 .ck-editor
		var anchor = (cke && cke.classList && cke.classList.contains('ck')) ? cke : ta;
		anchor.parentNode.insertBefore(bar, anchor);
		bar.querySelector('.rich-draft-save').addEventListener('click', function(){
			try { localStorage.setItem(draftKey(id), editor.getData()); } catch(e){}
			setDraftStatus(id, '임시저장 완료 · ' + hhmm());
			richToast('임시저장되었습니다');
		});
		bar.querySelector('.rich-draft-restore').addEventListener('click', function(){
			var d = localStorage.getItem(draftKey(id));
			if (!d) { richToast('임시저장된 내용이 없습니다'); return; }
			if (confirm('임시저장된 내용을 불러올까요? 현재 작성 중인 내용은 대체됩니다.')) {
				editor.setData(d);
				richToast('임시저장 내용을 불러왔습니다');
			}
		});
	}
	function restoreDraftPrompt(editor, id){
		var d = localStorage.getItem(draftKey(id));
		if (!d || !d.replace(/<[^>]*>|&nbsp;|\s/g, '')) return;  // 빈 draft skip
		var cur = editor.getData();
		var curEmpty = !cur.replace(/<[^>]*>|&nbsp;|\s/g, '');
		// 신규 작성(현재 본문 비어있음) + draft 존재 → 복원 제안
		if (curEmpty && d !== cur) {
			if (confirm('이전에 임시저장된 내용이 있습니다. 불러오시겠습니까?')) {
				editor.setData(d);
			}
		}
	}
	function clearAllDrafts(){
		Object.keys(global).forEach(function(k){
			if (k.indexOf('__richEditor_') !== 0) return;
			var id = k.replace('__richEditor_', '');
			try { localStorage.removeItem(draftKey(id)); } catch(e){}
		});
	}

	function attachRichEditor(id, optsOrFolder){
		var opts = (typeof optsOrFolder === 'string') ? { folder: optsOrFolder } : (optsOrFolder || {});
		var uploadUrl = opts.uploadUrl || UPLOAD_ENDPOINTS[opts.folder] || UPLOAD_ENDPOINTS.goods;

		var ta = document.getElementById(id);
		if (!ta) { console.warn('[rich-editor] textarea not found:', id); return; }

		loadAssets().then(function(){
			if (!global.CKEDITOR || !global.CKEDITOR.ClassicEditor) {
				console.error('[rich-editor] CKEDITOR super-build not available');
				return;
			}
			return global.CKEDITOR.ClassicEditor.create(ta, editorConfig(uploadUrl)).then(function(editor){
				global['__richEditor_' + id] = editor;
				var draftTimer;
				// 매 변경마다 textarea 동기화 + 디바운스 localStorage 자동 임시저장
				editor.model.document.on('change:data', function(){
					ta.value = editor.getData();
					clearTimeout(draftTimer);
					draftTimer = setTimeout(function(){
						try { localStorage.setItem(draftKey(id), editor.getData()); } catch(e){}
						setDraftStatus(id, '자동 임시저장됨 · ' + hhmm());
					}, 1500);
				});
				// 초기값 1회 반영
				ta.value = editor.getData();
				// 임시저장 바 + 신규 작성 시 복원 제안
				injectDraftBar(ta, id, editor);
				restoreDraftPrompt(editor, id);
			});
		}).catch(function(e){
			console.error('[rich-editor] init failed:', e && e.message);
		});
	}

	function getRichEditor(id){
		var ta = document.getElementById(id);
		var ed = global['__richEditor_' + id];
		if (!ed) { return ta ? (ta.value || '') : ''; }
		var html = ed.getData();
		if (ta) ta.value = html;
		return html;
	}

	// submit 직전 모든 인스턴스 강제 동기화
	function flushRichEditors(){
		Object.keys(global).forEach(function(k){
			if (k.indexOf('__richEditor_') !== 0) return;
			var ed = global[k];
			var id = k.replace('__richEditor_', '');
			var ta = document.getElementById(id);
			if (ed && ta) ta.value = ed.getData();
		});
	}

	// HTMLFormElement.prototype.submit() patch — native submit()은 submit 이벤트 미발생
	if (typeof HTMLFormElement !== 'undefined' && HTMLFormElement.prototype && !HTMLFormElement.prototype.__richSubmitPatched) {
		var origSubmit = HTMLFormElement.prototype.submit;
		HTMLFormElement.prototype.submit = function(){
			try { flushRichEditors(); } catch(e) { /* silent */ }
			try { clearAllDrafts(); } catch(e) { /* silent */ }  // 정상 제출 시 임시저장 비움
			return origSubmit.apply(this, arguments);
		};
		HTMLFormElement.prototype.__richSubmitPatched = true;
	}

	// 신규 API
	global.attachRichEditor = attachRichEditor;
	global.getRichEditor = getRichEditor;
	global.flushRichEditors = flushRichEditors;
	// 기존 호출부 호환 alias (Toast UI 시그니처 그대로 — 호출부 코드 변경 0)
	global.attachToastEditor = attachRichEditor;
	global.getToastEditor = getRichEditor;
	global.flushToastEditors = flushRichEditors;
})(window);
