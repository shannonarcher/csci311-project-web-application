<?php 

namespace App\Services;

class COCOMO {

	public static function getRatings() {
		return [
			"Extra Low",
			"Very Low",
			"Low",
			"Nominal",
			"High",
			"Very High",
			"Extra High"
		];
	}

	public static function getScaleFactors() {
		return [
			["PREC", 6.2, 4.96, 3.72, 2.48, 1.24, 0.00, "Precedentedness", "Degree to which there are past examples that can be consulted."],
			["FLEX", 5.07, 4.05, 3.04, 2.03, 1.01, 0.00, "Development flexibility", "Degree of flexibility that exists when implementing the project"],
			["RESL", 7.07, 5.65, 4.24, 2.83, 1.41, 0.00, "Architecture/risk resolution", "Degree of uncertainty about requirements"],
			["TEAM", 5.48, 4.38, 3.29, 2.19, 1.10, 0.00, "Team Cohesion", "Degree to which there is a large dispersed team (e.g. in different locations) as opposed to there being a small tightly knit team"],
			["PMAT", 7.80, 6.24, 4.68, 3.12, 1.56, 0.00, "Process Maturity", "Degree to how structured and organized the way the software is produced."]
		];
	}

	public static function getEffortMultipliers() {
		return [
			["RCPX", 0.49, 0.60, 0.83, 1.00, 1.33, 1.91, 2.72, "Product reliability and complexity"], 
			["RUSE", false, false, 0.95, 1.00, 1.07, 1.15, 1.24, "Reuse required"],
			["PDIF", false, false, 0.87, 1.00, 1.29, 1.81, 2.61, "Platform difficulty"],
			["PERS", 2.12, 1.62, 1.26, 1.00, 0.83, 0.63, 0.50, "Personnel capability"], 
			["PREX", 1.59, 1.33, 1.12, 1.00, 0.87, 0.74, 0.62, "Personal experience"],
			["FCIL", 1.43, 1.30, 1.10, 1.00, 0.87, 0.73, 0.62, "Facilities available"],
			["SCED", false, 1.43, 1.14, 1.00, 1.00, 1.00, false, "Schedule pressure"]
		];
	}

	public static function calculateCOCOMO1($project) {
		if (isset($project->cocomoi)) {
			return number_format($project->cocomoi->c * pow($project->kloc, $project->cocomoi->k), 2);
		}
		return 0;
	}

	public static function calculateCOCOMO2($project) {
		if (isset($project->cocomoii)) {
			$sf = 0;
			$sum_sf = 0;

			$sum_sf = $project->cocomoii->PREC;
			$sum_sf += $project->cocomoii->FLEX;
			$sum_sf += $project->cocomoii->RESL;
			$sum_sf += $project->cocomoii->TEAM;
			$sum_sf += $project->cocomoii->PMAT;

			$sf = 0.91 + 0.01 * $sum_sf;

			$em = 1;

			$em *= $project->cocomoii->RCPX;
			$em *= $project->cocomoii->RUSE;
			$em *= $project->cocomoii->PDIF;
			$em *= $project->cocomoii->PERS;
			$em *= $project->cocomoii->PREX;
			$em *= $project->cocomoii->FCIL;
			$em *= $project->cocomoii->SCED;

			return number_format(pow(2.94 * $project->kloc, $sf) * $em, 2);
		}
		return 0;
	}
}
