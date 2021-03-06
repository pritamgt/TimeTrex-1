<?php
/*********************************************************************************
 * TimeTrex is a Payroll and Time Management program developed by
 * TimeTrex Payroll Services Copyright (C) 2003 - 2010 TimeTrex Payroll Services.
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License version 3 as published by
 * the Free Software Foundation with the addition of the following permission
 * added to Section 15 as permitted in Section 7(a): FOR ANY PART OF THE COVERED
 * WORK IN WHICH THE COPYRIGHT IS OWNED BY TIMETREX, TIMETREX DISCLAIMS THE
 * WARRANTY OF NON INFRINGEMENT OF THIRD PARTY RIGHTS.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE.  See the GNU Affero General Public License for more
 * details.
 *
 * You should have received a copy of the GNU Affero General Public License along
 * with this program; if not, see http://www.gnu.org/licenses or write to the Free
 * Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
 * 02110-1301 USA.
 *
 * You can contact TimeTrex headquarters at Unit 22 - 2475 Dobbin Rd. Suite
 * #292 Westbank, BC V4T 2E9, Canada or at email address info@timetrex.com.
 *
 * The interactive user interfaces in modified source and object code versions
 * of this program must display Appropriate Legal Notices, as required under
 * Section 5 of the GNU Affero General Public License version 3.
 *
 * In accordance with Section 7(b) of the GNU Affero General Public License
 * version 3, these Appropriate Legal Notices must retain the display of the
 * "Powered by TimeTrex" logo. If the display of the logo is not reasonably
 * feasible for technical reasons, the Appropriate Legal Notices must display
 * the words "Powered by TimeTrex".
 ********************************************************************************/
/*
 * $Revision: 3041 $
 * $Id: DateTimeTest.php 3041 2009-11-12 19:34:13Z ipso $
 * $Date: 2009-11-12 11:34:13 -0800 (Thu, 12 Nov 2009) $
 */
require_once('PHPUnit/Framework/TestCase.php');

class DateTimeTest extends PHPUnit_Framework_TestCase {

    public function __construct() {
        global $db, $cache;

        require_once('../includes/global.inc.php');
		require_once( Environment::getBasePath().'/classes/payroll_deduction/PayrollDeduction.class.php');

        $profiler = new Profiler( true );
        Debug::setBufferOutput(FALSE);
        Debug::setEnable(TRUE);

        if ( PRODUCTION != FALSE ) {
            echo "DO NOT RUN ON A PRODUCTION SERVER<br>\n";
            exit;
        }
    }

    public function setUp() {
        Debug::text('Running setUp(): ', __FILE__, __LINE__, __METHOD__,10);
        return TRUE;
    }

    public function tearDown() {
        Debug::text('Running tearDown(): ', __FILE__, __LINE__, __METHOD__,10);
        return TRUE;
    }

	function testDate_DMY_1() {
		Debug::text('Testing Date Format: d-M-y', __FILE__, __LINE__, __METHOD__,10);

		TTDate::setDateFormat('d-M-y');
		TTDate::setTimeZone('PST');
		TTDate::setTimeFormat('g:i A');

		$this->assertEquals( TTDate::parseDateTime('25-Feb-05'), 1109318400 );

		$this->assertEquals( TTDate::parseDateTime('25-Feb-05 8:09PM'), 1109390940 );
		$this->assertEquals( TTDate::parseDateTime('25-Feb-05 8:09 AM'), 1109347740 );
		$this->assertEquals( TTDate::parseDateTime('25-Feb-05 8:09:10 AM'), 1109347750 );
		$this->assertEquals( TTDate::parseDateTime('25-Feb-05 8:09:10 AM EST'), 1109336950 );

		$this->assertEquals( TTDate::parseDateTime('25-Feb-05 18:09'), 1109383740 );
		$this->assertEquals( TTDate::parseDateTime('25-Feb-05 18:09:10'), 1109383750 );
		$this->assertEquals( TTDate::parseDateTime('25-Feb-05 18:09:10 EST'), 1109372950 );


		//Fails on PHP 5.1.2 due to strtotime()
		//TTDate::setDateFormat('d/M/y');
		//TTDate::setTimeZone('PST');
		//TTDate::setTimeFormat('g:i A');

		//$this->assertEquals( TTDate::parseDateTime('25/Feb/05'), 1109318400 );

		//$this->assertEquals( TTDate::parseDateTime('25/Feb/05 8:09PM'), 1109390940 );
		//$this->assertEquals( TTDate::parseDateTime('25/Feb/05 8:09 AM'), 1109347740 );
		//$this->assertEquals( TTDate::parseDateTime('25/Feb/05 8:09:10 AM'), 1109347750 );
		//$this->assertEquals( TTDate::parseDateTime('25/Feb/05 8:09:10 AM EST'), 1109336950 );

		//$this->assertEquals( TTDate::parseDateTime('25/Feb/05 18:09'), 1109383740 );
		//$this->assertEquals( TTDate::parseDateTime('25/Feb/05 18:09:10'), 1109383750 );
		//$this->assertEquals( TTDate::parseDateTime('25/Feb/05 18:09:10 EST'), 1109372950 );


		TTDate::setDateFormat('d-M-Y');
		TTDate::setTimeZone('PST');
		TTDate::setTimeFormat('g:i A');

		$this->assertEquals( TTDate::parseDateTime('25-Feb-2005'), 1109318400 );

		$this->assertEquals( TTDate::parseDateTime('25-Feb-2005 8:09PM'), 1109390940 );
		$this->assertEquals( TTDate::parseDateTime('25-Feb-2005 8:09 AM'), 1109347740 );
		$this->assertEquals( TTDate::parseDateTime('25-Feb-2005 8:09:10 AM'), 1109347750 );
		$this->assertEquals( TTDate::parseDateTime('25-Feb-2005 8:09:10 AM EST'), 1109336950 );

		$this->assertEquals( TTDate::parseDateTime('25-Feb-2005 18:09'), 1109383740 );
		$this->assertEquals( TTDate::parseDateTime('25-Feb-2005 18:09:10'), 1109383750 );
		$this->assertEquals( TTDate::parseDateTime('25-Feb-2005 18:09:10 EST'), 1109372950 );

		//Fails on PHP 5.1.2 due to strtotime()

		//TTDate::setDateFormat('d/M/Y');
		//TTDate::setTimeZone('PST');
		//TTDate::setTimeFormat('g:i A');

		//$this->assertEquals( TTDate::parseDateTime('25/Feb/2005'), 1109318400 );

		//$this->assertEquals( TTDate::parseDateTime('25/Feb/2005 8:09PM'), 1109390940 );
		//$this->assertEquals( TTDate::parseDateTime('25/Feb/2005 8:09 AM'), 1109347740 );
		//$this->assertEquals( TTDate::parseDateTime('25/Feb/2005 8:09:10 AM'), 1109347750 );
		//$this->assertEquals( TTDate::parseDateTime('25/Feb/2005 8:09:10 AM EST'), 1109336950 );

		//$this->assertEquals( TTDate::parseDateTime('25/Feb/2005 18:09'), 1109383740 );
		//$this->assertEquals( TTDate::parseDateTime('25/Feb/2005 18:09:10'), 1109383750 );
		//$this->assertEquals( TTDate::parseDateTime('25/Feb/2005 18:09:10 EST'), 1109372950 );
	}

	function testDate_DMY_2() {
		Debug::text('Testing Date Format: dMY', __FILE__, __LINE__, __METHOD__,10);

		TTDate::setDateFormat('dMY');
		TTDate::setTimeZone('PST');
		TTDate::setTimeFormat('g:i A');

		$this->assertEquals( TTDate::parseDateTime('25Feb2005'), 1109318400 );

		$this->assertEquals( TTDate::parseDateTime('25Feb2005 8:09PM'), 1109390940 );
		$this->assertEquals( TTDate::parseDateTime('25Feb2005 8:09 AM'), 1109347740 );
		$this->assertEquals( TTDate::parseDateTime('25Feb2005 8:09:10 AM'), 1109347750 );
		$this->assertEquals( TTDate::parseDateTime('25Feb2005 8:09:10 AM EST'), 1109336950 );

		$this->assertEquals( TTDate::parseDateTime('25Feb2005 18:09'), 1109383740 );
		$this->assertEquals( TTDate::parseDateTime('25Feb2005 18:09:10'), 1109383750 );
		$this->assertEquals( TTDate::parseDateTime('25Feb2005 18:09:10 EST'), 1109372950 );
	}

	function testDate_DMY_3() {
		Debug::text('Testing Date Format: d-m-y', __FILE__, __LINE__, __METHOD__,10);

		TTDate::setDateFormat('d-m-y');
		TTDate::setTimeZone('PST');

		TTDate::setTimeFormat('g:i A');
		$this->assertEquals( TTDate::parseDateTime('25-02-2005'), 1109318400 );
		$this->assertEquals( TTDate::parseDateTime('25-02-2005 8:09PM'), 1109390940 );
		$this->assertEquals( TTDate::parseDateTime('25-02-2005 8:09 AM'), 1109347740 );

		TTDate::setTimeFormat('g:i A T');
		$this->assertEquals( TTDate::parseDateTime('25-02-2005 8:09 AM EST'), 1109336940 );

		TTDate::setTimeFormat('G:i');
		$this->assertEquals( TTDate::parseDateTime('25-02-2005 18:09'), 1109383740 );
		$this->assertEquals( TTDate::parseDateTime('25-02-2005 18:09 EST'), 1109372940 );

		//
		// Different separator
		//

		TTDate::setDateFormat('d/m/y');
		TTDate::setTimeZone('PST');

		TTDate::setTimeFormat('g:i A');
		$this->assertEquals( TTDate::parseDateTime('25/02/2005'), 1109318400 );
		$this->assertEquals( TTDate::parseDateTime('25/02/2005 8:09PM'), 1109390940 );
		$this->assertEquals( TTDate::parseDateTime('25/02/2005 8:09 AM'), 1109347740 );

		TTDate::setTimeFormat('g:i A T');
		$this->assertEquals( TTDate::parseDateTime('25/02/2005 8:09 AM EST'), 1109336940 );

		TTDate::setTimeFormat('G:i');
		$this->assertEquals( TTDate::parseDateTime('25/02/2005 18:09'), 1109383740 );
		$this->assertEquals( TTDate::parseDateTime('25/02/2005 18:09 EST'), 1109372940 );
	}

	function testDate_MDY_1() {
		Debug::text('Testing Date Format: m-d-y', __FILE__, __LINE__, __METHOD__,10);

		TTDate::setDateFormat('m-d-y');
		TTDate::setTimeZone('PST');

		TTDate::setTimeFormat('g:i A');
		$this->assertEquals( TTDate::parseDateTime('02-25-2005'), 1109318400 );
		$this->assertEquals( TTDate::parseDateTime('02-25-05'), 1109318400 );

		$this->assertEquals( TTDate::parseDateTime('10-27-06'), 1161932400 );

		$this->assertEquals( TTDate::parseDateTime('02-25-2005 8:09PM'), 1109390940 );
		$this->assertEquals( TTDate::parseDateTime('02-25-2005 8:09 AM'), 1109347740 );

		TTDate::setTimeFormat('g:i A T');
		$this->assertEquals( TTDate::parseDateTime('02-25-2005 8:09 AM EST'), 1109336940 );

		TTDate::setTimeFormat('G:i');
		$this->assertEquals( TTDate::parseDateTime('02-25-2005 18:09'), 1109383740 );
		$this->assertEquals( TTDate::parseDateTime('02-25-2005 18:09 EST'), 1109372940 );

		//
		// Different separator
		//
		TTDate::setDateFormat('m/d/y');
		TTDate::setTimeZone('PST');

		TTDate::setTimeFormat('g:i A');
		$this->assertEquals( TTDate::parseDateTime('02/25/2005'), 1109318400 );
		$this->assertEquals( TTDate::parseDateTime('02/25/2005 8:09PM'), 1109390940 );
		$this->assertEquals( TTDate::parseDateTime('02/25/2005 8:09 AM'), 1109347740 );

		TTDate::setTimeFormat('g:i A T');
		$this->assertEquals( TTDate::parseDateTime('02/25/2005 8:09 AM EST'), 1109336940 );

		TTDate::setTimeFormat('G:i');
		$this->assertEquals( TTDate::parseDateTime('02/25/2005 18:09'), 1109383740 );
		$this->assertEquals( TTDate::parseDateTime('02/25/2005 18:09 EST'), 1109372940 );
	}

	function testDate_MDY_2() {
		Debug::text('Testing Date Format: M-d-y', __FILE__, __LINE__, __METHOD__,10);

		TTDate::setDateFormat('M-d-y');
		TTDate::setTimeZone('PST');

		TTDate::setTimeFormat('g:i A');
		$this->assertEquals( TTDate::parseDateTime('Feb-25-2005'), 1109318400 );
		$this->assertEquals( TTDate::parseDateTime('Feb-25-05'), 1109318400 );
		$this->assertEquals( TTDate::parseDateTime('Feb-25-2005 8:09PM'), 1109390940 );
		$this->assertEquals( TTDate::parseDateTime('Feb-25-2005 8:09 AM'), 1109347740 );

		TTDate::setTimeFormat('g:i A T');
		$this->assertEquals( TTDate::parseDateTime('Feb-25-2005 8:09 AM EST'), 1109336940 );

		TTDate::setTimeFormat('G:i');
		$this->assertEquals( TTDate::parseDateTime('Feb-25-2005 18:09'), 1109383740 );
		$this->assertEquals( TTDate::parseDateTime('Feb-25-2005 18:09 EST'), 1109372940 );
	}

	function testDate_MDY_3() {
		Debug::text('Testing Date Format: m-d-y (two digit year)', __FILE__, __LINE__, __METHOD__,10);

		TTDate::setDateFormat('m-d-y');
		TTDate::setTimeZone('PST');

		TTDate::setTimeFormat('g:i A');
		$this->assertEquals( TTDate::parseDateTime('02-25-05'), 1109318400 );
		$this->assertEquals( TTDate::parseDateTime('02-25-05 8:09PM'), 1109390940 );
		$this->assertEquals( TTDate::parseDateTime('02-25-05 8:09 AM'), 1109347740 );

		TTDate::setTimeFormat('g:i A T');
		$this->assertEquals( TTDate::parseDateTime('02-25-05 8:09 AM EST'), 1109336940 );

		TTDate::setTimeFormat('G:i');
		$this->assertEquals( TTDate::parseDateTime('02-25-05 18:09'), 1109383740 );
		$this->assertEquals( TTDate::parseDateTime('02-25-05 18:09 EST'), 1109372940 );

		//Try test before 1970, like 1920
		$this->assertEquals( TTDate::parseDateTime('02-25-20'), -1573142400 );
		$this->assertEquals( TTDate::parseDateTime('02-25-20 8:09PM'), -1573069860);
		$this->assertEquals( TTDate::parseDateTime('02-25-20 8:09 AM'), -1573113060 );

	}


	function testDate_YMD_1() {
		Debug::text('Testing Date Format: Y-m-d', __FILE__, __LINE__, __METHOD__,10);

		TTDate::setDateFormat('Y-m-d');
		TTDate::setTimeZone('PST');

		TTDate::setTimeFormat('g:i A');
		$this->assertEquals( TTDate::parseDateTime('2005-02-25'), 1109318400 );
		$this->assertEquals( TTDate::parseDateTime('05-02-25'), 1109318400 );
		$this->assertEquals( TTDate::parseDateTime('2005-02-25 8:09PM'), 1109390940 );
		$this->assertEquals( TTDate::parseDateTime('2005-02-25 8:09 AM'), 1109347740 );

		TTDate::setTimeFormat('g:i A T');
		$this->assertEquals( TTDate::parseDateTime('2005-02-25 8:09 AM EST'), 1109336940 );

		TTDate::setTimeFormat('G:i');
		$this->assertEquals( TTDate::parseDateTime('2005-02-25 18:09'), 1109383740 );
		$this->assertEquals( TTDate::parseDateTime('2005-02-25 18:09 EST'), 1109372940 );
	}

	function test_getDayOfNextWeek() {
		Debug::text('Testing Date Format: Y-m-d', __FILE__, __LINE__, __METHOD__,10);

		TTDate::setDateFormat('Y-m-d');
		TTDate::setTimeZone('PST');

		TTDate::setTimeFormat('g:i A');
		$this->assertEquals( TTDate::getDateOfNextDayOfWeek( strtotime('29-Dec-06'), strtotime('27-Dec-06') ), strtotime('03-Jan-07') );
		$this->assertEquals( TTDate::getDateOfNextDayOfWeek( strtotime('25-Dec-06'), strtotime('28-Dec-06') ), strtotime('28-Dec-06') );
		$this->assertEquals( TTDate::getDateOfNextDayOfWeek( strtotime('31-Dec-06'), strtotime('25-Dec-06') ), strtotime('01-Jan-07') );

	}

	function test_getDateOfNextDayOfMonth() {
		Debug::text('Testing Date Format: Y-m-d', __FILE__, __LINE__, __METHOD__,10);

		TTDate::setDateFormat('Y-m-d');
		TTDate::setTimeZone('PST');

		TTDate::setTimeFormat('g:i A');
		$this->assertEquals( TTDate::getDateOfNextDayOfMonth( strtotime('01-Dec-06'), strtotime('02-Dec-06') ), strtotime('02-Dec-06') );
		$this->assertEquals( TTDate::getDateOfNextDayOfMonth( strtotime('14-Dec-06'), strtotime('23-Nov-06') ), strtotime('23-Dec-06') );
		$this->assertEquals( TTDate::getDateOfNextDayOfMonth( strtotime('14-Dec-06'), strtotime('13-Dec-06') ), strtotime('13-Jan-07') );
		$this->assertEquals( TTDate::getDateOfNextDayOfMonth( strtotime('14-Dec-06'), strtotime('14-Dec-06') ), strtotime('14-Dec-06') );
		$this->assertEquals( TTDate::getDateOfNextDayOfMonth( strtotime('12-Dec-06'), strtotime('01-Dec-04') ), strtotime('01-Jan-07') );

		$this->assertEquals( TTDate::getDateOfNextDayOfMonth( strtotime('12-Dec-06'), NULL, 1 ), strtotime('01-Jan-07') );
		$this->assertEquals( TTDate::getDateOfNextDayOfMonth( strtotime('12-Dec-06'), NULL, 12 ), strtotime('12-Dec-06') );
		$this->assertEquals( TTDate::getDateOfNextDayOfMonth( strtotime('12-Dec-06'), NULL, 31 ), strtotime('31-Dec-06') );

		$this->assertEquals( TTDate::getDateOfNextDayOfMonth( strtotime('01-Feb-07'), NULL, 31 ), strtotime('28-Feb-07') );
		$this->assertEquals( TTDate::getDateOfNextDayOfMonth( strtotime('01-Feb-08'), NULL, 29 ), strtotime('29-Feb-08') );
		$this->assertEquals( TTDate::getDateOfNextDayOfMonth( strtotime('01-Feb-08'), NULL, 31 ), strtotime('29-Feb-08') );

		//Anchor Epoch: 09-Apr-04 11:59 PM PDT Day Of Month Epoch:  Day Of Month: 24<br>
		$this->assertEquals( TTDate::getDateOfNextDayOfMonth( strtotime('09-Apr-04'), NULL, 24 ), strtotime('24-Apr-04') );
	}

	function test_parseEpoch() {
		Debug::text('Testing Date Parsing of EPOCH!', __FILE__, __LINE__, __METHOD__,10);

		TTDate::setDateFormat('m-d-y');
		TTDate::setTimeZone('PST');

		TTDate::setTimeFormat('g:i A');
		$this->assertEquals( TTDate::parseDateTime(1162670400), (int)1162670400 );


		TTDate::setDateFormat('Y-m-d');
		TTDate::setTimeZone('PST');

		TTDate::setTimeFormat('g:i A');
		$this->assertEquals( TTDate::parseDateTime(1162670400), (int)1162670400 );
	}

	function test_roundTime() {
		//10 = Down
		//20 = Average
		//30 = Up

		//Test rounding down by 15minutes
		$this->assertEquals( (int)TTDate::roundTime( strtotime('15-Apr-07 8:06 AM'), (60*15), 10), strtotime('15-Apr-07 8:00 AM') );
		//Test rounding down by 5minutes
		$this->assertEquals( (int)TTDate::roundTime( strtotime('15-Apr-07 8:06 AM'), (60*5), 10), strtotime('15-Apr-07 8:05 AM') );
		//Test rounding down by 5minutes when no rounding should occur.
		$this->assertEquals( (int)TTDate::roundTime( strtotime('15-Apr-07 8:05 AM'), (60*5), 10), strtotime('15-Apr-07 8:05 AM') );

		//Test rounding down by 15minutes with 3minute grace.
		$this->assertEquals( (int)TTDate::roundTime( strtotime('15-Apr-07 4:58 PM'), (60*15), 10, (60*3) ), strtotime('15-Apr-07 5:00 PM') );
		$this->assertEquals( (int)TTDate::roundTime( strtotime('15-Apr-07 4:56 PM'), (60*15), 10, (60*3) ), strtotime('15-Apr-07 4:45 PM') );
		$this->assertEquals( (int)TTDate::roundTime( strtotime('15-Apr-07 5:11 PM'), (60*15), 10, (60*3) ), strtotime('15-Apr-07 5:00 PM') );
		//Test rounding down by 5minutes with 2minute grace
		$this->assertEquals( (int)TTDate::roundTime( strtotime('15-Apr-07 5:11 PM'), (60*5), 10, (60*2) ), strtotime('15-Apr-07 5:10 PM') );
		$this->assertEquals( (int)TTDate::roundTime( strtotime('15-Apr-07 5:07 PM'), (60*5), 10, (60*2) ), strtotime('15-Apr-07 5:05 PM') );


		//Test rounding avg by 15minutes
		$this->assertEquals( (int)TTDate::roundTime( strtotime('15-Apr-07 8:06 AM'), (60*15), 20), strtotime('15-Apr-07 8:00 AM') );
		$this->assertEquals( (int)TTDate::roundTime( strtotime('15-Apr-07 8:08 AM'), (60*15), 20), strtotime('15-Apr-07 8:15 AM') );
		//Test rounding avg by 5minutes
		$this->assertEquals( (int)TTDate::roundTime( strtotime('15-Apr-07 8:06 AM'), (60*5), 20), strtotime('15-Apr-07 8:05 AM') );
		$this->assertEquals( (int)TTDate::roundTime( strtotime('15-Apr-07 8:08 AM'), (60*5), 20), strtotime('15-Apr-07 8:10 AM') );
		//Test rounding avg by 5minutes when no rounding should occur.
		$this->assertEquals( (int)TTDate::roundTime( strtotime('15-Apr-07 8:05 AM'), (60*5), 20), strtotime('15-Apr-07 8:05 AM') );


		//Test rounding up by 15minutes
		$this->assertEquals( (int)TTDate::roundTime( strtotime('15-Apr-07 8:06 AM'), (60*15), 30), strtotime('15-Apr-07 8:15 AM') );
		//Test rounding up by 5minutes
		$this->assertEquals( (int)TTDate::roundTime( strtotime('15-Apr-07 8:06 AM'), (60*5), 30), strtotime('15-Apr-07 8:10 AM') );
		//Test rounding up by 5minutes when no rounding should occur.
		$this->assertEquals( (int)TTDate::roundTime( strtotime('15-Apr-07 8:05 AM'), (60*5), 30), strtotime('15-Apr-07 8:05 AM') );

		//Test rounding up by 15minutes with 3minute grace.
		$this->assertEquals( (int)TTDate::roundTime( strtotime('15-Apr-07 8:01 AM'), (60*15), 30, (60*3) ), strtotime('15-Apr-07 8:00 AM') );
		$this->assertEquals( (int)TTDate::roundTime( strtotime('15-Apr-07 8:04 AM'), (60*15), 30, (60*3) ), strtotime('15-Apr-07 8:15 AM') );
		$this->assertEquals( (int)TTDate::roundTime( strtotime('15-Apr-07 8:03 AM'), (60*15), 30, (60*3) ), strtotime('15-Apr-07 8:00 AM') );
		//Test rounding up by 5minutes with 2minute grace
		$this->assertEquals( (int)TTDate::roundTime( strtotime('15-Apr-07 8:03 AM'), (60*5), 30, (60*2) ), strtotime('15-Apr-07 8:05 AM') );
		$this->assertEquals( (int)TTDate::roundTime( strtotime('15-Apr-07 8:01 AM'), (60*5), 30, (60*2) ), strtotime('15-Apr-07 8:00 AM') );

	}

	function test_graceTime() {
		$this->assertEquals( (int)TTDate::graceTime( strtotime('15-Apr-07 7:58 AM'), (60*5), strtotime('15-Apr-07 8:00 AM') ), strtotime('15-Apr-07 8:00 AM') );
		$this->assertEquals( (int)TTDate::graceTime( strtotime('15-Apr-07 7:58:23 AM'), (60*5), strtotime('15-Apr-07 8:00 AM') ), strtotime('15-Apr-07 8:00 AM') );
	}

	function test_calculateTimeOnEachDayBetweenRange() {
		$test1_result = TTDate::calculateTimeOnEachDayBetweenRange( strtotime('01-Jan-09 8:00AM'), strtotime('01-Jan-09 11:30PM') );
		$this->assertEquals( count($test1_result), 1 );
		$this->assertEquals( $test1_result[1230796800], 55800 );

		$test2_result = TTDate::calculateTimeOnEachDayBetweenRange( strtotime('01-Jan-09 4:00PM'), strtotime('02-Jan-09 8:00AM') );
		$this->assertEquals( count($test2_result), 2 );
		$this->assertEquals( $test2_result[1230796800], 28800 );
		$this->assertEquals( $test2_result[1230883200], 28800 );

		$test3_result = TTDate::calculateTimeOnEachDayBetweenRange( strtotime('01-Jan-09 4:00PM'), strtotime('03-Jan-09 8:00AM') );
		$this->assertEquals( count($test3_result), 3 );
		$this->assertEquals( $test3_result[1230796800], 28800 );
		$this->assertEquals( $test3_result[1230883200], 86400 );
		$this->assertEquals( $test3_result[1230969600], 28800 );

		$test4_result = TTDate::calculateTimeOnEachDayBetweenRange( strtotime('01-Jan-09 4:00PM'), strtotime('9-Jan-09 8:00AM') );
		$this->assertEquals( count($test4_result), 9 );
		$this->assertEquals( $test4_result[1230796800], 28800 );
		$this->assertEquals( $test4_result[1230883200], 86400 );
		$this->assertEquals( $test4_result[1230969600], 86400 );
		$this->assertEquals( $test4_result[1231056000], 86400 );
		$this->assertEquals( $test4_result[1231142400], 86400 );
		$this->assertEquals( $test4_result[1231228800], 86400 );
		$this->assertEquals( $test4_result[1231315200], 86400 );
		$this->assertEquals( $test4_result[1231401600], 86400 );
		$this->assertEquals( $test4_result[1231488000], 28800 );

		$test5_result = TTDate::calculateTimeOnEachDayBetweenRange( strtotime('01-Jan-09 12:00AM'), strtotime('01-Jan-09 12:59:59PM') );
		$this->assertEquals( count($test5_result), 1 );
		$this->assertEquals( $test5_result[1230796800], 46799 );

		$test5_result = TTDate::calculateTimeOnEachDayBetweenRange( strtotime('01-Jan-09 12:00AM'), strtotime('02-Jan-09 12:00AM') );
		$this->assertEquals( count($test5_result), 1 );
		$this->assertEquals( $test5_result[1230796800], 86400 );

		$test5_result = TTDate::calculateTimeOnEachDayBetweenRange( strtotime('01-Jan-09 12:01AM'), strtotime('02-Jan-09 12:01AM') );
		$this->assertEquals( count($test5_result), 2 );
		$this->assertEquals( $test5_result[1230796800], 86340);
		$this->assertEquals( $test5_result[1230883200], 60);

		$test5_result = TTDate::calculateTimeOnEachDayBetweenRange( strtotime('01-Jan-09 1:53PM'), strtotime('03-Jan-09 6:12AM') );
		$this->assertEquals( count($test5_result), 3 );
		$this->assertEquals( $test5_result[1230796800], 36420);
		$this->assertEquals( $test5_result[1230883200], 86400);
		$this->assertEquals( $test5_result[1230969600], 22320);
	}

	function test_getWeek() {
		//Match up with PHP's function
		$date1 = strtotime('01-Nov-09 12:00PM');
		$this->assertEquals( date('W', $date1 ), 44 );
		$this->assertEquals( TTDate::getWeek( $date1, 1), 44 );

		$date1 = strtotime('02-Nov-09 12:00PM');
		$this->assertEquals( date('W', $date1 ), 45 );
		$this->assertEquals( TTDate::getWeek( $date1, 1), 45 );

		$date1 = strtotime('03-Nov-09 12:00PM');
		$this->assertEquals( date('W', $date1 ), 45 );
		$this->assertEquals( TTDate::getWeek( $date1, 1), 45 );

		$date1 = strtotime('04-Nov-09 12:00PM');
		$this->assertEquals( date('W', $date1 ), 45 );
		$this->assertEquals( TTDate::getWeek( $date1, 1), 45 );

		$date1 = strtotime('05-Nov-09 12:00PM');
		$this->assertEquals( date('W', $date1 ), 45 );
		$this->assertEquals( TTDate::getWeek( $date1, 1), 45 );

		$date1 = strtotime('06-Nov-09 12:00PM');
		$this->assertEquals( date('W', $date1 ), 45 );
		$this->assertEquals( TTDate::getWeek( $date1, 1), 45 );

		$date1 = strtotime('07-Nov-09 12:00PM');
		$this->assertEquals( date('W', $date1 ), 45 );
		$this->assertEquals( TTDate::getWeek( $date1, 1), 45 );

		$date1 = strtotime('08-Nov-09 12:00PM');
		$this->assertEquals( date('W', $date1 ), 45 );
		$this->assertEquals( TTDate::getWeek( $date1, 1), 45 );

		$date1 = strtotime('09-Nov-09 12:00PM');
		$this->assertEquals( date('W', $date1 ), 46 );
		$this->assertEquals( TTDate::getWeek( $date1, 1), 46 );

		//Test with Sunday as start day of week.
		$date1 = strtotime('01-Nov-09 12:00PM');
		$this->assertEquals( TTDate::getWeek( $date1, 0), 45 );

		$date1 = strtotime('02-Nov-09 12:00PM');
		$this->assertEquals( TTDate::getWeek( $date1, 0), 45 );

		$date1 = strtotime('03-Nov-09 12:00PM');
		$this->assertEquals( TTDate::getWeek( $date1, 0), 45 );

		$date1 = strtotime('04-Nov-09 12:00PM');
		$this->assertEquals( TTDate::getWeek( $date1, 0), 45 );

		$date1 = strtotime('05-Nov-09 12:00PM');
		$this->assertEquals( TTDate::getWeek( $date1, 0), 45 );

		$date1 = strtotime('06-Nov-09 12:00PM');
		$this->assertEquals( TTDate::getWeek( $date1, 0), 45 );

		$date1 = strtotime('07-Nov-09 12:00PM');
		$this->assertEquals( TTDate::getWeek( $date1, 0), 45 );

		$date1 = strtotime('08-Nov-09 12:00PM');
		$this->assertEquals( TTDate::getWeek( $date1, 0), 46 );

		$date1 = strtotime('09-Nov-09 12:00PM');
		$this->assertEquals( TTDate::getWeek( $date1, 0), 46 );


		//Test with Tuesday as start day of week.
		$date1 = strtotime('01-Nov-09 12:00PM');
		$this->assertEquals( TTDate::getWeek( $date1, 2), 44 );

		$date1 = strtotime('02-Nov-09 12:00PM');
		$this->assertEquals( TTDate::getWeek( $date1, 2), 44 );

		$date1 = strtotime('03-Nov-09 12:00PM');
		$this->assertEquals( TTDate::getWeek( $date1, 2), 45 );

		$date1 = strtotime('04-Nov-09 12:00PM');
		$this->assertEquals( TTDate::getWeek( $date1, 2), 45 );

		$date1 = strtotime('05-Nov-09 12:00PM');
		$this->assertEquals( TTDate::getWeek( $date1, 2), 45 );

		$date1 = strtotime('06-Nov-09 12:00PM');
		$this->assertEquals( TTDate::getWeek( $date1, 2), 45 );

		$date1 = strtotime('07-Nov-09 12:00PM');
		$this->assertEquals( TTDate::getWeek( $date1, 2), 45 );

		$date1 = strtotime('08-Nov-09 12:00PM');
		$this->assertEquals( TTDate::getWeek( $date1, 2), 45 );

		$date1 = strtotime('09-Nov-09 12:00PM');
		$this->assertEquals( TTDate::getWeek( $date1, 2), 45 );

		$date1 = strtotime('10-Nov-09 12:00PM');
		$this->assertEquals( TTDate::getWeek( $date1, 2), 46 );

		$date1 = strtotime('11-Nov-09 12:00PM');
		$this->assertEquals( TTDate::getWeek( $date1, 2), 46 );


		//Test with Wed as start day of week.
		$date1 = strtotime('03-Nov-09 12:00PM');
		$this->assertEquals( TTDate::getWeek( $date1, 3), 44 );

		$date1 = strtotime('04-Nov-09 12:00PM');
		$this->assertEquals( TTDate::getWeek( $date1, 3), 45 );

		$date1 = strtotime('05-Nov-09 12:00PM');
		$this->assertEquals( TTDate::getWeek( $date1, 3), 45 );

		//Test with Thu as start day of week.
		$date1 = strtotime('04-Nov-09 12:00PM');
		$this->assertEquals( TTDate::getWeek( $date1, 4), 44 );

		$date1 = strtotime('05-Nov-09 12:00PM');
		$this->assertEquals( TTDate::getWeek( $date1, 4), 45 );

		$date1 = strtotime('06-Nov-09 12:00PM');
		$this->assertEquals( TTDate::getWeek( $date1, 4), 45 );

		//Test with Fri as start day of week.
		$date1 = strtotime('05-Nov-09 12:00PM');
		$this->assertEquals( TTDate::getWeek( $date1, 5), 44 );

		$date1 = strtotime('06-Nov-09 12:00PM');
		$this->assertEquals( TTDate::getWeek( $date1, 5), 45 );

		$date1 = strtotime('07-Nov-09 12:00PM');
		$this->assertEquals( TTDate::getWeek( $date1, 5), 45 );

		//Test with Sat as start day of week.
		$date1 = strtotime('06-Nov-09 12:00PM');
		$this->assertEquals( TTDate::getWeek( $date1, 6), 44 );

		$date1 = strtotime('07-Nov-09 12:00PM');
		$this->assertEquals( TTDate::getWeek( $date1, 6), 45 );

		$date1 = strtotime('08-Nov-09 12:00PM');
		$this->assertEquals( TTDate::getWeek( $date1, 6), 45 );

		//Test with different years
		$date1 = strtotime('31-Dec-09 12:00PM');
		$this->assertEquals( TTDate::getWeek( $date1, 1), 53 );

		$date1 = strtotime('01-Jan-10 12:00PM');
		$this->assertEquals( TTDate::getWeek( $date1, 1), 53 );

		$date1 = strtotime('04-Jan-10 12:00PM');
		$this->assertEquals( TTDate::getWeek( $date1, 1), 1 );

		$date1 = strtotime('03-Jan-10 12:00PM');
		$this->assertEquals( TTDate::getWeek( $date1, 0), 1 );

		$date1 = strtotime('09-Jan-10 12:00PM');
		$this->assertEquals( TTDate::getWeek( $date1, 6), 1 );
	}
}
?>