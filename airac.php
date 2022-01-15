<?php
/*
	airac.php
	Handles calendar functions
	Created: 3/2/19
*/

/*
$aac = new airac();
print "Current cycle: " . $aac->current_cycle . "\nCurrent effective date: " . $aac->current_effective . "<br/>";
print "Next cycle: " . $aac->next_cycle . "\nNext effective date: " . $aac->next_effective;
*/
class airac {
	private $base_date = "1/29/1998"; // AIRAC base date is 1/29/1998 per ICAO Do 8126 para 2.6.2(b)
	private $interval = 28; // AIRAC calendar cycle is 28 days
	public $current_cycle; // In format YYCC
	public $next_cycle;
	public $current_effective; // In unix time format
	public $next_effective;
	
	function __construct() {
		$this->calc_current();
		$this->calc_next();
	}
	
	private function calc_current() {
		$eBaseDate = strtotime($this->base_date);
		$dateDif = time() - $eBaseDate; // Find difference between AIRAC calendar base date and current date
		$daysDif = $dateDif / 60 / 60 / 24; // Convert difference to the number of days
		$cycleDif = floor($daysDif / $this->interval); // Divide difference by 28 to find how many 28-day cycles to add to the base cycle
		$this->current_effective = date('m/d/Y', strtotime($this->base_date . ' + ' . $cycleDif * 28 . ' days'));
		$this->current_cycle = $this->cycle_generator($this->current_effective); 
	}
	
	private function calc_next() {
		$this->next_effective = date('m/d/Y', strtotime($this->current_effective . ' + ' . $this->interval . ' days'));
		$this->next_cycle = $this->cycle_generator($this->next_effective); 
	}
	
	private function cycle_generator($effective_date) {
		$effective_date = strtotime($effective_date);
		$year = date('y', $effective_date);
		$firstOfYear = strtotime("1/1/" . $year);
		$yearDif = ($effective_date - $firstOfYear) / 60 / 60 / 24;
		$incr = floor($yearDif / $this->interval);
		return $year . sprintf('%02d',$incr);
	}
}
?>