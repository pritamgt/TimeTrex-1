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
 * $Revision: 3091 $
 * $Id: StationListFactory.class.php 3091 2009-11-18 18:00:31Z ipso $
 * $Date: 2009-11-18 10:00:31 -0800 (Wed, 18 Nov 2009) $
 */

/**
 * @package Core
 */
class StationListFactory extends StationFactory implements IteratorAggregate {

	function getAll($limit = NULL, $page = NULL, $where = NULL, $order = NULL) {
		$query = '
					select 	*
					from	'. $this->getTable() .'
					WHERE deleted = 0';
		$query .= $this->getWhereSQL( $where );
		$query .= $this->getSortSQL( $order );

		if ($limit == NULL) {
			//Run query without limit
			$this->rs = $this->db->SelectLimit($query);
		} else {
			$this->rs = $this->db->PageExecute($query, $limit, $page);
		}

		return $this;
	}

	function getById($id, $where = NULL, $order = NULL) {
		if ( $id == '') {
			return FALSE;
		}

		$ph = array(
					'id' => $id,
					);

		$query = '
					select 	*
					from	'. $this->getTable() .'
					where	id = ?
						AND deleted = 0
					';
		$query .= $this->getWhereSQL( $where );
		$query .= $this->getSortSQL( $order );

		$this->rs = $this->db->Execute($query, $ph);

		return $this;
	}

	function getByCompanyId($id, $limit = NULL, $page = NULL, $where = NULL, $order = NULL) {
		if ( $id == '') {
			return FALSE;
		}

		$additional_order_fields = array('created_date', 'updated_date', 'updated_date_null' );
		if ( $order == NULL ) {
			$order = array( 'a.type_id' => 'asc', 'a.status_id' => 'asc', 'updated_date_null' => 'asc', 'updated_date' => 'desc', 'a.created_date' => 'desc');
			$strict = FALSE;
		} else {
			//Always sort by created/updated date last.
			if ( !isset($order['update_date']) ) {
				$order['updated_date'] = 'desc';
			}
			if ( !isset($order['created_date']) ) {
				$order['created_date'] = 'desc';
			}
			$strict = TRUE;
		}

		$suf = new StationUserFactory();

		$ph = array(
					'id' => $id,
					);

		$query = '
					select 	a.*,
							CASE WHEN ( a.updated_date is NULL) THEN TRUE ELSE FALSE END as updated_date_null
					from	'. $this->getTable() .' as a
					where	a.company_id = ?
						AND a.deleted = 0
					';
		$query .= $this->getWhereSQL( $where );
		//Because of the null updated date, we have to manually sort.
		if ( $order == NULL ) {
			//$order = array( 'type_id' => 'asc', 'status_id' => 'asc', 'updated_date_null' => 'asc', 'updated_date' => 'desc', 'created_date' => 'desc' );
			$query .= 'ORDER BY a.type_id asc, a.status_id asc, updated_date_null asc, updated_date desc, a.created_date desc';
		} else {
			$query .= $this->getSortSQL( $order );
		}

		if ($limit == NULL) {
			$this->rs = $this->db->Execute($query, $ph);
		} else {
			$this->rs = $this->db->PageExecute($query, $limit, $page, $ph);
		}

		return $this;
	}

	function getByIdAndCompanyId($id, $company_id, $order = NULL) {
		if ( $id == '') {
			return FALSE;
		}

		if ( $company_id == '') {
			return FALSE;
		}

		$ph = array(
					'company_id' => $company_id,
					'id' => $id,
					);

		$query = '
					select 	*
					from 	'. $this->getTable() .'
					where	company_id = ?
						AND	id = ?
						AND deleted = 0';
		$query .= $this->getSortSQL( $order );

		$this->rs = $this->db->Execute($query, $ph);

		return $this;
	}

	function getByCompanyIdAndTypeId($company_id, $type_id, $order = NULL) {
		if ( $company_id == '') {
			return FALSE;
		}

		if ( $type_id == '') {
			return FALSE;
		}

		$ph = array(
					'company_id' => $company_id,
					);

		$query = '
					select 	*
					from 	'. $this->getTable() .'
					where	company_id = ?
						AND type_id in ('. $this->getListSQL($type_id, $ph) .')
						AND deleted = 0';
		$query .= $this->getSortSQL( $order );

		$this->rs = $this->db->Execute($query, $ph);

		return $this;
	}


	function getByStationId($station_id, $order = NULL) {
		if ( $station_id == '' OR strtolower($station_id) == 'any' ) {
			return FALSE;
		}

		$this->rs = $this->getCache($station_id);
		if ( $this->rs === FALSE ) {
			$ph = array(
						'station_id' => $station_id,
						);

			$query = '
						select 	*
						from 	'. $this->getTable() .'
						where
							station_id = ?
							AND deleted = 0';
			$query .= $this->getSortSQL( $order );

			$this->rs = $this->db->Execute($query, $ph);

			$this->saveCache($this->rs,$station_id);
		}

		return $this;
	}

	function getByStationIdAndCompanyId($station_id, $company_id, $order = NULL) {
		if ( $station_id == '') {
			return FALSE;
		}

		if ( $company_id == '') {
			return FALSE;
		}

		$ph = array(
					'company_id' => $company_id,
					'station_id' => $station_id,
					);

		$query = '
					select 	*
					from 	'. $this->getTable() .'
					where	company_id = ?
						AND	station_id = ?
						AND deleted = 0';
		$query .= $this->getSortSQL( $order );

		$this->rs = $this->db->Execute($query, $ph);

		return $this;
	}

	function getPendingSynchronizationByCompanyIdAndTypeId($company_id, $type_id, $order = NULL) {
		if ( $company_id == '') {
			return FALSE;
		}

		if ( $type_id == '') {
			return FALSE;
		}

		$ph = array(
					'company_id' => $company_id,
					);

		$query = '
					select 	*
					from 	'. $this->getTable() .'
					where	company_id = ?
						AND status_id = 20
						AND type_id in ('. $this->getListSQL($type_id, $ph) .')
						AND	(
								( last_poll_date is NULL OR last_poll_date < ('. time() .' - poll_frequency) )
								OR
								( last_push_date is NULL OR last_push_date < ('. time() .' - push_frequency) )
								OR
								( last_partial_push_date is NULL OR last_partial_push_date < ('. time() .' - partial_push_frequency) )
							)
						AND deleted = 0';
		$query .= $this->getSortSQL( $order );

		$this->rs = $this->db->Execute($query, $ph);

		return $this;
	}

	function getByUserIdAndStatusAndType($user_id, $status, $type, $order = NULL) {
		if ( $user_id == '') {
			return FALSE;
		}

		if ( $status == '') {
			return FALSE;
		}

		if ( $type == '') {
			return FALSE;
		}

		$status_key = Option::getByValue($status, $this->getOptions('status') );
		if ($status_key !== FALSE) {
			$status = $status_key;
		}

		$type_key = Option::getByValue($type, $this->getOptions('type') );
		if ($type_key !== FALSE) {
			$type = $type_key;
		}

		$ulf = new UserListFactory();
		$ulf->getById( $user_id );
		if ( $ulf->getRecordCount() != 1 ) {
			return FALSE;
		}

		$sugf = new StationUserGroupFactory();
		$sbf = new StationBranchFactory();
		$sdf = new StationDepartmentFactory();
		$siuf = new StationIncludeUserFactory();
		$seuf = new StationExcludeUserFactory();
		$uf = new UserFactory();

		$ph = array(
					'user_id_a' => $user_id,
					'company_id' => $ulf->getCurrent()->getCompany(),
					'status' => $status,
					'type' => $type,
					);

		$query = '
					select 	a.*
					from 	'. $this->getTable() .' as a
						LEFT JOIN '. $uf->getTable() .' as z ON z.id = ?
					where a.company_id = ?
						AND a.status_id = ?
						AND a.type_id = ?
						AND
							(
								(
									(
										a.user_group_selection_type_id = 10
											OR ( a.user_group_selection_type_id = 20 AND z.group_id in ( select b.group_id from '. $sugf->getTable() .' as b WHERE a.id = b.station_id ) )
											OR ( a.user_group_selection_type_id = 30 AND z.group_id not in ( select b.group_id from '. $sugf->getTable() .' as b WHERE a.id = b.station_id ) )
									)
									AND
									(
										a.branch_selection_type_id = 10
											OR ( a.branch_selection_type_id = 20 AND z.default_branch_id in ( select c.branch_id from '. $sbf->getTable() .' as c WHERE a.id = c.station_id ) )
											OR ( a.branch_selection_type_id = 30 AND z.default_branch_id not in ( select c.branch_id from '. $sbf->getTable() .' as c WHERE a.id = c.station_id ) )
									)
									AND
									(
										a.department_selection_type_id = 10
											OR ( a.department_selection_type_id = 20 AND z.default_department_id in ( select d.department_id from '. $sdf->getTable() .' as d WHERE a.id = d.station_id ) )
											OR ( a.department_selection_type_id = 30 AND z.default_department_id not in ( select d.department_id from '. $sdf->getTable() .' as d WHERE a.id = d.station_id ) )
									)
									AND z.id not in ( select f.user_id from '. $seuf->getTable() .' as f WHERE a.id = f.station_id )
								)
								OR z.id in ( select e.user_id from '. $siuf->getTable() .' as e WHERE a.id = e.station_id )
							)
						AND ( a.deleted = 0 AND z.deleted = 0 )
						ORDER BY lower(a.source) = \'any\' desc, lower(station_id) = \'any\' desc
						';
		//Try to order the SQL query to hit wildcard stations first.

		//$query .= $this->getSortSQL( $order, $strict );

		//Debug::text('Query: '. $query, __FILE__, __LINE__, __METHOD__, 10);
		//Debug::arr($ph, 'PH: ', __FILE__, __LINE__, __METHOD__, 10);

		$this->rs = $this->db->Execute($query, $ph);

		return $this;
	}

	function getByCompanyIdArray($company_id) {
		if ( $company_id == '') {
			return FALSE;
		}

		$blf = new BranchListFactory();
		$blf->getByCompanyId($company_id);

		$branch_list[0] = '--';

		foreach ($blf as $branch) {
			$branch_list[$branch->getID()] = $branch->getName();
		}

		return $branch_list;
	}

	function getAPISearchByCompanyIdAndArrayCriteria( $company_id, $filter_data, $limit = NULL, $page = NULL, $where = NULL, $order = NULL ) {
		if ( $company_id == '') {
			return FALSE;
		}

		if ( !is_array($order) ) {
			//Use Filter Data ordering if its set.
			if ( isset($filter_data['sort_column']) AND $filter_data['sort_order']) {
				$order = array(Misc::trimSortPrefix($filter_data['sort_column']) => $filter_data['sort_order']);
			}
		}

		$additional_order_fields = array();
		if ( $order == NULL ) {
			$order = array( 'status_id' => 'asc', 'type_id' => 'asc', 'source' => 'asc');
			$strict = FALSE;
		} else {
			//Always try to order by status first so INACTIVE employees go to the bottom.
			if ( !isset($order['status_id']) ) {
				$order = Misc::prependArray( array('status_id' => 'asc'), $order );
			}
			$strict = TRUE;
		}
		//Debug::Arr($order,'Order Data:', __FILE__, __LINE__, __METHOD__,10);
		//Debug::Arr($filter_data,'Filter Data:', __FILE__, __LINE__, __METHOD__,10);

		$uf = new UserFactory();

		$ph = array(
					'company_id' => $company_id,
					);

		$query = '
					select 	a.*,
							y.first_name as created_by_first_name,
							y.middle_name as created_by_middle_name,
							y.last_name as created_by_last_name,
							z.first_name as updated_by_first_name,
							z.middle_name as updated_by_middle_name,
							z.last_name as updated_by_last_name
					from 	'. $this->getTable() .' as a
						LEFT JOIN '. $uf->getTable() .' as y ON ( a.created_by = y.id AND y.deleted = 0 )
						LEFT JOIN '. $uf->getTable() .' as z ON ( a.updated_by = z.id AND z.deleted = 0 )
					where	a.company_id = ?
					';

		if ( isset($filter_data['id']) AND isset($filter_data['id'][0]) AND !in_array(-1, (array)$filter_data['id']) ) {
			$query  .=	' AND a.id in ('. $this->getListSQL($filter_data['id'], $ph) .') ';
		}
		if ( isset($filter_data['exclude_id']) AND isset($filter_data['exclude_id'][0]) AND !in_array(-1, (array)$filter_data['exclude_id']) ) {
			$query  .=	' AND a.id not in ('. $this->getListSQL($filter_data['exclude_id'], $ph) .') ';
		}
		if ( isset($filter_data['status_id']) AND isset($filter_data['status_id'][0]) AND !in_array(-1, (array)$filter_data['status_id']) ) {
			$query  .=	' AND a.status_id in ('. $this->getListSQL($filter_data['status_id'], $ph) .') ';
		}
		if ( isset($filter_data['type_id']) AND isset($filter_data['type_id'][0]) AND !in_array(-1, (array)$filter_data['type_id']) ) {
			$query  .=	' AND a.type_id in ('. $this->getListSQL($filter_data['type_id'], $ph) .') ';
		}
		if ( isset($filter_data['source']) AND trim($filter_data['source']) != '' ) {
			$ph[] = strtolower(trim($filter_data['source']));
			$query  .=	' AND lower(a.source) LIKE ?';
		}
		if ( isset($filter_data['description']) AND trim($filter_data['description']) != '' ) {
			$ph[] = strtolower(trim($filter_data['description']));
			$query  .=	' AND lower(a.description) LIKE ?';
		}
		if ( isset($filter_data['created_by']) AND isset($filter_data['created_by'][0]) AND !in_array(-1, (array)$filter_data['created_by']) ) {
			$query  .=	' AND a.created_by in ('. $this->getListSQL($filter_data['created_by'], $ph) .') ';
		}
		if ( isset($filter_data['updated_by']) AND isset($filter_data['updated_by'][0]) AND !in_array(-1, (array)$filter_data['updated_by']) ) {
			$query  .=	' AND a.updated_by in ('. $this->getListSQL($filter_data['updated_by'], $ph) .') ';
		}

		$query .= 	'
						AND a.deleted = 0
					';
		$query .= $this->getWhereSQL( $where );
		$query .= $this->getSortSQL( $order, $strict, $additional_order_fields );

		if ($limit == NULL) {
			$this->rs = $this->db->Execute($query, $ph);
		} else {
			$this->rs = $this->db->PageExecute($query, $limit, $page, $ph);
		}

		return $this;
	}

}
?>
