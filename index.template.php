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
// Initialize the template... mainly little settings.
function template_init()
{
	global $context, $settings, $options, $txt;

	/* Use images from default theme when using templates from the default theme?
		if this is 'always', images from the default theme will be used.
		if this is 'defaults', images from the default theme will only be used with default templates.
		if this is 'never' or isn't set at all, images from the default theme will not be used. */
	$settings['use_default_images'] = 'never';

	/* What document type definition is being used? (for font size and other issues.)
		'xhtml' for an XHTML 1.0 document type definition.
		'html' for an HTML 4.01 document type definition. */
	$settings['doctype'] = 'xhtml';

	/* The version this template/theme is for.
		This should probably be the version of SMF it was created for. */
	$settings['theme_version'] = '2.0';

	/* Set a setting that tells the theme that it can render the tabs. */
	$settings['use_tabs'] = true;

	/* Use plain buttons - as opposed to text buttons? */
	$settings['use_buttons'] = true;

	/* Show sticky and lock status separate from topic icons? */
	$settings['separate_sticky_lock'] = true;

	/* Does this theme use the strict doctype? */
	$settings['strict_doctype'] = false;

	/* Does this theme use post previews on the message index? */
	$settings['message_index_preview'] = false;

	/* Set the following variable to true if this theme requires the optional theme strings file to be loaded. */
	$settings['require_theme_strings'] = true;

	/* Translation Credits. Thanks for all the translators ! */
	$settings['translations'] = array(
		array(
			'lang' => 'Spanish',
			'code' => 'es',
			'author' => array(
				'name' => 'Rock Lee',
				'link' => 'https://www.simplemachines.org/community/index.php?action=profile;u=322597'
			)
		),
		array(
			'lang' => 'Polish',
			'code' => 'pl',
			'author' => array(
				'name' => 'UltimatePremium',
				'link' => 'https://www.simplemachines.org/community/index.php?action=profile;u=597100'
			)
		),
		array(
			'lang' => 'Russian',
			'code' => 'ru',
			'author' => array(
				'name' => 'Whyskas',
				'link' => 'https://www.simplemachines.org/community/index.php?action=profile;u=598285'
			)
		)
	);
}

// The main sub template above the content.
function template_html_above()
{
	global $context, $settings, $options, $scripturl, $txt, $modSettings;
	// Theme Mode Cookie (SunRise or MidNight)(Dark or Light)(Ying or Yang)...
	if (isset($_COOKIE['sr_mode']) && empty($settings['no_personal_layout']))
	{
		$context['sr_mode'] = array(
			'value' => $_COOKIE['sr_mode'],
			'title' => $txt['sr_mode_'.$_COOKIE['sr_mode']],
			'fa' => $_COOKIE['sr_mode'] == 1 ? 'sun' : 'moon',
		);
	}
	else
	{
		$settings['default_sr_mode'] = !isset($settings['default_sr_mode']) ? 0 : $settings['default_sr_mode'];
		$context['sr_mode'] = array(
			'value' => $settings['default_sr_mode'],
			'title' => $txt['sr_mode_'.$settings['default_sr_mode']],
			'fa' => $settings['default_sr_mode'] == 1 ? 'sun' : 'moon',
		);
	}
	/* Set the default avatar image */
	$settings['sr_default_avatar'] = '<img src="'.$settings['theme_url'].'/images/default_avatar' . (!empty($context['sr_mode']['value']) ? '_midnight' : '') . '.png" class="avatar sr_switchable"/>';
	/* Remove [ & ] from the page index, ugh */
	$mybefore = array("[", "]", "<strong");
	$myafter = array("", "", "<strong class='navPages active'");
	if(!empty($context['page_index']))
		$context['page_index'] = str_replace($mybefore, $myafter, $context['page_index']);

	// Show right to left and the character set for ease of translating.
	echo '
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml"', $context['right_to_left'] ? ' dir="rtl"' : '', '', !empty($txt['lang_locale']) ? ' lang="' . str_replace("_", "-", substr($txt['lang_locale'], 0, strcspn($txt['lang_locale'], "."))) . '"' : '', '>
<head>';

	// Meta comes first
	echo'
	<meta charset="', $context['character_set'], '" />';

	// The ?fin20 part of this link is just here to make sure browsers don't cache it wrongly.
	echo '
	<link rel="stylesheet" type="text/css" href="', $settings['theme_url'], '/css/index', $context['theme_variant'], '.css?fin20" />';

	// Responsive
	echo'
	<link rel="stylesheet" type="text/css" href="', $settings['theme_url'], '/css/responsive.css?fin20" />';

	// FontAwesome, Free
	echo'
	<link rel="stylesheet" type="text/css" href="', $settings['theme_url'], '/css/fontawesome-all.min.css" />';

	// Sunrise & Midnight, Negative & Positive, Ying & Yang
	if($context['sr_mode']['value']==0)
		echo'
		<link class="tobeswitched" rel="stylesheet" type="text/css" href="', $settings['theme_url'], '/css/sunrise.css?fin20" />';
	else
		echo'
		<link class="tobeswitched" rel="stylesheet" type="text/css" href="', $settings['theme_url'], '/css/midnight.css?fin20" />';

	// Some browsers need an extra stylesheet due to bugs/compatibility issues.
	foreach (array('ie7', 'ie6', 'webkit') as $cssfix)
		if ($context['browser']['is_' . $cssfix])
			echo '
	<link rel="stylesheet" type="text/css" href="', $settings['default_theme_url'], '/css/', $cssfix, '.css" />';

	// RTL languages require an additional stylesheet.
	if ($context['right_to_left'])
		echo '
	<link rel="stylesheet" type="text/css" href="', $settings['theme_url'], '/css/rtl.css" />';

	// jQuery 3.2.1
	echo'
	<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
	<script>
		var jq = jQuery.noConflict(true);
	</script>';

	// Here comes the JavaScript bits!
	echo '
	<script src="', $settings['default_theme_url'], '/scripts/script.js?fin20"></script>
	<script src="', $settings['theme_url'], '/scripts/theme.js?fin20"></script>
	<script><!-- // --><![CDATA[
		var smf_theme_url = "', $settings['theme_url'], '";
		var smf_default_theme_url = "', $settings['default_theme_url'], '";
		var smf_images_url = "', $settings['images_url'], '";
		var smf_scripturl = "', $scripturl, '";
		var smf_iso_case_folding = ', $context['server']['iso_case_folding'] ? 'true' : 'false', ';
		var smf_charset = "', $context['character_set'], '";', $context['show_pm_popup'] ? '
		var fPmPopup = function ()
		{
			if (confirm("' . $txt['show_personal_messages'] . '"))
				window.open(smf_prepareScriptUrl(smf_scripturl) + "action=pm");
		}
		addLoadEvent(fPmPopup);' : '', '
		var ajax_notification_text = "', $txt['ajax_in_progress'], '";
		var ajax_notification_cancel_text = "', $txt['modify_cancel'], '";
	// ]]></script>';

	echo '
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="theme-color" content="', $context['sr_mode']['value']==0 ? '#FF6120' : '#0073AB', '">
	<meta name="description" content="', $context['page_title_html_safe'], '" />', !empty($context['meta_keywords']) ? '
	<meta name="keywords" content="' . $context['meta_keywords'] . '" />' : '', '
	<title>', $context['page_title_html_safe'], '</title>';

	// Please don't index these Mr Robot.
	if (!empty($context['robot_no_index']))
		echo '
	<meta name="robots" content="noindex" />';

	// Present a canonical url for search engines to prevent duplicate content in their indices.
	if (!empty($context['canonical_url']))
		echo '
	<link rel="canonical" href="', $context['canonical_url'], '" />';

	// Show all the relative links, such as help, search, contents, and the like.
	echo '
	<link rel="help" href="', $scripturl, '?action=help" />
	<link rel="search" href="', $scripturl, '?action=search" />
	<link rel="contents" href="', $scripturl, '" />';

	// If RSS feeds are enabled, advertise the presence of one.
	if (!empty($modSettings['xmlnews_enable']) && (!empty($modSettings['allow_guestAccess']) || $context['user']['is_logged']))
		echo '
	<link rel="alternate" type="application/rss+xml" title="', $context['forum_name_html_safe'], ' - ', $txt['rss'], '" href="', $scripturl, '?type=rss;action=.xml" />';

	// If we're viewing a topic, these should be the previous and next topics, respectively.
	if (!empty($context['current_topic']))
		echo '
	<link rel="prev" href="', $scripturl, '?topic=', $context['current_topic'], '.0;prev_next=prev" />
	<link rel="next" href="', $scripturl, '?topic=', $context['current_topic'], '.0;prev_next=next" />';

	// If we're in a board, or a topic for that matter, the index will be the board's index.
	if (!empty($context['current_board']))
		echo '
	<link rel="index" href="', $scripturl, '?board=', $context['current_board'], '.0" />';

	// Output any remaining HTML headers. (from mods, maybe?)
	echo $context['html_headers'];

	echo '
	<script>
		jq(document).ready(function() {
			jq(\'.tooltip\').tooltipster({
				delay: 0,
				theme: \'tooltipster-borderless\'
			});
			jq("ul.quickbuttons:not(.phoneList) li a").each(function() {
				jq(this).tooltipster({
					content: jq(this).find("span"),
					selfDestruction: false,
					// if you use a single element as content for several tooltips, set this option to true
					contentCloning: false,
					delay: 0,
					theme: \'tooltipster-borderless\'
				});
			});
		});
	</script>
</head>
<body>';
}

function template_body_above()
{
	global $context, $settings, $options, $scripturl, $txt, $modSettings;

	$header_class = (!empty($settings['no_header_mountains']) ? 'no_header_mountains' : '') . ' ' . (!empty($settings['no_header_stars']) ? 'no_header_stars' : '') . ' ' . (!empty($settings['no_header_sunmoon']) ? 'no_header_sunmoon' : '');
	$trimmed = trim($header_class);

	echo '
	<header', !empty($trimmed) ? ' class="' . $header_class . '"' : '', '>
		<div class="frame">
			<div id="top_section">
				<div class="wrapper" ', (!empty($settings['top_section_width']) ? 'style="width:96%;"' : (!empty($settings['forum_width']) ? 'style="width:'.$settings['forum_width'].'"' : '')), '>
					<div class="user ', !empty($context['show_login_bar']) ? 'guest' : '', '">
						<ul class="dropmenu">';

	// If the user is logged in, display stuff like their name, new messages, etc.
	if ($context['user']['is_logged'])
	{
		$one_of_three = !empty($context['unapproved_members']) || (!empty($context['open_mod_reports']) && $context['show_open_reports']) || !empty($context['user']['unread_messages']);
		$totally = (!empty($context['unapproved_members']) ? (int)$context['unapproved_members'] : 0) + (!empty($context['open_mod_reports']) ? (int)$context['open_mod_reports'] : 0) + $context['user']['unread_messages'];

		echo'
							<li>
								<a class="firstlevel" href="javascript:void(0)">', !empty($context['user']['avatar']) ? $context['user']['avatar']['image'] : $settings['sr_default_avatar'], '<span class="spshl"><span class="toolong">', $context['user']['name'], '   </span>' . fontawesome('fas fa-caret-down') . '</span></a>
								<ul>';

		if (allowedTo('pm_read'))
			echo '
									<li><a class="', $context['user']['unread_messages'] > 0 ? 'notice' : '', '" href="', $scripturl, '?action=pm">' . fontawesome('fas fa-envelope') . '	 ', $context['user']['unread_messages'] > 0 ? '<span class="newNumber">'.$context['user']['unread_messages'].'</span> '.$txt['new_messages'] : $txt['messages'].' ('.$context['user']['messages'].')', '</a></li>';

		if(allowedTo('profile_identity_own'))
			echo'
									<li><a class="" href="'.$scripturl.'?action=profile;area=account">' . fontawesome('fas fa-user-cog') . '	'.$txt['account'].'</a></li>';

		if(allowedTo('profile_extra_own'))
			echo '
									<li><a href="'.$scripturl.'?action=profile;area=theme;u='.$context['user']['id'].'">' . fontawesome('fas fa-paint-brush') . '	'.$txt['look_and_layout'].'</a></li>
									<li><a href="'.$scripturl.'?action=profile;area=notification;u='.$context['user']['id'].'">' . fontawesome('fas fa-bell') . '	'.$txt['notifications'].'</a></li>';

		// Support for vbgamer45's Like Posts Mod. A Comment which will remove an error from the mod installation
		// <li><a href="', $scripturl, '?action=unreadreplies">', $txt['show_unread_replies'], '</a></li>';

		// LikePosts Mod Support
		if(class_exists('LikePosts'))
			echo '
									<li><a href="', $scripturl, '?action=unreadreplies">', $txt['show_unread_replies'], '</a></li>';

		if (!empty($context['unapproved_members']))
			echo '
									<li><a class="notice" href="', $scripturl, '?action=admin;area=viewmembers;sa=browse;type=approve">' . fontawesome('fas fa-users-cog') . '	', $txt['sr_approve_members_waiting'], ' <span class="newNumber">', $context['unapproved_members'], '</span></a></li>';

		if (!empty($context['open_mod_reports']) && $context['show_open_reports'])
			echo '
									<li><a class="notice" href="', $scripturl, '?action=moderate;area=reports">' . fontawesome('fas fa-folder-open') . '	', $txt['sr_mod_reports_waiting'], ' <span class="newNumber">', $context['open_mod_reports'], '</span></a></li>';

		echo'
									<li><a href="', $scripturl, '?action=logout;', $context['session_var'], '=', $context['session_id'], '">' . fontawesome('fas fa-sign-out-alt') . '	', $txt['logout'], '</a></li>
								</ul>
								', $one_of_three ? '<span class="newNumber newNumberAbs">'.$totally.'</span>' : '', '
							</li>
							<li><a class="firstlevel" href="', $scripturl, '?action=unread">' . fontawesome('fas fa-user-plus') . '	 <span>', $txt['sr_unread_since_visit'], '</span></a></li>
							<li><a class="firstlevel" href="', $scripturl, '?action=unreadreplies">' . fontawesome('fas fa-comments') . '	<span>', $txt['sr_show_unread_replies'], '</span></a></li>';
	}
	// Otherwise they're a guest - this time ask them to either register or login - lazy bums...
	elseif (!empty($context['show_login_bar']))
	{
		echo'
							<li><a href="javascript:void(0)" class="firstlevel loginOpen">' . fontawesome('fas fa-sign-in-alt') . '	<span>', $txt['login'], '</span></a></li>
							<li><a href="', $scripturl, '?action=register" class="firstlevel">' . fontawesome('fas fa-user-plus') . '	<span>', $txt['register'], '</span></a></li>';
	}

	echo '
						</ul>
					</div>';

	// Show the menu here, according to the menu sub template.
	template_menu();

	echo '
					<div class="menuOpener taphoOnlyInline floatright buttonLike" data-state="closed">' . fontawesome('fas fa-bars') . '</div>
				</div>
			</div>
		</div>
			<div id="upper_section" class="middletext"><div class="wrapper" ', !empty($settings['forum_width']) ? 'style="width:'.$settings['forum_width'].'"' : '', '>
				<div class="mosunmo">
					<div class="mountain"></div>
					<div class="sunmoon ', $context['sr_mode']['fa']=="moon" ? 'sunrise_m' : 'midnight_m', '"></div>
					<div class="sr_star"></div><div class="sr_star"></div><div class="sr_star"></div><div class="sr_star"></div><div class="sr_star"></div>
					<div class="sr_star"></div><div class="sr_star"></div><div class="sr_star"></div><div class="sr_star"></div><div class="sr_star"></div>
				</div>';

	if (empty($settings['sr_header_logo_url_' . ($context['sr_mode']['fa']=='sun' ? 'moon' : 'sun')]))
	{
		$site_slogan = empty($settings['site_slogan']) ? '' : $settings['site_slogan'];
		echo'
					<div class="forumtitle" style="', !empty($settings['uppercase_forumtitle']) ? 'text-transform: uppercase;' : '', '', !empty($settings['forumtitle_size']) ? 'font-size: '.$settings['forumtitle_size'].'px;' : '', '">
						<div onclick="window.location.href = \'', $scripturl, '\'" class="title_onclick">
							', !empty($settings['header_fa']) ? '<div class="side_icon float"><i class="'.((strpos($settings['header_fa'], "fab")!==FALSE || strpos($settings['header_fa'], "fas")!==FALSE || strpos($settings['header_fa'], "fal")!==FALSE || strpos($settings['header_fa'], "far")!==FALSE) ? '' : 'fas ').''.$settings['header_fa'].'"></i></div>' : '',
							'<div>', $context['forum_name'], '', !empty($site_slogan) ? '<br><span>'.$site_slogan.'</span>' : '', '</div>
						</div>
					</div>';
	}
	else
		echo'
					<div class="forumtitle imgHead">
						<div onclick="window.location.href = \'', $scripturl, '\'" class="title_onclick">
							<img src="' . $settings['sr_header_logo_url_' . ($context['sr_mode']['fa']=='sun' ? 'moon' : 'sun')] . '" class="sr_logo_switch" alt="' . $context['forum_name'] . '" />
						</div>
					</div>';

	echo'
				<div class="topSettings">
					<div class="searchButton fsOpen buttonLike icon_style floatright tooltip" title="', $txt['search'], '">' . fontawesome('fas fa-search') . '</div>
					', $context['user']['is_admin'] ? '<a class="themeSettings buttonLike icon_style floatright tooltip" title="'.$txt['theme_settings'].'" href="'.$scripturl.'?action=admin;area=theme;sa=settings;th='.$settings['theme_id'].';'.$context['session_var'].'='.$context['session_id'].'">' . fontawesome('fas fa-cogs') . '</a>' : '', '
					', empty($settings['no_personal_layout']) ? '<div class="sr_modeSwitcher buttonLike icon_style floatright tooltip" title="' . $context['sr_mode']['title'] . '"><i class="fas fa-' . $context['sr_mode']['fa'] . '"></i></div>' : '', '
				</div>
			</div>
		</div>';

	echo '
	</header>';

	// The main content should go here.
	echo '
	<div id="content_section" class="wrapper" ', !empty($settings['forum_width']) ? 'style="width:'.$settings['forum_width'].'"' : '', '>
		<div class="frame">
			<div id="main_content_section">';

	if ($settings['show_newsfader'] && !empty($context['fader_news_lines']) && empty($settings['news_boardindex_only']))
	{
		echo'
				<div class="news_marquee">
					' . fontawesome('fas fa-info') . '
					<div class="newslines">
						<div class="marquee">
							', implode(fontawesome('fas fa-circle'), $context['news_lines']), '
						</div>
					</div>
				</div>
				<script>
					jq(\'.marquee\').marquee({
						// Speed in milliseconds of the marquee
						duration: ', empty($settings['newsmarquee_time']) ? '14000' : $settings['newsmarquee_time'], ',
						pauseOnHover: true
					});
				</script>';
	}

	// Custom banners and shoutboxes should be placed here, before the linktree.

	// Show the navigation tree.
	theme_linktree();
}

function template_body_below()
{
	global $context, $settings, $options, $scripturl, $txt, $modSettings;

	echo '
			</div>
		</div>
	</div>';

	// Show the "Powered by" and "Valid" logos, as well as the copyright. Remember, the copyright must be somewhere!
	echo '
	<footer', !empty($settings['no_footer_mountains']) ? ' class="no_footer_mountains"' : '', '>
		<div class="frame">
			<div class="wrapper" ', !empty($settings['forum_width']) ? 'style="width:'.$settings['forum_width'].'"' : '', '>
				<a href="#top_section" class="buttonLike toTopSR phoneNone">' . fontawesome('fas fa-arrow-up') . '</a>
				<ul class="reset">
					<li>', !empty($settings['footer_copyright']) ? $settings['footer_copyright'] : '', '</li>
					<li class="copyright">', theme_copyright(), '</li>
					<li class="social_media">
					', !empty($settings['facebook']) ? '<a href="'.$settings['facebook'].'" class="buttonLike invert icon_style tooltip facebook" title="'.$txt['facebook'].'">' . fontawesome('fab fa-facebook-f') . '</a>' : '', '<!--
					-->', !empty($settings['youtube']) ? '<a href="'.$settings['youtube'].'" class="buttonLike invert icon_style tooltip youtube" title="'.$txt['youtube'].'">' . fontawesome('fab fa-youtube') . '</a>' : '', '<!--
					-->', !empty($settings['twitter']) ? '<a href="'.$settings['twitter'].'" class="buttonLike invert icon_style tooltip twitter" title="'.$txt['twitter'].'">' . fontawesome('fab fa-twitter') . '</a>' : '', '<!--
					-->', !empty($settings['steam']) ? '<a href="'.$settings['steam'].'" class="buttonLike invert icon_style tooltip steam" title="'.$txt['steam'].'">' . fontawesome('fab fa-steam-symbol') . '</a>' : '', '', '<!--
					-->', !empty($settings['github']) ? '<a href="'.$settings['github'].'" class="buttonLike invert icon_style tooltip github" title="'.$txt['github'].'">' . fontawesome('fab fa-github') . '</a>' : '', '', '<!--
					-->', !empty($settings['discord']) ? '<a href="'.$settings['discord'].'" class="buttonLike invert icon_style tooltip discord" title="'.$txt['discord'].'">' . fontawesome('fab fa-discord') . '</a>' : '<!--
					-->', !empty($settings['instagram']) ? '<a href="'.$settings['instagram'].'" class="buttonLike invert icon_style tooltip instagram" title="'.$txt['instagram'].'">' . fontawesome('fab fa-instagram') . '</a>' : '', '';

	if (!empty($settings['more_link']))
	{
		echo'
			<a href="'.$settings['more_link'].'" class="buttonLike invert icon_style tooltip" title="', !empty($settings['more_link_label']) ? $settings['more_link_label'] : $txt['more'],'">' . fontawesome($settings['more_link_fa']) . '</a>';
	}

					echo'
						</li>';

	if (!empty($settings['footer_blinks_state']))
		echo'
						<li class="additional_links">
							<ul>
								', (empty($settings['footer_blinks_1'])||empty($settings['footer_blinks_href_1'])) ? '' : '<li><a href="'.$settings['footer_blinks_href_1'].'" target="_blank">'.$settings['footer_blinks_1'].'</a></li>', '<!--
								-->', (empty($settings['footer_blinks_2'])||empty($settings['footer_blinks_href_2'])) ? '' : '<li><a href="'.$settings['footer_blinks_href_2'].'" target="_blank">'.$settings['footer_blinks_2'].'</a></li>', '<!--
								-->', (empty($settings['footer_blinks_3'])||empty($settings['footer_blinks_href_3'])) ? '' : '<li><a href="'.$settings['footer_blinks_href_3'].'" target="_blank">'.$settings['footer_blinks_3'].'</a></li>', '<!--
								-->', (empty($settings['footer_blinks_4'])||empty($settings['footer_blinks_href_4'])) ? '' : '<li><a href="'.$settings['footer_blinks_href_4'].'" target="_blank">'.$settings['footer_blinks_4'].'</a></li>', '<!--
								-->', (empty($settings['footer_blinks_5'])||empty($settings['footer_blinks_href_5'])) ? '' : '<li><a href="'.$settings['footer_blinks_href_5'].'" target="_blank">'.$settings['footer_blinks_5'].'</a></li>', '
							</ul>
						</li>';

	echo'
					</ul>';

	// Show the load time?
	if ($context['show_load_time'])
		echo '
					<p>', $txt['page_created'], $context['load_time'], $txt['seconds_with'], $context['load_queries'], $txt['queries'], '</p>';

	echo '
			</div>
		</div>
	</footer>';
}

function template_html_below()
{
	global $context, $settings, $options, $scripturl, $txt, $modSettings;

	// Fullscreen Search Bar
	echo'
	<div class="fullscreen searchBar" style="display:none;">
		<div class="fsClose buttonLike icon_style">' . fontawesome('fas fa-times-circle') . '</div>
		<div class="fsInner">';
		echo'
			<form id="search_form" action="', $scripturl, '?action=search2" method="post" accept-charset="', $context['character_set'], '">
				<div class="inGroup" style="margin-top: 25px;">
					<input type="text" name="search" value="" class="input_text" required/>
					<span class="highlight"></span>
					<span class="bar"></span>
					<label>Search </label>
				</div>
				<input type="submit" name="submit" value="', $txt['search'], '" class="button_submit" />
				<input type="hidden" name="advanced" value="0" />';

	// Search within current topic?
	if (!empty($context['current_topic']))
		echo '
				<input type="hidden" name="topic" value="', $context['current_topic'], '" />';
	// If we're on a certain board, limit it to this board ;).
	elseif (!empty($context['current_board']))
		echo '
				<input type="hidden" name="brd[', $context['current_board'], ']" value="', $context['current_board'], '" />';

			echo '
			</form>
		</div>
	</div>';

	if (!empty($context['show_login_bar']))
	{
		echo '
		<div class="fullscreen loginBar" style="display:none;">
			<div class="fsClose buttonLike icon_style">' . fontawesome('fas fa-times-circle') . '</div>
			<div class="fsInner">
				<script src="', $settings['default_theme_url'], '/scripts/sha1.js"></script>
				<form id="guest_form" action="', $scripturl, '?action=login2" method="post" accept-charset="', $context['character_set'], '" ', empty($context['disable_login_hashing']) ? ' onsubmit="hashLoginPassword(this, \'' . $context['session_id'] . '\');"' : '', ' autocomplete="off" >
					<input autocomplete="false" name="hidden" type="text" style="display:none;">
					<div class="inGroup griny" style="margin-top: 25px;">
						<input type="text" name="user" size="10" class="input_text" autocomplete="off" required/>
						<span class="highlight"></span>
						<span class="bar"></span>
						<label>', $txt['username'], '</label>
					</div>
					<div class="inGroup griny">
						<input type="password" name="passwrd" size="10" class="input_password" autocomplete="nope" required/>
						<span class="highlight"></span>
						<span class="bar"></span>
						<label>', $txt['password'], '</label>
					</div>
					<label class="container checkmall">', $txt['always_logged_in'], '
					  <input type="checkbox" name="cookieneverexp" class="input_check" />
					  <span class="checkmark"></span>
					</label>
					<div class="downer">
						<input type="submit" value="', $txt['login'], '" class="button_submit" />
						<a href="', $scripturl, '?action=reminder">', $txt['forgot_your_password'], '</a>
					</div>';

		if (!empty($modSettings['enableOpenID']))
			echo '
					<br /><input type="text" name="openid_identifier" id="openid_url" size="25" class="input_text openid_login" />';

		echo '
					<input type="hidden" name="hash_passwrd" value="" /><input type="hidden" name="', $context['session_var'], '" value="', $context['session_id'], '" />
				</form>
			</div>
		</div>';
	}

	echo '
	<script>
		jq(document).ready(function () {
			jq(".fsOpen").click(function () {
				jq("input:text:visible:first").focus();
				jq("body").css("overflow", "hidden");
			});
			jq(".searchButton").click(function () {
				jq(".fullscreen.searchBar").css("display", "block");
			});
			jq(".loginOpen").click(function () {
				jq(".fullscreen.loginBar").css("display", "block");
			});
			jq(".fullscreen .fsClose").click(function () {
				jq(".fullscreen").css("display", "none");
				jq("body").css("overflow", "auto");
			});
		});

		// Cookies
		jq(".sr_modeSwitcher").click( function() {
			var sr_value = getCookie("sr_mode");
			var oSunRise = {
				default: {
					avatar: "', $context['sr_mode']['value']==0 ? $settings['theme_url'].'/images/default_avatar_midnight.png' : $settings['theme_url'].'/images/default_avatar.png', '",
					fa: "', $context['sr_mode']['fa']=='sun' ? 'moon' : 'sun', '",
					stylesheet: "<link class=\"tobeswitched\" rel=\"stylesheet\" type=\"text/css\" href=\"', $settings['theme_url'], '/css/', $context['sr_mode']['value']==0 ? 'midnight' : 'sunrise', '.css?fin20\" />",
					logo: "', !empty($settings['sr_header_logo_url_' . $context['sr_mode']['fa']]) ? $settings['sr_header_logo_url_' . $context['sr_mode']['fa']] : '', '"
				},
				0: {
					avatar: "', $settings['theme_url'], '/images/default_avatar.png",
					fa: "moon",
					stylesheet: "<link class=\"tobeswitched\" rel=\"stylesheet\" type=\"text/css\" href=\"', $settings['theme_url'], '/css/sunrise.css?fin20\" />",
					logo: "', !empty($settings['sr_header_logo_url_sun']) ? $settings['sr_header_logo_url_sun'] : '', '"
				},
				1: {
					avatar: "', $settings['theme_url'], '/images/default_avatar_midnight.png",
					fa: "sun",
					stylesheet: "<link class=\"tobeswitched\" rel=\"stylesheet\" type=\"text/css\" href=\"', $settings['theme_url'], '/css/midnight.css?fin20\" />",
					logo: "', !empty($settings['sr_header_logo_url_moon']) ? $settings['sr_header_logo_url_moon'] : '', '"
				}
			};

			var sr_new_val = "default";
			if (sr_value == "") {
				setCookie("sr_mode", ', $context['sr_mode']['value']==0 ? '1' : '0', ', 30);
				jq(this).tooltipster("content", "', $txt['sr_mode_'.($context['sr_mode']['value']==0 ? '1' : '0')], '");
			} else {
				if(sr_value == 1) {
					sr_new_val = 0;
					jq(this).tooltipster("content", "', $txt['sr_mode_0'], '");
				} else {
					sr_new_val = 1;
					jq(this).tooltipster("content", "', $txt['sr_mode_1'], '");
				}

				setCookie("sr_mode", sr_new_val, 30);
			}

			// Fade body out
			jq("body").fadeOut();

			// Switch stuff
			jq(".sr_modeSwitcher").html("<i class=\'fas fa-"+oSunRise[sr_new_val].fa+"\'></i>");
			setTimeout(function() {
					jq(".tobeswitched").remove();
					jq("head").append(oSunRise[sr_new_val].stylesheet);
					jq(".avatar.sr_switchable").attr("src", oSunRise[sr_new_val].avatar);
					if(oSunRise[sr_new_val].logo)
						jq(".sr_logo_switch").attr("src", oSunRise[sr_new_val].logo);
					if(oSunRise[sr_new_val].fa=="moon")
						jq(".sunmoon").removeClass("midnight");
					else
						jq(".sunmoon").removeClass("sunrise");
			}, 400);


			// Fade back in
			jq("body").fadeIn();

			setTimeout(function() {
					if(oSunRise[sr_new_val].fa=="moon")
						jq(".sunmoon").addClass("sunrise");
					else
						jq(".sunmoon").addClass("midnight");
			}, 500);
		} );

	</script>
	<div class="sampleClass"></div>';

	echo '
</body></html>';
}

// Show a linktree. This is that thing that shows "My Community | General Category | General Discussion"..
function theme_linktree($force_show = false)
{
	global $context, $settings, $options, $shown_linktree;

	// If linktree is empty, just return - also allow an override.
	if (empty($context['linktree']) || (!empty($context['dont_default_linktree']) && !$force_show))
		return;

	echo '
	<div class="navigate_section">
		<ul>';

	// Each tree item has a URL and name. Some may have extra_before and extra_after.
	foreach ($context['linktree'] as $link_num => $tree)
	{
		echo '
			<li', ($link_num == count($context['linktree']) - 1) ? ' class="last"' : '', '>';

		// Show something before the link?
		if (isset($tree['extra_before']))
			echo $tree['extra_before'];

		// Show the link, including a URL if it should have one.
		echo $settings['linktree_link'] && isset($tree['url']) ? '
				<a href="' . $tree['url'] . '"><span>' . $tree['name'] . '</span></a>' : '<span>' . $tree['name'] . '</span>';

		// Show something after the link...?
		if (isset($tree['extra_after']))
			echo $tree['extra_after'];

		// Don't show a separator for the last one.
		if ($link_num != count($context['linktree']) - 1)
			echo ' &#187;';

		echo '
			</li>';
	}
	echo '
		</ul>
	</div>';

	$shown_linktree = true;
}

// Show the menu up top. Something like [home] [help] [profile] [logout]...
function template_menu()
{
	global $context, $settings, $options, $scripturl, $txt;

	echo '
		<nav>
			<div class="incarn taphoOnly"><h4>', $txt['menu'], '</h4><div class="menuOpener taphoOnlyInline floatright buttonLike" data-state="opened">' . fontawesome('fas fa-times') . '</div></div>
			<ul class="dropmenu" id="menu_nav">';

	$menu_icons = array(
		'home' => empty($settings['home_icon']) ? 'fa-home' : $settings['home_icon'],
		'help' => empty($settings['help_icon']) ? 'fa-info' : $settings['help_icon'],
		'search' => empty($settings['search_icon']) ? 'fa-search' : $settings['search_icon'],
		'admin' => empty($settings['admin_icon']) ? 'fa-key' : $settings['admin_icon'],
		'moderate' => empty($settings['moderate_icon']) ? 'fa-pencil-alt' : $settings['moderate_icon'],
		'profile' => empty($settings['profile_icon']) ? 'fa-user' : $settings['profile_icon'],
		'pm' => empty($settings['pm_icon']) ? 'fa-envelope' : $settings['pm_icon'],
		'calendar' => empty($settings['calendar_icon']) ? 'fa-calendar-alt' : $settings['calendar_icon'],
		'mlist' => empty($settings['mlist_icon']) ? 'fa-users' : $settings['mlist_icon'],
		'logout' => empty($settings['logout_icon']) ? 'fa-sign-out-alt' : $settings['logout_icon'],
		'login' => empty($settings['login_icon']) ? 'fa-sign-in-alt' : $settings['login_icon'],
		'register' => empty($settings['register_icon']) ? 'fa-user-plus' : $settings['register_icon'],
	);
	foreach ($context['menu_buttons'] as $act => $button)
	{
		if($act == "logout" || $act == "login" || $act == "register")
			continue;

		$icon = '';
		if (!empty($settings[$act . '_icon']))
			$icon = $settings[$act . '_icon'];

		echo '
				<li id="button_', $act, '">
					<a class="', $button['active_button'] ? 'active ' : '', 'firstlevel" href="', $button['href'], '"', isset($button['target']) ? ' target="' . $button['target'] . '"' : '', '>
						', fontawesome($icon, !empty($menu_icons[$act]) ? $menu_icons[$act] : 'fas fa-chevron-circle-right'), '
						<span class="', isset($button['is_last']) ? 'last ' : '', 'firstlevel">', $button['title'], '</span>
						', !empty($button['sub_buttons']) ? '<div class="taphoOnlyInline floatright"><i class="fas fa-caret-down" style="padding:8px"></i></div>' : '', '
					</a>';

		if (!empty($button['sub_buttons']))
		{
			echo '
					<ul>';

			foreach ($button['sub_buttons'] as $childbutton)
			{
				echo '
						<li>
							<a href="', $childbutton['href'], '"', isset($childbutton['target']) ? ' target="' . $childbutton['target'] . '"' : '', '>
								<span', isset($childbutton['is_last']) ? ' class="last"' : '', '>', $childbutton['title'], !empty($childbutton['sub_buttons']) ? '...' : '', '</span>
							</a>';

				// 3rd level menus :)
				if (!empty($childbutton['sub_buttons']))
				{
					echo '
							<ul>';

					foreach ($childbutton['sub_buttons'] as $grandchildbutton)
						echo '
								<li>
									<a href="', $grandchildbutton['href'], '"', isset($grandchildbutton['target']) ? ' target="' . $grandchildbutton['target'] . '"' : '', '>
										<span', isset($grandchildbutton['is_last']) ? ' class="last"' : '', '>', $grandchildbutton['title'], '</span>
									</a>
								</li>';

					echo '
							</ul>';
				}

				echo '
						</li>';
			}
				echo '
					</ul>';
		}
		echo '
				</li>';
	}

	echo '
			</ul>
		</nav>';
}

// Generate a strip of buttons.
function template_button_strip($button_strip, $direction = 'top', $strip_options = array())
{
	global $settings, $context, $txt, $scripturl;

	if (!is_array($strip_options))
		$strip_options = array();

	// List the buttons in reverse order for RTL languages.
	if ($context['right_to_left'])
		$button_strip = array_reverse($button_strip, true);

	// Create the buttons...
	$buttons = array();
	foreach ($button_strip as $key => $value)
	{
		if (!isset($value['test']) || !empty($context[$value['test']]))
			$buttons[] = '
				<li><a' . (isset($value['id']) ? ' id="button_strip_' . $value['id'] . '"' : '') . ' class="button_strip_' . $key . (isset($value['active']) ? ' active' : '') . '" href="' . $value['url'] . '"' . (isset($value['custom']) ? ' ' . $value['custom'] : '') . '><span>' . $txt[$value['text']] . '</span></a></li>';
	}

	// No buttons? No button strip either.
	if (empty($buttons))
		return;

	// Make the last one, as easy as possible.
	$buttons[count($buttons) - 1] = str_replace('<span>', '<span class="last">', $buttons[count($buttons) - 1]);

	echo '
		<div class="buttonlist', !empty($direction) ? ' float' . $direction : '', '"', (empty($buttons) ? ' style="display: none;"' : ''), (!empty($strip_options['id']) ? ' id="' . $strip_options['id'] . '"': ''), '>
			<ul>',
				implode('', $buttons), '
			</ul>
		</div>';
}

// Special strip of buttons :), don't judge me
function template_button_strip_special($button_strip, $direction = 'top', $strip_options = array())
{
	global $settings, $context, $txt, $scripturl;

	if (!is_array($strip_options))
		$strip_options = array();

	// List the buttons in reverse order for RTL languages.
	if ($context['right_to_left'])
		$button_strip = array_reverse($button_strip, true);

	// Create the buttons...
	$buttons = array();
	foreach ($button_strip as $key => $value)
	{
		if (!isset($value['test']) || !empty($context[$value['test']]))
			$buttons[] = '
				<li><a' . (isset($value['id']) ? ' id="button_strip_' . $value['id'] . '"' : '') . ' title="' . $txt[$value['text']] . '" class="tooltip button_strip_' . $key . (isset($value['active']) ? ' active' : '') . '" href="' . $value['url'] . '"' . (isset($value['custom']) ? ' ' . $value['custom'] : '') . '><span>' . $txt[$value['text']] . '</span></a></li>';
	}

	// No buttons? No button strip either.
	if (empty($buttons))
		return;

	// Make the last one, as easy as possible.
	$buttons[count($buttons) - 1] = str_replace('<span>', '<span class="last">', $buttons[count($buttons) - 1]);

	echo '
		<div class="specialBL buttonlist', !empty($direction) ? ' float' . $direction : '', '"', (empty($buttons) ? ' style="display: none;"' : ''), (!empty($strip_options['id']) ? ' id="' . $strip_options['id'] . '"': ''), '>
			<ul>',
				implode('', $buttons), '
			</ul>
		</div>';
}

/**
 * @param string $key fontawesome icon full name
 * @param string $default
 */
function fontawesome($key = '', $default = null)
{
	return '<i class="' . faIcon($key, $default) . '"></i>';
}

/**
 * @param string $key fontawesome icon full name
 * @param string $default
 */
function faIcon($key = '', $default = null)
{
	if (empty($key) && !empty($default))
		return faIcon($default);

	$types = array(
		'fab', 'fas', 'far', 'fal'
	);
	$def_type = $types[1];
	$prefixed = false;

	foreach ($types as $type)
		$prefixed |= strpos($key, $type) !== false;

	if (!$prefixed && !empty($key))
		$key = $def_type . ' ' . $key;

	return $key;
}

function sycho_credits()
{
	global $context, $settings, $txt;

	echo '
	<div class="cat_bar">
		<h3 class="catbg">
			<span class="ie6_header floatleft">' . fontawesome('fas fa-smile') . '    ', $txt['sunrise_skidayo'], '</span>
		</h3>
	</div>
	<div class="windowbg2">
		<div class="content">
			<div class="sycho_credits">
				<img src="', $settings['theme_url'], '/images/custom/SunRise', $context['sr_mode']['value']==1 ? '_midnight' : '', '.png" style="float: right;max-width: 100%;height:50px">
				<font style="color:red">' . fontawesome('fas fa-heart') . '</font>    ', $txt['sunrise_support'], '<br />
				<font style="color:#ff6195">' . fontawesome('fas fa-comments') . '</font>    ', $txt['sunrise_qs'], '<br />
				<font style="color:orange">' . fontawesome('fas fa-user') . '</font>    ', $txt['sunrise_custom'], '<br />
				<font style="color:lime">' . fontawesome('fas fa-globe-africa') . '</font>    ', $txt['translation_by'], ':<br/>
				<ul>';

	foreach($settings['translations'] as $translation)
	{
		echo '
					<li>
						<img src="', $settings['images_url'], '/custom/', $translation['code'], '.png" alt="', $translation['code'], '"/>   ', $translation['lang'], ' <a href="', $translation['author']['link'], '" target="_blank">', $translation['author']['name'], '</a>
					</li>';
	}

		echo'
				</ul>
				<font color="#7289da">' . fontawesome('fab fa-discord') . '</font>    ' . $txt['join_us_on_discord'] . '
			</div>
		</div>
	</div>';
}