<?php
/**
 * System messages translation for CodeIgniter(tm)
 *
 * @author CodeIgniter community
 * @author HyeongJoo Kwon
 * @copyright Copyright (c) 2014 - 2017, British Columbia Institute of Technology (http://bcit.ca/)
 * @license http://opensource.org/licenses/MIT MIT License
 * @link https://codeigniter.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/* COMMON */
$lang[ 'inaccessible_pages'] = 'アクセスすることができないページです。';
$lang[ 'please_try_again'] = 'しばらくして再試行してください。';
$lang[ 'an_error_has_occurred'] = 'エラーが発生しました。';


/* MEMBER.CONTROLLER */
$lang[ 'new'] = '新しい';
$lang[ 'confirm'] = '確認';
$lang[ 'prevent_automatic_subscription'] = '自動登録防止';
$lang['prevent_automatic_does_not_match'] = '%sを正確に入力してください。';
$lang[ 'already_signed_in'] = 'すでにログイン中です。';
$lang['does_not_exist'] = '存在しない%sです。';
$lang['does_not_match'] = '%sが一致していません。';
$lang[ 'member_who_withdrew'] = 'すでに脱退したメンバーです。';
$lang[ 'no_member_information_found'] = '会員情報を見つけることができません。';
$lang[ 'already_member'] = 'すでに登録されているメンバーです。';
$lang[ 'go_to_the_login_page'] ='ログインページに移動します。';
$lang[ 'edit_membership_information'] = '会員情報が変更されました。';
$lang[ 'change_password'] ='パスワードが変更されました。';
$lang[ 'member_withdrawal_processing'] = '会員脱退処理しました。';
$lang['already_registered'] = '既に登録されている%sです。';
$lang['available'] = '使用可能な%sです。';
$lang['password_validation'] = '%sは、英語/数字/特殊文字を2種類以上混合して使用します。';
$lang['dormant_release_process_fail'] = '休眠解除の処理が正常に行われていない。';
$lang['dormant_release_notification_message'] = "%sさんのアカウントは休眠状態のアカウントです。\n休眠アカウントを無効にした後のサービスを利用することができます\n解除後、再ログインが必要です。休眠アカウントを無効にしますか？";
$lang['join_under_14'] = '14歳未満の会員登録時法定代理人の同意が必要です。お客様センターにお問い合わせください。';
$lang['not_category_information_found'] = "カテゴリーについての情報が見つからない";


/* BOARD.CONTROLLER */
$lang['board_mode'] = 'の呼び出しタイプ';
$lang['board_title'] = 'タイトル';
$lang['board_name'] = '作成者';
$lang['board_content'] = '内容';
$lang['board_password'] = 'パスワード';
$lang['board_is_secret'] = '秘密文を追加';
$lang['board_file'] = 'ファイルを添付';
$lang['board_no'] = 'スレッド番号';
$lang['board_idx'] = 'スレッド';
$lang['board_email'] = 'メール';
$lang['board_mobile'] = '携帯電話';
$lang['board_video_url'] = '動画アドレス';
$lang['board_answer_title'] = '回答のタイトル';
$lang['board_answer_content'] = '回答内容';
$lang['no_information_found_in_the_post'] = 'スレッドの情報を見つけることができません。';
$lang['password_does_not_match'] = 'パスワードが一致しません。';
$lang['add_comment'] = 'コメントを登録しました。';
$lang['modify_comment'] = 'コメントを修正しました。';
$lang['delete_comment'] = 'コメントを削除しました。';
$lang['failed_to_delete_post'] = 'スレッドの削除に失敗しました。';
$lang['could_not_write'] = '文を作成しませんでした。';
$lang['delete_post'] = 'スレッドを削除しました。';
$lang['no_bulletin_board_information_found'] = '掲示板の情報を見つけることができません。';
$lang['failed_to_comment'] = 'コメントの作成に失敗しました。';
$lang['comment_modify_failed'] = 'コメントの修正に失敗しました。';
$lang['do_not_have_permission'] = '権限がありません。';
$lang['could_not_find_information_in_the_comment'] = 'コメントの情報が見つかりませんでした。';
$lang['inquiry_is_registered'] = '問い合わせが登録されました。';
$lang['do_no_have_is_secret_permission'] = '秘密の文を閲覧する権限がありません。';
$lang['wrong_approach'] = '誤ったアクセスです。';

/* GOODS.CONTROLLER */
$lang['could_not_get_the_goods_information'] = '商品情報を取得できませんでした。';
$lang['category_permission_denied'] = "そのカテゴリに対するアクセス権限がありません。";


/* FILEREQUEST.CONTROLLER */
$lang[ 'filerequest_folder'] = 'ファイルのアップロードパス';
$lang[ 'filerequest_type'] = 'ファイルの拡張子';
$lang[ 'filerequest_size'] = 'ファイルサイズ';
$lang[ 'filerequest_width'] = 'ファイルサイズ';
$lang[ 'filerequest_height'] = 'ファイルサイズ';
$lang['filerequest_file_name'] = 'ファイル名';
$lang[ 'no_files_selected'] = '選択したファイルがありません。';
$lang[ 'no_file_information_to_download'] = 'ダウンロードするファイルの情報がありません。';
$lang[ 'no_files_to_download'] = 'ダウンロードするファイルはありません。';
$lang[ 'file_can_not_be_downloaded'] = 'ダウンロードすることができないファイルです。';
$lang['upload_ path_cannot_be_uploaded'] = 'このパスはアップロードできない。';
$lang['download_path_cannot_be_downloaded'] = 'このパスはダウンロードできません。';
$lang['download_path_does_not_exist'] = 'ダウンロードパスが存在しません。';
$lang['upload_size_over_php'] = '許可されたファイルのサイズ（%smb）を超えました。';
$lang['file_name_is_required'] = 'ファイル名は必須。';
$lang['no_files_on_the_route'] = 'このパスにファイルが存在しません。';