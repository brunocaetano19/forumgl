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
function template_main()
{
	global $context, $settings, $options, $txt, $scripturl, $modSettings;

	// Random News For Phones&Tablets.
	if ($settings['show_newsfader'] && !empty($context['fader_news_lines']) && !empty($settings['news_boardindex_only']))
	{
		echo'
		<div class="news_marquee" style="margin-bottom: 2em;">
			<i class="fas fa-info"></i>
			<div class="newslines"><div class="marquee">';

		foreach ($context['news_lines'] as $news) {
			echo'', $news, '           <i class="fas fa-circle"></i>           ';
		}

		echo'</div></div>
		</div>
		<script>jq(\'.marquee\').marquee({
			//speed in milliseconds of the marquee
			duration: ', empty($settings['newsmarquee_time']) ? '14000' : $settings['newsmarquee_time'], ',  pauseOnHover: true
		});</script>';
	}

	// Show some statistics if stat info is off.
	if (!$settings['show_stats_index'])
		echo '
	<div id="index_common_stats" class="taphoNone">
		<div class="layr">
			<div class="blck"><i class="fas fa-user-friends"></i><strong class="tppi">', $context['common_stats']['total_members'], '</strong><span class="phoneNone">', $txt['members'], '</span></div>
		</div>
		<div class="layr">
			<div class="blck"><i class="fas fa-comments"></i><strong class="tppi">', $context['common_stats']['total_posts'], '</strong><span class="phoneNone">', $txt['posts_made'], '</span></div>
		</div>
		<div class="layr">
			<div class="blck"><i class="fas fa-copy"></i><strong class="tppi">', $context['common_stats']['total_topics'], '</strong><span class="phoneNone">', $txt['topics'], '</span></div>
		</div>
		', ($settings['show_latest_member'] ? '
		<div class="layr phoneNone">
			<div class="blck"><i class="fas fa-user-plus"></i><strong class="tppi">' . $context['common_stats']['latest_member']['link'] . '</strong>' . $txt['latest_member'] . '</div>
		</div>' : '') , '
	</div>';

	echo '
	<div id="boardindex_table">
		<table class="table_list">';

	/* Each category in categories is made up of:
	id, href, link, name, is_collapsed (is it collapsed?), can_collapse (is it okay if it is?),
	new (is it new?), collapse_href (href to collapse/expand), collapse_image (up/down image),
	and boards. (see below.) */
	foreach ($context['categories'] as $category)
	{
		// If theres no parent boards we can see, avoid showing an empty category (unless its collapsed)
		if (empty($category['boards']) && !$category['is_collapsed'])
			continue;

		echo '
			<tbody class="header" id="category_', $category['id'], '">
				<tr>
					<td colspan="4">
						<div class="cat_bar">
							<h3 class="catbg ', $category['is_collapsed'] ? 'colpst' : 'expnt', '">';

		// If this category even can collapse, show a link to collapse it.
		if ($category['can_collapse'])
			echo '
								<a class="collapse buttonLike icon_style tooltip" title="', $category['is_collapsed'] ? $txt['expand'] : $txt['collapse'], '" href="', $category['collapse_href'], '">', $category['is_collapsed'] ? '<i class="fas fa-plus"></i>' : '<i class="fas fa-minus"></i>', '</a>';

		echo '
								<a class="tooltip" title="', $txt['view_unread_category'], '" href="', $scripturl, '?action=unread;c=', $category['id'], '">', $category['name'], '</a>
							</h3>
						</div>
					</td>
				</tr>
			</tbody>';

		// Assuming the category hasn't been collapsed...
		if (!$category['is_collapsed'])
		{

		echo '
			<tbody class="content" id="category_', $category['id'], '_boards">';
			/* Each board in each category's boards has:
			new (is it new?), id, name, description, moderators (see below), link_moderators (just a list.),
			children (see below.), link_children (easier to use.), children_new (are they new?),
			topics (# of), posts (# of), link, href, and last_post. (see below.) */
			$alter = true;
			foreach ($category['boards'] as $board)
			{
				echo '
				<tr id="board_', $board['id'], '" class="windowbg', $alter ? 2 : '', ' ', $board['is_redirect'] ? 'redirect' : '', '">
					<td class="icon ', ($board['new'] || $board['children_new']) ? 'new_posts' : '', '">
						<a href="', ($board['is_redirect'] || $context['user']['is_guest'] ? $board['href'] : $scripturl . '?action=unread;board=' . $board['id'] . '.0;children'), '">';

				// If the board or children is new, show an indicator.
				if ($board['new'] || $board['children_new'])
					echo '
							<img src="', $settings['images_url'], '/', $context['theme_variant_url'], 'on', $board['new'] ? '' : '2', '.png" alt="', $txt['new_posts'], '" class="tooltip" title="', $txt['new_posts'], '" />';
				// Is it a redirection board?
				elseif ($board['is_redirect'])
					echo '
							<img src="', $settings['images_url'], '/', $context['theme_variant_url'], 'redirect.png" alt="*" title="*" />';
				// No new posts at all! The agony!!
				else
					echo '
							<img src="', $settings['images_url'], '/', $context['theme_variant_url'], 'off.png" alt="', $txt['old_posts'], '" title="', $txt['old_posts'], '" />';

				echo '
						</a>
					</td>
					<td class="info">
						<a class="subject" href="', $board['href'], '" id="b', $board['id'], '">', $board['name'], '</a>';

				// Has it outstanding posts for approval?
				if ($board['can_approve_posts'] && ($board['unapproved_posts'] || $board['unapproved_topics']))
					echo '
						<a href="', $scripturl, '?action=moderate;area=postmod;sa=', ($board['unapproved_topics'] > 0 ? 'topics' : 'posts'), ';brd=', $board['id'], ';', $context['session_var'], '=', $context['session_id'], '" title="', sprintf($txt['unapproved_posts'], $board['unapproved_topics'], $board['unapproved_posts']), '" class="moderation_link tooltip newNumber">!</a>';

				echo '

						<p class="bdcrp">', $board['description'] , '</p>';

				// Show the "Moderators: ". Each has name, href, link, and id. (but we're gonna use link_moderators.)
				if (!empty($board['moderators']))
					echo '
						<span class="moderators smalltext">', count($board['moderators']) == 1 ? '<i class="fas fa-user"></i>    '.$txt['moderator'] : '<i class="fas fa-users"></i>    '.$txt['moderators'], ': ', implode(', ', $board['link_moderators']), '</span>';

				// Show the "Child Boards: ". (there's a link_children but we're going to bold the new ones...)
				if (!empty($board['children']))
				{
					// Sort the links into an array with new boards bold so it can be imploded.
					$children = array();
					/* Each child in each board's children has:
							id, name, description, new (is it new?), topics (#), posts (#), href, link, and last_post. */
					foreach ($board['children'] as $child)
					{
						if (!$child['is_redirect'])
							$child['link'] = '<a href="' . $child['href'] . '" class="' . ($child['new'] ? 'new_posts ' : '') . 'tooltip" title="' . ($child['new'] ? $txt['new_posts'] : $txt['old_posts']) . ' (' . $txt['board_topics'] . ': ' . comma_format($child['topics']) . ', ' . $txt['posts'] . ': ' . comma_format($child['posts']) . ')">' . $child['name'] . '</a>';
						else
							$child['link'] = '<a href="' . $child['href'] . '" class="tooltip " title="' . comma_format($child['posts']) . ' ' . $txt['redirects'] . '">' . $child['name'] . '</a>';

						// Has it posts awaiting approval?
						if ($child['can_approve_posts'] && ($child['unapproved_posts'] || $child['unapproved_topics']))
							$child['link'] .= ' <a href="' . $scripturl . '?action=moderate;area=postmod;sa=' . ($child['unapproved_topics'] > 0 ? 'topics' : 'posts') . ';brd=' . $child['id'] . ';' . $context['session_var'] . '=' . $context['session_id'] . '" title="' . sprintf($txt['unapproved_posts'], $child['unapproved_topics'], $child['unapproved_posts']) . '" class="tooltip moderation_link newNumber">!</a>';

						$children[] = $child['new'] ? '<strong>' . $child['link'] . '</strong>' : $child['link'];
					}
					echo '
					<div class="child_boards">
						<i class="fas fa-angle-double-right"></i>    ', implode(' ', $children), '
					</div>';
				}

				// Show some basic information about the number of posts, etc.
					echo '
					</td>
					<td class="stats">';
					if($board['is_redirect'])
						echo'
						<span class=""><span>', comma_format($board['posts']), '</span>    <i class="fas fa-share-square"></i></span>';
					else
						echo'
						<span class=""><span>', comma_format($board['posts']), '</span>    <i class="fas fa-comments"></i></span><span class=""><span>', comma_format($board['topics']), '</span>    <i class="fas fa-file-alt"></i></span>';
					echo'
					</td>
					<td class="lastpost">';

				/* The board's and children's 'last_post's have:
				time, timestamp (a number that represents the time.), id (of the post), topic (topic id.),
				link, href, subject, start (where they should go for the first unread post.),
				and member. (which has id, name, link, href, username in it.) */
				if (!empty($board['last_post']['id']))
					echo '
						<span style="display:block;" class=""><i class="fas fa-user-edit"></i>    <strong>', $txt['last_post'], '</strong>  ', $txt['by'], ' ', $board['last_post']['member']['link'] , '<br />
						<i class="fas fa-folder"></i>     ', $board['last_post']['link'], '<br />
						<i class="fas fa-clock"></i>     ', $board['last_post']['time'],'
						</span>';
				echo '
					</td>
				</tr>';
				$alter = !$alter;
			}
		echo '
			</tbody>';
		}
		echo '
			<tbody class="divider">
				<tr>
					<td colspan="4"></td>
				</tr>
			</tbody>';
	}
	echo '
		</table>
	</div>';

	if ($context['user']['is_logged'])
	{
		echo '
	<div id="posting_icons" class="floatleft">';

		// Mark read button.
		$mark_read_button = array(
			'markread' => array('text' => 'mark_as_read', 'image' => 'markread.gif', 'lang' => true, 'url' => $scripturl . '?action=markasread;sa=all;' . $context['session_var'] . '=' . $context['session_id']),
		);

		echo '
		<ul class="reset">
			<li class="floatleft"><img src="', $settings['images_url'], '/', $context['theme_variant_url'], 'new_some.png" alt="" /> ', $txt['new_posts'], '</li>
			<li class="floatleft"><img src="', $settings['images_url'], '/', $context['theme_variant_url'], 'new_none.png" alt="" /> ', $txt['old_posts'], '</li>
			<li class="floatleft"><img src="', $settings['images_url'], '/', $context['theme_variant_url'], 'new_redirect.png" alt="" /> ', $txt['redirect_board'], '</li>
		</ul>
	</div>';

		// Show the mark all as read button?
		if ($settings['show_mark_read'] && !empty($context['categories']))
			echo '<div class="mark_read">', template_button_strip($mark_read_button, 'right'), '</div>';
	}
	else
	{
		echo '
	<div id="posting_icons" class="flow_hidden">
		<ul class="reset">
			<li class="floatleft"><img src="', $settings['images_url'], '/new_none.png" alt="" /> ', $txt['old_posts'], '</li>
			<li class="floatleft"><img src="', $settings['images_url'], '/new_redirect.png" alt="" /> ', $txt['redirect_board'], '</li>
		</ul>
	</div>';
	}

	template_info_center();
}

function template_info_center()
{
	global $context, $settings, $options, $txt, $scripturl, $modSettings, $memberContext;

	// Here's where the "Info Center" starts...
	echo '
	<span class="clear upperframe"><span></span></span>
	<div class="roundframe bi" style="background-color: transparent"><div class="innerframe">';
		echo'
		<div id="upshrinkHeaderIC"', empty($options['collapse_header_ic']) ? '' : ' style="display: none;"', '>
		<div class="boardindex_block users_online" ', !(!empty($settings['number_recent_posts']) && (!empty($context['latest_posts']) || !empty($context['latest_post']))) ? 'style="width:100%;float:none;"' : '', '>';

	// Show information about events, birthdays, and holidays on the calendar.
	if ($context['show_calendar'])
	{
		echo '
		<div style="margin:2px">
			<div class="title_barIC">
				<h4 class="titlebg">
					<span class="ie6_header floatleft">
						<i class="fas fa-calendar-alt"></i>    ', $context['calendar_only_today'] ? $txt['calendar_today'] : $txt['calendar_upcoming'], '
					</span>
				</h4>
			</div>
			<div class="quan">
				<p class="smalltext">';

			// Holidays like "Christmas", "Chanukah", and "We Love [Unknown] Day" :P.
			if (!empty($context['calendar_holidays']))
					echo '
					<span class="holiday">', $txt['calendar_prompt'], ' ', implode(', ', $context['calendar_holidays']), '</span><br />';

			// People's birthdays. Like mine. And yours, I guess. Kidding.
			if (!empty($context['calendar_birthdays']))
			{
					echo '
					<span class="birthday">', $context['calendar_only_today'] ? $txt['birthdays'] : $txt['birthdays_upcoming'], '</span> ';
			/* Each member in calendar_birthdays has:
					id, name (person), age (if they have one set?), is_last. (last in list?), and is_today (birthday is today?) */
			foreach ($context['calendar_birthdays'] as $member)
					echo '
					<a href="', $scripturl, '?action=profile;u=', $member['id'], '">', $member['is_today'] ? '<strong>' : '', $member['name'], $member['is_today'] ? '</strong>' : '', isset($member['age']) ? ' (' . $member['age'] . ')' : '', '</a>', $member['is_last'] ? '<br />' : ', ';
			}
			// Events like community get-togethers.
			if (!empty($context['calendar_events']))
			{
				echo '
					<span class="event">', $context['calendar_only_today'] ? $txt['events'] : $txt['events_upcoming'], '</span> ';
				/* Each event in calendar_events should have:
						title, href, is_last, can_edit (are they allowed?), modify_href, and is_today. */
				foreach ($context['calendar_events'] as $event)
					echo '
						', $event['can_edit'] ? '<a href="' . $event['modify_href'] . '" class="tooltip" title="' . $txt['calendar_edit'] . '"><img src="' . $settings['images_url'] . '/icons/modify_small.gif" alt="*" /></a> ' : '', $event['href'] == '' ? '' : '<a href="' . $event['href'] . '">', $event['is_today'] ? '<strong>' . $event['title'] . '</strong>' : $event['title'], $event['href'] == '' ? '' : '</a>', $event['is_last'] ? '<br />' : ', ';
			}
			echo '
				</p>
			</div>
		</div>';
	}

	// "Users online" - in order of activity.
	echo '
		<div style="margin:2px">
			<div class="title_barIC">
				<h4 class="titlebg">
					<span class="ie6_header floatleft">
						<i class="fas fa-users"></i>    ', $txt['online_users'], '  <i class="smalltext">(', sprintf($txt['users_active'], $modSettings['lastActive']), ')</i>
					</span>
				</h4>
			</div>
			<div class="quan">
				<p class="inline stats">
					', $context['show_who'] ? '<a href="' . $scripturl . '?action=who">' : '', comma_format($context['num_guests']), ' ', $context['num_guests'] == 1 ? $txt['guest'] : $txt['guests'], ', ' . comma_format($context['num_users_online']), ' ', $context['num_users_online'] == 1 ? $txt['user'] : $txt['users'];

		// Handle hidden users and buddies.
		$bracketList = array();
		if ($context['show_buddies'])
			$bracketList[] = comma_format($context['num_buddies']) . ' ' . ($context['num_buddies'] == 1 ? $txt['buddy'] : $txt['buddies']);
		if (!empty($context['num_spiders']))
			$bracketList[] = comma_format($context['num_spiders']) . ' ' . ($context['num_spiders'] == 1 ? $txt['spider'] : $txt['spiders']);
		if (!empty($context['num_users_hidden']))
			$bracketList[] = comma_format($context['num_users_hidden']) . ' ' . $txt['hidden'];

		if (!empty($bracketList))
			echo ' (' . implode(', ', $bracketList) . ')';

		echo $context['show_who'] ? '</a>' : '', '
				</p>';

		// Assuming there ARE users online... each user in users_online has an id, username, name, group, href, and link.
		if (!empty($context['users_online']))
		{
			echo '<p class="inline smalltext">
					', implode(', ', $context['list_users_online']), '</p>';

			// Showing membergroups?
			if (!empty($settings['show_group_key']) && !empty($context['membergroups'])) {
				echo '<hr class="sycho">
					<div class="group_key"><p class="inline smalltext">' . implode(', ', $context['membergroups']) . '</p></div>';
			}
		}

		echo '
				<span class="smalltext" style="margin: 0;">
					', $txt['most_online_today'], ': <strong>', comma_format($modSettings['mostOnlineToday']), '</strong>.
					', $txt['most_online_ever'], ': ', comma_format($modSettings['mostOnline']), ' (', timeformat($modSettings['mostDate']), ')
				</span>
			</div>
		</div>';

	// If they are logged in, but statistical information is off... show a personal message bar.

		$stats_index_cond1 = !empty($settings['show_latest_member']) ? '<i class="fas fa-user"></i>    <span>'.$txt['latest_member'] . ': <strong> ' . $context['common_stats']['latest_member']['link'] . '</strong></span>' : '';
		$stats_index_cond2 = (!empty($context['latest_post']) ? '<i class="fas fa-clock"></i>    <span>'.$txt['latest_post'] . ': <strong>&quot;' . $context['latest_post']['link'] . '&quot;</strong>  ( ' . $context['latest_post']['time'] . ' )<br /></span>' : '');
		$stats_index_cond3 = $context['show_stats'] ? '<a class="buttonLike  " href="' . $scripturl . '?action=stats">' . str_replace("[", "", str_replace("]", "", $txt['more_stats'])) . '</a>' : '';
		$stats_index = '
		<div style="margin:2px">
			<div class="title_barIC">
				<h4 class="titlebg">
					<span class="ie6_header floatleft">
						<i class="fas fa-chart-pie"></i>    '.$txt['forum_stats'].'
					</span>
				</h4>
			</div>
			<div class="quan">
				<ul class="stats_index_items">
					<li><i class="fas fa-file-alt"></i>    <span><b>'.$context['common_stats']['total_posts'].'</b> '.$txt['posts_made'].' '.$txt['in'].' <b>'.$context['common_stats']['total_topics'].'</b> '.$txt['topics'].' '.$txt['by'].' <b>'.$context['common_stats']['total_members'].'</b> '.$txt['members'].'. </span></li>
					<li>'.$stats_index_cond1.'</li>
					<li>'.$stats_index_cond2.'</li>
				</ul>
				<a class="buttonLike  " href="'.$scripturl.'?action=recent">'.$txt['recent_view'].'</a>
				'.$stats_index_cond3.'
			</div>
		</div>';

	// Show statistical style information...
	if ($settings['show_stats_index'])
	{
		echo $stats_index;
	}
	// If they are logged in, but statistical information is off... show a personal message bar.
	if ($context['user']['is_logged'] && !$settings['show_stats_index'])
	{
		echo '
		<div style="margin:2px">
			<div class="title_barIC">
				<h4 class="titlebg">
					<span class="ie6_header floatleft">
						', $context['allow_pm'] ? '<a href="' . $scripturl . '?action=pm">' : '', '<i class="fas fa-envelope"></i>    ', $context['allow_pm'] ? '</a>' : '', '
						<span>', $txt['personal_message'], '</span>
					</span>
				</h4>
			</div>
			<div class="quan">
				<p class="pminfo">
					<strong><a href="', $scripturl, '?action=pm">', $txt['personal_message'], '</a></strong>
					<span class="smalltext">
						', $txt['you_have'], ' ', comma_format($context['user']['messages']), ' ', $context['user']['messages'] == 1 ? $txt['message_lowercase'] : $txt['msg_alert_messages'], '.... ', $txt['click'], ' <a href="', $scripturl, '?action=pm">', $txt['here'], '</a> ', $txt['to_view'], '
					</span>
				</p>
			</div>
		</div>';
	}
	echo'</div>';

	// This is the "Recent Posts" bar.
	if (!empty($settings['number_recent_posts']) && (!empty($context['latest_posts']) || !empty($context['latest_post'])))
	{

		echo '
		<div class="boardindex_block recent_posts"><div style="margin:2px">
			<div class="title_barIC">
				<h4 class="titlebg">
					<span class="ie6_header floatleft">
						<a href="', $scripturl, '?action=recent"><i class="fas fa-copy"></i></a>
						', $txt['recent_posts'], '
					</span>
				</h4>
			</div>';

        echo'
				<div class="topic_table borderLess">
					<table>
						<tbody>';

        $poster_ids = array();
        foreach ($context['latest_posts'] as $post)
        {
            if (!empty($post['poster']['id']))
                $poster_ids[] = $post['poster']['id'];
        }

        loadMemberData($poster_ids);

        $alter = true;
        foreach ($context['latest_posts'] as $post)
        {
            $last_poster = $post['poster']['id'];

            if (!empty($last_poster))
            {
                // Get The Avatar the easy Way
                loadMemberContext($last_poster);

                $ava = !empty($memberContext[$last_poster]['avatar']['image']) ? $memberContext[$last_poster]['avatar']['image'] : $settings['sr_default_avatar'];
            }
            else
                $ava = $settings['sr_default_avatar'];

            echo'
                            <tr class="windowbg', $alter ? 2 : '', '">
                                <td width="2%" class="icon2 avatared">', $ava, '</td>
                                <td class="ellipsis">', $post['link'], '<br><span class="smalltext"><i class="fas fa-user"></i>   ', $post['poster']['link'], '  <span class="phoneOnlyInline">&middot;  <i class="fas fa-folder-open"></i>    ', $post['board']['link'], '</span></span></td>
                                <td align="right" class="phoneNone"><i class="fas fa-folder"></i>    ', $post['board']['link'], '<br><span class="smalltext"><i class="fas fa-clock"></i>    ', $post['time'], '</span></td>
                            </tr>';

            $alter = !$alter;
        }

    echo'
						</tbody>
					</table>
				</div>
			</div>
		</div>';
	}


	echo '
		</div>
	</div></div>
	<span class="lowerframe"><span></span></span>';

	// Info center collapse object.
	echo '
	<script><!-- // --><![CDATA[
		var oInfoCenterToggle = new smc_Toggle({
			bToggleEnabled: true,
			bCurrentlyCollapsed: ', empty($options['collapse_header_ic']) ? 'false' : 'true', ',
			aSwappableContainers: [
				\'upshrinkHeaderIC\'
			],
			aSwapImages: [
				{
					sId: \'upshrink_ic\',
					srcExpanded: smf_images_url + \'/collapse.gif\',
					altExpanded: ', JavaScriptEscape($txt['upshrink_description']), ',
					srcCollapsed: smf_images_url + \'/expand.gif\',
					altCollapsed: ', JavaScriptEscape($txt['upshrink_description']), '
				}
			],
			oThemeOptions: {
				bUseThemeSettings: ', $context['user']['is_guest'] ? 'false' : 'true', ',
				sOptionName: \'collapse_header_ic\',
				sSessionVar: ', JavaScriptEscape($context['session_var']), ',
				sSessionId: ', JavaScriptEscape($context['session_id']), '
			},
			oCookieOptions: {
				bUseCookie: ', $context['user']['is_guest'] ? 'true' : 'false', ',
				sCookieName: \'upshrinkIC\'
			}
		});
	// ]]></script>';
}
?>