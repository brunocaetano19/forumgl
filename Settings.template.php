<?php
/**
 * @package SunRise SMF Theme
 * @author SychO (M.S) https://sycho9.github.io
 * @version 2.0
 *
 * @license Copyright (C) 2020 SychO (M.S)
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License along with this program; if not, write to the Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
 * LICENSE.TXT
 *
 * version 2.0
*/
function template_options()
{
	global $context, $settings, $options, $scripturl, $txt;

	$context['theme_options'] = array(
		array(
			'id' => 'show_board_desc',
			'label' => $txt['board_desc_inside'],
			'default' => true,
		),
		array(
			'id' => 'show_children',
			'label' => $txt['show_children'],
			'default' => true,
		),
		array(
			'id' => 'use_sidebar_menu',
			'label' => $txt['use_sidebar_menu'],
			'default' => true,
		),
		array(
			'id' => 'show_no_avatars',
			'label' => $txt['show_no_avatars'],
			'default' => true,
		),
		array(
			'id' => 'show_no_signatures',
			'label' => $txt['show_no_signatures'],
			'default' => true,
		),
		array(
			'id' => 'show_no_censored',
			'label' => $txt['show_no_censored'],
			'default' => true,
		),
		array(
			'id' => 'return_to_post',
			'label' => $txt['return_to_post'],
			'default' => true,
		),
		array(
			'id' => 'no_new_reply_warning',
			'label' => $txt['no_new_reply_warning'],
			'default' => true,
		),
		array(
			'id' => 'view_newest_first',
			'label' => $txt['recent_posts_at_top'],
			'default' => true,
		),
		array(
			'id' => 'view_newest_pm_first',
			'label' => $txt['recent_pms_at_top'],
			'default' => true,
		),
		array(
			'id' => 'posts_apply_ignore_list',
			'label' => $txt['posts_apply_ignore_list'],
			'default' => false,
		),
		array(
			'id' => 'wysiwyg_default',
			'label' => $txt['wysiwyg_default'],
			'default' => false,
		),
		array(
			'id' => 'popup_messages',
			'label' => $txt['popup_messages'],
			'default' => true,
		),
		array(
			'id' => 'copy_to_outbox',
			'label' => $txt['copy_to_outbox'],
			'default' => true,
		),
		array(
			'id' => 'pm_remove_inbox_label',
			'label' => $txt['pm_remove_inbox_label'],
			'default' => true,
		),
		array(
			'id' => 'auto_notify',
			'label' => $txt['auto_notify'],
			'default' => true,
		),
		array(
			'id' => 'topics_per_page',
			'label' => $txt['topics_per_page'],
			'options' => array(
				0 => $txt['per_page_default'],
				5 => 5,
				10 => 10,
				25 => 25,
				50 => 50,
			),
			'default' => true,
		),
		array(
			'id' => 'messages_per_page',
			'label' => $txt['messages_per_page'],
			'options' => array(
				0 => $txt['per_page_default'],
				5 => 5,
				10 => 10,
				25 => 25,
				50 => 50,
			),
			'default' => true,
		),
		array(
			'id' => 'calendar_start_day',
			'label' => $txt['calendar_start_day'],
			'options' => array(
				0 => $txt['days'][0],
				1 => $txt['days'][1],
				6 => $txt['days'][6],
			),
			'default' => true,
		),
		array(
			'id' => 'display_quick_reply',
			'label' => $txt['display_quick_reply'],
			'options' => array(
				0 => $txt['display_quick_reply1'],
				1 => $txt['display_quick_reply2'],
				2 => $txt['display_quick_reply3']
			),
			'default' => true,
		),
		array(
			'id' => 'display_quick_mod',
			'label' => $txt['display_quick_mod'],
			'options' => array(
				0 => $txt['display_quick_mod_none'],
				1 => $txt['display_quick_mod_check'],
				2 => $txt['display_quick_mod_image'],
			),
			'default' => true,
		),
	);
}

function template_settings()
{
	global $context, $settings, $options, $scripturl, $txt;

	$context['theme_settings'] = array(
		array(
			'id' => 'sr_header_logo_url_sun',
			'label' => $txt['sr_header_logo_url_sun'],
			'type' => 'text',
		),
		array(
			'id' => 'sr_header_logo_url_moon',
			'label' => $txt['sr_header_logo_url_moon'],
			'type' => 'text',
		),
		array(
			'id' => 'site_slogan',
			'label' => $txt['site_slogan'],
			'description' => $txt['site_slogan_desc'],
			'type' => 'text',
		),
		array(
			'id' => 'header_fa',
			'label' => $txt['header_fa'],
			'description' => $txt['menu_buttons_settings'],
			'type' => 'text',
		),
		array(
			'id' => 'forumtitle_size',
			'label' => $txt['forumtitle_size'],
			'description' => $txt['forumtitle_size_d'],
			'type' => 'text',
		),
		array(
			'id' => 'uppercase_forumtitle',
			'label' => $txt['uppercase_forumtitle'],
		),
		array(
			'id' => 'default_sr_mode',
			'label' => $txt['default_sr_mode'],
			'options' => Array(
						0 => $txt['sr_sunrise'],
						1 => $txt['sr_midnight'],
					),
			'type' => 'text',
		),
		array(
			'id' => 'no_personal_layout',
			'label' => $txt['no_personal_layout'],
		),
		array(
			'id' => 'forum_width',
			'label' => $txt['forum_width'],
			'description' => $txt['forum_width_desc'],
			'type' => 'text',
			'size' => 8,
		),
		array(
			'id' => 'top_section_width',
			'label' => $txt['top_section_width'],
		),
		array(
			'id' => 'sr_avatar_width',
			'label' => $txt['sr_avatar_width'],
			'type' => 'text'
		),
		array(
			'id' => 'sr_avatar_force_height',
			'label' => $txt['sr_avatar_force_height'],
		),
		array(
			'id' => 'sr_topic_avatar_disable',
			'label' => $txt['sr_topic_avatar_disable'],
		),
	'',
		$txt['sr_graphics'],
		array(
			'id' => 'no_header_mountains',
			'label' => $txt['no_header_mountains']
		),
		array(
			'id' => 'no_footer_mountains',
			'label' => $txt['no_footer_mountains']
		),
		array(
			'id' => 'no_header_sunmoon',
			'label' => $txt['no_header_sunmoon']
		),
		array(
			'id' => 'no_header_stars',
			'label' => $txt['no_header_stars']
		),
	'',
		array(
			'id' => 'smiley_sets_default',
			'label' => $txt['smileys_default_set_for_theme'],
			'options' => $context['smiley_sets'],
			'type' => 'text',
		),
	'',
		array(
			'id' => 'linktree_link',
			'label' => $txt['current_pos_text_img'],
		),
		array(
			'id' => 'show_mark_read',
			'label' => $txt['enable_mark_as_read'],
		),
		array(
			'id' => 'allow_no_censored',
			'label' => $txt['allow_no_censored'],
		),
	'',
		array(
			'id' => 'show_newsfader',
			'label' => $txt['news_fader'],
		),
		array(
			'id' => 'news_boardindex_only',
			'label' => $txt['news_boardindex_only'],
		),
		array(
			'id' => 'newsmarquee_time',
			'label' => $txt['admin_fader_delay'],
			'type' => 'number',
		),
		array(
			'id' => 'number_recent_posts',
			'label' => $txt['number_recent_posts'],
			'description' => $txt['number_recent_posts_desc'],
			'type' => 'number',
		),
		array(
			'id' => 'show_stats_index',
			'label' => $txt['show_stats_index'],
		),
		array(
			'id' => 'show_latest_member',
			'label' => $txt['latest_members'],
		),
		array(
			'id' => 'show_group_key',
			'label' => $txt['show_group_key'],
		),
		array(
			'id' => 'display_who_viewing',
			'label' => $txt['who_display_viewing'],
			'options' => array(
				0 => $txt['who_display_viewing_off'],
				1 => $txt['who_display_viewing_numbers'],
				2 => $txt['who_display_viewing_names'],
			),
			'type' => 'number',
		),
	'',
		array(
			'id' => 'show_modify',
			'label' => $txt['last_modification'],
		),
		array(
			'id' => 'show_profile_buttons',
			'label' => $txt['show_view_profile_button'],
		),
		array(
			'id' => 'show_user_images',
			'label' => $txt['user_avatars'],
		),
		array(
			'id' => 'show_blurb',
			'label' => $txt['user_text'],
		),
		array(
			'id' => 'show_gender',
			'label' => $txt['gender_images'],
		),
		array(
			'id' => 'hide_post_group',
			'label' => $txt['hide_post_group'],
			'description' => $txt['hide_post_group_desc'],
		),
	'',
		array(
			'id' => 'show_bbc',
			'label' => $txt['admin_bbc'],
		),
		array(
			'id' => 'additional_options_collapsable',
			'label' => $txt['additional_options_collapsable'],
		),
	'',
		array(
			'id' => 'footer_copyright',
			'label' => $txt['footer_copyright'],
			'type' => 'text',
		),
	'',
		array(
			'id' => 'footer_blinks_1',
			'label' => $txt['footer_blinks_link'].' 1',
			'type' => 'input',
		),
		array(
			'id' => 'footer_blinks_href_1',
			'label' => $txt['footer_blinks_href_link'].' 1',
			'type' => 'input',
		),
		array(
			'id' => 'footer_blinks_2',
			'label' => $txt['footer_blinks_link'].' 2',
			'type' => 'input',
		),
		array(
			'id' => 'footer_blinks_href_2',
			'label' => $txt['footer_blinks_href_link'].' 2',
			'type' => 'input',
		),
		array(
			'id' => 'footer_blinks_3',
			'label' => $txt['footer_blinks_link'].' 3',
			'type' => 'input',
		),
		array(
			'id' => 'footer_blinks_href_3',
			'label' => $txt['footer_blinks_href_link'].' 3',
			'type' => 'input',
		),
		array(
			'id' => 'footer_blinks_4',
			'label' => $txt['footer_blinks_link'].' 4',
			'type' => 'input',
		),
		array(
			'id' => 'footer_blinks_href_4',
			'label' => $txt['footer_blinks_href_link'].' 4',
			'type' => 'input',
		),
		array(
			'id' => 'footer_blinks_5',
			'label' => $txt['footer_blinks_link'].' 5',
			'type' => 'input',
		),
		array(
			'id' => 'footer_blinks_href_5',
			'label' => $txt['footer_blinks_href_link'].' 5',
			'type' => 'input',
		),array(
			'id' => 'footer_blinks_state',
			'label' => $txt['footer_blinks_state'],
		),
	'',
		array(
			'id' => 'facebook',
			'label' => $txt['facebook'],
			'type' => 'text',
		),
		array(
			'id' => 'youtube',
			'label' => $txt['youtube'],
			'type' => 'text',
		),
		array(
			'id' => 'twitter',
			'label' => $txt['twitter'],
			'type' => 'text',
		),
		array(
			'id' => 'steam',
			'label' => $txt['steam'],
			'type' => 'text',
		),
		array(
			'id' => 'github',
			'label' => $txt['github'],
			'type' => 'text',
		),
		array(
			'id' => 'instagram',
			'label' => $txt['instagram'],
			'type' => 'text',
		),
		array(
			'id' => 'discord',
			'label' => $txt['discord'],
			'type' => 'text',
		),
		array(
			'id' => 'more_link',
			'label' => $txt['more_link'],
			'type' => 'text',
		),
		array(
			'id' => 'more_link_label',
			'label' => $txt['more_link_label'],
			'type' => 'text',
		),
		array(
			'id' => 'more_link_fa',
			'label' => $txt['more_link_fa'],
			'type' => 'text',
		),
	'',
	);
}

?>