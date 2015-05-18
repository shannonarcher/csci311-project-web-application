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
}
