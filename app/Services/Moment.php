<?php 

namespace App\Services;

use \DateTime;

class Moment {

	public static function fromNow($dateAsString, $format) {

		$then = DateTime::createFromFormat($format, $dateAsString);
		$now  = new DateTime('now');

		$diff = $then->diff($now);

		if ($then < $now) {
			if ($diff->y > 0)
				return $diff->y . " year" . ($diff->y == 1 ? '' : 's') . " ago.";
			else if ($diff->m > 0) 
				return $diff->m . " month" . ($diff->m == 1 ? '' : 's') . " ago.";
			else if ($diff->d > 0)
				return $diff->d . " day" . ($diff->d == 1 ? '' : 's') . " ago.";
			else if ($diff->h > 0)
				return $diff->h . " hour" . ($diff->h == 1 ? '' : 's') . " ago.";
			else if ($diff->i > 0)
				return "About " . ($diff->i == 1 ? 'a' : $diff->i) . " minute" . ($diff->i == 1 ? '' : 's') . " ago.";
			else if ($diff->s > 0)
				return "A few seconds ago.";
		}
		else {
			if ($diff->y > 0)
				return "In " . $diff->y . " year" . ($diff->y == 1 ? '' : 's') . " from now.";
			else if ($diff->m > 0)
				return "In " . $diff->m . " month" . ($diff->m == 1 ? '' : 's') . " from now.";
			else if ($diff->d > 0)
				return "In " . $diff->d . " day" . ($diff->d == 1 ? '' : 's') . " from now.";
			else if ($diff->i > 0)
				return "In " . $diff->i . " minute" . ($diff->i == 1 ? '' : 's') . " from now.";
			else if ($diff->s > 0)
				return "In " . $diff->s . " second" . ($diff->s == 1 ? '' : 's') . " from now.";
		}

		return "Just now.";
	}

	public static function format($dateAsString, $format, $toFormat) {
		$then = DateTime::createFromFormat($format, $dateAsString);
		if (is_object($then))
			return $then->format($toFormat);
		else
			return "Somethings up with your date.";
	}

}
