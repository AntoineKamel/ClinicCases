<?php
require('../db.php');
include('includes/generate_avatar.php');
include('../lib/php/users/user_data.php');
include('../lib/php/utilities/names.php');
include('../lib/php/utilities/convert_times.php');
include('../lib/php/utilities/format_text.php');

//function to sort the activities array by subkey - date
function sortBySubkey(&$array, $subkey, $sortType = SORT_DESC) {

    foreach ($array as $subarray) {

        $keys[] = $subarray[$subkey];
    }

    array_multisort($keys, $sortType, $array);
}

$username = $_SESSION['login'];

$phpdate = strtotime('-60 days');

$mysqldate = date( 'Y-m-d H:i:s', $phpdate );

//Case notes
$get_notes = $dbh->prepare("SELECT *,cm_case_assignees.id as assign_id,
	cm_case_notes.id as note_id,
	cm_case_assignees.username as assign_user,
	cm_case_notes.username as note_user
	FROM cm_case_assignees,cm_case_notes
	WHERE cm_case_assignees.username = '$username'
	AND cm_case_assignees.status = 'active'
	AND cm_case_notes.case_id = cm_case_assignees.case_id
	AND cm_case_notes.datestamp >= '$mysqldate'");

$get_notes->execute();

$casenotes = $get_notes->fetchAll(PDO::FETCH_ASSOC);

foreach ($casenotes as $note) {
	$activity_type = 'casenote';

	if ($note['note_user'] === $username) {
		$by = 'You';
	} else {
		$by = username_to_fullname($dbh,$note['note_user']);
	}

	$thumb = generate_avatar($dbh,$note['note_user']);
	$action_text = " added a case note to ";
	$casename = case_id_to_casename($dbh,$note['case_id']);
	$time_done = $note['datestamp'];
	$time_formatted = extract_date_time($note['datestamp']);
	$id = $note['note_id'];
	$what = snippet(35,htmlentities($note['description']));
	$follow_url = 'index.php?i=Cases.php#cases/' . $note['case_id'];

	$item = array('activity_type' => $activity_type, 'by' => $by, 'thumb' => $thumb,
		'action_text' => $action_text,'casename' => $casename, 'id' => $id,
		'what' => $what,'follow_url' => $follow_url, 'time_done' => $time_done,
		'time_formatted' => $time_formatted);

	$activities[] = $item;

}

//Get any non-case time
$get_noncase = $dbh->prepare("SELECT * FROM cm_case_notes
	WHERE username = '$username'
	AND case_id = 'NC'
	AND datestamp >= '$mysqldate'");

$get_noncase->execute();

$noncases = $get_noncase->fetchAll(PDO::FETCH_ASSOC);

foreach ($noncases as $noncase) {
	$activity_type = 'non-case';

	$by = 'You';
	$thumb = generate_avatar($dbh,$noncase['username']);
	$action_text = " added non-case activity ";
	$casename = '';
	$time_done = $noncase['datestamp'];
	$time_formatted = extract_date_time($noncase['datestamp']);
	$id = $noncase['id'];
	$what = snippet(35,htmlentities($noncase['description']));
	$follow_url = 'index.php?i=Cases.php#cases/' . $noncase['case_id'];

	$item = array('activity_type' => $activity_type, 'by' => $by, 'thumb' => $thumb,
		'action_text' => $action_text,'casename' => $casename, 'id' => $id,
		'what' => $what,'follow_url' => $follow_url, 'time_done' => $time_done,
		'time_formatted' => $time_formatted);

	$activities[] = $item;

}

//Documents
$get_documents = $dbh->prepare("SELECT *,cm_case_assignees.id as assign_id,
	cm_documents.id as doc_id,
	cm_case_assignees.username as assign_user,
	cm_documents.username as doc_user
	FROM cm_case_assignees,cm_documents
	WHERE cm_case_assignees.username = '$username'
	AND cm_documents.case_id = cm_case_assignees.case_id
	AND cm_documents.date_modified >= '$mysqldate'
	AND cm_documents.name != ''");

$get_documents->execute();

$documents = $get_documents->fetchAll(PDO::FETCH_ASSOC);

foreach ($documents as $document) {
	$activity_type = 'document';

	if ($document['doc_user'] === $username) {
		$by = 'You';
	} else {
		$by = username_to_fullname($dbh,$document['doc_user']);
	}

	$thumb = generate_avatar($dbh,$document['doc_user']);
	$action_text = ' added a document to ';
	$casename = case_id_to_casename($dbh,$document['case_id']);
	$time_done = $document['date_modified'];
	$time_formatted = extract_date_time($document['date_modified']);
	$id = $document['doc_id'];
	$doc_title = htmlentities($document['name']);
	$what = "<a href='#' data-id='" . $id . "' class='doc_view " . $document['extension'] . "'>" . $doc_title . "</a>";
	$follow_url = 'index.php?i=Cases.php#cases/' . $document['case_id'] . '/3';
	//3 indicates third item in nav list

	$item = array('activity_type' => $activity_type, 'by' => $by,'thumb' => $thumb,
		'action_text' => $action_text,'casename' => $casename, 'id' => $id,
		'what' => $what,'follow_url' => $follow_url, 'time_done' => $time_done,
		'time_formatted' => $time_formatted);

	$activities[] = $item;
}

//Cases opening

$get_opened_cases = $dbh->prepare("SELECT *,cm_case_assignees.id as assign_id,
	cm_case_assignees.username as assign_user
	FROM cm_case_assignees,cm
	WHERE cm_case_assignees.username = '$username'
	AND cm.id = cm_case_assignees.case_id
	AND cm.time_opened >= '$mysqldate'");

$get_opened_cases->execute();

$opened = $get_opened_cases->fetchAll(PDO::FETCH_ASSOC);

foreach ($opened as $open) {
	$activity_type = 'opening';

	if ($open['opened_by'] === $username) {
		$by = 'You';
	} else {
		$by = username_to_fullname($dbh,$open['opened_by']);
	}

	$thumb = generate_avatar($dbh,$open['opened_by']);
	$action_text = " opened a case: ";
	$casename = case_id_to_casename($dbh,$open['case_id']);
	$time_done = $open['time_opened'];
	$time_formatted = extract_date_time($open['time_opened']);
	$id = $open['id'];
	$what = snippet(35,$open['notes']);
	$follow_url = 'index.php?i=Cases.php#cases/' . $open['id'];

	$item = array('activity_type' => $activity_type, 'by' => $by, 'thumb' => $thumb,
		'action_text' => $action_text,'casename' => $casename, 'id' => $id,
		'what' => $what,'follow_url' => $follow_url, 'time_done' => $time_done,
		'time_formatted' => $time_formatted);

	$activities[] = $item;

}

//Cases closing

$get_closed_cases = $dbh->prepare("SELECT *,cm_case_assignees.id as assign_id,
	cm_case_assignees.username as assign_user
	FROM cm_case_assignees,cm
	WHERE cm_case_assignees.username = '$username'
	AND cm.id = cm_case_assignees.case_id
	AND cm.time_closed >= '$mysqldate'");

$get_closed_cases->execute();

$closed = $get_closed_cases->fetchAll(PDO::FETCH_ASSOC);

foreach ($closed as $close) {
	$activity_type = 'closing';

	if ($close['closed_by'] === $username) {
		$by = 'You';
	} else {
		$by = username_to_fullname($dbh,$close['closed_by']);
	}

	$thumb = generate_avatar($dbh,$close['closed_by']);
	$action_text = " closed a case: ";
	$casename = case_id_to_casename($dbh,$close['case_id']);
	$time_done = $close['time_closed'];
	$time_formatted = extract_date_time($close['time_closed']);
	$id = $close['id'];
	$what = snippet(35,$close['close_notes']);
	$follow_url = 'index.php?i=Cases.php#cases/' . $close['id'];

	$item = array('activity_type' => $activity_type, 'by' => $by, 'thumb' => $thumb,
		'action_text' => $action_text,'casename' => $casename, 'id' => $id,
		'what' => $what,'follow_url' => $follow_url, 'time_done' => $time_done,
		'time_formatted' => $time_formatted);

	$activities[] = $item;

}

//Case assignments

//Thanks to this gentleman for the query:
//http://stackoverflow.com/questions/9906326/run-a-query-on-a-query-in-mysql

$get_assignments = $dbh->prepare("SELECT assignments_join.*
	FROM cm_case_assignees AS assignments_base
    JOIN cm_case_assignees AS assignments_join ON
    assignments_base.case_id = assignments_join.case_id
	WHERE
    assignments_base.username = '$username' AND
    assignments_join.date_assigned > '$mysqldate'");

$get_assignments->execute();

$assignments = $get_assignments->fetchAll(PDO::FETCH_ASSOC);

foreach ($assignments as $assign) {
	$activity_type = 'assign';

	if ($assign['username'] === $username) {
		$by = 'You were ';
	} else {
		$by = username_to_fullname($dbh,$assign['username']) . ' was ';
	}

	$thumb = generate_avatar($dbh,$assign['username']);
	$action_text = " assigned to a case: ";
	$casename = case_id_to_casename($dbh,$assign['case_id']);
	$time_done = $assign['date_assigned'];
	$time_formatted = extract_date_time($assign['date_assigned']);
	$id = $assign['id'];
	$what = '';
	$follow_url = 'index.php?i=Cases.php#cases/' . $assign['case_id'];

	$item = array('activity_type' => $activity_type, 'by' => $by, 'thumb' => $thumb,
		'action_text' => $action_text,'casename' => $casename, 'id' => $id,
		'what' => $what,'follow_url' => $follow_url, 'time_done' => $time_done,
		'time_formatted' => $time_formatted);

	$activities[] = $item;

}

//Create Events
$get_events = $dbh->prepare("SELECT * FROM cm_events WHERE set_by = :username AND time_added >= :mysqldate");

$data = array('username' => $username, 'mysqldate' => $mysqldate);

$get_events->execute($data);

$events = $get_events->fetchAll(PDO::FETCH_ASSOC);

foreach ($events as $event) {

	$activity_type = "event_create";

	if ($event['set_by'] === $username) {
		$by = 'You';
	} else {
		$by = username_to_fullname($dbh,$assign['username']);
	}

	$thumb = generate_avatar($dbh,$event['set_by']);
	$action_text = " created an event in ";
	$casename = case_id_to_casename($dbh,$event['case_id']);
	$time_done = $event['time_added'];
	$time_formatted = extract_date_time($event['time_added']);
	$id = $event['id'];
	$what = snippet(35,$event['task']);
	$follow_url = 'index.php?i=Cases.php#cases/' . $event['case_id'] . '/4';

	$item = array('activity_type' => $activity_type, 'by' => $by, 'thumb' => $thumb,
		'action_text' => $action_text,'casename' => $casename, 'id' => $id,
		'what' => $what,'follow_url' => $follow_url, 'time_done' => $time_done,
		'time_formatted' => $time_formatted);

	$activities[] = $item;

}

//Assigned to Events
$get_event_assign = $dbh->prepare("SELECT * FROM cm_events,cm_events_responsibles WHERE cm_events.id = cm_events_responsibles.event_id AND cm_events_responsibles.username = :username AND cm_events_responsibles.time_added >= :mysqldate");

$data = array('username' => $username, 'mysqldate' => $mysqldate);

$get_event_assign->execute($data);

$ev_assigns = $get_event_assign->fetchAll(PDO::FETCH_ASSOC);

foreach ($ev_assigns as $e) {

	$activity_type = "event_assign";

	if ($e['username'] === $username) {
		$by = 'You';
	} else {
		$by = username_to_fullname($dbh,$e['username']);
	}

	$thumb = generate_avatar($dbh,$e['username']);
	$action_text = " were assigned to an event in ";
	$casename = case_id_to_casename($dbh,$e['case_id']);
	$time_done = $e['time_added'];
	$time_formatted = extract_date_time($e['time_added']);
	$id = $e['id'];
	$what = snippet(35,$e['task']);
	$follow_url = 'index.php?i=Cases.php#cases/' . $e['case_id'] .'/4';

	$item = array('activity_type' => $activity_type, 'by' => $by, 'thumb' => $thumb,
		'action_text' => $action_text,'casename' => $casename, 'id' => $id,
		'what' => $what,'follow_url' => $follow_url, 'time_done' => $time_done,
		'time_formatted' => $time_formatted);

	$activities[] = $item;
}

//
//Here are special queries for people who open/close cases (admins)
//

//cases that have opened
if ($_SESSION['permissions']['add_cases'] == '1' && $_SESSION['permissions']['view_all_cases'] == '1')
{
	$get_opened_cases = $dbh->prepare("SELECT * FROM cm
		WHERE time_opened >= '$mysqldate'");

	$get_opened_cases->execute();

	$opened = $get_opened_cases->fetchAll(PDO::FETCH_ASSOC);

	foreach ($opened as $open) {
		$activity_type = 'opening';

		if ($open['opened_by'] === $username) {
			$by = 'You';
		} else {
			$by = username_to_fullname($dbh,$open['opened_by']);
		}

		$thumb = generate_avatar($dbh,$open['opened_by']);
		$action_text = " opened a case: ";
		$casename = case_id_to_casename($dbh,$open['id']);
		$time_done = $open['time_opened'];
		$time_formatted = extract_date_time($open['time_opened']);
		$id = $open['id'];
		$what = snippet(35,$open['notes']);
		$follow_url = 'index.php?i=Cases.php#cases/' . $open['id'];

		$item = array('activity_type' => $activity_type, 'by' => $by, 'thumb' => $thumb,
			'action_text' => $action_text,'casename' => $casename, 'id' => $id,
			'what' => $what,'follow_url' => $follow_url, 'time_done' => $time_done,
			'time_formatted' => $time_formatted);

		$activities[] = $item;
	}
}

//cases that have been closed
if ($_SESSION['permissions']['close_cases'] == '1' && $_SESSION['permissions']['view_all_cases'] == '1')
{
	$get_closed_cases = $dbh->prepare("SELECT * FROM cm
	WHERE time_closed >= '$mysqldate'");

	$get_closed_cases->execute();

	$closed = $get_closed_cases->fetchAll(PDO::FETCH_ASSOC);

	foreach ($closed as $close) {
		$activity_type = 'closing';

		if ($close['closed_by'] === $username) {
			$by = 'You';
		} else {
			$by = username_to_fullname($dbh,$close['closed_by']);
		}

		$thumb = generate_avatar($dbh,$close['closed_by']);
		$action_text = " closed a case: ";
		$casename = case_id_to_casename($dbh,$close['id']);
		$time_done = $close['time_closed'];
		$time_formatted = extract_date_time($close['time_closed']);
		$id = $close['id'];
		$what = snippet(35,$close['close_notes']);
		$follow_url = 'index.php?i=Cases.php#cases/' . $close['id'];

		$item = array('activity_type' => $activity_type, 'by' => $by, 'thumb' => $thumb,
			'action_text' => $action_text,'casename' => $casename, 'id' => $id,
			'what' => $what,'follow_url' => $follow_url, 'time_done' => $time_done,
			'time_formatted' => $time_formatted);

		$activities[] = $item;

		}

}

//new users who have requested access
if ($_SESSION['permissions']['activate_users'] == '1')
{
	$get_new_users = $dbh->prepare("SELECT * FROM cm_users
		WHERE date_created >= '$mysqldate' AND new = 'yes'");

	$get_new_users->execute();

	$news = $get_new_users->fetchAll(PDO::FETCH_ASSOC);

	foreach ($news as $new) {

		$activity_type = 'new_user';
		$by = username_to_fullname($dbh,$new['username']);
		$thumb = 'people/tn_no_picture.png';
		$action_text = " signed up for ClinicCases ";
		$time_done = $new['date_created'];
		$time_formatted = extract_date_time($new['date_created']);
		$what = 'Please review this application.';
		$follow_url = 'index.php?i=Users.php';
		$casename = '(view here)';
		$id = null;

		$item = array('activity_type' => $activity_type, 'by' => $by, 'thumb' => $thumb,
				'action_text' => $action_text,'casename' => $casename, 'id' => $id,
				'what' => $what,'follow_url' => $follow_url, 'time_done' => $time_done,
				'time_formatted' => $time_formatted);

		$activities[] = $item;
	}
}

//So what do admins see?  Any new user signups, any opening or closing of cases,
//and any events that they created or were assigned to
//End queries for admins

//Journal comments
	//select all journals that are newer than start date
	//then take all serialized arrays from comments and add to $activities
if ($_SESSION['permissions']['reads_journals'] == '1' ||
	$_SESSION['permissions']['writes_journals'] == '1')
{
	$get_journals = $dbh->prepare("SELECT * FROM cm_journals WHERE date_added >= '$phpdate'
		AND comments != '' AND (reader LIKE '$username,%' OR reader LIKE '%,$username,%' OR username LIKE '$username'");
	//query is constructed like this so that if there is more than one reader,
	//they get to be notified of the other reader's comments

	$get_journals->execute();

	$journals = $get_journals->fetchAll(PDO::FETCH_ASSOC);

	if (count($journals) > 0)
	{
		foreach ($journals as $j) {

			$c = unserialize($j['comments']);

			foreach ($c as $d) {
				$activity_type = 'new_journal_comment';
				if ($d['by'] === $username)
				{
					$by = 'You';
				}
				else
				{
					$by = username_to_fullname($dbh,$d['by']);
				}
				$thumb = generate_avatar($dbh,$d['by']);
				$action_text = " added a comment to a journal ";
				$time_done = $d['time'];
				$time_formatted = extract_date_time($d['time']);
				$what = snippet(35,strip_tags($d['text']));
				$follow_url = "index.php?i=Journals.php#journals/" . $j['id'];
				$casename = "(view here)";
				$id = null;

				$item = array('activity_type' => $activity_type, 'by' => $by, 'thumb' => $thumb,
				'action_text' => $action_text,'casename' => $casename, 'id' => $id,
				'what' => $what,'follow_url' => $follow_url, 'time_done' => $time_done,
				'time_formatted' => $time_formatted);

				$activities[] = $item;

			}

		}
	}

}

//Journals
if ($_SESSION['permissions']['reads_journals'] == '1' ||
	$_SESSION['permissions']['writes_journals'] == '1')
{
	$get_journals = $dbh->prepare("SELECT * FROM cm_journals WHERE date_added >= '$phpdate'
		AND (reader LIKE '$username,%' OR reader LIKE '%,$username,%' OR username LIKE '$username')");

	$get_journals->execute();

	$journals = $get_journals->fetchAll(PDO::FETCH_ASSOC);

	if (count($journals) > 0)
	{
		foreach ($journals as $j) {
			$activity_type = 'new_journal';
			if ($j['username'] === $username)
			{
				$by = 'You';
			}
			else
			{
				$by = username_to_fullname($dbh,$j['username']);
			}
			$thumb = generate_avatar($dbh,$j['username']);
			$action_text = " submitted a journal ";
			$time_done = $j['date_added'];
			$time_formatted = extract_date_time($j['date_added']);
			$what = snippet(35,strip_tags($j['text']));
			$follow_url = "index.php?i=Journals.php#journals/" . $j['id'];
			$casename = "(view here)";
			$id = null;

			$item = array('activity_type' => $activity_type, 'by' => $by, 'thumb' => $thumb,
			'action_text' => $action_text,'casename' => $casename, 'id' => $id,
			'what' => $what,'follow_url' => $follow_url, 'time_done' => $time_done,
			'time_formatted' => $time_formatted);

			$activities[] = $item;
		}
	}

}


//Add board posts

if ($_SESSION['permissions']['view_board'] == '1')
{
	$this_users_groups = user_which_groups($dbh,$_SESSION['login']);

	$grps = implode("','", $this_users_groups);

	$q = $dbh->prepare("SELECT * FROM `cm_board` as all_posts
	JOIN
	(SELECT * FROM cm_board_viewers WHERE viewer IN ('$grps') GROUP BY cm_board_viewers.post_id) AS  this_user
	ON
	all_posts.id = this_user.post_id AND all_posts.time_added >= '$phpdate'");

	$q->execute();

	$posts = $q->fetchAll(PDO::FETCH_ASSOC);

	foreach ($posts as $post) {

		$activity_type = 'new_board_post';
		if ($post['author'] === $username) {
			$by = 'You';
		} else {
			$by = username_to_fullname($dbh,$post['author']);
		}
		$thumb = generate_avatar($dbh,$post['author']);
		$action_text = " posted on your Board ";
		$time_done = $post['time_added'];
		$time_formatted = extract_date_time($post['time_added']);
		$what = $post['title'];
		$follow_url = 'index.php?i=Board.php';
		$casename = "(view here)";
		$id = null;

		$item = array('activity_type' => $activity_type, 'by' => $by, 'thumb' => $thumb,
				'action_text' => $action_text,'casename' => $casename, 'id' => $id,
				'what' => $what,'follow_url' => $follow_url, 'time_done' => $time_done,
				'time_formatted' => $time_formatted);

		$activities[] = $item;
	}
}

if (!empty($activities)) {
	sortBySubkey($activities,'time_done');
}
