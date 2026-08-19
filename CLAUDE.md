# askim-project

## 개요
에스킴 그룹 두 옥외광고 회사 사이트(askim.kr, bloomingterra.com)의 **SEO 개선 + 디자인 1:1 정렬 + 어드민 본문 에디터 현대화** 작업 모노레포.

## 기술스택
- **askim**: PHP + twcenter CMS (오래된 한국 CMS, 코어 수정 최소화)
- **bloomingterra**: PHP + CodeIgniter 3 + nForce-like Template 엔진 (`_compile/` 캐시 자동 생성)
- **본문 에디터**: Toast UI Editor 단일 (SmartEditor 2 폐기, `lib/js/toast-editor.js` helper 사용)
- **테스트**: Playwright + pytest (`tests/`)
- **DB**: MariaDB/MySQL (서버 내부 또는 IP 화이트리스트 접근)

## 디렉토리 구조
- `askim/` — 에스킴 소스 (twcenter)
- `bloomingterra/` — 블루밍테라 소스 (CI3)
  - `app/controllers/` `app/views/admin/` `data/skin/respon_default_en/` `_compile/` `lib/`
- `_docs/` — **OneDrive 심볼릭** (절대로 git에 추적 안 됨)
  - `overview.md` `specs/` `runbook/env.md` `worklogs/YYYY-MM-DD.md` `prompts/` `db-backup/`
- `tests/` `scripts/`

## 핵심 패턴 / 자주 쓰는 명령어

### FTP 배포 (블루밍)
```bash
FTP="ftp://gcsd33_bloomingterra:PW@bloomingterra.com"
curl -sS -T <local> "$FTP/<remote>" -w "[%{http_code}]\n" -o /dev/null  # 업로드 (226 = OK)
curl -sS -Q "DELE <path>" "$FTP/" -w "[%{http_code}]\n" -o /dev/null    # 삭제
# leading slash 없이 상대 경로 사용 (FTP root = 웹 루트)
```

### 라이브 검증
```bash
curl -sI --max-time 10 "https://bloomingterra.com/<path>"   # 헤더만
curl -s --max-time 15 "https://..." -o /tmp/x.html          # 본문 fetch 후 grep
```

### 어드민 cookie 로그인 (블루밍)
```bash
# POST /admin/login 후 HttpOnly cookie `designart_site` 발급 (#HttpOnly_ prefix로 jar 저장)
curl -s -c /tmp/jar.txt -X POST -d "userid=admin&password=PW" "https://bloomingterra.com/admin/login"
```

### askim 라이브 fetch
```bash
# UA 없으면 403. UA 명시 필수.
curl -sL -A "Mozilla/5.0 ... Chrome/120" "https://www.askim.kr/portfolio/portfolio.php?ptype=view&idx=N"
```

## 주의사항

- **블루밍 언어 설정 함정** — `multilingual=0`이라 사이트는 **항상 kor 언어로 렌더** (스킨만 `respon_default_en`). `cfg_menu.php` 등 언어별 설정은 **kor 블록**이 실제 반영 대상 (eng 블록은 무시됨)
- **비밀번호는 `_docs/runbook/env.md`만**. 코드/CLAUDE.md/worklog에 하드코딩 절대 금지
- **에스킴 FTP IP 화이트리스트, 1주일 만료** — 만료 시 쓰리웨이(`cms.web2002.co.kr`)에 IP 재등록 + PW 새로 받음
- **블루밍 PHP-FPM OPCache** — controller 변경 시 워커별로 즉시 갱신 안 됨. 즉시 반영 필요한 로직은 **view 단 `<?php ?>` block으로 우회** (view는 자동 재컴파일)
- **블루밍 `_compile/` 캐시** — 원본 변경 후 mtime 갱신만으론 재컴파일 보장 안 됨. FTP DELE로 강제 (`curl -Q "DELE _compile/..."`)
- **CodeIgniter Template parser 한계** — `{=preg_replace(...)}` 같은 복잡한 PHP 함수 호출 처리 못함. `<?php ?>` block 직접 inject가 정공법
- **운영자 본문 에디터 빈 값** — Toast UI/SmartEditor 모두 빈 본문이 `<p><br></p>`로 저장됨. `trim(strip_tags($v))`로 정규화 후 판정
- **Toast UI form submit** — jQuery `.submit()` / native `form.submit()`은 submit 이벤트 미발생. `lib/js/toast-editor.js`가 `HTMLFormElement.prototype.submit()` patch + `flushToastEditors()` 노출로 자동 sync
- **운영 트래픽 초과 → 호스팅 차단** 이력 있음 (블루밍 fmcity). 사이트 ERR_CONNECTION_RESET 시 호스팅 측 풀어달라 요청
- **운영자 어드민 사이드바 메뉴 = 4개만** (board/goods/member/popup). admin/auth/* 페이지들은 사이드바 없음 (직접 진입 X)

## 어드민 입력 → 프론트 출력 매핑 (블루밍 goods)

- **goods_view URL**: `/goods/goods_view?no=N&cate=001` 또는 `/service/<slug>`
- **유튜브 영상**: 어드민 "유튜브 링크" 필드(extraFieldData에서 `name=='유튜브 링크'`) 한 줄 입력 → view에서 정규식으로 video ID 추출 → light section 최상단 iframe embed 자동 노출
- **본문 메타** (`Region:`, `Period:`, `Client:`, `Location:`, `Media:`): 운영자가 본문 평문에 적으면 controller가 정규식 추출 → 좌측 info dl로 자동 정렬 (`auto_meta`)
- **본문 인라인 이미지**: view JS가 `<img>`를 `<a class="viewImg">`로 자동 wrap → askim 호버 줌/돋보기 효과 작동
- **빈 글 처리**: 본문(`info`)도 영상(`extraFieldData[유튜브 링크]`)도 모두 없으면 light section 자체 미렌더 → 빈 박스 안 그려짐 (dark만 보임)

## 작업 흐름
1. 코드 변경 → 로컬 syntax 점검 (`node -e "new Function(...)"` for JS)
2. FTP 배포 → 라이브 응답 즉시 검증 (`curl -s | grep` 정적 검증)
3. 운영자 실 검증은 사용자 액션 (정적 응답 OK ≠ 운영자 사용 OK)
4. 작업 완료 → `/worklog`로 `_docs/worklogs/YYYY-MM-DD.md` 작성
5. 커밋 + 푸시 (사용자가 명시 요청 시)

## 큰 디자인 작업 = 3에이전트 분리 패턴
askim과의 시각 1:1 정렬 같은 대규모 작업은 다음 사이클로 진행:
1. **분석 에이전트** (Explore) — 두 라이브 fetch + 차이 항목별 정리 (Critical/Major/Minor + 점수)
2. **수정 에이전트** (general-purpose) — 분석 결과 그대로 패치 + FTP 배포 + 1차 검증
3. **검증 에이전트** (Explore 독립) — 라이브 응답으로 재측정 (점수 재산정 + 새 deviation 발견)

종합 점수(예: 72→91→95+)로 정량 추적. 단일 에이전트가 분석+수정+검증 모두 하면 자기 작업 합리화로 점수 부풀림.

## 참고 링크
- 에스킴 어드민: `https://www.askim.kr/twcenter/`
- 블루밍테라 어드민: `https://bloomingterra.com/admin`
- 쓰리웨이(에스킴 호스팅): `https://cms.web2002.co.kr/member/login.php`
- 참고 디자인 — askim portfolio(idx=34/55), ohbrown 웹진
- 상세 가능여부/난이도: `_docs/specs/작업계획-가능여부및난이도.md`
- 전체 계획: `_docs/specs/프로젝트-계획.md`
