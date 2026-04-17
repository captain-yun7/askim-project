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
$lang['inaccessible_pages'] = '此页面无法访问。';
$lang['please_try_again'] = '请稍后再试。';
$lang['an_error_has_occurred'] = '发生了错误。';


/* MEMBER.CONTROLLER */
$lang['new'] = '新';
$lang['confirm'] = '确认';
$lang['prevent_automatic_subscription'] = '防止自动订阅';
$lang['prevent_automatic_does_not_match'] = '请准确输入%s';
$lang['already_signed_in'] = '您已登录。';
$lang['does_not_exist'] = '%s不存在。';
$lang['does_not_match'] = '%s不匹配。';
$lang['member_who_withdrew'] = '我是已经离开的会员。';
$lang['no_member_information_found'] = '未找到会员信息。';
$lang['already_member'] = '您已经是会员。';
$lang['go_to_the_login_page'] = '转到登录页面。';
$lang['edit_membership_information'] = '会员资格已被修改。';
$lang['change_password'] = '您的密码已更改。';
$lang['member_withdrawal_processing'] = '您的取消订阅已处理完毕。';
$lang['already_registered'] = '它已经注册%s。';
$lang['available'] = '%s可用。';
$lang['password_validation'] = '%s必须结合使用两个或多个英文/数字/特殊字符。';
$lang['dormant_release_process_fail'] = '睡眠释放处理不成功。';
$lang['dormant_release_notification_message'] = "%s的帐户是一个休眠帐户。\n您可以在停用休眠帐户后使用该服务。\n您需要在禁用它后再次登录。 您确定要释放休眠帐户吗？";
$lang['join_under_14'] = '未满14岁的代理人必须征得法定代表人的同意。 请联系我们。';
$lang['not_category_information_found'] = "找不到有關類別的信息。";


/* BOARD.CONTROLLER */
$lang['board_mode'] = '通话类型';
$lang['board_title'] = '主题';
$lang['board_name'] = '作者';
$lang['board_content'] = '内容';
$lang['board_password'] = '密码';
$lang['board_is_secret'] = '添加密文';
$lang['board_file'] = '文件附件';
$lang['board_no'] = '帖子编号';
$lang['board_idx'] = '帖子';
$lang['board_email'] = '电子邮件';
$lang['board_mobile'] = '手机';
$lang['board_video_url'] = '视频地址';
$lang['board_answer_title'] = '答案标题';
$lang['board_answer_content'] = '回答内容';
$lang['no_information_found_in_the_post'] = '在帖子中找不到任何信息。';
$lang['password_does_not_match'] = '密码不匹配。';
$lang['add_comment'] = '您已注册您的评论。';
$lang['modify_comment'] = '您的评论已被修改。';
$lang['delete_comment'] = '您的评论已被删除。';
$lang['failed_to_delete_post'] = '无法删除帖子。';
$lang['could_not_write'] = '无法写。';
$lang['delete_post'] = '您的帖子已被删除。';
$lang['no_bulletin_board_information_found'] = '我在公告板上找不到相关信息。';
$lang['failed_to_comment'] = '评论失败';
$lang['comment_modify_failed'] = '评论编辑失败。';
$lang['do_not_have_permission'] = '您没有权限。';
$lang['could_not_find_information_in_the_comment'] = '我们在评论中找不到任何信息。';
$lang['inquiry_is_registered'] = '您的查询已注册。';
$lang['do_no_have_is_secret_permission'] = '没有权限阅读密文。';
$lang['wrong_approach'] = '錯誤的接近。';

/* GOODS.CONTROLLER */
$lang['could_not_get_the_goods_information'] = '我们无法获得产品信息。';
$lang['category_permission_denied'] = "没有权利接近相应类别。";


/* FILEREQUEST.CONTROLLER */
$lang['filerequest_folder'] =  '文件上传路径';
$lang['filerequest_type'] = '文件扩展名';
$lang['filerequest_size'] = '文件大小';
$lang['filerequest_width'] = '文件大小';
$lang['filerequest_height'] = '文件大小';
$lang['filerequest_file_name'] = '文件名';
$lang['no_files_selected'] = '没有选择文件。';
$lang['no_file_information_to_download'] = '没有要下载的文件信息。';
$lang['no_files_to_download'] = '没有要下载的文件。';
$lang['file_can_not_be_downloaded'] = '无法下载此文件。';
$lang['upload_ path_cannot_be_uploaded'] = '此路徑不可上傳。';
$lang['download_path_cannot_be_downloaded'] = '這個路徑不能下載。';
$lang['download_path_does_not_exist'] = '下載路徑不存在。';
$lang['upload_size_over_php'] = '超出了允许的文件大小（%s mb）。';
$lang['file_name_is_required'] = '文件名是必須的。';
$lang['no_files_on_the_route'] = '相關路徑中不存在文件。';