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
// The only template in the file.
function template_main()
{
	global $context, $settings, $options, $scripturl, $txt;

	// Display the table header and linktree.
	echo '
	<div class="main_section" id="whos_online">
		<form action="', $scripturl, '?action=who" method="post" id="whoFilter" accept-charset="', $context['character_set'], '">
			<div class="title_bar">
				<h4 class="titlebg margin_lower">', $txt['who_title'], '</h4>
			</div>
			<div class="topic_table" id="mlist">
				<div class="pagesection">
					<div class="pagelinks floatleft">', $txt['pages'], ': ', $context['page_index'], '</div>';
		echo '
					<div class="selectbox floatright">', $txt['who_show1'], '
						<select name="show_top" onchange="document.forms.whoFilter.show.value = this.value; document.forms.whoFilter.submit();">';

		foreach ($context['show_methods'] as $value => $label)
			echo '
							<option value="', $value, '" ', $value == $context['show_by'] ? ' selected="selected"' : '', '>', $label, '</option>';
		echo '
						</select>
						<noscript>
							<input type="submit" name="submit_top" value="', $txt['go'], '" class="button_submit" />
						</noscript>
					</div>
				</div>
				<table class="table_grid" cellspacing="0">
					<thead>
						<tr class="catbg">
							<th scope="col" class="lefttext first_th" width="40%"><a href="', $scripturl, '?action=who;start=', $context['start'], ';show=', $context['show_by'], ';sort=user', $context['sort_direction'] != 'down' && $context['sort_by'] == 'user' ? '' : ';asc', '" rel="nofollow">', $txt['who_user'], ' ', $context['sort_by'] == 'user' ? '<img src="' . $settings['images_url'] . '/sort_' . $context['sort_direction'] . '.gif" alt="" />' : '', '</a></th>
							<th scope="col" class="lefttext phoneNone" width="10%"><a href="', $scripturl, '?action=who;start=', $context['start'], ';show=', $context['show_by'], ';sort=time', $context['sort_direction'] == 'down' && $context['sort_by'] == 'time' ? ';asc' : '', '" rel="nofollow">', $txt['who_time'], ' ', $context['sort_by'] == 'time' ? '<img src="' . $settings['images_url'] . '/sort_' . $context['sort_direction'] . '.gif" alt="" />' : '', '</a></th>
							<th scope="col" class="lefttext last_th phoneNone" width="50%">', $txt['who_action'], '</th>
						</tr>
					</thead>
					<tbody>';

	// For every member display their name, time and action (and more for admin).
	$alternate = 0;

	foreach ($context['members'] as $member)
	{
		// $alternate will either be true or false. If it's true, use "windowbg2" and otherwise use "windowbg".
		echo '
						<tr class="windowbg', $alternate ? '2' : '', '">
							<td>';

		// Guests don't have information like icq, msn, y!, and aim... and they can't be messaged.
		if (!$member['is_guest'])
		{
			echo '
								<span class="contact_info floatright">
									', $context['can_send_pm'] ? '<a href="' . $member['online']['href'] . '" title="' . $member['online']['label'] . '">' : '', $settings['use_image_buttons'] ? '<img src="' . $member['online']['image_href'] . '" alt="' . $member['online']['text'] . '" align="bottom" />' : $member['online']['text'], $context['can_send_pm'] ? '</a>' : '', '
									', isset($context['disabled_fields']['icq']) ? '' : $member['icq']['link'] , ' ', isset($context['disabled_fields']['msn']) ? '' : $member['msn']['link'], ' ', isset($context['disabled_fields']['yim']) ? '' : $member['yim']['link'], ' ', isset($context['disabled_fields']['aim']) ? '' : $member['aim']['link'], '
								</span>';
		}

		echo '
								<span class="member', $member['is_hidden'] ? ' hidden' : '', '">
									', $member['is_guest'] ? $member['name'] : '<a href="' . $member['href'] . '" title="' . $txt['profile_of'] . ' ' . $member['name'] . '"' . (empty($member['color']) ? '' : ' style="color: ' . $member['color'] . '"') . '>' . $member['name'] . '</a>', '
								</span>';

		if (!empty($member['ip']))
			echo '
								(<a href="' . $scripturl . '?action=', ($member['is_guest'] ? 'trackip' : 'profile;area=tracking;sa=ip;u=' . $member['id']), ';searchip=' . $member['ip'] . '">' . $member['ip'] . '</a>)';

		echo '					<span class="phoneOnly">', $member['time'], ' / ', $member['action'], '</span>
							</td>
							<td nowrap="nowrap" class="phoneNone">', $member['time'], '</td>
							<td class="phoneNone">', $member['action'], '</td>
						</tr>';

		// Switch alternate to whatever it wasn't this time. (true -> false -> true -> false, etc.)
		$alternate = !$alternate;
	}

	// No members?
	if (empty($context['members']))
	{
		echo '
						<tr class="windowbg2">
							<td colspan="3" align="center">
							', $txt['who_no_online_' . ($context['show_by'] == 'guests' || $context['show_by'] == 'spiders' ? $context['show_by'] : 'members')], '
							</td>
						</tr>';
	}

	echo '
					</tbody>
				</table>
			</div>
			<div class="pagesection">
				<div class="pagelinks floatleft">', $txt['pages'], ': ', $context['page_index'], '</div>';

	echo '
				<div class="selectbox floatright">', $txt['who_show1'], '
					<select name="show" onchange="document.forms.whoFilter.submit();">';

	foreach ($context['show_methods'] as $value => $label)
		echo '
						<option value="', $value, '" ', $value == $context['show_by'] ? ' selected="selected"' : '', '>', $label, '</option>';
	echo '
					</select>
					<noscript>
						<input type="submit" value="', $txt['go'], '" class="button_submit" />
					</noscript>
				</div>
			</div>
		</form>
	</div>';
}

function template_credits()
{
	global $context, $txt;

	// The most important part - the credits :P.
	echo '
	<div class="main_section" id="credits">
		<div class="cat_bar">
			<h3 class="catbg">', $txt['credits'], '</h3>
		</div>';

	foreach ($context['credits'] as $section)
	{
		if (isset($section['pretext']))
		echo '
		<div class="windowbg">
			<span class="topslice"><span></span></span>
			<div class="content">
				<p>', $section['pretext'], '</p>
			</div>
			<span class="botslice"><span></span></span>
		</div>';

		if (isset($section['title']))
		echo '
		<div class="cat_bar">
			<h3 class="catbg">', $section['title'], '</h3>
		</div>';

		echo '
		<div class="windowbg2">
			<span class="topslice"><span></span></span>
			<div class="content">
				<dl>';

		foreach ($section['groups'] as $group)
		{
			if (isset($group['title']))
				echo '
					<dt>
						<strong>', $group['title'], '</strong>
					</dt>
					<dd>';

			// Try to make this read nicely.
			if (count($group['members']) <= 2)
				echo implode(' ' . $txt['credits_and'] . ' ', $group['members']);
			else
			{
				$last_peep = array_pop($group['members']);
				echo implode(', ', $group['members']), ' ', $txt['credits_and'], ' ', $last_peep;
			}

			echo '
					</dd>';
		}

		echo '
				</dl>';

		if (isset($section['posttext']))
			echo '
				<p class="posttext">', $section['posttext'], '</p>';

		echo '
			</div>
			<span class="botslice"><span></span></span>
		</div>';
	}

	echo '
		<div class="cat_bar">
			<h3 class="catbg">', $txt['credits_copyright'], '</h3>
		</div>
		<div class="windowbg">
			<span class="topslice"><span></span></span>
			<div class="content">
				<dl>
					<dt><strong>', $txt['credits_forum'], '</strong></dt>', '
					<dd>', $context['copyrights']['smf'];

	echo '
					</dd>
				</dl>';

	if (!empty($context['copyrights']['mods']))
	{
		echo '
				<dl>
					<dt><strong>', $txt['credits_modifications'], '</strong></dt>
					<dd>', implode('</dd><dd>', $context['copyrights']['mods']), '</dd>
				</dl>';
	}

	echo '
			</div>
			<span class="botslice"><span></span></span>
		</div>
	</div>';
}
?>