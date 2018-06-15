<?php
/**
 * Provides the base api for BlueSpiceSocialResolve.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 *
 * This file is part of BlueSpice MediaWiki
 * For further information visit http://bluespice.com
 *
 * @author     Patric Wirth <wirth@hallowelt.com>
 * @package    BluespiceSocial
 * @copyright  Copyright (C) 2017 Hallo Welt! GmbH, All rights reserved.
 * @license    http://www.gnu.org/copyleft/gpl.html GNU Public License v2 or later
 * @filesource
 */
namespace BlueSpice\Social\Resolve\Api\Task;

use BlueSpice\Social\Entity;
use BlueSpice\Services;

/**
 * Api base class for simple tasks in BlueSpice
 * @package BlueSpiceSocial
 */
class Resolve extends \BSApiTasksBase {

	/**
	 * Methods that can be called by task param
	 * @var array
	 */
	protected $aTasks = array(
		'resolve',
	);

	protected function getRequiredTaskPermissions() {
		return array(
			'resolve' => [ 'edit' ],
		);
	}

	public function task_resolve( $taskData, $aParams ) {
		$result = $this->makeStandardReturn();
		$this->checkPermissions();

		if( empty( $taskData->id ) ) {
			$taskData->id = 0;
		}
		if( empty( $taskData->resolved ) ) {
			$taskData->resolved = false;
		}
		$services = Services::getInstance();
		$entity = $services->getBSEntityFactory()->newFromID(
			$taskData->id,
			NS_BSSOCIAL
		);
		if( !$entity instanceof Entity || !$entity->exists() ) {
			return $result;
		}

		$resolveItem = $services->getService( 'BSSocialResolveFactory' )
			->newFromEntity( $entity );
		if( !$resolveItem ) {
			return $result;
		}

		$status = $resolveItem->resolve( $this->getUser(), $taskData->resolved );

		if( !$status->isOK() ) {
			$result->message = $status->getHTML();
			return $result;
		}
		$result->success = true;
		$result->payload['resolve'] = \FormatJson::encode( $resolveItem );
		return $result;
	}
}
