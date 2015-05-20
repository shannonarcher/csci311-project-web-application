<?php 

namespace App\Services;

class AlbrechtFP {

	public static function getComplexityTable() {
		return [
			// low, med, hi
			[7, 10, 15], // ILF
			[5, 7, 10], // EIF
			[3, 4, 6], // EI
			[4, 5, 7], // EO
			[3, 4, 6] // EQ
		];
	}

	public static function getGSCList() {
		return [
			["1. Data Communications", "How many communication facilities are there to aid in the transfer or exchange of information with the application or system?"],
			["2. Distributed Data Processing", "How are distributed data and processing functions handled?"],
			["3. Performance", "Did the user require response time or throughput?"], 
			["4. Heavily Used Configuration", "How heavily used is the current hardware platform where the application will be executed?"],
			["5. Transaction Rate", "How frequently are transactions executed daily, weekly, monthly, etc.?"],
			["6. Online Data Entry", "What percentage of the information is entered On-Line?"],
			["7. End-User Efficiency", "Was the application designed for end-user efficiency?"],
			["8. Online Update", "How many ILF’s are updated by On-Line transaction?"],
			["9. Complex Processing", "Does the application have extensive logical or mathematical processing?"],
			["10. Reusability", "Was the application developed to meet one or many user’s needs?"],
			["11. Installation Ease", "How difficult is conversion and installation?"],
			["12. Operational Ease", "How effective and/or automated are start-up, back up, and recovery procedures?"],
			["13. Multiple Sites", "Was the application specifically designed, developed, and supported to be installed at multiple sites for multiple organizations?"],
			["14. Facilitate Change", "Was the application specifically designed, developed, and supported to facilitate change?"]
		];
	}

	public static function calculateFP($project) {
		$complexity = AlbrechtFP::getComplexityTable();

		$ufp = 0;

		if ($project->function_points) {

			$ufp += $project->function_points->low_ilf * $complexity[0][0];
			$ufp += $project->function_points->med_ilf * $complexity[0][1];
			$ufp += $project->function_points->hi_ilf * $complexity[0][2];

			$ufp += $project->function_points->low_eif * $complexity[1][0];
			$ufp += $project->function_points->med_eif * $complexity[1][1];
			$ufp += $project->function_points->hi_eif * $complexity[1][2];

			$ufp += $project->function_points->low_ei * $complexity[2][0];
			$ufp += $project->function_points->med_ei * $complexity[2][1];
			$ufp += $project->function_points->hi_ei * $complexity[2][2];

			$ufp += $project->function_points->low_eo * $complexity[3][0];
			$ufp += $project->function_points->med_eo * $complexity[3][1];
			$ufp += $project->function_points->hi_eo * $complexity[3][2];

			$ufp += $project->function_points->low_eq * $complexity[4][0];
			$ufp += $project->function_points->med_eq * $complexity[4][1];
			$ufp += $project->function_points->hi_eq * $complexity[4][2];


			$vaf = 0;

			$vaf += $project->function_points->gsc_1;
			$vaf += $project->function_points->gsc_2;
			$vaf += $project->function_points->gsc_3;
			$vaf += $project->function_points->gsc_4;
			$vaf += $project->function_points->gsc_5;
			$vaf += $project->function_points->gsc_6;
			$vaf += $project->function_points->gsc_7;
			$vaf += $project->function_points->gsc_8;
			$vaf += $project->function_points->gsc_9;
			$vaf += $project->function_points->gsc_10;
			$vaf += $project->function_points->gsc_11;
			$vaf += $project->function_points->gsc_12;
			$vaf += $project->function_points->gsc_13;
			$vaf += $project->function_points->gsc_14;

			$vaf = $vaf * 0.01 + 0.65;

			return number_format($ufp * $vaf, 2);
		}
		else {
			return 0;
		}
	}
}
